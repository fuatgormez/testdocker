<?php

namespace ttact\Controllers;

class LohnkonfigurationController extends Controller
{
    public function bearbeiten()
    {
        $redirect = true;

        if (isset($this->params[0])) {
            if ($this->user_input->isPositiveInteger($this->params[0])) {
                $lohnkonfiguration = \ttact\Models\LohnkonfigurationModel::findByID($this->db, $this->user_input->getOnlyNumbers($this->params[0]));
                if ($lohnkonfiguration instanceof \ttact\Models\LohnkonfigurationModel) {
                    $redirect = false;

                    $error = "";
                    $success = "";

                    // array with all input data
                    if ($this->company == 'tps') {
                        $i = [
                            'gueltig_ab'      => '',
                            'wochenstunden'   => '',
                            'lohn'            => ''
                        ];
                    } else {
                        $i = [
                            'gueltig_ab'      => '',
                            'tarif'           => '',
                            'wochenstunden'   => '',
                            'lohn'            => ''
                        ];
                    }
                    foreach ($i as $key => $value) {
                        $i[$key] = $this->user_input->getPostParameter($key);
                    }
                    if ($this->company != 'tps') {
                        $i['tarif'] = $this->user_input->getOnlyNumbers($i['tarif']);
                    }
                    $i['wochenstunden'] = (float) str_replace(',', '.', $i['wochenstunden']);
                    $i['lohn'] = (float) str_replace(',', '.', $i['lohn']);

                    // check and save
                    $gueltig_ab = '00.00.0000';
                    if ($lohnkonfiguration->getGueltigAb() instanceof \DateTime) {
                        $gueltig_ab = $lohnkonfiguration->getGueltigAb()->format("d.m.Y");
                    }
                    if ($i['gueltig_ab'] != "") {
                        // Gültig ab
                        if ($i['gueltig_ab'] != $gueltig_ab) {
                            if (!$this->user_input->isDate($i['gueltig_ab'])) {
                                $error .= "Das Datum ist ungültig. ";
                            } else {
                                $date_already_exists = false;
                                $all_lohnkonfigurationen = \ttact\Models\LohnkonfigurationModel::findByMitarbeiter($this->db, $lohnkonfiguration->getMitarbeiter()->getID());
                                foreach ($all_lohnkonfigurationen as $l) {
                                    if ($l->getGueltigAb() instanceof \DateTime) {
                                        if ($l->getGueltigAb()->format("d.m.Y") == $i['gueltig_ab']) {
                                            $date_already_exists = true;
                                        }
                                    }
                                }
                                if ($date_already_exists) {
                                    $error .= "Es gibt bereits eine Lohnkonfiguration mit diesem Datum. ";
                                } else {
                                    $date = \DateTime::createFromFormat('d.m.Y', $i['gueltig_ab']);
                                    if ($date instanceof \DateTime) {
                                        if ($date->format("d.m.Y") == $i['gueltig_ab']) {
                                            if ($lohnkonfiguration->setGueltigAb($date->format("Y-m-d"))) {
                                                $success .= "Das Datum wurde erfolgreich geändert. ";
                                            } else {
                                                $error .= "Beim Speichern des Datums ist ein technischer Fehler aufgetreten. ";
                                            }
                                        } else {
                                            $error .= "Das Datum ist ungültig. ";
                                        }
                                    } else {
                                        $error .= "Das Datum ist ungültig. ";
                                    }
                                }
                            }
                        }

                        if ($this->company != 'tps') {
                            // Tarif
                            $tarif = '';
                            if ($lohnkonfiguration->getTarif() instanceof \ttact\Models\TarifModel) {
                                $tarif = $lohnkonfiguration->getTarif()->getID();
                            }
                            if ($i['tarif'] != $tarif) {
                                if ($i['tarif'] == '') {
                                    if ($lohnkonfiguration->setTarifID('')) {
                                        $success .= "Der Tarif wurde erfolgreich geändert. ";
                                    } else {
                                        $error .= "Beim Speichern des Tarifs ist ein technischer Fehler aufgetreten. ";
                                    }
                                } else {
                                    $test_tarif = \ttact\Models\TarifModel::findByID($this->db, $i['tarif']);
                                    if ($test_tarif instanceof \ttact\Models\TarifModel) {
                                        if ($lohnkonfiguration->setTarifID($test_tarif->getID())) {
                                            $success .= "Der Tarif wurde erfolgreich geändert. ";
                                        } else {
                                            $error .= "Beim Speichern des Tarifs ist ein technischer Fehler aufgetreten. ";
                                        }
                                    } else {
                                        $error .= "Der Tarif ist ungültig. ";
                                    }
                                }
                            }
                        }

                        // Wochenstunden
                        if ($i['wochenstunden'] != $lohnkonfiguration->getWochenstunden()) {
                            if ($i['wochenstunden'] == '') {
                                if ($lohnkonfiguration->setWochenstunden(0)) {
                                    $success .= "Die Wochenstundenanzahl wurde erfolgreich geändert. ";
                                } else {
                                    $error .= "Beim Speichern der Wochenstundenanzahl ist ein technischer Fehler aufgetreten. ";
                                }
                            } else {
                                if ($lohnkonfiguration->setWochenstunden($i['wochenstunden'])) {
                                    $success .= "Die Wochenstundenanzahl wurde erfolgreich geändert. ";
                                } else {
                                    $error .= "Beim Speichern der Wochenstundenanzahl ist ein technischer Fehler aufgetreten. ";
                                }
                            }
                        }

                        // Lohn
                        if ($i['lohn'] != $lohnkonfiguration->getSollLohn()) {
                            if ($i['lohn'] == '') {
                                if ($this->company == 'tps') {
                                    $error .= "Der Gesamtlohn ist ungültig. ";
                                } else {
                                    if ($lohnkonfiguration->setSollLohn(0)) {
                                        $success .= "Der Gesamtlohn wurde erfolgreich geändert. ";
                                    } else {
                                        $error .= "Beim Speichern des Gesamtlohns ist ein technischer Fehler aufgetreten. ";
                                    }
                                }
                            } else {
                                if ($lohnkonfiguration->setSollLohn($i['lohn'])) {
                                    $success .= "Der Gesamtlohn wurde erfolgreich geändert. ";
                                } else {
                                    $error .= "Beim Speichern des Gesamtlohns ist ein technischer Fehler aufgetreten. ";
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
                        $this->misc_utils->redirect('mitarbeiter', 'bearbeiten', $lohnkonfiguration->getMitarbeiter()->getPersonalnummer(), 'vertragliches', 'erfolgreich', 'bearbeitet');
                    }

                    // fill values into the form
                    $gueltig_ab = '00.00.0000';
                    if ($lohnkonfiguration->getGueltigAb() instanceof \DateTime) {
                        $gueltig_ab = $lohnkonfiguration->getGueltigAb()->format("d.m.Y");
                    }
                    if ($this->company == 'tps') {
                        $values = [
                            'id' => $lohnkonfiguration->getID(),
                            'personalnummer' => $lohnkonfiguration->getMitarbeiter()->getPersonalnummer(),
                            'vorname' => $lohnkonfiguration->getMitarbeiter()->getVorname(),
                            'nachname' => $lohnkonfiguration->getMitarbeiter()->getNachname(),
                            'gueltig_ab' => $gueltig_ab,
                            'wochenstunden' => $lohnkonfiguration->getWochenstunden(),
                            'lohn' => $lohnkonfiguration->getSollLohn()
                        ];
                        $this->smarty_vars['values'] = $values;
                    } else {
                        $tarif = '';
                        if ($lohnkonfiguration->getTarif() instanceof \ttact\Models\TarifModel) {
                            $tarif = $lohnkonfiguration->getTarif()->getID();
                        }
                        $values = [
                            'id' => $lohnkonfiguration->getID(),
                            'personalnummer' => $lohnkonfiguration->getMitarbeiter()->getPersonalnummer(),
                            'vorname' => $lohnkonfiguration->getMitarbeiter()->getVorname(),
                            'nachname' => $lohnkonfiguration->getMitarbeiter()->getNachname(),
                            'gueltig_ab' => $gueltig_ab,
                            'tarif' => $tarif,
                            'wochenstunden' => $lohnkonfiguration->getWochenstunden(),
                            'lohn' => $lohnkonfiguration->getSollLohn()
                        ];
                        $this->smarty_vars['values'] = $values;

                        // get data for Tarif
                        $this->smarty_vars['tarifliste'] = $this->misc_utils->getTarifliste($this->db);
                    }

                    // template settings
                    $this->template = 'main';
                }
            }
        }

        if ($redirect) {
            $this->misc_utils->redirect('mitarbeiter', 'index', 'fehler');
        }
    }

    public function erstellen()
    {
        $redirect = true;

        if (isset($this->params[0])) {
            if ($this->user_input->isPositiveInteger($this->params[0])) {
                $mitarbeiter = \ttact\Models\MitarbeiterModel::findByPersonalnummer($this->db, $this->user_input->getOnlyNumbers($this->params[0]));
                if ($mitarbeiter instanceof \ttact\Models\MitarbeiterModel) {
                    $redirect = false;

                    $error = "";
                    $success = "";

                    $lohnkonfiguration_model = null;

                    if ($this->company == 'tps') {
                        // array with all input data
                        $i = [
                            'gueltig_ab'      => '',
                            'wochenstunden'   => '',
                            'lohn'            => ''
                        ];
                    } else {
                        // array with all input data
                        $i = [
                            'gueltig_ab'      => '',
                            'tarif'           => '',
                            'wochenstunden'   => '',
                            'lohn'            => ''
                        ];
                    }
                    foreach ($i as $key => $value) {
                        $i[$key] = $this->user_input->getPostParameter($key);
                    }
                    if ($this->company != 'tps') {
                        $i['tarif'] = $this->user_input->getOnlyNumbers($i['tarif']);
                    }
                    $i['wochenstunden'] = (float) str_replace(',', '.', $i['wochenstunden']);
                    $i['lohn'] = (float) str_replace(',', '.', $i['lohn']);

                    // check and save
                    $continue = false;
                    if ($this->company == 'tps') {
                        if ($i['gueltig_ab'] != '') {
                            $continue = true;
                        }
                    } else {
                        if ($i['gueltig_ab'] != '' || $i['tarif'] != '') {
                            $continue = true;
                        }
                    }

                    if ($continue) {
                        if ($this->user_input->isDate($i['gueltig_ab'])) {
                            $date_already_exists = false;
                            $all_lohnkonfigurationen = \ttact\Models\LohnkonfigurationModel::findByMitarbeiter($this->db, $mitarbeiter->getID());
                            foreach ($all_lohnkonfigurationen as $l) {
                                if ($l->getGueltigAb() instanceof \DateTime) {
                                    if ($l->getGueltigAb()->format("d.m.Y") == $i['gueltig_ab']) {
                                        $date_already_exists = true;
                                    }
                                }
                            }
                            if (!$date_already_exists) {
                                $date = \DateTime::createFromFormat('d.m.Y', $i['gueltig_ab']);
                                if ($date instanceof \DateTime) {
                                    if ($date->format("d.m.Y") == $i['gueltig_ab']) {
                                        $wochenstunden = 0;
                                        $soll_lohn = 0;

                                        if ($this->company != 'tps') {
                                            $tarif = '';
                                            if ($i['tarif'] != '') {
                                                $tarif_model = \ttact\Models\TarifModel::findByID($this->db, $i['tarif']);
                                                if ($tarif_model instanceof \ttact\Models\TarifModel) {
                                                    $tarif = $tarif_model->getID();
                                                }
                                            }
                                        }

                                        if ($i['wochenstunden'] != '') {
                                            $wochenstunden = $i['wochenstunden'];
                                        }

                                        $lohn_error = false;

                                        if ($i['lohn'] != '') {
                                            $soll_lohn = $i['lohn'];
                                        } else {
                                            if ($this->company == 'tps') {
                                                $lohn_error = true;
                                            }
                                        }

                                        if (!$lohn_error) {
                                            if ($this->company == 'tps') {
                                                $data = [
                                                    'gueltig_ab' => $date->format("Y-m-d"),
                                                    'mitarbeiter_id' => $mitarbeiter->getID(),
                                                    'wochenstunden' => $wochenstunden,
                                                    'soll_lohn' => $soll_lohn
                                                ];
                                            } else {
                                                $data = [
                                                    'gueltig_ab' => $date->format("Y-m-d"),
                                                    'mitarbeiter_id' => $mitarbeiter->getID(),
                                                    'tarif_id' => $tarif,
                                                    'wochenstunden' => $wochenstunden,
                                                    'soll_lohn' => $soll_lohn
                                                ];
                                            }

                                            $lohnkonfiguration_model = \ttact\Models\LohnkonfigurationModel::createNew($this->db, $data);
                                            if ($lohnkonfiguration_model instanceof \ttact\Models\LohnkonfigurationModel) {
                                                $success = 'Die Lohnkonfiguration wurde erfolgreich angelegt.';
                                            } else {
                                                $error .= 'Beim Anlegen der Lohnkonfiguration ist ein technischer Fehler aufgetreten.';
                                            }
                                        } else {
                                            $error .= "Der Lohn ist ungültig. ";
                                        }
                                    } else {
                                        $error .= "Das Datum ist ungültig. ";
                                    }
                                } else {
                                    $error .= "Das Datum ist ungültig. ";
                                }
                            } else {
                                $error .= "Es gibt bereits eine Lohnkonfiguration mit diesem Datum. ";
                            }
                        } else {
                            $error .= "Das Datum ist ungültig. ";
                        }
                    } else {
                        foreach ($i as $value) {
                            if ($value != "") {
                                $error = "Die mit einem (*) gekennzeichneten Felder dürfen nicht leer sein.";
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
                        $this->misc_utils->redirect('mitarbeiter', 'bearbeiten', $mitarbeiter->getPersonalnummer(), 'vertragliches', 'erfolgreich', 'erstellt');
                    }

                    if ($this->company == 'tps') {
                        // fill values into the form
                        $values = [
                            'personalnummer'    => $mitarbeiter->getPersonalnummer(),
                            'vorname'           => $mitarbeiter->getVorname(),
                            'nachname'          => $mitarbeiter->getNachname(),
                            'gueltig_ab'        => $i['gueltig_ab'],
                            'wochenstunden'     => $i['wochenstunden'] == 0 ? '' : $i['wochenstunden'],
                            'lohn'              => $i['lohn'] == 0 ? '' : $i['lohn']
                        ];
                        $this->smarty_vars['values'] = $values;
                    } else {
                        // fill values into the form
                        $values = [
                            'personalnummer'    => $mitarbeiter->getPersonalnummer(),
                            'vorname'           => $mitarbeiter->getVorname(),
                            'nachname'          => $mitarbeiter->getNachname(),
                            'gueltig_ab'        => $i['gueltig_ab'],
                            'tarif'             => $i['tarif'],
                            'wochenstunden'     => $i['wochenstunden'] == 0 ? '' : $i['wochenstunden'],
                            'lohn'              => $i['lohn'] == 0 ? '' : $i['lohn']
                        ];
                        $this->smarty_vars['values'] = $values;

                        // get data for Tarif
                        $this->smarty_vars['tarifliste'] = $this->misc_utils->getTarifliste($this->db);
                    }

                    // template settings
                    $this->template = 'main';
                }
            }
        }

        if ($redirect) {
            $this->misc_utils->redirect('mitarbeiter', 'index', 'fehler');
        }
    }
}
