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
echo "Import von tps.paletten zu ttact.palette" . PHP_EOL;

$tps_paletten = $tps_db->getRows('paletten', [], [], ['wpID'], 'DESC');

// translate tps.abteilungen to ttact.abteilung
$tps_abteilung_id_to_ttact_abteilung_id = [];
$counter = 1;
$tps_abteilungen = $tps_db->getRows('abteilung', ['name', 'aid'], [], ['aid']);
foreach ($tps_abteilungen as $row) {
    $tps_abteilung_id_to_ttact_abteilung_id[$row['aid']] = $counter;
    $counter++;
}

// save tps.paletten in ttact.palette
$errors = [];
foreach ($tps_paletten as $row) {
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
                    $save_abteilung_id = $ttact_abteilung->getID();

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
                            $save_datum = $tps_datetime->format('Y-m-d');
                            if ($row['anzahl'] != '') {
                                $save_anzahl = (float) $row['anzahl'];
                                if ($save_anzahl == $row['anzahl']) {
                                    if ($save_anzahl > 0) {
                                        if (!$ttact_abteilung->getPalettenabteilung()) {
                                            $ttact_abteilung->setPalettenabteilung(1);
                                        }

                                        $previous_anzahl = 0;

                                        $delete_error = false;

                                        foreach (\ttact\Models\PaletteModel::findAllByYearWeekDayKundeAbteilung($db, $tps_y, $tps_kw, $tps_tag, $ttact_kunde->getID(), $ttact_abteilung->getID()) as $palette) {
                                            $previous_anzahl += $palette->getAnzahl();
                                            if (!$palette->delete()) {
                                                $delete_error = true;
                                            }
                                        }

                                        if (!$delete_error) {
                                            $data = [
                                                'kunde_id' => $save_kunde_id,
                                                'abteilung_id' => $save_abteilung_id,
                                                'datum' => $save_datum,
                                                'anzahl' => $previous_anzahl + $save_anzahl
                                            ];
                                            $ttact_palette_model = \ttact\Models\PaletteModel::createNew($db, $data);
                                            if ($ttact_palette_model instanceof \ttact\Models\PaletteModel) {
                                                //
                                            }
                                            else {
                                                echo $row['wpID'] . '@tps.paletten: Speichern in ttact fehlgeschlagen.' . PHP_EOL;
                                            }
                                        } else {
                                            echo $row['wpID'] . '@tps.paletten: Das Löschen von bereits vorhandenen Paletteneinträgen ist fehlgeschlagen.' . PHP_EOL;
                                        }
                                    }
                                } else {
                                    echo $row['wpID'] . '@tps.paletten: Die Anzahl ist fehlerhaft.' . PHP_EOL;
                                }
                            } else {
                                echo $row['wpID'] . '@tps.paletten: Die Anzahl ist leer.' . PHP_EOL;
                            }
                        } else {
                            echo $row['wpID'] . '@tps.paletten: Der Wochentag gehört nicht zum angegebenen Datum.' . PHP_EOL;
                        }
                    } else {
                        echo $row['wpID'] . '@tps.paletten: Die Kalenderwoche gehört nicht zum angegebenen Jahr.' . PHP_EOL;
                    }
                } else {
                    echo $row['wpID'] . '@tps.paletten: Die Abteilung existiert in ttact nicht.' . PHP_EOL;
                }
            } else {
                echo $row['wpID'] . '@tps.paletten: Die Abteilung existiert in ttact nicht.' . PHP_EOL;
            }
        } else {
            echo $row['wpID'] . '@tps.paletten: Der Kunde existiert in ttact nicht.' . PHP_EOL;
        }
    } else {
        echo $row['wpID'] . '@tps.paletten: Der Kunde existiert in TPS nicht.' . PHP_EOL;
    }
}
