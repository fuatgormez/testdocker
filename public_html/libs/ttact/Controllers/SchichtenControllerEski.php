<?php

namespace ttact\Controllers;

use League\Period\Period;

class SchichtenController extends Controller
{
    const OFFEN = "offen";
    const NICHT_BENACHRICHTIGT = "nicht_benachrichtigt";
    const BENACHRICHTIGT = "benachrichtigt";
    const NICHT_BESTAETIGT = "nicht_bestaetigt";
    const KANN_NICHT = "kann_nicht";
    const KANN_ANDERE_UHRZEIT = "kann_andere_uhrzeit";
    const STUNDENZETTEL_BESTAETIGT = "stundenzettel_bestaetigt";
    const ARCHIVIERT = "archiviert";

    public function index()
    {
        $error = "";

        if (isset($this->params[0])) {
            $this->smarty_vars['error'] = "Der Schichtplaner konnte nicht geöffnet werden. Unter den Filtern befindet sich mindestens eine ungültige Angabe.";
        }

        // array with all input data
        $i = [
            'kalenderwoche' => $this->user_input->getPostParameter('kalenderwoche'),
            'kunden' => $this->user_input->getArrayPostParameter('kunden'),
            'jahr' => $this->user_input->getPostParameter('jahr')
        ];
        if (is_array($i['kunden'])) {
            foreach ($i['kunden'] as $index => $value) {
                $i['kunden'][$index] = $this->user_input->getOnlyNumbers($value);
            }
        }

        // check and open Schichtplaner
        if ($i['jahr'] != "") {
            $jahr = (int) $this->user_input->getOnlyNumbers($i['jahr']);
            if ($jahr > 1000 && $jahr <= 9999) {
                // form was actually submitted
                if ($i['kalenderwoche'] != "") {
                    $kalenderwoche = (int) $this->user_input->getOnlyNumbers($i['kalenderwoche']);
                    if ($kalenderwoche > 0 && $kalenderwoche <= 53) {
                        if (count($i['kunden']) > 0) {
                            array_unshift($i['kunden'], "schichten", "planer", $jahr, $kalenderwoche);
                            call_user_func_array([$this->misc_utils, 'redirect'], $i['kunden']);
                        } else {
                            $this->misc_utils->redirect("schichten", "planer", $jahr, $kalenderwoche);
                        }
                    } else {
                        $error = "Es ist ein Fehler aufgetreten.";
                    }
                } else {
                    $error = "Es ist keine Kalenderwoche ausgewählt worden.";
                }
            } else {
                $error = "Es ist ein Fehler aufgetreten.";
            }
        }

        // error
        if ($error != "") {
            $this->smarty_vars['error'] = $error;
        }

        // get data for Kalenderwochen
        $this->smarty_vars['kalenderwochen'] = $this->misc_utils->getKalenderwochen(new \DateTime("now"));

        // get data for Kunden
        $kunden = \ttact\Models\KundeModel::findAll($this->db);
        $kundenliste = [];
        foreach ($kunden as $k) {
            $kundenliste[$k->getKundennummer()] = [
                'kundennummer' => $k->getKundennummer(),
                'name' => $k->getName()
            ];
        }
        ksort($kundenliste);
        $this->smarty_vars['kundenliste'] = $kundenliste;

        if ($this->current_user->getUsergroup()->hasRight('schichtplaner_alle_kunden')) {
            $this->smarty_vars['kunden_auswaehlen_anzeigen'] = true;
        } elseif ($this->current_user->getUsergroup()->hasRight('schichtplaner_bestimmte_kunden')) {
            $this->smarty_vars['kunden_auswaehlen_anzeigen'] = false;
        } else {
            $this->smarty_vars['kunden_auswaehlen_anzeigen'] = false;
            $this->misc_utils->redirect('fehler', 'index');
            return;
        }

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

        // set current year and calendar week
        $now = new \DateTime("now");
        $this->smarty_vars['values']['jahr'] = $now->format("Y");
        $this->smarty_vars['values']['kalenderwoche'] = $now->format("W");

        // get data for prev week/year + next week/year
        $previous_week = new \DateTime("now");
        $previous_week->sub(new \DateInterval("P0000-00-07T00:00:00"));
        $this->smarty_vars['prev']['week'] = $previous_week->format("W");
        $this->smarty_vars['prev']['year'] = $previous_week->format("Y");
        $next_week = new \DateTime("now");
        $next_week->add(new \DateInterval("P0000-00-07T00:00:00"));
        $this->smarty_vars['next']['week'] = $next_week->format("W");
        $this->smarty_vars['next']['year'] = $next_week->format("Y");

        // template settings
        $this->template = 'main';
    }

    public function ajax()
    {
        switch ($this->user_input->getPostParameter('type')) {
            case "kalenderwochen":
                $year = (int) $this->user_input->getOnlyNumbers($this->user_input->getPostParameter('year'));

                if ($year > 1000 && $year <= 9999) {
                    $this->smarty_vars['data']['status'] = 'success';
                    $this->smarty_vars['data']['kalenderwochen'] = $this->misc_utils->getKalenderwochen(new \DateTime($year . "-01-01 00:00:00"));
                } else {
                    $this->smarty_vars['data']['status'] = 'error';
                }

                break;
            case "set":
                if ($this->current_user->getUsergroup()->hasRight('schichtplaner_aenderungen')) {
                    $schichten_ids = $this->user_input->getArrayPostParameter('schichten');
                    $schichten = [];
                    foreach ($schichten_ids as $id) {
                        $id = $this->user_input->getOnlyNumbers($id);
                        $schicht = \ttact\Models\AuftragModel::findByID($this->db, $this->current_user, $id);
                        if ($schicht instanceof \ttact\Models\AuftragModel) {
                            $schichten[] = $schicht;
                        }
                    }
                    if (count($schichten) > 0) {
                        switch($this->user_input->getPostParameter("field")) {
                            case "mitarbeiter":
                                if ($this->user_input->getPostParameter("value") != "") {
                                    $mitarbeiter_id = $this->user_input->getOnlyNumbers($this->user_input->getPostParameter("value"));
                                    $mitarbeiter = \ttact\Models\MitarbeiterModel::findByID($this->db, $mitarbeiter_id);
                                    if ($mitarbeiter instanceof \ttact\Models\MitarbeiterModel) {
                                        foreach ($schichten as $schicht) {
                                            if ($schicht->getStatus() != self::ARCHIVIERT) {
                                                $schicht->setMitarbeiterID($mitarbeiter->getID());
                                                if ($schicht->getStatus() == self::OFFEN || $schicht->getStatus() == self::BENACHRICHTIGT || $schicht->getStatus() == self::STUNDENZETTEL_BESTAETIGT) {
                                                    $schicht->setStatus(self::NICHT_BENACHRICHTIGT);
                                                }
                                            }
                                        }
                                        $this->smarty_vars['data']['status'] = 'success';
                                    } else {
                                        $this->smarty_vars['data']['status'] = 'error';
                                    }
                                } else {
                                    foreach ($schichten as $schicht) {
                                        if ($schicht->getStatus() != self::ARCHIVIERT) {
                                            $schicht->setMitarbeiterID("");
                                            $schicht->setStatus(self::OFFEN);
                                            $schicht->setPause("00:00:00");
                                        }
                                    }
                                    $this->smarty_vars['data']['status'] = 'success';
                                }
                                break;
                            case "status":
                                switch ($this->user_input->getPostParameter("value")) {
                                    case self::BENACHRICHTIGT:
                                        switch ($this->user_input->getPostParameter("mode")) {
                                            case "markierte":
                                                foreach ($schichten as $schicht) {
                                                    if ($schicht->getStatus() == self::NICHT_BENACHRICHTIGT || $schicht->getStatus() == self::NICHT_BESTAETIGT || $schicht->getStatus() == self::KANN_NICHT || $schicht->getStatus() == self::KANN_ANDERE_UHRZEIT) {
                                                        $schicht->setStatus(self::BENACHRICHTIGT);
                                                    }
                                                }
                                                $this->smarty_vars['data']['status'] = 'success';
                                                break;
                                            case "alle_kunde":
                                                $mitarbeiter_ids = [];
                                                $kunde_model = null;
                                                $year = -1;
                                                $week = -1;
                                                foreach ($schichten as $schicht) {
                                                    if ($year == -1) {
                                                        $year = (int) $schicht->getVon()->format("Y");
                                                    }
                                                    if ($week == -1) {
                                                        $week = (int) $schicht->getVon()->format("W");
                                                    }
                                                    if ($schicht->getMitarbeiter() instanceof \ttact\Models\MitarbeiterModel) {
                                                        if (!in_array($schicht->getMitarbeiter()->getID(), $mitarbeiter_ids)) {
                                                            $mitarbeiter_ids[] = $schicht->getMitarbeiter()->getID();
                                                        }
                                                    }
                                                    if (!($kunde_model instanceof \ttact\Models\KundeModel) && ($schicht->getKunde() instanceof \ttact\Models\KundeModel)) {
                                                        $kunde_model = $schicht->getKunde();
                                                    }
                                                }
                                                if ($kunde_model instanceof \ttact\Models\KundeModel) {
                                                    if ($year > 1000 && $year <= 9999 && $week > 0 && $week <= 53) {
                                                        $alle_schichten = \ttact\Models\AuftragModel::findByYearWeekKundenMitarbeiter($this->db, $this->current_user, $year, $week, $kunde_model->getID(), $mitarbeiter_ids);
                                                        foreach ($alle_schichten as $schicht) {
                                                            if ($schicht->getStatus() == self::NICHT_BENACHRICHTIGT || $schicht->getStatus() == self::NICHT_BESTAETIGT || $schicht->getStatus() == self::KANN_NICHT || $schicht->getStatus() == self::KANN_ANDERE_UHRZEIT) {
                                                                $schicht->setStatus(self::BENACHRICHTIGT);
                                                            }
                                                        }
                                                    }
                                                }
                                                $this->smarty_vars['data']['status'] = 'success';
                                                break;
                                            case "alle_kw":
                                                $mitarbeiter_ids = [];
                                                $year = -1;
                                                $week = -1;
                                                foreach ($schichten as $schicht) {
                                                    if ($year == -1) {
                                                        $year = (int) $schicht->getVon()->format("Y");
                                                    }
                                                    if ($week == -1) {
                                                        $week = (int) $schicht->getVon()->format("W");
                                                    }
                                                    if ($schicht->getMitarbeiter() instanceof \ttact\Models\MitarbeiterModel) {
                                                        if (!in_array($schicht->getMitarbeiter()->getID(), $mitarbeiter_ids)) {
                                                            $mitarbeiter_ids[] = $schicht->getMitarbeiter()->getID();
                                                        }
                                                    }
                                                }
                                                if ($year > 1000 && $year <= 9999 && $week > 0 && $week <= 53) {
                                                    $alle_schichten = \ttact\Models\AuftragModel::findByYearWeekMitarbeiter($this->db, $this->current_user, $year, $week, $mitarbeiter_ids);
                                                    foreach ($alle_schichten as $schicht) {
                                                        if ($schicht->getStatus() == self::NICHT_BENACHRICHTIGT || $schicht->getStatus() == self::NICHT_BESTAETIGT || $schicht->getStatus() == self::KANN_NICHT || $schicht->getStatus() == self::KANN_ANDERE_UHRZEIT) {
                                                            $schicht->setStatus(self::BENACHRICHTIGT);
                                                        }
                                                    }
                                                }
                                                $this->smarty_vars['data']['status'] = 'success';
                                                break;
                                            default:
                                                $this->smarty_vars['data']['status'] = 'error';
                                                break;
                                        }
                                        break;
                                    case self::NICHT_BESTAETIGT:
                                    case self::KANN_NICHT:
                                    case self::KANN_ANDERE_UHRZEIT:
                                        foreach ($schichten as $schicht) {
                                            if ($schicht->getStatus() == self::NICHT_BENACHRICHTIGT || $schicht->getStatus() == self::BENACHRICHTIGT || $schicht->getStatus() == self::NICHT_BESTAETIGT || $schicht->getStatus() == self::KANN_NICHT || $schicht->getStatus() == self::KANN_ANDERE_UHRZEIT || $schicht->getStatus() == self::STUNDENZETTEL_BESTAETIGT) {
                                                $schicht->setStatus($this->user_input->getPostParameter("value"));
                                            }
                                        }
                                        $this->smarty_vars['data']['status'] = 'success';
                                        break;
                                    default:
                                        $this->smarty_vars['data']['status'] = 'error';
                                        break;
                                }
                                break;
                            case "pause":
                                $value = $this->user_input->getOnlyNumbers($this->user_input->getPostParameter("value"));
                                if (strlen($value) == 4 && preg_match('/^[0-9][0-9][0-5][0-9]$/', $value)) {
                                    foreach ($schichten as $schicht) {
                                        if ($schicht->getStatus() == self::BENACHRICHTIGT || $schicht->getStatus() == self::STUNDENZETTEL_BESTAETIGT) {
                                            if ($schicht->getStatus() == self::BENACHRICHTIGT) {
                                                $schicht->setStatus(self::STUNDENZETTEL_BESTAETIGT);
                                            }

                                            $interval = new \DateInterval("P0000-00-00T" . substr($value, 0, 2) . ":" . substr($value, 2, 2) . ":00");
                                            $schicht->setPause($interval->format("%H:%I"));
                                        }
                                    }
                                    $this->smarty_vars['data']['status'] = 'success';
                                } else {
                                    $this->smarty_vars['data']['status'] = 'error';
                                }
                                break;
                            case "zeit":
                                $von = $this->user_input->getOnlyNumbers($this->user_input->getPostParameter("value_von"));
                                $bis = $this->user_input->getOnlyNumbers($this->user_input->getPostParameter("value_bis"));
                                if (strlen($von) == 4 && preg_match('/^[0-2][0-9][0-5][0-9]$/', $von) && strlen($bis) == 4 && preg_match('/^[0-2][0-9][0-5][0-9]$/', $bis)) {
                                    foreach ($schichten as $schicht) {
                                        if ($schicht->getStatus() == self::OFFEN || $schicht->getStatus() == self::NICHT_BENACHRICHTIGT || $schicht->getStatus() == self::BENACHRICHTIGT || $schicht->getStatus() == self::NICHT_BESTAETIGT || $schicht->getStatus() == self::KANN_NICHT || $schicht->getStatus() == self::KANN_ANDERE_UHRZEIT || $schicht->getStatus() == self::STUNDENZETTEL_BESTAETIGT) {
                                            if ($schicht->getStatus() == self::BENACHRICHTIGT) {
                                                $schicht->setStatus(self::NICHT_BENACHRICHTIGT);
                                            }

                                            $save_von = new \DateTime($schicht->getVon()->format("Y-m-d") . " " . substr($von, 0, 2) . ":" . substr($von, 2, 2) . ":00");
                                            $save_bis = new \DateTime($schicht->getVon()->format("Y-m-d") . " " . substr($bis, 0, 2) . ":" . substr($bis, 2, 2) . ":00");

                                            if ($save_bis < $save_von) {
                                                $save_bis->add(new \DateInterval("P0000-00-01T00:00:00"));
                                            }

                                            $schicht->setVon($save_von->format("Y-m-d H:i:s"));
                                            $schicht->setBis($save_bis->format("Y-m-d H:i:s"));
                                        }
                                    }
                                    $this->smarty_vars['data']['status'] = 'success';
                                } else {
                                    $this->smarty_vars['data']['status'] = 'error';
                                }
                                break;
                            default:
                                $this->smarty_vars['data']['status'] = 'error';
                                break;
                        }
                    } else {
                        $this->smarty_vars['data']['status'] = 'error';
                    }
                }
                break;
            case "get":
                switch ($this->user_input->getPostParameter("data")) {
                    case "benachrichtigungen":
                        if ($this->current_user->getUsergroup()->hasRight('schichtplaner_aenderungen')) {
                            $schichten = [];
                            foreach ($this->user_input->getArrayPostParameter('schichten') as $id) {
                                $id = $this->user_input->getOnlyNumbers($id);
                                $schicht = \ttact\Models\AuftragModel::findByID($this->db, $this->current_user, $id);
                                if ($schicht instanceof \ttact\Models\AuftragModel) {
                                    $schichten[] = $schicht;
                                }
                            }
                            if (count($schichten) > 0) {
                                $wochentage = [
                                    1 => 'Montag',
                                    2 => 'Dienstag',
                                    3 => 'Mittwoch',
                                    4 => 'Donnerstag',
                                    5 => 'Freitag',
                                    6 => 'Samstag',
                                    7 => 'Sonntag'
                                ];

                                switch ($this->user_input->getPostParameter("mode")) {
                                    case "markierte":
                                        $output = [];
                                        $mitarbeiter = [];
                                        $schichten_ids = [];
                                        foreach ($schichten as $schicht) {
                                            if ($schicht->getMitarbeiter() instanceof \ttact\Models\MitarbeiterModel) {
                                                if (!key_exists($schicht->getMitarbeiter()->getID(), $mitarbeiter)) {
                                                    $mitarbeiter[$schicht->getMitarbeiter()->getID()] = $schicht->getMitarbeiter();
                                                }
                                            }
                                            $schichten_ids[] = $schicht->getID();
                                        }
                                        foreach ($mitarbeiter as $m) {
                                            $email = '';
                                            $whatsapp = '';
                                            $text = '' .
                                                'Sehr ' . ($m->getGeschlecht() == 'männlich' ? 'geehrter Herr ' . $m->getNachname() : 'geehrte Frau ' . $m->getNachname()) . ',' . PHP_EOL . '' .
                                                '' . PHP_EOL . '' .
                                                'bitte notieren Sie sich folgende Schichten:' .
                                                '';

                                            $alle_schichten = \ttact\Models\AuftragModel::findByMitarbeiterIDs($this->db, $this->current_user, $m->getID(), $schichten_ids);
                                            foreach ($alle_schichten as $schicht) {
                                                $text .= '' . PHP_EOL . '' .
                                                    '' . PHP_EOL . '' .
                                                    '' . $wochentage[$schicht->getVon()->format("N")] . ', ' . $schicht->getVon()->format("d.m.Y") . ' von ' . $schicht->getVon()->format("H:i") . ' bis ' . $schicht->getBis()->format("H:i") . ' Uhr' . PHP_EOL . '' .
                                                    '' . $schicht->getKunde()->getName() . ', Abt. ' . $schicht->getAbteilung()->getBezeichnung() . '' . PHP_EOL . '' .
                                                    '' . $schicht->getKunde()->getStrasse() . ', ' . $schicht->getKunde()->getPostleitzahl() . ' ' . $schicht->getKunde()->getOrt() . '' .
                                                    '';
                                            }

                                            $text .= '' . PHP_EOL . '' .
                                                '' . PHP_EOL . '' .
                                                'Bitte denken Sie unbedingt daran, den Erhalt dieser Nachricht kurz zu bestätigen. Vielen Dank.' . PHP_EOL . '' .
                                                '' . PHP_EOL . '' .
                                                'Mit freundlichen Grüßen,' . PHP_EOL . '' .
                                                'Ihre Dispo' .
                                                '';

                                            $email = "mailto:" . $m->getEmailadresse() . "?subject=Schichtplan&body=" . str_replace('+', ' ', urlencode($text));
                                            $phone = $m->getTelefon1();
                                            if (strlen($phone) > 0) {
                                                if ($phone[0] == '0') {
                                                    $phone = ltrim($phone, '0');
                                                    $phone = "49" . $phone;
                                                }
                                            }
                                            //$whatsapp = "whatsapp://send/?phone=" . $phone . "&text=" . str_replace('+', ' ', urlencode($text));
                                            $whatsapp = "https://api.whatsapp.com/send?phone=" . $phone . "&text=" . str_replace('+', '%20', urlencode($text));

                                            $output[] = [
                                                'personalnummer' => $m->getPersonalnummer(),
                                                'vorname' => $m->getVorname(),
                                                'nachname' => $m->getNachname(),
                                                'email' => $email,
                                                'whatsapp' => $whatsapp
                                            ];
                                        }
                                        $this->smarty_vars['data']['mitarbeiter'] = $output;
                                        $this->smarty_vars['data']['status'] = 'success';
                                        break;
                                    case "alle_kunde":
                                        $output = [];
                                        $mitarbeiter = [];
                                        $schichten_ids = [];
                                        $kunde_model = null;
                                        $year = -1;
                                        $week = -1;
                                        foreach ($schichten as $schicht) {
                                            $schichten_ids[] = $schicht->getID();
                                            if ($year == -1) {
                                                $year = (int)$schicht->getVon()->format("Y");
                                            }
                                            if ($week == -1) {
                                                $week = (int)$schicht->getVon()->format("W");
                                            }
                                            if (!($kunde_model instanceof \ttact\Models\KundeModel) && ($schicht->getKunde() instanceof \ttact\Models\KundeModel)) {
                                                $kunde_model = $schicht->getKunde();
                                            }
                                            if ($schicht->getMitarbeiter() instanceof \ttact\Models\MitarbeiterModel) {
                                                if (!key_exists($schicht->getMitarbeiter()->getID(), $mitarbeiter)) {
                                                    $mitarbeiter[$schicht->getMitarbeiter()->getID()] = $schicht->getMitarbeiter();
                                                }
                                            }
                                        }
                                        if ($kunde_model instanceof \ttact\Models\KundeModel) {
                                            if ($year > 1000 && $year <= 9999 && $week > 0 && $week <= 53) {
                                                foreach ($mitarbeiter as $m) {
                                                    $email = '';
                                                    $whatsapp = '';
                                                    $text = '' .
                                                        'Sehr ' . ($m->getGeschlecht() == 'männlich' ? 'geehrter Herr ' . $m->getNachname() : 'geehrte Frau ' . $m->getNachname()) . ',' . PHP_EOL . '' .
                                                        '' . PHP_EOL . '' .
                                                        'bitte notieren Sie sich folgende Schichten:' .
                                                        '';

                                                    $alle_schichten = \ttact\Models\AuftragModel::findByYearWeekKundenMitarbeiter($this->db, $this->current_user, $year, $week, $kunde_model->getID(), [$m->getID()]);
                                                    $wtage = [
                                                        1 => [],
                                                        2 => [],
                                                        3 => [],
                                                        4 => [],
                                                        5 => [],
                                                        6 => [],
                                                        7 => []
                                                    ];
                                                    foreach ($alle_schichten as $schicht) {
                                                        $wtage[$schicht->getVon()->format("N")][] = $schicht;
                                                    }
                                                    foreach ($wtage as $wtag => $array) {
                                                        if (count($array) > 0) {
                                                            foreach ($array as $schicht) {
                                                                $text .= '' . PHP_EOL . '' .
                                                                    '' . PHP_EOL . '' .
                                                                    '' . $wochentage[$schicht->getVon()->format("N")] . ', ' . $schicht->getVon()->format("d.m.Y") . ' von ' . $schicht->getVon()->format("H:i") . ' bis ' . $schicht->getBis()->format("H:i") . ' Uhr' . PHP_EOL . '' .
                                                                    '' . $schicht->getKunde()->getName() . ', Abt. ' . $schicht->getAbteilung()->getBezeichnung() . '' . PHP_EOL . '' .
                                                                    '' . $schicht->getKunde()->getStrasse() . ', ' . $schicht->getKunde()->getPostleitzahl() . ' ' . $schicht->getKunde()->getOrt() . '' .
                                                                    '';
                                                            }
                                                        } else {
                                                            $text .= '' . PHP_EOL . '' .
                                                                '' . PHP_EOL . '' .
                                                                '' . $wochentage[$wtag] . '' . PHP_EOL . '' .
                                                                '- frei -' .
                                                                '';
                                                        }
                                                    }

                                                    $text .= '' . PHP_EOL . '' .
                                                        '' . PHP_EOL . '' .
                                                        'Bitte denken Sie unbedingt daran, den Erhalt dieser Nachricht kurz zu bestätigen. Vielen Dank.' . PHP_EOL . '' .
                                                        '' . PHP_EOL . '' .
                                                        'Mit freundlichen Grüßen,' . PHP_EOL . '' .
                                                        'Ihre Dispo' .
                                                        '';

                                                    $email = "mailto:" . $m->getEmailadresse() . "?subject=Schichtplan&body=" . str_replace('+', ' ', urlencode($text));
                                                    $phone = $m->getTelefon1();
                                                    if (strlen($phone) > 0) {
                                                        if ($phone[0] == '0') {
                                                            $phone = ltrim($phone, '0');
                                                            $phone = "49" . $phone;
                                                        }
                                                    }
                                                    //$whatsapp = "whatsapp://send/?phone=" . $phone . "&text=" . str_replace('+', ' ', urlencode($text));
                                                    $whatsapp = "https://api.whatsapp.com/send?phone=" . $phone . "&text=" . str_replace('+', '%20', urlencode($text));

                                                    $output[] = [
                                                        'personalnummer' => $m->getPersonalnummer(),
                                                        'vorname' => $m->getVorname(),
                                                        'nachname' => $m->getNachname(),
                                                        'email' => $email,
                                                        'whatsapp' => $whatsapp
                                                    ];
                                                }
                                            }
                                        }
                                        $this->smarty_vars['data']['mitarbeiter'] = $output;
                                        $this->smarty_vars['data']['status'] = 'success';
                                        break;
                                    case "alle_kw":
                                        $output = [];
                                        $mitarbeiter = [];
                                        $schichten_ids = [];
                                        $year = -1;
                                        $week = -1;
                                        foreach ($schichten as $schicht) {
                                            $schichten_ids[] = $schicht->getID();
                                            if ($year == -1) {
                                                $year = (int)$schicht->getVon()->format("Y");
                                            }
                                            if ($week == -1) {
                                                $week = (int)$schicht->getVon()->format("W");
                                            }
                                            if ($schicht->getMitarbeiter() instanceof \ttact\Models\MitarbeiterModel) {
                                                if (!key_exists($schicht->getMitarbeiter()->getID(), $mitarbeiter)) {
                                                    $mitarbeiter[$schicht->getMitarbeiter()->getID()] = $schicht->getMitarbeiter();
                                                }
                                            }
                                        }
                                        if ($year > 1000 && $year <= 9999 && $week > 0 && $week <= 53) {
                                            foreach ($mitarbeiter as $m) {
                                                $email = '';
                                                $whatsapp = '';
                                                $text = '' .
                                                    'Sehr ' . ($m->getGeschlecht() == 'männlich' ? 'geehrter Herr ' . $m->getNachname() : 'geehrte Frau ' . $m->getNachname()) . ',' . PHP_EOL . '' .
                                                    '' . PHP_EOL . '' .
                                                    'bitte notieren Sie sich folgende Schichten:' .
                                                    '';

                                                $alle_schichten = \ttact\Models\AuftragModel::findByYearWeekMitarbeiter($this->db, $this->current_user, $year, $week, [$m->getID()]);
                                                $wtage = [
                                                    1 => [],
                                                    2 => [],
                                                    3 => [],
                                                    4 => [],
                                                    5 => [],
                                                    6 => [],
                                                    7 => []
                                                ];
                                                foreach ($alle_schichten as $schicht) {
                                                    $wtage[$schicht->getVon()->format("N")][] = $schicht;
                                                }
                                                foreach ($wtage as $wtag => $array) {
                                                    if (count($array) > 0) {
                                                        foreach ($array as $schicht) {
                                                            $text .= '' . PHP_EOL . '' .
                                                                '' . PHP_EOL . '' .
                                                                '' . $wochentage[$schicht->getVon()->format("N")] . ', ' . $schicht->getVon()->format("d.m.Y") . ' von ' . $schicht->getVon()->format("H:i") . ' bis ' . $schicht->getBis()->format("H:i") . ' Uhr' . PHP_EOL . '' .
                                                                '' . $schicht->getKunde()->getName() . ', Abt. ' . $schicht->getAbteilung()->getBezeichnung() . '' . PHP_EOL . '' .
                                                                '' . $schicht->getKunde()->getStrasse() . ', ' . $schicht->getKunde()->getPostleitzahl() . ' ' . $schicht->getKunde()->getOrt() . '' .
                                                                '';
                                                        }
                                                    } else {
                                                        $text .= '' . PHP_EOL . '' .
                                                            '' . PHP_EOL . '' .
                                                            '' . $wochentage[$wtag] . '' . PHP_EOL . '' .
                                                            '- frei -' .
                                                            '';
                                                    }
                                                }

                                                $text .= '' . PHP_EOL . '' .
                                                    '' . PHP_EOL . '' .
                                                    'Bitte denken Sie unbedingt daran, den Erhalt dieser Nachricht kurz zu bestätigen. Vielen Dank.' . PHP_EOL . '' .
                                                    '' . PHP_EOL . '' .
                                                    'Mit freundlichen Grüßen,' . PHP_EOL . '' .
                                                    'Ihre Dispo' .
                                                    '';

                                                $email = "mailto:" . $m->getEmailadresse() . "?subject=Schichtplan&body=" . str_replace('+', ' ', urlencode($text));
                                                $phone = $m->getTelefon1();
                                                if (strlen($phone) > 0) {
                                                    if ($phone[0] == '0') {
                                                        $phone = ltrim($phone, '0');
                                                        $phone = "49" . $phone;
                                                    }
                                                }
                                                //$whatsapp = "whatsapp://send/?phone=" . $phone . "&text=" . str_replace('+', '%20', urlencode($text));
                                                $whatsapp = "https://api.whatsapp.com/send?phone=" . $phone . "&text=" . str_replace('+', '%20', urlencode($text));

                                                $output[] = [
                                                    'personalnummer' => $m->getPersonalnummer(),
                                                    'vorname' => $m->getVorname(),
                                                    'nachname' => $m->getNachname(),
                                                    'email' => $email,
                                                    'whatsapp' => $whatsapp
                                                ];
                                            }
                                        }
                                        $this->smarty_vars['data']['mitarbeiter'] = $output;
                                        $this->smarty_vars['data']['status'] = 'success';
                                        break;
                                    default:
                                        $this->smarty_vars['data']['status'] = 'error';
                                        break;
                                }
                            } else {
                                $this->smarty_vars['data']['status'] = 'error';
                            }
                        }
                        break;
                    case "mitarbeiter":
                        if ($this->current_user->getUsergroup()->hasRight('schichtplaner_aenderungen')) {
                            $schichten_ids = $this->user_input->getArrayPostParameter('schichten');
                            $schichten = [];
                            foreach ($schichten_ids as $id) {
                                $id = $this->user_input->getOnlyNumbers($id);
                                $schicht = \ttact\Models\AuftragModel::findByID($this->db, $this->current_user, $id);
                                if ($schicht instanceof \ttact\Models\AuftragModel) {
                                    $schichten[] = $schicht;
                                }
                            }
                            $auswahl_ueberschneidet_sich = false;
                            foreach ($schichten as $schicht) {
                                $von = $schicht->getVon();
                                $bis = $schicht->getBis();
                                foreach ($schichten as $schicht2) {
                                    if ($schicht2 != $schicht) {
                                        $a = $schicht2->getVon();
                                        $b = $schicht2->getBis();

                                        if ($von >= $a && $von <= $b) {
                                            $auswahl_ueberschneidet_sich = true;
                                            break 2;
                                        } elseif ($bis >= $a && $bis <= $b) {
                                            $auswahl_ueberschneidet_sich = true;
                                            break 2;
                                        } elseif ($von < $a && $bis > $b) {
                                            $auswahl_ueberschneidet_sich = true;
                                            break 2;
                                        }
                                    }
                                }
                            }
                            if (count($schichten) > 0) {
                                $return_mitarbeiter = [];

                                if ($this->company == 'tps') {
                                    $vorzieher = \ttact\Models\MitarbeiterModel::findByPersonalnummer($this->db, 12260);
                                    if ($vorzieher instanceof \ttact\Models\MitarbeiterModel) {
                                        $return_mitarbeiter[$vorzieher->getID()] = [
                                            'data' => [
                                                "id" => $vorzieher->getID(),
                                                "vorname" => $vorzieher->getVorname(),
                                                "nachname" => $vorzieher->getNachname(),
                                                "personalnummer" => $vorzieher->getPersonalnummer()
                                            ],
                                            'sperre' => false,
                                            'stamm' => false,
                                            'springer' => false,
                                            'zeitliche_ueberschneidung' => false,
                                            'abteilungsfreigabe' => true,
                                            'innerhalb_arbeitszeiten' => true
                                        ];
                                    }
                                }

                                if (!$auswahl_ueberschneidet_sich) {
                                    // get year
                                    $jahr = (int)$schichten[0]->getVon()->format("Y");

                                    // get week
                                    $woche = (int)$schichten[0]->getVon()->format("W");

                                    // get Montag
                                    $montag = new \DateTime("0000-00-00 00:00:00");
                                    $montag->setISODate($jahr, $woche, 1);

                                    // get kunde_id
                                    $kunde_id = $schichten[0]->getKunde()->getID();

                                    // get abteilung_id
                                    $abteilung_id = $schichten[0]->getAbteilung()->getID();

                                    $mitarbeiterliste = \ttact\Models\MitarbeiterModel::findActivesSchichtplaner($this->db, $montag, $abteilung_id);
                                    foreach ($mitarbeiterliste as $mitarbeiter) {
                                        // sperre, stamm, and springer
                                        $sperre = false;
                                        $stamm = false;
                                        $springer = false;
                                        $mitarbeiterfilter = \ttact\Models\MitarbeiterfilterModel::findByMitarbeiter($this->db, $mitarbeiter->getID());
                                        foreach ($mitarbeiterfilter as $filter) {
                                            if ($filter->getKunde()->getID() == $kunde_id) {
                                                switch ($filter->getType()) {
                                                    case "stamm":
                                                        $stamm = true;
                                                        break;
                                                    case "springer":
                                                        $springer = true;
                                                        break;
                                                    case "sperre":
                                                        $sperre = true;
                                                        break;
                                                    default:
                                                        break;
                                                }
                                            }
                                        }

                                        if (!$sperre) {
                                            // auch noch am Tag der letzten Schicht aus der Selektion aktiv?
                                            $aktiv = true;
                                            if ($mitarbeiter->getAustritt() instanceof \DateTime) {
                                                foreach ($schichten as $schicht) {
                                                    if ($mitarbeiter->getAustritt() < $schicht->getBis()) {
                                                        $aktiv = false;
                                                        break;
                                                    }
                                                }
                                            }

                                            if ($aktiv) {
                                                // Kalendereinträge
                                                $hat_kalendereintrag = false;
                                                $kalendereintraege = \ttact\Models\KalendereintragModel::findByYearWeekMitarbeiter($this->db, $jahr, $woche, $mitarbeiter->getID());
                                                foreach ($kalendereintraege as $kalendereintrag) {
                                                    if ($kalendereintrag->getVon()->format("Y-m-d") == $kalendereintrag->getBis()->format("Y-m-d")) {
                                                        foreach ($schichten as $schicht) {
                                                            if ($schicht->getVon()->format("Y-m-d") == $kalendereintrag->getVon()->format("Y-m-d") || $schicht->getBis()->format("Y-m-d") == $kalendereintrag->getVon()->format("Y-m-d")) {
                                                                $hat_kalendereintrag = true;
                                                                break 2;
                                                            }
                                                        }
                                                    } else {
                                                        foreach ($schichten as $schicht) {
                                                            // checks
                                                            $von = $kalendereintrag->getVon();
                                                            $bis = $kalendereintrag->getBis();
                                                            $a = $schicht->getVon();
                                                            $b = $schicht->getBis();

                                                            if ($von >= $a && $von <= $b) {
                                                                $hat_kalendereintrag = true;
                                                                break 2;
                                                            } elseif ($bis >= $a && $bis <= $b) {
                                                                $hat_kalendereintrag = true;
                                                                break 2;
                                                            } elseif ($von < $a && $bis > $b) {
                                                                $hat_kalendereintrag = true;
                                                                break 2;
                                                            }
                                                        }
                                                    }
                                                }

                                                if (!$hat_kalendereintrag) {
                                                    // zeitliche Überschneidung mit anderen Schichten
                                                    $zeitliche_ueberschneidung = false;
                                                    $alle_schichten_des_mitarbeiters = \ttact\Models\AuftragModel::findByYearWeekMitarbeiter($this->db, $this->current_user, $jahr, $woche, [$mitarbeiter->getID()]);
                                                    foreach ($alle_schichten_des_mitarbeiters as $mitarbeiterschicht) {
                                                        foreach ($schichten as $planerschicht) {
                                                            // checks
                                                            $von = $mitarbeiterschicht->getVon();
                                                            $bis = $mitarbeiterschicht->getBis();
                                                            $a = $planerschicht->getVon();
                                                            $b = $planerschicht->getBis();

                                                            if ($von >= $a && $von <= $b) {
                                                                $zeitliche_ueberschneidung = true;
                                                                break 2;
                                                            } elseif ($bis >= $a && $bis <= $b) {
                                                                $zeitliche_ueberschneidung = true;
                                                                break 2;
                                                            } elseif ($von < $a && $bis > $b) {
                                                                $zeitliche_ueberschneidung = true;
                                                                break 2;
                                                            }
                                                        }
                                                    }

                                                    if (!$zeitliche_ueberschneidung) {
                                                        // abteilungsfreigabe
                                                        $abteilungsfreigabe = true;
                                                        /*$alle_abteilungsfreigaben_des_mitarbeiters = \ttact\Models\AbteilungsfreigabeModel::findByAbteilungMitarbeiter($this->db, $abteilung_id, $mitarbeiter->getID());
                                                        if (count($alle_abteilungsfreigaben_des_mitarbeiters) > 0) {
                                                        $abteilungsfreigabe = true;
                                                        }*/

                                                        // innerhalb_der_arbeitszeiten
                                                        $innerhalb_arbeitszeiten = true;
                                                        $wochentage = [
                                                            1 => ['klein' => 'montag', 'gross' => 'Montag'],
                                                            2 => ['klein' => 'dienstag', 'gross' => 'Dienstag'],
                                                            3 => ['klein' => 'mittwoch', 'gross' => 'Mittwoch'],
                                                            4 => ['klein' => 'donnerstag', 'gross' => 'Donnerstag'],
                                                            5 => ['klein' => 'freitag', 'gross' => 'Freitag'],
                                                            6 => ['klein' => 'samstag', 'gross' => 'Samstag'],
                                                            7 => ['klein' => 'sonntag', 'gross' => 'Sonntag']
                                                        ];
                                                        foreach ($schichten as $schicht) {
                                                            $wochentag = $wochentage[$schicht->getVon()->format("N")];
                                                            $method1 = 'get' . $wochentag['gross'] . 'Von';
                                                            $method2 = 'get' . $wochentag['gross'] . 'Bis';
                                                            if ($mitarbeiter->$method1() instanceof \DateTime && $mitarbeiter->$method2() instanceof \DateTime) {
                                                                $von = $schicht->getVon();
                                                                $bis = $schicht->getBis();
                                                                $a = new \DateTime($von->format("Y-m-d") . " " . $mitarbeiter->$method1()->format("H:i") . ":00");
                                                                $b = new \DateTime($bis->format("Y-m-d") . " " . $mitarbeiter->$method2()->format("H:i") . ":00");

                                                                if ($von >= $a && $von <= $b) {
                                                                    //
                                                                } elseif ($bis >= $a && $bis <= $b) {
                                                                    //
                                                                } elseif ($von < $a && $bis > $b) {
                                                                    //
                                                                } else {
                                                                    $innerhalb_arbeitszeiten = false;
                                                                    break;
                                                                }
                                                            }
                                                        }

                                                        if ($innerhalb_arbeitszeiten) {
                                                            $return_mitarbeiter[$mitarbeiter->getID()] = [
                                                                'data' => [
                                                                    "id" => $mitarbeiter->getID(),
                                                                    "vorname" => $mitarbeiter->getVorname(),
                                                                    "nachname" => $mitarbeiter->getNachname(),
                                                                    "personalnummer" => $mitarbeiter->getPersonalnummer()
                                                                ],
                                                                'sperre' => $sperre,
                                                                'stamm' => $stamm,
                                                                'springer' => $springer,
                                                                'zeitliche_ueberschneidung' => $zeitliche_ueberschneidung,
                                                                'abteilungsfreigabe' => $abteilungsfreigabe,
                                                                'innerhalb_arbeitszeiten' => $innerhalb_arbeitszeiten
                                                            ];
                                                        }
                                                    }
                                                }
                                            }

                                        }
                                    }
                                    usort($return_mitarbeiter, function ($a, $b) {
                                        if (($a['sperre'] && !$b['sperre']) || ($a['zeitliche_ueberschneidung'] && !$b['zeitliche_ueberschneidung']) || (!$a['abteilungsfreigabe'] && $b['abteilungsfreigabe'])) {
                                            return 1;
                                        } else {
                                            return -1;
                                        }
                                    });
                                }
                                $this->smarty_vars['data']['mitarbeiter'] = $return_mitarbeiter;
                                $this->smarty_vars['data']['status'] = 'success';
                            } else {
                                $this->smarty_vars['data']['status'] = 'error';
                            }
                        }
                        break;
                    case "schichten":
                        $last_update = new \DateTime($this->user_input->getPostParameter('last_update'));
                        $year = (int) $this->user_input->getOnlyNumbers($this->user_input->getPostParameter('year'));
                        $week = (int) $this->user_input->getOnlyNumbers($this->user_input->getPostParameter('week'));
                        $kunden = json_decode($this->user_input->getPostParameter('kunden'));

                        if (($last_update instanceof \DateTime) && $year > 1000 && $year <= 9999 && $week > 0 && $week <= 53) {
                            $requested_kunden = [];

                            $error = true;

                            $week_test = new \DateTime();
                            $week_test->setISODate($year, $week);
                            if ($week_test->format("W") == $week) {
                                // check validity of Kunden
                                $invalid_kunden = false;

                                foreach ($kunden as $kunde_model) {
                                    if (!$this->user_input->isPositiveInteger($kunde_model)) {
                                        $invalid_kunden = true;
                                    } else {
                                        $kunde_model = $this->user_input->getOnlyNumbers($kunde_model);
                                        $kunde_test = \ttact\Models\KundeModel::findByID($this->db, $kunde_model);
                                        if ($kunde_test instanceof \ttact\Models\KundeModel) {
                                            $requested_kunden[] = $kunde_test->getID();
                                        } else {
                                            $invalid_kunden = true;
                                        }
                                    }
                                }

                                if (!$invalid_kunden) {
                                    // show the specified Kunden
                                    $error = false;
                                }
                            }

                            if (!$error) {
                                $first_day = new \DateTime();
                                $first_day->setISODate($year, $week, 1);
                                $first_day->setTime(0, 0, 0);

                                $last_day = new \DateTime();
                                $last_day->setISODate($year, $week, 7);
                                $last_day->setTime(23, 59, 59);

                                $now = new \DateTime("now");
                                $this->smarty_vars['data']['last_update'] = $now->format("Y-m-d H:i:s");

                                $auftrag_logs = \ttact\Models\AuftragLogModel::findAllAfter($this->db, $this->current_user, $last_update);

                                $schichten = [];

                                $send_statistics = false;

                                function getSchichtstunden($db, $current_user, $kunde, $abteilung, $year, $week, $wochentag, &$stunden_insgesamt, &$stunden_wochentag) {
                                    $auftraege = \ttact\Models\AuftragModel::findByYearWeekKunden($db, $current_user, $year, $week, [$kunde]);
                                    foreach ($auftraege as $auftrag) {
                                        if ($auftrag->getAbteilung()->getID() == $abteilung) {
                                            $stunden = ($auftrag->getBis()->getTimestamp() - $auftrag->getVon()->getTimestamp() - $auftrag->getPauseSeconds()) / 3600;
                                            $stunden_insgesamt += $stunden;
                                            if ($auftrag->getVon()->format('N') == $wochentag) {
                                                $stunden_wochentag += $stunden;
                                            }
                                        }
                                    }
                                }

                                function sendSchichtstunden($db, $current_user, $kunde, $abteilung, $year, $week, $wochentag, &$schichten) {
                                    $stunden_insgesamt = 0;
                                    $stunden_wochentag = 0;
                                    getSchichtstunden($db, $current_user, $kunde, $abteilung, $year, $week, $wochentag, $stunden_insgesamt, $stunden_wochentag);

                                    $schichten[] = [
                                        'action' => 'update_schichtstunden',
                                        'kunde' => $kunde,
                                        'abteilung' => $abteilung,
                                        'wochentag' => $wochentag,
                                        'stunden_wochentag' => number_format($stunden_wochentag, 2, ',', ''),
                                        'stunden_insgesamt' => number_format($stunden_insgesamt, 2, ',', '')
                                    ];
                                }

                                if ($this->company == 'tps') {
                                    function getPalettenanzahl($db, $kunde, $abteilung, $year, $week, $wochentag, &$palettenanzahl_insgesamt, &$palettenanzahl_wochentag) {
                                        $paletten = \ttact\Models\PaletteModel::findByYearWeekKunden($db, $year, $week, [$kunde]);
                                        foreach ($paletten as $palette) {
                                            if ($palette->getAbteilung()->getID() == $abteilung) {
                                                $palettenanzahl_insgesamt += $palette->getAnzahl();
                                                if ($palette->getDatum()->format('N') == $wochentag) {
                                                    $palettenanzahl_wochentag += $palette->getAnzahl();
                                                }
                                            }
                                        }
                                    }

                                    function sendPalettenanzahl($db, $kunde, $abteilung, $year, $week, $wochentag, &$schichten) {
                                        $palettenanzahl_wochentag = 0;
                                        $palettenanzahl_insgesamt = 0;
                                        getPalettenanzahl($db, $kunde, $abteilung, $year, $week, $wochentag, $palettenanzahl_insgesamt, $palettenanzahl_wochentag);

                                        $schichten[] = [
                                            'action' => 'update_palettenanzahl',
                                            'kunde' => $kunde,
                                            'abteilung' => $abteilung,
                                            'wochentag' => $wochentag,
                                            'palettenanzahl_wochentag' => number_format($palettenanzahl_wochentag, 2, ',', ''),
                                            'palettenanzahl_insgesamt' => number_format($palettenanzahl_insgesamt, 2, ',', '')
                                        ];
                                    }

                                    function getSollStundenInsgesamt($db, $current_user, $kunde, $abteilung, $year, $week, &$soll_stunden_insgesamt) {
                                        $date = new \DateTime('now');
                                        $date->setTime(0, 0, 0);
                                        $datetime_zero = new \DateTime('0000-00-00 00:00:00');

                                        for ($i = 1; $i <= 7; $i++) {
                                            $date->setISODate($year, $week, $i);

                                            $kundenkondition = \ttact\Models\KundenkonditionModel::findForKundeAbteilungDate($db, $kunde, $abteilung, $date);
                                            if ($kundenkondition instanceof \ttact\Models\KundenkonditionModel) {
                                                $zeit_pro_palette = ($kundenkondition->getZeitProPalette()->getTimestamp() - $datetime_zero->getTimestamp()) / 3600;

                                                $palettenanzahl = 0;
                                                $paletten = \ttact\Models\PaletteModel::findByYearWeekKunden($db, $year, $week, [$kunde]);
                                                foreach ($paletten as $palette) {
                                                    if ($palette->getAbteilung()->getID() == $abteilung) {
                                                        if ($palette->getDatum()->format('N') == $i) {
                                                            $palettenanzahl += $palette->getAnzahl();
                                                        }
                                                    }
                                                }

                                            }
                                        }
                                    }

                                    function sendProduktivitaetsfaktor($db, $current_user, $kunde, $abteilung, $year, $week, $wochentag, &$schichten) {
                                        $palettenanzahl_wochentag = 0;
                                        $palettenanzahl_insgesamt = 0;
                                        getPalettenanzahl($db, $kunde, $abteilung, $year, $week, $wochentag, $palettenanzahl_insgesamt, $palettenanzahl_wochentag);

                                        $ist_stunden_insgesamt = 0;
                                        $ist_stunden_wochentag = 0;
                                        getSchichtstunden($db, $current_user, $kunde, $abteilung, $year, $week, $wochentag, $ist_stunden_insgesamt, $ist_stunden_wochentag);

                                        $soll_stunden_wochentag = 0;
                                        $date = new \DateTime();
                                        $date->setISODate($year, $week, $wochentag);
                                        $date->setTime(0, 0, 0);
                                        $zero = new \DateTime('0000-00-00 00:00:00');
                                        $kundenkondition = \ttact\Models\KundenkonditionModel::findForKundeAbteilungDate($db, $kunde, $abteilung, $date);
                                        if ($kundenkondition instanceof \ttact\Models\KundenkonditionModel) {
                                            $soll_stunden_wochentag = $palettenanzahl_wochentag * (($kundenkondition->getZeitProPalette()->getTimestamp() - $zero->getTimestamp()) / 3600);
                                        }

                                        $soll_stunden_insgesamt = 0;
                                        for ($i = 1; $i <= 7; $i++) {
                                            $date->setISODate($year, $week, $i);

                                            $kundenkondition = \ttact\Models\KundenkonditionModel::findForKundeAbteilungDate($db, $kunde, $abteilung, $date);
                                            if ($kundenkondition instanceof \ttact\Models\KundenkonditionModel) {
                                                $zeit_pro_palette = ($kundenkondition->getZeitProPalette()->getTimestamp() - $zero->getTimestamp()) / 3600;

                                                $palettenanzahl = 0;
                                                $temp = 0;
                                                getPalettenanzahl($db, $kunde, $abteilung, $year, $week, $i, $temp, $palettenanzahl);

                                                $soll_stunden_insgesamt += $palettenanzahl * $zeit_pro_palette;
                                            }
                                        }

                                        $schichten[] = [
                                            'action' => 'update_produktivitaetsfaktor',
                                            'kunde' => $kunde,
                                            'abteilung' => $abteilung,
                                            'wochentag' => $wochentag,
                                            'produktivitaetsfaktor_wochentag' => number_format($soll_stunden_wochentag > 0 ? $ist_stunden_wochentag / $soll_stunden_wochentag : 0, 2, ',', ''),
                                            'produktivitaetsfaktor_insgesamt' => number_format($soll_stunden_insgesamt > 0 ? $ist_stunden_insgesamt / $soll_stunden_insgesamt : 0, 2, ',', '')
                                        ];
                                    }
                                }

                                foreach ($auftrag_logs as $log) {
                                    if ($log->getAction() == "insert") {
                                        if ($log->getAuftrag() instanceof \ttact\Models\AuftragModel) {
                                            if ($log->getAuftrag()->getVon() >= $first_day && $log->getAuftrag()->getVon() <= $last_day) {
                                                $send_insert = false;

                                                if (count($requested_kunden) > 0) {
                                                    if (in_array($log->getAuftrag()->getKunde()->getID(), $requested_kunden)) {
                                                        $send_insert = true;
                                                    }
                                                } else {
                                                    $send_insert = true;
                                                }

                                                if ($send_insert) {
                                                    $schichten[] = [
                                                        'action' => 'insert'
                                                    ];
                                                }
                                            }
                                        }
                                    } elseif ($log->getAction() == "update") {
                                        $send_update = false;

                                        if ($log->getAuftrag() instanceof \ttact\Models\AuftragModel) {
                                            if ($log->getAuftrag()->getVon() >= $first_day && $log->getAuftrag()->getVon() <= $last_day) {
                                                if (count($requested_kunden) > 0) {
                                                    if ($log->getUpdateField() == "kunde_id") {
                                                        if (in_array($log->getUpdateOldValue(), $requested_kunden) && !in_array($log->getUpdateNewValue(), $requested_kunden)) {
                                                            // previous kunde_id was visible but new one is not --> remove from viewport (send action=delete to Schichtplaner)
                                                            $send_statistics = true;
                                                            $schichten[] = [
                                                                'action' => 'delete',
                                                                'id' => $log->getAuftragID()
                                                            ];
                                                        } elseif (!in_array($log->getUpdateOldValue(), $requested_kunden) && in_array($log->getUpdateNewValue(), $requested_kunden)) {
                                                            // previous kunde_id was not visible but new one is --> add to viewport (send action=insert to Schichtplaner)
                                                            $schichten[] = [
                                                                'action' => 'insert'
                                                            ];
                                                        } elseif (in_array($log->getUpdateOldValue(), $requested_kunden) && in_array($log->getUpdateNewValue(), $requested_kunden)) {
                                                            // previous kunde_id AND new one are BOTH visible --> just update (send action=update to Schichtplaner)
                                                            $schichten[] = [
                                                                'action' => 'update',
                                                                'id' => $log->getAuftrag()->getID(),
                                                                'field' => $log->getUpdateField(),
                                                                'value' => $log->getUpdateNewValue()
                                                            ];
                                                        }
                                                    } else {
                                                        if (in_array($log->getAuftrag()->getKunde()->getID(), $requested_kunden)) {
                                                            $send_update = true;
                                                        }
                                                    }
                                                } else {
                                                    $send_update = true;
                                                }
                                            }
                                        }

                                        if ($send_update) {
                                            switch ($log->getUpdateField()) {
                                                case "von":
                                                case "bis":
                                                    $datetime = new \DateTime($log->getUpdateNewValue());
                                                    $schichten[] = [
                                                        'action' => 'update',
                                                        'id' => $log->getAuftrag()->getID(),
                                                        'field' => $log->getUpdateField(),
                                                        'value' => $datetime->format("H:i")
                                                    ];
                                                    sendSchichtstunden($this->db, $this->current_user, $log->getAuftrag()->getKunde()->getID(), $log->getAuftrag()->getAbteilung()->getID(), $log->getAuftrag()->getVon()->format('Y'), $log->getAuftrag()->getVon()->format('W'), $log->getAuftrag()->getVon()->format('N'), $schichten);
                                                    if ($this->company == 'tps') {
                                                        sendProduktivitaetsfaktor($this->db, $this->current_user, $log->getAuftrag()->getKunde()->getID(), $log->getAuftrag()->getAbteilung()->getID(), $log->getAuftrag()->getVon()->format('Y'), $log->getAuftrag()->getVon()->format('W'), $log->getAuftrag()->getVon()->format('N'), $schichten);
                                                    }
                                                    break;
                                                case "status":
                                                    $send_statistics = true;

                                                    $schichten[] = [
                                                        'action' => 'update',
                                                        'id' => $log->getAuftrag()->getID(),
                                                        'field' => $log->getUpdateField(),
                                                        'value' => $log->getUpdateNewValue()
                                                    ];
                                                    break;
                                                case "mitarbeiter_id":
                                                    $mitarbeiter = \ttact\Models\MitarbeiterModel::findByID($this->db, $log->getUpdateNewValue());
                                                    if ($mitarbeiter instanceof \ttact\Models\MitarbeiterModel) {
                                                        $schichten[] = [
                                                            'action' => 'update',
                                                            'id' => $log->getAuftrag()->getID(),
                                                            'field' => 'vorname',
                                                            'value' => $mitarbeiter->getVorname(),
                                                            'mitarbeiter_id' => $mitarbeiter->getID()
                                                        ];
                                                        $schichten[] = [
                                                            'action' => 'update',
                                                            'id' => $log->getAuftrag()->getID(),
                                                            'field' => 'nachname',
                                                            'value' => $mitarbeiter->getNachname(),
                                                            'mitarbeiter_id' => $mitarbeiter->getID()
                                                        ];
                                                        $schichten[] = [
                                                            'action' => 'update',
                                                            'id' => $log->getAuftrag()->getID(),
                                                            'field' => 'personalnummer',
                                                            'value' => $mitarbeiter->getPersonalnummer(),
                                                            'mitarbeiter_id' => $mitarbeiter->getID()
                                                        ];
                                                    } elseif ($log->getUpdateNewValue() == "") {
                                                        $schichten[] = [
                                                            'action' => 'update',
                                                            'id' => $log->getAuftrag()->getID(),
                                                            'field' => 'vorname',
                                                            'value' => '',
                                                            'mitarbeiter_id' => ''
                                                        ];
                                                        $schichten[] = [
                                                            'action' => 'update',
                                                            'id' => $log->getAuftrag()->getID(),
                                                            'field' => 'nachname',
                                                            'value' => 'offen',
                                                            'mitarbeiter_id' => ''
                                                        ];
                                                        $schichten[] = [
                                                            'action' => 'update',
                                                            'id' => $log->getAuftrag()->getID(),
                                                            'field' => 'personalnummer',
                                                            'value' => '',
                                                            'mitarbeiter_id' => ''
                                                        ];
                                                    }
                                                    break;
                                                case "kunde_id":
                                                case "abteilung_id":
                                                    $schichten[] = [
                                                        'action' => 'update',
                                                        'id' => $log->getAuftrag()->getID(),
                                                        'field' => $log->getUpdateField(),
                                                        'value' => $log->getUpdateNewValue()
                                                    ];
                                                    break;
                                                case "pause":
                                                    $dateinterval = new \DateInterval("P0000-00-00T" . $log->getUpdateNewValue());
                                                    $schichten[] = [
                                                        'action' => 'update',
                                                        'id' => $log->getAuftrag()->getID(),
                                                        'field' => $log->getUpdateField(),
                                                        'value' => $dateinterval->format("%h:%I")
                                                    ];
                                                    sendSchichtstunden($this->db, $this->current_user, $log->getAuftrag()->getKunde()->getID(), $log->getAuftrag()->getAbteilung()->getID(), $log->getAuftrag()->getVon()->format('Y'), $log->getAuftrag()->getVon()->format('W'), $log->getAuftrag()->getVon()->format('N'), $schichten);
                                                    if ($this->company == 'tps') {
                                                        sendProduktivitaetsfaktor($this->db, $this->current_user, $log->getAuftrag()->getKunde()->getID(), $log->getAuftrag()->getAbteilung()->getID(), $log->getAuftrag()->getVon()->format('Y'), $log->getAuftrag()->getVon()->format('W'), $log->getAuftrag()->getVon()->format('N'), $schichten);
                                                    }
                                                    break;
                                                default:
                                                    break;
                                            }
                                        }

                                    } elseif ($log->getAction() == "delete") {
                                        if ($log->getDeleteVon() >= $first_day && $log->getDeleteVon() <= $last_day) {
                                            if (count($requested_kunden) > 0) {
                                                if (in_array($log->getDeleteKunde()->getID(), $requested_kunden))  {
                                                    $send_statistics = true;
                                                    $schichten[] = [
                                                        'action' => 'delete',
                                                        'id' => $log->getAuftragID()
                                                    ];
                                                    sendSchichtstunden($this->db, $this->current_user, $log->getDeleteKunde()->getID(), $log->getDeleteAbteilung()->getID(), $log->getDeleteVon()->format('Y'), $log->getDeleteVon()->format('W'), $log->getDeleteVon()->format('N'), $schichten);
                                                    if ($this->company == 'tps') {
                                                        sendProduktivitaetsfaktor($this->db, $this->current_user, $log->getDeleteKunde()->getID(), $log->getDeleteAbteilung()->getID(), $log->getDeleteVon()->format('Y'), $log->getDeleteVon()->format('W'), $log->getDeleteVon()->format('N'), $schichten);
                                                    }
                                                }
                                            } else {
                                                $send_statistics = true;
                                                $schichten[] = [
                                                    'action' => 'delete',
                                                    'id' => $log->getAuftragID()
                                                ];
                                                sendSchichtstunden($this->db, $this->current_user, $log->getDeleteKunde()->getID(), $log->getDeleteAbteilung()->getID(), $log->getDeleteVon()->format('Y'), $log->getDeleteVon()->format('W'), $log->getDeleteVon()->format('N'), $schichten);
                                                if ($this->company == 'tps') {
                                                    sendProduktivitaetsfaktor($this->db, $this->current_user, $log->getDeleteKunde()->getID(), $log->getDeleteAbteilung()->getID(), $log->getDeleteVon()->format('Y'), $log->getDeleteVon()->format('W'), $log->getDeleteVon()->format('N'), $schichten);
                                                }
                                            }
                                        }
                                    }
                                }

                                if ($send_statistics) {
                                    $insgesamt = \ttact\Models\AuftragModel::countByYearWeekKunden($this->db, $year, $week, $requested_kunden);
                                    $offen = \ttact\Models\AuftragModel::countByYearWeekKundenStatus($this->db, $year, $week, $requested_kunden, 'offen');

                                    $schichten[] = [
                                        'action' => 'update_statistics',
                                        'statistik_insgesamt' => $insgesamt,
                                        'statistik_offen' => $offen,
                                        'statistik_nicht_benachrichtigt' => \ttact\Models\AuftragModel::countByYearWeekKundenStatus($this->db, $year, $week, $requested_kunden, 'nicht_benachrichtigt'),
                                        'statistik_benachrichtigt' => \ttact\Models\AuftragModel::countByYearWeekKundenStatus($this->db, $year, $week, $requested_kunden, 'benachrichtigt'),
                                        'statistik_nicht_bestaetigt' => \ttact\Models\AuftragModel::countByYearWeekKundenStatus($this->db, $year, $week, $requested_kunden, 'nicht_bestaetigt'),
                                        'statistik_kann_nicht' => \ttact\Models\AuftragModel::countByYearWeekKundenStatus($this->db, $year, $week, $requested_kunden, 'kann_nicht'),
                                        'statistik_kann_andere_uhrzeit' => \ttact\Models\AuftragModel::countByYearWeekKundenStatus($this->db, $year, $week, $requested_kunden, 'kann_andere_uhrzeit'),
                                        'statistik_stundenzettel_bestaetigt' => \ttact\Models\AuftragModel::countByYearWeekKundenStatus($this->db, $year, $week, $requested_kunden, 'stundenzettel_bestaetigt'),
                                        'statistik_archiviert' => \ttact\Models\AuftragModel::countByYearWeekKundenStatus($this->db, $year, $week, $requested_kunden, 'archiviert'),
                                        'statistik_prozent' => $insgesamt > 0 ? round((($insgesamt - $offen) / $insgesamt) * 100) : 100
                                    ];
                                }

                                if ($this->company == 'tps') {
                                    $palette_logs = \ttact\Models\PaletteLogModel::findAllAfter($this->db, $this->current_user, $last_update);
                                    foreach ($palette_logs as $log) {
                                        $send_palettenanzahl_and_produktivitaetsfaktor = false;

                                        if ($log->getAction() == "insert") {
                                            if ($log->getPalette() instanceof \ttact\Models\PaletteModel) {
                                                if ($log->getPalette()->getDatum() >= $first_day && $log->getPalette()->getDatum() <= $last_day) {
                                                    if (count($requested_kunden) > 0) {
                                                        if (in_array($log->getPalette()->getKunde()->getID(), $requested_kunden)) {
                                                            $send_palettenanzahl_and_produktivitaetsfaktor = true;
                                                        }
                                                    } else {
                                                        $send_palettenanzahl_and_produktivitaetsfaktor = true;
                                                    }
                                                }
                                            }
                                        } elseif ($log->getAction() == "update") {
                                            if ($log->getPalette() instanceof \ttact\Models\PaletteModel) {
                                                if ($log->getPalette()->getDatum() >= $first_day && $log->getPalette()->getDatum() <= $last_day) {
                                                    if (count($requested_kunden) > 0) {
                                                        if ($log->getUpdateField() == "kunde_id") {
                                                            $send_palettenanzahl_and_produktivitaetsfaktor = true;
                                                        } elseif (in_array($log->getPalette()->getKunde()->getID(), $requested_kunden)) {
                                                            $send_palettenanzahl_and_produktivitaetsfaktor = true;
                                                        }
                                                    } else {
                                                        $send_palettenanzahl_and_produktivitaetsfaktor = true;
                                                    }
                                                }
                                            }
                                        } elseif ($log->getAction() == "delete") {
                                            if ($log->getDeleteDatum() >= $first_day && $log->getDeleteDatum() <= $last_day) {
                                                if (count($requested_kunden) > 0) {
                                                    if (in_array($log->getDeleteKunde()->getID(), $requested_kunden))  {
                                                        $send_palettenanzahl_and_produktivitaetsfaktor = true;
                                                    }
                                                } else {
                                                    $send_palettenanzahl_and_produktivitaetsfaktor = true;
                                                }
                                            }
                                        }

                                        if ($send_palettenanzahl_and_produktivitaetsfaktor) {
                                            if ($log->getAction() == 'delete') {
                                                sendPalettenanzahl($this->db, $log->getDeleteKunde()->getID(), $log->getDeleteAbteilung()->getID(), $log->getDeleteDatum()->format('Y'), $log->getDeleteDatum()->format('W'), $log->getDeleteDatum()->format('N'), $schichten);
                                                sendProduktivitaetsfaktor($this->db, $this->current_user, $log->getDeleteKunde()->getID(), $log->getDeleteAbteilung()->getID(), $log->getDeleteDatum()->format('Y'), $log->getDeleteDatum()->format('W'), $log->getDeleteDatum()->format('N'), $schichten);
                                            } else {
                                                sendPalettenanzahl($this->db, $log->getPalette()->getKunde()->getID(), $log->getPalette()->getAbteilung()->getID(), $log->getPalette()->getDatum()->format('Y'), $log->getPalette()->getDatum()->format('W'), $log->getPalette()->getDatum()->format('N'), $schichten);
                                                sendProduktivitaetsfaktor($this->db, $this->current_user, $log->getPalette()->getKunde()->getID(), $log->getPalette()->getAbteilung()->getID(), $log->getPalette()->getDatum()->format('Y'), $log->getPalette()->getDatum()->format('W'), $log->getPalette()->getDatum()->format('N'), $schichten);
                                            }
                                        }
                                    }
                                }

                                $this->smarty_vars['data']['schichten'] = $schichten;
                                $this->smarty_vars['data']['status'] = 'success';
                            } else {
                                $this->smarty_vars['data']['status'] = 'error';
                            }
                        } else {
                            $this->smarty_vars['data']['status'] = 'error';
                        }
                        break;
                    default:
                        $this->smarty_vars['data']['status'] = 'error';
                        break;
                }
                break;
            case "schnellinfo":
                if ($this->current_user->getUsergroup()->hasRight('schichtplaner_aenderungen')) {
                    $schicht_id = $this->user_input->getPostParameter('id');
                    $schicht_model = \ttact\Models\AuftragModel::findByID($this->db, $this->current_user, $schicht_id);
                    if ($schicht_model instanceof \ttact\Models\AuftragModel) {
                        if ($schicht_model->getMitarbeiter() instanceof \ttact\Models\MitarbeiterModel) {
                            // personalnummer
                            $this->smarty_vars['data']['personalnummer'] = $schicht_model->getMitarbeiter()->getPersonalnummer();

                            // telefon
                            $telefon = [];
                            if ($schicht_model->getMitarbeiter()->getTelefon1() != '') {
                                $telefon[] = $schicht_model->getMitarbeiter()->getTelefon1();
                            }
                            if ($schicht_model->getMitarbeiter()->getTelefon2() != '') {
                                $telefon[] = $schicht_model->getMitarbeiter()->getTelefon2();
                            }
                            if (count($telefon) > 0) {
                                $this->smarty_vars['data']['telefon'] = implode(', ', $telefon);
                            } else {
                                $this->smarty_vars['data']['telefon'] = '--';
                            }

                            // emailadresse
                            if ($schicht_model->getMitarbeiter()->getEmailadresse() != '') {
                                $this->smarty_vars['data']['emailadresse'] = $schicht_model->getMitarbeiter()->getEmailadresse();
                            } else {
                                $this->smarty_vars['data']['emailadresse'] = '--';
                            }

                            // vertr_woche
                            $lohnkonfiguration = \ttact\Models\LohnkonfigurationModel::findForMitarbeiterDate($this->db, $schicht_model->getMitarbeiter()->getID(), $schicht_model->getVon());
                            if ($lohnkonfiguration instanceof \ttact\Models\LohnkonfigurationModel) {
                                $this->smarty_vars['data']['vertr_woche'] = number_format($lohnkonfiguration->getWochenstunden(), 2, ',', '.');
                            } else {
                                $this->smarty_vars['data']['vertr_woche'] = '--';
                            }

                            // vertr_monat
                            if ($lohnkonfiguration instanceof \ttact\Models\LohnkonfigurationModel) {
                                $this->smarty_vars['data']['vertr_monat'] = number_format($lohnkonfiguration->getWochenstunden() * 4.333, 2, ',', '.');
                            } else {
                                $this->smarty_vars['data']['vertr_monat'] = '--';
                            }

                            // gepl_monat
                            $anfang = new \DateTime("now");
                            $anfang->setDate((int)$schicht_model->getVon()->format("Y"), (int)$schicht_model->getVon()->format("m"), 1);
                            $anfang->setTime(0, 0, 0);
                            $ende = clone $anfang;
                            $ende->setDate((int)$anfang->format("Y"), (int)$anfang->format("m"), (int)$anfang->format("t"));
                            $ende->setTime(23, 59, 59);
                            $alle_schichten = \ttact\Models\AuftragModel::findByStartEndMitarbeiterLohnberechnung($this->db, $this->current_user, $anfang, $ende, $schicht_model->getMitarbeiter()->getID());
                            $stunden = 0;
                            foreach ($alle_schichten as $schicht) {
                                $stunden += $schicht->getHours();
                            }
                            $this->smarty_vars['data']['gepl_monat'] = number_format($stunden, 2, ',', '.');

                            // gepl_woche
                            $anfang = new \DateTime("now");
                            $anfang->setISODate((int)$schicht_model->getVon()->format("Y"), (int)$schicht_model->getVon()->format("W"), 1);
                            $anfang->setTime(0, 0, 0);
                            $ende = clone $anfang;
                            $ende->setDate((int)$anfang->format("Y"), (int)$anfang->format("W"), 7);
                            $ende->setTime(23, 59, 59);
                            $alle_schichten = \ttact\Models\AuftragModel::findByStartEndMitarbeiterLohnberechnung($this->db, $this->current_user, $anfang, $ende, $schicht_model->getMitarbeiter()->getID());
                            $stunden = 0;
                            foreach ($alle_schichten as $schicht) {
                                $stunden += $schicht->getHours();
                            }
                            $this->smarty_vars['data']['gepl_woche'] = number_format($stunden, 2, ',', '.');
                        } else {
                            $this->smarty_vars['data']['personalnummer'] = '--';
                            $this->smarty_vars['data']['telefon'] = '--';
                            $this->smarty_vars['data']['emailadresse'] = '--';
                            $this->smarty_vars['data']['vertr_woche'] = '--';
                            $this->smarty_vars['data']['vertr_monat'] = '--';
                            $this->smarty_vars['data']['gepl_woche'] = '--';
                            $this->smarty_vars['data']['gepl_monat'] = '--';
                        }
                        $this->smarty_vars['data']['status'] = 'success';
                    }
                }
                break;
            case "delete":
                if ($this->current_user->getUsergroup()->hasRight('schichtplaner_aenderungen')) {
                    $schichten_ids = $this->user_input->getArrayPostParameter('schichten');
                    $schichten = [];
                    foreach ($schichten_ids as $id) {
                        $id = $this->user_input->getOnlyNumbers($id);
                        $schicht = \ttact\Models\AuftragModel::findByID($this->db, $this->current_user, $id);
                        if ($schicht instanceof \ttact\Models\AuftragModel) {
                            $schichten[] = $schicht;
                        }
                    }
                    if (count($schichten) > 0) {
                        foreach ($schichten as $schicht) {
                            if ($schicht->getStatus() == self::OFFEN || $schicht->getStatus() == self::NICHT_BENACHRICHTIGT || $schicht->getStatus() == self::BENACHRICHTIGT || $schicht->getStatus() == self::NICHT_BESTAETIGT || $schicht->getStatus() == self::KANN_NICHT || $schicht->getStatus() == self::KANN_ANDERE_UHRZEIT || $schicht->getStatus() == self::STUNDENZETTEL_BESTAETIGT) {
                                $schicht->delete();
                            }
                        }
                    }
                    $this->smarty_vars['data']['status'] = 'success';
                }
                break;
            case "add":
                if ($this->current_user->getUsergroup()->hasRight('schichtplaner_aenderungen')) {
                    $year = (int) $this->user_input->getOnlyNumbers($this->user_input->getPostParameter("year"));
                    $kw = (int) $this->user_input->getOnlyNumbers($this->user_input->getPostParameter("kw"));
                    $day = (int) $this->user_input->getOnlyNumbers($this->user_input->getPostParameter("day"));
                    $kunde_model = (int) $this->user_input->getOnlyNumbers($this->user_input->getPostParameter("kunde"));
                    $abteilung = (int) $this->user_input->getOnlyNumbers($this->user_input->getPostParameter("abteilung"));
                    $error = true;
                    if ($year > 1000 && $year <= 9999) {
                        if ($kw > 0 && $kw <= 53) {
                            if ($day >= 1 && $day <= 7) {
                                $date_model = new \DateTime("now");
                                $date_model->setISODate($year, $kw, $day);
                                $test_kunde = \ttact\Models\KundeModel::findByID($this->db, $kunde_model);
                                if ($test_kunde instanceof \ttact\Models\KundeModel) {
                                    $test_abteilung = \ttact\Models\AbteilungModel::findByID($this->db, $abteilung);
                                    if ($test_abteilung instanceof \ttact\Models\AbteilungModel) {
                                        $data = [
                                            'kunde_id' => $test_kunde->getID(),
                                            'abteilung_id' => $test_abteilung->getID(),
                                            'status' => 'offen',
                                            'von' => $date_model->format("Y-m-d") . ' 00:00:00',
                                            'bis' => $date_model->format("Y-m-d") . ' 23:59:00',
                                            'zusatzschicht' => '1'
                                        ];
                                        $auftrag_model = \ttact\Models\AuftragModel::createNew($this->db, $this->current_user, $data);
                                        if ($auftrag_model instanceof \ttact\Models\AuftragModel) {
                                            $error = false;
                                        }
                                    }
                                }
                            }
                        }
                    }
                    if ($error) {
                        $this->smarty_vars['data']['status'] = 'error';
                    } else {
                        $this->smarty_vars['data']['status'] = 'success';
                    }
                }
                break;
            case "close":
                if ($this->current_user->getUsergroup()->hasRight('schichtplaner_aenderungen')) {
                    $year = (int)$this->user_input->getOnlyNumbers($this->user_input->getPostParameter('year'));
                    $week = (int)$this->user_input->getOnlyNumbers($this->user_input->getPostParameter('week'));
                    $error = true;
                    if ($year > 1000 && $year <= 9999) {
                        if ($week > 0 && $week <= 53) {
                            $date_model = new \DateTime("now");
                            $date_model->setISODate($year, $week, 1);
                            $auftraege = [];

                            if ($this->user_input->getPostParameter('what') == 'woche') {
                                $auftraege = \ttact\Models\AuftragModel::findByYearWeekKunden($this->db, $this->current_user, $year, $week, []);
                                $error = false;
                            } elseif ($this->user_input->getPostParameter('what') == 'kunde') {
                                $kunde_id = (int)$this->user_input->getOnlyNumbers($this->user_input->getPostParameter('kunde'));
                                $test_kunde = \ttact\Models\KundeModel::findByID($this->db, $kunde_id);
                                if ($test_kunde instanceof \ttact\Models\KundeModel) {
                                    $auftraege = \ttact\Models\AuftragModel::findByYearWeekKunden($this->db, $this->current_user, $year, $week, [$test_kunde->getID()]);
                                    $error = false;
                                }
                            } elseif ($this->user_input->getPostParameter('what') == 'monat') {
                                $month = (int)$date_model->format("m");
                                $auftraege = \ttact\Models\AuftragModel::findByYearMonth($this->db, $this->current_user, $year, $month);
                                $error = false;
                            } elseif ($this->user_input->getPostParameter('what') == 'monat_kunde') {
                                $kunde_id = (int)$this->user_input->getOnlyNumbers($this->user_input->getPostParameter('kunde'));
                                $test_kunde = \ttact\Models\KundeModel::findByID($this->db, $kunde_id);
                                if ($test_kunde instanceof \ttact\Models\KundeModel) {
                                    $month = (int)$date_model->format("m");
                                    $auftraege = \ttact\Models\AuftragModel::findByYearMonthKunde($this->db, $this->current_user, $year, $month, $test_kunde->getID());
                                    $error = false;
                                }
                            }

                            if (!$error) {
                                $dont_save = false;

                                foreach ($auftraege as $auftrag) {
                                    if ($auftrag->getStatus() != self::STUNDENZETTEL_BESTAETIGT && $auftrag->getStatus() != self::ARCHIVIERT) {
                                        $dont_save = true;
                                    }
                                }

                                if (!$dont_save) {
                                    foreach ($auftraege as $auftrag) {
                                        $auftrag->setStatus(self::ARCHIVIERT);
                                    }
                                }
                            }
                        }
                    }
                    if ($error) {
                        $this->smarty_vars['data']['status'] = 'error';
                    } else {
                        $this->smarty_vars['data']['status'] = 'success';
                    }
                }
                break;
            case "open":
                if ($this->current_user->getUsergroup()->hasRight('schichtplaner_aenderungen')) {
                    $year = (int)$this->user_input->getOnlyNumbers($this->user_input->getPostParameter('year'));
                    $week = (int)$this->user_input->getOnlyNumbers($this->user_input->getPostParameter('week'));
                    $error = true;
                    if ($year > 1000 && $year <= 9999) {
                        if ($week > 0 && $week <= 53) {
                            $date_model = new \DateTime("now");
                            $date_model->setISODate($year, $week, 1);
                            $auftraege = [];

                            if ($this->user_input->getPostParameter('what') == 'woche') {
                                $auftraege = \ttact\Models\AuftragModel::findByYearWeekKunden($this->db, $this->current_user, $year, $week, []);
                                $error = false;
                            } elseif ($this->user_input->getPostParameter('what') == 'kunde') {
                                $kunde_id = (int)$this->user_input->getOnlyNumbers($this->user_input->getPostParameter('kunde'));
                                $test_kunde = \ttact\Models\KundeModel::findByID($this->db, $kunde_id);
                                if ($test_kunde instanceof \ttact\Models\KundeModel) {
                                    $auftraege = \ttact\Models\AuftragModel::findByYearWeekKunden($this->db, $this->current_user, $year, $week, [$test_kunde->getID()]);
                                    $error = false;
                                }
                            }

                            if (!$error) {
                                foreach ($auftraege as $auftrag) {
                                    if ($auftrag->getStatus() == self::ARCHIVIERT) {
                                        $auftrag->setStatus(self::STUNDENZETTEL_BESTAETIGT);
                                    }
                                }
                            }
                        }
                    }
                    if ($error) {
                        $this->smarty_vars['data']['status'] = 'error';
                    } else {
                        $this->smarty_vars['data']['status'] = 'success';
                    }
                }
                break;
            case "set_palettenanzahl":
                if ($this->company == 'tps') {
                    if ($this->current_user->getUsergroup()->hasRight('schichtplaner_aenderungen')) {
                        $year = (int) $this->user_input->getOnlyNumbers($this->user_input->getPostParameter('year'));
                        $week = (int) $this->user_input->getOnlyNumbers($this->user_input->getPostParameter('week'));
                        $day = (int) $this->user_input->getOnlyNumbers($this->user_input->getPostParameter('day'));
                        $error = true;
                        if ($year > 1000 && $year <= 9999) {
                            if ($week > 0 && $week <= 53) {
                                if ($day > 0 && $day <= 7) {
                                    $date_model = new \DateTime("now");
                                    $date_model->setISODate($year, $week, $day);
                                    $kunde_id = (int)$this->user_input->getOnlyNumbers($this->user_input->getPostParameter('kunde'));
                                    $kunde_model = \ttact\Models\KundeModel::findByID($this->db, $kunde_id);
                                    if ($kunde_model instanceof \ttact\Models\KundeModel) {
                                        $abteilung_id = (int) $this->user_input->getOnlyNumbers($this->user_input->getPostParameter('abteilung'));
                                        $abteilung_model = \ttact\Models\AbteilungModel::findByID($this->db, $abteilung_id);
                                        if ($abteilung_model instanceof \ttact\Models\AbteilungModel) {
                                            $temp_anzahl = $this->user_input->getPostParameter('anzahl');
                                            if (preg_match('/^\d{1,3}(,\d{1,2})?$/', $temp_anzahl)) {
                                                $anzahl = (float) str_replace(',', '.', $temp_anzahl);
                                                if ($anzahl >= 0 && $anzahl < 1000) {
                                                    // save
                                                    $paletten = \ttact\Models\PaletteModel::findByYearWeekDayKundenAbteilungen($this->db, $date_model->format('Y'), $date_model->format('W'), $date_model->format('N'), [$kunde_model->getID()], [$abteilung_model->getID()]);
                                                    $delete_errors = false;
                                                    $deleted_paletten = false;
                                                    foreach ($paletten as $palette) {
                                                        if (!$palette->delete()) {
                                                            $delete_errors = true;
                                                        } else {
                                                            $deleted_paletten = true;
                                                        }
                                                    }

                                                    if (!$delete_errors) {
                                                        $save_errors = false;

                                                        if ($anzahl > 0) {
                                                            $data = [
                                                                'kunde_id' => $kunde_model->getID(),
                                                                'abteilung_id' => $abteilung_model->getID(),
                                                                'datum' => $date_model->format('Y-m-d'),
                                                                'anzahl' => $anzahl
                                                            ];

                                                            if (\ttact\Models\PaletteModel::createNew($this->db, $data) instanceof \ttact\Models\PaletteModel) {
                                                                //
                                                            } else {
                                                                $save_errors = true;
                                                            }
                                                        }

                                                        if (!$save_errors) {
                                                            if ($anzahl == 0) {
                                                                if (!$deleted_paletten) {
                                                                    $this->smarty_vars['data']['set_to_zero'] = true;
                                                                }
                                                            }
                                                            $error = false;
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        if ($error) {
                            $this->smarty_vars['data']['status'] = 'error';
                        } else {
                            $this->smarty_vars['data']['status'] = 'success';
                        }
                    }
                    break;
                }
            default:
                $this->smarty_vars['data']['status'] = 'error';
                break;
        }

        // template settings
        $this->template = 'ajax';
    }

    public function planer()
    {
        $redirect = true;
        $requested_year = "";
        $requested_week = "";
        $requested_kunden = [];
        $requested_kunden_url_string = '';
        $kundenliste = [];

        if (isset($this->params[0]) && isset($this->params[1])) {
            if ($this->user_input->isPositiveInteger($this->params[0]) && $this->user_input->isPositiveInteger($this->params[1])) {
                $requested_year = $this->user_input->getOnlyNumbers($this->params[0]);
                $requested_week = $this->user_input->getOnlyNumbers($this->params[1]);

                if ($requested_year >= 1000 && $requested_year < 9999 && $requested_week > 0 && $requested_week <= 53) {
                    $week_test = new \DateTime();
                    $week_test->setISODate($requested_year, $requested_week);
                    if ($week_test->format("W") == $requested_week) {
                        if (isset($this->params[2])) {
                            // check validity of Kunden
                            $invalid_kunden = false;

                            $kunden = $this->params;
                            unset($kunden[0]);
                            unset($kunden[1]);
                            foreach ($kunden as $kunde) {
                                if (!$this->user_input->isPositiveInteger($kunde)) {
                                    $invalid_kunden = true;
                                } else {
                                    $kunde = $this->user_input->getOnlyNumbers($kunde);
                                    $kunde_test = \ttact\Models\KundeModel::findByKundennummer($this->db, $kunde);
                                    if ($kunde_test instanceof \ttact\Models\KundeModel) {
                                        $requested_kunden[] = $kunde_test->getID();
                                        $requested_kunden_url_string .= $kunde_test->getKundennummer() . "/";
                                        $kundenliste[$kunde_test->getKundennummer()] = [
                                            'id' => $kunde_test->getID(),
                                            'kundennummer' => $kunde_test->getKundennummer(),
                                            'name' => $kunde_test->getName(),
                                            'selected' => true
                                        ];
                                    } else {
                                        $invalid_kunden = true;
                                    }
                                }
                            }

                            if (!$invalid_kunden) {
                                // show the specified Kunden
                                $redirect = false;
                            }
                        } else {
                            // show all Kunden
                            $redirect = false;
                        }
                    }
                }
            }
        }

        if ($redirect) {
            $this->misc_utils->redirect('schichten', 'index', 'fehler');
        } else {
            if ($this->current_user->getUsergroup()->hasRight('schichtplaner_alle_kunden')) {
                $this->smarty_vars['kunden_auswaehlen_anzeigen'] = true;
            } elseif ($this->current_user->getUsergroup()->hasRight('schichtplaner_bestimmte_kunden')) {
                $this->smarty_vars['kunden_auswaehlen_anzeigen'] = false;
                $kundenbeschraenkungen = $this->current_user->getKundenbeschraenkungen();
                if (count($kundenbeschraenkungen) > 0) {
                    $requested_kunden = [];
                    foreach ($kundenbeschraenkungen as $kundenbeschraenkung) {
                        $requested_kunden[] = $kundenbeschraenkung->getKunde()->getID();
                    }
                } else {
                    $this->misc_utils->redirect('schichten', 'index', 'fehler');
                }
            } else {
                $this->smarty_vars['kunden_auswaehlen_anzeigen'] = false;
                $this->misc_utils->redirect('fehler', 'index');
                return;
            }

            if ($this->current_user->getUsergroup()->hasRight('schichtplaner_aenderungen')) {
                $this->smarty_vars['aenderungen'] = true;

                if ($this->current_user->getUsergroup()->hasRight('schichtplaner_alle_kunden')) {
                    $this->smarty_vars['woche_oeffnen_dispotabelle_herunterladen'] = true;
                } else {
                    $this->smarty_vars['woche_oeffnen_dispotabelle_herunterladen'] = false;
                }
            } else {
                $this->smarty_vars['aenderungen'] = false;
                $this->smarty_vars['woche_oeffnen_dispotabelle_herunterladen'] = false;
            }

            // --------------------------------------------------------------------
            # Get data for Schichtplaner
            // --------------------------------------------------------------------

            $auftraege = \ttact\Models\AuftragModel::findByYearWeekKunden($this->db, $this->current_user, $requested_year, $requested_week, $requested_kunden);
            $data = [];
            $wochentage = [
                1   => ['name' => 'Montag', 'datum' => ''],
                2   => ['name' => 'Dienstag', 'datum' => ''],
                3   => ['name' => 'Mittwoch', 'datum' => ''],
                4   => ['name' => 'Donnerstag', 'datum' => ''],
                5   => ['name' => 'Freitag', 'datum' => ''],
                6   => ['name' => 'Samstag', 'datum' => ''],
                7   => ['name' => 'Sonntag', 'datum' => ''],
            ];
            $tag = new \DateTime("now");
            $tag->setISODate($requested_year, $requested_week, 1);
            $intval_1_day = new \DateInterval("P0000-00-01T00:00:00");
            foreach ($wochentage as &$row) {
                $row['datum'] = $tag->format("d.m.");
                $tag->add($intval_1_day);
            }
            $abteilungsliste = [];
            $mitarbeiterliste = [];
            function storeKundendaten(&$data, $company, $kunde) {
                if (!isset($data[$kunde->getKundennummer()]['id'])) {
                    $data[$kunde->getKundennummer()]['id'] = $kunde->getID();
                    $data[$kunde->getKundennummer()]['name'] = $kunde->getName();
                    $data[$kunde->getKundennummer()]['kundennummer'] = $kunde->getKundennummer();

                    $data[$kunde->getKundennummer()]['strasse'] = $kunde->getStrasse();
                    $data[$kunde->getKundennummer()]['postleitzahl'] = $kunde->getPostleitzahl();
                    $data[$kunde->getKundennummer()]['ort'] = $kunde->getOrt();
                    $data[$kunde->getKundennummer()]['ansprechpartner'] = $kunde->getAnsprechpartner();
                    $data[$kunde->getKundennummer()]['telefon1'] = $kunde->getTelefon1();
                    $data[$kunde->getKundennummer()]['telefon2'] = $kunde->getTelefon2();
                    $data[$kunde->getKundennummer()]['fax'] = $kunde->getFax();
                    $data[$kunde->getKundennummer()]['emailadresse'] = $kunde->getEmailadresse();

                    if ($company != 'tps') {
                        $data[$kunde->getKundennummer()]['unterzeichnungsdatumrahmenvertrag'] = (($kunde->getUnterzeichnungsdatumRahmenvertrag() instanceof \DateTime) ? $kunde->getUnterzeichnungsdatumRahmenvertrag()->format('d.m.Y') : 'nicht bekannt');
                    }


                    /*if ($company != 'tps') {
                        $datum = $kunde->getUnterzeichnungsdatumRahmenvertrag();
                        $rechnungsanschrift = '';
                        $rechnungsanschrift_rows = explode(PHP_EOL, $kunde->getRechnungsanschrift());
                        foreach ($rechnungsanschrift_rows as $i => $row) {
                            $rechnungsanschrift .= trim($row);
                            if ($i < (count($rechnungsanschrift_rows) - 1)) {
                                $rechnungsanschrift .= ', ';
                            }
                        }
                        $data[$kunde->getKundennummer()]['kundeninformationen'] = $rechnungsanschrift . ($rechnungsanschrift != '' ? '; ' : '') . 'Rahmenvertrag vom: ' . ($datum instanceof \DateTime ? $datum->format('d.m.Y') : 'nicht bekannt');
                    }*/
                }
            }
            function storeAbteilungsdaten(&$data, $company, $kunde, $abteilung) {
                if (!isset($data[$kunde->getKundennummer()]['abteilungen'][$abteilung->getID()]['id'])) {
                    $data[$kunde->getKundennummer()]['abteilungen'][$abteilung->getID()]['id'] = $abteilung->getID();
                    $data[$kunde->getKundennummer()]['abteilungen'][$abteilung->getID()]['name'] = $abteilung->getBezeichnung();

                    if ($company == 'tps') {
                        $data[$kunde->getKundennummer()]['abteilungen'][$abteilung->getID()]['palettenabteilung'] = $abteilung->getPalettenabteilung();
                    }
                }
            }
            foreach ($auftraege as $auftrag) {
                // fill abteilungsliste
                $abteilungsliste[$auftrag->getAbteilung()->getID()] = [
                    'id' => $auftrag->getAbteilung()->getID(),
                    'bezeichnung' => $auftrag->getAbteilung()->getBezeichnung()
                ];

                // continue
                $vorname = "";
                $nachname = "";
                $mitarbeiter_id = "";
                $personalnummer = "";

                if ($auftrag->getMitarbeiter() instanceof \ttact\Models\MitarbeiterModel) {
                    $vorname = $auftrag->getMitarbeiter()->getVorname();
                    $nachname = $auftrag->getMitarbeiter()->getNachname();
                    $mitarbeiter_id = $auftrag->getMitarbeiter()->getID();
                    $personalnummer = $auftrag->getMitarbeiter()->getPersonalnummer();

                    // fill mitarbeiterliste
                    $mitarbeiterliste[$auftrag->getMitarbeiter()->getNachname() . $auftrag->getMitarbeiter()->getID()] = [
                        'id' => $auftrag->getMitarbeiter()->getID(),
                        'vorname' => $auftrag->getMitarbeiter()->getVorname(),
                        'nachname' => $auftrag->getMitarbeiter()->getNachname(),
                        'personalnummer' => $auftrag->getMitarbeiter()->getPersonalnummer()
                    ];
                } else {
                    $nachname = "offen";
                }

                $data[$auftrag->getKunde()->getKundennummer()]['abteilungen'][$auftrag->getAbteilung()->getID()]['schichten'][$auftrag->getVon()->format("N")][] = [
                    'id'                => $auftrag->getID(),
                    'von'               => $auftrag->getVon()->format("H:i"),
                    'bis'               => $auftrag->getBis()->format("H:i"),
                    'pause'             => (($auftrag->getStatus() == 'stundenzettel_bestaetigt' || $auftrag->getStatus() == 'archiviert') ? $auftrag->getPause()->format("%h:%I") : ''),
                    'vorname'           => $vorname,
                    'nachname'          => $nachname,
                    'personalnummer'    => $personalnummer,
                    'status'            => $auftrag->getStatus(),
                    'mitarbeiter_id'    => $mitarbeiter_id,
                    'zusatzschicht'     => $auftrag->getZusatzschicht()
                ];

                $stunden = ($auftrag->getBis()->getTimestamp() - $auftrag->getVon()->getTimestamp() - $auftrag->getPauseSeconds()) / 3600;
                if (!isset($data[$auftrag->getKunde()->getKundennummer()]['abteilungen'][$auftrag->getAbteilung()->getID()]['stunden'][$auftrag->getVon()->format("N")])) {
                    $data[$auftrag->getKunde()->getKundennummer()]['abteilungen'][$auftrag->getAbteilung()->getID()]['stunden'][$auftrag->getVon()->format("N")] = $stunden;
                } else {
                    $data[$auftrag->getKunde()->getKundennummer()]['abteilungen'][$auftrag->getAbteilung()->getID()]['stunden'][$auftrag->getVon()->format("N")] += $stunden;
                }
                if (!isset($data[$auftrag->getKunde()->getKundennummer()]['abteilungen'][$auftrag->getAbteilung()->getID()]['stunden']['insgesamt'])) {
                    $data[$auftrag->getKunde()->getKundennummer()]['abteilungen'][$auftrag->getAbteilung()->getID()]['stunden']['insgesamt'] = $stunden;
                } else {
                    $data[$auftrag->getKunde()->getKundennummer()]['abteilungen'][$auftrag->getAbteilung()->getID()]['stunden']['insgesamt'] += $stunden;
                }

                storeKundendaten($data, $this->company, $auftrag->getKunde());
                storeAbteilungsdaten($data, $this->company, $auftrag->getKunde(), $auftrag->getAbteilung());
            }

            if ($this->company == 'tps') {
                $paletten = \ttact\Models\PaletteModel::findByYearWeekKunden($this->db, $requested_year, $requested_week, $requested_kunden);
                foreach ($paletten as $palette) {
                    if (!isset($data[$palette->getKunde()->getKundennummer()]['abteilungen'][$palette->getAbteilung()->getID()]['paletten'][$palette->getDatum()->format("N")])) {
                        $data[$palette->getKunde()->getKundennummer()]['abteilungen'][$palette->getAbteilung()->getID()]['paletten'][$palette->getDatum()->format("N")] = $palette->getAnzahl();
                    } else {
                        $data[$palette->getKunde()->getKundennummer()]['abteilungen'][$palette->getAbteilung()->getID()]['paletten'][$palette->getDatum()->format("N")] += $palette->getAnzahl();
                    }
                    if (!isset($data[$palette->getKunde()->getKundennummer()]['abteilungen'][$palette->getAbteilung()->getID()]['paletten']['insgesamt'])) {
                        $data[$palette->getKunde()->getKundennummer()]['abteilungen'][$palette->getAbteilung()->getID()]['paletten']['insgesamt'] = $palette->getAnzahl();
                    } else {
                        $data[$palette->getKunde()->getKundennummer()]['abteilungen'][$palette->getAbteilung()->getID()]['paletten']['insgesamt'] += $palette->getAnzahl();
                    }

                    storeKundendaten($data, $this->company, $palette->getKunde());
                    storeAbteilungsdaten($data, $this->company, $palette->getKunde(), $palette->getAbteilung());
                }
            }

            ksort($data);

            foreach ($data as &$kunde) {
                foreach ($kunde['abteilungen'] as &$abteilung) {
                    if ($this->company == 'tps') {
                        if ($abteilung['palettenabteilung']) {
                            $abteilung['produktivitaetsfaktor'] = [];
                            $soll_stunden_insgesamt = 0;
                        }
                    }

                    if (!isset($abteilung['stunden']['insgesamt'])) {
                        $abteilung['stunden']['insgesamt'] = 0;
                    }

                    foreach ($wochentage as $index => $wochentag) {
                        if (!isset($abteilung['schichten'][$index])) {
                            $abteilung['schichten'][$index] = [];
                        }

                        if (!isset($abteilung['stunden'][$index])) {
                            $abteilung['stunden'][$index] = 0;
                        }

                        if ($this->company == 'tps') {
                            if ($abteilung['palettenabteilung']) {
                                if (!isset($abteilung['paletten'][$index])) {
                                    $abteilung['paletten'][$index] = 0;
                                }

                                // Produktivitaetsfaktor WOCHENTAG
                                $ist_stunden = $abteilung['stunden'][$index];

                                $zeit_pro_palette = 1.5;
                                $datum = new \DateTime("now");
                                $datum->setISODate($requested_year, $requested_week, $index);
                                $datum->setTime(0, 0, 0);
                                $kundenkondition = \ttact\Models\KundenkonditionModel::findForKundeAbteilungDate($this->db, $kunde['id'], $abteilung['id'], $datum);
                                if ($kundenkondition instanceof \ttact\Models\KundeModel) {
                                    $zeit_pro_palette_datetime = $kundenkondition->getZeitProPalette();
                                    if ($zeit_pro_palette_datetime instanceof \DateTime) {
                                        $null_uhr_datetime = new \DateTime($zeit_pro_palette_datetime->format('Y-m-d') . ' 00:00:00');
                                        if ($null_uhr_datetime instanceof \DateTime) {
                                            $zeit_pro_palette = ($zeit_pro_palette_datetime->getTimestamp() - $null_uhr_datetime->getTimestamp()) / 3600;
                                        }
                                    }
                                }
                                $soll_stunden = $abteilung['paletten'][$index] * $zeit_pro_palette;
                                $soll_stunden_insgesamt += $abteilung['paletten'][$index] * $zeit_pro_palette;

                                $abteilung['produktivitaetsfaktor'][$index] = $soll_stunden > 0 ? $ist_stunden / $soll_stunden : 0;
                            }
                        }
                    }

                    // Produktivitaetsfaktor INSGESAMT
                    if ($this->company == 'tps') {
                        if ($abteilung['palettenabteilung']) {
                            $ist_stunden = $abteilung['stunden']['insgesamt'];
                            $abteilung['produktivitaetsfaktor']['insgesamt'] = $soll_stunden_insgesamt > 0 ? $ist_stunden / $soll_stunden_insgesamt : 0;
                        }
                    }

                    ksort($abteilung['schichten']);
                }

                ksort($kunde['abteilungen']);
            }

            // --------------------------------------------------------------------
            # Set vars for header title
            // --------------------------------------------------------------------

            // $smarty_vars.von
            $von = new \DateTime("now");
            $von->setISODate($requested_year, $requested_week, 1);
            $von->setTime(0, 0, 0);
            $this->smarty_vars['von'] = $von;

            // $smarty_vars.requested_year
            $this->smarty_vars['requested_year'] = $requested_year;

            // $smarty_vars.requested_week
            $this->smarty_vars['requested_week'] = $requested_week;

            // $smarty_vars.bis
            $bis = clone $von;
            $bis->add(new \DateInterval("P0000-00-06T00:00:00"));
            $this->smarty_vars['bis'] = $bis;

            // $smarty_vars.letzte_woche.jahr & $smarty_vars.letzte_woche.kw
            $letzte_woche_von = clone $von;
            $letzte_woche_von->sub(new \DateInterval("P0000-00-07T00:00:00"));
            $this->smarty_vars['letzte_woche']['kw'] = (int) $letzte_woche_von->format("W");
            if ($letzte_woche_von->format("W") == 1) {
                $this->smarty_vars['letzte_woche']['jahr'] = $von->format("Y");
            } else {
                $this->smarty_vars['letzte_woche']['jahr'] = $letzte_woche_von->format("Y");
            }

            // $smarty_vars.naechste_woche.jahr & $smarty_vars.naechste_woche.kw
            $naechste_woche_von = clone $von;
            $naechste_woche_von->add(new \DateInterval("P0000-00-07T00:00:00"));
            $naechste_woche_jahr = (int) $naechste_woche_von->format("Y");
            $naechste_woche_kw = (int) $naechste_woche_von->format("W");
            if ($naechste_woche_kw == 1) {
                $naechste_woche_jahr++;
            }
            $this->smarty_vars['naechste_woche']['jahr'] = $naechste_woche_jahr;
            $this->smarty_vars['naechste_woche']['kw'] = $naechste_woche_kw;

            // $smarty_vars.kunden
            $kundenauswahlanzeige = "Alle Kunden";
            if (count($requested_kunden) == 1) {
                $kundenauswahlanzeige = "1 Kunde";
            } elseif (count($requested_kunden) > 1) {
                $kundenauswahlanzeige = count($requested_kunden) . " Kunden";
            }
            $this->smarty_vars['kunden'] = $kundenauswahlanzeige;

            // $smarty_vars.kunden_url_string
            $this->smarty_vars['kunden_url_string'] = $requested_kunden_url_string;

            // --------------------------------------------------------------------
            # Set vars for header menu
            // --------------------------------------------------------------------

            // $smarty_vars.kundenliste
            $kunden = \ttact\Models\KundeModel::findAll($this->db);
            foreach ($kunden as $k) {
                if (!isset($kundenliste[$k->getKundennummer()])) {
                    $kundenliste[$k->getKundennummer()] = [
                        'id' => $k->getID(),
                        'kundennummer' => $k->getKundennummer(),
                        'name' => $k->getName(),
                        'selected' => false
                    ];
                }
            }
            ksort($kundenliste);
            $this->smarty_vars['kundenliste'] = $kundenliste;

            // $smarty_vars.values.jahr
            $this->smarty_vars['values']['jahr'] = $von->format("Y");

            // $smarty_vars.values.kalenderwoche
            $this->smarty_vars['values']['kalenderwoche'] = $von->format("W");

            // $smarty_vars.kalenderwochens
            $this->smarty_vars['kalenderwochen'] = $this->misc_utils->getKalenderwochen($von);
            $this->smarty_vars['values']['title_zusatz'] = ' | KW ' . $von->format("W");

            // $smarty_vars.jahresliste
            $year = new \DateTime("2015-01-01 00:00:00");
            $last_year = new \DateTime("now");
            $last_year->add(new \DateInterval("P0005-00-00T00:00:00"));
            $jahresliste = [];
            while ($year->format('Y') <= $last_year->format('Y')) {
                $jahresliste[] = $year->format('Y');
                $year->add(new \DateInterval('P0001-00-00T00:00:00'));
            }
            $this->smarty_vars['jahresliste'] = $jahresliste;

            // $smarty_vars.abteilungsliste
            $this->smarty_vars['abteilungsliste'] = $abteilungsliste;

            // $smarty_vars.mitarbeiterliste
            ksort($mitarbeiterliste);
            $this->smarty_vars['mitarbeiterliste'] = $mitarbeiterliste;

            // $smarty_vars.prev.week
            $previous_week = new \DateTime("now");
            $previous_week->sub(new \DateInterval("P0000-00-07T00:00:00"));
            $this->smarty_vars['prev']['week'] = $previous_week->format("W");

            // $smarty_vars.prev.year
            $this->smarty_vars['prev']['year'] = $previous_week->format("Y");

            // $smarty_vars.next.week
            $next_week = new \DateTime("now");
            $next_week->add(new \DateInterval("P0000-00-07T00:00:00"));
            $this->smarty_vars['next']['week'] = $next_week->format("W");

            // $smarty_vars.next.year
            $this->smarty_vars['next']['year'] = $next_week->format("Y");

            // $smarty_vars.statistiken.xxxxx
            $this->smarty_vars['statistiken']['insgesamt'] = \ttact\Models\AuftragModel::countByYearWeekKunden($this->db, $requested_year, $requested_week, $requested_kunden);
            $this->smarty_vars['statistiken']['offen'] = \ttact\Models\AuftragModel::countByYearWeekKundenStatus($this->db, $requested_year, $requested_week, $requested_kunden, 'offen');
            $this->smarty_vars['statistiken']['nicht_benachrichtigt'] = \ttact\Models\AuftragModel::countByYearWeekKundenStatus($this->db, $requested_year, $requested_week, $requested_kunden, 'nicht_benachrichtigt');
            $this->smarty_vars['statistiken']['benachrichtigt'] = \ttact\Models\AuftragModel::countByYearWeekKundenStatus($this->db, $requested_year, $requested_week, $requested_kunden, 'benachrichtigt');
            $this->smarty_vars['statistiken']['nicht_bestaetigt'] = \ttact\Models\AuftragModel::countByYearWeekKundenStatus($this->db, $requested_year, $requested_week, $requested_kunden, 'nicht_bestaetigt');
            $this->smarty_vars['statistiken']['kann_nicht'] = \ttact\Models\AuftragModel::countByYearWeekKundenStatus($this->db, $requested_year, $requested_week, $requested_kunden, 'kann_nicht');
            $this->smarty_vars['statistiken']['kann_andere_uhrzeit'] = \ttact\Models\AuftragModel::countByYearWeekKundenStatus($this->db, $requested_year, $requested_week, $requested_kunden, 'kann_andere_uhrzeit');
            $this->smarty_vars['statistiken']['stundenzettel_bestaetigt'] = \ttact\Models\AuftragModel::countByYearWeekKundenStatus($this->db, $requested_year, $requested_week, $requested_kunden, 'stundenzettel_bestaetigt');
            $this->smarty_vars['statistiken']['archiviert'] = \ttact\Models\AuftragModel::countByYearWeekKundenStatus($this->db, $requested_year, $requested_week, $requested_kunden, 'archiviert');
            $this->smarty_vars['statistiken']['prozent'] = ($this->smarty_vars['statistiken']['insgesamt'] > 0) ? (round((($this->smarty_vars['statistiken']['insgesamt'] - $this->smarty_vars['statistiken']['offen']) / $this->smarty_vars['statistiken']['insgesamt']) * 100)) : 0;

            // --------------------------------------------------------------------
            # Set vars for Schichtplaner
            // --------------------------------------------------------------------

            // $smarty_vars.data
            $this->smarty_vars['data'] = $data;

            // $smarty_vars.monate
            $this->smarty_vars['monat'] = [
                '01' => 'Januar',
                '02' => 'Februar',
                '03' => 'März',
                '04' => 'April',
                '05' => 'Mai',
                '06' => 'Juni',
                '07' => 'Juli',
                '08' => 'August',
                '09' => 'September',
                '10' => 'Oktober',
                '11' => 'November',
                '12' => 'Dezember'
            ];

            // $smarty_vars.wochentage
            $this->smarty_vars['wochentage'] = $wochentage;

            // --------------------------------------------------------------------
            # Set vars for Javascript
            // --------------------------------------------------------------------

            // $smarty_vars.kunden_json
            $this->smarty_vars['kunden_json'] = json_encode($requested_kunden);

            // --------------------------------------------------------------------
            # Set vars for Smarty
            // --------------------------------------------------------------------

            // set template
            $this->template = 'main';
        }
    }

    public function dispotabelle()
    {
        if (isset($this->params[0]) && isset($this->params[1])) {
            $jahr = (int) $this->user_input->getOnlyNumbers($this->params[0]);
            $kw = (int) $this->user_input->getOnlyNumbers($this->params[1]);

            if ($jahr > 1000 && $jahr <= 9999) {
                if ($kw > 0 && $kw <= 53) {
                    $now = new \DateTime("now");
                    $this->misc_utils->sendCSVHeader("dispotabelle-KW".$kw."-stand-".$now->format("Ymd")."-".$now->format("Hi").".csv");
                    $data = [];
                    $data[] = [
                        'Mitarbeiter',
                        '',
                        '',
                        '',
                        '',
                        'Wochenstunden',
                        '',
                        '',
                        'Mo',
                        '',
                        '',
                        '',
                        'Di',
                        '',
                        '',
                        '',
                        'Mi',
                        '',
                        '',
                        '',
                        'Do',
                        '',
                        '',
                        '',
                        'Fr',
                        '',
                        '',
                        '',
                        'Sa',
                        '',
                        '',
                        '',
                        'So',
                        '',
                        '',
                        '',
                        'Abteilungsfreigaben'
                    ];
                    $data[] = [
                        'Personalnr',
                        'Familienname',
                        'Vorname',
                        'Telefon',
                        'Austritt',
                        'SOLL',
                        'IST',
                        'Diff',
                        'Kd',
                        'Zeit von',
                        'Zeit bis',
                        'Stunden',
                        'Kd',
                        'Zeit von',
                        'Zeit bis',
                        'Stunden',
                        'Kd',
                        'Zeit von',
                        'Zeit bis',
                        'Stunden',
                        'Kd',
                        'Zeit von',
                        'Zeit bis',
                        'Stunden',
                        'Kd',
                        'Zeit von',
                        'Zeit bis',
                        'Stunden',
                        'Kd',
                        'Zeit von',
                        'Zeit bis',
                        'Stunden',
                        'Kd',
                        'Zeit von',
                        'Zeit bis',
                        'Stunden',
                        ''
                    ];
                    $mitarbeitermodelle = \ttact\Models\MitarbeiterModel::findActives($this->db);
                    foreach ($mitarbeitermodelle as $mitarbeiter) {
                        $austritt = '';
                        if ($mitarbeiter->getAustritt() instanceof \DateTime) {
                            $austritt = $mitarbeiter->getAustritt()->format("Y-m-d");
                        }

                        $wochentage = [];
                        for ($i = 1; $i <= 7; $i++) {
                            $wochentage[$i] = [
                                'kunde' => 'X',
                                'von' => 'X',
                                'bis' => 'X',
                                'stunden' => 'X'
                            ];
                        }

                        $kalendereintraege = \ttact\Models\KalendereintragModel::findByYearWeekMitarbeiter($this->db, $jahr, $kw, $mitarbeiter->getID());
                        $kalendereintragstypen = [
                            'krank_bezahlt' => 'Krank (wird bezahlt)',
                            'urlaub_bezahlt' => 'Urlaub (wird bezahlt)',
                            'frei' => 'Frei',
                            'krank_unbezahlt' => 'Krank (unbezahlt)',
                            'urlaub_genehmigt' => 'Urlaub genehmigt (unbezahlt)'
                        ];
                        foreach ($kalendereintraege as $kalendereintrag) {
                            if (array_key_exists($kalendereintrag->getType(), $kalendereintragstypen)) {
                                $kalendereintrag_bis = $kalendereintrag->getBis();
                                $kalendereintrag_bis->add(new \DateInterval("P0000-00-01T00:00:00"));
                                $kalendereintrag_bis->setTime(0, 0, 0);
                                $period_kalendereintrag = new Period($kalendereintrag->getVon(), $kalendereintrag_bis);


                                for ($i = 1; $i <= 7; $i++) {
                                    $wochentag_datetime = new \DateTime("now");
                                    $wochentag_datetime->setISODate($jahr, $kw, $i);
                                    $wochentag_datetime->setTime(0, 0, 0);

                                    if ($period_kalendereintrag->contains($wochentag_datetime)) {
                                        $wochentage[$i] = [
                                            'kunde' => $kalendereintragstypen[$kalendereintrag->getType()],
                                            'von' => $kalendereintrag->getVon()->format("d.m.Y"),
                                            'bis' => $kalendereintrag->getBis()->format("d.m.Y"),
                                            'stunden' => '------'
                                        ];
                                    }
                                }
                            }
                        }

                        $schichten = \ttact\Models\AuftragModel::findByYearWeekMitarbeiter($this->db, $this->current_user, $jahr, $kw, [$mitarbeiter->getID()]);
                        $ist_sekunden = 0;
                        foreach ($schichten as $schicht) {
                            $sekunden = $schicht->getBis()->getTimestamp() - $schicht->getVon()->getTimestamp();
                            $ist_sekunden += $sekunden;

                            $wochentage[$schicht->getVon()->format("N")] = [
                                'kunde' => $schicht->getKunde()->getKundennummer(),
                                'von' => $schicht->getVon()->format("H:i"),
                                'bis' => $schicht->getBis()->format("H:i"),
                                'stunden' => gmdate("H:i", $sekunden)
                            ];
                        }

                        $abteilungsfreigaben = '';
                        $abteilungsfreigabemodelle = \ttact\Models\AbteilungsfreigabeModel::findByMitarbeiter($this->db, $mitarbeiter->getID());
                        foreach ($abteilungsfreigabemodelle as $abteilungsfreigabemodell) {
                            $abteilungsfreigaben .= $abteilungsfreigabemodell->getAbteilung()->getBezeichnung() . ", ";
                        }

                        if ($ist_sekunden > 0) {
                            $ist_stunden = floor($ist_sekunden / 3600);
                        } else {
                            $ist_stunden = ceil($ist_sekunden / 3600);
                        }

                        $ist_minuten = gmdate("i", ($ist_sekunden - ($ist_stunden * 3600)));

                        $soll_sekunden = 0;
                        $montag = new \DateTime("now");
                        $montag->setISODate($jahr, $kw, 1);
                        $montag->setTime(0, 0, 0);
                        $lohnkonfiguration = \ttact\Models\LohnkonfigurationModel::findForMitarbeiterDate($this->db, $mitarbeiter->getID(), $montag);
                        if ($lohnkonfiguration instanceof \ttact\Models\LohnkonfigurationModel) {
                            $wochenstunden = (float) $lohnkonfiguration->getWochenstunden();
                            $soll_sekunden = $wochenstunden * 3600;
                        }

                        if ($soll_sekunden > 0) {
                            $soll_stunden = floor($soll_sekunden / 3600);
                        } else {
                            $soll_stunden = ceil($soll_sekunden / 3600);
                        }

                        $soll_minuten = gmdate("i", ($soll_sekunden - ($soll_stunden * 3600)));

                        $diff_sekunden = $soll_sekunden - $ist_sekunden;
                        if ($diff_sekunden > 0) {
                            $diff_stunden = floor($diff_sekunden / 3600);
                        } else {
                            $diff_stunden = ceil($diff_sekunden / 3600);
                        }

                        $diff_minuten = gmdate("i", ($diff_sekunden - ($diff_stunden * 3600)));

                        $data[] = [
                            $mitarbeiter->getPersonalnummer(),
                            $mitarbeiter->getNachname(),
                            $mitarbeiter->getVorname(),
                            $mitarbeiter->getTelefon1(),
                            $austritt,
                            $soll_stunden . ":" . $soll_minuten,
                            $ist_stunden . ":" . $ist_minuten,
                            $diff_stunden . ":" . $diff_minuten,
                            $wochentage[1]['kunde'],
                            $wochentage[1]['von'],
                            $wochentage[1]['bis'],
                            $wochentage[1]['stunden'],
                            $wochentage[2]['kunde'],
                            $wochentage[2]['von'],
                            $wochentage[2]['bis'],
                            $wochentage[2]['stunden'],
                            $wochentage[3]['kunde'],
                            $wochentage[3]['von'],
                            $wochentage[3]['bis'],
                            $wochentage[3]['stunden'],
                            $wochentage[4]['kunde'],
                            $wochentage[4]['von'],
                            $wochentage[4]['bis'],
                            $wochentage[4]['stunden'],
                            $wochentage[5]['kunde'],
                            $wochentage[5]['von'],
                            $wochentage[5]['bis'],
                            $wochentage[5]['stunden'],
                            $wochentage[6]['kunde'],
                            $wochentage[6]['von'],
                            $wochentage[6]['bis'],
                            $wochentage[6]['stunden'],
                            $wochentage[7]['kunde'],
                            $wochentage[7]['von'],
                            $wochentage[7]['bis'],
                            $wochentage[7]['stunden'],
                            rtrim($abteilungsfreigaben, ', ')
                        ];
                    }

                    foreach ($data as $row) {
                        foreach ($row as $col) {
                            echo '"=""' . mb_convert_encoding($col, 'UTF-16LE', 'UTF-8') . '""";';
                        }
                        echo PHP_EOL;
                    }
                    echo chr(255) . chr(254);
                }
            }
        }

        $this->template = 'blank';
    }
}
