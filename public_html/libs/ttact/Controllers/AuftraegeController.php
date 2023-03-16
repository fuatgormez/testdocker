<?php

namespace ttact\Controllers;

class AuftraegeController extends Controller
{
    public function ajax()
    {
        $kunde = (int) $this->user_input->getOnlyNumbers($this->user_input->getPostParameter('kunde'));
        $jahr = (int) $this->user_input->getOnlyNumbers($this->user_input->getPostParameter('jahr'));
        $kalenderwoche = (int) $this->user_input->getOnlyNumbers($this->user_input->getPostParameter('kalenderwoche'));
        if ($kunde > 0 && $jahr >= 1000 && $jahr <= 9999 && $kalenderwoche >= 1 && $kalenderwoche <= 53) {
            $start = new \DateTime();
            $start->setISODate($jahr, $kalenderwoche, 1);
            $start->setTime(0, 0, 0);

            /*$ende = new \DateTime();
            $ende->setISODate($jahr, $kalenderwoche, 7);
            $ende->setTime(23, 59, 59);*/

            $kunde_model = \ttact\Models\KundeModel::findByID($this->db, $kunde);
            if ($kunde_model instanceof \ttact\Models\KundeModel) {
                $data = [];
                foreach (\ttact\Models\AbteilungModel::findAll($this->db) as $abteilung) {
                    $data[$abteilung->getID()] = [
                        'id' => $abteilung->getID(),
                        'freigegeben' => false
                    ];
                }
                $kundenkonditionen = \ttact\Models\KundenkonditionModel::findAllByKundeForDate($this->db, $kunde_model->getID(), $start);
                foreach ($kundenkonditionen as $kundenkondition) {
                    $data[$kundenkondition->getAbteilung()->getID()]['freigegeben'] = true;
                }
                $this->smarty_vars['data']['abteilungen'] = $data;
                $this->smarty_vars['data']['status'] = 'success';
            }
        } else {
            $this->smarty_vars['data']['status'] = 'error';
        }
        $this->template = 'ajax';
    }

    public function index()
    {
        $error = "";
        $success = "";
        if (isset($this->params[0])) {
            if ($this->params[0] == "erfolgreich") {
                $success = "Der Auftrag wurde erfolgreich gespeichert.";
            }
        }
        $warning = "";

        // array with all input data
        $i = [
            'kunde'             => $this->user_input->getPostParameter("kunde"),
            'kalenderwoche'     => $this->user_input->getPostParameter("kalenderwoche"),
            'abteilungsauswahl' => [],
            'zaw'               => [],
            'jahr'              => $this->user_input->getPostParameter("jahr")
        ];
        foreach ($this->user_input->getArrayPostParameter("abteilungsauswahl") as $index => $value) {
            $abteilung = $this->user_input->getOnlyNumbers($value);
            if ($abteilung != "") {
                if (isset($this->user_input->getArrayPostParameter("zaw")[$index])) {
                    $zaw = $this->user_input->getArrayPostParameter("zaw")[$index];
                    if (count($zaw) == 14) {
                        $leere_zeile = true;
                        for ($n = 0; $n <= 13; $n++) {
                            $zaw[$n] = $this->user_input->getOnlyNumbers($zaw[$n]);
                            if ($this->user_input->getOnlyNumbers($zaw[$n]) != "") {
                                $leere_zeile = false;
                            }
                        }
                        if (!$leere_zeile) {
                            $i['abteilungsauswahl'][] = $abteilung;
                            $zeile = [];
                            for ($n = 0; $n <= 13; $n++) {
                                $zeile[$n]['value'] = $this->user_input->getOnlyNumbers($zaw[$n]);
                                $zeile[$n]['error'] = false;
                            }
                            $i['zaw'][] = $zeile;
                        }
                    } else {
                        $warning = "Es ist ein technischer Fehler aufgetreten.";
                    }
                } else {
                    $warning = "Es ist ein technischer Fehler aufgetreten.";
                }
            } else {
                if (count($this->user_input->getArrayPostParameter("abteilungsauswahl")) > 1 && isset($this->user_input->getArrayPostParameter("zaw")[$index])) {
                    $warning = "Eine oder mehrere Zeile(n) wurde(n) ignoriert, da keine Abteilung ausgewählt war.";
                }
            }
        }

        // check and save
        if (($i['kunde'] != "") || ($i['kalenderwoche'] != "") || count($i['abteilungsauswahl']) > 0) {
            // form was actually submitted
            if ($i['kunde'] != "" && $i['kalenderwoche'] != "") {
                if (count($i['abteilungsauswahl']) > 0) {
                    $wochentage = [
                        0   => "Montag",
                        1   => "Montag",
                        2   => "Dienstag",
                        3   => "Dienstag",
                        4   => "Mittwoch",
                        5   => "Mittwoch",
                        6   => "Donnerstag",
                        7   => "Donnerstag",
                        8   => "Freitag",
                        9   => "Freitag",
                        10  => "Samstag",
                        11  => "Samstag",
                        12  => "Sonntag",
                        13  => "Sonntag"
                    ];
                    foreach ($i['zaw'] as $index => $value) {
                        for ($n = 0; $n <= 13; $n += 2) {

                            $this_val = &$i['zaw'][$index][$n]['value'];
                            $next_val = &$i['zaw'][$index][$n + 1]['value'];
                            $this_err = &$i['zaw'][$index][$n]['error'];
                            $next_err = &$i['zaw'][$index][$n + 1]['error'];

                            if ($this_val != "" && $next_val != "") {
                                if (strlen($this_val) != 4 || !preg_match('/^[0-2][0-9][0-5][0-9]/', $this_val)) {
                                    $error = "<strong>" . $wochentage[$n] . "</strong>: Die Zeitangabe ist ungültig.";
                                    $this_err = true;
                                }
                                if (strlen($next_val) != 4 || !preg_match('/^[0-2][0-9][0-5][0-9]/', $next_val)) {
                                    $error = "<strong>" . $wochentage[$n] . "</strong>: Die Zeitangabe ist ungültig.";
                                    $next_err = true;
                                }
                            } elseif ($this_val == "" && $next_val != "") {
                                $error = "<strong>" . $wochentage[$n] . "</strong>: Es fehlt eine Startzeit.";
                                $this_err = true;
                                $next_err = true;
                            } elseif ($next_val == "" && $this_val != "") {
                                $error = "<strong>" . $wochentage[$n] . "</strong>: Es fehlt eine Endzeit.";
                                $this_err = true;
                                $next_err = true;
                            }
                        }
                    }
                    if ($error == "") {
                        $success = true;
                        $now = new \DateTime("now");
                        foreach ($i['zaw'] as $index => $zaw) {
                            $add_days = 0;
                            for ($n = 0; $n <= 13; $n += 2) {
                                if ($zaw[$n]['value'] != "") {
                                    $add = new \DateInterval("P0000-00-0". $add_days ."T00:00:00");

                                    $von = new \DateTime();
                                    $von->setISODate((int) $i['jahr'], $i['kalenderwoche']);
                                    $von->setTime((int) substr($zaw[$n]['value'], 0, 2), (int) substr($zaw[$n]['value'], 2, 2));
                                    $von->add($add);

                                    $bis = new \DateTime();
                                    $bis->setISODate((int) $i['jahr'], $i['kalenderwoche']);
                                    $bis->setTime((int) substr($zaw[$n + 1]['value'], 0, 2), (int) substr($zaw[$n + 1]['value'], 2, 2));
                                    $bis->add($add);

                                    if ($bis < $von) {
                                        $bis->add(new \DateInterval("P0000-00-01T00:00:00"));
                                    }

                                    $auftrag_data = [
                                        'kunde_id' => $i['kunde'],
                                        'abteilung_id' => $i['abteilungsauswahl'][$index],
                                        'mitarbeiter_id' => '',
                                        'von' => $von->format("Y-m-d H:i:s"),
                                        'bis' => $bis->format("Y-m-d H:i:s"),
                                        'erstellungszeitpunkt' => $now->format("Y-m-d H:i:s"),
                                        'user_id' => $this->current_user->getID()
                                    ];

                                    $auftrag = \ttact\Models\AuftragModel::createNew($this->db, $this->current_user, $auftrag_data);
                                    if (!($auftrag instanceof \ttact\Models\AuftragModel)) {
                                        $success = false;
                                    }
                                }
                                $add_days++;
                            }
                        }
                        if ($success) {
                            $this->misc_utils->redirect('auftraege', 'index', 'erfolgreich');
                        } else {
                            $error = "Beim Speichern des Auftrags ist ein Fehler aufgetreten.";
                        }
                    }
                } else {
                    $error = "Die Abteilung darf nicht leer sein.";
                }
            } else {
                $error = "Der Kunde und die Kalenderwoche dürfen nicht leer sein.";
            }
        } else {
            // pre-select Kalenderwoche
            $heute = new \DateTime("now");
            $i['kalenderwoche'] = $heute->format("W");
            $i['jahr'] = $heute->format("Y");
        }

        $this->smarty_vars['values']['kalenderwoche'] = $i['kalenderwoche'];
        $this->smarty_vars['values']['jahr'] = $i['jahr'];

        if ($error != "") {
            // display error message
            $this->smarty_vars['error'] = $error;
            // display all user input values
            $this->smarty_vars['values'] = $i;
        } else {
            if ($success != "") {
                // display success message
                $this->smarty_vars['success'] = $success;
            }
            if ($warning != "") {
                // display warnings
                $this->smarty_vars['warning'] = $warning;
            }
        }

        // get data for Kalenderwochen
        $this->smarty_vars['kalenderwochen'] = $this->misc_utils->getKalenderwochen(new \DateTime($i['jahr'] . "-01-01 00:00:00"));

        // get data for Jahresliste
        $year = new \DateTime("2015-01-01 00:00:00");
        $last_year = new \DateTime("now");
        $last_year->add(new \DateInterval("P0005-00-00T00:00:00"));
        $jahresliste = [];
        while ($year->format('Y') <= $last_year->format('Y')) {
            $jahresliste[] = $year->format('Y');
            $year->add(new \DateInterval('P0001-00-00T00:00:00'));
        }
        $this->smarty_vars['jahresliste'] = $jahresliste;

        // get data for Kunden
        $kunden = \ttact\Models\KundeModel::findAll($this->db);
        $kundenliste = [];
        foreach ($kunden as $k) {
            $kundenliste[$k->getKundennummer()] = [
                'id' => $k->getID(),
                'kundennummer' => $k->getKundennummer(),
                'name' => $k->getName()
            ];
        }
        ksort($kundenliste);
        $kundenbeschraenkungen = $this->current_user->getKundenbeschraenkungen();
        if (count($kundenbeschraenkungen) > 0) {
            $kundenliste = [];
            foreach ($kundenbeschraenkungen as $kundenbeschraenkung) {
                $kundenliste[$kundenbeschraenkung->getKunde()->getKundennummer()] = [
                    'id' => $kundenbeschraenkung->getKunde()->getID(),
                    'kundennummer' => $kundenbeschraenkung->getKunde()->getKundennummer(),
                    'name' => $kundenbeschraenkung->getKunde()->getName()
                ];
            }
        }
        $this->smarty_vars['kundenliste'] = $kundenliste;

        // get data for Abteilungen
        $abteilungen = \ttact\Models\AbteilungModel::findAll($this->db);
        $abteilungsliste = [];
        foreach ($abteilungen as $a) {
            $abteilungsliste[$a->getBezeichnung()] = [
                'id' => $a->getID(),
                'bezeichnung' => $a->getBezeichnung()
            ];
        }
        ksort($abteilungsliste);
        $this->smarty_vars['abteilungsliste'] = $abteilungsliste;

        // template settings
        $this->template = 'main';
    }
}
