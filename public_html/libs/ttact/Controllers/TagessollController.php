<?php

namespace ttact\Controllers;

class TagessollController extends Controller
{
    public function bearbeiten()
    {
        $redirect = true;

        if (isset($this->params[0])) {
            if ($this->user_input->isPositiveInteger($this->params[0])) {
                $tagessoll_model = \ttact\Models\TagessollModel::findByID($this->db, $this->user_input->getOnlyNumbers($this->params[0]));
                if ($tagessoll_model instanceof \ttact\Models\TagessollModel) {
                    $redirect = false;

                    $error = "";
                    $success = "";

                    // array with all input data
                    if ($this->company == 'tps') {
                        $i = [
                            'tagessoll' => ''
                        ];
                    } else {
                        $i = [
                            'tagessoll' => '',
                            'tagessoll_montag' => '',
                            'tagessoll_dienstag' => '',
                            'tagessoll_mittwoch' => '',
                            'tagessoll_donnerstag' => '',
                            'tagessoll_freitag' => '',
                            'tagessoll_samstag' => '',
                            'tagessoll_sonntag' => ''
                        ];
                    }
                    foreach ($i as $key => $value) {
                        $i[$key] = (float) str_replace(',', '.', $this->user_input->getPostParameter($key));
                    }
                    $i['speichern'] = $this->user_input->getPostParameter('speichern');

                    // check and save
                    if ($i['speichern'] == 'ja') {
                        // Allgemein
                        if ($i['tagessoll'] != $tagessoll_model->getTagessoll()) {
                            if ($i['tagessoll'] >= 0 && $i['tagessoll'] < 100) {
                                if ($tagessoll_model->setTagessoll($i['tagessoll'])) {
                                    $success .= "Das Tagessoll wurde erfolgreich geändert. ";
                                } else {
                                    $error .= "Beim Speichern des Tagessolls ist ein technischer Fehler aufgetreten. ";
                                }
                            } else {
                                $error .= "Das Tagessoll muss zwischen 0,00 und 99,99 liegen. ";
                            }
                        }

                        if ($this->company != 'tps') {
                            // Montag
                            if ($i['tagessoll_montag'] != $tagessoll_model->getTagessollMontag()) {
                                if ($i['tagessoll_montag'] >= 0 && $i['tagessoll_montag'] < 100) {
                                    if ($tagessoll_model->setTagessollMontag($i['tagessoll_montag'])) {
                                        $success .= "Das Tagessoll für Montag wurde erfolgreich geändert. ";
                                    } else {
                                        $error .= "Beim Speichern des Tagessolls für Montag ist ein technischer Fehler aufgetreten. ";
                                    }
                                } else {
                                    $error .= "Das Tagessoll für Montag muss zwischen 0,00 und 99,99 liegen. ";
                                }
                            }

                            // Dienstag
                            if ($i['tagessoll_dienstag'] != $tagessoll_model->getTagessollDienstag()) {
                                if ($i['tagessoll_dienstag'] >= 0 && $i['tagessoll_dienstag'] < 100) {
                                    if ($tagessoll_model->setTagessollDienstag($i['tagessoll_dienstag'])) {
                                        $success .= "Das Tagessoll für Dienstag wurde erfolgreich geändert. ";
                                    } else {
                                        $error .= "Beim Speichern des Tagessolls für Dienstag ist ein technischer Fehler aufgetreten. ";
                                    }
                                } else {
                                    $error .= "Das Tagessoll für Dienstag muss zwischen 0,00 und 99,99 liegen. ";
                                }
                            }

                            // Mittwoch
                            if ($i['tagessoll_mittwoch'] != $tagessoll_model->getTagessollMittwoch()) {
                                if ($i['tagessoll_mittwoch'] >= 0 && $i['tagessoll_mittwoch'] < 100) {
                                    if ($tagessoll_model->setTagessollMittwoch($i['tagessoll_mittwoch'])) {
                                        $success .= "Das Tagessoll für Mittwoch wurde erfolgreich geändert. ";
                                    } else {
                                        $error .= "Beim Speichern des Tagessolls für Mittwoch ist ein technischer Fehler aufgetreten. ";
                                    }
                                } else {
                                    $error .= "Das Tagessoll für Mittwoch muss zwischen 0,00 und 99,99 liegen. ";
                                }
                            }

                            // Donnerstag
                            if ($i['tagessoll_donnerstag'] != $tagessoll_model->getTagessollDonnerstag()) {
                                if ($i['tagessoll_donnerstag'] >= 0 && $i['tagessoll_donnerstag'] < 100) {
                                    if ($tagessoll_model->setTagessollDonnerstag($i['tagessoll_donnerstag'])) {
                                        $success .= "Das Tagessoll für Donnerstag wurde erfolgreich geändert. ";
                                    } else {
                                        $error .= "Beim Speichern des Tagessolls für Donnerstag ist ein technischer Fehler aufgetreten. ";
                                    }
                                } else {
                                    $error .= "Das Tagessoll für Donnerstag muss zwischen 0,00 und 99,99 liegen. ";
                                }
                            }

                            // Freitag
                            if ($i['tagessoll_freitag'] != $tagessoll_model->getTagessollFreitag()) {
                                if ($i['tagessoll_freitag'] >= 0 && $i['tagessoll_freitag'] < 100) {
                                    if ($tagessoll_model->setTagessollFreitag($i['tagessoll_freitag'])) {
                                        $success .= "Das Tagessoll für Freitag wurde erfolgreich geändert. ";
                                    } else {
                                        $error .= "Beim Speichern des Tagessolls für Freitag ist ein technischer Fehler aufgetreten. ";
                                    }
                                } else {
                                    $error .= "Das Tagessoll für Freitag muss zwischen 0,00 und 99,99 liegen. ";
                                }
                            }

                            // Samstag
                            if ($i['tagessoll_samstag'] != $tagessoll_model->getTagessollSamstag()) {
                                if ($i['tagessoll_samstag'] >= 0 && $i['tagessoll_samstag'] < 100) {
                                    if ($tagessoll_model->setTagessollSamstag($i['tagessoll_samstag'])) {
                                        $success .= "Das Tagessoll für Samstag wurde erfolgreich geändert. ";
                                    } else {
                                        $error .= "Beim Speichern des Tagessolls für Samstag ist ein technischer Fehler aufgetreten. ";
                                    }
                                } else {
                                    $error .= "Das Tagessoll für Samstag muss zwischen 0,00 und 99,99 liegen. ";
                                }
                            }

                            // Sonntag
                            if ($i['tagessoll_sonntag'] != $tagessoll_model->getTagessollSonntag()) {
                                if ($i['tagessoll_sonntag'] >= 0 && $i['tagessoll_sonntag'] < 100) {
                                    if ($tagessoll_model->setTagessollSonntag($i['tagessoll_sonntag'])) {
                                        $success .= "Das Tagessoll für Sonntag wurde erfolgreich geändert. ";
                                    } else {
                                        $error .= "Beim Speichern des Tagessolls für Sonntag ist ein technischer Fehler aufgetreten. ";
                                    }
                                } else {
                                    $error .= "Das Tagessoll für Sonntag muss zwischen 0,00 und 99,99 liegen. ";
                                }
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
                    $tagessoll_model = \ttact\Models\TagessollModel::findByID($this->db, $this->user_input->getOnlyNumbers($this->params[0]));
                    $values = [
                        'id' => $tagessoll_model->getID(),
                        'monat' => $tagessoll_model->getMonat(),
                        'monatsname' => $monatsnamen[$tagessoll_model->getMonat()],
                        'jahr' => $tagessoll_model->getJahr(),
                        'vorname' => $tagessoll_model->getMitarbeiter()->getVorname(),
                        'nachname' => $tagessoll_model->getMitarbeiter()->getNachname(),
                        'personalnummer' => $tagessoll_model->getMitarbeiter()->getPersonalnummer(),
                        'tagessoll' => $tagessoll_model->getTagessoll()
                    ];
                    if ($this->company != 'tps') {
                        $values['tagessoll_montag'] = $tagessoll_model->getTagessollMontag();
                        $values['tagessoll_dienstag'] = $tagessoll_model->getTagessollDienstag();
                        $values['tagessoll_mittwoch'] = $tagessoll_model->getTagessollMittwoch();
                        $values['tagessoll_donnerstag'] = $tagessoll_model->getTagessollDonnerstag();
                        $values['tagessoll_freitag'] = $tagessoll_model->getTagessollFreitag();
                        $values['tagessoll_samstag'] = $tagessoll_model->getTagessollSamstag();
                        $values['tagessoll_sonntag'] = $tagessoll_model->getTagessollSonntag();
                    }
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
