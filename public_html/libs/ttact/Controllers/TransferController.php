<?php

namespace ttact\Controllers;

class TransferController extends Controller
{
    public function unterzeichnungsdatumrahmenvertrag()
    {
        $error = "";
        $success = "";
        if (isset($this->params[0])) {
            if ($this->params[0] == "erfolgreich") {
                $success = "Der Import wurde erfolgreich durchgeführt.";
            }
        }

        if (isset($_FILES['datei'])) {
            if ($_FILES["datei"]["error"] > 0) {
                $error = "Beim Hochladen der Datei ist ein Fehler aufgetreten.";
            } else {
                $extension = strtolower(pathinfo($_FILES['datei']['name'], PATHINFO_EXTENSION));
                if ($extension != 'csv') {
                    $error = "Die hochgeladene Datei ist keine .csv-Datei.";
                } elseif ($_FILES['datei']['size'] > 1024000) {
                    $error = "Die hochgeladene Datei darf nicht größer als 1 MB sein.";
                } else {
                    $file = file($_FILES['datei']['tmp_name']);
                    if (count($file) > 0) {
                        foreach ($file as &$row) {
                            $row = utf8_encode($row);
                            $array = explode(';', $row);
                            if (count($array) != 2) {
                                $error = "Etwas stimmt mit der Anzahl der exportierten Spalten nicht.";
                                break;
                            } elseif (!$this->user_input->getOnlyNumbers($array[0]) == $array[0]) {
                                $error = "Etwas stimmt mit der Datei nicht: die Kundennummer beinhaltet auch nichtnumerische Zeichen.";
                                break;
                            } else {
                                foreach ($array as &$col) {
                                    $col = filter_var(trim($col), FILTER_SANITIZE_STRING);
                                }

                                $kunde = \ttact\Models\KundeModel::findByKundennummer($this->db, (int) $this->user_input->getOnlyNumbers($array[0]));
                                if ($kunde instanceof \ttact\Models\KundeModel) {
                                    $datum = \DateTime::createFromFormat('Y-m-d', $array[1]);
                                    if ($datum instanceof \DateTime) {
                                        if (!$kunde->setUnterzeichnungsdatumRahmenvertrag($datum->format('Y-m-d'))) {
                                            $error .= "<kbd>" . $this->user_input->getOnlyNumbers($array[0]) . "</kbd> Beim Speichern ist ein Fehler aufgetreten.<br>";
                                        }
                                    } else {
                                        $error .= "<kbd>" . $this->user_input->getOnlyNumbers($array[0]) . "</kbd> Das Datum ist ungültig.<br>";
                                    }
                                } else {
                                    $error .= "Die Kundennummer <kbd>" . $this->user_input->getOnlyNumbers($array[0]) . "</kbd> existiert nicht in der Datenbank.<br>";
                                }
                            }
                        }
                    } else {
                        $error = "Etwas stimmt mit der Anzahl der exportierten Zeilen nicht.";
                    }
                }
            }
        }

        // display error message
        if ($error != "") {
            $this->smarty_vars['error'] = $error;
        } elseif ($success != "") {
            $this->smarty_vars['success'] = $success;
        }

        $this->template = 'main';
    }

    public function fehlzeiten()
    {
        $aps_db = new \ttact\Database('liluye.han-solo.net', 'aps', 'dJqQQWss2Bj9rzhv', 'aps');

        $kalendereintraege = $aps_db->getRowsQuery("SELECT mitarbeiter_kalender.*, mitarbeiter.Personalnr FROM mitarbeiter_kalender, mitarbeiter WHERE mitarbeiter_kalender.userID = mitarbeiter.Id AND reson = '19' AND date_start LIKE '2017-07%' ORDER BY date_start");
        foreach ($kalendereintraege as $kalendereintrag) {
            $mitarbeiter_model = \ttact\Models\MitarbeiterModel::findByPersonalnummer($this->db, (int) $kalendereintrag['Personalnr']);
            if ($mitarbeiter_model instanceof \ttact\Models\MitarbeiterModel) {
                $aps_von = explode('T', trim($kalendereintrag['date_start']));
                $aps_von_datum = $this->user_input->getOnlyNumbers($aps_von[0]);
                $aps_bis = explode('T', trim($kalendereintrag['date_end']));
                $aps_bis_datum = $this->user_input->getOnlyNumbers($aps_bis[0]);

                $save_von = new \DateTime((int) substr($aps_von_datum, 0, 4) . "-" . (int) substr($aps_von_datum, 4, 2) . "-" . (int) substr($aps_von_datum, 6, 2));
                $save_bis = new \DateTime((int) substr($aps_bis_datum, 0, 4) . "-" . (int) substr($aps_bis_datum, 4, 2) . "-" . (int) substr($aps_bis_datum, 6, 2));

                $data = [
                    'mitarbeiter_id' => $mitarbeiter_model->getID(),
                    'von' => $save_von->format("Y-m-d"),
                    'bis' => $save_bis->format("Y-m-d"),
                    'titel' => trim($kalendereintrag['title']),
                    'type' => 'fehlzeit'
                ];
                $kalendereintrag_model = \ttact\Models\KalendereintragModel::createNew($this->db, $data);
                if (!$kalendereintrag_model instanceof \ttact\Models\KalendereintragModel) {
                    echo "Beim Speichern eines Kalendereintrags ist ein technischer Fehler aufgetreten.<br>";
                }
            } else {
                echo "Die aps-alt.Personalnr " . $kalendereintrag['Personalnr'] . " konnte in ttact.mitarbeiter nicht gefunden werden.<br>";
            }
        }

        $this->template = 'blank';
    }

    public function mitarbeiter()
    {
        $info = "";
        $aps_db = new \ttact\Database('liluye.han-solo.net', 'aps', 'dJqQQWss2Bj9rzhv', 'aps');

        $info .= "Import von <strong>aps.mitarbeiter</strong> zu <strong>ttact.mitarbeiter</strong><br><br>";

        // fetch data from aps-table
        $aps_mitarbeiter = $aps_db->getRows('mitarbeiter', [], [], ['Personalnr']);
        $aps_personalnummern = [];
        foreach ($aps_mitarbeiter as $aps_mitarbeiter_key => $row) {
            $aps_personalnummern[$aps_mitarbeiter_key] = $row['Personalnr'];
        }

        // check aps-table for Personalnummer duplicates
        $aps_doubled_personalnummern = [];
        foreach ($aps_personalnummern as $aps_mitarbeiter_key => $personalnummer) {
            $keys = array_keys($aps_personalnummern, $personalnummer);
            if (count($keys) > 1) {
                foreach ($keys as $key) {
                    $aps_doubled_personalnummern[$key] = $personalnummer;
                    unset($aps_personalnummern[$key]);
                }
            }
        }

        // fetch data from ttact-table
        $ttact_mitarbeiter = \ttact\Models\MitarbeiterModel::findAll($this->db);
        $ttact_personalnummern = [];
        foreach($ttact_mitarbeiter as $ttact_mitarbeiter_key => $mitarbeiter) {
            $ttact_personalnummern[$ttact_mitarbeiter_key] = $mitarbeiter->getPersonalnummer();
        }

        // check for aps.mitarbeiter-Personalnummern that are already existing in ttact.mitarbeiter
        $ttact_already_has_personalnummern = [];
        foreach ($aps_personalnummern as $aps_mitarbeiter_key => $personalnummer) {
            if (in_array($personalnummer, $ttact_personalnummern)) {
                $ttact_already_has_personalnummern[$aps_mitarbeiter_key] = $personalnummer;
                unset($aps_personalnummern[$aps_mitarbeiter_key]);
            }
        }

        // create an array to map aps.abteilung_ids to ttact.abteilung_ids
        $aID_to_id = [];
        $counter = 1;
        $aps_abteilungen = $aps_db->getRows('abteilung', ['name', 'aID'], [], ['aID']);
        foreach ($aps_abteilungen as $row) {
            $aID_to_id[$row['aID']] = $counter;
            $counter++;
        }

        // create an array to map aps.kunden_ids to ttact.kunden_ids
        $kid_to_id = [];
        $aps_kunden = $aps_db->getRows('kunden');
        foreach ($aps_kunden as $row) {
            $k = \ttact\Models\KundeModel::findByKundennummer($this->db, $row['kundennummer']);
            if ($k instanceof \ttact\Models\KundeModel) {
                $kid_to_id[$row['kundenID']] = $k->getID();
            }
        }

        // check if all data is correct
        $aps_incorrect_personalnummern = [];
        $aps_unable_to_save_personalnummern = [];
        $aps_saved_ids = [];
        foreach($aps_personalnummern as $aps_mitarbeiter_key => $value) {
            // 'mitarbeiter'-data
            $personalnummer = $this->user_input->getOnlyNumbers($aps_mitarbeiter[$aps_mitarbeiter_key]['Personalnr']);
            $geschlecht = (($aps_mitarbeiter[$aps_mitarbeiter_key]['Anrede'] == 'Herr') ? 'männlich' : (($aps_mitarbeiter[$aps_mitarbeiter_key]['Anrede'] == 'Frau') ? 'weiblich' : ''));
            $vorname = trim($aps_mitarbeiter[$aps_mitarbeiter_key]['Vorname']);
            $nachname = trim($aps_mitarbeiter[$aps_mitarbeiter_key]['Familienname']);
            $telefon1 = $this->user_input->getOnlyNumbers($aps_mitarbeiter[$aps_mitarbeiter_key]['Telefon1']);
            $telefon2 = $this->user_input->getOnlyNumbers($aps_mitarbeiter[$aps_mitarbeiter_key]['Telefon2']);
            $emailadresse = trim($aps_mitarbeiter[$aps_mitarbeiter_key]['EMail']);

            // 'mitarbeiter_intern'-data
            $mitarbeiter_intern = $aps_db->getFirstRow('mitarbeiter_intern', ['MitarbeiterId' => $aps_mitarbeiter[$aps_mitarbeiter_key]['Id']]);

                $eintritt = trim($mitarbeiter_intern['Eintrittsdatum']); // YYYY-MM-DD // 0000-00-00
                $austritt = trim($mitarbeiter_intern['Austrittsdatum']); // YYYY-MM-DD // 0000-00-00
                $befristung = trim($mitarbeiter_intern['Befristung']); // YYYY-MM-DD // 0000-00-00
                $befristung1 = trim($mitarbeiter_intern['befristung_1']); // DD.MM.YYYY or YYYY-MM-DD // '' or 0000-00-00
                $befristung2 = trim($mitarbeiter_intern['befristung_2']); // DD.MM.YYYY or YYYY-MM-DD // '' or 0000-00-00
                $befristung3 = trim($mitarbeiter_intern['befristung_3']); // DD.MM.YYYY or YYYY-MM-DD // '' or 0000-00-00

                $obj_eintritt = \DateTime::createFromFormat('Y-m-d', $eintritt);
                $obj_austritt = \DateTime::createFromFormat('Y-m-d', $austritt);
                $obj_befristung = \DateTime::createFromFormat('Y-m-d', $befristung);
                $obj_befristung1 = null;
                $obj_befristung2 = null;
                $obj_befristung3 = null;
                if ($befristung1 != '' && $befristung1 != '0000-00-00') {
                    $obj_befristung1_dmy = \DateTime::createFromFormat('d.m.Y', $befristung1);
                    $obj_befristung1_ymd = \DateTime::createFromFormat('Y-m-d', $befristung1);

                    if (($obj_befristung1_dmy instanceof \DateTime) && $obj_befristung1_dmy->format('d.m.Y') == $befristung1) {
                        $obj_befristung1 = $obj_befristung1_dmy;
                    } elseif (($obj_befristung1_ymd instanceof \DateTime) && $obj_befristung1_ymd->format('Y-m-d') == $befristung1) {
                        $obj_befristung1 = $obj_befristung1_ymd;
                    }
                }
                if ($befristung2 != '' && $befristung2 != '0000-00-00') {
                    $obj_befristung2_dmy = \DateTime::createFromFormat('d.m.Y', $befristung2);
                    $obj_befristung2_ymd = \DateTime::createFromFormat('Y-m-d', $befristung2);

                    if (($obj_befristung2_dmy instanceof \DateTime) && $obj_befristung2_dmy->format('d.m.Y') == $befristung2) {
                        $obj_befristung2 = $obj_befristung2_dmy;
                    } elseif (($obj_befristung2_ymd instanceof \DateTime) && $obj_befristung2_ymd->format('Y-m-d') == $befristung2) {
                        $obj_befristung2 = $obj_befristung2_ymd;
                    }
                }
                if ($befristung3 != '' && $befristung3 != '0000-00-00') {
                    $obj_befristung3_dmy = \DateTime::createFromFormat('d.m.Y', $befristung3);
                    $obj_befristung3_ymd = \DateTime::createFromFormat('Y-m-d', $befristung3);

                    if (($obj_befristung3_dmy instanceof \DateTime) && $obj_befristung3_dmy->format('d.m.Y') == $befristung3) {
                        $obj_befristung3 = $obj_befristung3_dmy;
                    } elseif (($obj_befristung3_ymd instanceof \DateTime) && $obj_befristung3_ymd->format('Y-m-d') == $befristung3) {
                        $obj_befristung3 = $obj_befristung3_ymd;
                    }
                }

                $montag_von = \DateTime::createFromFormat('H:i:s', trim($mitarbeiter_intern['Mo_von']));
                $montag_bis = \DateTime::createFromFormat('H:i:s', trim($mitarbeiter_intern['Mo_bis']));
                $dienstag_von = \DateTime::createFromFormat('H:i:s', trim($mitarbeiter_intern['Di_von']));
                $dienstag_bis = \DateTime::createFromFormat('H:i:s', trim($mitarbeiter_intern['Di_bis']));
                $mittwoch_von = \DateTime::createFromFormat('H:i:s', trim($mitarbeiter_intern['Mi_von']));
                $mittwoch_bis = \DateTime::createFromFormat('H:i:s', trim($mitarbeiter_intern['Mi_bis']));
                $donnerstag_von = \DateTime::createFromFormat('H:i:s', trim($mitarbeiter_intern['Do_von']));
                $donnerstag_bis = \DateTime::createFromFormat('H:i:s', trim($mitarbeiter_intern['Do_bis']));
                $freitag_von = \DateTime::createFromFormat('H:i:s', trim($mitarbeiter_intern['Fr_von']));
                $freitag_bis = \DateTime::createFromFormat('H:i:s', trim($mitarbeiter_intern['Fr_bis']));
                $samstag_von = \DateTime::createFromFormat('H:i:s', trim($mitarbeiter_intern['Sa_von']));
                $samstag_bis = \DateTime::createFromFormat('H:i:s', trim($mitarbeiter_intern['Sa_bis']));
                $sonntag_von = \DateTime::createFromFormat('H:i:s', trim($mitarbeiter_intern['So_von']));
                $sonntag_bis = \DateTime::createFromFormat('H:i:s', trim($mitarbeiter_intern['So_bis']));

            // 'ZuteilungPersonal'-data
            $zuordnung_personal = $aps_db->getFirstRow('ZuteilungPersonal', ['Nummer' => $personalnummer]);

                $kunde = '';
                $kunde_exists = false;
                $kunde_model = null;
                $kunde_id_ttact = '';
                $abteilungen = [];
                if (isset($zuordnung_personal['Nummer'])) {
                    // Kunde, dessen Stammmitarbeiter dieser Mitarbeiter ist
                    $kunde = $this->user_input->getOnlyNumbers($zuordnung_personal['Kunde']);
                    if ($kunde != '' && $kunde != 0) {
                        $kunde_exists = true;
                        if (isset($kid_to_id[$kunde])) {
                            $kunde_id_ttact = $kid_to_id[$kunde];
                            $kunde_model = \ttact\Models\KundeModel::findByID($this->db, $kunde_id_ttact);
                        }
                    }

                    // Abteilungsfreigaben
                    $abteilungen[1]['aID'] = $this->user_input->getOnlyNumbers($zuordnung_personal['Abteilung1']);
                    $abteilungen[1]['exists'] = false;
                    $abteilungen[1]['ttact_id'] = '';
                    $abteilungen[1]['model'] = null;

                    $abteilungen[2]['aID'] = $this->user_input->getOnlyNumbers($zuordnung_personal['Abteilung2']);
                    $abteilungen[2]['exists'] = false;
                    $abteilungen[2]['ttact_id'] = '';
                    $abteilungen[2]['model'] = null;

                    $abteilungen[3]['aID'] = $this->user_input->getOnlyNumbers($zuordnung_personal['Abteilung3']);
                    $abteilungen[3]['exists'] = false;
                    $abteilungen[3]['ttact_id'] = '';
                    $abteilungen[3]['model'] = null;

                    $abteilungen[4]['aID'] = $this->user_input->getOnlyNumbers($zuordnung_personal['Abteilung4']);
                    $abteilungen[4]['exists'] = false;
                    $abteilungen[4]['ttact_id'] = '';
                    $abteilungen[4]['model'] = null;

                    $abteilungen[5]['aID'] = $this->user_input->getOnlyNumbers($zuordnung_personal['Abteilung5']);
                    $abteilungen[5]['exists'] = false;
                    $abteilungen[5]['ttact_id'] = '';
                    $abteilungen[5]['model'] = null;

                    $abteilungen[6]['aID'] = $this->user_input->getOnlyNumbers($zuordnung_personal['Abteilung6']);
                    $abteilungen[6]['exists'] = false;
                    $abteilungen[6]['ttact_id'] = '';
                    $abteilungen[6]['model'] = null;

                    foreach ($abteilungen as &$abteilung) {
                        if ($abteilung['aID'] != 0 && $abteilung['aID'] != '') {
                            $abteilung['exists'] = true;
                            if (isset($aID_to_id[$abteilung['aID']])) {
                                $abteilung['ttact_id'] = $aID_to_id[$abteilung['aID']];
                                $abteilung['model'] = \ttact\Models\AbteilungModel::findByID($this->db, $abteilung['ttact_id']);
                            }
                        }
                    }
                }

            // let the fun begin
            if ($personalnummer == "") {
                $aps_incorrect_personalnummern[$aps_mitarbeiter_key] = "Die Personalnummer ist ungültig.";
                unset($aps_personalnummern[$aps_mitarbeiter_key]);
            } elseif ($geschlecht == "") {
                $aps_incorrect_personalnummern[$aps_mitarbeiter_key] = "Es ist keine Anrede vorhanden.";
                unset($aps_personalnummern[$aps_mitarbeiter_key]);
            } elseif ($vorname == "") {
                $aps_incorrect_personalnummern[$aps_mitarbeiter_key] = "Es ist kein Vorname vorhanden.";
                unset($aps_personalnummern[$aps_mitarbeiter_key]);
            } elseif ($nachname == "") {
                $aps_incorrect_personalnummern[$aps_mitarbeiter_key] = "Es ist kein Nachname vorhanden.";
                unset($aps_personalnummern[$aps_mitarbeiter_key]);
            } elseif (($emailadresse != "") && !$this->user_input->isEmailadresse($emailadresse)) {
                $aps_incorrect_personalnummern[$aps_mitarbeiter_key] = "Die E-Mail-Adresse ist ungültig.";
                unset($aps_personalnummern[$aps_mitarbeiter_key]);
            } elseif ($austritt != '0000-00-00' && (!($obj_austritt instanceof \DateTime) || $austritt != $obj_austritt->format('Y-m-d'))) {
                $aps_incorrect_personalnummern[$aps_mitarbeiter_key] = 'Unter dem Punkt "Sozialversicherung": Das Datum im Feld "Austrittsdatum" ist ungültig.';
                unset($aps_personalnummern[$aps_mitarbeiter_key]);
            } elseif ($eintritt != '0000-00-00' && (!($obj_eintritt instanceof \DateTime) || $eintritt != $obj_eintritt->format('Y-m-d'))) {
                $aps_incorrect_personalnummern[$aps_mitarbeiter_key] = 'Unter dem Punkt "Sozialversicherung": Das Datum im Feld "Eintrittsdatum" ist ungültig.';
                unset($aps_personalnummern[$aps_mitarbeiter_key]);
            } elseif ($befristung != '0000-00-00' && (!($obj_befristung instanceof \DateTime) || $befristung != $obj_befristung->format('Y-m-d'))) {
                $aps_incorrect_personalnummern[$aps_mitarbeiter_key] = 'Unter dem Punkt "Sozialversicherung": Das Datum im Feld "Befristung" ist ungültig.';
                unset($aps_personalnummern[$aps_mitarbeiter_key]);
            } elseif ($befristung1 != '' && $befristung1 != '0000-00-00' && !($obj_befristung1 instanceof \DateTime)) {
                $aps_incorrect_personalnummern[$aps_mitarbeiter_key] = 'Unter dem Punkt "Sozialversicherung": Das Datum im Feld "1. Befristung" ist ungültig.';
                unset($aps_personalnummern[$aps_mitarbeiter_key]);
            } elseif ($befristung2 != '' && $befristung2 != '0000-00-00' && !($obj_befristung2 instanceof \DateTime)) {
                $aps_incorrect_personalnummern[$aps_mitarbeiter_key] = 'Unter dem Punkt "Sozialversicherung": Das Datum im Feld "2. Befristung" ist ungültig.';
                unset($aps_personalnummern[$aps_mitarbeiter_key]);
            } elseif ($befristung3 != '' && $befristung3 != '0000-00-00' && !($obj_befristung3 instanceof \DateTime)) {
                $aps_incorrect_personalnummern[$aps_mitarbeiter_key] = 'Unter dem Punkt "Sozialversicherung": Das Datum im Feld "3. Befristung" ist ungültig.';
                unset($aps_personalnummern[$aps_mitarbeiter_key]);
            } elseif ($austritt != '0000-00-00' && $eintritt != '0000-00-00' && ($obj_eintritt > $obj_austritt)) {
                $aps_incorrect_personalnummern[$aps_mitarbeiter_key] = 'Unter dem Punkt "Sozialversicherung": Das Austrittsdatum liegt nach dem Eintrittsdatum.';
                unset($aps_personalnummern[$aps_mitarbeiter_key]);
            } elseif ($befristung1 == '' && $befristung2 != '') {
                $aps_incorrect_personalnummern[$aps_mitarbeiter_key] = 'Unter dem Punkt "Sozialversicherung": Es ist eine 2. Befristung eingegeben, obwohl es keine 1. Befristung gibt.';
                unset($aps_personalnummern[$aps_mitarbeiter_key]);
            } elseif ($befristung2 == '' && $befristung3 != '') {
                $aps_incorrect_personalnummern[$aps_mitarbeiter_key] = 'Unter dem Punkt "Sozialversicherung": Es ist eine 3. Befristung eingegeben, obwohl es keine 2. Befristung gibt.';
                unset($aps_personalnummern[$aps_mitarbeiter_key]);
            } elseif (isset($zuordnung_personal['Nummer']) && $kunde_exists && $kunde_id_ttact == '') {
                $aps_incorrect_personalnummern[$aps_mitarbeiter_key] = 'Unter dem Punkt "Anforderung": Der Kunde, zu dessen Stammbelegschaft dieser Mitarbeiter gehören soll, existiert nicht.';
                unset($aps_personalnummern[$aps_mitarbeiter_key]);
            } elseif (isset($zuordnung_personal['Nummer']) && $kunde_exists && !($kunde_model instanceof \ttact\Models\KundeModel)) {
                $aps_incorrect_personalnummern[$aps_mitarbeiter_key] = 'Es ist ein Fehler aufgetreten. Fehlercode <strong style="color:red;">1</strong>.';
                unset($aps_personalnummern[$aps_mitarbeiter_key]);
            } elseif (isset($zuordnung_personal['Nummer']) && $abteilungen[1]['exists'] && $abteilungen[1]['ttact_id'] == '') {
                $aps_incorrect_personalnummern[$aps_mitarbeiter_key] = 'Unter dem Punkt "Anforderung": Die Abteilung für "Abteilung 1" existiert nicht.';
                unset($aps_personalnummern[$aps_mitarbeiter_key]);
            } elseif (isset($zuordnung_personal['Nummer']) && $abteilungen[1]['exists'] && !($abteilungen[1]['model'] instanceof \ttact\Models\AbteilungModel)) {
                $aps_incorrect_personalnummern[$aps_mitarbeiter_key] = 'Es ist ein Fehler aufgetreten. Fehlercode <strong style="color:red;">2</strong>.';
                unset($aps_personalnummern[$aps_mitarbeiter_key]);
            } elseif (isset($zuordnung_personal['Nummer']) && $abteilungen[2]['exists'] && $abteilungen[2]['ttact_id'] == '') {
                $aps_incorrect_personalnummern[$aps_mitarbeiter_key] = 'Unter dem Punkt "Anforderung": Die Abteilung für "Abteilung 2" existiert nicht.';
                unset($aps_personalnummern[$aps_mitarbeiter_key]);
            } elseif (isset($zuordnung_personal['Nummer']) && $abteilungen[2]['exists'] && !($abteilungen[2]['model'] instanceof \ttact\Models\AbteilungModel)) {
                $aps_incorrect_personalnummern[$aps_mitarbeiter_key] = 'Es ist ein Fehler aufgetreten. Fehlercode <strong style="color:red;">3</strong>.';
                unset($aps_personalnummern[$aps_mitarbeiter_key]);
            } elseif (isset($zuordnung_personal['Nummer']) && $abteilungen[3]['exists'] && $abteilungen[3]['ttact_id'] == '') {
                $aps_incorrect_personalnummern[$aps_mitarbeiter_key] = 'Unter dem Punkt "Anforderung": Die Abteilung für "Abteilung 3" existiert nicht.';
                unset($aps_personalnummern[$aps_mitarbeiter_key]);
            } elseif (isset($zuordnung_personal['Nummer']) && $abteilungen[3]['exists'] && !($abteilungen[3]['model'] instanceof \ttact\Models\AbteilungModel)) {
                $aps_incorrect_personalnummern[$aps_mitarbeiter_key] = 'Es ist ein Fehler aufgetreten. Fehlercode <strong style="color:red;">4</strong>.';
                unset($aps_personalnummern[$aps_mitarbeiter_key]);
            } elseif (isset($zuordnung_personal['Nummer']) && $abteilungen[4]['exists'] && $abteilungen[4]['ttact_id'] == '') {
                $aps_incorrect_personalnummern[$aps_mitarbeiter_key] = 'Unter dem Punkt "Anforderung": Die Abteilung für "Abteilung 4" existiert nicht.';
                unset($aps_personalnummern[$aps_mitarbeiter_key]);
            } elseif (isset($zuordnung_personal['Nummer']) && $abteilungen[4]['exists'] && !($abteilungen[4]['model'] instanceof \ttact\Models\AbteilungModel)) {
                $aps_incorrect_personalnummern[$aps_mitarbeiter_key] = 'Es ist ein Fehler aufgetreten. Fehlercode <strong style="color:red;">5</strong>.';
                unset($aps_personalnummern[$aps_mitarbeiter_key]);
            } elseif (isset($zuordnung_personal['Nummer']) && $abteilungen[5]['exists'] && $abteilungen[5]['ttact_id'] == '') {
                $aps_incorrect_personalnummern[$aps_mitarbeiter_key] = 'Unter dem Punkt "Anforderung": Die Abteilung für "Abteilung 5" existiert nicht.';
                unset($aps_personalnummern[$aps_mitarbeiter_key]);
            } elseif (isset($zuordnung_personal['Nummer']) && $abteilungen[5]['exists'] && !($abteilungen[5]['model'] instanceof \ttact\Models\AbteilungModel)) {
                $aps_incorrect_personalnummern[$aps_mitarbeiter_key] = 'Es ist ein Fehler aufgetreten. Fehlercode <strong style="color:red;">6</strong>.';
                unset($aps_personalnummern[$aps_mitarbeiter_key]);
            } elseif (isset($zuordnung_personal['Nummer']) && $abteilungen[6]['exists'] && $abteilungen[6]['ttact_id'] == '') {
                $aps_incorrect_personalnummern[$aps_mitarbeiter_key] = 'Unter dem Punkt "Anforderung": Die Abteilung für "Abteilung 6" existiert nicht.';
                unset($aps_personalnummern[$aps_mitarbeiter_key]);
            } elseif (isset($zuordnung_personal['Nummer']) && $abteilungen[6]['exists'] && !($abteilungen[6]['model'] instanceof \ttact\Models\AbteilungModel)) {
                $aps_incorrect_personalnummern[$aps_mitarbeiter_key] = 'Es ist ein Fehler aufgetreten. Fehlercode <strong style="color:red;">7</strong>.';
                unset($aps_personalnummern[$aps_mitarbeiter_key]);
            } elseif (isset($zuordnung_personal['Nummer']) && $zuordnung_personal['EinGrup'] != '' && $zuordnung_personal['EinGrup'] != 'E1' && $zuordnung_personal['EinGrup'] != 'E2' && $zuordnung_personal['EinGrup'] != null) {
                $aps_incorrect_personalnummern[$aps_mitarbeiter_key] = 'Unter dem Punkt "Anforderung": Der Tarif im Feld "EinGrup" ist ungültig.';
                unset($aps_personalnummern[$aps_mitarbeiter_key]);
            } else {
                if ($eintritt == '0000-00-00' && $austritt != '0000-00-00') {
                    $eintritt = $austritt;
                }

                if ($befristung1 != '' && $befristung1 != '0000-00-00') {
                    $befristung1 = $obj_befristung1->format("Y-m-d");
                } else {
                    $befristung1 = '0000-00-00';
                }

                if ($befristung2 != '' && $befristung2 != '0000-00-00') {
                    $befristung2 = $obj_befristung2->format("Y-m-d");
                } else {
                    $befristung2 = '0000-00-00';
                }

                if ($befristung3 != '' && $befristung3 != '0000-00-00') {
                    $befristung3 = $obj_befristung3->format("Y-m-d");
                } else {
                    $befristung3 = '0000-00-00';
                }

                // save Mitarbeiter
                $data = [
                    'personalnummer'    => $personalnummer,
                    'geschlecht'        => $geschlecht,
                    'vorname'           => $vorname,
                    'nachname'          => $nachname,
                    'telefon1'          => $telefon1,
                    'telefon2'          => $telefon2,
                    'emailadresse'      => $emailadresse,
                    'eintritt'          => $eintritt,
                    'austritt'          => $austritt,
                    'befristung'        => $befristung,
                    'befristung1'       => $befristung1,
                    'befristung2'       => $befristung2,
                    'befristung3'       => $befristung3,
                    'montag_von'        => $montag_von->format("H:i:s"),
                    'montag_bis'        => $montag_bis->format("H:i:s"),
                    'dienstag_von'      => $dienstag_von->format("H:i:s"),
                    'dienstag_bis'      => $dienstag_bis->format("H:i:s"),
                    'mittwoch_von'      => $mittwoch_von->format("H:i:s"),
                    'mittwoch_bis'      => $mittwoch_bis->format("H:i:s"),
                    'donnerstag_von'    => $donnerstag_von->format("H:i:s"),
                    'donnerstag_bis'    => $donnerstag_bis->format("H:i:s"),
                    'freitag_von'       => $freitag_von->format("H:i:s"),
                    'freitag_bis'       => $freitag_bis->format("H:i:s"),
                    'samstag_von'       => $samstag_von->format("H:i:s"),
                    'samstag_bis'       => $samstag_bis->format("H:i:s"),
                    'sonntag_von'       => $sonntag_von->format("H:i:s"),
                    'sonntag_bis'       => $sonntag_bis->format("H:i:s"),
                    'notizen_allgemein' => trim($mitarbeiter_intern['manote']),
                    'notizen_januar'    => trim($mitarbeiter_intern['manote01']),
                    'notizen_februar'   => trim($mitarbeiter_intern['manote02']),
                    'notizen_maerz'     => trim($mitarbeiter_intern['manote03']),
                    'notizen_april'     => trim($mitarbeiter_intern['manote04']),
                    'notizen_mai'       => trim($mitarbeiter_intern['manote05']),
                    'notizen_juni'      => trim($mitarbeiter_intern['manote06']),
                    'notizen_juli'      => trim($mitarbeiter_intern['manote07']),
                    'notizen_august'    => trim($mitarbeiter_intern['manote08']),
                    'notizen_september' => trim($mitarbeiter_intern['manote09']),
                    'notizen_oktober'   => trim($mitarbeiter_intern['manote10']),
                    'notizen_november'  => trim($mitarbeiter_intern['manote11']),
                    'notizen_dezember'  => trim($mitarbeiter_intern['manote12'])
                ];
                $mitarbeiter_model = \ttact\Models\MitarbeiterModel::createNew($this->db, $data);
                if ($mitarbeiter_model instanceof \ttact\Models\MitarbeiterModel) {
                    $aps_saved_ids[$aps_mitarbeiter_key] = $mitarbeiter_model->getID();
                    if (isset($zuordnung_personal['Nummer'])) {
                        // save Mitarbeiterfilter type = stamm
                        $save_error = false;
                        $mitarbeiterfilter_model = null;
                        if ($kunde_model instanceof \ttact\Models\KundeModel) {
                            $data = [
                                'type' => 'stamm',
                                'kunde_id' => $kunde_model->getID(),
                                'mitarbeiter_id' => $mitarbeiter_model->getID()
                            ];
                            $mitarbeiterfilter_model = \ttact\Models\MitarbeiterfilterModel::createNew($this->db, $data);
                            if (!$mitarbeiterfilter_model instanceof \ttact\Models\MitarbeiterfilterModel) {
                                $save_error = true;
                                $this->db->delete('mitarbeiter', $mitarbeiter_model->getID());
                                $aps_unable_to_save_personalnummern[$aps_mitarbeiter_key] = $personalnummer;
                                unset($aps_personalnummern[$aps_mitarbeiter_key]);
                            }
                        }

                        // save Abteilungsfreigaben
                        if (!$save_error) {
                            $abteilungsfreigabe_ids = [];
                            $saved_abteilungen = [];
                            foreach ($abteilungen as &$abteilung) {
                                if ($abteilung['model'] instanceof \ttact\Models\AbteilungModel) {
                                    if (!in_array($abteilung['model']->getID(), $saved_abteilungen)) {
                                        $data = [
                                            'mitarbeiter_id' => $mitarbeiter_model->getID(),
                                            'abteilung_id' => $abteilung['model']->getID()
                                        ];
                                        $abteilungsfreigabe_model = \ttact\Models\AbteilungsfreigabeModel::createNew($this->db, $data);
                                        if (!$abteilungsfreigabe_model instanceof \ttact\Models\AbteilungsfreigabeModel) {
                                            $save_error = true;
                                            $this->db->delete('mitarbeiter', $mitarbeiter_model->getID());
                                            if ($mitarbeiterfilter_model instanceof \ttact\Models\MitarbeiterfilterModel) {
                                                $this->db->delete('mitarbeiterfilter', $mitarbeiterfilter_model->getID());
                                            }
                                            $aps_unable_to_save_personalnummern[$aps_mitarbeiter_key] = $personalnummer;
                                            unset($aps_personalnummern[$aps_mitarbeiter_key]);
                                            foreach ($abteilungsfreigabe_ids as $id) {
                                                $this->db->delete('abteilungsfreigabe', $id);
                                            }
                                            break;
                                        } else {
                                            $abteilungsfreigabe_ids[] = $abteilungsfreigabe_model->getID();
                                            $saved_abteilungen[] = $abteilungsfreigabe_model->getAbteilung()->getID();
                                        }
                                    }
                                }
                            }
                        }

                        // save Lohnkonfigurationen
                        if (!$save_error) {
                            $lohnkonfiguration_ids = [];

                            $gueltig_ab = '0000-00-00';

                            $tarif_id = '';
                            $tarifgruppe = $zuordnung_personal['EinGrup'];
                            if ($tarifgruppe == 'E1') {
                                $tarif_id = 1;
                            } elseif ($tarifgruppe == 'E2') {
                                $tarif_id = 2;
                            }

                            $wochenstunden = $zuordnung_personal['WSdt'];
                            if ($wochenstunden == null) {
                                $wochenstunden = 0;
                            } elseif (strtolower($wochenstunden) == 'null') {
                                $wochenstunden = 0;
                            } elseif (strtolower($wochenstunden) == 'mini') {
                                $wochenstunden = 11.5;
                            } elseif ($wochenstunden == '') {
                                $wochenstunden = 0;
                            }

                            $soll_lohn = $zuordnung_personal['Lohn'];
                            if ($soll_lohn == '') {
                                $soll_lohn = 0;
                            } elseif (strtolower($soll_lohn) == 'nan') {
                                $soll_lohn = 0;
                            }

                            $data = [
                                'gueltig_ab'        => $gueltig_ab,
                                'mitarbeiter_id'    => $mitarbeiter_model->getID(),
                                'tarif_id'          => $tarif_id,
                                'wochenstunden'     => $wochenstunden,
                                'soll_lohn'         => $soll_lohn
                            ];
                            $lohnkonfiguration = \ttact\Models\LohnkonfigurationModel::createNew($this->db, $data);
                            if ($lohnkonfiguration instanceof \ttact\Models\LohnkonfigurationModel) {
                                $inhalt_encoded = $zuordnung_personal['inhalt'];

                                $inhalt_decoded = '';
                                $inhalt_array = [];
                                if ($inhalt_encoded != '') {
                                    $inhalt_decoded = base64_decode($inhalt_encoded);
                                    $inhalt_array = unserialize($inhalt_decoded);
                                    if (count($inhalt_array) > 0) {
                                        $saved_lohnkonfigurationen = ['0000-00-00'];
                                        foreach ($inhalt_array as $row) {
                                            $gueltig_ab = '0000-00-00';
                                            if (is_integer($row['abdate'])) {
                                                $d = new \DateTime();
                                                $d->setTimestamp($row['abdate']);
                                                $gueltig_ab = $d->format('Y-m-d');
                                            }
                                            if (!in_array($gueltig_ab, $saved_lohnkonfigurationen)) {
                                                $tarif_id = '';
                                                $tarifgruppe = $row['EinGrup'];
                                                if ($tarifgruppe == 'E1') {
                                                    $tarif_id = 1;
                                                } elseif ($tarifgruppe == 'E2') {
                                                    $tarif_id = 2;
                                                }

                                                $wochenstunden = 0;
                                                if (isset($row['WSdt'])) {
                                                    $wochenstunden = $row['WSdt'];
                                                    if ($wochenstunden == null) {
                                                        $wochenstunden = 0;
                                                    } elseif (strtolower($wochenstunden) == 'null') {
                                                        $wochenstunden = 0;
                                                    } elseif (strtolower($wochenstunden) == 'mini') {
                                                        $wochenstunden = 11.5;
                                                    } elseif ($wochenstunden == '') {
                                                        $wochenstunden = 0;
                                                    }
                                                }

                                                $soll_lohn = $row['Lohn'];
                                                if ($soll_lohn == '') {
                                                    $soll_lohn = 0;
                                                } elseif (strtolower($soll_lohn) == 'nan') {
                                                    $soll_lohn = 0;
                                                }

                                                $data = [
                                                    'gueltig_ab'        => $gueltig_ab,
                                                    'mitarbeiter_id'    => $mitarbeiter_model->getID(),
                                                    'tarif_id'          => $tarif_id,
                                                    'wochenstunden'     => $wochenstunden,
                                                    'soll_lohn'         => $soll_lohn
                                                ];
                                                $lk_model = \ttact\Models\LohnkonfigurationModel::createNew($this->db, $data);
                                                if ($lk_model instanceof \ttact\Models\LohnkonfigurationModel) {
                                                    $lohnkonfiguration_ids[] = $lk_model->getID();
                                                    $saved_lohnkonfigurationen[] = $gueltig_ab;
                                                } else {
                                                    $save_error = true;
                                                    $this->db->delete('mitarbeiter', $mitarbeiter_model->getID());
                                                    if ($mitarbeiterfilter_model instanceof \ttact\Models\MitarbeiterfilterModel) {
                                                        $this->db->delete('mitarbeiterfilter', $mitarbeiterfilter_model->getID());
                                                    }
                                                    $aps_unable_to_save_personalnummern[$aps_mitarbeiter_key] = $personalnummer;
                                                    unset($aps_personalnummern[$aps_mitarbeiter_key]);
                                                    foreach ($abteilungsfreigabe_ids as $id) {
                                                        $this->db->delete('abteilungsfreigabe', $id);
                                                    }
                                                    foreach ($lohnkonfiguration_ids as $id) {
                                                        $this->db->delete('lohnkonfiguration', $id);
                                                    }
                                                    break;
                                                }
                                            }
                                        }
                                    }
                                }
                            } else {
                                $save_error = true;
                                $this->db->delete('mitarbeiter', $mitarbeiter_model->getID());
                                if ($mitarbeiterfilter_model instanceof \ttact\Models\MitarbeiterfilterModel) {
                                    $this->db->delete('mitarbeiterfilter', $mitarbeiterfilter_model->getID());
                                }
                                $aps_unable_to_save_personalnummern[$aps_mitarbeiter_key] = $personalnummer;
                                unset($aps_personalnummern[$aps_mitarbeiter_key]);
                                foreach ($abteilungsfreigabe_ids as $id) {
                                    $this->db->delete('abteilungsfreigabe', $id);
                                }
                                break;
                            }
                        }

                        // save Mitarbeiterfilter type = sperre
                        if (!$save_error) {
                            $mitarbeiterfilter_ids = [];
                            $saved_marktsperren = [];
                            $marktsperren = $aps_db->getRows('marktsperren', [], ['mitarbeiter' => $this->user_input->getOnlyNumbers($aps_mitarbeiter[$aps_mitarbeiter_key]['Id'])]);
                            if (count($marktsperren) > 0) {
                                foreach ($marktsperren as $marktsperre) {
                                    $kunden_id = $this->user_input->getOnlyNumbers($marktsperre['kundenID']);
                                    if (isset($kid_to_id[$kunden_id])) {
                                        $kunde = \ttact\Models\KundeModel::findByID($this->db, $kid_to_id[$kunden_id]);
                                        if ($kunde instanceof \ttact\Models\KundeModel) {
                                            if (!in_array($kunde->getID(), $saved_marktsperren)) {
                                                $data = [
                                                    'type' => 'sperre',
                                                    'kunde_id' => $kunde->getID(),
                                                    'mitarbeiter_id' => $mitarbeiter_model->getID()
                                                ];
                                                $sperre_model = \ttact\Models\MitarbeiterfilterModel::createNew($this->db, $data);
                                                if (!$sperre_model instanceof \ttact\Models\MitarbeiterfilterModel) {
                                                    $save_error = true;
                                                    $this->db->delete('mitarbeiter', $mitarbeiter_model->getID());
                                                    if ($mitarbeiterfilter_model instanceof \ttact\Models\MitarbeiterfilterModel) {
                                                        $this->db->delete('mitarbeiterfilter', $mitarbeiterfilter_model->getID());
                                                    }
                                                    $aps_unable_to_save_personalnummern[$aps_mitarbeiter_key] = $personalnummer;
                                                    unset($aps_personalnummern[$aps_mitarbeiter_key]);
                                                    foreach ($abteilungsfreigabe_ids as $id) {
                                                        $this->db->delete('abteilungsfreigabe', $id);
                                                    }
                                                    foreach ($lohnkonfiguration_ids as $id) {
                                                        $this->db->delete('lohnkonfiguration', $id);
                                                    }
                                                    foreach ($mitarbeiterfilter_ids as $id) {
                                                        $this->db->delete('mitarbeiterfilter', $id);
                                                    }
                                                    break;
                                                } else {
                                                    $mitarbeiterfilter_ids[] = $sperre_model->getID();
                                                    $saved_marktsperren[] = $sperre_model->getKunde()->getID();
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        // save Lohnbuchungen
                        if (!$save_error) {
                            $lohnbuchung_ids = [];
                            $sonderbuchungen = $aps_db->getRows('sonderbuchungen', [], ['ma' => $this->user_input->getOnlyNumbers($aps_mitarbeiter[$aps_mitarbeiter_key]['Id'])], ['jahr', 'monat']);
                            if (count($sonderbuchungen) > 0) {
                                foreach ($sonderbuchungen as $sonderbuchung) {
                                    $lohnart = (int) $this->user_input->getOnlyNumbers($sonderbuchung['Lohnart']);
                                    $wert = (float) str_replace(",", ".", $sonderbuchung['wert']);
                                    $faktor = '';
                                    if ($sonderbuchung['faktor'] != '') {
                                        $faktor = (float) str_replace(",", ".", $sonderbuchung['faktor']);
                                    }
                                    $user_id = 1;
                                    $datum = new \DateTime("now");
                                    $year = (int) $this->user_input->getOnlyNumbers($sonderbuchung['jahr']);
                                    $month = (int) $this->user_input->getOnlyNumbers($sonderbuchung['monat']);
                                    $datum->setDate($year, $month, 1);

                                    $data = [
                                        'mitarbeiter_id' => $mitarbeiter_model->getID(),
                                        'datum' => $datum->format("Y-m") . "-01",
                                        'lohnart' => $lohnart,
                                        'wert' => $wert,
                                        'faktor' => $faktor,
                                        'user_id' => $user_id,
                                        'bezeichnung' => trim($sonderbuchung['bz'])
                                    ];
                                    $lohnbuchung_model = \ttact\Models\LohnbuchungModel::createNew($this->db, $data);
                                    if (!$lohnbuchung_model instanceof \ttact\Models\LohnbuchungModel) {
                                        $save_error = true;
                                        $this->db->delete('mitarbeiter', $mitarbeiter_model->getID());
                                        if ($mitarbeiterfilter_model instanceof \ttact\Models\MitarbeiterfilterModel) {
                                            $this->db->delete('mitarbeiterfilter', $mitarbeiterfilter_model->getID());
                                        }
                                        $aps_unable_to_save_personalnummern[$aps_mitarbeiter_key] = $personalnummer;
                                        unset($aps_personalnummern[$aps_mitarbeiter_key]);
                                        foreach ($abteilungsfreigabe_ids as $id) {
                                            $this->db->delete('abteilungsfreigabe', $id);
                                        }
                                        foreach ($lohnkonfiguration_ids as $id) {
                                            $this->db->delete('lohnkonfiguration', $id);
                                        }
                                        foreach ($mitarbeiterfilter_ids as $id) {
                                            $this->db->delete('mitarbeiterfilter', $id);
                                        }
                                        foreach ($lohnbuchung_ids as $id) {
                                            $this->db->delete('lohnbuchung', $id);
                                        }
                                        break;
                                    } else {
                                        $lohnbuchung_ids[] = $lohnbuchung_model->getID();
                                    }
                                }
                            }
                        }

                        // save Kalendereinträge
                        if (!$save_error) {
                            $kalendereintrag_ids = [];
                            $kalendereintraege = $aps_db->getRows('mitarbeiter_kalender', [], ['userID' => $this->user_input->getOnlyNumbers($aps_mitarbeiter[$aps_mitarbeiter_key]['Id'])], ['date_start']);
                            if (count($kalendereintraege) > 0) {
                                foreach ($kalendereintraege as $kalendereintrag) {
                                    $save_date = '0000-00-00';

                                    $aps_von = explode('T', trim($kalendereintrag['date_start']));
                                    $aps_von_datum = $this->user_input->getOnlyNumbers($aps_von[0]);

                                    $save_date = new \DateTime((int) substr($aps_von_datum, 0, 4) . "-" . (int) substr($aps_von_datum, 4, 2) . "-" . (int) substr($aps_von_datum, 6, 2));

                                    $save_type = '';
                                    $aps_type = (int) $this->user_input->getOnlyNumbers($kalendereintrag['reson']);
                                    $type_translate = [
                                        0 => '',
                                        1 => 'krank_bezahlt',
                                        2 => 'urlaub_bezahlt',
                                        3 => 'kind_krank',
                                        14 => 'weiterbildung',
                                        15 => 'frei',
                                        16 => 'krank_unbezahlt',
                                        17 => 'unentschuldigt_fehlen',
                                        18 => 'feiertag_bezahlt',
                                        19 => 'fehlzeit',
                                        20 => 'unbekannt',
                                        21 => 'urlaub_genehmigt',
                                        22 => 'krank',
                                        23 => 'urlaub_unbezahlt'
                                    ];
                                    if (key_exists($aps_type, $type_translate)) {
                                        $save_type = $type_translate[$aps_type];
                                    }

                                    $data = [
                                        'mitarbeiter_id' => $mitarbeiter_model->getID(),
                                        'datum' => $save_date->format("Y-m-d"),
                                        'titel' => trim($kalendereintrag['title']),
                                        'type' => $save_type
                                    ];
                                    $kalendereintrag_model = \ttact\Models\KalendereintragModel::createNew($this->db, $data);
                                    if (!$kalendereintrag_model instanceof \ttact\Models\KalendereintragModel) {
                                        $save_error = true;
                                        $this->db->delete('mitarbeiter', $mitarbeiter_model->getID());
                                        if ($mitarbeiterfilter_model instanceof \ttact\Models\MitarbeiterfilterModel) {
                                            $this->db->delete('mitarbeiterfilter', $mitarbeiterfilter_model->getID());
                                        }
                                        $aps_unable_to_save_personalnummern[$aps_mitarbeiter_key] = $personalnummer;
                                        unset($aps_personalnummern[$aps_mitarbeiter_key]);
                                        foreach ($abteilungsfreigabe_ids as $id) {
                                            $this->db->delete('abteilungsfreigabe', $id);
                                        }
                                        foreach ($lohnkonfiguration_ids as $id) {
                                            $this->db->delete('lohnkonfiguration', $id);
                                        }
                                        foreach ($mitarbeiterfilter_ids as $id) {
                                            $this->db->delete('mitarbeiterfilter', $id);
                                        }
                                        foreach ($lohnbuchung_ids as $id) {
                                            $this->db->delete('lohnbuchung', $id);
                                        }
                                        foreach ($kalendereintrag_ids as $id) {
                                            $this->db->delete('kalendereintrag', $id);
                                        }
                                        break;
                                    } else {
                                        $kalendereintrag_ids[] = $kalendereintrag_model->getID();
                                    }
                                }
                            }
                        }
                    }
                } else {
                    $aps_unable_to_save_personalnummern[$aps_mitarbeiter_key] = $personalnummer;
                    unset($aps_personalnummern[$aps_mitarbeiter_key]);
                }
            }
        }

        // print not imported rows
        $not_imported_rows = count($aps_doubled_personalnummern) /*+ count($ttact_already_has_personalnummern)*/ + count($aps_incorrect_personalnummern) + count($aps_unable_to_save_personalnummern);
        if ($not_imported_rows == 0) {
            $info .= "0 Zeilen wurden nicht importiert.";
        } else {
            $info .= $not_imported_rows . " Zeilen wurden nicht importiert:<br>";
            foreach($aps_doubled_personalnummern as $aps_mitarbeiter_key => $personalnummer) {
                $info .= '<kbd><a href="http://aps2.c-multimedia.de/?s=mitarbeiter&a=edit3&mitarbeiter=' . $aps_mitarbeiter[$aps_mitarbeiter_key]['Id'] . '" target="_new">' . $aps_mitarbeiter[$aps_mitarbeiter_key]['Id'] . "@aps.mitarbeiter</a></kbd> Die Personalnummer <strong>" . $personalnummer . "</strong> ist in <strong>aps.mitarbeiter</strong> mehrfach vorhanden.<br>";
            }
            //foreach($ttact_already_has_personalnummern as $aps_mitarbeiter_key => $personalnummer) {
            //    $info .= "<kbd>" . $aps_mitarbeiter[$aps_mitarbeiter_key]['Id'] . "@aps.mitarbeiter</kbd> Die Personalnummer <strong>" . $personalnummer . "</strong> existiert bereits in <strong>ttact.mitarbeiter</strong>.<br>";
            //}
            foreach($aps_incorrect_personalnummern as $aps_mitarbeiter_key => $grund) {
                $info .= '<kbd><a href="http://aps2.c-multimedia.de/?s=mitarbeiter&a=edit3&mitarbeiter=' . $aps_mitarbeiter[$aps_mitarbeiter_key]['Id'] . '" target="_new">' . $aps_mitarbeiter[$aps_mitarbeiter_key]['Id'] . '@aps.mitarbeiter</a></kbd> Personalnummer <strong>' . $aps_mitarbeiter[$aps_mitarbeiter_key]['Personalnr'] . "</strong>: " . $grund . "<br>";
            }
            foreach($aps_unable_to_save_personalnummern as $aps_mitarbeiter_key => $grund) {
                $info .= "<kbd>" . $aps_mitarbeiter[$aps_mitarbeiter_key]['Id'] . "@aps.mitarbeiter</kbd> konnte nicht importiert werden!<br>";
            }
        }

        // print imported rows
        $imported_rows = count($aps_personalnummern);
        if ($imported_rows == 0) {
            $info .= "<br>0 Zeilen wurden importiert.<br>";
        } else {
            $info .= "<br>" . $imported_rows . " Zeilen wurden importiert:<br>";
            foreach($aps_personalnummern as $aps_mitarbeiter_key => $personalnummer) {
                $info .= "<kbd>" . $aps_mitarbeiter[$aps_mitarbeiter_key]['Id'] . "@aps.mitarbeiter</kbd> nach <kbd>" . $aps_saved_ids[$aps_mitarbeiter_key] . "@ttact.mitarbeiter</kbd>.<br>";
            }
        }

        // template content
        $this->smarty_vars['info'] = $info;

        // template settings
        $this->template = 'main';
    }

    public function arbeitszeitkonto()
    {
        $aps_db = new \ttact\Database('liluye.han-solo.net', 'aps', 'dJqQQWss2Bj9rzhv', 'aps');

        $aps_mitarbeiter = $aps_db->getRowsQuery("SELECT mitarbeiter.Id, mitarbeiter.Personalnr FROM mitarbeiter");

        foreach ($aps_mitarbeiter as $aps_m) {
            $mitarbeiter_model = \ttact\Models\MitarbeiterModel::findByPersonalnummer($this->db, $aps_m['Personalnr']);
            if ($mitarbeiter_model instanceof \ttact\Models\MitarbeiterModel) {
                $azk_staende = $aps_db->getRows('azk', [], ['userID' => $aps_m['Id']]);
                foreach ($azk_staende as $azk_stand) {
                    $aps_datum_parts = explode('-', $azk_stand['datum']);
                    if (count($aps_datum_parts) == 2) {
                        $jahr = (int) $aps_datum_parts[1];
                        $monat = (int) $aps_datum_parts[0];

                        $data = [
                            'mitarbeiter_id' => $mitarbeiter_model->getID(),
                            'jahr' => $jahr,
                            'monat' => $monat,
                            'stunden' => (float) $azk_stand['azk_neu']
                        ];

                        $arbeitszeitkonto_model = \ttact\Models\ArbeitszeitkontoModel::createNew($this->db, $data);
                        if (!$arbeitszeitkonto_model instanceof \ttact\Models\ArbeitszeitkontoModel) {
                            echo "Beim Speichern des AZK-Stands ist ein Fehler aufgetreten (APS azk.id = ".$azk_stand['id'].").<br>";
                        }
                    } else {
                        echo "Ein AZK Stand hat ein ungültiges Datum (APS azk.id = ".$azk_stand['id'].").<br>";
                    }
                }
            } else {
                echo "Ein Mitarbeiter konnte nicht gefunden werden (APS mitarbeiter.Id = ".$aps_m['Id'].").<br>";
            }
        }

        $this->template = 'blank';
    }

    public function kunden()
    {
        $info = "";
        $aps_db = new \ttact\Database('liluye.han-solo.net', 'aps', 'dJqQQWss2Bj9rzhv', 'aps');

        $info .= "Import von <strong>aps.kunden</strong> zu <strong>ttact.kunde</strong><br><br>";

        // fetch data from aps-table
        $aps_mitarbeiter = $aps_db->getRows('kunden');
        $aps_personalnummern = [];
        foreach ($aps_mitarbeiter as $aps_mitarbeiter_key => $row) {
            $aps_personalnummern[$aps_mitarbeiter_key] = $row['kundennummer'];
        }

        // check aps-table for Personalnummer duplicates
        $aps_doubled_personalnummern = [];
        foreach ($aps_personalnummern as $aps_mitarbeiter_key => $personalnummer) {
            $keys = array_keys($aps_personalnummern, $personalnummer);
            if (count($keys) > 1) {
                foreach ($keys as $key) {
                    $aps_doubled_personalnummern[$key] = $personalnummer;
                    unset($aps_personalnummern[$key]);
                }
            }
        }

        // fetch data from ttact-table
        $ttact_mitarbeiter = \ttact\Models\KundeModel::findAll($this->db);
        $ttact_personalnummern = [];
        foreach($ttact_mitarbeiter as $ttact_mitarbeiter_key => $mitarbeiter) {
            $ttact_personalnummern[$ttact_mitarbeiter_key] = $mitarbeiter->getKundennummer();
        }

        // check for aps.mitarbeiter-Personalnummern that are already existing in ttact.mitarbeiter
        $ttact_already_has_personalnummern = [];
        foreach ($aps_personalnummern as $aps_mitarbeiter_key => $personalnummer) {
            if (in_array($personalnummer, $ttact_personalnummern)) {
                $ttact_already_has_personalnummern[$aps_mitarbeiter_key] = $personalnummer;
                unset($aps_personalnummern[$aps_mitarbeiter_key]);
            }
        }

        // check if all data is correct
        $aps_incorrect_personalnummern = [];
        foreach($aps_personalnummern as $aps_mitarbeiter_key => $value) {
            $personalnummer = $this->user_input->getOnlyNumbers($aps_mitarbeiter[$aps_mitarbeiter_key]['kundennummer']);
            $name = trim($aps_mitarbeiter[$aps_mitarbeiter_key]['name']);
            $emailadresse = trim($aps_mitarbeiter[$aps_mitarbeiter_key]['email']);
            $postleitzahl = trim($aps_mitarbeiter[$aps_mitarbeiter_key]['plz']);

            if ($personalnummer == "") {
                $aps_incorrect_personalnummern[$aps_mitarbeiter_key] = "Die Kundennummer ist ungültig.";
                unset($aps_personalnummern[$aps_mitarbeiter_key]);
            } elseif ($name == "") {
                $aps_incorrect_personalnummern[$aps_mitarbeiter_key] = "Es ist kein Name vorhanden.";
                unset($aps_personalnummern[$aps_mitarbeiter_key]);
            } elseif (($emailadresse != "") && !$this->user_input->isEmailadresse($emailadresse)) {
                $aps_incorrect_personalnummern[$aps_mitarbeiter_key] = "Die E-Mail-Adresse ist ungültig.";
                unset($aps_personalnummern[$aps_mitarbeiter_key]);
            } elseif (($postleitzahl != "") && !$this->user_input->isPostleitzahl($postleitzahl)) {
                $aps_incorrect_personalnummern[$aps_mitarbeiter_key] = "Die Postleitzahl ist ungültig.";
                unset($aps_personalnummern[$aps_mitarbeiter_key]);
            }
        }

        // sort $aps_personalnummern by Personalnummer
        asort($aps_personalnummern);

        // save and check if all data was saved correctly
        $aps_unable_to_save_personalnummern = [];
        $aps_saved_ids = [];
        foreach($aps_personalnummern as $aps_mitarbeiter_key => $personalnummer) {
            $model_data = [
                'kundennummer' => $this->user_input->getOnlyNumbers($aps_mitarbeiter[$aps_mitarbeiter_key]['kundennummer']),
                'name' => trim($aps_mitarbeiter[$aps_mitarbeiter_key]['name']),
                'strasse' => trim($aps_mitarbeiter[$aps_mitarbeiter_key]['strasse']),
                'postleitzahl' => trim($this->user_input->getOnlyNumbers($aps_mitarbeiter[$aps_mitarbeiter_key]['plz'])),
                'ort' => trim($aps_mitarbeiter[$aps_mitarbeiter_key]['ort']),
                'ansprechpartner' => trim($aps_mitarbeiter[$aps_mitarbeiter_key]['kontaktname']),
                'telefon1' => $this->user_input->getOnlyNumbers($aps_mitarbeiter[$aps_mitarbeiter_key]['telefon1']),
                'telefon2' => $this->user_input->getOnlyNumbers($aps_mitarbeiter[$aps_mitarbeiter_key]['telefon2']),
                'fax' => $this->user_input->getOnlyNumbers($aps_mitarbeiter[$aps_mitarbeiter_key]['fax']),
                'emailadresse' => trim(strtolower($aps_mitarbeiter[$aps_mitarbeiter_key]['email'])),
                'rechnungsanschrift' => trim($aps_mitarbeiter[$aps_mitarbeiter_key]['rechnungsanschrift']),
                'rechnungszusatz' => ((trim($aps_mitarbeiter[$aps_mitarbeiter_key]['kostenstelle']) == "") ? '' : (trim($aps_mitarbeiter[$aps_mitarbeiter_key]['kostenstellenart']) . " " . trim($aps_mitarbeiter[$aps_mitarbeiter_key]['kostenstelle'])))
            ];
            $mitarbeiter = \ttact\Models\KundeModel::createNew($this->db, $model_data);
            if ($mitarbeiter instanceof \ttact\Models\KundeModel) {
                $aps_saved_ids[$aps_mitarbeiter_key] = $mitarbeiter->getID();
            } else {
                $aps_unable_to_save_personalnummern[$aps_mitarbeiter_key] = $personalnummer;
                unset($aps_personalnummern[$aps_mitarbeiter_key]);
            }
        }

        // print not imported rows
        $not_imported_rows = count($aps_doubled_personalnummern) /*+ count($ttact_already_has_personalnummern)*/ + count($aps_incorrect_personalnummern) + count($aps_unable_to_save_personalnummern);
        if ($not_imported_rows == 0) {
            $info .= "0 Zeilen wurden nicht importiert.";
        } else {
            $info .= $not_imported_rows . " Zeilen wurden nicht importiert:<br>";
            foreach($aps_doubled_personalnummern as $aps_mitarbeiter_key => $personalnummer) {
                $info .= "<kbd>" . $aps_mitarbeiter[$aps_mitarbeiter_key]['kundenID'] . "@aps.kunden</kbd> Die Kundennummer <strong>" . $personalnummer . "</strong> ist in <strong>aps.kunden</strong> mehrfach vorhanden.<br>";
            }
            //foreach($ttact_already_has_personalnummern as $aps_mitarbeiter_key => $personalnummer) {
            //    $info .= "<kbd>" . $aps_mitarbeiter[$aps_mitarbeiter_key]['kundenID'] . "@aps.kunden</kbd> Die Kundennummer <strong>" . $personalnummer . "</strong> existiert bereits in <strong>ttact.kunde</strong>.<br>";
            //}
            foreach($aps_incorrect_personalnummern as $aps_mitarbeiter_key => $grund) {
                $info .= "<kbd>" . $aps_mitarbeiter[$aps_mitarbeiter_key]['kundenID'] . "@aps.kunden</kbd> Kundennummer <strong>" . $aps_mitarbeiter[$aps_mitarbeiter_key]['kundennummer'] . "</strong>: " . $grund . "<br>";
            }
            foreach($aps_unable_to_save_personalnummern as $aps_mitarbeiter_key => $grund) {
                $info .= "<kbd>" . $aps_mitarbeiter[$aps_mitarbeiter_key]['kundenID'] . "@aps.kunden</kbd> konnte nicht importiert werden!<br>";
            }
        }

        // print imported rows
        $imported_rows = count($aps_personalnummern);
        if ($imported_rows == 0) {
            $info .= "<br>0 Zeilen wurden importiert.<br>";
        } else {
            $info .= "<br>" . $imported_rows . " Zeilen wurden importiert:<br>";
            foreach($aps_personalnummern as $aps_mitarbeiter_key => $personalnummer) {
                $info .= "<kbd>" . $aps_mitarbeiter[$aps_mitarbeiter_key]['kundenID'] . "@aps.kunden</kbd> nach <kbd>" . $aps_saved_ids[$aps_mitarbeiter_key] . "@ttact.kunde</kbd>.<br>";
            }
        }

        // template content
        $this->smarty_vars['info'] = $info;

        // template settings
        $this->template = 'main';
    }

    public function abteilungen()
    {
        $info = "";
        $aps_db = new \ttact\Database('liluye.han-solo.net', 'aps', 'dJqQQWss2Bj9rzhv', 'aps');

        $info .= "Import von <strong>aps.abteilungen</strong> zu <strong>ttact.abteilungen</strong><br><br>";

        $aps_abteilungen = $aps_db->getRows('abteilung', ['name', 'aID'], [], ['aID']);

        foreach ($aps_abteilungen as $row) {
            $data = [
                'bezeichnung' => $row['name']
            ];
            \ttact\Models\AbteilungModel::createNew($this->db, $data);
        }

        $this->smarty_vars['info'] = $info;
        $this->template = 'main';
    }

    public function notizen()
    {
        $aps_db = new \ttact\Database('liluye.han-solo.net', 'aps', 'dJqQQWss2Bj9rzhv', 'aps');

        $mitarbeiter = $aps_db->getRowsQuery("SELECT mitarbeiter.Personalnr, mitarbeiter_intern.manote, mitarbeiter_intern.manote01, mitarbeiter_intern.manote02, mitarbeiter_intern.manote03, mitarbeiter_intern.manote04, mitarbeiter_intern.manote05, mitarbeiter_intern.manote06, mitarbeiter_intern.manote07, mitarbeiter_intern.manote08, mitarbeiter_intern.manote09, mitarbeiter_intern.manote10, mitarbeiter_intern.manote11, mitarbeiter_intern.manote12 FROM mitarbeiter, mitarbeiter_intern WHERE mitarbeiter.Id = mitarbeiter_intern.MitarbeiterId");

        foreach ($mitarbeiter as $row) {
            $personalnummer = (int) $this->user_input->getOnlyNumbers($row['Personalnr']);
            $mitarbeiter_model = \ttact\Models\MitarbeiterModel::findByPersonalnummer($this->db, $personalnummer);
            if ($mitarbeiter_model instanceof \ttact\Models\MitarbeiterModel) {
                $mitarbeiter_model->setNotizenAllgemein(trim($row['manote']));
                $mitarbeiter_model->setNotizenJanuar(trim($row['manote01']));
                $mitarbeiter_model->setNotizenFebruar(trim($row['manote02']));
                $mitarbeiter_model->setNotizenMaerz(trim($row['manote03']));
                $mitarbeiter_model->setNotizenApril(trim($row['manote04']));
                $mitarbeiter_model->setNotizenMai(trim($row['manote05']));
                $mitarbeiter_model->setNotizenJuni(trim($row['manote06']));
                $mitarbeiter_model->setNotizenJuli(trim($row['manote07']));
                $mitarbeiter_model->setNotizenAugust(trim($row['manote08']));
                $mitarbeiter_model->setNotizenSeptember(trim($row['manote09']));
                $mitarbeiter_model->setNotizenOktober(trim($row['manote10']));
                $mitarbeiter_model->setNotizenNovember(trim($row['manote11']));
                $mitarbeiter_model->setNotizenDezember(trim($row['manote12']));
            } else {
                echo "Problem: ". $row['Personalnr'] ."<br>";
            }
        }

        $this->template = 'blank';
    }

    public function kalender()
    {
        $aps_db = new \ttact\Database('liluye.han-solo.net', 'aps', 'dJqQQWss2Bj9rzhv', 'aps');
        $mitarbeiter = $aps_db->getRowsQuery("SELECT mitarbeiter.Id as id, mitarbeiter.Personalnr as personalnummer FROM mitarbeiter");
        foreach ($mitarbeiter as $row) {
            $mitarbeiter_model = \ttact\Models\MitarbeiterModel::findByPersonalnummer($this->db, $this->user_input->getOnlyNumbers($row['personalnummer']));
            if ($mitarbeiter_model instanceof \ttact\Models\MitarbeiterModel) {
                $kalendereintrag_ids = [];
                $kalendereintraege = $aps_db->getRows('mitarbeiter_kalender', [], ['userID' => $this->user_input->getOnlyNumbers($row['id'])], ['date_start']);
                if (count($kalendereintraege) > 0) {
                    foreach ($kalendereintraege as $kalendereintrag) {
                        $save_von = '0000-00-00';
                        $save_bis = '0000-00-00';

                        $aps_von = explode('T', trim($kalendereintrag['date_start']));
                        $aps_von_datum = $this->user_input->getOnlyNumbers($aps_von[0]);

                        $aps_bis = explode('T', trim($kalendereintrag['date_end']));
                        $aps_bis_datum = $this->user_input->getOnlyNumbers($aps_bis[0]);

                        $save_von = new \DateTime((int) substr($aps_von_datum, 0, 4) . "-" . (int) substr($aps_von_datum, 4, 2) . "-" . (int) substr($aps_von_datum, 6, 2));
                        $save_bis = new \DateTime((int) substr($aps_bis_datum, 0, 4) . "-" . (int) substr($aps_bis_datum, 4, 2) . "-" . (int) substr($aps_bis_datum, 6, 2));

                        $save_type = '';
                        $aps_type = (int) $this->user_input->getOnlyNumbers($kalendereintrag['reson']);
                        $type_translate = [
                            0 => '',
                            1 => 'krank_bezahlt',
                            2 => 'urlaub_bezahlt',
                            3 => 'kind_krank',
                            14 => 'weiterbildung',
                            15 => 'frei',
                            16 => 'krank_unbezahlt',
                            17 => 'unentschuldigt_fehlen',
                            18 => 'feiertag_bezahlt',
                            19 => 'fehlzeit',
                            20 => 'unbekannt',
                            21 => 'urlaub_genehmigt',
                            22 => 'krank',
                            23 => 'urlaub_unbezahlt'
                        ];
                        if (key_exists($aps_type, $type_translate)) {
                            $save_type = $type_translate[$aps_type];
                        }

                        $data = [
                            'mitarbeiter_id' => $mitarbeiter_model->getID(),
                            'von' => $save_von->format("Y-m-d"),
                            'bis' => $save_bis->format("Y-m-d"),
                            'titel' => trim($kalendereintrag['title']),
                            'type' => $save_type
                        ];
                        $kalendereintrag_model = \ttact\Models\KalendereintragModel::createNew($this->db, $data);
                        if (!$kalendereintrag_model instanceof \ttact\Models\KalendereintragModel) {
                            echo "Fehler beim Speichern.<br>";
                        } else {
                            $kalendereintrag_ids[] = $kalendereintrag_model->getID();
                        }
                    }
                }
            } else {
                echo "MA in ttact nicht gefunden: Personalnummer " . $row['personalnummer'] . "<br>";
            }
        }
    }

    public function kundenkonditionen()
    {
        $aps_db = new \ttact\Database('liluye.han-solo.net', 'aps', 'dJqQQWss2Bj9rzhv', 'aps');

        $aid_to_id = [];
        $counter = 1;
        $aps_abteilungen = $aps_db->getRows('abteilung', ['name', 'aid'], [], ['aid']);
        foreach ($aps_abteilungen as $row) {
            $aid_to_id[$row['aid']] = $counter;
            $counter++;
        }

        $aps_kundenkonditionen = $aps_db->getRowsQuery("SELECT kundenkonditionen.id as id, kundennummer, abteilung, preis, zso as sonntagszuschlag, zfei as feiertagszuschlag, znacht as nachtzuschlag, nachtvon as nacht_von, nachbis as nacht_bis FROM kunden, kundenkonditionen WHERE kundenkonditionen.kunde = kunden.kundenID AND istaktiv = 1 AND preis > 0");

        foreach ($aps_kundenkonditionen as $row) {
            $kunde = \ttact\Models\KundeModel::findByKundennummer($this->db, $row['kundennummer']);
            if ($kunde instanceof \ttact\Models\KundeModel) {
                if (isset($aid_to_id[$row['abteilung']])) {
                    $abteilung = \ttact\Models\AbteilungModel::findByID($this->db, $aid_to_id[$row['abteilung']]);
                    if ($abteilung instanceof \ttact\Models\AbteilungModel) {
                        $save_preis = (float) $row['preis'];
                        $save_sonntagszuschlag = (int) $row['sonntagszuschlag'];
                        $save_feiertagszuschlag = (int) $row['feiertagszuschlag'];
                        $save_nachtzuschlag = (int) $row['nachtzuschlag'];

                        $save_nacht_von = '00:00:00';
                        if ($row['nacht_von'] != '') {
                            $save_nacht_von = $row['nacht_von'] . ":00";
                        }
                        $save_nacht_bis = '00:00:00';
                        if ($row['nacht_bis'] != '') {
                            $save_nacht_bis = $row['nacht_bis'] . ":00";
                        }

                        $data = [
                            'gueltig_ab' => '0000-00-00',
                            'kunde_id' => $kunde->getID(),
                            'abteilung_id' => $abteilung->getID(),
                            'preis' => $save_preis,
                            'sonntagszuschlag' => $save_sonntagszuschlag,
                            'feiertagszuschlag' => $save_feiertagszuschlag,
                            'nachtzuschlag' => $save_nachtzuschlag,
                            'nacht_von' => $save_nacht_von,
                            'nacht_bis' => $save_nacht_bis
                        ];

                        $kundenkonditionen_model = \ttact\Models\KundenkonditionModel::createNew($this->db, $data);
                        if ($kundenkonditionen_model instanceof \ttact\Models\KundenkonditionModel) {
                            //
                        } else {
                            echo "Speichern fehlgeschlagen: ".$row['id']."<br>";
                        }
                    } else {
                        echo "Speichern fehlgeschlagen: ".$row['id']."<br>";
                    }
                } else {
                    echo "Speichern fehlgeschlagen: ".$row['id']."<br>";
                }
            } else {
                echo "Speichern fehlgeschlagen: ".$row['id']."<br>";
            }
        }

        $this->template = "blank";
    }

	public function rechnungen()
    {
        $info = "";
        $aps_db = new \ttact\Database('liluye.han-solo.net', 'aps', 'dJqQQWss2Bj9rzhv', 'aps');

        $info .= "Import von <strong>aps.rechnungen</strong> zu <strong>ttact.rechnungen</strong><br><br>";

        // fetch data from aps-table
		$aps_rechnungen = $aps_db->getRowsQuery('SELECT * FROM rechnung WHERE vorschau = 0 ORDER BY rID');
		$aps_unable_to_save_ids = array();
        $aps_saved_ids = array();
        foreach ($aps_rechnungen as $aps_rechnungen_key => $row) {
            $stornierungsdatum = '';
            $kunde_id = '';
            $rechnungsdatum = '';
            $rechnungsnummer = '';
            $zeitraum_von = '';
            $zeitraum_bis = '';
            $zahlungsziel = '';
            $kassendifferenz = '';
            $bezahlt_am = '';
            $kommentar = '';
            $alternative_anrede = '';

			$rInhalt = unserialize(base64_decode($row['rInhalt']));
			$rEmpfaenger = unserialize(base64_decode($row['rEmpfaenger']));

			if (isset($rEmpfaenger['kundennummer'])) {
			    $kundennummer = $this->user_input->getOnlyNumbers($rEmpfaenger['kundennummer']);
			    $kunde_model = \ttact\Models\KundeModel::findByKundennummer($this->db, $kundennummer);

			    if ($kunde_model instanceof \ttact\Models\KundeModel) {
			        $kunde_id = $kunde_model->getID();

			        $error = false;
                    if ($row['rStronoDatum'] != "") {
                        if ($this->user_input->isDate($row['rStronoDatum'])) {
                            $stornierungsdatum_model = \DateTime::createFromFormat('d.m.Y', $row['rStronoDatum']);
                            $stornierungsdatum = $stornierungsdatum_model->format('Y-m-d');
                        } else {
                            $error = true;
                        }
                    }

                    if (!$error) {
                        if ($this->user_input->isDate($row['rDatum'])) {
                            $rechnungsdatum_model = \DateTime::createFromFormat('d.m.Y', $row['rDatum']);
                            $rechnungsdatum = $rechnungsdatum_model->format('Y-m-d');

                            if ($this->user_input->isDate($rInhalt['stammdaten']['rvon'])) {
                                $zeitraum_von_model = \DateTime::createFromFormat('d.m.Y', $rInhalt['stammdaten']['rvon']);
                                $zeitraum_von = $zeitraum_von_model->format('Y-m-d');

                                if ($this->user_input->isDate($rInhalt['stammdaten']['rbis'])) {
                                    $zeitraum_bis_model = \DateTime::createFromFormat('d.m.Y', $rInhalt['stammdaten']['rbis']);
                                    $zeitraum_bis = $zeitraum_bis_model->format('Y-m-d');

                                    if ($this->user_input->isDate($rInhalt['stammdaten']['rzahlungsziel'])) {
                                        $zahlungsziel_model = \DateTime::createFromFormat('d.m.Y', $rInhalt['stammdaten']['rzahlungsziel']);
                                        $zahlungsziel = $zahlungsziel_model->format('Y-m-d');

                                        $rechnungsnummer_array = explode(' - ', $row['rNr']);
                                        if (isset($rechnungsnummer_array[1])) {
                                            $rechnungsnummer = $this->user_input->getOnlyNumbers($rechnungsnummer_array[1]);

                                            if (($zeitraum_von_model->format('Y') . ' - ' . $rechnungsnummer) == $row['rNr']) {
                                                if ($row['komm'] != '') {
                                                    $kommentar = filter_var(trim($row['komm']), FILTER_SANITIZE_STRING);
                                                }

                                                $error = false;
                                                if ($row['rStatus'] != '') {
                                                    $bezahlt_am_timestamp = (int) $this->user_input->getOnlyNumbers($row['rStatus']);
                                                    if ($bezahlt_am_timestamp > 0) {
                                                        $bezahlt_am_model = new \DateTime();
                                                        $bezahlt_am_model->setTimestamp($bezahlt_am_timestamp);
                                                        if ($bezahlt_am_model->getTimestamp() == $row['rStatus']) {
                                                            $bezahlt_am = $bezahlt_am_model->format('Y-m-d');
                                                        } else {
                                                            $error = true;
                                                        }
                                                    }
                                                }

                                                if (!$error) {
                                                    if (isset($rInhalt['stammdaten']['ranrede'])) {
                                                        if ($rInhalt['stammdaten']['ranrede'] != '') {
                                                            $alternative_anrede = filter_var(trim($rInhalt['stammdaten']['ranrede']), FILTER_SANITIZE_STRING);
                                                        }
                                                    }

                                                    if (isset($rInhalt['rechnungbetrag']['bruttoKD'])) {
                                                        if ($rInhalt['rechnungbetrag']['bruttoKD'] != '') {
                                                            $kassendifferenz = (float) str_replace(',', '.', $rInhalt['rechnungbetrag']['bruttoKD']);
                                                        }
                                                    }

                                                    $gesamtpreis = 0;
                                                    foreach ($rInhalt['rechnungsposten'] as $rechnungsposten) {
                                                        $gesamtpreis += $rechnungsposten['gesamtpreis'];
                                                    }
                                                    $gesamtpreis = round($gesamtpreis, 2);

                                                    $nettobetrag = round($rInhalt['rechnungbetrag']['netto'], 2);
                                                    $mehrwertsteuer = round($rInhalt['rechnungbetrag']['mwst'], 2);
                                                    $bruttobetrag = round($rInhalt['rechnungbetrag']['brutto'], 2);

                                                    if (strval($gesamtpreis) == strval($nettobetrag)) {
                                                        $data = [
                                                            'kunde_id' => $kunde_id,
                                                            'stornierungsdatum' => $stornierungsdatum,
                                                            'bezahlt_am' => $bezahlt_am,
                                                            'nettobetrag' => $nettobetrag,
                                                            'mehrwertsteuer' => $mehrwertsteuer,
                                                            'bruttobetrag' => $bruttobetrag,
                                                            'rechnungsdatum' => $rechnungsdatum,
                                                            'rechnungsnummer' => $rechnungsnummer,
                                                            'zeitraum_von' => $zeitraum_von,
                                                            'zeitraum_bis' => $zeitraum_bis,
                                                            'zahlungsziel' => $zahlungsziel,
                                                            'kassendifferenz' => $kassendifferenz,
                                                            'alternative_anrede' => $alternative_anrede,
                                                            'kommentar' => $kommentar,
                                                            'alte_rechnung_id' => $row['rID']
                                                        ];

                                                        $rechnung_model = \ttact\Models\RechnungModel::createNew($this->db, $data);
                                                        if ($rechnung_model instanceof \ttact\Models\RechnungModel) {
                                                            $rechnungsposten_models = [];

                                                            $error = false;

                                                            foreach ($rInhalt['rechnungsposten'] as $rechnungsposten) {
                                                                $data = [
                                                                    'rechnung_id' => $rechnung_model->getID(),
                                                                    'leistungsart' => $rechnungsposten['leistungsart'],
                                                                    'menge' => $rechnungsposten['menge'],
                                                                    'einzelpreis' => $rechnungsposten['einzelpreis'],
                                                                    'gesamtpreis' => $rechnungsposten['gesamtpreis']
                                                                ];

                                                                $rechnungsposten_model = \ttact\Models\RechnungspostenModel::createNew($this->db, $data);
                                                                if ($rechnungsposten_model instanceof \ttact\Models\RechnungspostenModel) {
                                                                    $rechnungsposten_models[] = $rechnungsposten_model;
                                                                } else {
                                                                    $error = true;
                                                                    break;
                                                                }
                                                            }

                                                            if (!$error) {
                                                                // success
                                                                $aps_saved_ids[$row['rID']] = $rechnung_model->getID();
                                                            } else {
                                                                // error
                                                                $aps_unable_to_save_ids[] = $row['rID'];
                                                                $info .= "<kbd>" . $row['rID'] . "@aps.rechnung</kbd> Ein Rechnungsposten konnte nicht gespeichert werden.<br>";

                                                                foreach ($rechnungsposten_models as $rechnungsposten_model) {
                                                                    $rechnungsposten_model->delete();
                                                                }
                                                                $rechnung_model->delete();
                                                            }
                                                        } else {
                                                            // error
                                                            $aps_unable_to_save_ids[$row['rID']] = "Die Rechnung konnte nicht gespeichert werden.";
                                                        }
                                                    } else {
                                                        // error
                                                        $aps_unable_to_save_ids[$row['rID']] = "Der berechnete Nettobetrag entspricht nicht dem originalen Gesamtpreis.";
                                                    }
                                                } else {
                                                    // error
                                                    $aps_unable_to_save_ids[$row['rID']] = "Das Datum für 'bezahlt am' ist ungültig.";
                                                }
                                            } else {
                                                // error
                                                $aps_unable_to_save_ids[$row['rID']] = "Die Rechnungsnummern stimmen nicht überein.";
                                            }
                                        } else {
                                            // error
                                            $aps_unable_to_save_ids[$row['rID']] = "Die Rechnungsnummer konnte nicht extrahiert werden.";
                                        }
                                    } else {
                                        // error
                                        $aps_unable_to_save_ids[$row['rID']] = "Das Datum für 'Zahlungsziel' ist ungültig.";
                                    }
                                } else {
                                    // error
                                    $aps_unable_to_save_ids[$row['rID']] = "Das Datum für 'Zeitraum bis' ist ungültig.";
                                }
                            } else {
                                // error
                                $aps_unable_to_save_ids[$row['rID']] = "Das Datum für 'Zeitraum von' ist ungültig.";
                            }
                        } else {
                            // error
                            $aps_unable_to_save_ids[$row['rID']] = "Das Rechnungsdatum ist ungültig.";
                        }
                    } else {
                        // error
                        $aps_unable_to_save_ids[$row['rID']] = "Das Stornierungsdatum ist ungültig.";
                    }
                } else {
			        // error
                    $aps_unable_to_save_ids[$row['rID']] = "Der Kunde konnte in ttact.kunde nicht gefunden werden.";
                }
            } else {
			    // error
                $aps_unable_to_save_ids[$row['rID']] = "Es ist keine Kundennummer vorhanden.";
            }
        }

        // print not imported rows
        $not_imported_rows = count($aps_unable_to_save_ids) ;
        if ($not_imported_rows == 0) {
            $info .= "0 Zeilen wurden nicht importiert.";
        } else {
            $info .= $not_imported_rows . " Zeilen wurden nicht importiert:<br>";
            foreach ($aps_unable_to_save_ids as $aps_id => $message) {
                $info .= "<kbd><a href='http://aps2.c-multimedia.de/pdf/create_rechnung.php?rID=" . $aps_id . "' target='_blank'>" . $aps_id . "@aps.rechnung</a></kbd> " . $message . "<br>";
            }
        }

        // print imported rows
        $imported_rows = count($aps_saved_ids);
        if ($imported_rows == 0) {
            $info .= "<br>0 Zeilen wurden importiert.<br>";
        } else {
            $info .= "<br>" . $imported_rows . " Zeilen wurden importiert:<br>";

            foreach ($aps_saved_ids as $aps_id => $ttact_id) {
                $info .= "<kbd><a href='http://aps2.c-multimedia.de/pdf/create_rechnung.php?rID=" . $aps_id . "' target='_blank'>" . $aps_id . "@aps.rechnung</a></kbd> nach <kbd><a href='/rechnungen/pdf/" . $ttact_id . "' target='_blank'>" . $ttact_id . "@ttact.rechnung</a></kbd><br>";
            }
        }

        // template content
        $this->smarty_vars['info'] = $info;

        // template settings
        $this->template = 'main';
    }
}
