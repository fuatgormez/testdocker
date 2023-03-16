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
echo "Import von tps.sonderauftrag zu ttact.auftrag" . PHP_EOL;

$tps_sonderauftraege = $tps_db->getRows('sonderauftrag', [], [['deletetime', '=', 0]], ['shID'], 'DESC');

// translate tps.abteilungen to ttact.abteilung
$tps_abteilung_id_to_ttact_abteilung_id = [];
$counter = 1;
$tps_abteilungen = $tps_db->getRows('abteilung', ['name', 'aid'], [], ['aid']);
foreach ($tps_abteilungen as $row) {
    $tps_abteilung_id_to_ttact_abteilung_id[$row['aid']] = $counter;
    $counter++;
}

// save tps.sonderauftrag in ttact.auftrag
$errors = [];

$ttact_palettenabteilung_to_stundenabteilung = [];

$mitarbeiter_model = \ttact\Models\MitarbeiterModel::findByPersonalnummer($db, 12260);

foreach ($tps_sonderauftraege as $row) {
    $tps_kunde = $tps_db->getFirstRow('kunden', ['kundenID' => $row['kunde']], ['kundennummer']);
    if (isset($tps_kunde['kundennummer'])) {
        $tps_kunde_kundennummer = $user_input->getOnlyNumbers($tps_kunde['kundennummer']);
        $ttact_kunde = \ttact\Models\KundeModel::findByKundennummer($db, $tps_kunde_kundennummer);
        if ($ttact_kunde instanceof \ttact\Models\KundeModel) {
            $save_kunde_id = $ttact_kunde->getID();
            $tps_abteilung_id = $user_input->getOnlyNumbers($row['abteilung']);
            if (isset($tps_abteilung_id_to_ttact_abteilung_id[$tps_abteilung_id])) {
                $ttact_abteilung = \ttact\Models\AbteilungModel::findByID($db, $tps_abteilung_id_to_ttact_abteilung_id[$tps_abteilung_id]);
                if ($ttact_abteilung instanceof \ttact\Models\AbteilungModel) {
                    // Datum: von, bis
                    $tps_y = (int) $user_input->getOnlyNumbers($row['y']);
                    $tps_kw = (int) $user_input->getOnlyNumbers($row['kw']);
                    $tps_tag = (int) $user_input->getOnlyNumbers($row['tag']);

                    $tps_datetime = new \DateTime();
                    $tps_datetime->setISODate($tps_y, $tps_kw, $tps_tag);
                    $tps_datetime_kw = (int) $tps_datetime->format("W");
                    $tps_datetime_tag = (int) $tps_datetime->format("N");
                    if ($tps_datetime_kw == $tps_kw) {
                        if ($tps_datetime_tag == $tps_tag) {
                            if ($row['stunden'] != '') {
                                $save_stunden = (float) $row['stunden'];
                                if ($save_stunden == $row['stunden']) {
                                    if ($save_stunden > 0) {
                                        $palettenabteilung_stundenabteilung_error = false;

                                        if ($ttact_abteilung->getPalettenabteilung()) {
                                            if (isset($ttact_palettenabteilung_to_stundenabteilung[$ttact_abteilung->getID()])) {
                                                $save_abteilung_id = $ttact_palettenabteilung_to_stundenabteilung[$ttact_abteilung->getID()];
                                            } else {
                                                $data = [
                                                    'bezeichnung' => $ttact_abteilung->getBezeichnung() . ' (Vorziehen)',
                                                    'in_rechnung_stellen' => 1,
                                                    'palettenabteilung' => 0
                                                ];
                                                $ttact_stundenabteilung = \ttact\Models\AbteilungModel::createNew($db, $data);
                                                if ($ttact_stundenabteilung instanceof \ttact\Models\AbteilungModel) {
                                                    $ttact_palettenabteilung_to_stundenabteilung[$ttact_abteilung->getID()] = $ttact_stundenabteilung->getID();
                                                    $save_abteilung_id = $ttact_stundenabteilung->getID();
                                                } else {
                                                    $palettenabteilung_stundenabteilung_error = true;
                                                }
                                            }
                                        } else {
                                            $save_abteilung_id = $ttact_abteilung->getID();
                                        }

                                        if (!$palettenabteilung_stundenabteilung_error) {
                                            $save_von = clone $tps_datetime;
                                            if ($row['nacht'] == '1') {
                                                $save_von->setTime(3, 0, 0);
                                            } else {
                                                $save_von->setTime(15, 0, 0);
                                            }

                                            if ($save_stunden < 1) {
                                                $save_bis = clone $save_von;
                                                $save_bis->setTimestamp($save_von->getTimestamp() + $save_stunden * 3600);

                                                $data = [
                                                    'kunde_id' => $save_kunde_id,
                                                    'abteilung_id' => $save_abteilung_id,
                                                    'mitarbeiter_id' => $mitarbeiter_model->getID(),
                                                    'status' => 'archiviert',
                                                    'von' => $save_von->format('Y-m-d H:i:s'),
                                                    'bis' => $save_bis->format('Y-m-d H:i:s'),
                                                    'pause' => '00:00:00',
                                                    'zusatzschicht' => 0
                                                ];
                                                $ttact_auftrag_model = \ttact\Models\AuftragModel::createNew($db, $current_user, $data);
                                                if ($ttact_auftrag_model instanceof \ttact\Models\AuftragModel) {
                                                    //
                                                } else {
                                                    echo $row['shID'] . '@tps.sonderauftrag: Speichern in ttact fehlgeschlagen.' . PHP_EOL;
                                                }
                                            } else {
                                                $save_bis = clone $save_von;
                                                $save_bis->add(new \DateInterval('P0000-00-00T01:00:00'));

                                                for ($i = 1; $i <= floor($save_stunden); $i++) {
                                                    $data = [
                                                        'kunde_id' => $save_kunde_id,
                                                        'abteilung_id' => $save_abteilung_id,
                                                        'mitarbeiter_id' => $mitarbeiter_model->getID(),
                                                        'status' => 'archiviert',
                                                        'von' => $save_von->format('Y-m-d H:i:s'),
                                                        'bis' => $save_bis->format('Y-m-d H:i:s'),
                                                        'pause' => '00:00:00',
                                                        'zusatzschicht' => 0
                                                    ];
                                                    $ttact_auftrag_model = \ttact\Models\AuftragModel::createNew($db, $current_user, $data);
                                                    if ($ttact_auftrag_model instanceof \ttact\Models\AuftragModel) {
                                                        //
                                                    } else {
                                                        echo $row['shID'] . '@tps.sonderauftrag: Speichern in ttact fehlgeschlagen.' . PHP_EOL;
                                                    }
                                                }

                                                if (fmod($save_stunden, floor($save_stunden)) > 0) {
                                                    $save_bis = clone $save_von;
                                                    $save_bis->setTimestamp($save_von->getTimestamp() + fmod($save_stunden, floor($save_stunden)) * 3600);

                                                    $data = [
                                                        'kunde_id' => $save_kunde_id,
                                                        'abteilung_id' => $save_abteilung_id,
                                                        'mitarbeiter_id' => $mitarbeiter_model->getID(),
                                                        'status' => 'archiviert',
                                                        'von' => $save_von->format('Y-m-d H:i:s'),
                                                        'bis' => $save_bis->format('Y-m-d H:i:s'),
                                                        'pause' => '00:00:00',
                                                        'zusatzschicht' => 0
                                                    ];
                                                    $ttact_auftrag_model = \ttact\Models\AuftragModel::createNew($db, $current_user, $data);
                                                    if ($ttact_auftrag_model instanceof \ttact\Models\AuftragModel) {
                                                        //
                                                    } else {
                                                        echo $row['shID'] . '@tps.sonderauftrag: Speichern in ttact fehlgeschlagen.' . PHP_EOL;
                                                    }
                                                }
                                            }
                                        } else {
                                            echo $row['shID'] . '@tps.sonderauftrag: Das Erstellen einer Stundenabteilung zur Palettenabteilung ist fehlgeschlagen.' . PHP_EOL;
                                        }
                                    } elseif ($save_stunden < 0) {
                                        echo $row['shID'] . '@tps.sonderauftrag: Die Stundenanzahl ist fehlerhaft.' . PHP_EOL;
                                    }
                                } else {
                                    echo $row['shID'] . '@tps.sonderauftrag: Die Stundenanzahl ist fehlerhaft.' . PHP_EOL;
                                }
                            } else {
                                echo $row['shID'] . '@tps.sonderauftrag: Die Stundenanzahl ist leer.' . PHP_EOL;
                            }
                        } else {
                            echo $row['shID'] . '@tps.sonderauftrag: Der Wochentag gehört nicht zum angegebenen Datum.' . PHP_EOL;
                        }
                    } else {
                        echo $row['shID'] . '@tps.sonderauftrag: Die Kalenderwoche gehört nicht zum angegebenen Jahr.' . PHP_EOL;
                    }
                } else {
                    echo $row['shID'] . '@tps.sonderauftrag: Die Abteilung existiert in ttact nicht.' . PHP_EOL;
                }
            } else {
                echo $row['shID'] . '@tps.sonderauftrag: Die Abteilung existiert in ttact nicht.' . PHP_EOL;
            }
        } else {
            echo $row['shID'] . '@tps.sonderauftrag: Der Kunde existiert in ttact nicht.' . PHP_EOL;
        }
    } else {
        echo $row['shID'] . '@tps.sonderauftrag: Der Kunde existiert in TPS nicht.' . PHP_EOL;
    }
}
