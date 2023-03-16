<?php

namespace ttact\Controllers;

use Dompdf\Dompdf;

class KundenController extends Controller
{
    public function index()
    {
        if (isset($this->params[0])) {
            $this->smarty_vars['error'] = "Der Kunde konnte nicht gefunden werden.";
        }

        $kunden = \ttact\Models\KundeModel::findAll($this->db);
        $kundenliste = [];
        foreach ($kunden as $k) {
            $kundenliste[$k->getKundennummer()] = [
                'kundennummer'      => $k->getKundennummer(),
                'name'              => $k->getName(),
                'strasse'           => $k->getStrasse(),
                'postleitzahl'      => $k->getPostleitzahl(),
                'ort'               => $k->getOrt(),
                'ansprechpartner'   => $k->getAnsprechpartner(),
                'telefon1'          => $k->getTelefon1(),
                'telefon2'          => $k->getTelefon2(),
                'fax'               => $k->getFax(),
                'emailadresse'      => $k->getEmailadresse()
            ];
        }
        ksort($kundenliste);
        $this->smarty_vars['kundenliste'] = $kundenliste;

        // template settings
        $this->template = 'main';
    }

    public function erstellen()
    {
        $error = "";
        $success = "";
        if (isset($this->params[0])) {
            if ($this->params[0] == "erfolgreich") {
                $success = "Der Kunde wurde erfolgreich angelegt.";
            }
        }

        // array with all input data
        $i = [
            'kundennummer'                          => '',
            'name'                                  => '',
            'strasse'                               => '',
            'postleitzahl'                          => '',
            'ort'                                   => '',
            'ansprechpartner'                       => '',
            'telefon1'                              => '',
            'telefon2'                              => '',
            'fax'                                   => '',
            'emailadresse'                          => '',
            'rechnungsanschrift'                    => '',
            'rechnungszusatz'                       => ''
        ];
        if ($this->company != 'tps') {
            $i['unterzeichnungsdatum_rahmenvertrag'] = '';
        }
        foreach ($i as $key => $value) {
            $i[$key] = $this->user_input->getPostParameter($key);
        }
        $i['telefon1'] = $this->user_input->getOnlyNumbers($i['telefon1']);
        $i['telefon2'] = $this->user_input->getOnlyNumbers($i['telefon2']);
        $i['fax'] = $this->user_input->getOnlyNumbers($i['fax']);

        // check and save
        if (($i['kundennummer'] != "") && ($i['name'] != "")) {
            if ($this->user_input->isPositiveInteger($i['kundennummer'])) {
                if (($i['postleitzahl'] != "") && !$this->user_input->isPostleitzahl($i['postleitzahl'])) {
                    // Postleitzahl is not valid
                    $error = "Die Postleitzahl ist ungültig.";
                } elseif (($i['emailadresse'] != "") && !$this->user_input->isEmailadresse($i['emailadresse'])) {
                    // E-Mail-Adresse is not valid
                    $error = "Die E-Mail-Adresse ist ungültig.";
                } else {
                    $unterzeichnungsdatum_rahmenvertrag_error = false;

                    if ($this->company != 'tps') {
                        if ($i['unterzeichnungsdatum_rahmenvertrag'] != "") {
                            if ($this->user_input->isDate($i['unterzeichnungsdatum_rahmenvertrag'])) {
                                $unterzeichnungsdatum_rahmenvertrag = \DateTime::createFromFormat('d.m.Y', $i['unterzeichnungsdatum_rahmenvertrag']);
                                if ($unterzeichnungsdatum_rahmenvertrag instanceof \DateTime) {
                                    if ($unterzeichnungsdatum_rahmenvertrag->format('d.m.Y') == $i['unterzeichnungsdatum_rahmenvertrag']) {
                                        $i['unterzeichnungsdatum_rahmenvertrag'] = $unterzeichnungsdatum_rahmenvertrag->format('Y-m-d');
                                    } else {
                                        $unterzeichnungsdatum_rahmenvertrag_error = true;
                                        $error = "Das Unterzeichnungsdatum des Rahmenvertrags ist ungültig. ";
                                    }
                                } else {
                                    $unterzeichnungsdatum_rahmenvertrag_error = true;
                                    $error = "Das Unterzeichnungsdatum des Rahmenvertrags ist ungültig. ";
                                }
                            } else {
                                $unterzeichnungsdatum_rahmenvertrag_error = true;
                                $error = "Das Unterzeichnungsdatum des Rahmenvertrags ist ungültig. ";
                            }
                        }
                    }

                    if (!$unterzeichnungsdatum_rahmenvertrag_error) {
                        // check if Kunde with that Kundennummer already exists
                        $test = \ttact\Models\KundeModel::findByKundennummer($this->db, $this->user_input->getOnlyNumbers($i['kundennummer']));
                        if ($test instanceof \ttact\Models\KundeModel) {
                            $error = "Die Kundennummer ist bereits vergeben.";
                        } else {
                            // save the data and check if it worked
                            $new = \ttact\Models\KundeModel::createNew($this->db, $i);
                            if ($new instanceof \ttact\Models\KundeModel) {
                                $this->misc_utils->redirect('kunden', 'erstellen', 'erfolgreich');
                            } else {
                                $error = "Beim Anlegen des Kunden ist ein Fehler aufgetreten.";
                            }

                        }
                    }
                }
            } else {
                $error = "Die Kundennummer ist ungültig.";
            }
        } else {
            foreach ($i as $value) {
                if ($value != "") {
                    $error = "Die Kundennummer und der Name dürfen nicht leer sein.";
                }
            }
        }

        if ($error != "") {
            // display error message
            $this->smarty_vars['error'] = $error;

            // display all user input values
            $this->smarty_vars['values'] = $i;
        } else {
            // display success message
            if ($success != "") {
                $this->smarty_vars['success'] = $success;
            }

            // fill in the next free Kundennummer-value if the form was not yet submitted
            $default_kundennummer = '';
            $last_kunde = \ttact\Models\KundeModel::findLastByKundennummer($this->db);
            if ($last_kunde instanceof \ttact\Models\KundeModel) {
                $default_kundennummer = $last_kunde->getKundennummer() + 1;
            }
            $this->smarty_vars['values']['kundennummer'] = $default_kundennummer;
        }

        // template settings
        $this->template = 'main';
    }

    public function bearbeiten()
    {
        $redirect = true;

        if (isset($this->params[0])) {
            if ($this->user_input->isPositiveInteger($this->params[0])) {
                $kunde = \ttact\Models\KundeModel::findByKundennummer($this->db, $this->user_input->getOnlyNumbers($this->params[0]));
                if ($kunde instanceof \ttact\Models\KundeModel) {
                    $redirect = false;

                    $error = "";
                    $success = "";
                    if (isset($this->params[1]) && isset($this->params[2])) {
                        if ($this->params[1] == 'erfolgreich' && $this->params[2] == 'erstellt') {
                            $success = 'Die Kundenkonfiguration wurde erfolgreich angelegt.';
                        } elseif ($this->params[1] == 'erfolgreich' && $this->params[2] == 'bearbeitet') {
                            $success = 'Die Kundenkonfiguration wurde erfolgreich bearbeitet.';
                        }
                    }

                    // array with all input data
                    $i = [
                        'kundennummer'                          => '',
                        'name'                                  => '',
                        'strasse'                               => '',
                        'postleitzahl'                          => '',
                        'ort'                                   => '',
                        'ansprechpartner'                       => '',
                        'telefon1'                              => '',
                        'telefon2'                              => '',
                        'fax'                                   => '',
                        'emailadresse'                          => '',
                        'rechnungsanschrift'                    => '',
                        'rechnungszusatz'                       => '',
                        'submitted'                             => ''
                    ];
                    if ($this->company != 'tps') {
                        $i['unterzeichnungsdatum_rahmenvertrag'] = '';
                    }
                    foreach ($i as $key => $value) {
                        $i[$key] = $this->user_input->getPostParameter($key);
                    }
                    $i['telefon1'] = $this->user_input->getOnlyNumbers($i['telefon1']);
                    $i['telefon2'] = $this->user_input->getOnlyNumbers($i['telefon2']);
                    $i['fax'] = $this->user_input->getOnlyNumbers($i['fax']);

                    // check and save
                    if ($i['submitted'] == 'true') {
                        if ($i['kundennummer'] != "" && $i['kundennummer'] != $kunde->getKundennummer()) {
                            if ($this->user_input->isPositiveInteger($i['kundennummer'])) {
                                // check if Kunde with that Kundennummer already exists
                                $test = \ttact\Models\KundeModel::findByKundennummer($this->db, $this->user_input->getOnlyNumbers($i['kundennummer']));
                                if ($test instanceof \ttact\Models\KundeModel) {
                                    $error .= "Die Kundennummer ist bereits vergeben. ";
                                } else {
                                    if ($kunde->setKundennummer($this->user_input->getOnlyNumbers($i['kundennummer']))) {
                                        $success .= "Die Kundennummer wurde erfolgreich geändert. ";
                                    } else {
                                        $error .= "Beim Speichern der Kundennummer ist ein technischer Fehler aufgetreten. ";
                                    }
                                }
                            } else {
                                $error .= "Die Kundennummer ist ungültig.";
                            }
                        }

                        if ($i['name'] != "" && $i['name'] != $kunde->getName()) {
                            if ($kunde->setName($i['name'])) {
                                $success .= "Der Name wurde erfolgreich geändert. ";
                            } else {
                                $error .= "Beim Speichern des Namens ist ein technischer Fehler aufgetreten. ";
                            }
                        }

                        if ($i['strasse'] != "" && $i['strasse'] != $kunde->getStrasse()) {
                            if ($kunde->setStrasse($i['strasse'])) {
                                $success .= "Die Straße wurde erfolgreich geändert. ";
                            } else {
                                $error .= "Beim Speichern der Straße ist ein technischer Fehler aufgetreten. ";
                            }
                        }

                        if ($i['postleitzahl'] != "" && $i['postleitzahl'] != $kunde->getPostleitzahl()) {
                            if (!$this->user_input->isPostleitzahl($i['postleitzahl'])) {
                                // data of select field 'geschlecht' is not valid
                                $error .= "Die Postleitzahl ist ungültig. ";
                            } else {
                                if ($kunde->setPostleitzahl($i['postleitzahl'])) {
                                    $success .= "Die Postleitzahl wurde erfolgreich geändert. ";
                                } else {
                                    $error .= "Beim Speichern der Postleitzahl ist ein technischer Fehler aufgetreten. ";
                                }
                            }
                        }

                        if ($i['ort'] != "" && $i['ort'] != $kunde->getOrt()) {
                            if ($kunde->setOrt($i['ort'])) {
                                $success .= "Der Ort wurde erfolgreich geändert. ";
                            } else {
                                $error .= "Beim Speichern des Orts ist ein technischer Fehler aufgetreten. ";
                            }
                        }

                        if ($i['ansprechpartner'] != "" && $i['ansprechpartner'] != $kunde->getAnsprechpartner()) {
                            if ($kunde->setAnsprechpartner($i['ansprechpartner'])) {
                                $success .= "Der Ansprechpartner wurde erfolgreich geändert. ";
                            } else {
                                $error .= "Beim Speichern des Ansprechpartners ist ein technischer Fehler aufgetreten. ";
                            }
                        }

                        if ($i['telefon1'] != "" && $i['telefon1'] != $kunde->getTelefon1()) {
                            if ($kunde->setTelefon1($i['telefon1'])) {
                                $success .= "Die Telefonnummer 1 wurde erfolgreich geändert. ";
                            } else {
                                $error .= "Beim Speichern der Telefonnummer 1 ist ein technischer Fehler aufgetreten. ";
                            }
                        }

                        if ($i['telefon2'] != "" && $i['telefon2'] != $kunde->getTelefon2()) {
                            if ($kunde->setTelefon2($i['telefon2'])) {
                                $success .= "Die Telefonnummer 2 wurde erfolgreich geändert. ";
                            } else {
                                $error .= "Beim Speichern der Telefonnummer 2 ist ein technischer Fehler aufgetreten. ";
                            }
                        }

                        if ($i['fax'] != "" && $i['fax'] != $kunde->getFax()) {
                            if ($kunde->setFax($i['fax'])) {
                                $success .= "Die Faxnummer wurde erfolgreich geändert. ";
                            } else {
                                $error .= "Beim Speichern der Faxnummer ist ein technischer Fehler aufgetreten. ";
                            }
                        }

                        if ($i['emailadresse'] != "" && $i['emailadresse'] != $kunde->getEmailadresse()) {
                            if ($this->user_input->isEmailadresse($i['emailadresse'])) {
                                if ($kunde->setEmailadresse($i['emailadresse'])) {
                                    $success .= "Die E-Mail-Adresse wurde erfolgreich geändert. ";
                                } else {
                                    $error .= "Beim Speichern der E-Mail-Adresse ist ein technischer Fehler aufgetreten. ";
                                }
                            } else {
                                // E-Mail-Adresse is not valid
                                $error = "Die E-Mail-Adresse ist ungültig.";
                            }
                        }

                        if ($i['rechnungsanschrift'] != "" && $i['rechnungsanschrift'] != $kunde->getRechnungsanschrift()) {
                            if ($kunde->setRechnungsanschrift($i['rechnungsanschrift'])) {
                                $success .= "Die Rechnungsanschrift wurde erfolgreich geändert. ";
                            } else {
                                $error .= "Beim Speichern der Rechnungsanschrift ist ein technischer Fehler aufgetreten. ";
                            }
                        }

                        if ($i['rechnungszusatz'] != "" && $i['rechnungszusatz'] != $kunde->getRechnungszusatz()) {
                            if ($kunde->setRechnungszusatz($i['rechnungszusatz'])) {
                                $success .= "Der Rechnungszusatz wurde erfolgreich geändert. ";
                            } else {
                                $error .= "Beim Speichern des Rechnungszusatzes ist ein technischer Fehler aufgetreten. ";
                            }
                        }

                        if ($this->company != 'tps') {
                            $unterzeichnungsdatum_rahmenvertrag = '';
                            if ($kunde->getUnterzeichnungsdatumRahmenvertrag() instanceof \DateTime) {
                                $unterzeichnungsdatum_rahmenvertrag = $kunde->getUnterzeichnungsdatumRahmenvertrag()->format('d.m.Y');
                            }
                            if ($i['unterzeichnungsdatum_rahmenvertrag'] != $unterzeichnungsdatum_rahmenvertrag) {
                                if ($i['unterzeichnungsdatum_rahmenvertrag'] == '') {
                                    if ($kunde->setUnterzeichnungsdatumRahmenvertrag('0000-00-00')) {
                                        $success .= "Das Unterzeichnungsdatum des Rahmenvertrags wurde erfolgreich geändert. ";
                                    } else {
                                        $error .= "Beim Speichern des Unterzeichnungsdatums des Rahmenvertrags ist ein technischer Fehler aufgetreten. ";
                                    }
                                } else {
                                    if ($this->user_input->isDate($i['unterzeichnungsdatum_rahmenvertrag'])) {
                                        $unterzeichnungsdatum_rahmenvertrag_neu = \DateTime::createFromFormat('d.m.Y', $i['unterzeichnungsdatum_rahmenvertrag']);
                                        if ($unterzeichnungsdatum_rahmenvertrag_neu instanceof \DateTime) {
                                            if ($unterzeichnungsdatum_rahmenvertrag_neu->format('d.m.Y') == $i['unterzeichnungsdatum_rahmenvertrag']) {
                                                if ($kunde->setUnterzeichnungsdatumRahmenvertrag($unterzeichnungsdatum_rahmenvertrag_neu->format('Y-m-d'))) {
                                                    $success .= "Das Unterzeichnungsdatum des Rahmenvertrags wurde erfolgreich geändert. ";
                                                } else {
                                                    $error .= "Beim Speichern des Unterzeichnungsdatums des Rahmenvertrags ist ein technischer Fehler aufgetreten. ";
                                                }
                                            } else {
                                                $error .= "Das Unterzeichnungsdatum des Rahmenvertrags ist ungültig. ";
                                            }
                                        } else {
                                            $error .= "Das Unterzeichnungsdatum des Rahmenvertrags ist ungültig. ";
                                        }
                                    } else {
                                        $error .= "Das Unterzeichnungsdatum des Rahmenvertrags ist ungültig. ";
                                    }
                                }
                            }
                        }
                    }

                    // display error message
                    if ($error == "" && $success != "") {
                        $success = "Die Änderungen wurden erfolgreich vorgenommen.";
                    }
                    if ($error != "") {
                        $this->smarty_vars['error'] = $error;
                    }
                    if ($success != "") {
                        $this->smarty_vars['success'] = $success;
                    }

                    // fill values into the form
                    $values = [
                        'id'                                    => $kunde->getID(),
                        'kundennummer'                          => $kunde->getKundennummer(),
                        'name'                                  => $kunde->getName(),
                        'strasse'                               => $kunde->getStrasse(),
                        'postleitzahl'                          => $kunde->getPostleitzahl(),
                        'ort'                                   => $kunde->getOrt(),
                        'ansprechpartner'                       => $kunde->getAnsprechpartner(),
                        'telefon1'                              => $kunde->getTelefon1(),
                        'telefon2'                              => $kunde->getTelefon2(),
                        'fax'                                   => $kunde->getFax(),
                        'emailadresse'                          => $kunde->getEmailadresse(),
                        'rechnungsanschrift'                    => $kunde->getRechnungsanschrift(),
                        'rechnungszusatz'                       => $kunde->getRechnungszusatz()
                    ];
                    if ($this->company != 'tps') {
                        $values['unterzeichnungsdatum_rahmenvertrag'] = ($kunde->getUnterzeichnungsdatumRahmenvertrag() instanceof \DateTime ? $kunde->getUnterzeichnungsdatumRahmenvertrag()->format('d.m.Y') : '');
                    }
                    $this->smarty_vars['values'] = $values;

                    // $smarty_vars.kundenkonditionsliste
                    $kundenkonditionsliste = [];
                    $konditionen = \ttact\Models\KundenkonditionModel::findAllByKunde($this->db, $kunde->getID());
                    if ($konditionen > 0) {
                        foreach ($konditionen as $kondition) {
                            $kundenkonditionsliste[$kondition->getID()] = [
                                'id' => $kondition->getID(),
                                'gueltig_ab' => $kondition->getGueltigAb() instanceof \DateTime ? $kondition->getGueltigAb()->format("d.m.Y") : '00.00.0000',
                                'gueltig_ab_ordering' => $kondition->getGueltigAb() instanceof \DateTime ? $kondition->getGueltigAb()->format("Ymd") : '00000000',
                                'gueltig_bis' => $kondition->getGueltigBis() instanceof \DateTime ? $kondition->getGueltigBis()->format("d.m.Y") : '',
                                'gueltig_bis_ordering' => $kondition->getGueltigBis() instanceof \DateTime ? $kondition->getGueltigBis()->format("Ymd") : '99999999',
                                'abteilung' => $kondition->getAbteilung() instanceof \ttact\Models\AbteilungModel ? $kondition->getAbteilung()->getBezeichnung() : '',
                                'preis' => $kondition->getPreis(),
                                'sonntagszuschlag' => $kondition->getSonntagszuschlag(),
                                'feiertagszuschlag' => $kondition->getFeiertagszuschlag(),
                                'nachtzuschlag' => $kondition->getNachtzuschlag(),
                                'nacht_von' => $kondition->getNachtVon() instanceof \DateTime ? $kondition->getNachtVon()->format("H:i") : '',
                                'nacht_bis' => $kondition->getNachtBis() instanceof \DateTime ? $kondition->getNachtBis()->format("H:i") : ''
                            ];

                            if ($this->company == 'tps') {
                                $kundenkonditionsliste[$kondition->getID()]['zeit_pro_palette'] = '';

                                if ($kondition->getAbteilung() instanceof \ttact\Models\AbteilungModel) {
                                    if ($kondition->getAbteilung()->getPalettenAbteilung()) {
                                        $kundenkonditionsliste[$kondition->getID()]['zeit_pro_palette'] = $kondition->getZeitProPalette() instanceof \DateTime ? $kondition->getZeitProPalette()->format("H:i") . ' h' : '';
                                    }
                                }
                            }
                        }
                        $this->smarty_vars['kundenkonditionsliste'] = $kundenkonditionsliste;
                    }

                    // template settings
                    $this->template = 'main';
                }
            }
        }

        if ($redirect) {
            $this->misc_utils->redirect('kunden', 'index', 'fehler');
        }
    }

    public function mitarbeiterlisteAPS()
    {
        $error = "";
        $success = "";

        $i = [
            'von'  => '',
            'bis' => '',
            'kunde' => '',
            'anlage2' => '',
            'exportieren' => ''
        ];
        foreach ($i as $key => $value) {
            $i[$key] = $this->user_input->getPostParameter($key);
        }

        $filename = 'mitarbeiterliste';

        if ($i['exportieren'] != '') {
            $this->misc_utils->sendCSVHeader($this->user_input->getPostParameter('filename') . ".csv");
            $array = $this->user_input->getArrayPostParameter("export");

            if ($i['von'] != '' || $i['bis'] != '' || $i['kunde'] != '') {
                if ($this->user_input->isDate($i['von'])) {
                    $von = \DateTime::createFromFormat('d.m.Y', $i['von']);
                    if ($von instanceof \DateTime) {
                        if ($this->user_input->isDate($i['bis'])) {
                            $bis = \DateTime::createFromFormat('d.m.Y', $i['bis']);
                            if ($bis instanceof \DateTime) {
                                echo utf8_decode('Anlage 2;;;;;;;;;') . PHP_EOL;

                                $kunde = \ttact\Models\KundeModel::findByID($this->db, $i['kunde']);
                                if ($kunde instanceof \ttact\Models\KundeModel) {
                                    $datum = $kunde->getUnterzeichnungsdatumRahmenvertrag();

                                    echo utf8_decode('Bezugnehmend auf den Arbeitnehmerüberlassungsvertrag vom ' . ($datum instanceof \DateTime ? $datum->format('d.m.Y') : 'nicht bekannt') . ' stellen wir Ihnen folgende Arbeitnehmer zur Verfügung;;;;;;;;;') . PHP_EOL;
                                }

                                echo utf8_decode($von->format('d.m.Y') . ' - ' . $bis->format('d.m.Y') . ';;;;;;;;;') . PHP_EOL;
                                echo utf8_decode(';;;;;;;;;') . PHP_EOL;
                            }
                        }
                    }
                }
            }

            echo utf8_decode('Pers. Nr.;Name;Vorname;Geb. Datum;Beginn;Dauer;Qualifikation;Tätigkeit;Preis je Std. zzgl. 16% MwSt.;Arbeitszeit;W. Std.') . PHP_EOL;

            foreach ($array as $row) {
                echo \utf8_decode($row) . PHP_EOL;
            }

            $footer = [];
            $footer[] = ['ANDROM Personalservice GmbH', ''];
            $footer[] = ['Berliner Str. 55, D - 10713 Berlin', ''];
            $footer[] = ['Tel.: 030 34 34 90 90', ''];
            $footer[] = ['Fax: 030 34 34 90 921', ''];
            $footer[] = ['E-Mail: info@ttact.de', ''];
            $footer[] = ['Internet: www.ttact.de', ''];

            if (isset($kunde)) {
                if ($kunde instanceof \ttact\Models\KundeModel) {
                    $rechnungsanschrift_rows = explode(PHP_EOL, $kunde->getRechnungsanschrift());
                    $i = 0;
                    foreach ($rechnungsanschrift_rows as $row) {
                        if (isset($footer[$i])) {
                            $footer[$i][1] = trim($row);
                        } else {
                            $footer[$i] = ['', trim($row)];
                        }

                        $i++;
                    }

                    if (isset($footer[$i + 1])) {
                        $footer[$i + 1][1] = trim('Kundennummer: ' . $kunde->getKundennummer());
                    } else {
                        $footer[$i + 1] = ['', trim('Kundennummer: ' . $kunde->getKundennummer())];
                    }
                }
            }

            echo utf8_decode(';;;;;;;;;') . PHP_EOL;

            foreach ($footer as $array) {
                echo utf8_decode($array[0] . ';;;;;;;;' . $array[1] . ';') . PHP_EOL;
            }

            $this->template = 'blank';
        } elseif ($i['anlage2'] != '') {
            $dompdf = new Dompdf();

            $anlage2_data = unserialize(base64_decode($i['anlage2']));
            if (is_array($anlage2_data)) {
                $kunde_model = \ttact\Models\KundeModel::findByID($this->db, $i['kunde']);
                if ($kunde_model instanceof \ttact\Models\KundeModel) {
                    $mitarbeiter_model = \ttact\Models\MitarbeiterModel::findByID($this->db, $anlage2_data['mitarbeiter_id']);
                    if ($mitarbeiter_model instanceof \ttact\Models\MitarbeiterModel) {
                        $this->smarty_vars['rahmenvertragsdatum'] = $kunde_model->getUnterzeichnungsdatumRahmenvertrag() instanceof \DateTime ? $kunde_model->getUnterzeichnungsdatumRahmenvertrag()->format('d.m.Y') : '(k.A.)';
                        $this->smarty_vars['nachname'] = $mitarbeiter_model->getNachname();
                        $this->smarty_vars['vorname'] = $mitarbeiter_model->getVorname();
                        $this->smarty_vars['geburtsdatum'] = $mitarbeiter_model->getGeburtsdatum() instanceof \DateTime ? $mitarbeiter_model->getGeburtsdatum()->format('d.m.Y') : '';
                        $this->smarty_vars['personalnummer'] = $mitarbeiter_model->getPersonalnummer();
                        $this->smarty_vars['arbeitsort'] = $kunde_model->getStrasse() . ', ' . $kunde_model->getPostleitzahl() . ' ' . $kunde_model->getOrt();
                        $this->smarty_vars['taetigkeit'] = implode(', ', $anlage2_data['taetigkeiten']);
                        $this->smarty_vars['taetigkeitsbeschreibung'] = implode('<br><br>', $anlage2_data['taetigkeitsbeschreibung']);
                        $this->smarty_vars['wochenstunden'] = $anlage2_data['wochenstunden'];
                        $this->smarty_vars['ueberlassungsbeginn'] = $anlage2_data['ueberlassungsbeginn'];
                        $this->smarty_vars['ueberlassungsdauer'] = $anlage2_data['ueberlassungsdauer'];
                        $this->smarty_vars['verguetung'] = implode(', ', $anlage2_data['verguetung']);
                        $this->smarty_vars['datum'] = $anlage2_data['datum'];
                        $this->smarty_vars['kundensignatur'] = $anlage2_data['kundensignatur'];
                        $this->smarty->assign('smarty_vars', $this->smarty_vars, true);

                        // PDF settings
                        if (file_exists($this->smarty->getTemplateDir()[0] . 'main/Kunden/anlage2.' . $this->company . '.tpl')) {
                            $dompdf->loadHtml($this->smarty->fetch('main/Kunden/anlage2.' . $this->company . '.tpl'));
                        } elseif (file_exists($this->smarty->getTemplateDir()[0] . 'main/Kunden/anlage2.tpl')) {
                            $dompdf->loadHtml($this->smarty->fetch('main/Kunden/anlage2.tpl'));
                        }

                        $dompdf->setPaper('A4', 'portrait');
                        $dompdf->render();
                        $dompdf->stream('anlage2.pdf', array('Attachment' => 0));

                        $this->template = 'blank';
                    } else {
                        $this->template = '404';
                    }
                } else {
                    $this->template = '404';
                }
            } else {
                $this->template = '404';
            }
        } else {
            $erlaubte_kunden_ids = [];

            $alle_kunden = [];
            if ($this->current_user->getUsergroup()->hasRight('berechnungen_stunden_alle_kunden')) {
                $alle_kunden = \ttact\Models\KundeModel::findAll($this->db);
            } elseif ($this->current_user->getUsergroup()->hasRight('berechnungen_stunden_bestimmte_kunden')) {
                $alle_kundenbeschraenkungen = \ttact\Models\KundenbeschraenkungModel::findAllByUserID($this->db, $this->current_user->getID());
                foreach ($alle_kundenbeschraenkungen as $kundenbeschraenkung) {
                    if ($kundenbeschraenkung->getKunde() instanceof \ttact\Models\KundeModel) {
                        $alle_kunden[] = $kundenbeschraenkung->getKunde();
                        $erlaubte_kunden_ids[] = $kundenbeschraenkung->getKunde()->getID();
                    }
                }
            }
            $kundenliste = [];
            foreach ($alle_kunden as $kunde) {
                $kundenliste[] = [
                    'id' => $kunde->getID(),
                    'kundennummer' => $kunde->getKundennummer(),
                    'name' => $kunde->getName()
                ];
            }
            $this->smarty_vars['kundenliste'] = $kundenliste;

            if ($i['von'] != '' || $i['bis'] != '' || $i['kunde'] != '') {
                if ($this->user_input->isDate($i['von'])) {
                    $von = \DateTime::createFromFormat('d.m.Y', $i['von']);
                    if ($von instanceof \DateTime) {
                        if ($this->user_input->isDate($i['bis'])) {
                            $bis = \DateTime::createFromFormat('d.m.Y', $i['bis']);
                            if ($bis instanceof \DateTime) {
                                // Liste
                                $liste = [];

                                // Monatsanfang
                                $von->setTime(0, 0, 0);

                                // Monatsende
                                $bis->setTime(23, 59, 59);

                                $kunde_error = false;

                                $kunde = -1;
                                $kunde_model = \null;
                                if ($i['kunde'] != '') {
                                    $fehler = false;

                                    if (!$this->current_user->getUsergroup()->hasRight('berechnungen_stunden_alle_kunden')) {
                                        if (!in_array($i['kunde'], $erlaubte_kunden_ids)) {
                                            $kunde_error = true;
                                            $error = 'Der Kunde ist ungültig.';
                                        }
                                    }

                                    if (!$fehler) {
                                        $kunde_model = \ttact\Models\KundeModel::findByID($this->db, $i['kunde']);
                                        if ($kunde_model instanceof \ttact\Models\KundeModel) {
                                            $kunde = $kunde_model->getID();
                                            $filename .= '_kunde' . $kunde_model->getKundennummer();
                                        } else {
                                            $kunde_error = true;
                                            $error = 'Der Kunde ist ungültig.';
                                        }
                                    }
                                } elseif (!$this->current_user->getUsergroup()->hasRight('berechnungen_stunden_alle_kunden')) {
                                    $kunde_error = true;
                                    $error = 'Es muss ein Kunde angegeben werden.';
                                }

                                if (!$kunde_error) {
                                    $filename .= '_' . $von->format("d.m.Y") . '_bis_' . $bis->format("d.m.Y");

                                    $mitarbeiter = \ttact\Models\AuftragModel::findMitarbeiterLohnberechnungByStartEndKunde($this->db, $von, $bis, $kunde);
                                    foreach ($mitarbeiter as $m) {
                                        $abteilungen = \ttact\Models\AuftragModel::findAbteilungenByStartEndMitarbeiterKunde($this->db, $von, $bis, $m->getID(), $kunde);
                                        $taetigkeiten = [];
                                        $berechnete_kundenpreise = [];
                                        $berechnete_kundenpreise_zeige_abteilungsnamen = false;
                                        if (count($abteilungen) > 1) {
                                            $berechnete_kundenpreise_zeige_abteilungsnamen = true;
                                        }
                                        foreach ($abteilungen as $abteilung) {
                                            $taetigkeiten[$abteilung->getID()] = $abteilung->getBezeichnung();

                                            $kundenkondition = \ttact\Models\KundenkonditionModel::findForKundeAbteilungDate($this->db, $kunde, $abteilung->getID(), $von);
                                            if ($kundenkondition instanceof \ttact\Models\KundenkonditionModel) {
                                                if ($berechnete_kundenpreise_zeige_abteilungsnamen) {
                                                    $berechnete_kundenpreise[] = $abteilung->getBezeichnung() . ": " . str_replace('.', ',', $kundenkondition->getPreis()) . " Euro";
                                                } else {
                                                    $berechnete_kundenpreise[] = str_replace('.', ',', $kundenkondition->getPreis()) . " Euro";
                                                }
                                            }
                                        }

                                        $monate = [
                                            1 => 'Januar',
                                            2 => 'Februar',
                                            3 => 'März',
                                            4 => 'April',
                                            5 => 'Mai',
                                            6 => 'Juni',
                                            7 => 'Juli',
                                            8 => 'August',
                                            9 => 'September',
                                            10 => 'Oktober',
                                            11 => 'November',
                                            12 => 'Dezember'
                                        ];

                                        $beginn = '';
                                        if ($m->getEintritt() instanceof \DateTime) {
                                            if ($m->getEintritt() <= $von) {
                                                $beginn = $monate[(int) $von->format('m')] . ' ' . $von->format('Y');
                                            } else {
                                                $beginn = $m->getEintritt()->format('d.m.Y');
                                            }
                                        }

                                        $dauer = '';
                                        if ($m->getAustritt() instanceof \DateTime) {
                                            if ($m->getAustritt() >= $bis) {
                                                $dauer = 'offen';
                                            } else {
                                                $dauer = $m->getAustritt()->format('d.m.Y');
                                            }
                                        } else {
                                            $dauer = 'offen';
                                        }

                                        $wochenstunden = '';
                                        $lohnkonfiguration = \ttact\Models\LohnkonfigurationModel::findForMitarbeiterDate($this->db, $m->getID(), $von);
                                        if ($lohnkonfiguration instanceof \ttact\Models\LohnkonfigurationModel) {
                                            $wochenstunden = str_replace('.', ',', $lohnkonfiguration->getWochenstunden());
                                        }

                                        $ueberlassungsbeginn = '';
                                        $ueberlassungsdauer = 'Max. 9 Monate (270 Tage) nach Regelung Equal Pay';
                                        $datum = '';
                                        $first_auftrag_model = \ttact\Models\AuftragModel::findFirstByKundeMitarbeiter($this->db, $this->current_user, $kunde_model->getID(), $m->getID());
                                        if ($first_auftrag_model instanceof \ttact\Models\AuftragModel) {
                                            $ueberlassungsbeginn = $first_auftrag_model->getVon()->format('d.m.Y');
                                            $ueberlassungsbeginn_plus_9_monate = clone $first_auftrag_model->getVon();
                                            $ueberlassungsbeginn_plus_9_monate->add(new \DateInterval('P0000-09-00T00:00:00'));
                                            if ($m->getAustritt() instanceof \DateTime) {
                                                if ($m->getAustritt() <= $ueberlassungsbeginn_plus_9_monate) {
                                                    $ueberlassungsdauer = $m->getAustritt()->format('d.m.Y');
                                                }
                                            }

                                            $datum_model = clone $first_auftrag_model->getVon();
                                            $datum_model->sub(new \DateInterval("P0000-00-01T00:00:00"));
                                            while ($datum_model->format('N') == 6 || $datum_model->format('N') == 7) {
                                                $datum_model->sub(new \DateInterval("P0000-00-01T00:00:00"));
                                            }
                                            $datum = $datum_model->format('d.m.Y');
                                        }

                                        $verguetung = [];
                                        $taetigkeitsbeschreibung = [];
                                        $taetigkeitsbeschreibungen = [
                                            'kasse' => 'Kassieren mittels computergesteuerten Scanner-Kassen, Abwicklung des Zahlungsvorgangs mit Bar- oder Kartenzahlung, Kassenabrechnung, Sauberhalten der Kassenzone',
                                            'backshop' => 'Aufbacken und Präsentation der Backwaren, Zubereitung von Heißgetränken, Belegen von Brötchen, Bestücken der Verkaufsfläche',
                                            'bedienung' => 'Verkauf und Kundenberatung in den Bereichen Fleisch, Wurst und Käse, Einhaltung der HACCP-Richtlinien, Ordnung und Sauberkeit',
                                            'convenience' => 'Erstellung von verzehrfertigen Lebensmitteln (z.B. Salate) nach Rezept, Bestücken des Verkaufstresens, korrekte Preisauszeichnung und kontinuierliche Frischekontrollen, Einhaltung von Qualitätsstandards',
                                            'kolo' => 'Warenverräumung im gesamten Trockensortiment, Mindesthaltbarkeitsdatum (MHD) kontrollieren Ware vorziehen',
                                            'mopro' => 'Warenverräumung im Bereich Molkereiprodukte, Mindesthaltbarkeitsdatum (MHD) kontrollieren, Ware vorziehen',
                                            'obst und gemüse' => 'Verkaufstresen mit Obst und Gemüse befüllen und nachfüllen, regelmäßige Qualitätskontrolle',
                                            'getränke' => 'Warenverräumung im Bereich Getränke, Mindesthaltbarkeitsdatum (MHD) kontrollieren, Ware vorziehen',
                                            'promotion' => 'Flyer verteilen, Präsente an Kunden verteilen'
                                        ];
                                        foreach ($taetigkeiten as $abteilung_id => $abteilung_bezeichnung) {
                                            $k = \ttact\Models\KundenkonditionModel::findForKundeAbteilungDate($this->db, $kunde_model->getID(), $abteilung_id, new \DateTime('now'));
                                            if ($k instanceof \ttact\Models\KundenkonditionModel) {
                                                $verguetung[] = (count($taetigkeiten) > 1 ? $abteilung_bezeichnung . ': ' : '') . number_format($k->getPreis(), 2, ',', '') . '€ zzgl. 19% MwSt.';
                                            }

                                            $t = '';
                                            foreach ($taetigkeitsbeschreibungen as $keyword => $beschreibung) {
                                                if (strpos(strtolower($abteilung_bezeichnung), $keyword) !== false) {
                                                    $t = $beschreibung;
                                                    break;
                                                }
                                            }
                                            if ($t != '') {
                                                $taetigkeitsbeschreibung[] = (count($taetigkeiten) > 1 ? $abteilung_bezeichnung . ': ' : '') . $t;
                                            }
                                        }

                                        $kundensignatur = str_replace(PHP_EOL, '<br>', $kunde_model->getRechnungsanschrift());
                                        if ($kunde_model->getTelefon1() != '') {
                                            $kundensignatur .= '<br>Tel.: ' . $kunde_model->getTelefon1();
                                        } elseif ($kunde_model->getTelefon2() != '') {
                                            $kundensignatur .= '<br>Tel.: ' . $kunde_model->getTelefon2();
                                        }

                                        $anlage2_data = [
                                            'mitarbeiter_id' => $m->getID(),
                                            'taetigkeiten' => $taetigkeiten,
                                            'wochenstunden' => $wochenstunden,
                                            'ueberlassungsbeginn' => $ueberlassungsbeginn,
                                            'ueberlassungsdauer' => $ueberlassungsdauer,
                                            'verguetung' => $verguetung,
                                            'datum' => $datum,
                                            'taetigkeitsbeschreibung' => $taetigkeitsbeschreibung,
                                            'kundensignatur' => $kundensignatur
                                        ];

                                        $liste[] = [
                                            'personalnummer' => $m->getPersonalnummer(),
                                            'vorname' => $m->getVorname(),
                                            'nachname' => $m->getNachname(),
                                            'taetigkeit' => implode($taetigkeiten, ', '),
                                            'export' =>
                                                $m->getPersonalnummer() . ';' .
                                                $m->getNachname() . ';' .
                                                $m->getVorname() . ';' .
                                                ($m->getGeburtsdatum() instanceof \DateTime ? $m->getGeburtsdatum()->format('d.m.Y') : '') . ';' .
                                                $beginn . ';' .
                                                $dauer . ';' .
                                                'keine' . ';' .
                                                implode($taetigkeiten, ', ') . ';' .
                                                implode($berechnete_kundenpreise, ', ') . ';' .
                                                'nach Bedarf' . ';' .
                                                $wochenstunden,
                                            'anlage2_data' => base64_encode(serialize($anlage2_data))
                                        ];
                                    }

                                    $this->smarty_vars['liste'] = $liste;
                                }
                            } else {
                                // error
                            }
                        } else {
                            // error
                        }
                    } else {
                        // error
                    }
                } else {
                    // error
                }
            }

            // display error message
            if ($error == "" && $success != "") {
                $this->smarty_vars['success'] = $success;
            }
            if ($error != "") {
                $this->smarty_vars['error'] = rtrim($error, '<br>');
            }

            if ($this->current_user->getUsergroup()->hasRight('mitarbeiterliste') && !$this->current_user->getUsergroup()->hasRight('kundendaten')) {
                $this->smarty_vars['kunde_pflichtangabe'] = true;
            } else {
                $this->smarty_vars['kunde_pflichtangabe'] = false;
            }

            $date = new \DateTime("now");

            $this->smarty_vars['values'] = [
                'von'  => $i['von'],
                'bis' => $i['bis'],
                'kunde' => $i['kunde'],
                'filename' => $filename
            ];

            // template settings
            $this->template = 'main';
        }
    }

    public function dokumente() {
        $error = [];
        $success = [];

        if (isset($this->params[0])) {
            if ($this->params[0] == 'fehler') {
                $error[] = "Das Dokument konnte nicht gefunden werden.";
                unset($this->params[0]);
            }
        }

        // $smarty_vars.kundenliste
        $erlaubte_kundennummern = [];
        $alle_kunden = [];
        if ($this->current_user->getUsergroup()->hasRight('dokumente_alle_kunden')) {
            $alle_kunden = \ttact\Models\KundeModel::findAll($this->db);
        } elseif ($this->current_user->getUsergroup()->hasRight('dokumente_einsehen_bestimmte_kunden')) {
            $alle_kundenbeschraenkungen = \ttact\Models\KundenbeschraenkungModel::findAllByUserID($this->db, $this->current_user->getID());
            foreach ($alle_kundenbeschraenkungen as $kundenbeschraenkung) {
                if ($kundenbeschraenkung->getKunde() instanceof \ttact\Models\KundeModel) {
                    $alle_kunden[] = $kundenbeschraenkung->getKunde();
                    $erlaubte_kundennummern[] = $kundenbeschraenkung->getKunde()->getKundennummer();
                }
            }
        }
        $kundenliste = [];
        foreach ($alle_kunden as $kunde) {
            $kundenliste[] = [
                'kundennummer' => $kunde->getKundennummer(),
                'name' => $kunde->getName()
            ];
        }
        $this->smarty_vars['kundenliste'] = $kundenliste;

        $kunde = null;
        if ($this->current_user->getUsergroup()->hasRight('dokumente_einsehen_bestimmte_kunden')) {
            if (count($erlaubte_kundennummern) == 1) {
                $kunde = \ttact\Models\KundeModel::findByKundennummer($this->db, $erlaubte_kundennummern[0]);
            } elseif (isset($this->params[0])) {
                if (in_array($this->params[0], $erlaubte_kundennummern)) {
                    $kunde = \ttact\Models\KundeModel::findByKundennummer($this->db, $this->user_input->getOnlyNumbers($this->params[0]));
                } else {
                    $error[] = "Der Kunde ist ungültig!";
                }
            }
        } elseif ($this->current_user->getUsergroup()->hasRight('dokumente_alle_kunden')) {
            if (isset($this->params[0])) {
                if ($this->user_input->isPositiveInteger($this->params[0])) {
                    $kunde = \ttact\Models\KundeModel::findByKundennummer($this->db, $this->user_input->getOnlyNumbers($this->params[0]));
                    if (!$kunde instanceof \ttact\Models\KundeModel) {
                        $error[] = "Der Kunde ist ungültig!";
                    }
                } else {
                    $error[] = "Der Kunde ist ungültig!";
                }
            }
        }

        if ($kunde instanceof \ttact\Models\KundeModel) {
            $this->smarty_vars['kundennummer'] = $kunde->getKundennummer();

            if ($this->current_user->getUsergroup()->hasRight('dokumente_alle_kunden')) {
                $prev_kunde = \ttact\Models\KundeModel::findPrev($this->db, $kunde->getKundennummer());
                if ($prev_kunde instanceof \ttact\Models\KundeModel) {
                    $this->smarty_vars['prev_kunde'] = $prev_kunde->getKundennummer();
                }

                $next_kunde = \ttact\Models\KundeModel::findNext($this->db, $kunde->getKundennummer());
                if ($next_kunde instanceof \ttact\Models\KundeModel) {
                    $this->smarty_vars['next_kunde'] = $next_kunde->getKundennummer();
                }

                if (isset($_FILES['datei'])) {
                    foreach ($_FILES['datei']['name'] as $id => $name) {
                        if ($_FILES['datei']['error'][$id] !== UPLOAD_ERR_OK) {
                            $error[] = "Beim Hochladen der Datei '" . $_FILES['datei']['name'][$id] . "' ist ein Fehler aufgetreten.";
                        } elseif (finfo_file(finfo_open(FILEINFO_MIME_TYPE), $_FILES['datei']['tmp_name'][$id]) != 'application/pdf') {
                            $error[] = "Die hochgeladene Datei '" . $_FILES['datei']['name'][$id] . "' ist keine .pdf-Datei.";
                        } elseif (strtolower(pathinfo($name, PATHINFO_EXTENSION)) != 'pdf') {
                            $error[] = "Die hochgeladene Datei '" . $_FILES['datei']['name'][$id] . "' ist keine .pdf-Datei.";
                        } elseif ($_FILES['datei']['size'][$id] > 50000000) {
                            $error[] = "Die hochgeladene Datei '" . $_FILES['datei']['name'][$id] . "' darf nicht größer als 50 MB sein.";
                        } else {
                            // save file
                            $ttact_intern_docs_path = __DIR__ . '/../../../../ttact-intern-docs/';
                            $filename = md5(random_int(100000000000000000, 999999999999999999));
                            while (file_exists($ttact_intern_docs_path . $filename)) {
                                $filename = md5(random_int(100000000000000000, 999999999999999999));
                            }

                            $successful_upload = true;
                            set_error_handler(function () {
                                throw new \Exception();
                            });
                            try {
                                move_uploaded_file($_FILES['datei']['tmp_name'][$id], $ttact_intern_docs_path . $filename);
                            } catch (\Exception $exception) {
                                $successful_upload = false;
                            }
                            restore_error_handler();

                            if ($successful_upload) {
                                if (file_exists($ttact_intern_docs_path . $filename)) {
                                    $data = [
                                        'kunde_id' => $kunde->getID(),
                                        'size' => $_FILES['datei']['size'][$id],
                                        'name' => $_FILES['datei']['name'][$id],
                                        'path' => $filename,
                                        'user_id' => $this->current_user->getID()
                                    ];

                                    $dokument_model = \ttact\Models\DokumentModel::createNew($this->db, $data);
                                    if ($dokument_model instanceof \ttact\Models\DokumentModel) {
                                        $success[] = "Die Datei '" . $_FILES['datei']['name'][$id] . "' wurde erfolgreich hochgeladen.";
                                    } else {
                                        unlink($ttact_intern_docs_path . $filename);
                                        $error[] = "Beim Hochladen der Datei '" . $_FILES['datei']['name'][$id] . "' ist ein Fehler aufgetreten.";
                                    }
                                } else {
                                    $error[] = "Beim Hochladen der Datei '" . $_FILES['datei']['name'][$id] . "' ist ein Fehler aufgetreten.";
                                }
                            } else {
                                $error[] = "Beim Hochladen der Datei '" . $_FILES['datei']['name'][$id] . "' ist ein Fehler aufgetreten.";
                            }
                        }
                    }
                }
            }

            function human_filesize($bytes, $decimals = 2) {
                $sz = 'BKMGTP';
                $factor = floor((strlen($bytes) - 1) / 3);
                return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
            }

            $dokumentenliste = [];
            foreach (\ttact\Models\DokumentModel::findByKunde($this->db, $kunde->getID()) as $dokument_model) {
                $dokumentenliste[] = [
                    'id' => $dokument_model->getID(),
                    'name' => $dokument_model->getName(),
                    'size' => human_filesize($dokument_model->getSize())
                ];
            }
            $this->smarty_vars['dokumentenliste'] = $dokumentenliste;

            if (isset($this->params[1])) {
                if ($this->params[1] == 'erfolgreich') {
                    $success[] = 'Das Dokument wurde erfolgreich gelöscht.';
                } elseif ($this->params[1] == 'fehler') {
                    $success[] = 'Das Dokument konnte nicht gelöscht werden.';
                }
            }
        }

        // $smarty_vars.error
        if (count($error) > 0) {
            $this->smarty_vars['error'] = $error;
        }

        // $smarty_vars.success
        if (count($success) > 0) {
            $this->smarty_vars['success'] = $success;
        }

        $this->template = 'main';
    }
}
