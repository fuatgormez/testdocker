<?php

namespace ttact\Controllers;

class AbteilungenController extends Controller
{
    public function index()
    {
        if (isset($this->params[0])) {
            if ($this->params[0] == "fehler") {
                $this->smarty_vars['warning'] = "Die Abteilung konnte nicht gefunden werden.";
            }
        }

        // Erstellen

            $error = "";
            $success = "";
            if (isset($this->params[0])) {
                if ($this->params[0] == "erfolgreich") {
                    $success = "Die Abteilung wurde erfolgreich angelegt.";
                }
            }

            // array with all input data
            $i = [
                'bezeichnung' => '',
                'in_rechnung_stellen' => ''
            ];
            if ($this->company == 'tps') {
                $i['palettenabteilung'] = '';
            }
            foreach ($i as $key => $value) {
                $i[$key] = $this->user_input->getPostParameter($key);
            }

            // check and save
            $check_and_save = false;
            if ($this->company == 'tps') {
                if ($i['bezeichnung'] != "" || $i['in_rechnung_stellen'] != "" || $i['palettenabteilung'] != "") {
                    $check_and_save = true;
                }
            } else {
                if ($i['bezeichnung'] != "" || $i['in_rechnung_stellen'] != "") {
                    $check_and_save = true;
                }
            }

            if ($check_and_save) {
                if ($i['bezeichnung'] == "") {
                    $error = "Bitte geben Sie eine Bezeichnung ein.";
                } elseif ($i['in_rechnung_stellen'] != "ja" && $i['in_rechnung_stellen'] != "nein") {
                    $error = "Bitte geben Sie an, ob die Abteilung Kunden in Rechnung gestellt werden soll.";
                } else {
                    $save_data = false;
                    if ($this->company == 'tps') {
                        if ($i['palettenabteilung'] != "ja" && $i['palettenabteilung'] != "nein") {
                            $error = "Bitte geben Sie an, ob bei dieser Abteilung Paletten verräumt werden.";
                        } else {
                            $save_data = true;
                        }
                    } else {
                        $save_data = true;
                    }

                    if ($save_data)  {
                        // save the data and check if it worked
                        $in_rechnung_stellen = 1;
                        if ($i['in_rechnung_stellen'] == 'nein') {
                            $in_rechnung_stellen = 0;
                        }

                        if ($this->company == 'tps') {
                            $palettenabteilung = 0;
                            if ($i['palettenabteilung'] == 'ja') {
                                $palettenabteilung = 1;
                            }

                            $data = [
                                'bezeichnung' => $i['bezeichnung'],
                                'in_rechnung_stellen' => $in_rechnung_stellen,
                                'palettenabteilung' => $palettenabteilung
                            ];
                        } else {
                            $data = [
                                'bezeichnung' => $i['bezeichnung'],
                                'in_rechnung_stellen' => $in_rechnung_stellen
                            ];
                        }
                        $new = \ttact\Models\AbteilungModel::createNew($this->db, $data);
                        if ($new instanceof \ttact\Models\AbteilungModel) {
                            $this->misc_utils->redirect('abteilungen', 'index', 'erfolgreich');
                        } else {
                            $error = "Beim Anlegen der Abteilung ist ein Fehler aufgetreten.";
                        }
                    }
                }

            }

            // display error message
            if ($error != "") {
                $this->smarty_vars['error'] = $error;
                $this->smarty_vars['values'] = $i;
            } elseif ($success != "") {
                $this->smarty_vars['success'] = $success;
            }


        // Alle anzeigen

            $abteilungen = \ttact\Models\AbteilungModel::findAll($this->db);
            $abteilungsliste = [];
            foreach ($abteilungen as $a) {
                $abteilungsliste[$a->getID()] = [
                    'id'                => $a->getID(),
                    'bezeichnung'       => $a->getBezeichnung(),
                    'in_rechnung_stellen' => $a->getInRechnungStellen() ? 'ja' : 'nein'
                ];

                if ($this->company == 'tps') {
                    $abteilungsliste[$a->getID()]['palettenabteilung'] = $a->getPalettenabteilung() ? 'ja' : 'nein';
                }
            }
            ksort($abteilungsliste);
            $this->smarty_vars['abteilungsliste'] = $abteilungsliste;

        // template settings
        $this->template = 'main';
    }

    public function bearbeiten()
    {
        $redirect = true;

        if (isset($this->params[0])) {
            if ($this->user_input->isPositiveInteger($this->params[0])) {
                $abteilung = \ttact\Models\AbteilungModel::findByID($this->db, $this->user_input->getOnlyNumbers($this->params[0]));
                if ($abteilung instanceof \ttact\Models\AbteilungModel) {
                    $redirect = false;

                    $error = "";
                    $success = "";

                    // array with all input data
                    $i = [
                        'id'            => '',
                        'bezeichnung'   => '',
                        'in_rechnung_stellen' => ''
                    ];
                    if ($this->company == 'tps') {
                        $i['palettenabteilung'] = '';
                    }
                    foreach ($i as $key => $value) {
                        $i[$key] = $this->user_input->getPostParameter($key);
                    }

                    // check and save
                    $check_and_save = false;
                    if ($this->company == 'tps') {
                        if ($i['bezeichnung'] != "" && $i['in_rechnung_stellen'] != "" && $i['palettenabteilung'] != "") {
                            $check_and_save = true;
                        }
                    } else {
                        if ($i['bezeichnung'] != "" && $i['in_rechnung_stellen'] != "") {
                            $check_and_save = true;
                        }
                    }

                    if ($check_and_save) {
                        if ($i['bezeichnung'] != $abteilung->getBezeichnung()) {
                            if ($abteilung->setBezeichnung($i['bezeichnung'])) {
                                $success .= "Die Bezeichnung wurde erfolgreich geändert. ";
                            } else {
                                $error .= "Beim Speichern der Bezeichnung ist ein technischer Fehler aufgetreten. ";
                            }
                        }

                        $in_rechnung_stellen_alt = $abteilung->getInRechnungStellen() ? 'ja' : 'nein';
                        if ($i['in_rechnung_stellen'] != $in_rechnung_stellen_alt) {
                            if ($abteilung->setInRechnungStellen($i['in_rechnung_stellen'] == 'ja' ? 1 : 0)) {
                                $success .= "Die Option 'In Rechnung stellen' wurde erfolgreich geändert. ";
                            } else {
                                $error .= "Beim Speichern der Option 'In Rechnung stellen' ist ein technischer Fehler aufgetreten. ";
                            }
                        }

                        if ($this->company == 'tps') {
                            $palettenabteilung_alt = $abteilung->getPalettenabteilung() ? 'ja' : 'nein';
                            if ($i['palettenabteilung'] != $palettenabteilung_alt) {
                                if ($abteilung->setPalettenabteilung($i['palettenabteilung'] == 'ja' ? 1 : 0)) {
                                    $success .= "Die Option 'Palettenabteilung' wurde erfolgreich geändert. ";
                                } else {
                                    $error .= "Beim Speichern der Option 'Palettenabteilung' ist ein technischer Fehler aufgetreten. ";
                                }
                            }
                        }
                    }

                    // display error message
                    if ($error != "") {
                        $this->smarty_vars['error'] = $error;
                    }
                    if ($success != "") {
                        $this->smarty_vars['success'] = $success;
                    }

                    // fill values into the form
                    $values = [
                        'id'           => $abteilung->getID(),
                        'bezeichnung'  => $abteilung->getBezeichnung(),
                        'in_rechnung_stellen' => $abteilung->getInRechnungStellen() ? 'ja' : 'nein'
                    ];
                    if ($this->company == 'tps') {
                        $values['palettenabteilung'] = $abteilung->getPalettenabteilung() ? 'ja' : 'nein';
                    }
                    $this->smarty_vars['values'] = $values;

                    // template settings
                    $this->template = 'main';
                }
            }
        }

        if ($redirect) {
            $this->misc_utils->redirect('abteilungen', 'index', 'fehler');
        }
    }
}
