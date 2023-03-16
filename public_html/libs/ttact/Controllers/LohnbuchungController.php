<?php

namespace ttact\Controllers;

class LohnbuchungController extends Controller
{
    public function bearbeiten()
    {
        $redirect = true;

        if (isset($this->params[0])) {
            if ($this->user_input->isPositiveInteger($this->params[0])) {
                $lohnbuchung = \ttact\Models\LohnbuchungModel::findByID($this->db, $this->user_input->getOnlyNumbers($this->params[0]));
                if ($lohnbuchung instanceof \ttact\Models\LohnbuchungModel) {
                    $redirect = false;

                    $error = "";
                    $success = "";

                    // array with all input data
                    $i = [
                        'jahr'          => '',
                        'monat'         => '',
                        'lohnart'       => '',
                        'wert'          => '',
                        'faktor'        => '',
                        'bezeichnung'   => '',
                        'action'        => ''
                    ];
                    foreach ($i as $key => $value) {
                        $i[$key] = $this->user_input->getPostParameter($key);
                    }
                    $i['jahr'] = (int) $this->user_input->getOnlyNumbers($i['jahr']);
                    $i['monat'] = (int) $this->user_input->getOnlyNumbers($i['monat']);
                    $i['lohnart'] = (int) $this->user_input->getOnlyNumbers($i['lohnart']);
                    $i['wert'] = (float) str_replace(',', '.', $i['wert']);
                    $i['faktor'] = (float) str_replace(',', '.', $i['faktor']);

                    // check and save
                    if ($i['action'] == 'loeschen') {
                        $lohnbuchung->delete();
                        $this->misc_utils->redirect('mitarbeiter', 'bearbeiten', $lohnbuchung->getMitarbeiter()->getPersonalnummer(), 'lohnbuchungen', 'erfolgreich', 'bearbeitet');
                    } elseif ($i['action'] == 'speichern') {
                        // Jahr
                        $jahr = '';
                        if ($lohnbuchung->getDatum() instanceof \DateTime) {
                            $jahr = (int) $lohnbuchung->getDatum()->format('Y');
                        }
                        if ($i['jahr'] != $jahr) {
                            if (!($i['jahr'] > 1000 && $i['jahr'] <= 9999)) {
                                $error .= "Das Jahr ist ungültig. ";
                            } else {
                                if ($lohnbuchung->getDatum() instanceof \DateTime) {
                                    $datum = $lohnbuchung->getDatum();
                                    $datum->setDate($i['jahr'], (int) $datum->format("m"), (int) $datum->format("d"));
                                    if ($datum->format("Y") == $i['jahr']) {
                                        if ($lohnbuchung->setDatum($datum->format("Y-m-d"))) {
                                            $success .= "Das Jahr wurde erfolgreich geändert. ";
                                        } else {
                                            $error .= "Beim Speichern des Jahres ist ein technischer Fehler aufgetreten. ";
                                        }
                                    } else {
                                        $error .= "Das Jahr ist ungültig. ";
                                    }
                                }
                            }
                        }

                        // Monat
                        $monat = '';
                        if ($lohnbuchung->getDatum() instanceof \DateTime) {
                            $monat = (int) $lohnbuchung->getDatum()->format('m');
                        }
                        if ($i['monat'] != $monat) {
                            if (!($i['monat'] > 0 && $i['monat'] <= 12)) {
                                $error .= "Der Monat ist ungültig. ";
                            } else {
                                if ($lohnbuchung->getDatum() instanceof \DateTime) {
                                    $datum = $lohnbuchung->getDatum();
                                    $datum->setDate((int) $datum->format("Y"), $i['monat'], (int) $datum->format("d"));
                                    if ($datum->format("m") == $i['monat']) {
                                        if ($lohnbuchung->setDatum($datum->format("Y-m-d"))) {
                                            $success .= "Der Monat wurde erfolgreich geändert. ";
                                        } else {
                                            $error .= "Beim Speichern des Monats ist ein technischer Fehler aufgetreten. ";
                                        }
                                    } else {
                                        $error .= "Der Monat ist ungültig. ";
                                    }
                                }
                            }
                        }

                        // Lohnart
                        if ($i['lohnart'] != $lohnbuchung->getLohnart()) {
                            if ($i['lohnart'] == '' || $i['lohnart'] == 0) {
                                $error .= "Die Lohnart ist ungültig. ";
                            } else {
                                if ($lohnbuchung->setLohnart($i['lohnart'])) {
                                    $success .= "Die Lohnart wurde erfolgreich geändert. ";
                                } else {
                                    $error .= "Beim Speichern der Lohnart ist ein technischer Fehler aufgetreten. ";
                                }
                            }
                        }

                        // Wert
                        if ($i['wert'] != $lohnbuchung->getWert()) {
                            if ($i['wert'] == '') {
                                if ($lohnbuchung->setWert(0)) {
                                    $success .= "Der Wert wurde erfolgreich geändert. ";
                                } else {
                                    $error .= "Beim Speichern des Werts ist ein technischer Fehler aufgetreten. ";
                                }
                            } else {
                                if ($lohnbuchung->setWert($i['wert'])) {
                                    $success .= "Der Wert wurde erfolgreich geändert. ";
                                } else {
                                    $error .= "Beim Speichern des Werts ist ein technischer Fehler aufgetreten. ";
                                }
                            }
                        }

                        // Faktor
                        if ($i['faktor'] != $lohnbuchung->getFaktor()) {
                            if ($i['faktor'] == '') {
                                if ($lohnbuchung->setFaktor(0)) {
                                    $success .= "Der Faktor wurde erfolgreich geändert. ";
                                } else {
                                    $error .= "Beim Speichern des Faktors ist ein technischer Fehler aufgetreten. ";
                                }
                            } else {
                                if ($lohnbuchung->setFaktor($i['faktor'])) {
                                    $success .= "Der Faktor wurde erfolgreich geändert. ";
                                } else {
                                    $error .= "Beim Speichern des Faktors ist ein technischer Fehler aufgetreten. ";
                                }
                            }
                        }

                        // Bezeichnung
                        if ($i['bezeichnung'] != $lohnbuchung->getBezeichnung()) {
                            if ($lohnbuchung->setBezeichnung($i['bezeichnung'])) {
                                $success .= "Die Bezeichnung wurde erfolgreich geändert. ";
                            } else {
                                $error .= "Beim Speichern der Bezeichnung ist ein technischer Fehler aufgetreten. ";
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
                        $this->misc_utils->redirect('mitarbeiter', 'bearbeiten', $lohnbuchung->getMitarbeiter()->getPersonalnummer(), 'lohnbuchungen', 'erfolgreich', 'bearbeitet');
                    }

                    // fill values into the form
                    $jahr = '';
                    if ($lohnbuchung->getDatum() instanceof \DateTime) {
                        $jahr = (int) $lohnbuchung->getDatum()->format("Y");
                    }
                    $monat = '';
                    if ($lohnbuchung->getDatum() instanceof \DateTime) {
                        $monat = (int) $lohnbuchung->getDatum()->format("m");
                    }
                    $values = [
                        'id'                => $lohnbuchung->getID(),
                        'personalnummer'    => $lohnbuchung->getMitarbeiter()->getPersonalnummer(),
                        'vorname'           => $lohnbuchung->getMitarbeiter()->getVorname(),
                        'nachname'          => $lohnbuchung->getMitarbeiter()->getNachname(),
                        'jahr'              => $jahr,
                        'monat'             => $monat,
                        'lohnart'           => $lohnbuchung->getLohnart(),
                        'wert'              => $lohnbuchung->getWert(),
                        'faktor'            => $lohnbuchung->getFaktor(),
                        'bezeichnung'       => $lohnbuchung->getBezeichnung()
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

                    // array with all input data
                    $i = [
                        'jahr'          => '',
                        'monat'         => '',
                        'lohnart'       => '',
                        'wert'          => '',
                        'faktor'        => '',
                        'bezeichnung'   => ''
                    ];
                    foreach ($i as $key => $value) {
                        $i[$key] = $this->user_input->getPostParameter($key);
                    }
                    $i['jahr'] = (int) $this->user_input->getOnlyNumbers($i['jahr']);
                    $i['monat'] = (int) $this->user_input->getOnlyNumbers($i['monat']);
                    $i['lohnart'] = (int) $this->user_input->getOnlyNumbers($i['lohnart']);
                    $i['wert'] = (float) str_replace(',', '.', $i['wert']);
                    $i['faktor'] = (float) str_replace(',', '.', $i['faktor']);

                    // check and save
                    if ($i['jahr'] != '' || $i['monat'] != '' || $i['lohnart'] != '' || $i['wert'] != '') {
                        if (!($i['jahr'] > 1000 && $i['jahr'] <= 9999)) {
                            $error .= "Das Jahr ist ungültig. ";
                        } elseif (!($i['monat'] > 0 && $i['monat'] <= 12)) {
                            $error .= "Der Monat ist ungültig. ";
                        } elseif ($i['lohnart'] == '' || $i['lohnart'] == 0) {
                            $error .= "Die Lohnart ist ungültig. ";
                        } else {
                            $datum = new \DateTime("now");
                            $datum->setDate($i['jahr'], $i['monat'], 1);
                            if ($datum->format("Y") != $i['jahr']) {
                                $error .= "Das Jahr ist ungültig. ";
                            } elseif ($datum->format("m") != $i['monat']) {
                                $error .= "Der Monat ist ungültig. ";
                            } else {
                                $wert = 0;
                                if ($i['wert'] != '') {
                                    $wert = $i['wert'];
                                }

                                $faktor = 0;
                                if ($i['faktor'] != '') {
                                    $faktor = $i['faktor'];
                                }

                                $data = [
                                    'mitarbeiter_id' => $mitarbeiter->getID(),
                                    'datum' => $datum->format("Y-m") . "-01",
                                    'lohnart' => $i['lohnart'],
                                    'wert' => $wert,
                                    'faktor' => $faktor,
                                    'bezeichnung' => $i['bezeichnung'],
                                    'user_id' => $this->current_user->getID()
                                ];

                                $lohnbuchung_model = \ttact\Models\LohnbuchungModel::createNew($this->db, $data);
                                if ($lohnbuchung_model instanceof \ttact\Models\LohnbuchungModel) {
                                    $success .= "Die Lohnbuchung wurde erfolgreich gespeichert. ";
                                } else {
                                    $error .= "Beim Speichern der Lohnbuchung ist ein technischer Fehler aufgetreten. ";
                                }
                            }
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
                        $this->misc_utils->redirect('mitarbeiter', 'bearbeiten', $mitarbeiter->getPersonalnummer(), 'lohnbuchungen', 'erfolgreich', 'erstellt');
                    }

                    // fill values into the form
                    $values = [
                        'personalnummer'    => $mitarbeiter->getPersonalnummer(),
                        'vorname'           => $mitarbeiter->getVorname(),
                        'nachname'          => $mitarbeiter->getNachname(),
                        'jahr'              => $i['jahr'] == 0 ? '' : $i['jahr'],
                        'monat'             => $i['monat'] == 0 ? '' : $i['monat'],
                        'lohnart'           => $i['lohnart'] == 0 ? '' : $i['lohnart'],
                        'wert'              => $i['wert'] == 0 ? '' : $i['wert'],
                        'faktor'            => $i['faktor'] == 0 ? '' : $i['faktor'],
                        'bezeichnung'       => $i['bezeichnung']
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
