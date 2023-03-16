<?php

namespace ttact\Controllers;

class KundenkonditionController extends Controller
{
    public function bearbeiten()
    {
        $redirect = true;

        if (isset($this->params[0])) {
            if ($this->user_input->isPositiveInteger($this->params[0])) {
                $kundenkondition = \ttact\Models\KundenkonditionModel::findByID($this->db, $this->user_input->getOnlyNumbers($this->params[0]));
                if ($kundenkondition instanceof \ttact\Models\KundenkonditionModel) {
                    $redirect = false;

                    $error = "";
                    $success = "";

                    // array with all input data
                    $i = [
                        'gueltig_ab'        => '',
                        'gueltig_bis'       => '',
                        'abteilung'         => '',

                        'preis'             => '',
                        'sonntagszuschlag'  => '',
                        'feiertagszuschlag' => '',
                        'nachtzuschlag'     => '',

                        'nacht_von'         => '',
                        'nacht_bis'         => ''
                    ];
                    if ($this->company == 'tps') {
                        $i['zeit_pro_palette'] = '';
                    }
                    foreach ($i as $key => $value) {
                        $i[$key] = $this->user_input->getPostParameter($key);
                    }
                    $i['abteilung'] = (int) $this->user_input->getOnlyNumbers($i['abteilung']);
                    $i['preis'] = (float) str_replace(',', '.', $i['preis']);
                    $i['sonntagszuschlag'] = (int) $this->user_input->getOnlyNumbers($i['sonntagszuschlag']);
                    $i['feiertagszuschlag'] = (int) $this->user_input->getOnlyNumbers($i['feiertagszuschlag']);
                    $i['nachtzuschlag'] = (int) $this->user_input->getOnlyNumbers($i['nachtzuschlag']);
                    $i['nacht_von'] = $this->user_input->getOnlyNumbers($i['nacht_von']);
                    $i['nacht_bis'] = $this->user_input->getOnlyNumbers($i['nacht_bis']);
                    if ($this->company == 'tps') {
                        $i['zeit_pro_palette'] = $this->user_input->getOnlyNumbers($i['zeit_pro_palette']);
                    }

                    // check and save
                    $gueltig_ab = '00.00.0000';
                    if ($kundenkondition->getGueltigAb() instanceof \DateTime) {
                        $gueltig_ab = $kundenkondition->getGueltigAb()->format("d.m.Y");
                    }
                    $gueltig_bis = '';
                    if ($kundenkondition->getGueltigBis() instanceof \DateTime) {
                        $gueltig_bis = $kundenkondition->getGueltigBis()->format("d.m.Y");
                    }
                    if ($i['abteilung'] != "") {
                        // Gültig ab
                        if (!$this->user_input->isDate($i['gueltig_ab'])) {
                            $error .= "Das Datum für Gültig ab ist ungültig. ";
                        } elseif ($i['gueltig_ab'] != $gueltig_ab) {
                            $date_already_exists = false;
                            $all_kundenkonditionen = \ttact\Models\KundenkonditionModel::findAllByKunde($this->db, $kundenkondition->getKunde()->getID());
                            foreach ($all_kundenkonditionen as $l) {
                                if ($l->getAbteilung()->getID() == $kundenkondition->getAbteilung()->getID()) {
                                    if ($l->getGueltigAb() instanceof \DateTime) {
                                        if ($l->getGueltigAb()->format("d.m.Y") == $i['gueltig_ab']) {
                                            $date_already_exists = true;
                                        }
                                    }
                                }
                            }
                            if ($date_already_exists) {
                                $error .= "Es gibt bereits eine Kundenkondition für die ausgewählte Abteilung mit diesem Datum für Gültig ab. ";
                            } else {
                                $date = \DateTime::createFromFormat('d.m.Y', $i['gueltig_ab']);
                                if ($date instanceof \DateTime) {
                                    if ($date->format("d.m.Y") == $i['gueltig_ab']) {
                                        if ($kundenkondition->setGueltigAb($date->format("Y-m-d"))) {
                                            $success .= "Das Datum für Gültig ab wurde erfolgreich geändert. ";
                                        } else {
                                            $error .= "Beim Speichern des Datums für Gültig ab ist ein technischer Fehler aufgetreten. ";
                                        }
                                    } else {
                                        $error .= "Das Datum für Gültig ab ist ungültig. ";
                                    }
                                } else {
                                    $error .= "Das Datum für Gültig ab ist ungültig. ";
                                }
                            }
                        }

                        // keine Änderungen erlauben, falls irgendetwas mit dem Gültig ab Datum nicht stimmt.
                        if ($error == "") {
                            // Gültig bis
                            if ($i['gueltig_bis'] != $gueltig_bis) {
                                if ($this->user_input->isDate($i['gueltig_bis'])) {
                                    // gültiges Datum einspeichern
                                    $date = \DateTime::createFromFormat('d.m.Y', $i['gueltig_bis']);
                                    if ($date instanceof \DateTime) {
                                        if ($date->format("d.m.Y") == $i['gueltig_bis']) {
                                            if ($kundenkondition->setGueltigBis($date->format("Y-m-d"))) {
                                                $success .= "Das Datum für Gültig bis wurde erfolgreich geändert. ";
                                            }
                                            else {
                                                $error .= "Beim Speichern des Datums für Gültig bis ist ein technischer Fehler aufgetreten. ";
                                            }
                                        }
                                        else {
                                            $error .= "Das Datum für Gültig bis ist ungültig. ";
                                        }
                                    }
                                    else {
                                        $error .= "Das Datum für Gültig bis ist ungültig. ";
                                    }
                                }
                                elseif ($i['gueltig_bis'] != "") {
                                    // das Datum ist ungültig. Fehlermeldung!
                                    $error .= "Das Datum für Gültig bis ist ungültig. ";
                                }
                                else {
                                    // das Datum soll entfernt werden
                                    if ($kundenkondition->setGueltigBis('0000-00-00')) {
                                        $success .= "Das Datum für Gültig bis wurde erfolgreich geändert. ";
                                    }
                                    else {
                                        $error .= "Beim Speichern des Datums für Gültig bis ist ein technischer Fehler aufgetreten. ";
                                    }
                                }
                            }

                            // Abteilung
                            $abteilung = '';
                            if ($kundenkondition->getAbteilung() instanceof \ttact\Models\AbteilungModel) {
                                $abteilung = $kundenkondition->getAbteilung()->getID();
                            }
                            if ($i['abteilung'] != $abteilung) {
                                $test_abteilung = \ttact\Models\AbteilungModel::findByID($this->db, $i['abteilung']);
                                if ($test_abteilung instanceof \ttact\Models\AbteilungModel) {
                                    if ($kundenkondition->setAbteilungID($test_abteilung->getID())) {
                                        $success .= "Die Abteilung wurde erfolgreich geändert. ";
                                    }
                                    else {
                                        $error .= "Beim Speichern der Abteilung ist ein technischer Fehler aufgetreten. ";
                                    }
                                }
                                else {
                                    $error .= "Die Abteilung ist ungültig. ";
                                }
                            }

                            // Preis
                            if ($i['preis'] != $kundenkondition->getPreis()) {
                                if ($kundenkondition->setPreis($i['preis'])) {
                                    $success .= "Der Preis wurde erfolgreich geändert. ";
                                }
                                else {
                                    $error .= "Beim Speichern des Preises ist ein technischer Fehler aufgetreten. ";
                                }
                            }

                            // Sonntagszuschlag
                            if ($i['sonntagszuschlag'] != $kundenkondition->getSonntagszuschlag()) {
                                if ($kundenkondition->setSonntagszuschlag($i['sonntagszuschlag'])) {
                                    $success .= "Der Sonntagszuschlag wurde erfolgreich geändert. ";
                                }
                                else {
                                    $error .= "Beim Speichern des Sonntagszuschlags ist ein technischer Fehler aufgetreten. ";
                                }
                            }

                            // Feiertagszuschlag
                            if ($i['feiertagszuschlag'] != $kundenkondition->getFeiertagszuschlag()) {
                                if ($kundenkondition->setFeiertagszuschlag($i['feiertagszuschlag'])) {
                                    $success .= "Der Feiertagszuschlag wurde erfolgreich geändert. ";
                                }
                                else {
                                    $error .= "Beim Speichern des Feiertagszuschlags ist ein technischer Fehler aufgetreten. ";
                                }
                            }

                            // Nachtzuschlag
                            if ($i['nachtzuschlag'] != $kundenkondition->getNachtzuschlag()) {
                                if ($kundenkondition->setNachtzuschlag($i['nachtzuschlag'])) {
                                    $success .= "Der Nachtzuschlag wurde erfolgreich geändert. ";
                                }
                                else {
                                    $error .= "Beim Speichern des Nachtzuschlags ist ein technischer Fehler aufgetreten. ";
                                }
                            }

                            // Nachtarbeitszeiten
                            $nacht_von = '';
                            if ($kundenkondition->getNachtVon() instanceof \DateTime) {
                                $nacht_von = $kundenkondition->getNachtVon()->format("Hi");
                            }
                            $nacht_bis = '';
                            if ($kundenkondition->getNachtBis() instanceof \DateTime) {
                                $nacht_bis = $kundenkondition->getNachtBis()->format("Hi");
                            }
                            if ($i['nacht_von'] != $nacht_von || $i['nacht_bis'] != $nacht_bis) {
                                if ($i['nacht_von'] != "" && $i['nacht_bis'] != "") {
                                    if (strlen($i['nacht_von']) != 4 || !preg_match('/^[0-2][0-9][0-5][0-9]/', $i['nacht_von'])) {
                                        $error .= "Nacht von: Die Zeitangabe ist ungültig. ";
                                    }
                                    elseif (strlen($i['nacht_bis']) != 4 || !preg_match('/^[0-2][0-9][0-5][0-9]/', $i['nacht_bis'])) {
                                        $error .= "Nacht bis: Die Zeitangabe ist ungültig. ";
                                    }
                                    else {
                                        $von = new \DateTime();
                                        $von->setTime((int)substr($i['nacht_von'], 0, 2), (int)substr($i['nacht_von'], 2, 2));

                                        $bis = new \DateTime();
                                        $bis->setTime((int)substr($i['nacht_bis'], 0, 2), (int)substr($i['nacht_bis'], 2, 2));

                                        if (!$kundenkondition->setNachtVon($von->format("H:i") . ":00")) {
                                            $error .= "Beim Speichern der Uhrzeit für Nacht von ist ein technischer Fehler aufgetreten. ";
                                        }
                                        elseif (!$kundenkondition->setNachtBis($bis->format("H:i") . ":00")) {
                                            $error .= "Beim Speichern der Uhrzeit für Nacht bis ist ein technischer Fehler aufgetreten. ";
                                        }
                                        else {
                                            $success .= "Die Uhrzeit für Nacht von und Nacht bis wurde erfolgreich geändert. ";
                                        }
                                    }
                                }
                                elseif ($i['nacht_von'] == "" && $i['nacht_bis'] != "") {
                                    $error .= "Nacht von: Es fehlt eine Zeit. ";
                                }
                                elseif ($i['nacht_bis'] == "" && $i['nacht_von'] != "") {
                                    $error .= "Nacht bis: Es fehlt eine Zeit. ";
                                }
                            }

                            if ($this->company == 'tps') {
                                // Zeit pro Palette
                                $zeit_pro_palette = '';
                                if ($kundenkondition->getZeitProPalette() instanceof \DateTime) {
                                    $zeit_pro_palette = $kundenkondition->getZeitProPalette()->format("Hi");
                                }
                                if ($i['zeit_pro_palette'] != $zeit_pro_palette) {
                                    if ($i['zeit_pro_palette'] != "") {
                                        if (strlen($i['zeit_pro_palette']) != 4 || !preg_match('/^[0-2][0-9][0-5][0-9]/', $i['zeit_pro_palette'])) {
                                            $error .= "Zeit pro Palette: Die Zeitangabe ist ungültig. ";
                                        } else {
                                            $save_zeit_pro_palette = new \DateTime();
                                            $save_zeit_pro_palette->setTime((int)substr($i['zeit_pro_palette'], 0, 2), (int)substr($i['zeit_pro_palette'], 2, 2));

                                            if (!$kundenkondition->setZeitProPalette($save_zeit_pro_palette->format("H:i") . ":00")) {
                                                $error .= "Beim Speichern der Zeit pro Palette ist ein technischer Fehler aufgetreten. ";
                                            } else {
                                                $success .= "Die Zeit pro Palette wurde erfolgreich geändert. ";
                                            }
                                        }
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
                        $this->misc_utils->redirect('kunden', 'bearbeiten', $kundenkondition->getKunde()->getKundennummer(), 'erfolgreich', 'bearbeitet');
                    }

                    // fill values into the form
                    $gueltig_ab = '00.00.0000';
                    if ($kundenkondition->getGueltigAb() instanceof \DateTime) {
                        $gueltig_ab = $kundenkondition->getGueltigAb()->format("d.m.Y");
                    }
                    $gueltig_bis = '';
                    if ($kundenkondition->getGueltigBis() instanceof \DateTime) {
                        $gueltig_bis = $kundenkondition->getGueltigBis()->format("d.m.Y");
                    }
                    $abteilung = '';
                    if ($this->company == 'tps') {
                        $palettenabteilung = false;
                        $zeit_pro_palette = '01:30';
                    }
                    if ($kundenkondition->getAbteilung() instanceof \ttact\Models\AbteilungModel) {
                        $abteilung = $kundenkondition->getAbteilung()->getID();

                        if ($this->company == 'tps') {
                            $palettenabteilung = $kundenkondition->getAbteilung()->getPalettenabteilung();
                            if ($kundenkondition->getZeitProPalette() instanceof \DateTime) {
                                $zeit_pro_palette = $kundenkondition->getZeitProPalette()->format('H:i');
                            }
                        }
                    }
                    $values = [
                        'id' => $kundenkondition->getID(),
                        'kundennummer' => $kundenkondition->getKunde()->getKundennummer(),
                        'bezeichnung' => $kundenkondition->getKunde()->getName(),
                        'gueltig_ab' => $gueltig_ab,
                        'gueltig_bis' => $gueltig_bis,
                        'abteilung' => $abteilung,
                        'preis' => $kundenkondition->getPreis(),
                        'sonntagszuschlag' => $kundenkondition->getSonntagszuschlag(),
                        'feiertagszuschlag' => $kundenkondition->getFeiertagszuschlag(),
                        'nachtzuschlag' => $kundenkondition->getNachtzuschlag(),
                        'nacht_von' => $kundenkondition->getNachtVon() instanceof \DateTime ? $kundenkondition->getNachtVon()->format("H:i") : '',
                        'nacht_bis' => $kundenkondition->getNachtBis() instanceof \DateTime ? $kundenkondition->getNachtBis()->format("H:i") : ''
                    ];
                    if ($this->company == 'tps') {
                        $values['palettenabteilung'] = $palettenabteilung;
                        $values['zeit_pro_palette'] = $zeit_pro_palette;
                    }
                    $this->smarty_vars['values'] = $values;

                    // get data for Abteilung
                    $abteilungsliste = [];
                    $abteilungen = \ttact\Models\AbteilungModel::findAll($this->db);
                    foreach ($abteilungen as $abteilung) {
                        $abteilungsliste[$abteilung->getID()] = [
                            'id' => $abteilung->getID(),
                            'bezeichnung' => $abteilung->getBezeichnung()
                        ];

                        if ($this->company == 'tps') {
                            $abteilungsliste[$abteilung->getID()]['palettenabteilung'] = $abteilung->getPalettenabteilung() ? 'ja' : 'nein';
                        }
                    }
                    $this->smarty_vars['abteilungsliste'] = $abteilungsliste;

                    // template settings
                    $this->template = 'main';
                }
            }
        }

        if ($redirect) {
            $this->misc_utils->redirect('kunden', 'index', 'fehler');
        }
    }

    public function erstellen()
    {
        $redirect = true;

        if (isset($this->params[0])) {
            if ($this->user_input->isPositiveInteger($this->params[0])) {
                $kunde = \ttact\Models\KundeModel::findByKundennummer($this->db, $this->user_input->getOnlyNumbers($this->params[0]));
                if ($kunde instanceof \ttact\Models\KundeModel) {
                    $redirect = false;

                    $error = "";
                    $success = "";

                    $kundenkondition_model = null;

                    // array with all input data
                    $i = [
                        'gueltig_ab'        => '',
                        'gueltig_bis'       => '',
                        'abteilung'         => '',

                        'preis'             => '',
                        'sonntagszuschlag'  => '',
                        'feiertagszuschlag' => '',
                        'nachtzuschlag'     => '',

                        'nacht_von'         => '',
                        'nacht_bis'         => ''
                    ];
                    if ($this->company == 'tps') {
                        $i['zeit_pro_palette'] = '';
                    }
                    foreach ($i as $key => $value) {
                        $i[$key] = $this->user_input->getPostParameter($key);
                    }
                    $i['abteilung'] = (int) $this->user_input->getOnlyNumbers($i['abteilung']);
                    $i['preis'] = (float) str_replace(',', '.', $i['preis']);
                    $i['sonntagszuschlag'] = (int) $this->user_input->getOnlyNumbers($i['sonntagszuschlag']);
                    $i['feiertagszuschlag'] = (int) $this->user_input->getOnlyNumbers($i['feiertagszuschlag']);
                    $i['nachtzuschlag'] = (int) $this->user_input->getOnlyNumbers($i['nachtzuschlag']);
                    $i['nacht_von'] = $this->user_input->getOnlyNumbers($i['nacht_von']);
                    $i['nacht_bis'] = $this->user_input->getOnlyNumbers($i['nacht_bis']);
                    if ($this->company == 'tps') {
                        $i['zeit_pro_palette'] = $this->user_input->getOnlyNumbers($i['zeit_pro_palette']);
                    }

                    // check and save
                    if ($i['gueltig_ab'] != '' || $i['abteilung'] != '') {
                        $test_abteilung = \ttact\Models\AbteilungModel::findByID($this->db, $i['abteilung']);
                        if ($test_abteilung instanceof \ttact\Models\AbteilungModel) {
                            if ($this->user_input->isDate($i['gueltig_ab'])) {
                                $date_already_exists = false;
                                $all_kundenkonditionen = \ttact\Models\KundenkonditionModel::findAllByKunde($this->db, $kunde->getID());
                                foreach ($all_kundenkonditionen as $l) {
                                    if ($l->getGueltigAb() instanceof \DateTime) {
                                        if ($l->getGueltigAb()->format("d.m.Y") == $i['gueltig_ab'] && $l->getAbteilung()->getID() == $test_abteilung->getID()) {
                                            $date_already_exists = true;
                                        }
                                    }
                                }
                                if ($date_already_exists) {
                                    $error .= "Es gibt bereits eine Kundenkondition für die ausgewählte Abteilung mit diesem Gültig ab Datum. ";
                                } else {
                                    $gueltig_ab = \DateTime::createFromFormat('d.m.Y', $i['gueltig_ab']);
                                    if ($gueltig_ab instanceof \DateTime) {
                                        if ($gueltig_ab->format("d.m.Y") == $i['gueltig_ab']) {
                                            if ($i['nacht_von'] != "" && $i['nacht_bis'] != "") {
                                                if (strlen($i['nacht_von']) != 4 || !preg_match('/^[0-2][0-9][0-5][0-9]/', $i['nacht_von'])) {
                                                    $error .= "Nacht von: Die Zeitangabe ist ungültig. ";
                                                } elseif (strlen($i['nacht_bis']) != 4 || !preg_match('/^[0-2][0-9][0-5][0-9]/', $i['nacht_bis'])) {
                                                    $error .= "Nacht bis: Die Zeitangabe ist ungültig. ";
                                                } else {
                                                    $continue = false;

                                                    if ($this->company == 'tps') {
                                                        $save_zeit_pro_palette = '01:30:00';

                                                        if ($test_abteilung->getPalettenabteilung()) {
                                                            if (strlen($i['zeit_pro_palette']) != 4 || !preg_match('/^[0-2][0-9][0-5][0-9]/', $i['zeit_pro_palette'])) {
                                                                $error .= "Zeit pro Palette: Die Zeitangabe ist ungültig. ";
                                                            } else {
                                                                $zeit_pro_palette_model = new \DateTime();
                                                                $zeit_pro_palette_model->setTime((int) substr($i['zeit_pro_palette'], 0, 2), (int) substr($i['zeit_pro_palette'], 2, 2));

                                                                $save_zeit_pro_palette = $zeit_pro_palette_model->format('H:i') . ':00';
                                                                $continue = true;
                                                            }
                                                        } else {
                                                            $continue = true;
                                                        }
                                                    } else {
                                                        $continue = true;
                                                    }

                                                    if ($continue) {
                                                        $von = new \DateTime();
                                                        $von->setTime((int) substr($i['nacht_von'], 0, 2), (int) substr($i['nacht_von'], 2, 2));

                                                        $bis = new \DateTime();
                                                        $bis->setTime((int) substr($i['nacht_bis'], 0, 2), (int) substr($i['nacht_bis'], 2, 2));

                                                        $gueltig_bis_error = false;
                                                        $gueltig_bis = '0000-00-00';
                                                        if ($i['gueltig_bis'] != "") {
                                                            $gueltig_bis_datetime = \DateTime::createFromFormat('d.m.Y', $i['gueltig_bis']);
                                                            if ($gueltig_bis_datetime instanceof \DateTime) {
                                                                if ($gueltig_bis_datetime->format("d.m.Y") == $i['gueltig_bis']) {
                                                                    $gueltig_bis = $gueltig_bis_datetime->format('d.m.Y');
                                                                } else {
                                                                    $gueltig_bis_error = true;
                                                                }
                                                            } else {
                                                                $gueltig_bis_error = true;
                                                            }
                                                        }

                                                        if (!$gueltig_bis_error) {
                                                            $data = [
                                                                'gueltig_ab' => $gueltig_ab->format("Y-m-d"),
                                                                'gueltig_bis' => $gueltig_bis,
                                                                'kunde_id' => $kunde->getID(),
                                                                'abteilung_id' => $test_abteilung->getID(),
                                                                'preis' => $i['preis'],
                                                                'sonntagszuschlag' => $i['sonntagszuschlag'],
                                                                'feiertagszuschlag' => $i['feiertagszuschlag'],
                                                                'nachtzuschlag' => $i['nachtzuschlag'],
                                                                'nacht_von' => $von->format("H:i") . ":00",
                                                                'nacht_bis' => $bis->format("H:i") . ":00"
                                                            ];
                                                            if ($this->company == 'tps') {
                                                                $data['zeit_pro_palette'] = $save_zeit_pro_palette;
                                                            }

                                                            $kundenkondition_model = \ttact\Models\KundenkonditionModel::createNew($this->db, $data);
                                                            if ($kundenkondition_model instanceof \ttact\Models\KundenkonditionModel) {
                                                                $success = "Die Kundenkondition wurde erfolgreich gespeichert.";
                                                            } else {
                                                                $error = "Beim Speichern der Kundenkondition ist ein technischer Fehler aufgetreten.";
                                                            }
                                                        } else {
                                                            $error .= "Das Datum für Gültig bis ist ungültig. ";
                                                        }
                                                    }
                                                }
                                            } elseif ($i['nacht_von'] == "" && $i['nacht_bis'] != "") {
                                                $error .= "Nacht von: Die Zeitangabe ist ungültig. ";
                                            } elseif ($i['nacht_bis'] == "" && $i['nacht_von'] != "") {
                                                $error .= "Nacht bis: Die Zeitangabe ist ungültig. ";
                                            }
                                        } else {
                                            $error .= "Das Datum für Gültig ab ist ungültig. ";
                                        }
                                    } else {
                                        $error .= "Das Datum für Gültig ab ist ungültig. ";
                                    }
                                }
                            } else {
                                $error .= "Das Datum für Gültig ab ist ungültig. ";
                            }
                        } else {
                            $error .= "Die Abteilung ist ungültig. ";
                        }
                    } else {
                        foreach ($i as $value) {
                            if ($value != "") {
                                $error = "Die mit einem (*) gekennzeichneten Felder dürfen nicht leer sein.";
                                break;
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
                        $this->misc_utils->redirect('kunden', 'bearbeiten', $kunde->getKundennummer(), 'erfolgreich', 'erstellt');
                    }

                    // get data for Abteilung
                    $abteilungsliste = [];
                    $abteilungen = \ttact\Models\AbteilungModel::findAll($this->db);
                    foreach ($abteilungen as $abteilung) {
                        $abteilungsliste[$abteilung->getID()] = [
                            'id' => $abteilung->getID(),
                            'bezeichnung' => $abteilung->getBezeichnung()
                        ];

                        if ($this->company == 'tps') {
                            $abteilungsliste[$abteilung->getID()]['palettenabteilung'] = $abteilung->getPalettenabteilung() ? 'ja' : 'nein';
                        }
                    }
                    $this->smarty_vars['abteilungsliste'] = $abteilungsliste;

                    // fill values into the form
                    $values = [
                        'kundennummer' => $kunde->getKundennummer(),
                        'bezeichnung' => $kunde->getName(),
                        'gueltig_ab' => $i['gueltig_ab'],
                        'gueltig_bis' => $i['gueltig_bis'],
                        'abteilung' => $i['abteilung'] != 0 ? $i['abteilung'] : '',
                        'preis' => $i['preis'] != 0 ? $i['preis'] : '',
                        'sonntagszuschlag' => $i['sonntagszuschlag'] != 0 ? $i['sonntagszuschlag'] : '50',
                        'feiertagszuschlag' => $i['feiertagszuschlag'] != 0 ? $i['feiertagszuschlag'] : '100',
                        'nachtzuschlag' => $i['nachtzuschlag'] != 0 ? $i['nachtzuschlag'] : '25',
                        'nacht_von' => $i['nacht_von'] == '' ? '23:00' : $i['nacht_von'],
                        'nacht_bis' => $i['nacht_bis'] == '' ? '06:00' : $i['nacht_bis']
                    ];
                    if ($this->company == 'tps') {
                        $values['palettenabteilung'] = false;
                        if ($i['abteilung'] != 0) {
                            $abteilung_model = \ttact\Models\AbteilungModel::findByID($this->db, $i['abteilung']);
                            if ($abteilung_model instanceof \ttact\Models\AbteilungModel) {
                                $values['palettenabteilung'] = $abteilung_model->getPalettenabteilung();
                            }
                        } elseif (isset($abteilungen[0])) {
                            if ($abteilungen[0] instanceof \ttact\Models\AbteilungModel) {
                                $values['palettenabteilung'] = $abteilungen[0]->getPalettenabteilung();
                            }
                        }
                        $values['zeit_pro_palette'] = $i['zeit_pro_palette'] == '' ? '01:30' : $i['zeit_pro_palette'];
                    }
                    $this->smarty_vars['values'] = $values;

                    // template settings
                    $this->template = 'main';
                }
            }
        }

        if ($redirect) {
            $this->misc_utils->redirect('kunden', 'index', 'fehler');
        }
    }
}
