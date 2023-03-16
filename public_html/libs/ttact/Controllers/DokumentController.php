<?php

namespace ttact\Controllers;

class DokumentController extends Controller
{
    public function anzeigen()
    {
        $error = true;

        if (isset($this->params[0])) {
            if ($this->user_input->isPositiveInteger($this->params[0])) {
                $dokument = \ttact\Models\DokumentModel::findByID($this->db, $this->user_input->getOnlyNumbers($this->params[0]));
                if ($dokument instanceof \ttact\Models\DokumentModel) {
                    $erlaubte_kundennummern = [];
                    if ($this->current_user->getUsergroup()->hasRight('dokumente_einsehen_bestimmte_kunden')) {
                        $alle_kundenbeschraenkungen = \ttact\Models\KundenbeschraenkungModel::findAllByUserID($this->db, $this->current_user->getID());
                        foreach ($alle_kundenbeschraenkungen as $kundenbeschraenkung) {
                            if ($kundenbeschraenkung->getKunde() instanceof \ttact\Models\KundeModel) {
                                $erlaubte_kundennummern[] = $kundenbeschraenkung->getKunde()->getKundennummer();
                            }
                        }
                    }

                    $deliver_file = false;
                    if ($this->current_user->getUsergroup()->hasRight('dokumente_alle_kunden')) {
                        $deliver_file = true;
                    } elseif ($this->current_user->getUsergroup()->hasRight('dokumente_einsehen_bestimmte_kunden')) {
                        if (in_array($dokument->getKunde()->getKundennummer(), $erlaubte_kundennummern)) {
                            $deliver_file = true;
                        }
                    }

                    if ($deliver_file) {
                        $ttact_intern_docs_path = __DIR__ . '/../../../../ttact-intern-docs/';
                        $file = $ttact_intern_docs_path . $dokument->getPath();

                        if (file_exists($file)) {
                            header('Content-Description: File Transfer');
                            header('Content-Type: application/octet-stream');
                            header('Content-Disposition: attachment; filename="' . $dokument->getName() . '"');
                            header('Expires: 0');
                            header('Cache-Control: must-revalidate');
                            header('Pragma: public');
                            header('Content-Length: ' . filesize($file));
                            readfile($file);
                            $error = false;
                        }
                    }
                }
            }
        }

        if ($error) {
            $this->template = '404';
        } else {
            $this->template = 'blank';
        }
    }

    public function bearbeiten()
    {
        $redirect = true;

        if (isset($this->params[0])) {
            if ($this->user_input->isPositiveInteger($this->params[0])) {
                $dokument = \ttact\Models\DokumentModel::findByID($this->db, $this->user_input->getOnlyNumbers($this->params[0]));
                if ($dokument instanceof \ttact\Models\DokumentModel) {
                    $redirect = false;

                    $error = "";
                    $success = "";
                    if (isset($this->params[1]) && isset($this->params[2])) {
                        if ($this->params[1] == 'erfolgreich' && $this->params[2] == 'bearbeitet') {
                            $success = 'Das Dokument wurde erfolgreich bearbeitet.';
                        }
                    }

                    // array with all input data
                    $i = [
                        'kundennummer'  => '',
                        'name'          => '',
                        'submitted'     => ''
                    ];
                    foreach ($i as $key => $value) {
                        $i[$key] = $this->user_input->getPostParameter($key);
                    }
                    $i['kundennummer'] = $this->user_input->getOnlyNumbers($i['kundennummer']);

                    // check and save
                    if ($i['submitted'] == 'true') {
                        if ($i['kundennummer'] != "" && $i['kundennummer'] != $dokument->getKunde()->getKundennummer()) {
                            if ($this->user_input->isPositiveInteger($i['kundennummer'])) {
                                // check if the provided Kundennummer is valid
                                $test = \ttact\Models\KundeModel::findByKundennummer($this->db, $this->user_input->getOnlyNumbers($i['kundennummer']));
                                if ($test instanceof \ttact\Models\KundeModel) {
                                    if ($dokument->setKundeID($test->getID())) {
                                        $success .= "Der Kunde wurde erfolgreich geändert. ";
                                    } else {
                                        $error .= "Beim Speichern des Kundens ist ein technischer Fehler aufgetreten. ";
                                    }
                                }
                            } else {
                                $error .= "Der Kunde ist ungültig.";
                            }
                        }

                        if ($i['name'] != "" && $i['name'] != $dokument->getName()) {
                            if ($dokument->setName($i['name'])) {
                                $success .= "Der Name wurde erfolgreich geändert. ";
                            } else {
                                $error .= "Beim Speichern des Namens ist ein technischer Fehler aufgetreten. ";
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
                        $this->smarty_vars['success'] = $success;
                    }

                    // fill values into the form
                    $values = [
                        'id'            => $dokument->getID(),
                        'kundennummer'  => $dokument->getKunde()->getKundennummer(),
                        'name'          => $dokument->getName()
                    ];
                    $this->smarty_vars['values'] = $values;

                    // $smarty_vars.kundenliste
                    $kundenliste = [];
                    $kunden = \ttact\Models\KundeModel::findAll($this->db);
                    foreach ($kunden as $kunde) {
                        $kundenliste[] = [
                            'kundennummer' => $kunde->getKundennummer(),
                            'name' => $kunde->getName()
                        ];
                    }
                    $this->smarty_vars['kundenliste'] = $kundenliste;

                    // template settings
                    $this->template = 'main';
                }
            }
        }

        if ($redirect) {
            $this->misc_utils->redirect('kunden', 'dokumente', 'fehler');
        }
    }

    public function loeschen()
    {
        $redirect = true;

        if (isset($this->params[0])) {
            if ($this->user_input->isPositiveInteger($this->params[0])) {
                $dokument = \ttact\Models\DokumentModel::findByID($this->db, $this->user_input->getOnlyNumbers($this->params[0]));
                if ($dokument instanceof \ttact\Models\DokumentModel) {
                    $ttact_intern_docs_path = __DIR__ . '/../../../../ttact-intern-docs/';
                    $file = $ttact_intern_docs_path . $dokument->getPath();

                    if (file_exists($file)) {
                        $redirect = false;

                        if (unlink($file)) {
                            if ($dokument->delete()) {
                                // Löschen erfolgreich
                                $this->misc_utils->redirect('kunden', 'dokumente', $dokument->getKunde()->getKundennummer(), 'erfolgreich');
                            } else {
                                // Löschen fehlgeschlagen
                                $this->misc_utils->redirect('kunden', 'dokumente', $dokument->getKunde()->getKundennummer(), 'fehler');
                            }
                        } else {
                            // Löschen fehlgeschlagen
                            $this->misc_utils->redirect('kunden', 'dokumente', $dokument->getKunde()->getKundennummer(), 'fehler');
                        }
                    }
                }
            }
        }

        if ($redirect) {
            $this->misc_utils->redirect('kunden', 'dokumente', 'fehler');
        }
    }
}
