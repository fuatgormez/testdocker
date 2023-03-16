<?php

// show all possible errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(-1);

// set correct time zone
ini_set('date.timezone', 'Europe/Berlin');

// set maximum execution time to forever
ini_set('max_execution_time', 0);

// set company
$company = 'tps';

// include/register autoloaders
spl_autoload_register(function ($class_name) use ($company) {
    // project-specific namespace prefix
    $prefix = 'ttact\\';

    // base directory for the namespace prefix
    $base_dir = __DIR__ . '/../libs/ttact/';

    // does the class use the namespace prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $class_name, $len) !== 0) {
        // no, move to the next registered autoloader
        return;
    }

    // get the relative class name
    $relative_class = substr($class_name, $len);

    // replace the namespace prefix with the base directory, replace namespace
    // separators with directory separators in the relative class name, append
    // with .php
    $file = $base_dir . str_replace('\\', '/', $relative_class);

    if (file_exists($file . '.' . $company . '.php')) {
        // if the file exists with company suffix, require it
        require $file . '.' . $company . '.php';
    } elseif (file_exists($file . '.php')) {
        // otherwise try without company suffix
        require $file . '.php';
    }
});

// create a new Database instance
$db = new \ttact\Database('localhost', 'root', 'lacowu#7', 'tps');

// create a new UserInput instance
$user_input = new \ttact\UserInput([], []);

// TPS Database
$tps_db = new \ttact\Database('digehe.han-solo.net', 'root', 'gClVMCJqux0G0', 'tps');

// current user
$current_user = \ttact\Models\UserModel::findByID($db, 1);

// let the fun begin
echo "Import von tps.aufträgen zu ttact.aufträgen" . PHP_EOL;

// associate tps.abteilung ids to ttact.abteilung ids
$aID_to_id = [];
$counter = 1;

$tps_abteilungen = $tps_db->getRows('abteilung', ['name', 'aID'], [], ['aID']);

foreach ($tps_abteilungen as $row) {
    $aID_to_id[$row['aID']] = $counter;
    $counter++;
}

// let the fun begin once more
//$tps_auftraege = $tps_db->getRows('auftraege', [], ['von'], ['aID'], 'DESC');
$tps_auftraege = $tps_db->getRowsQuery("SELECT * FROM auftraege ORDER BY aID DESC");
# WHERE ((y = '2017' AND kw <= '31') OR (y < 2017)) AND NOT (y = '2017' AND kw = '31' AND tag != '1')
foreach ($tps_auftraege as $row) {
    if ($row['deletetime'] == 0) {
        $save_kunde_id = '';
        $save_abteilung_id = '';
        $save_mitarbeiter_id = '';
        $save_pause = '';
        $save_status = '';
        $save_von = '';
        $save_bis = '';

        $tps_kunde = $tps_db->getFirstRow('kunden', ['kundenID' => $row['kunde']], ['kundennummer']);
        if (isset($tps_kunde['kundennummer'])) {
            $tps_kunde_kundennummer = $user_input->getOnlyNumbers($tps_kunde['kundennummer']);
            $ttact_kunde = \ttact\Models\KundeModel::findByKundennummer($db, $tps_kunde_kundennummer);
            if ($ttact_kunde instanceof \ttact\Models\KundeModel) {
                $save_kunde_id = $ttact_kunde->getID();
                $tps_abteilung_id = $user_input->getOnlyNumbers($row['abteilung']);
                if (isset($aID_to_id[$tps_abteilung_id])) {
                    $ttact_abteilung = \ttact\Models\AbteilungModel::findByID($db, $aID_to_id[$tps_abteilung_id]);
                    if ($ttact_abteilung instanceof \ttact\Models\AbteilungModel) {
                        $save_abteilung_id = $ttact_abteilung->getID();

                        $mitarbeiter_error = false;

                        // mitarbeiter_id
                        $tps_mitarbeiter_id = $user_input->getOnlyNumbers($row['mid']);
                        if ($tps_mitarbeiter_id != 0) {
                            $tps_mitarbeiter = $tps_db->getFirstRow('mitarbeiter', ['Id' => $tps_mitarbeiter_id]);
                            if (isset($tps_mitarbeiter['Id'])) {
                                $tps_mitarbeiter_personalnummer = $user_input->getOnlyNumbers($tps_mitarbeiter['Personalnr']);
                                $ttact_mitarbeiter = \ttact\Models\MitarbeiterModel::findByPersonalnummer($db, $tps_mitarbeiter_personalnummer);
                                if ($ttact_mitarbeiter instanceof \ttact\Models\MitarbeiterModel) {
                                    $save_mitarbeiter_id = $ttact_mitarbeiter->getID();
                                } else {
                                    echo "" . $row['aID'] . "@tps.aufträge Der Mitarbeiter konnte in ttact nicht gefunden werden." . PHP_EOL;
                                    $mitarbeiter_error = true;
                                }
                            } else {
                                echo "" . $row['aID'] . "@tps.aufträge Der Mitarbeiter konnte in TPS nicht gefunden werden." . PHP_EOL;
                                $mitarbeiter_error = true;
                            }
                        }

                        if (!$mitarbeiter_error) {
                            $kalenderwoche_fehler = false;

                            // Datum: von, bis
                            $tps_y = (int) $user_input->getOnlyNumbers($row['y']);
                            $tps_kw = (int) $user_input->getOnlyNumbers($row['kw']);
                            $tps_tag = (int) $user_input->getOnlyNumbers($row['tag']);

                            $tps_datetime = new \DateTime();
                            $tps_datetime->setISODate($tps_y, $tps_kw, $tps_tag);
                            $tps_datetime_kw = (int) $tps_datetime->format("W");
                            $tps_datetime_tag = (int) $tps_datetime->format("N");
                            if ($tps_datetime_kw != $tps_kw || $tps_datetime_tag != $tps_tag) {
                                $kalenderwoche_fehler = true;
                                echo "" . $row['aID'] . "@tps.aufträge Die Kalenderwoche gehört nicht zum angegebenen Jahr." . PHP_EOL;
                            }

                            if (!$kalenderwoche_fehler) {
                                // Uhrzeit: von, bis
                                $tps_von = $user_input->getOnlyNumbers($row['von']);
                                $tps_von_datetime = new \DateTime();
                                $tps_von_datetime->setTimestamp($tps_von);
                                $save_von = new \DateTime($tps_datetime->format("Y-m-d") . " " . $tps_von_datetime->format("H:i") . ":00");

                                $tps_bis = $user_input->getOnlyNumbers($row['bis']);
                                $tps_bis_datetime = new \DateTime();
                                $tps_bis_datetime->setTimestamp($tps_bis);
                                $save_bis = new \DateTime($tps_datetime->format("Y-m-d") . " " . $tps_bis_datetime->format("H:i") . ":00");

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
                                    echo "" . $row['aID'] . "@tps.aufträge Der Status konnte nicht ermittelt werden." . PHP_EOL;
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
                                        echo "" . $row['aID'] . "@tps.aufträge Beim Speichern ist ein Fehler aufgetreten." . PHP_EOL;
                                    }
                                }
                            }
                        }
                    } else {
                        echo "" . $row['aID'] . "@tps.aufträge Die Abteilung konnte nicht geladen werden." . PHP_EOL;
                    }
                } else {
                    echo "" . $row['aID'] . "@tps.aufträge Die Abteilung existiert nicht." . PHP_EOL;
                }
            } else {
                echo "" . $row['aID'] . "@tps.aufträge Der Kunde ist ungültig." . PHP_EOL;
            }
        } else {
            echo "" . $row['aID'] . "@tps.aufträge Der Kunde ist ungültig." . PHP_EOL;
        }
    }
}

