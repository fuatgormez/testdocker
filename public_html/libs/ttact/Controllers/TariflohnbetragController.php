<?php

namespace ttact\Controllers;

class TariflohnbetragController extends Controller
{
    public function bearbeiten()
    {
        $redirect = true;

        if (isset($this->params[0])) {
            if ($this->user_input->isPositiveInteger($this->params[0])) {
                $tariflohnbetrag = \ttact\Models\TariflohnbetragModel::findByID($this->db, $this->user_input->getOnlyNumbers($this->params[0]));
                if ($tariflohnbetrag instanceof \ttact\Models\TariflohnbetragModel) {
                    $redirect = false;

                    $error = "";
                    $success = "";

                    // array with all input data
                    $i = [
                        'gueltig_ab'      => '',
                        'lohn'            => ''
                    ];
                    foreach ($i as $key => $value) {
                        $i[$key] = $this->user_input->getPostParameter($key);
                    }
                    $i['lohn'] = (float) str_replace(',', '.', $i['lohn']);

                    // check and save
                    $gueltig_ab = '00.00.0000';
                    if ($tariflohnbetrag->getGueltigAb() instanceof \DateTime) {
                        $gueltig_ab = $tariflohnbetrag->getGueltigAb()->format("d.m.Y");
                    }
                    if ($i['gueltig_ab'] != "") {
                        // Gültig ab
                        if ($i['gueltig_ab'] != $gueltig_ab) {
                            if (!$this->user_input->isDate($i['gueltig_ab'])) {
                                $error .= "Das Datum ist ungültig. ";
                            } else {
                                $date_already_exists = false;
                                $all_tariflohnbetraege = \ttact\Models\TariflohnbetragModel::findAllByTarifID($this->db, $tariflohnbetrag->getTarif($this->db)->getID());
                                foreach ($all_tariflohnbetraege as $t) {
                                    if ($t->getGueltigAb() instanceof \DateTime) {
                                        if ($t->getGueltigAb()->format("d.m.Y") == $i['gueltig_ab']) {
                                            $date_already_exists = true;
                                        }
                                    }
                                }
                                if ($date_already_exists) {
                                    $error .= "Es gibt bereits einen Tariflohnbetrag mit diesem Datum. ";
                                } else {
                                    $date = \DateTime::createFromFormat('d.m.Y', $i['gueltig_ab']);
                                    if ($date instanceof \DateTime) {
                                        if ($date->format("d.m.Y") == $i['gueltig_ab']) {
                                            if ($tariflohnbetrag->setGueltigAb($date->format("Y-m-d"))) {
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

                        // Lohn
                        if ($i['lohn'] != $tariflohnbetrag->getLohn()) {
                            if ($i['lohn'] == '') {
                                if ($tariflohnbetrag->setLohn(0)) {
                                    $success .= "Der Lohn wurde erfolgreich geändert. ";
                                } else {
                                    $error .= "Beim Speichern des Lohns ist ein technischer Fehler aufgetreten. ";
                                }
                            } else {
                                if ($tariflohnbetrag->setLohn($i['lohn'])) {
                                    $success .= "Der Lohn wurde erfolgreich geändert. ";
                                } else {
                                    $error .= "Beim Speichern des Lohns ist ein technischer Fehler aufgetreten. ";
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
                        $this->misc_utils->redirect('tarife', 'bearbeiten', $tariflohnbetrag->getTarif($this->db)->getID(), 'erfolgreich', 'bearbeitet');
                    }

                    // fill values into the form
                    $gueltig_ab = '00.00.0000';
                    if ($tariflohnbetrag->getGueltigAb() instanceof \DateTime) {
                        $gueltig_ab = $tariflohnbetrag->getGueltigAb()->format("d.m.Y");
                    }
                    $values = [
                        'id'                => $tariflohnbetrag->getID(),
                        'gueltig_ab'        => $gueltig_ab,
                        'lohn'              => $tariflohnbetrag->getLohn(),
                        'tarifbezeichnung'  => $tariflohnbetrag->getTarif($this->db)->getBezeichnung()
                    ];
                    $this->smarty_vars['values'] = $values;

                    // template settings
                    $this->template = 'main';
                }
            }
        }

        if ($redirect) {
            $this->misc_utils->redirect('tarife', 'index', 'fehler');
        }
    }

    public function erstellen()
    {
        $redirect = true;

        if (isset($this->params[0])) {
            if ($this->user_input->isPositiveInteger($this->params[0])) {
                $tarif = \ttact\Models\TarifModel::findByID($this->db, $this->user_input->getOnlyNumbers($this->params[0]));
                if ($tarif instanceof \ttact\Models\TarifModel) {
                    $redirect = false;

                    $error = "";
                    $success = "";

                    $tariflohnbetrag_model = null;

                    // array with all input data
                    $i = [
                        'gueltig_ab'      => '',
                        'lohn'            => ''
                    ];
                    foreach ($i as $key => $value) {
                        $i[$key] = $this->user_input->getPostParameter($key);
                    }
                    $i['lohn'] = (float) str_replace(',', '.', $i['lohn']);

                    // check and save
                    if ($i['gueltig_ab'] != '' || $i['lohn'] != '') {
                        if ($this->user_input->isDate($i['gueltig_ab'])) {
                            $date_already_exists = false;
                            $all_tariflohnbetraege = \ttact\Models\TariflohnbetragModel::findAllByTarifID($this->db, $tarif->getID());
                            foreach ($all_tariflohnbetraege as $t) {
                                if ($t->getGueltigAb() instanceof \DateTime) {
                                    if ($t->getGueltigAb()->format("d.m.Y") == $i['gueltig_ab']) {
                                        $date_already_exists = true;
                                    }
                                }
                            }
                            if (!$date_already_exists) {
                                $date = \DateTime::createFromFormat('d.m.Y', $i['gueltig_ab']);
                                if ($date instanceof \DateTime) {
                                    if ($date->format("d.m.Y") == $i['gueltig_ab']) {
                                        $lohn = 0;

                                        if ($i['lohn'] != '') {
                                            $lohn = $i['lohn'];
                                        }

                                        $data = [
                                            'gueltig_ab' => $date->format("Y-m-d"),
                                            'tarif_id' => $tarif->getID(),
                                            'lohn' => $lohn
                                        ];

                                        $tariflohnbetrag_model = \ttact\Models\TariflohnbetragModel::createNew($this->db, $data);
                                        if ($tariflohnbetrag_model instanceof \ttact\Models\TariflohnbetragModel) {
                                            $success = 'Der Tariflohnbetrag wurde erfolgreich angelegt.';
                                        } else {
                                            $error .= 'Beim Anlegen des Tariflohnbetrags ist ein technischer Fehler aufgetreten.';
                                        }
                                    } else {
                                        $error .= "Das Datum ist ungültig. ";
                                    }
                                } else {
                                    $error .= "Das Datum ist ungültig. ";
                                }
                            } else {
                                $error .= "Es gibt bereits einen Tariflohnbetrag mit diesem Datum. ";
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
                        $this->misc_utils->redirect('tarife', 'bearbeiten', $tarif->getID(), 'erfolgreich', 'erstellt');
                    }

                    // fill values into the form
                    $values = [
                        'gueltig_ab'        => $i['gueltig_ab'],
                        'tarifbezeichnung'  => $tarif->getBezeichnung(),
                        'tarifid'           => $tarif->getID(),
                        'lohn'              => $i['lohn'] == 0 ? '' : $i['lohn']
                    ];
                    $this->smarty_vars['values'] = $values;

                    // template settings
                    $this->template = 'main';
                }
            }
        }

        if ($redirect) {
            $this->misc_utils->redirect('tarife', 'index', 'fehler');
        }
    }
}
