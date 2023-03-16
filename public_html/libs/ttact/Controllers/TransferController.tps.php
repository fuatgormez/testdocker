<?php

namespace ttact\Controllers;

class TransferController extends Controller
{
    public function mitarbeiter()
    {
        $info = "";
        $tps_db = new \ttact\Database('digehe.han-solo.net', 'root', 'gClVMCJqux0G0', 'tps');

        $info .= "Import von <strong>tps.mitarbeiter</strong> zu <strong>ttact.mitarbeiter</strong><br><br>";

        // fetch data from tps-table
        $tps_mitarbeiter = $tps_db->getRows('mitarbeiter', [], [], ['Personalnr']);
        $tps_personalnummern = [];
        foreach ($tps_mitarbeiter as $tps_mitarbeiter_key => $row) {
            $tps_personalnummern[$tps_mitarbeiter_key] = $row['Personalnr'];
        }

        // check tps-table for Personalnummer duplicates
        $tps_doubled_personalnummern = [];
        foreach ($tps_personalnummern as $tps_mitarbeiter_key => $personalnummer) {
            $keys = array_keys($tps_personalnummern, $personalnummer);
            if (count($keys) > 1) {
                foreach ($keys as $key) {
                    $tps_doubled_personalnummern[$key] = $personalnummer;
                    unset($tps_personalnummern[$key]);
                }
            }
        }

        // fetch data from ttact-table
        $ttact_mitarbeiter = \ttact\Models\MitarbeiterModel::findAll($this->db);
        $ttact_personalnummern = [];
        foreach($ttact_mitarbeiter as $ttact_mitarbeiter_key => $mitarbeiter) {
            $ttact_personalnummern[$ttact_mitarbeiter_key] = $mitarbeiter->getPersonalnummer();
        }

        // check for tps.mitarbeiter-Personalnummern that are already existing in ttact.mitarbeiter
        $ttact_already_has_personalnummern = [];
        foreach ($tps_personalnummern as $tps_mitarbeiter_key => $personalnummer) {
            if (in_array($personalnummer, $ttact_personalnummern)) {
                $ttact_already_has_personalnummern[$tps_mitarbeiter_key] = $personalnummer;
                unset($tps_personalnummern[$tps_mitarbeiter_key]);
            }
        }

        // create an array to map tps.abteilung_ids to ttact.abteilung_ids
        $aID_to_id = [];
        $counter = 1;
        $tps_abteilungen = $tps_db->getRows('abteilung', ['name', 'aID'], [], ['aID']);
        foreach ($tps_abteilungen as $row) {
            $aID_to_id[$row['aID']] = $counter;
            $counter++;
        }

        // create an array to map tps.kunden_ids to ttact.kunden_ids
        $kid_to_id = [];
        $tps_kunden = $tps_db->getRows('kunden');
        foreach ($tps_kunden as $row) {
            $k = \ttact\Models\KundeModel::findByKundennummer($this->db, $row['kundennummer']);
            if ($k instanceof \ttact\Models\KundeModel) {
                $kid_to_id[$row['kundenID']] = $k->getID();
            }
        }

        // check if all data is correct
        $tps_incorrect_personalnummern = [];
        $tps_unable_to_save_personalnummern = [];
        $tps_saved_ids = [];
        foreach($tps_personalnummern as $tps_mitarbeiter_key => $value) {
            // 'mitarbeiter'-data
            $personalnummer = $this->user_input->getOnlyNumbers($tps_mitarbeiter[$tps_mitarbeiter_key]['Personalnr']);
            $geschlecht = (($tps_mitarbeiter[$tps_mitarbeiter_key]['Anrede'] == 'Herr') ? 'männlich' : (($tps_mitarbeiter[$tps_mitarbeiter_key]['Anrede'] == 'Frau') ? 'weiblich' : ''));
            $vorname = ucfirst(strtolower(trim($tps_mitarbeiter[$tps_mitarbeiter_key]['Vorname'])));
            $nachname = ucfirst(strtolower(trim($tps_mitarbeiter[$tps_mitarbeiter_key]['Familienname'])));
            $telefon1 = $this->user_input->getOnlyNumbers($tps_mitarbeiter[$tps_mitarbeiter_key]['Telefon1']);
            $telefon2 = $this->user_input->getOnlyNumbers($tps_mitarbeiter[$tps_mitarbeiter_key]['Telefon2']);
            $emailadresse = trim($tps_mitarbeiter[$tps_mitarbeiter_key]['EMail']);

            // 'mitarbeiter_intern'-data
            $mitarbeiter_intern = $tps_db->getFirstRow('mitarbeiter_intern', ['MitarbeiterId' => $tps_mitarbeiter[$tps_mitarbeiter_key]['Id']]);

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
            $zuordnung_personal = $tps_db->getFirstRow('ZuteilungPersonal', ['Nummer' => $personalnummer]);

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
                $tps_incorrect_personalnummern[$tps_mitarbeiter_key] = "Die Personalnummer ist ungültig.";
                unset($tps_personalnummern[$tps_mitarbeiter_key]);
            } elseif ($geschlecht == "") {
                $tps_incorrect_personalnummern[$tps_mitarbeiter_key] = "Es ist keine Anrede vorhanden.";
                unset($tps_personalnummern[$tps_mitarbeiter_key]);
            } elseif ($vorname == "") {
                $tps_incorrect_personalnummern[$tps_mitarbeiter_key] = "Es ist kein Vorname vorhanden.";
                unset($tps_personalnummern[$tps_mitarbeiter_key]);
            } elseif ($nachname == "") {
                $tps_incorrect_personalnummern[$tps_mitarbeiter_key] = "Es ist kein Nachname vorhanden.";
                unset($tps_personalnummern[$tps_mitarbeiter_key]);
            } elseif (($emailadresse != "") && !$this->user_input->isEmailadresse($emailadresse)) {
                $tps_incorrect_personalnummern[$tps_mitarbeiter_key] = "Die E-Mail-Adresse ist ungültig.";
                unset($tps_personalnummern[$tps_mitarbeiter_key]);
            } elseif ($austritt != '0000-00-00' && (!($obj_austritt instanceof \DateTime) || $austritt != $obj_austritt->format('Y-m-d'))) {
                $tps_incorrect_personalnummern[$tps_mitarbeiter_key] = 'Unter dem Punkt "Sozialversicherung": Das Datum im Feld "Austrittsdatum" ist ungültig.';
                unset($tps_personalnummern[$tps_mitarbeiter_key]);
            } elseif ($eintritt != '0000-00-00' && (!($obj_eintritt instanceof \DateTime) || $eintritt != $obj_eintritt->format('Y-m-d'))) {
                $tps_incorrect_personalnummern[$tps_mitarbeiter_key] = 'Unter dem Punkt "Sozialversicherung": Das Datum im Feld "Eintrittsdatum" ist ungültig.';
                unset($tps_personalnummern[$tps_mitarbeiter_key]);
            } elseif ($befristung != '0000-00-00' && (!($obj_befristung instanceof \DateTime) || $befristung != $obj_befristung->format('Y-m-d'))) {
                $tps_incorrect_personalnummern[$tps_mitarbeiter_key] = 'Unter dem Punkt "Sozialversicherung": Das Datum im Feld "Befristung" ist ungültig.';
                unset($tps_personalnummern[$tps_mitarbeiter_key]);
            } elseif ($befristung1 != '' && $befristung1 != '0000-00-00' && !($obj_befristung1 instanceof \DateTime)) {
                $tps_incorrect_personalnummern[$tps_mitarbeiter_key] = 'Unter dem Punkt "Sozialversicherung": Das Datum im Feld "1. Befristung" ist ungültig.';
                unset($tps_personalnummern[$tps_mitarbeiter_key]);
            } elseif ($befristung2 != '' && $befristung2 != '0000-00-00' && !($obj_befristung2 instanceof \DateTime)) {
                $tps_incorrect_personalnummern[$tps_mitarbeiter_key] = 'Unter dem Punkt "Sozialversicherung": Das Datum im Feld "2. Befristung" ist ungültig.';
                unset($tps_personalnummern[$tps_mitarbeiter_key]);
            } elseif ($befristung3 != '' && $befristung3 != '0000-00-00' && !($obj_befristung3 instanceof \DateTime)) {
                $tps_incorrect_personalnummern[$tps_mitarbeiter_key] = 'Unter dem Punkt "Sozialversicherung": Das Datum im Feld "3. Befristung" ist ungültig.';
                unset($tps_personalnummern[$tps_mitarbeiter_key]);
            } elseif ($austritt != '0000-00-00' && $eintritt != '0000-00-00' && ($obj_eintritt > $obj_austritt)) {
                $tps_incorrect_personalnummern[$tps_mitarbeiter_key] = 'Unter dem Punkt "Sozialversicherung": Das Austrittsdatum liegt nach dem Eintrittsdatum.';
                unset($tps_personalnummern[$tps_mitarbeiter_key]);
            } elseif ($befristung1 == '' && $befristung2 != '') {
                $tps_incorrect_personalnummern[$tps_mitarbeiter_key] = 'Unter dem Punkt "Sozialversicherung": Es ist eine 2. Befristung eingegeben, obwohl es keine 1. Befristung gibt.';
                unset($tps_personalnummern[$tps_mitarbeiter_key]);
            } elseif ($befristung2 == '' && $befristung3 != '') {
                $tps_incorrect_personalnummern[$tps_mitarbeiter_key] = 'Unter dem Punkt "Sozialversicherung": Es ist eine 3. Befristung eingegeben, obwohl es keine 2. Befristung gibt.';
                unset($tps_personalnummern[$tps_mitarbeiter_key]);
            } elseif (isset($zuordnung_personal['Nummer']) && $kunde_exists && $kunde_id_ttact == '') {
                $tps_incorrect_personalnummern[$tps_mitarbeiter_key] = 'Unter dem Punkt "Anforderung": Der Kunde, zu dessen Stammbelegschaft dieser Mitarbeiter gehören soll, existiert nicht.';
                unset($tps_personalnummern[$tps_mitarbeiter_key]);
            } elseif (isset($zuordnung_personal['Nummer']) && $kunde_exists && !($kunde_model instanceof \ttact\Models\KundeModel)) {
                $tps_incorrect_personalnummern[$tps_mitarbeiter_key] = 'Es ist ein Fehler aufgetreten. Fehlercode <strong style="color:red;">1</strong>.';
                unset($tps_personalnummern[$tps_mitarbeiter_key]);
            } elseif (isset($zuordnung_personal['Nummer']) && $abteilungen[1]['exists'] && $abteilungen[1]['ttact_id'] == '') {
                $tps_incorrect_personalnummern[$tps_mitarbeiter_key] = 'Unter dem Punkt "Anforderung": Die Abteilung für "Abteilung 1" existiert nicht.';
                unset($tps_personalnummern[$tps_mitarbeiter_key]);
            } elseif (isset($zuordnung_personal['Nummer']) && $abteilungen[1]['exists'] && !($abteilungen[1]['model'] instanceof \ttact\Models\AbteilungModel)) {
                $tps_incorrect_personalnummern[$tps_mitarbeiter_key] = 'Es ist ein Fehler aufgetreten. Fehlercode <strong style="color:red;">2</strong>.';
                unset($tps_personalnummern[$tps_mitarbeiter_key]);
            } elseif (isset($zuordnung_personal['Nummer']) && $abteilungen[2]['exists'] && $abteilungen[2]['ttact_id'] == '') {
                $tps_incorrect_personalnummern[$tps_mitarbeiter_key] = 'Unter dem Punkt "Anforderung": Die Abteilung für "Abteilung 2" existiert nicht.';
                unset($tps_personalnummern[$tps_mitarbeiter_key]);
            } elseif (isset($zuordnung_personal['Nummer']) && $abteilungen[2]['exists'] && !($abteilungen[2]['model'] instanceof \ttact\Models\AbteilungModel)) {
                $tps_incorrect_personalnummern[$tps_mitarbeiter_key] = 'Es ist ein Fehler aufgetreten. Fehlercode <strong style="color:red;">3</strong>.';
                unset($tps_personalnummern[$tps_mitarbeiter_key]);
            } elseif (isset($zuordnung_personal['Nummer']) && $abteilungen[3]['exists'] && $abteilungen[3]['ttact_id'] == '') {
                $tps_incorrect_personalnummern[$tps_mitarbeiter_key] = 'Unter dem Punkt "Anforderung": Die Abteilung für "Abteilung 3" existiert nicht.';
                unset($tps_personalnummern[$tps_mitarbeiter_key]);
            } elseif (isset($zuordnung_personal['Nummer']) && $abteilungen[3]['exists'] && !($abteilungen[3]['model'] instanceof \ttact\Models\AbteilungModel)) {
                $tps_incorrect_personalnummern[$tps_mitarbeiter_key] = 'Es ist ein Fehler aufgetreten. Fehlercode <strong style="color:red;">4</strong>.';
                unset($tps_personalnummern[$tps_mitarbeiter_key]);
            } elseif (isset($zuordnung_personal['Nummer']) && $abteilungen[4]['exists'] && $abteilungen[4]['ttact_id'] == '') {
                $tps_incorrect_personalnummern[$tps_mitarbeiter_key] = 'Unter dem Punkt "Anforderung": Die Abteilung für "Abteilung 4" existiert nicht.';
                unset($tps_personalnummern[$tps_mitarbeiter_key]);
            } elseif (isset($zuordnung_personal['Nummer']) && $abteilungen[4]['exists'] && !($abteilungen[4]['model'] instanceof \ttact\Models\AbteilungModel)) {
                $tps_incorrect_personalnummern[$tps_mitarbeiter_key] = 'Es ist ein Fehler aufgetreten. Fehlercode <strong style="color:red;">5</strong>.';
                unset($tps_personalnummern[$tps_mitarbeiter_key]);
            } elseif (isset($zuordnung_personal['Nummer']) && $abteilungen[5]['exists'] && $abteilungen[5]['ttact_id'] == '') {
                $tps_incorrect_personalnummern[$tps_mitarbeiter_key] = 'Unter dem Punkt "Anforderung": Die Abteilung für "Abteilung 5" existiert nicht.';
                unset($tps_personalnummern[$tps_mitarbeiter_key]);
            } elseif (isset($zuordnung_personal['Nummer']) && $abteilungen[5]['exists'] && !($abteilungen[5]['model'] instanceof \ttact\Models\AbteilungModel)) {
                $tps_incorrect_personalnummern[$tps_mitarbeiter_key] = 'Es ist ein Fehler aufgetreten. Fehlercode <strong style="color:red;">6</strong>.';
                unset($tps_personalnummern[$tps_mitarbeiter_key]);
            } elseif (isset($zuordnung_personal['Nummer']) && $abteilungen[6]['exists'] && $abteilungen[6]['ttact_id'] == '') {
                $tps_incorrect_personalnummern[$tps_mitarbeiter_key] = 'Unter dem Punkt "Anforderung": Die Abteilung für "Abteilung 6" existiert nicht.';
                unset($tps_personalnummern[$tps_mitarbeiter_key]);
            } elseif (isset($zuordnung_personal['Nummer']) && $abteilungen[6]['exists'] && !($abteilungen[6]['model'] instanceof \ttact\Models\AbteilungModel)) {
                $tps_incorrect_personalnummern[$tps_mitarbeiter_key] = 'Es ist ein Fehler aufgetreten. Fehlercode <strong style="color:red;">7</strong>.';
                unset($tps_personalnummern[$tps_mitarbeiter_key]);
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
                    $tps_saved_ids[$tps_mitarbeiter_key] = $mitarbeiter_model->getID();
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
                                $tps_unable_to_save_personalnummern[$tps_mitarbeiter_key] = $personalnummer;
                                unset($tps_personalnummern[$tps_mitarbeiter_key]);
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
                                            $tps_unable_to_save_personalnummern[$tps_mitarbeiter_key] = $personalnummer;
                                            unset($tps_personalnummern[$tps_mitarbeiter_key]);
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
                                                    $tps_unable_to_save_personalnummern[$tps_mitarbeiter_key] = $personalnummer;
                                                    unset($tps_personalnummern[$tps_mitarbeiter_key]);
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
                                $tps_unable_to_save_personalnummern[$tps_mitarbeiter_key] = $personalnummer;
                                unset($tps_personalnummern[$tps_mitarbeiter_key]);
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
                            $marktsperren = $tps_db->getRows('marktsperren', [], ['mitarbeiter' => $this->user_input->getOnlyNumbers($tps_mitarbeiter[$tps_mitarbeiter_key]['Id'])]);
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
                                                    $tps_unable_to_save_personalnummern[$tps_mitarbeiter_key] = $personalnummer;
                                                    unset($tps_personalnummern[$tps_mitarbeiter_key]);
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
                            $sonderbuchungen = $tps_db->getRows('sonderbuchungen', [], ['ma' => $this->user_input->getOnlyNumbers($tps_mitarbeiter[$tps_mitarbeiter_key]['Id'])], ['jahr', 'monat']);
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
                                        $tps_unable_to_save_personalnummern[$tps_mitarbeiter_key] = $personalnummer;
                                        unset($tps_personalnummern[$tps_mitarbeiter_key]);
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
                            $kalendereintraege = $tps_db->getRows('mitarbeiter_kalender', [], ['userID' => $this->user_input->getOnlyNumbers($tps_mitarbeiter[$tps_mitarbeiter_key]['Id'])], ['date_start']);
                            if (count($kalendereintraege) > 0) {
                                foreach ($kalendereintraege as $kalendereintrag) {
                                    $save_von = '0000-00-00';
                                    $save_bis = '0000-00-00';

                                    $tps_von = explode('T', trim($kalendereintrag['date_start']));
                                    $tps_von_datum = $this->user_input->getOnlyNumbers($tps_von[0]);

                                    $tps_bis = explode('T', trim($kalendereintrag['date_end']));
                                    $tps_bis_datum = $this->user_input->getOnlyNumbers($tps_bis[0]);

                                    $save_von = new \DateTime((int) substr($tps_von_datum, 0, 4) . "-" . (int) substr($tps_von_datum, 4, 2) . "-" . (int) substr($tps_von_datum, 6, 2));
                                    $save_bis = new \DateTime((int) substr($tps_bis_datum, 0, 4) . "-" . (int) substr($tps_bis_datum, 4, 2) . "-" . (int) substr($tps_bis_datum, 6, 2));

                                    $save_type = '';
                                    $tps_type = (int) $this->user_input->getOnlyNumbers($kalendereintrag['reson']);
                                    if ($tps_type != 19) {
                                        // fehlzeiten nicht speichern

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
                                            20 => 'unbekannt',
                                            21 => 'urlaub_genehmigt',
                                            22 => 'krank',
                                            23 => 'urlaub_unbezahlt'
                                        ];
                                        if (key_exists($tps_type, $type_translate)) {
                                            $save_type = $type_translate[$tps_type];
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
                                            $save_error = true;
                                            $this->db->delete('mitarbeiter', $mitarbeiter_model->getID());
                                            if ($mitarbeiterfilter_model instanceof \ttact\Models\MitarbeiterfilterModel) {
                                                $this->db->delete('mitarbeiterfilter', $mitarbeiterfilter_model->getID());
                                            }
                                            $tps_unable_to_save_personalnummern[$tps_mitarbeiter_key] = $personalnummer;
                                            unset($tps_personalnummern[$tps_mitarbeiter_key]);
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
                    }
                } else {
                    $tps_unable_to_save_personalnummern[$tps_mitarbeiter_key] = $personalnummer;
                    unset($tps_personalnummern[$tps_mitarbeiter_key]);
                }
            }
        }

        // print not imported rows
        $not_imported_rows = count($tps_doubled_personalnummern) /*+ count($ttact_already_has_personalnummern)*/ + count($tps_incorrect_personalnummern) + count($tps_unable_to_save_personalnummern);
        if ($not_imported_rows == 0) {
            $info .= "0 Zeilen wurden nicht importiert.";
        } else {
            $info .= $not_imported_rows . " Zeilen wurden nicht importiert:<br>";
            foreach($tps_doubled_personalnummern as $tps_mitarbeiter_key => $personalnummer) {
                $info .= '<kbd><a href="http://tps2.c-multimedia.de/?s=mitarbeiter&a=edit3&mitarbeiter=' . $tps_mitarbeiter[$tps_mitarbeiter_key]['Id'] . '" target="_new">' . $tps_mitarbeiter[$tps_mitarbeiter_key]['Id'] . "@tps.mitarbeiter</a></kbd> Die Personalnummer <strong>" . $personalnummer . "</strong> ist in <strong>tps.mitarbeiter</strong> mehrfach vorhanden.<br>";
            }
            foreach($tps_incorrect_personalnummern as $tps_mitarbeiter_key => $grund) {
                $info .= '<kbd><a href="http://tps2.c-multimedia.de/?s=mitarbeiter&a=edit3&mitarbeiter=' . $tps_mitarbeiter[$tps_mitarbeiter_key]['Id'] . '" target="_new">' . $tps_mitarbeiter[$tps_mitarbeiter_key]['Id'] . '@tps.mitarbeiter</a></kbd> Personalnummer <strong>' . $tps_mitarbeiter[$tps_mitarbeiter_key]['Personalnr'] . "</strong>: " . $grund . "<br>";
            }
            foreach($tps_unable_to_save_personalnummern as $tps_mitarbeiter_key => $grund) {
                $info .= "<kbd>" . $tps_mitarbeiter[$tps_mitarbeiter_key]['Id'] . "@tps.mitarbeiter</kbd> konnte nicht importiert werden!<br>";
            }
        }

        // print imported rows
        $imported_rows = count($tps_personalnummern);
        if ($imported_rows == 0) {
            $info .= "<br>0 Zeilen wurden importiert.<br>";
        } else {
            $info .= "<br>" . $imported_rows . " Zeilen wurden importiert:<br>";
            foreach($tps_personalnummern as $tps_mitarbeiter_key => $personalnummer) {
                $info .= "<kbd>" . $tps_mitarbeiter[$tps_mitarbeiter_key]['Id'] . "@tps.mitarbeiter</kbd> nach <kbd>" . $tps_saved_ids[$tps_mitarbeiter_key] . "@ttact.mitarbeiter</kbd>.<br>";
            }
        }

        // template content
        $this->smarty_vars['info'] = $info;

        // template settings
        $this->template = 'main';
    }

    public function kunden()
    {
        $info = "";
        $tps_db = new \ttact\Database('digehe.han-solo.net', 'root', 'gClVMCJqux0G0', 'tps');

        $info .= "Import von <strong>tps.kunden</strong> zu <strong>ttact.kunde</strong><br><br>";

        // fetch data from tps-table
        $tps_kunden = $tps_db->getRows('kunden');
        $tps_kundennummern = [];
        foreach ($tps_kunden as $tps_kunde_key => $row) {
            $tps_kundennummern[$tps_kunde_key] = $row['kundennummer'];
        }

        // check tps-table for Kundennummer duplicates
        $tps_doubled_kundennummern = [];
        foreach ($tps_kundennummern as $tps_kunde_key => $kundennummer) {
            $keys = array_keys($tps_kundennummern, $kundennummer);
            if (count($keys) > 1) {
                foreach ($keys as $key) {
                    $tps_doubled_kundennummern[$key] = $kundennummer;
                    unset($tps_kundennummern[$key]);
                }
            }
        }

        // fetch data from ttact-table
        $ttact_kunden = \ttact\Models\KundeModel::findAll($this->db);
        $ttact_kundennummern = [];
        foreach($ttact_kunden as $ttact_kunde_key => $kunde) {
            $ttact_kundennummern[$ttact_kunde_key] = $kunde->getKundennummer();
        }

        // check for tps.kunden-Kundennummern that are already existing in ttact.kunde
        $ttact_already_has_kundennummern = [];
        foreach ($tps_kundennummern as $tps_kunde_key => $kundennummer) {
            if (in_array($kundennummer, $ttact_kundennummern)) {
                $ttact_already_has_kundennummern[$tps_kunde_key] = $kundennummer;
                unset($tps_kundennummern[$tps_kunde_key]);
            }
        }

        // check if all data is correct
        $tps_incorrect_kundennummern = [];
        foreach($tps_kundennummern as $tps_kunde_key => $value) {
            $kundennummer = $this->user_input->getOnlyNumbers($tps_kunden[$tps_kunde_key]['kundennummer']);
            $name = trim($tps_kunden[$tps_kunde_key]['name']);
            $emailadresse = trim($tps_kunden[$tps_kunde_key]['email']);
            $postleitzahl = trim($tps_kunden[$tps_kunde_key]['plz']);

            if ($kundennummer == "") {
                $tps_incorrect_kundennummern[$tps_kunde_key] = "Die Kundennummer ist ungültig.";
                unset($tps_kundennummern[$tps_kunde_key]);
            } elseif ($name == "") {
                $tps_incorrect_kundennummern[$tps_kunde_key] = "Es ist kein Name vorhanden.";
                unset($tps_kundennummern[$tps_kunde_key]);
            } elseif (($emailadresse != "") && !$this->user_input->isEmailadresse($emailadresse)) {
                $tps_incorrect_kundennummern[$tps_kunde_key] = "Die E-Mail-Adresse ist ungültig.";
                unset($tps_kundennummern[$tps_kunde_key]);
            } elseif (($postleitzahl != "") && !$this->user_input->isPostleitzahl($postleitzahl)) {
                $tps_incorrect_kundennummern[$tps_kunde_key] = "Die Postleitzahl ist ungültig.";
                unset($tps_kundennummern[$tps_kunde_key]);
            }
        }

        // sort $tps_kundennummern by Kundennummer
        asort($tps_kundennummern);

        // save and check if all data was saved correctly
        $tps_unable_to_save_kundennummern = [];
        $tps_saved_ids = [];
        foreach($tps_kundennummern as $tps_kunde_key => $kundennummer) {
            $model_data = [
                'kundennummer' => $this->user_input->getOnlyNumbers($tps_kunden[$tps_kunde_key]['kundennummer']),
                'name' => trim($tps_kunden[$tps_kunde_key]['name']),
                'strasse' => trim($tps_kunden[$tps_kunde_key]['strasse']),
                'postleitzahl' => trim($this->user_input->getOnlyNumbers($tps_kunden[$tps_kunde_key]['plz'])),
                'ort' => trim($tps_kunden[$tps_kunde_key]['ort']),
                'ansprechpartner' => trim($tps_kunden[$tps_kunde_key]['kontaktname']),
                'telefon1' => $this->user_input->getOnlyNumbers($tps_kunden[$tps_kunde_key]['telefon1']),
                'telefon2' => $this->user_input->getOnlyNumbers($tps_kunden[$tps_kunde_key]['telefon2']),
                'fax' => $this->user_input->getOnlyNumbers($tps_kunden[$tps_kunde_key]['fax']),
                'emailadresse' => trim(strtolower($tps_kunden[$tps_kunde_key]['email'])),
                'rechnungsanschrift' => trim($tps_kunden[$tps_kunde_key]['rechnungsanschrift']),
                'rechnungszusatz' => ((trim($tps_kunden[$tps_kunde_key]['kostenstelle']) == "") ? '' : (trim($tps_kunden[$tps_kunde_key]['kostenstellenart']) . " " . trim($tps_kunden[$tps_kunde_key]['kostenstelle'])))
            ];
            $kunde = \ttact\Models\KundeModel::createNew($this->db, $model_data);
            if ($kunde instanceof \ttact\Models\KundeModel) {
                $tps_saved_ids[$tps_kunde_key] = $kunde->getID();
            } else {
                $tps_unable_to_save_kundennummern[$tps_kunde_key] = $kundennummer;
                unset($tps_kundennummern[$tps_kunde_key]);
            }
        }

        // print not imported rows
        $not_imported_rows = count($tps_doubled_kundennummern) /*+ count($ttact_already_has_kundennummern)*/ + count($tps_incorrect_kundennummern) + count($tps_unable_to_save_kundennummern);
        if ($not_imported_rows == 0) {
            $info .= "0 Zeilen wurden nicht importiert.";
        } else {
            $info .= $not_imported_rows . " Zeilen wurden nicht importiert:<br>";
            foreach($tps_doubled_kundennummern as $tps_kunde_key => $kundennummer) {
                $info .= "<kbd>" . $tps_kunden[$tps_kunde_key]['kundenID'] . "@tps.kunden</kbd> Die Kundennummer <strong>" . $kundennummer . "</strong> ist in <strong>tps.kunden</strong> mehrfach vorhanden.<br>";
            }
            //foreach($ttact_already_has_personalnummern as $tps_mitarbeiter_key => $personalnummer) {
            //    $info .= "<kbd>" . $tps_mitarbeiter[$tps_mitarbeiter_key]['kundenID'] . "@tps.kunden</kbd> Die Kundennummer <strong>" . $personalnummer . "</strong> existiert bereits in <strong>ttact.kunde</strong>.<br>";
            //}
            foreach($tps_incorrect_kundennummern as $tps_kunde_key => $grund) {
                $info .= "<kbd>" . $tps_kunden[$tps_kunde_key]['kundenID'] . "@tps.kunden</kbd> Kundennummer <strong>" . $tps_kunden[$tps_kunde_key]['kundennummer'] . "</strong>: " . $grund . "<br>";
            }
            foreach($tps_unable_to_save_kundennummern as $tps_kunde_key => $grund) {
                $info .= "<kbd>" . $tps_kunden[$tps_kunde_key]['kundenID'] . "@tps.kunden</kbd> konnte nicht importiert werden!<br>";
            }
        }

        // print imported rows
        $imported_rows = count($tps_kundennummern);
        if ($imported_rows == 0) {
            $info .= "<br>0 Zeilen wurden importiert.<br>";
        } else {
            $info .= "<br>" . $imported_rows . " Zeilen wurden importiert:<br>";
            foreach($tps_kundennummern as $tps_kunde_key => $kundennummer) {
                $info .= "<kbd>" . $tps_kunden[$tps_kunde_key]['kundenID'] . "@tps.kunden</kbd> nach <kbd>" . $tps_saved_ids[$tps_kunde_key] . "@ttact.kunde</kbd>.<br>";
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
        $tps_db = new \ttact\Database('digehe.han-solo.net', 'root', 'gClVMCJqux0G0', 'tps');

        $info .= "Import von <strong>tps.abteilungen</strong> zu <strong>ttact.abteilungen</strong><br><br>";

        $tps_abteilungen = $tps_db->getRows('abteilung', ['name', 'aID'], [], ['aID']);

        foreach ($tps_abteilungen as $row) {
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
        $tps_db = new \ttact\Database('digehe.han-solo.net', 'root', 'gClVMCJqux0G0', 'tps');

        $mitarbeiter = $tps_db->getRowsQuery("SELECT mitarbeiter.Personalnr, mitarbeiter_intern.manote, mitarbeiter_intern.manote01, mitarbeiter_intern.manote02, mitarbeiter_intern.manote03, mitarbeiter_intern.manote04, mitarbeiter_intern.manote05, mitarbeiter_intern.manote06, mitarbeiter_intern.manote07, mitarbeiter_intern.manote08, mitarbeiter_intern.manote09, mitarbeiter_intern.manote10, mitarbeiter_intern.manote11, mitarbeiter_intern.manote12 FROM mitarbeiter, mitarbeiter_intern WHERE mitarbeiter.Id = mitarbeiter_intern.MitarbeiterId");

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

    public function tagessoll()
    {
        $datetime_april = new \DateTime('2018-04-01 00:00:00');
        $datetime_mai = new \DateTime('2018-05-01 00:00:00');
        $datetime_juni = new \DateTime('2018-06-01 00:00:00');
        $datetime_juli = new \DateTime('2018-07-01 00:00:00');
        $datetime_august = new \DateTime('2018-08-01 00:00:00');

        function setTagessoll(\ttact\Database $db, int $mitarbeiter_id, \DateTime $date, int $jahr, int $monat) {
            $lohnkonfiguration = \ttact\Models\LohnkonfigurationModel::findForMitarbeiterDate($db, $mitarbeiter_id, $date);
            if ($lohnkonfiguration instanceof \ttact\Models\LohnkonfigurationModel) {
                $wochenstunden = $lohnkonfiguration->getWochenstunden();
                $save_tagessoll = $wochenstunden / 6;

                $tagessoll = \ttact\Models\TagessollModel::findByJahrMonatMitarbeiter($db, $jahr, $monat, $mitarbeiter_id);
                if ($tagessoll instanceof \ttact\Models\TagessollModel) {
                    if ($tagessoll->setTagessoll($save_tagessoll)) {
                        // success
                    } else {
                        echo 'MA-ID ' . $mitarbeiter_id . ': Das Tagessoll für ' . $jahr . '-' . $monat . ' konnte nicht aktualisiert werden.' . PHP_EOL;
                    }
                } else {
                    $data = [
                        'mitarbeiter_id' => $mitarbeiter_id,
                        'jahr' => $jahr,
                        'monat' => $monat,
                        'tagessoll' => $save_tagessoll
                    ];
                    $tagessoll = \ttact\Models\TagessollModel::createNew($db, $data);
                    if ($tagessoll instanceof \ttact\Models\TagessollModel) {
                        // success
                    } else {
                        echo 'MA-ID ' . $mitarbeiter_id . ': Das Tagessoll für ' . $jahr . '-' . $monat . ' konnte nicht gespeichert werden.' . PHP_EOL;
                    }
                }
            } else {
                echo 'MA-ID ' . $mitarbeiter_id . ': Es ist keine Lohnkonfiguration für ' . $jahr . '-' . $monat . ' vorhanden. Das Tagessoll konnte nicht gespeichert werden.' . PHP_EOL;
            }
        }

        foreach (\ttact\Models\MitarbeiterModel::findAllLohnberechnung($this->db, $datetime_juli) as $mitarbeiter) {
            if ($mitarbeiter->getEintritt() <= $datetime_april) {
                setTagessoll($this->db, $mitarbeiter->getID(), $datetime_april, 2018, 4);
            }
            if ($mitarbeiter->getEintritt() <= $datetime_mai) {
                setTagessoll($this->db, $mitarbeiter->getID(), $datetime_mai, 2018, 5);
            }
            if ($mitarbeiter->getEintritt() <= $datetime_juni) {
                setTagessoll($this->db, $mitarbeiter->getID(), $datetime_juni, 2018, 6);
            }
            if ($mitarbeiter->getEintritt() <= $datetime_juli) {
                setTagessoll($this->db, $mitarbeiter->getID(), $datetime_juli, 2018, 7);
            }
        }

        $this->template = 'blank';
    }

    public function kundenkonditionen()
    {
        $tps_db = new \ttact\Database('digehe.han-solo.net', 'root', 'gClVMCJqux0G0', 'tps');

        $aid_to_id = [];
        $counter = 1;
        $tps_abteilungen = $tps_db->getRows('abteilung', ['name', 'aid'], [], ['aid']);
        foreach ($tps_abteilungen as $row) {
            $aid_to_id[$row['aid']] = $counter;
            $counter++;
        }

        $tps_kundenkonditionen = $tps_db->getRowsQuery("SELECT kundenkonditionen.id as id, kundennummer, abteilung, preis, zso as sonntagszuschlag, zfei as feiertagszuschlag, znacht as nachtzuschlag, nachtvon as nacht_von, nachbis as nacht_bis FROM kunden, kundenkonditionen WHERE kundenkonditionen.kunde = kunden.kundenID AND istaktiv = 1 AND preis > 0");

        foreach ($tps_kundenkonditionen as $row) {
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
                            echo "Speichern fehlgeschlagen (1): ".$row['id']."<br>";
                        }
                    } else {
                        echo "Speichern fehlgeschlagen (2): ".$row['id']."<br>";
                    }
                } else {
                    echo "Speichern fehlgeschlagen (3): ".$row['id']."<br>";
                }
            } else {
                echo "Speichern fehlgeschlagen (4): ".$row['id']."<br>";
            }
        }

        $this->template = "blank";
    }

    public function rechnungen()
    {
        $info = "";
        $tps_db = new \ttact\Database('digehe.han-solo.net', 'tps', '2B6nNZHHTStxAyLt', 'tps');

        $info .= "Import von <strong>tps.rechnungen</strong> zu <strong>ttact.rechnungen</strong><br><br>";

        // fetch data from tps-table
        $tps_rechnungen = $tps_db->getRowsQuery('SELECT * FROM rechnung WHERE vorschau = 0 ORDER BY rID');
        $tps_unable_to_save_ids = array();
        $tps_saved_ids = array();
        foreach ($tps_rechnungen as $tps_rechnungen_key => $row) {
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
                                                                $tps_saved_ids[$row['rID']] = $rechnung_model->getID();
                                                            } else {
                                                                // error
                                                                $tps_unable_to_save_ids[] = $row['rID'];
                                                                $info .= "<kbd>" . $row['rID'] . "@tps.rechnung</kbd> Ein Rechnungsposten konnte nicht gespeichert werden.<br>";

                                                                foreach ($rechnungsposten_models as $rechnungsposten_model) {
                                                                    $rechnungsposten_model->delete();
                                                                }
                                                                $rechnung_model->delete();
                                                            }
                                                        } else {
                                                            // error
                                                            $tps_unable_to_save_ids[$row['rID']] = "Die Rechnung konnte nicht gespeichert werden.";
                                                        }
                                                    } else {
                                                        // error
                                                        $tps_unable_to_save_ids[$row['rID']] = "Der berechnete Nettobetrag entspricht nicht dem originalen Gesamtpreis.";
                                                    }
                                                } else {
                                                    // error
                                                    $tps_unable_to_save_ids[$row['rID']] = "Das Datum für 'bezahlt am' ist ungültig.";
                                                }
                                            } else {
                                                // error
                                                $tps_unable_to_save_ids[$row['rID']] = "Die Rechnungsnummern stimmen nicht überein.";
                                            }
                                        } else {
                                            // error
                                            $tps_unable_to_save_ids[$row['rID']] = "Die Rechnungsnummer konnte nicht extrahiert werden.";
                                        }
                                    } else {
                                        // error
                                        $tps_unable_to_save_ids[$row['rID']] = "Das Datum für 'Zahlungsziel' ist ungültig.";
                                    }
                                } else {
                                    // error
                                    $tps_unable_to_save_ids[$row['rID']] = "Das Datum für 'Zeitraum bis' ist ungültig.";
                                }
                            } else {
                                // error
                                $tps_unable_to_save_ids[$row['rID']] = "Das Datum für 'Zeitraum von' ist ungültig.";
                            }
                        } else {
                            // error
                            $tps_unable_to_save_ids[$row['rID']] = "Das Rechnungsdatum ist ungültig.";
                        }
                    } else {
                        // error
                        $tps_unable_to_save_ids[$row['rID']] = "Das Stornierungsdatum ist ungültig.";
                    }
                } else {
                    // error
                    $tps_unable_to_save_ids[$row['rID']] = "Der Kunde konnte in ttact.kunde nicht gefunden werden.";
                }
            } else {
                // error
                $tps_unable_to_save_ids[$row['rID']] = "Es ist keine Kundennummer vorhanden.";
            }
        }

        // print not imported rows
        $not_imported_rows = count($tps_unable_to_save_ids) ;
        if ($not_imported_rows == 0) {
            $info .= "0 Zeilen wurden nicht importiert.";
        } else {
            $info .= $not_imported_rows . " Zeilen wurden nicht importiert:<br>";
            foreach ($tps_unable_to_save_ids as $tps_id => $message) {
                $info .= "<kbd><a href='http://tps2.c-multimedia.de/pdf/create_rechnung.php?rID=" . $tps_id . "' target='_blank'>" . $tps_id . "@tps.rechnung</a></kbd> " . $message . "<br>";
            }
        }

        // print imported rows
        $imported_rows = count($tps_saved_ids);
        if ($imported_rows == 0) {
            $info .= "<br>0 Zeilen wurden importiert.<br>";
        } else {
            $info .= "<br>" . $imported_rows . " Zeilen wurden importiert:<br>";

            foreach ($tps_saved_ids as $tps_id => $ttact_id) {
                $info .= "<kbd><a href='http://tps2.c-multimedia.de/pdf/create_rechnung.php?rID=" . $tps_id . "' target='_blank'>" . $tps_id . "@tps.rechnung</a></kbd> nach <kbd><a href='/rechnungen/pdf/" . $ttact_id . "' target='_blank'>" . $ttact_id . "@ttact.rechnung</a></kbd><br>";
            }
        }

        // template content
        $this->smarty_vars['info'] = $info;

        // template settings
        $this->template = 'main';
    }
}
