<?php

// show all possible errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(-1);

// set correct time zone
ini_set('date.timezone', 'Europe/Berlin');

// set maximum execution time to forever
ini_set('max_execution_time', 0);

// load libraries
require '../libs/ttact/autoload-script.php';

// create a new Database instance
$db = new \ttact\Database('localhost', 'root', 'lacowu#7', 'ttact');

// create a new UserInput instance
$user_input = new \ttact\UserInput([], []);

// APS Database
$aps_db = new \ttact\Database('liluye.han-solo.net', 'aps', 'dJqQQWss2Bj9rzhv', 'aps');

// current user
$current_user = \ttact\Models\UserModel::findByID($db, 1);

// let the fun begin
echo "Import von aps.aufträgen zu ttact.aufträgen" . PHP_EOL;


//test start
$fID_to_id = [];
$counterf = 1;

$aps_abteilungen_price = $aps_db->getRows('kundenkondition', ['pries'], []);

foreach ($aps_abteilungen_price as $row) {
    $aID_to_idf[$row['preis']] = $counterf;
    $counterf++;
}
//test end


// associate aps.abteilung ids to ttact.abteilung ids
$aID_to_id = [];
$counter = 1;

$aps_abteilungen = $aps_db->getRows('abteilung', ['name', 'aID'], [], ['aID']);

foreach ($aps_abteilungen as $row) {
    $aID_to_id[$row['aID']] = $counter;
    $counter++;
}

// let the fun begin once more
//$aps_auftraege = $aps_db->getRows('auftraege', [], ['von'], ['aID'], 'DESC');
$aps_auftraege = $aps_db->getRowsQuery("SELECT * FROM auftraege WHERE ((y = '2017' AND kw <= '31') OR (y < 2017)) AND NOT (y = '2017' AND kw = '31' AND tag != '1') ORDER BY aID DESC");
foreach ($aps_auftraege as $row) {
    if ($row['deletetime'] == 0) {
        $save_kunde_id = '';
        $save_abteilung_id = '';
        $save_mitarbeiter_id = '';
        $save_pause = '';
        $save_status = '';
        $save_von = '';
        $save_bis = '';

        $aps_kunde = $aps_db->getFirstRow('kunden', ['kundenID' => $row['kunde']], ['kundennummer']);
        if (isset($aps_kunde['kundennummer'])) {
            $aps_kunde_kundennummer = $user_input->getOnlyNumbers($aps_kunde['kundennummer']);
            $ttact_kunde = \ttact\Models\KundeModel::findByKundennummer($db, $aps_kunde_kundennummer);
            if ($ttact_kunde instanceof \ttact\Models\KundeModel) {
                $save_kunde_id = $ttact_kunde->getID();
                $aps_abteilung_id = $user_input->getOnlyNumbers($row['abteilung']);
                if (isset($aID_to_id[$aps_abteilung_id])) {
                    $ttact_abteilung = \ttact\Models\AbteilungModel::findByID($db, $aID_to_id[$aps_abteilung_id]);
                    if ($ttact_abteilung instanceof \ttact\Models\AbteilungModel) {
                        $save_abteilung_id = $ttact_abteilung->getID();

                        $mitarbeiter_error = false;

                        // mitarbeiter_id
                        $aps_mitarbeiter_id = $user_input->getOnlyNumbers($row['mid']);
                        if ($aps_mitarbeiter_id != 0) {
                            $aps_mitarbeiter = $aps_db->getFirstRow('mitarbeiter', ['Id' => $aps_mitarbeiter_id]);
                            if (isset($aps_mitarbeiter['Id'])) {
                                $aps_mitarbeiter_personalnummer = $user_input->getOnlyNumbers($aps_mitarbeiter['Personalnr']);
                                $ttact_mitarbeiter = \ttact\Models\MitarbeiterModel::findByPersonalnummer($db, $aps_mitarbeiter_personalnummer);
                                if ($ttact_mitarbeiter instanceof \ttact\Models\MitarbeiterModel) {
                                    $save_mitarbeiter_id = $ttact_mitarbeiter->getID();
                                } else {
                                    echo "" . $row['aID'] . "@aps.aufträge Der Mitarbeiter konnte in ttact nicht gefunden werden." . PHP_EOL;
                                    $mitarbeiter_error = true;
                                }
                            } else {
                                echo "" . $row['aID'] . "@aps.aufträge Der Mitarbeiter konnte in APS nicht gefunden werden." . PHP_EOL;
                                $mitarbeiter_error = true;
                            }
                        }

                        if (!$mitarbeiter_error) {
                            $kalenderwoche_fehler = false;

                            // Datum: von, bis
                            $aps_y = (int) $user_input->getOnlyNumbers($row['y']);
                            $aps_kw = (int) $user_input->getOnlyNumbers($row['kw']);
                            $aps_tag = (int) $user_input->getOnlyNumbers($row['tag']);

                            $aps_datetime = new \DateTime();
                            $aps_datetime->setISODate($aps_y, $aps_kw, $aps_tag);
                            $aps_datetime_kw = (int) $aps_datetime->format("W");
                            $aps_datetime_tag = (int) $aps_datetime->format("N");
                            if ($aps_datetime_kw != $aps_kw || $aps_datetime_tag != $aps_tag) {
                                $kalenderwoche_fehler = true;
                                echo "" . $row['aID'] . "@aps.aufträge Die Kalenderwoche gehört nicht zum angegebenen Jahr." . PHP_EOL;
                            }

                            if (!$kalenderwoche_fehler) {
                                // Uhrzeit: von, bis
                                $aps_von = $user_input->getOnlyNumbers($row['von']);
                                $aps_von_datetime = new \DateTime();
                                $aps_von_datetime->setTimestamp($aps_von);
                                $save_von = new \DateTime($aps_datetime->format("Y-m-d") . " " . $aps_von_datetime->format("H:i") . ":00");

                                $aps_bis = $user_input->getOnlyNumbers($row['bis']);
                                $aps_bis_datetime = new \DateTime();
                                $aps_bis_datetime->setTimestamp($aps_bis);
                                $save_bis = new \DateTime($aps_datetime->format("Y-m-d") . " " . $aps_bis_datetime->format("H:i") . ":00");

                                if ($save_bis < $save_von) {
                                    $save_bis->add(new \DateInterval("P0000-00-01T00:00:00"));
                                }

                                // pause
                                $pause = $row['pause'];
                                if ($pause != null) {
                                    if (strtolower($pause) != "null" && $pause != '') {
                                        $seconds = $pause * 3600;
                                        $save_pause = gmdate("H:i", $seconds) . ":00";
                                    }
                                }

                                // status
                                $status_error = false;
                                if ($save_mitarbeiter_id == '') {
                                    $save_status = 'offen';
                                    $save_pause = '00:00:00';
                                } elseif ($row['aktivtime'] == 0) {
                                    $save_status = 'nicht_benachrichtigt';
                                    $save_pause = '00:00:00';
                                } elseif ($row['pause'] == null && $row['color'] == '') {
                                    $save_status = 'benachrichtigt';
                                    $save_pause = '00:00:00';
                                } elseif ($row['pause'] == null && $row['color'] == '#99CC00') {
                                    $save_status = 'nicht_bestaetigt';
                                    $save_pause = '00:00:00';
                                } elseif ($row['pause'] == null && $row['color'] == '#990000') {
                                    $save_status = 'kann_nicht';
                                    $save_pause = '00:00:00';
                                } elseif ($row['pause'] == null && $row['color'] == '#663300') {
                                    $save_status = 'kann_andere_uhrzeit';
                                    $save_pause = '00:00:00';
                                } elseif ($row['pause'] != null && $row['close'] == 0) {
                                    $save_status = 'stundenzettel_bestaetigt';
                                } elseif ($row['close'] != 0) {
                                    $save_status = 'archiviert';
                                } else {
                                    $status_error = true;
                                    echo "" . $row['aID'] . "@aps.aufträge Der Status konnte nicht ermittelt werden." . PHP_EOL;
                                }

                                // save
                                if (!$status_error) {
                                    $data = [
                                        'kunde_id' => $save_kunde_id,
                                        'abteilung_id' => $save_abteilung_id,
                                        'mitarbeiter_id' => $save_mitarbeiter_id,
                                        'status' => $save_status,
                                        'von' => $save_von->format('Y-m-d H:i:s'),
                                        'bis' => $save_bis->format('Y-m-d H:i:s'),
                                        'pause' => $save_pause
                                    ];
                                    $auftrag_model = \ttact\Models\AuftragModel::createNew($db, $current_user, $data);
                                    if (!$auftrag_model instanceof \ttact\Models\AuftragModel) {
                                        echo "" . $row['aID'] . "@aps.aufträge Beim Speichern ist ein Fehler aufgetreten." . PHP_EOL;
                                    }
                                }
                            }
                        }
                    } else {
                        echo "" . $row['aID'] . "@aps.aufträge Die Abteilung konnte nicht geladen werden." . PHP_EOL;
                    }
                } else {
                    echo "" . $row['aID'] . "@aps.aufträge Die Abteilung existiert nicht." . PHP_EOL;
                }
            } else {
                echo "" . $row['aID'] . "@aps.aufträge Der Kunde ist ungültig." . PHP_EOL;
            }
        } else {
            echo "" . $row['aID'] . "@aps.aufträge Der Kunde ist ungültig." . PHP_EOL;
        }
    }
}
