<?php

namespace ttact\Controllers;

class TarifeController extends Controller
{
    public function index()
    {
        if (isset($this->params[0])) {
            if ($this->params[0] == "fehler") {
                $this->smarty_vars['warning'] = "Der Tarif oder Tariflohnbetrag konnte nicht gefunden werden.";
            }
        }

        // Erstellen

            $error = "";
            $success = "";
            if (isset($this->params[0])) {
                if ($this->params[0] == "erfolgreich") {
                    $success = "Der Tarif wurde erfolgreich angelegt.";
                }
            }

            // array with all input data
            $i = [
                'bezeichnung' => ''
            ];
            foreach ($i as $key => $value) {
                $i[$key] = $this->user_input->getPostParameter($key);
            }

            // check and save
            if ($i['bezeichnung'] != "") {
                // save the data and check if it worked
                $new = \ttact\Models\TarifModel::createNew($this->db, $i);
                if ($new instanceof \ttact\Models\TarifModel) {
                    $this->misc_utils->redirect('tarife', 'index', 'erfolgreich');
                } else {
                    $error = "Beim Anlegen des Tarifs ist ein Fehler aufgetreten.";
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

            $tarife = \ttact\Models\TarifModel::findAll($this->db);
            $tarifliste = [];
            foreach ($tarife as $t) {
                $tarifliste[$t->getID()] = [
                    'id'                => $t->getID(),
                    'bezeichnung'       => $t->getBezeichnung()
                ];
            }
            ksort($tarifliste);
            $this->smarty_vars['tarifliste'] = $tarifliste;

        // template settings
        $this->template = 'main';
    }

    public function bearbeiten()
    {
        $redirect = true;

        if (isset($this->params[0])) {
            if ($this->user_input->isPositiveInteger($this->params[0])) {
                $tarif = \ttact\Models\TarifModel::findByID($this->db, $this->user_input->getOnlyNumbers($this->params[0]));
                if ($tarif instanceof \ttact\Models\TarifModel) {
                    $redirect = false;

                    $error = "";
                    $success = "";
                    if (isset($this->params[1])) {
                        if ($this->params[1] == "erfolgreich") {
                            if ($this->params[2] == "bearbeitet") {
                                $success = "Der Tariflohnbetrag wurde erfolgreich bearbeitet.";
                            } elseif ($this->params[2] == "erstellt") {
                                $success = "Der Tariflohnbetrag wurde erfolgreich angelegt.";
                            }
                        } elseif ($this->params[1] == "fehler") {
                            if ($this->params[2] == "bearbeitet") {
                                $success = "Der Tariflohnbetrag konnte nicht bearbeitet werden.";
                            } elseif ($this->params[2] == "erstellt") {
                                $success = "Der Tariflohnbetrag konnte nicht angelegt werden.";
                            }
                        }
                    }

                    // array with all input data
                    $i = [
                        'id'            => '',
                        'bezeichnung'   => ''
                    ];
                    foreach ($i as $key => $value) {
                        $i[$key] = $this->user_input->getPostParameter($key);
                    }

                    // check and save
                    if ($i['bezeichnung'] != "" && $i['bezeichnung'] != $tarif->getBezeichnung()) {
                        if ($tarif->setBezeichnung($i['bezeichnung'])) {
                            $success = "Die Bezeichnung wurde erfolgreich geändert. ";
                        } else {
                            $error = "Beim Speichern der Bezeichnung ist ein technischer Fehler aufgetreten. ";
                        }
                    }

                    // display error message
                    if ($error != "") {
                        $this->smarty_vars['error'] = $error;
                    }
                    if ($success != "") {
                        $this->smarty_vars['success'] = $success;
                    }

                    // get Tariflohnbetragsliste
                    $tariflohnbetragsliste = [];
                    $tariflohnbetraege = \ttact\Models\TariflohnbetragModel::findAllByTarifID($this->db, $tarif->getID());
                    foreach ($tariflohnbetraege as $tariflohnbetrag) {
                        $gueltig_ab = '00.00.0000';
                        if ($tariflohnbetrag->getGueltigAb() instanceof \DateTime) {
                            $gueltig_ab = $tariflohnbetrag->getGueltigAb()->format("d.m.Y");
                        }

                        $tariflohnbetragsliste[] = [
                            'id' => $tariflohnbetrag->getID(),
                            'gueltig_ab' => $gueltig_ab,
                            'lohn' => $tariflohnbetrag->getLohn()
                        ];
                    }
                    $this->smarty_vars['tariflohnbetragsliste'] = $tariflohnbetragsliste;

                    // fill values into the form
                    $values = [
                        'id'           => $tarif->getID(),
                        'bezeichnung'  => $tarif->getBezeichnung()
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
