<?php

namespace ttact\Controllers;

class ArbeitszeitkontoController extends Controller
{
    public function bearbeiten()
    {
        $redirect = true;

        if (isset($this->params[0])) {
            if ($this->user_input->isPositiveInteger($this->params[0])) {
                $arbeitszeitkonto_model = \ttact\Models\ArbeitszeitkontoModel::findByID($this->db, $this->user_input->getOnlyNumbers($this->params[0]));
                if ($arbeitszeitkonto_model instanceof \ttact\Models\ArbeitszeitkontoModel) {
                    $redirect = false;

                    $error = "";
                    $success = "";

                    // array with all input data
                    $i = [
                        'stunden' => ''
                    ];
                    foreach ($i as $key => $value) {
                        $i[$key] = (float) str_replace(',', '.', $this->user_input->getPostParameter($key));
                    }
                    $i['speichern'] = $this->user_input->getPostParameter('speichern');

                    // check and save
                    if ($i['speichern'] == 'ja') {
                        if ($i['stunden'] != $arbeitszeitkonto_model->getStunden()) {
                            if ($i['stunden'] > -1000 && $i['stunden'] < 1000) {
                                if ($arbeitszeitkonto_model->setStunden($i['stunden'])) {
                                    $success .= "Das Arbeitszeitkonto wurde erfolgreich geändert. ";
                                } else {
                                    $error .= "Beim Speichern des Arbeitszeitkontos ist ein technischer Fehler aufgetreten. ";
                                }
                            } else {
                                $error .= "Das Arbeitszeitkonto muss zwischen -999,99 und 999,99 liegen. ";
                            }
                        }
                    }


                    // display error message
                    if ($success != "") {
                        $this->smarty_vars['success'] = $success;
                    }
                    if ($error != "") {
                        $this->smarty_vars['error'] = $error;
                    }

                    // fill values into the form
                    $monatsnamen = [
                        1 => 'Januar',
                        2 => 'Februar',
                        3 => 'März',
                        4 => 'April',
                        5 => 'Mai',
                        6 => 'Juni',
                        7 => 'Juli',
                        8 => 'August',
                        9 => 'September',
                        10 => 'Oktober',
                        11 => 'November',
                        12 => 'Dezember'
                    ];
                    $arbeitszeitkonto_model = \ttact\Models\ArbeitszeitkontoModel::findByID($this->db, $this->user_input->getOnlyNumbers($this->params[0]));
                    $values = [
                        'id' => $arbeitszeitkonto_model->getID(),
                        'monat' => $arbeitszeitkonto_model->getMonat(),
                        'monatsname' => $monatsnamen[$arbeitszeitkonto_model->getMonat()],
                        'jahr' => $arbeitszeitkonto_model->getJahr(),
                        'vorname' => $arbeitszeitkonto_model->getMitarbeiter()->getVorname(),
                        'nachname' => $arbeitszeitkonto_model->getMitarbeiter()->getNachname(),
                        'personalnummer' => $arbeitszeitkonto_model->getMitarbeiter()->getPersonalnummer(),
                        'stunden' => $arbeitszeitkonto_model->getStunden()
                    ];
                    $this->smarty_vars['values'] = $values;

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
