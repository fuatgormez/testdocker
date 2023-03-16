<?php

namespace ttact\Controllers;

use League\Period\Period;

class MitarbeiterController extends Controller
{
    public function notizen()
    {
        $error = "";

        // array with all input data
        $i = [
            'monat' => '',
            'jahr'  => ''
        ];
        foreach ($i as $key => $value) {
            $i[$key] = $this->user_input->getPostParameter($key);
        }
        $i['monat'] = (int) $i['monat'];
        $i['jahr'] = (int) $i['jahr'];

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

        if ($i['monat'] != 0 || $i['jahr'] != 0) {
            if ($i['monat'] >= 1 && $i['monat'] <= 12 && $i['jahr'] >= 1000 && $i['jahr'] <= 9999) {
                $this->misc_utils->redirect('mitarbeiter', 'notizen', $i['jahr'], $i['monat']);
            } else {
                $error = 'Bitte füllen Sie alle Felder korrekt aus.';
            }
        } else {
            $now = new \DateTime("now");
            $i['jahr'] = $now->format("Y");
            $i['monat'] = $now->format("m");
        }

        if (isset($this->params[0]) && isset($this->params[1])) {
            $jahr = (int) $this->params[0];
            $monat = (int) $this->params[1];
            if ($monat >= 1 && $monat <= 12 && $jahr >= 1000 && $jahr <= 9999) {
                $i['jahr'] = $jahr;
                $i['monat'] = $monat;

                $monatsanfang = new \DateTime('now');
                $monatsanfang->setDate($jahr, $monat, 1);
                $monatsanfang->setTime(0, 0, 0);

                $notizenliste = [];

                $funktionsname = 'getNotizen' . str_replace('ä', 'ae', $monatsnamen[(int) $monatsanfang->format("m")]);

                $alle_mitarbeiter = \ttact\Models\MitarbeiterModel::findAllLohnberechnung($this->db, $monatsanfang);
                foreach ($alle_mitarbeiter as $mitarbeiter) {
                    if ($mitarbeiter->$funktionsname() != '') {
                        $notizenliste[] = [
                            'personalnummer' => $mitarbeiter->getPersonalnummer(),
                            'vorname' => $mitarbeiter->getVorname(),
                            'nachname' => $mitarbeiter->getNachname(),
                            'notiz' => $mitarbeiter->$funktionsname()
                        ];
                    }
                }

                $this->smarty_vars['notizenliste'] = $notizenliste;
            } else {
                $this->misc_utils->redirect('mitarbeiter', 'notizen');
            }
        }

        // display error message
        if ($error != "") {
            $this->smarty_vars['error'] = $error;
        } else {
            $this->smarty_vars['values'] = $i;
        }

        $this->template = 'main';
    }

    public function zusammensetzen()
    {
        $error = "";
        $success = "";

        // array with all input data
        $i = [
            'von' => '',
            'zu'  => ''
        ];
        foreach ($i as $key => $value) {
            $i[$key] = $this->user_input->getPostParameter($key);
        }
        $i['von'] = (int) $this->user_input->getOnlyNumbers($i['von']);
        $i['zu'] = (int) $this->user_input->getOnlyNumbers($i['zu']);

        if ($i['von'] > 0 || $i['zu'] > 0) {
            if ($i['von'] > 0 && $i['zu'] > 0) {
                $von = \ttact\Models\MitarbeiterModel::findByID($this->db, $i['von']);
                $zu = \ttact\Models\MitarbeiterModel::findByID($this->db, $i['zu']);

                if ($von instanceof \ttact\Models\MitarbeiterModel && $zu instanceof \ttact\Models\MitarbeiterModel) {
                    foreach (\ttact\Models\AuftragModel::findByMitarbeiter($this->db, $this->current_user, $von->getID()) as $schicht) {
                        $schicht->setMitarbeiterID($zu->getID());
                    }
                    foreach (\ttact\Models\AuftragLogModel::findByDeleteMitarbeiter($this->db, $this->current_user, $von->getID()) as $log) {
                        $log->setDeleteMitarbeiterID($zu->getID());
                    }
                    foreach (\ttact\Models\KalendereintragModel::findByMitarbeiter($this->db, $von->getID()) as $kalendereintrag) {
                        $kalendereintrag->setMitarbeiterID($zu->getID());
                    }
                    foreach (\ttact\Models\AbteilungsfreigabeModel::findByMitarbeiter($this->db, $von->getID()) as $abteilungsfreigabe) {
                        $abteilungsfreigabe->delete();
                    }
                    if ($this->company != 'tps') {
                        foreach (\ttact\Models\ArbeitszeitkontoModel::findByMitarbeiter($this->db, $von->getID()) as $arbeitszeitkonto) {
                            $arbeitszeitkonto->delete();
                        }
                    }
                    foreach (\ttact\Models\LohnbuchungModel::findByMitarbeiter($this->db, $von->getID()) as $lohnbuchung) {
                        $lohnbuchung->delete();
                    }
                    foreach (\ttact\Models\LohnkonfigurationModel::findByMitarbeiter($this->db, $von->getID()) as $lohnkonfiguration) {
                        $lohnkonfiguration->delete();
                    }
                    foreach (\ttact\Models\MitarbeiterfilterModel::findByMitarbeiter($this->db, $von->getID()) as $mitarbeiterfilter) {
                        $mitarbeiterfilter->delete();
                    }
                    foreach (\ttact\Models\TagessollModel::findByMitarbeiter($this->db, $von->getID()) as $tagessoll) {
                        $tagessoll->delete();
                    }
                    foreach (\ttact\Models\StundenimporteintragModel::findByMitarbeiter($this->db, $von->getID()) as $stundenimporteintrag) {
                        $stundenimporteintrag->setMitarbeiterID($zu->getID());
                    }
                    if ($von->delete()) {
                        $success = "Die Zusammenlegung war erfolgreich.";
                    } else {
                        $error = "Die Zusammenlegung ist fehlgeschlagen, der Von-Mitarbeiter konnte nicht gelöscht werden.";
                    }
                } else {
                    $error = 'Bitte füllen Sie beide Felder aus.';
                }
            } else {
                $error = 'Bitte füllen Sie beide Felder aus.';
            }
        }

        // mitarbeiterliste
        $mitarbeiterliste = [];
        $alle_mitarbeiter = \ttact\Models\MitarbeiterModel::findAll($this->db);
        foreach ($alle_mitarbeiter as $mitarbeiter) {
            $mitarbeiterliste[] = [
                'id' => $mitarbeiter->getID(),
                'personalnummer' => $mitarbeiter->getPersonalnummer(),
                'vorname' => $mitarbeiter->getVorname(),
                'nachname' => $mitarbeiter->getNachname()
            ];
        }
        $this->smarty_vars['mitarbeiterliste'] = $mitarbeiterliste;

        // display error message
        if ($error != "") {
            $this->smarty_vars['error'] = $error;
            $this->smarty_vars['values'] = $i;
        } elseif ($success != "") {
            $this->smarty_vars['success'] = $success;
        }

        $this->template = 'main';
    }

    public function kalenderuebersicht()
    {
        $kalenderuebersicht = [];

        $now = new \DateTime("now");
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
        $jahr = (int) $now->format("Y");
        $monat = (int) $now->format("m");

        if (isset($this->params[0]) && isset($this->params[1])) {
            $req_year = (int) $this->params[0];
            $req_month = (int) $this->params[1];

            if ($req_year >= 1000 && $req_year <= 9999 && $req_month >= 1 && $req_month <= 12) {
                $jahr = $req_year;
                $monat = $req_month;
            }
        }

        $this_month_datetime = new \DateTime();
        $this_month_datetime->setDate($jahr, $monat, 1);
        $this_month_datetime->setTime(0, 0, 0);

        $monatsanfang = $this_month_datetime;
        $monatsende = clone $monatsanfang;
        $monatsende->setDate((int) $monatsende->format("Y"), (int) $monatsende->format("m"), (int) $monatsende->format("t"));
        $monatsende->setTime(23, 59, 59);
        $monatsende_exclusive = clone $monatsanfang;
        $monatsende_exclusive->add(new \DateInterval("P0000-01-00T00:00:00"));

        $period_monat = new Period($monatsanfang, $monatsende_exclusive);

        $kalendertage = [];
        $kalendertageliste = [];
        $temp_datetime = clone $monatsanfang;
        while ($temp_datetime->format("m") == $monatsanfang->format("m")) {
            $kalendertage[(int) $temp_datetime->format("d")] = '';
            $kalendertageliste[(int) $temp_datetime->format("d")] = $temp_datetime->format("d");

            $temp_datetime->add(new \DateInterval("P0000-00-01T00:00:00"));
        }

        $abkuerzungen = [
            'krank_bezahlt'         => 'Kb',
            'urlaub_bezahlt'        => 'Ub',
            'kind_krank'            => 'Kk',
            'frei'                  => 'F',
            'krank_unbezahlt'       => 'Ku',
            'unentschuldigt_fehlen' => 'uF',
            'feiertag_bezahlt'      => 'FT',
            'fehlzeit'              => 'FZ',
            'urlaub_genehmigt'      => 'Ug'
        ];

        $mitarbeiternamen = [];
        $alle_mitarbeiter = \ttact\Models\MitarbeiterModel::findActives($this->db, $monatsanfang);
        foreach ($alle_mitarbeiter as $mitarbeiter) {
            $mitarbeiternamen[$mitarbeiter->getPersonalnummer()] = $mitarbeiter->getNachname() . ', ' . $mitarbeiter->getVorname();
            $kalenderuebersicht[$mitarbeiter->getPersonalnummer()] = $kalendertage;

            $kalendereintraege = \ttact\Models\KalendereintragModel::findByStartEndMitarbeiter($this->db, $monatsanfang, $monatsende, $mitarbeiter->getID());
            foreach ($kalendereintraege as $kalendereintrag) {
                if (key_exists($kalendereintrag->getType(), $abkuerzungen)) {
                    $kalendereintrag_bis = $kalendereintrag->getBis();
                    $kalendereintrag_bis->add(new \DateInterval("P0000-00-01T00:00:00"));
                    $kalendereintrag_bis->setTime(0, 0, 0);
                    $period_kalendereintrag = new Period($kalendereintrag->getVon(), $kalendereintrag_bis);

                    if ($period_monat->overlaps($period_kalendereintrag)) {
                        $period_intersection = $period_monat->intersect($period_kalendereintrag);
                        foreach ($period_intersection->getDatePeriod(new \DateInterval("P0000-00-01T00:00:00")) as $day) {
                            $kalenderuebersicht[$mitarbeiter->getPersonalnummer()][(int) $day->format("d")] = $abkuerzungen[$kalendereintrag->getType()];
                        }
                    }
                }
            }
        }

        $next_month_datetime = clone $this_month_datetime;
        $next_month_datetime->add(new \DateInterval("P0000-01-00T00:00:00"));

        $prev_month_datetime = clone $this_month_datetime;
        $prev_month_datetime->sub(new \DateInterval("P0000-01-00T00:00:00"));

        $this->smarty_vars['values'] = [
            'year' => (int) $this_month_datetime->format("Y"),
            'next_year' => (int) $next_month_datetime->format("Y"),
            'prev_year' => (int) $prev_month_datetime->format("Y"),
            'month' => (int) $this_month_datetime->format("m"),
            'next_month' => (int) $next_month_datetime->format("m"),
            'prev_month' => (int) $prev_month_datetime->format("m"),
            'monatsname' => $monatsnamen[$monat]
        ];

        $this->smarty_vars['kalenderuebersicht'] = $kalenderuebersicht;
        $this->smarty_vars['kalendertageliste'] = $kalendertageliste;
        $this->smarty_vars['mitarbeiternamen'] = $mitarbeiternamen;

        $this->template = 'main';
    }

    public function aktiv()
    {
        if (isset($this->params[0])) {
            $this->smarty_vars['error'] = "Der Mitarbeiter konnte nicht gefunden werden.";
        }

        $mitarbeiter = \ttact\Models\MitarbeiterModel::findActives($this->db);
        $mitarbeiterliste = [];
        foreach ($mitarbeiter as $m) {
            $geburtsdatum = '';
            if ($m->getGeburtsdatum() instanceof \DateTime) {
                $geburtsdatum = $m->getGeburtsdatum()->format("d.m.Y");
            }

            $mitarbeiterliste[$m->getPersonalnummer()] = [
                'personalnummer'    => $m->getPersonalnummer(),
                'vorname'           => $m->getVorname(),
                'nachname'          => $m->getNachname(),
                'telefon1'          => $m->getTelefon1(),
                'telefon2'          => $m->getTelefon2(),
                'emailadresse'      => $m->getEmailadresse(),
                'strasse'           => $m->getStrasse(),
                'hausnummer'        => $m->getHausnummer(),
                'adresszusatz'      => $m->getAdresszusatz(),
                'postleitzahl'      => $m->getPostleitzahl(),
                'ort'               => $m->getOrt(),
                'geburtsdatum'      => $geburtsdatum
            ];
        }
        ksort($mitarbeiterliste);
        $this->smarty_vars['mitarbeiterliste'] = $mitarbeiterliste;

        // template settings
        $this->template = 'main';
    }

    public function inaktiv()
    {
        if (isset($this->params[0])) {
            $this->smarty_vars['error'] = "Der Mitarbeiter konnte nicht gefunden werden.";
        }

        $mitarbeiter = \ttact\Models\MitarbeiterModel::findInactives($this->db);
        $mitarbeiterliste = [];
        foreach ($mitarbeiter as $m) {
            $geburtsdatum = '';
            if ($m->getGeburtsdatum() instanceof \DateTime) {
                $geburtsdatum = $m->getGeburtsdatum()->format("d.m.Y");
            }

            $mitarbeiterliste[$m->getPersonalnummer()] = [
                'personalnummer'    => $m->getPersonalnummer(),
                'vorname'           => $m->getVorname(),
                'nachname'          => $m->getNachname(),
                'telefon1'          => $m->getTelefon1(),
                'telefon2'          => $m->getTelefon2(),
                'emailadresse'      => $m->getEmailadresse(),
                'strasse'           => $m->getStrasse(),
                'hausnummer'        => $m->getHausnummer(),
                'adresszusatz'      => $m->getAdresszusatz(),
                'postleitzahl'      => $m->getPostleitzahl(),
                'ort'               => $m->getOrt(),
                'geburtsdatum'      => $geburtsdatum
            ];
        }
        ksort($mitarbeiterliste);
        $this->smarty_vars['mitarbeiterliste'] = $mitarbeiterliste;

        // template settings
        $this->template = 'main';
    }

    public function index()
    {
        if (isset($this->params[0])) {
            $this->smarty_vars['error'] = "Der Mitarbeiter konnte nicht gefunden werden.";
        }

        $mitarbeiter = \ttact\Models\MitarbeiterModel::findAll($this->db);
        $mitarbeiterliste = [];
        foreach ($mitarbeiter as $m) {
            $geburtsdatum = '';
            if ($m->getGeburtsdatum() instanceof \DateTime) {
                $geburtsdatum = $m->getGeburtsdatum()->format("d.m.Y");
            }

            $mitarbeiterliste[$m->getPersonalnummer()] = [
                'personalnummer'    => $m->getPersonalnummer(),
                'vorname'           => $m->getVorname(),
                'nachname'          => $m->getNachname(),
                'telefon1'          => $m->getTelefon1(),
                'telefon2'          => $m->getTelefon2(),
                'emailadresse'      => $m->getEmailadresse(),
                'strasse'           => $m->getStrasse(),
                'hausnummer'        => $m->getHausnummer(),
                'adresszusatz'      => $m->getAdresszusatz(),
                'postleitzahl'      => $m->getPostleitzahl(),
                'ort'               => $m->getOrt(),
                'geburtsdatum'      => $geburtsdatum
            ];
        }
        ksort($mitarbeiterliste);
        $this->smarty_vars['mitarbeiterliste'] = $mitarbeiterliste;

        // template settings
        $this->template = 'main';
    }

    public function erstellen()
    {
        $error = "";
        $success = "";
        if (isset($this->params[0])) {
            if ($this->params[0] == "erfolgreich") {
                $success = "Der Mitarbeiter wurde erfolgreich angelegt.";
            }
        }

        // array with all input data
        $i = [
            'personalnummer'    => '',
            'geschlecht'        => '',
            'vorname'           => '',
            'nachname'          => '',
            'telefon1'          => '',
            'telefon2'          => '',
            'emailadresse'      => ''
        ];
        foreach ($i as $key => $value) {
            $i[$key] = $this->user_input->getPostParameter($key);
        }
        $i['telefon1'] = $this->user_input->getOnlyNumbers($i['telefon1']);
        $i['telefon2'] = $this->user_input->getOnlyNumbers($i['telefon2']);

        // check and save
        if (($i['personalnummer'] != "") && ($i['geschlecht'] != "") && ($i['vorname'] != "") && ($i['nachname'] != "")) {
            if ($this->user_input->isPositiveInteger($i['personalnummer'])) {
                if (($i['geschlecht'] != "männlich") && ($i['geschlecht'] != "weiblich")) {
                    // data of select field 'geschlecht' is not valid
                    $error = "Es ist ein technischer Fehler aufgetreten.";
                } elseif (($i['emailadresse'] != "") && !$this->user_input->isEmailadresse($i['emailadresse'])) {
                    // E-Mail-Adresse is not valid
                    $error = "Die E-Mail-Adresse ist ungültig.";
                } else {
                    // check if Mitarbeiter with that Personalnummer already exists
                    $test = \ttact\Models\MitarbeiterModel::findByPersonalnummer($this->db, $this->user_input->getOnlyNumbers($i['personalnummer']));
                    if ($test instanceof \ttact\Models\MitarbeiterModel) {
                        $error = "Die Personalnummer ist bereits vergeben.";
                    } else {
                        // save the data and check if it worked
                        $new = \ttact\Models\MitarbeiterModel::createNew($this->db, $i);
                        if ($new instanceof \ttact\Models\MitarbeiterModel) {
                            $this->misc_utils->redirect('mitarbeiter', 'erstellen', 'erfolgreich');
                        } else {
                            $error = "Beim Anlegen des Mitarbeiters ist ein Fehler aufgetreten.";
                        }

                    }
                }
            } else {
                $error = "Die Personalnummer ist ungültig.";
            }
        } else {
            foreach ($i as $value) {
                if ($value != "") {
                    $error = "Die mit einem (*) gekennzeichneten Felder dürfen nicht leer sein.";
                }
            }
        }

        // display error message
        if ($error != "") {
            $this->smarty_vars['error'] = $error;
            $this->smarty_vars['values'] = $i;
        } else {
            // display error message
            if ($success != "") {
                $this->smarty_vars['success'] = $success;
            }

            // fill in the next free Kundennummer-value if the form was not yet submitted
            $default_personalnummer = '';
            $last_mitarbeiter = \ttact\Models\MitarbeiterModel::findLastByPersonalnummer($this->db);
            if ($last_mitarbeiter instanceof \ttact\Models\MitarbeiterModel) {
                $default_personalnummer = $last_mitarbeiter->getPersonalnummer() + 1;
            }
            $this->smarty_vars['values']['personalnummer'] = $default_personalnummer;
        }

        // template settings
        $this->template = 'main';
    }

    public function bearbeiten()
    {
        $redirect = true;

        if (isset($this->params[0])) {
            if ($this->user_input->isPositiveInteger($this->params[0])) {
                $mitarbeiter = \ttact\Models\MitarbeiterModel::findByPersonalnummer($this->db, $this->user_input->getOnlyNumbers($this->params[0]));
                if ($mitarbeiter instanceof \ttact\Models\MitarbeiterModel) {
                    $redirect = false;

                    $error = "";
                    $success = "";
                    if (isset($this->params[2]) && isset($this->params[3])) {
                        if ($this->params[2] == 'erfolgreich' && $this->params[3] == 'erstellt') {
                            $success = 'Die Lohnkonfiguration wurde erfolgreich angelegt.';
                        } elseif ($this->params[2] == 'erfolgreich' && $this->params[3] == 'bearbeitet') {
                            $success = 'Die Lohnkonfiguration wurde erfolgreich bearbeitet.';
                        }
                    }
                    $tab = 'persoenliches';
                    if (isset($this->params[1])) {
                        switch ($this->params[1]) {
                            case "persoenliches":
                                $tab = $this->params[1];
                                break;
                            case "notizen":
                                if ($this->current_user->getUsergroup()->hasRight('notizen')) {
                                    $tab = $this->params[1];
                                } else {
                                    $tab = 'persoenliches';
                                }
                                break;
                            case "vertragliches":
                                if ($this->current_user->getUsergroup()->hasRight('vertragliches')) {
                                    $tab = $this->params[1];
                                } else {
                                    $tab = 'persoenliches';
                                }
                                break;
                            case "praeferenzen":
                                $tab = $this->params[1];
                                break;
                            case "lohnbuchungen":
                                if ($this->current_user->getUsergroup()->hasRight('lohnbuchungen')) {
                                    $tab = $this->params[1];
                                } else {
                                    $tab = 'persoenliches';
                                }
                                break;
                            default:
                                $tab = 'persoenliches';
                                break;
                        }
                    }

                    // array with all input data
                    $i = [
                        'personalnummer'    => '',
                        'geschlecht'        => '',
                        'vorname'           => '',
                        'nachname'          => '',
                        'telefon1'          => '',
                        'telefon2'          => '',
                        'emailadresse'      => '',
                        'austritt'          => '',
                        'befristung'        => '',
                        'befristung1'       => '',
                        'befristung2'       => '',
                        'befristung3'       => '',
                        'montag_von'        => '',
                        'montag_bis'        => '',
                        'dienstag_von'      => '',
                        'dienstag_bis'      => '',
                        'mittwoch_von'      => '',
                        'mittwoch_bis'      => '',
                        'donnerstag_von'    => '',
                        'donnerstag_bis'    => '',
                        'freitag_von'       => '',
                        'freitag_bis'       => '',
                        'samstag_von'       => '',
                        'samstag_bis'       => '',
                        'sonntag_von'       => '',
                        'sonntag_bis'       => '',
                        'notizen_allgemein' => '',
                        'notizen_januar'    => '',
                        'notizen_februar'   => '',
                        'notizen_maerz'     => '',
                        'notizen_april'     => '',
                        'notizen_mai'       => '',
                        'notizen_juni'      => '',
                        'notizen_juli'      => '',
                        'notizen_august'    => '',
                        'notizen_september' => '',
                        'notizen_oktober'   => '',
                        'notizen_november'  => '',
                        'notizen_dezember'  => '',
                        'notizen_submitted' => '',
                        'vertragliches_submitted' => '',
                        'praeferenzen_submitted' => ''
                    ];
                    foreach ($i as $key => $value) {
                        $i[$key] = $this->user_input->getPostParameter($key);
                    }
                    $i['telefon1'] = $this->user_input->getOnlyNumbers($i['telefon1']);
                    $i['telefon2'] = $this->user_input->getOnlyNumbers($i['telefon2']);
                    $i['montag_von'] = $this->user_input->getOnlyNumbers($i['montag_von']);
                    $i['montag_bis'] = $this->user_input->getOnlyNumbers($i['montag_bis']);
                    $i['dienstag_von'] = $this->user_input->getOnlyNumbers($i['dienstag_von']);
                    $i['dienstag_bis'] = $this->user_input->getOnlyNumbers($i['dienstag_bis']);
                    $i['mittwoch_von'] = $this->user_input->getOnlyNumbers($i['mittwoch_von']);
                    $i['mittwoch_bis'] = $this->user_input->getOnlyNumbers($i['mittwoch_bis']);
                    $i['donnerstag_von'] = $this->user_input->getOnlyNumbers($i['donnerstag_von']);
                    $i['donnerstag_bis'] = $this->user_input->getOnlyNumbers($i['donnerstag_bis']);
                    $i['freitag_von'] = $this->user_input->getOnlyNumbers($i['freitag_von']);
                    $i['freitag_bis'] = $this->user_input->getOnlyNumbers($i['freitag_bis']);
                    $i['samstag_von'] = $this->user_input->getOnlyNumbers($i['samstag_von']);
                    $i['samstag_bis'] = $this->user_input->getOnlyNumbers($i['samstag_bis']);
                    $i['sonntag_von'] = $this->user_input->getOnlyNumbers($i['sonntag_von']);
                    $i['sonntag_bis'] = $this->user_input->getOnlyNumbers($i['sonntag_bis']);
                    $i['notizen_allgemein'] = trim($i['notizen_allgemein']);
                    $i['notizen_januar'] = trim($i['notizen_januar']);
                    $i['notizen_februar'] = trim($i['notizen_februar']);
                    $i['notizen_maerz'] = trim($i['notizen_maerz']);
                    $i['notizen_april'] = trim($i['notizen_april']);
                    $i['notizen_mai'] = trim($i['notizen_mai']);
                    $i['notizen_juni'] = trim($i['notizen_juni']);
                    $i['notizen_juli'] = trim($i['notizen_juli']);
                    $i['notizen_august'] = trim($i['notizen_august']);
                    $i['notizen_september'] = trim($i['notizen_september']);
                    $i['notizen_oktober'] = trim($i['notizen_oktober']);
                    $i['notizen_november'] = trim($i['notizen_november']);
                    $i['notizen_dezember'] = trim($i['notizen_dezember']);
                    $i['stamm'] = $this->user_input->getArrayPostParameter('stamm');
                    $i['springer'] = $this->user_input->getArrayPostParameter('springer');
                    $i['sperre'] = $this->user_input->getArrayPostParameter('sperre');
                    $i['abteilungsfreigaben'] = $this->user_input->getArrayPostParameter('abteilungsfreigaben');

                    // stamm
                    $stamm = [];
                    if (count($i['stamm']) > 0) {
                        foreach ($i['stamm'] as &$kundennummer) {
                            $kundennummer = (int) $this->user_input->getOnlyNumbers($kundennummer);
                            $kunde_model = \ttact\Models\KundeModel::findByKundennummer($this->db, $kundennummer);
                            if ($kunde_model instanceof \ttact\Models\KundeModel) {
                                $stamm[] = $kunde_model->getKundennummer();
                            }
                        }
                    }
                    $i['stamm'] = $stamm;

                    // springer
                    $springer = [];
                    if (count($i['springer']) > 0) {
                        foreach ($i['springer'] as &$kundennummer) {
                            $kundennummer = (int) $this->user_input->getOnlyNumbers($kundennummer);
                            $kunde_model = \ttact\Models\KundeModel::findByKundennummer($this->db, $kundennummer);
                            if ($kunde_model instanceof \ttact\Models\KundeModel) {
                                $springer[] = $kunde_model->getKundennummer();
                            }
                        }
                    }
                    $i['springer'] = $springer;

                    // sperre
                    $sperre = [];
                    if (count($i['sperre']) > 0) {
                        foreach ($i['sperre'] as &$kundennummer) {
                            $kundennummer = (int) $this->user_input->getOnlyNumbers($kundennummer);
                            $kunde_model = \ttact\Models\KundeModel::findByKundennummer($this->db, $kundennummer);
                            if ($kunde_model instanceof \ttact\Models\KundeModel) {
                                $sperre[] = $kunde_model->getKundennummer();
                            }
                        }
                    }
                    $i['sperre'] = $sperre;

                    // Abteilungsfreigaben
                    $abteilungsfreigaben = [];
                    if (count($i['abteilungsfreigaben']) > 0) {
                        foreach ($i['abteilungsfreigaben'] as &$abteilung) {
                            $abteilungs_id = (int) $this->user_input->getOnlyNumbers($abteilung);
                            $abteilung_model = \ttact\Models\AbteilungModel::findByID($this->db, $abteilungs_id);
                            if ($abteilung_model instanceof \ttact\Models\AbteilungModel) {
                                $abteilungsfreigaben[] = $abteilung_model->getID();
                            }
                        }
                    }
                    $i['abteilungsfreigaben'] = $abteilungsfreigaben;

                    $montag_von_error = false;
                    $montag_bis_error = false;
                    $dienstag_von_error = false;
                    $dienstag_bis_error = false;
                    $mittwoch_von_error = false;
                    $mittwoch_bis_error = false;
                    $donnerstag_von_error = false;
                    $donnerstag_bis_error = false;
                    $freitag_von_error = false;
                    $freitag_bis_error = false;
                    $samstag_von_error = false;
                    $samstag_bis_error = false;
                    $sonntag_von_error = false;
                    $sonntag_bis_error = false;

                    // check and save
                    if ($i['personalnummer'] != "") {
                        $tab = 'persoenliches';
                        if ($i['personalnummer'] != $mitarbeiter->getPersonalnummer()) {
                            if ($this->user_input->isPositiveInteger($i['personalnummer'])) {
                                // check if Mitarbeiter with that Personalnummer already exists
                                $test = \ttact\Models\MitarbeiterModel::findByPersonalnummer($this->db, $this->user_input->getOnlyNumbers($i['personalnummer']));
                                if ($test instanceof \ttact\Models\MitarbeiterModel) {
                                    $error .= "Die Personalnummer ist bereits vergeben. ";
                                } else {
                                    if ($mitarbeiter->setPersonalnummer($this->user_input->getOnlyNumbers($i['personalnummer']))) {
                                        $success .= "Die Personalnummer wurde erfolgreich geändert. ";
                                    } else {
                                        $error .= "Beim Speichern der Personalnummer ist ein technischer Fehler aufgetreten. ";
                                    }
                                }
                            } else {
                                $error .= "Die Personalnummer ist ungültig.";
                            }
                        }

                        if ($i['geschlecht'] != "" && $i['geschlecht'] != $mitarbeiter->getGeschlecht()) {
                            if (($i['geschlecht'] != "männlich") && ($i['geschlecht'] != "weiblich")) {
                                // data of select field 'geschlecht' is not valid
                                $error .= "Es ist ein technischer Fehler aufgetreten.";
                            } else {
                                if ($mitarbeiter->setGeschlecht($i['geschlecht'])) {
                                    $success .= "Die Anrede wurde erfolgreich geändert. ";
                                } else {
                                    $error .= "Beim Speichern der Anrede ist ein technischer Fehler aufgetreten. ";
                                }
                            }
                        }

                        if ($i['vorname'] != "" && $i['vorname'] != $mitarbeiter->getVorname()) {
                            if ($mitarbeiter->setVorname($i['vorname'])) {
                                $success .= "Der Vorname wurde erfolgreich geändert. ";
                            } else {
                                $error .= "Beim Speichern des Vornamens ist ein technischer Fehler aufgetreten. ";
                            }
                        }

                        if ($i['nachname'] != "" && $i['nachname'] != $mitarbeiter->getNachname()) {
                            if ($mitarbeiter->setNachname($i['nachname'])) {
                                $success .= "Der Nachname wurde erfolgreich geändert. ";
                            } else {
                                $error .= "Beim Speichern des Nachnamens ist ein technischer Fehler aufgetreten. ";
                            }
                        }

                        if ($i['telefon1'] != $mitarbeiter->getTelefon1()) {
                            if ($mitarbeiter->setTelefon1($i['telefon1'])) {
                                $success .= "Die Telefonnummer 1 wurde erfolgreich geändert. ";
                            } else {
                                $error .= "Beim Speichern der Telefonnummer 1 ist ein technischer Fehler aufgetreten. ";
                            }
                        }

                        if ($i['telefon2'] != $mitarbeiter->getTelefon2()) {
                            if ($mitarbeiter->setTelefon2($i['telefon2'])) {
                                $success .= "Die Telefonnummer 2 wurde erfolgreich geändert. ";
                            } else {
                                $error .= "Beim Speichern der Telefonnummer 2 ist ein technischer Fehler aufgetreten. ";
                            }
                        }

                        if ($i['emailadresse'] != $mitarbeiter->getEmailadresse()) {
                            if ($this->user_input->isEmailadresse($i['emailadresse'])) {
                                if ($mitarbeiter->setEmailadresse($i['emailadresse'])) {
                                    $success .= "Die E-Mail-Adresse wurde erfolgreich geändert. ";
                                } else {
                                    $error .= "Beim Speichern der E-Mail-Adresse ist ein technischer Fehler aufgetreten. ";
                                }
                            } else {
                                // E-Mail-Adresse is not valid
                                $error = "Die E-Mail-Adresse ist ungültig.";
                            }
                        }
                    }

                    if ($this->current_user->getUsergroup()->hasRight('notizen')) {
                        if ($i['notizen_submitted'] == 'true') {
                            $tab = 'notizen';

                            if ($mitarbeiter->getNotizenAllgemein() != $i['notizen_allgemein']) {
                                if ($mitarbeiter->setNotizenAllgemein($i['notizen_allgemein'])) {
                                    $success .= "Die allgemeinen Notizen wurde erfolgreich geändert. ";
                                }
                                else {
                                    $error .= "Beim Speichern der allgemeinen Notizen ist ein technischer Fehler aufgetreten. ";
                                }
                            }

                            if ($mitarbeiter->getNotizenJanuar() != $i['notizen_januar']) {
                                if ($mitarbeiter->setNotizenJanuar($i['notizen_januar'])) {
                                    $success .= "Die Notizen für Januar wurden erfolgreich geändert. ";
                                }
                                else {
                                    $error .= "Beim Speichern der Notizen für Januar ist ein technischer Fehler aufgetreten. ";
                                }
                            }

                            if ($mitarbeiter->getNotizenFebruar() != $i['notizen_februar']) {
                                if ($mitarbeiter->setNotizenFebruar($i['notizen_februar'])) {
                                    $success .= "Die Notizen für Februar wurden erfolgreich geändert. ";
                                }
                                else {
                                    $error .= "Beim Speichern der Notizen für Februar ist ein technischer Fehler aufgetreten. ";
                                }
                            }

                            if ($mitarbeiter->getNotizenMaerz() != $i['notizen_maerz']) {
                                if ($mitarbeiter->setNotizenMaerz($i['notizen_maerz'])) {
                                    $success .= "Die Notizen für März wurden erfolgreich geändert. ";
                                }
                                else {
                                    $error .= "Beim Speichern der Notizen für März ist ein technischer Fehler aufgetreten. ";
                                }
                            }

                            if ($mitarbeiter->getNotizenApril() != $i['notizen_april']) {
                                if ($mitarbeiter->setNotizenApril($i['notizen_april'])) {
                                    $success .= "Die Notizen für April wurden erfolgreich geändert. ";
                                }
                                else {
                                    $error .= "Beim Speichern der Notizen für April ist ein technischer Fehler aufgetreten. ";
                                }
                            }

                            if ($mitarbeiter->getNotizenMai() != $i['notizen_mai']) {
                                if ($mitarbeiter->setNotizenMai($i['notizen_mai'])) {
                                    $success .= "Die Notizen für Mai wurden erfolgreich geändert. ";
                                }
                                else {
                                    $error .= "Beim Speichern der Notizen für Mai ist ein technischer Fehler aufgetreten. ";
                                }
                            }

                            if ($mitarbeiter->getNotizenJuni() != $i['notizen_juni']) {
                                if ($mitarbeiter->setNotizenJuni($i['notizen_juni'])) {
                                    $success .= "Die Notizen für Juni wurden erfolgreich geändert. ";
                                }
                                else {
                                    $error .= "Beim Speichern der Notizen für Juni ist ein technischer Fehler aufgetreten. ";
                                }
                            }

                            if ($mitarbeiter->getNotizenJuli() != $i['notizen_juli']) {
                                if ($mitarbeiter->setNotizenJuli($i['notizen_juli'])) {
                                    $success .= "Die Notizen für Juli wurden erfolgreich geändert. ";
                                }
                                else {
                                    $error .= "Beim Speichern der Notizen für Juli ist ein technischer Fehler aufgetreten. ";
                                }
                            }

                            if ($mitarbeiter->getNotizenAugust() != $i['notizen_august']) {
                                if ($mitarbeiter->setNotizenAugust($i['notizen_august'])) {
                                    $success .= "Die Notizen für August wurden erfolgreich geändert. ";
                                }
                                else {
                                    $error .= "Beim Speichern der Notizen für August ist ein technischer Fehler aufgetreten. ";
                                }
                            }

                            if ($mitarbeiter->getNotizenSeptember() != $i['notizen_september']) {
                                if ($mitarbeiter->setNotizenSeptember($i['notizen_september'])) {
                                    $success .= "Die Notizen für September wurden erfolgreich geändert. ";
                                }
                                else {
                                    $error .= "Beim Speichern der Notizen für September ist ein technischer Fehler aufgetreten. ";
                                }
                            }

                            if ($mitarbeiter->getNotizenOktober() != $i['notizen_oktober']) {
                                if ($mitarbeiter->setNotizenOktober($i['notizen_oktober'])) {
                                    $success .= "Die Notizen für Oktober wurden erfolgreich geändert. ";
                                }
                                else {
                                    $error .= "Beim Speichern der Notizen für Oktober ist ein technischer Fehler aufgetreten. ";
                                }
                            }

                            if ($mitarbeiter->getNotizenNovember() != $i['notizen_november']) {
                                if ($mitarbeiter->setNotizenNovember($i['notizen_november'])) {
                                    $success .= "Die Notizen für November wurden erfolgreich geändert. ";
                                }
                                else {
                                    $error .= "Beim Speichern der Notizen für November ist ein technischer Fehler aufgetreten. ";
                                }
                            }

                            if ($mitarbeiter->getNotizenDezember() != $i['notizen_dezember']) {
                                if ($mitarbeiter->setNotizenDezember($i['notizen_dezember'])) {
                                    $success .= "Die Notizen für Dezember wurden erfolgreich geändert. ";
                                }
                                else {
                                    $error .= "Beim Speichern der Notizen für Dezember ist ein technischer Fehler aufgetreten. ";
                                }
                            }
                        }
                    }

                    if ($this->current_user->getUsergroup()->hasRight('vertragliches')) {
                        if ($i['vertragliches_submitted'] == 'true') {
                            $tab = 'vertragliches';

                            if ($this->current_user->getUsergroup()->hasRight('austritt_befristungen')) {
                                $austritt = '';
                                if ($mitarbeiter->getAustritt() instanceof \DateTime) {
                                    $austritt = $mitarbeiter->getAustritt()->format("d.m.Y");
                                }
                                if ($i['austritt'] != $austritt) {
                                    if ($i['austritt'] == '') {
                                        if ($mitarbeiter->setAustritt('0000-00-00')) {
                                            $success .= "Das Datum für den Austritt wurde erfolgreich geändert. ";
                                        }
                                        else {
                                            $error .= "Beim Speichern des Datums für den Austritt ist ein technischer Fehler aufgetreten. ";
                                        }
                                    }
                                    else {
                                        if (!$this->user_input->isDate($i['austritt'])) {
                                            $error .= "Das Datum für den Austritt ist ungültig. ";
                                        }
                                        else {
                                            $date = \DateTime::createFromFormat('d.m.Y', $i['austritt']);
                                            if ($date instanceof \DateTime) {
                                                if ($date->format("d.m.Y") == $i['austritt']) {
                                                    if ($mitarbeiter->setAustritt($date->format("Y-m-d"))) {
                                                        $success .= "Das Datum für den Austritt wurde erfolgreich geändert. ";
                                                    }
                                                    else {
                                                        $error .= "Beim Speichern des Datums für den Austritt ist ein technischer Fehler aufgetreten. ";
                                                    }
                                                }
                                                else {
                                                    $error .= "Das Datum ist ungültig. ";
                                                }
                                            }
                                            else {
                                                $error .= "Das Datum ist ungültig. ";
                                            }
                                        }
                                    }
                                }

                                $befristung = '';
                                if ($mitarbeiter->getBefristung() instanceof \DateTime) {
                                    $befristung = $mitarbeiter->getBefristung()->format("d.m.Y");
                                }
                                if ($i['befristung'] != $befristung) {
                                    if ($i['befristung'] == '') {
                                        if ($mitarbeiter->setBefristung('0000-00-00')) {
                                            $success .= "Das Datum für die Befristung wurde erfolgreich geändert. ";
                                        }
                                        else {
                                            $error .= "Beim Speichern des Datums für die Befristung ist ein technischer Fehler aufgetreten. ";
                                        }
                                    }
                                    else {
                                        if (!$this->user_input->isDate($i['befristung'])) {
                                            $error .= "Das Datum für die Befristung ist ungültig. ";
                                        }
                                        else {
                                            $date = \DateTime::createFromFormat('d.m.Y', $i['befristung']);
                                            if ($date instanceof \DateTime) {
                                                if ($date->format("d.m.Y") == $i['befristung']) {
                                                    if ($mitarbeiter->setBefristung($date->format("Y-m-d"))) {
                                                        $success .= "Das Datum für die Befristung wurde erfolgreich geändert. ";
                                                    }
                                                    else {
                                                        $error .= "Beim Speichern des Datums für die Befristung ist ein technischer Fehler aufgetreten. ";
                                                    }
                                                }
                                                else {
                                                    $error .= "Das Datum ist ungültig. ";
                                                }
                                            }
                                            else {
                                                $error .= "Das Datum ist ungültig. ";
                                            }
                                        }
                                    }
                                }

                                $befristung1 = '';
                                if ($mitarbeiter->getBefristung1() instanceof \DateTime) {
                                    $befristung1 = $mitarbeiter->getBefristung1()->format("d.m.Y");
                                }
                                if ($i['befristung1'] != $befristung1) {
                                    if ($i['befristung1'] == '') {
                                        if ($mitarbeiter->setBefristung1('0000-00-00')) {
                                            $success .= "Das Datum für die 1. Befristung wurde erfolgreich geändert. ";
                                        }
                                        else {
                                            $error .= "Beim Speichern des Datums für die 1. Befristung ist ein technischer Fehler aufgetreten. ";
                                        }
                                    }
                                    else {
                                        if (!$this->user_input->isDate($i['befristung1'])) {
                                            $error .= "Das Datum für die 1. Befristung ist ungültig. ";
                                        }
                                        else {
                                            $date = \DateTime::createFromFormat('d.m.Y', $i['befristung1']);
                                            if ($date instanceof \DateTime) {
                                                if ($date->format("d.m.Y") == $i['befristung1']) {
                                                    if ($mitarbeiter->setBefristung1($date->format("Y-m-d"))) {
                                                        $success .= "Das Datum für die 1. Befristung wurde erfolgreich geändert. ";
                                                    }
                                                    else {
                                                        $error .= "Beim Speichern des Datums für die 1. Befristung ist ein technischer Fehler aufgetreten. ";
                                                    }
                                                }
                                                else {
                                                    $error .= "Das Datum ist ungültig. ";
                                                }
                                            }
                                            else {
                                                $error .= "Das Datum ist ungültig. ";
                                            }
                                        }
                                    }
                                }

                                $befristung2 = '';
                                if ($mitarbeiter->getBefristung2() instanceof \DateTime) {
                                    $befristung2 = $mitarbeiter->getBefristung2()->format("d.m.Y");
                                }
                                if ($i['befristung2'] != $befristung2) {
                                    if ($i['befristung2'] == '') {
                                        if ($mitarbeiter->setBefristung2('0000-00-00')) {
                                            $success .= "Das Datum für die 2. Befristung wurde erfolgreich geändert. ";
                                        }
                                        else {
                                            $error .= "Beim Speichern des Datums für die 2. Befristung ist ein technischer Fehler aufgetreten. ";
                                        }
                                    }
                                    else {
                                        if (!$this->user_input->isDate($i['befristung2'])) {
                                            $error .= "Das Datum für die 2. Befristung ist ungültig. ";
                                        }
                                        else {
                                            $date = \DateTime::createFromFormat('d.m.Y', $i['befristung2']);
                                            if ($date instanceof \DateTime) {
                                                if ($date->format("d.m.Y") == $i['befristung2']) {
                                                    if ($mitarbeiter->setBefristung2($date->format("Y-m-d"))) {
                                                        $success .= "Das Datum für die 2. Befristung wurde erfolgreich geändert. ";
                                                    }
                                                    else {
                                                        $error .= "Beim Speichern des Datums für die 2. Befristung ist ein technischer Fehler aufgetreten. ";
                                                    }
                                                }
                                                else {
                                                    $error .= "Das Datum ist ungültig. ";
                                                }
                                            }
                                            else {
                                                $error .= "Das Datum ist ungültig. ";
                                            }
                                        }
                                    }
                                }

                                $befristung3 = '';
                                if ($mitarbeiter->getBefristung3() instanceof \DateTime) {
                                    $befristung3 = $mitarbeiter->getBefristung3()->format("d.m.Y");
                                }
                                if ($i['befristung3'] != $befristung3) {
                                    if ($i['befristung3'] == '') {
                                        if ($mitarbeiter->setBefristung3('0000-00-00')) {
                                            $success .= "Das Datum für die 3. Befristung wurde erfolgreich geändert. ";
                                        }
                                        else {
                                            $error .= "Beim Speichern des Datums für die 3. Befristung ist ein technischer Fehler aufgetreten. ";
                                        }
                                    }
                                    else {
                                        if (!$this->user_input->isDate($i['befristung3'])) {
                                            $error .= "Das Datum für die 3. Befristung ist ungültig. ";
                                        }
                                        else {
                                            $date = \DateTime::createFromFormat('d.m.Y', $i['befristung3']);
                                            if ($date instanceof \DateTime) {
                                                if ($date->format("d.m.Y") == $i['befristung3']) {
                                                    if ($mitarbeiter->setBefristung3($date->format("Y-m-d"))) {
                                                        $success .= "Das Datum für die 3. Befristung wurde erfolgreich geändert. ";
                                                    }
                                                    else {
                                                        $error .= "Beim Speichern des Datums für die 3. Befristung ist ein technischer Fehler aufgetreten. ";
                                                    }
                                                }
                                                else {
                                                    $error .= "Das Datum ist ungültig. ";
                                                }
                                            }
                                            else {
                                                $error .= "Das Datum ist ungültig. ";
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }

                    if ($i['praeferenzen_submitted'] == 'true') {
                        $tab = 'praeferenzen';

                        // Montag
                        $montag_von = '';
                        if ($mitarbeiter->getMontagVon() instanceof \DateTime) {
                            $montag_von = $mitarbeiter->getMontagVon()->format("Hi");
                        }
                        $montag_bis = '';
                        if ($mitarbeiter->getMontagBis() instanceof \DateTime) {
                            $montag_bis = $mitarbeiter->getMontagBis()->format("Hi");
                        }
                        if ($i['montag_von'] != $montag_von || $i['montag_bis'] != $montag_bis) {
                            if ($i['montag_von'] != "" && $i['montag_bis'] != "") {
                                if (strlen($i['montag_von']) != 4 || !preg_match('/^[0-2][0-9][0-5][0-9]/', $i['montag_von'])) {
                                    $error .= "<strong>Montag</strong>: Die Zeitangabe ist ungültig. ";
                                    $montag_von_error = true;
                                } elseif (strlen($i['montag_bis']) != 4 || !preg_match('/^[0-2][0-9][0-5][0-9]/', $i['montag_bis'])) {
                                    $error .= "<strong>Montag</strong>: Die Zeitangabe ist ungültig. ";
                                    $montag_bis_error = true;
                                } else {
                                    $von = new \DateTime();
                                    $von->setTime((int) substr($i['montag_von'], 0, 2), (int) substr($i['montag_von'], 2, 2));

                                    $bis = new \DateTime();
                                    $bis->setTime((int) substr($i['montag_bis'], 0, 2), (int) substr($i['montag_bis'], 2, 2));

                                    if (!$mitarbeiter->setMontagVon($von->format("H:i") . ":00")) {
                                        $error .= "Beim Speichern der Uhrzeit für Montag ist ein technischer Fehler aufgetreten. ";
                                    } elseif (!$mitarbeiter->setMontagBis($bis->format("H:i") . ":00")) {
                                        $error .= "Beim Speichern der Uhrzeit für Montag ist ein technischer Fehler aufgetreten. ";
                                    } else {
                                        $success .= "Die Uhrzeit für Montag wurde erfolgreich geändert. ";
                                    }
                                }
                            } elseif ($i['montag_von'] == "" && $i['montag_bis'] != "") {
                                $error .= "<strong>Montag</strong>: Es fehlt eine Startzeit. ";
                                $montag_von_error = true;
                                $montag_bis_error = true;
                            } elseif ($i['montag_bis'] == "" && $i['montag_von'] != "") {
                                $error .= "<strong>Montag</strong>: Es fehlt eine Endzeit. ";
                                $montag_von_error = true;
                                $montag_bis_error = true;
                            }
                        }

                        // Dienstag
                        $dienstag_von = '';
                        if ($mitarbeiter->getDienstagVon() instanceof \DateTime) {
                            $dienstag_von = $mitarbeiter->getDienstagVon()->format("Hi");
                        }
                        $dienstag_bis = '';
                        if ($mitarbeiter->getDienstagBis() instanceof \DateTime) {
                            $dienstag_bis = $mitarbeiter->getDienstagBis()->format("Hi");
                        }
                        if ($i['dienstag_von'] != $dienstag_von || $i['dienstag_bis'] != $dienstag_bis) {
                            if ($i['dienstag_von'] != "" && $i['dienstag_bis'] != "") {
                                if (strlen($i['dienstag_von']) != 4 || !preg_match('/^[0-2][0-9][0-5][0-9]/', $i['dienstag_von'])) {
                                    $error .= "<strong>Dienstag</strong>: Die Zeitangabe ist ungültig. ";
                                    $dienstag_von_error = true;
                                } elseif (strlen($i['dienstag_bis']) != 4 || !preg_match('/^[0-2][0-9][0-5][0-9]/', $i['dienstag_bis'])) {
                                    $error .= "<strong>Dienstag</strong>: Die Zeitangabe ist ungültig. ";
                                    $dienstag_bis_error = true;
                                } else {
                                    $von = new \DateTime();
                                    $von->setTime((int) substr($i['dienstag_von'], 0, 2), (int) substr($i['dienstag_von'], 2, 2));

                                    $bis = new \DateTime();
                                    $bis->setTime((int) substr($i['dienstag_bis'], 0, 2), (int) substr($i['dienstag_bis'], 2, 2));

                                    if (!$mitarbeiter->setDienstagVon($von->format("H:i") . ":00")) {
                                        $error .= "Beim Speichern der Uhrzeit für Dienstag ist ein technischer Fehler aufgetreten. ";
                                    } elseif (!$mitarbeiter->setDienstagBis($bis->format("H:i") . ":00")) {
                                        $error .= "Beim Speichern der Uhrzeit für Dienstag ist ein technischer Fehler aufgetreten. ";
                                    } else {
                                        $success .= "Die Uhrzeit für Dienstag wurde erfolgreich geändert. ";
                                    }
                                }
                            } elseif ($i['dienstag_von'] == "" && $i['dienstag_bis'] != "") {
                                $error .= "<strong>Dienstag</strong>: Es fehlt eine Startzeit. ";
                                $dienstag_von_error = true;
                                $dienstag_bis_error = true;
                            } elseif ($i['dienstag_bis'] == "" && $i['dienstag_von'] != "") {
                                $error .= "<strong>Dienstag</strong>: Es fehlt eine Endzeit. ";
                                $dienstag_von_error = true;
                                $dienstag_bis_error = true;
                            }
                        }

                        // Mittwoch
                        $mittwoch_von = '';
                        if ($mitarbeiter->getMittwochVon() instanceof \DateTime) {
                            $mittwoch_von = $mitarbeiter->getMittwochVon()->format("Hi");
                        }
                        $mittwoch_bis = '';
                        if ($mitarbeiter->getMittwochBis() instanceof \DateTime) {
                            $mittwoch_bis = $mitarbeiter->getMittwochBis()->format("Hi");
                        }
                        if ($i['mittwoch_von'] != $mittwoch_von || $i['mittwoch_bis'] != $mittwoch_bis) {
                            if ($i['mittwoch_von'] != "" && $i['mittwoch_bis'] != "") {
                                if (strlen($i['mittwoch_von']) != 4 || !preg_match('/^[0-2][0-9][0-5][0-9]/', $i['mittwoch_von'])) {
                                    $error .= "<strong>Mittwoch</strong>: Die Zeitangabe ist ungültig. ";
                                    $mittwoch_von_error = true;
                                } elseif (strlen($i['mittwoch_bis']) != 4 || !preg_match('/^[0-2][0-9][0-5][0-9]/', $i['mittwoch_bis'])) {
                                    $error .= "<strong>Mittwoch</strong>: Die Zeitangabe ist ungültig. ";
                                    $mittwoch_bis_error = true;
                                } else {
                                    $von = new \DateTime();
                                    $von->setTime((int) substr($i['mittwoch_von'], 0, 2), (int) substr($i['mittwoch_von'], 2, 2));

                                    $bis = new \DateTime();
                                    $bis->setTime((int) substr($i['mittwoch_bis'], 0, 2), (int) substr($i['mittwoch_bis'], 2, 2));

                                    if (!$mitarbeiter->setMittwochVon($von->format("H:i") . ":00")) {
                                        $error .= "Beim Speichern der Uhrzeit für Mittwoch ist ein technischer Fehler aufgetreten. ";
                                    } elseif (!$mitarbeiter->setMittwochBis($bis->format("H:i") . ":00")) {
                                        $error .= "Beim Speichern der Uhrzeit für Mittwoch ist ein technischer Fehler aufgetreten. ";
                                    } else {
                                        $success .= "Die Uhrzeit für Mittwoch wurde erfolgreich geändert. ";
                                    }
                                }
                            } elseif ($i['mittwoch_von'] == "" && $i['mittwoch_bis'] != "") {
                                $error .= "<strong>Mittwoch</strong>: Es fehlt eine Startzeit. ";
                                $mittwoch_von_error = true;
                                $mittwoch_bis_error = true;
                            } elseif ($i['mittwoch_bis'] == "" && $i['mittwoch_von'] != "") {
                                $error .= "<strong>Mittwoch</strong>: Es fehlt eine Endzeit. ";
                                $mittwoch_von_error = true;
                                $mittwoch_bis_error = true;
                            }
                        }

                        // Donnerstag
                        $donnerstag_von = '';
                        if ($mitarbeiter->getDonnerstagVon() instanceof \DateTime) {
                            $donnerstag_von = $mitarbeiter->getDonnerstagVon()->format("Hi");
                        }
                        $donnerstag_bis = '';
                        if ($mitarbeiter->getDonnerstagBis() instanceof \DateTime) {
                            $donnerstag_bis = $mitarbeiter->getDonnerstagBis()->format("Hi");
                        }
                        if ($i['donnerstag_von'] != $donnerstag_von || $i['donnerstag_bis'] != $donnerstag_bis) {
                            if ($i['donnerstag_von'] != "" && $i['donnerstag_bis'] != "") {
                                if (strlen($i['donnerstag_von']) != 4 || !preg_match('/^[0-2][0-9][0-5][0-9]/', $i['donnerstag_von'])) {
                                    $error .= "<strong>Donnerstag</strong>: Die Zeitangabe ist ungültig. ";
                                    $donnerstag_von_error = true;
                                } elseif (strlen($i['donnerstag_bis']) != 4 || !preg_match('/^[0-2][0-9][0-5][0-9]/', $i['donnerstag_bis'])) {
                                    $error .= "<strong>Donnerstag</strong>: Die Zeitangabe ist ungültig. ";
                                    $donnerstag_bis_error = true;
                                } else {
                                    $von = new \DateTime();
                                    $von->setTime((int) substr($i['donnerstag_von'], 0, 2), (int) substr($i['donnerstag_von'], 2, 2));

                                    $bis = new \DateTime();
                                    $bis->setTime((int) substr($i['donnerstag_bis'], 0, 2), (int) substr($i['donnerstag_bis'], 2, 2));

                                    if (!$mitarbeiter->setDonnerstagVon($von->format("H:i") . ":00")) {
                                        $error .= "Beim Speichern der Uhrzeit für Donnerstag ist ein technischer Fehler aufgetreten. ";
                                    } elseif (!$mitarbeiter->setDonnerstagBis($bis->format("H:i") . ":00")) {
                                        $error .= "Beim Speichern der Uhrzeit für Donnerstag ist ein technischer Fehler aufgetreten. ";
                                    } else {
                                        $success .= "Die Uhrzeit für Donnerstag wurde erfolgreich geändert. ";
                                    }
                                }
                            } elseif ($i['donnerstag_von'] == "" && $i['donnerstag_bis'] != "") {
                                $error .= "<strong>Donnerstag</strong>: Es fehlt eine Startzeit. ";
                                $donnerstag_von_error = true;
                                $donnerstag_bis_error = true;
                            } elseif ($i['donnerstag_bis'] == "" && $i['donnerstag_von'] != "") {
                                $error .= "<strong>Donnerstag</strong>: Es fehlt eine Endzeit. ";
                                $donnerstag_von_error = true;
                                $donnerstag_bis_error = true;
                            }
                        }

                        // Freitag
                        $freitag_von = '';
                        if ($mitarbeiter->getFreitagVon() instanceof \DateTime) {
                            $freitag_von = $mitarbeiter->getFreitagVon()->format("Hi");
                        }
                        $freitag_bis = '';
                        if ($mitarbeiter->getFreitagBis() instanceof \DateTime) {
                            $freitag_bis = $mitarbeiter->getFreitagBis()->format("Hi");
                        }
                        if ($i['freitag_von'] != $freitag_von || $i['freitag_bis'] != $freitag_bis) {
                            if ($i['freitag_von'] != "" && $i['freitag_bis'] != "") {
                                if (strlen($i['freitag_von']) != 4 || !preg_match('/^[0-2][0-9][0-5][0-9]/', $i['freitag_von'])) {
                                    $error .= "<strong>Freitag</strong>: Die Zeitangabe ist ungültig. ";
                                    $freitag_von_error = true;
                                } elseif (strlen($i['freitag_bis']) != 4 || !preg_match('/^[0-2][0-9][0-5][0-9]/', $i['freitag_bis'])) {
                                    $error .= "<strong>Freitag</strong>: Die Zeitangabe ist ungültig. ";
                                    $freitag_bis_error = true;
                                } else {
                                    $von = new \DateTime();
                                    $von->setTime((int) substr($i['freitag_von'], 0, 2), (int) substr($i['freitag_von'], 2, 2));

                                    $bis = new \DateTime();
                                    $bis->setTime((int) substr($i['freitag_bis'], 0, 2), (int) substr($i['freitag_bis'], 2, 2));

                                    if (!$mitarbeiter->setFreitagVon($von->format("H:i") . ":00")) {
                                        $error .= "Beim Speichern der Uhrzeit für Freitag ist ein technischer Fehler aufgetreten. ";
                                    } elseif (!$mitarbeiter->setFreitagBis($bis->format("H:i") . ":00")) {
                                        $error .= "Beim Speichern der Uhrzeit für Freitag ist ein technischer Fehler aufgetreten. ";
                                    } else {
                                        $success .= "Die Uhrzeit für Freitag wurde erfolgreich geändert. ";
                                    }
                                }
                            } elseif ($i['freitag_von'] == "" && $i['freitag_bis'] != "") {
                                $error .= "<strong>Freitag</strong>: Es fehlt eine Startzeit. ";
                                $freitag_von_error = true;
                                $freitag_bis_error = true;
                            } elseif ($i['freitag_bis'] == "" && $i['freitag_von'] != "") {
                                $error .= "<strong>Freitag</strong>: Es fehlt eine Endzeit. ";
                                $freitag_von_error = true;
                                $freitag_bis_error = true;
                            }
                        }

                        // Samstag
                        $samstag_von = '';
                        if ($mitarbeiter->getSamstagVon() instanceof \DateTime) {
                            $samstag_von = $mitarbeiter->getSamstagVon()->format("Hi");
                        }
                        $samstag_bis = '';
                        if ($mitarbeiter->getSamstagBis() instanceof \DateTime) {
                            $samstag_bis = $mitarbeiter->getSamstagBis()->format("Hi");
                        }
                        if ($i['samstag_von'] != $samstag_von || $i['samstag_bis'] != $samstag_bis) {
                            if ($i['samstag_von'] != "" && $i['samstag_bis'] != "") {
                                if (strlen($i['samstag_von']) != 4 || !preg_match('/^[0-2][0-9][0-5][0-9]/', $i['samstag_von'])) {
                                    $error .= "<strong>Samstag</strong>: Die Zeitangabe ist ungültig. ";
                                    $samstag_von_error = true;
                                } elseif (strlen($i['samstag_bis']) != 4 || !preg_match('/^[0-2][0-9][0-5][0-9]/', $i['samstag_bis'])) {
                                    $error .= "<strong>Samstag</strong>: Die Zeitangabe ist ungültig. ";
                                    $samstag_bis_error = true;
                                } else {
                                    $von = new \DateTime();
                                    $von->setTime((int) substr($i['samstag_von'], 0, 2), (int) substr($i['samstag_von'], 2, 2));

                                    $bis = new \DateTime();
                                    $bis->setTime((int) substr($i['samstag_bis'], 0, 2), (int) substr($i['samstag_bis'], 2, 2));

                                    if (!$mitarbeiter->setSamstagVon($von->format("H:i") . ":00")) {
                                        $error .= "Beim Speichern der Uhrzeit für Samstag ist ein technischer Fehler aufgetreten. ";
                                    } elseif (!$mitarbeiter->setSamstagBis($bis->format("H:i") . ":00")) {
                                        $error .= "Beim Speichern der Uhrzeit für Samstag ist ein technischer Fehler aufgetreten. ";
                                    } else {
                                        $success .= "Die Uhrzeit für Samstag wurde erfolgreich geändert. ";
                                    }
                                }
                            } elseif ($i['samstag_von'] == "" && $i['samstag_bis'] != "") {
                                $error .= "<strong>Samstag</strong>: Es fehlt eine Startzeit. ";
                                $samstag_von_error = true;
                                $samstag_bis_error = true;
                            } elseif ($i['samstag_bis'] == "" && $i['samstag_von'] != "") {
                                $error .= "<strong>Samstag</strong>: Es fehlt eine Endzeit. ";
                                $samstag_von_error = true;
                                $samstag_bis_error = true;
                            }
                        }

                        // Sonntag
                        $sonntag_von = '';
                        if ($mitarbeiter->getSonntagVon() instanceof \DateTime) {
                            $sonntag_von = $mitarbeiter->getSonntagVon()->format("Hi");
                        }
                        $sonntag_bis = '';
                        if ($mitarbeiter->getSonntagBis() instanceof \DateTime) {
                            $sonntag_bis = $mitarbeiter->getSonntagBis()->format("Hi");
                        }
                        if ($i['sonntag_von'] != $sonntag_von || $i['sonntag_bis'] != $sonntag_bis) {
                            if ($i['sonntag_von'] != "" && $i['sonntag_bis'] != "") {
                                if (strlen($i['sonntag_von']) != 4 || !preg_match('/^[0-2][0-9][0-5][0-9]/', $i['sonntag_von'])) {
                                    $error .= "<strong>Sonntag</strong>: Die Zeitangabe ist ungültig. ";
                                    $sonntag_von_error = true;
                                } elseif (strlen($i['sonntag_bis']) != 4 || !preg_match('/^[0-2][0-9][0-5][0-9]/', $i['sonntag_bis'])) {
                                    $error .= "<strong>Sonntag</strong>: Die Zeitangabe ist ungültig. ";
                                    $sonntag_bis_error = true;
                                } else {
                                    $von = new \DateTime();
                                    $von->setTime((int) substr($i['sonntag_von'], 0, 2), (int) substr($i['sonntag_von'], 2, 2));

                                    $bis = new \DateTime();
                                    $bis->setTime((int) substr($i['sonntag_bis'], 0, 2), (int) substr($i['sonntag_bis'], 2, 2));

                                    if (!$mitarbeiter->setSonntagVon($von->format("H:i") . ":00")) {
                                        $error .= "Beim Speichern der Uhrzeit für Sonntag ist ein technischer Fehler aufgetreten. ";
                                    } elseif (!$mitarbeiter->setSonntagBis($bis->format("H:i") . ":00")) {
                                        $error .= "Beim Speichern der Uhrzeit für Sonntag ist ein technischer Fehler aufgetreten. ";
                                    } else {
                                        $success .= "Die Uhrzeit für Sonntag wurde erfolgreich geändert. ";
                                    }
                                }
                            } elseif ($i['sonntag_von'] == "" && $i['sonntag_bis'] != "") {
                                $error .= "<strong>Sonntag</strong>: Es fehlt eine Startzeit. ";
                                $sonntag_von_error = true;
                                $sonntag_bis_error = true;
                            } elseif ($i['sonntag_bis'] == "" && $i['sonntag_von'] != "") {
                                $error .= "<strong>Sonntag</strong>: Es fehlt eine Endzeit. ";
                                $sonntag_von_error = true;
                                $sonntag_bis_error = true;
                            }
                        }

                        // Stamm
                        $mitarbeiterfiltermodelle = \ttact\Models\MitarbeiterfilterModel::findStammByMitarbeiter($this->db, $mitarbeiter->getID());
                        $mitarbeiterfiltermodelle_kundennummern = [];
                        foreach ($mitarbeiterfiltermodelle as $mitarbeiterfilter) {
                            if ($mitarbeiterfilter->getKunde() instanceof \ttact\Models\KundeModel) {
                                $mitarbeiterfiltermodelle_kundennummern[] = $mitarbeiterfilter->getKunde()->getKundennummer();
                            }
                        }
                        sort($i['stamm']);
                        sort($mitarbeiterfiltermodelle_kundennummern);
                        if ($i['stamm'] != $mitarbeiterfiltermodelle_kundennummern) {
                            $save = [];
                            $delete = [];
                            foreach ($i['stamm'] as $kundennummer) {
                                if (!in_array($kundennummer, $mitarbeiterfiltermodelle_kundennummern)) {
                                    $save[] = $kundennummer;
                                }
                            }
                            foreach ($mitarbeiterfiltermodelle_kundennummern as $kundennummer) {
                                if (!in_array($kundennummer, $i['stamm'])) {
                                    $delete[] = $kundennummer;
                                }
                            }
                            foreach ($save as $kundennummer) {
                                $kunde = \ttact\Models\KundeModel::findByKundennummer($this->db, $kundennummer);
                                if ($kunde instanceof \ttact\Models\KundeModel) {
                                    $data = [
                                        'type' => 'stamm',
                                        'kunde_id' => $kunde->getID(),
                                        'mitarbeiter_id' => $mitarbeiter->getID()
                                    ];
                                    $mitarbeiterfilter_model = \ttact\Models\MitarbeiterfilterModel::createNew($this->db, $data);
                                    if ($mitarbeiterfilter_model instanceof \ttact\Models\MitarbeiterfilterModel) {
                                        $success .= "Die Stammmärkte des Mitarbeiters wurden erfolgreich geändert. ";
                                    } else {
                                        $error .= "Beim Speichern eines Stammmarkts für den Mitarbeiter ist ein technischer Fehler aufgetreten. ";
                                    }
                                }
                            }
                            foreach ($delete as $kundennummer) {
                                $kunde = \ttact\Models\KundeModel::findByKundennummer($this->db, $kundennummer);
                                if ($kunde instanceof \ttact\Models\KundeModel) {
                                    foreach ($mitarbeiterfiltermodelle as $mitarbeiterfilter) {
                                        if ($mitarbeiterfilter->getKunde() instanceof \ttact\Models\KundeModel) {
                                            if ($mitarbeiterfilter->getKunde()->getKundennummer() == $kundennummer) {
                                                if ($mitarbeiterfilter->delete()) {
                                                    $success .= "Die Stammmärkte des Mitarbeiters wurden erfolgreich geändert. ";
                                                } else {
                                                    $error .= "Beim Speichern eines Stammmarkts für den Mitarbeiter ist ein technischer Fehler aufgetreten. ";
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        // Springer
                        $mitarbeiterfiltermodelle = \ttact\Models\MitarbeiterfilterModel::findSpringerByMitarbeiter($this->db, $mitarbeiter->getID());
                        $mitarbeiterfiltermodelle_kundennummern = [];
                        foreach ($mitarbeiterfiltermodelle as $mitarbeiterfilter) {
                            if ($mitarbeiterfilter->getKunde() instanceof \ttact\Models\KundeModel) {
                                $mitarbeiterfiltermodelle_kundennummern[] = $mitarbeiterfilter->getKunde()->getKundennummer();
                            }
                        }
                        sort($i['springer']);
                        sort($mitarbeiterfiltermodelle_kundennummern);
                        if ($i['springer'] != $mitarbeiterfiltermodelle_kundennummern) {
                            $save = [];
                            $delete = [];
                            foreach ($i['springer'] as $kundennummer) {
                                if (!in_array($kundennummer, $mitarbeiterfiltermodelle_kundennummern)) {
                                    $save[] = $kundennummer;
                                }
                            }
                            foreach ($mitarbeiterfiltermodelle_kundennummern as $kundennummer) {
                                if (!in_array($kundennummer, $i['springer'])) {
                                    $delete[] = $kundennummer;
                                }
                            }
                            foreach ($save as $kundennummer) {
                                $kunde = \ttact\Models\KundeModel::findByKundennummer($this->db, $kundennummer);
                                if ($kunde instanceof \ttact\Models\KundeModel) {
                                    $data = [
                                        'type' => 'springer',
                                        'kunde_id' => $kunde->getID(),
                                        'mitarbeiter_id' => $mitarbeiter->getID()
                                    ];
                                    $mitarbeiterfilter_model = \ttact\Models\MitarbeiterfilterModel::createNew($this->db, $data);
                                    if ($mitarbeiterfilter_model instanceof \ttact\Models\MitarbeiterfilterModel) {
                                        $success .= "Die Springermärkte des Mitarbeiters wurden erfolgreich geändert. ";
                                    } else {
                                        $error .= "Beim Speichern eines Springermarkts für den Mitarbeiter ist ein technischer Fehler aufgetreten. ";
                                    }
                                }
                            }
                            foreach ($delete as $kundennummer) {
                                $kunde = \ttact\Models\KundeModel::findByKundennummer($this->db, $kundennummer);
                                if ($kunde instanceof \ttact\Models\KundeModel) {
                                    foreach ($mitarbeiterfiltermodelle as $mitarbeiterfilter) {
                                        if ($mitarbeiterfilter->getKunde() instanceof \ttact\Models\KundeModel) {
                                            if ($mitarbeiterfilter->getKunde()->getKundennummer() == $kundennummer) {
                                                if ($mitarbeiterfilter->delete()) {
                                                    $success .= "Die Springermärkte des Mitarbeiters wurden erfolgreich geändert. ";
                                                } else {
                                                    $error .= "Beim Speichern eines Springermarkts für den Mitarbeiter ist ein technischer Fehler aufgetreten. ";
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        // Sperre
                        $mitarbeiterfiltermodelle = \ttact\Models\MitarbeiterfilterModel::findSperreByMitarbeiter($this->db, $mitarbeiter->getID());
                        $mitarbeiterfiltermodelle_kundennummern = [];
                        foreach ($mitarbeiterfiltermodelle as $mitarbeiterfilter) {
                            if ($mitarbeiterfilter->getKunde() instanceof \ttact\Models\KundeModel) {
                                $mitarbeiterfiltermodelle_kundennummern[] = $mitarbeiterfilter->getKunde()->getKundennummer();
                            }
                        }
                        sort($i['sperre']);
                        sort($mitarbeiterfiltermodelle_kundennummern);
                        if ($i['sperre'] != $mitarbeiterfiltermodelle_kundennummern) {
                            $save = [];
                            $delete = [];
                            foreach ($i['sperre'] as $kundennummer) {
                                if (!in_array($kundennummer, $mitarbeiterfiltermodelle_kundennummern)) {
                                    $save[] = $kundennummer;
                                }
                            }
                            foreach ($mitarbeiterfiltermodelle_kundennummern as $kundennummer) {
                                if (!in_array($kundennummer, $i['sperre'])) {
                                    $delete[] = $kundennummer;
                                }
                            }
                            foreach ($save as $kundennummer) {
                                $kunde = \ttact\Models\KundeModel::findByKundennummer($this->db, $kundennummer);
                                if ($kunde instanceof \ttact\Models\KundeModel) {
                                    $data = [
                                        'type' => 'sperre',
                                        'kunde_id' => $kunde->getID(),
                                        'mitarbeiter_id' => $mitarbeiter->getID()
                                    ];
                                    $mitarbeiterfilter_model = \ttact\Models\MitarbeiterfilterModel::createNew($this->db, $data);
                                    if ($mitarbeiterfilter_model instanceof \ttact\Models\MitarbeiterfilterModel) {
                                        $success .= "Die Sperrmärkte des Mitarbeiters wurden erfolgreich geändert. ";
                                    } else {
                                        $error .= "Beim Speichern eines Sperrmarkts für den Mitarbeiter ist ein technischer Fehler aufgetreten. ";
                                    }
                                }
                            }
                            foreach ($delete as $kundennummer) {
                                $kunde = \ttact\Models\KundeModel::findByKundennummer($this->db, $kundennummer);
                                if ($kunde instanceof \ttact\Models\KundeModel) {
                                    foreach ($mitarbeiterfiltermodelle as $mitarbeiterfilter) {
                                        if ($mitarbeiterfilter->getKunde() instanceof \ttact\Models\KundeModel) {
                                            if ($mitarbeiterfilter->getKunde()->getKundennummer() == $kundennummer) {
                                                if ($mitarbeiterfilter->delete()) {
                                                    $success .= "Die Sperrmärkte des Mitarbeiters wurden erfolgreich geändert. ";
                                                } else {
                                                    $error .= "Beim Speichern eines Sperrmarkts für den Mitarbeiter ist ein technischer Fehler aufgetreten. ";
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        // Abteilungsfreigaben
                        $abteilungsfreigabenmodelle = \ttact\Models\AbteilungsfreigabeModel::findByMitarbeiter($this->db, $mitarbeiter->getID());
                        $abteilungen_ids = [];
                        foreach ($abteilungsfreigabenmodelle as $abteilungsfreigabenmodell) {
                            if ($abteilungsfreigabenmodell->getAbteilung() instanceof \ttact\Models\AbteilungModel) {
                                $abteilungen_ids[] = $abteilungsfreigabenmodell->getAbteilung()->getID();
                            }
                        }
                        sort($i['abteilungsfreigaben']);
                        sort($abteilungen_ids);
                        if ($i['abteilungsfreigaben'] != $abteilungen_ids) {
                            $save = [];
                            $delete = [];
                            foreach ($i['abteilungsfreigaben'] as $id) {
                                if (!in_array($id, $abteilungen_ids)) {
                                    $save[] = $id;
                                }
                            }
                            foreach ($abteilungen_ids as $id) {
                                if (!in_array($id, $i['abteilungsfreigaben'])) {
                                    $delete[] = $id;
                                }
                            }
                            foreach ($save as $id) {
                                $abteilung = \ttact\Models\AbteilungModel::findByID($this->db, $id);
                                if ($abteilung instanceof \ttact\Models\AbteilungModel) {
                                    $data = [
                                        'mitarbeiter_id' => $mitarbeiter->getID(),
                                        'abteilung_id' => $abteilung->getID()
                                    ];
                                    $abteilungsfreigabemodell = \ttact\Models\AbteilungsfreigabeModel::createNew($this->db, $data);
                                    if ($abteilungsfreigabemodell instanceof \ttact\Models\AbteilungsfreigabeModel) {
                                        $success .= "Die Abteilungsfreigaben des Mitarbeiters wurden erfolgreich geändert. ";
                                    } else {
                                        $error .= "Beim Speichern der Abteilungsfreigaben für den Mitarbeiter ist ein technischer Fehler aufgetreten. ";
                                    }
                                }
                            }
                            foreach ($delete as $id) {
                                $abteilung = \ttact\Models\AbteilungModel::findByID($this->db, $id);
                                if ($abteilung instanceof \ttact\Models\AbteilungModel) {
                                    foreach ($abteilungsfreigabenmodelle as $abteilungsfreigabenmodell) {
                                        if ($abteilungsfreigabenmodell->getAbteilung() instanceof \ttact\Models\AbteilungModel) {
                                            if ($abteilungsfreigabenmodell->getAbteilung()->getID() == $id) {
                                                if ($abteilungsfreigabenmodell->delete()) {
                                                    $success .= "Die Abteilungsfreigaben des Mitarbeiters wurden erfolgreich geändert. ";
                                                } else {
                                                    $error .= "Beim Speichern der Abteilungsfreigaben für den Mitarbeiter ist ein technischer Fehler aufgetreten. ";
                                                }
                                            }
                                        }
                                    }
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
                        $this->smarty_vars['success'] = $success;
                    }

                    // fill values into the form
                    $eintritt = '';
                    if ($mitarbeiter->getEintritt() instanceof \DateTime) {
                        $eintritt = $mitarbeiter->getEintritt()->format("d.m.Y");
                    }
                    $austritt = '';
                    if ($mitarbeiter->getAustritt() instanceof \DateTime) {
                        $austritt = $mitarbeiter->getAustritt()->format("d.m.Y");
                    }
                    $befristung = '';
                    if ($mitarbeiter->getBefristung() instanceof \DateTime) {
                        $befristung = $mitarbeiter->getBefristung()->format("d.m.Y");
                    }
                    $befristung1 = '';
                    if ($mitarbeiter->getBefristung1() instanceof \DateTime) {
                        $befristung1 = $mitarbeiter->getBefristung1()->format("d.m.Y");
                    }
                    $befristung2 = '';
                    if ($mitarbeiter->getBefristung2() instanceof \DateTime) {
                        $befristung2 = $mitarbeiter->getBefristung2()->format("d.m.Y");
                    }
                    $befristung3 = '';
                    if ($mitarbeiter->getBefristung3() instanceof \DateTime) {
                        $befristung3 = $mitarbeiter->getBefristung3()->format("d.m.Y");
                    }

                    $stamm = [];
                    foreach (\ttact\Models\MitarbeiterfilterModel::findStammByMitarbeiter($this->db, $mitarbeiter->getID()) as $mitarbeiterfilter) {
                        if ($mitarbeiterfilter->getKunde() instanceof \ttact\Models\KundeModel) {
                            $stamm[] = $mitarbeiterfilter->getKunde()->getKundennummer();
                        }
                    }
                    $springer = [];
                    foreach (\ttact\Models\MitarbeiterfilterModel::findSpringerByMitarbeiter($this->db, $mitarbeiter->getID()) as $mitarbeiterfilter) {
                        if ($mitarbeiterfilter->getKunde() instanceof \ttact\Models\KundeModel) {
                            $springer[] = $mitarbeiterfilter->getKunde()->getKundennummer();
                        }
                    }
                    $sperre = [];
                    foreach (\ttact\Models\MitarbeiterfilterModel::findSperreByMitarbeiter($this->db, $mitarbeiter->getID()) as $mitarbeiterfilter) {
                        if ($mitarbeiterfilter->getKunde() instanceof \ttact\Models\KundeModel) {
                            $sperre[] = $mitarbeiterfilter->getKunde()->getKundennummer();
                        }
                    }

                    $abteilungsfreigaben = [];
                    foreach (\ttact\Models\AbteilungsfreigabeModel::findByMitarbeiter($this->db, $mitarbeiter->getID()) as $abteilungsfreigabe) {
                        if ($abteilungsfreigabe->getAbteilung() instanceof \ttact\Models\AbteilungModel) {
                            $abteilungsfreigaben[] = $abteilungsfreigabe->getAbteilung()->getID();
                        }
                    }

                    $montag_von = '';
                    if ($mitarbeiter->getMontagVon() instanceof \DateTime) {
                        $montag_von = $mitarbeiter->getMontagVon()->format("H:i");
                    }
                    $montag_bis = '';
                    if ($mitarbeiter->getMontagBis() instanceof \DateTime) {
                        $montag_bis = $mitarbeiter->getMontagBis()->format("H:i");
                    }
                    $dienstag_von = '';
                    if ($mitarbeiter->getDienstagVon() instanceof \DateTime) {
                        $dienstag_von = $mitarbeiter->getDienstagVon()->format("H:i");
                    }
                    $dienstag_bis = '';
                    if ($mitarbeiter->getDienstagBis() instanceof \DateTime) {
                        $dienstag_bis = $mitarbeiter->getDienstagBis()->format("H:i");
                    }
                    $mittwoch_von = '';
                    if ($mitarbeiter->getMittwochVon() instanceof \DateTime) {
                        $mittwoch_von = $mitarbeiter->getMittwochVon()->format("H:i");
                    }
                    $mittwoch_bis = '';
                    if ($mitarbeiter->getMittwochBis() instanceof \DateTime) {
                        $mittwoch_bis = $mitarbeiter->getMittwochBis()->format("H:i");
                    }
                    $donnerstag_von = '';
                    if ($mitarbeiter->getDonnerstagVon() instanceof \DateTime) {
                        $donnerstag_von = $mitarbeiter->getDonnerstagVon()->format("H:i");
                    }
                    $donnerstag_bis = '';
                    if ($mitarbeiter->getDonnerstagBis() instanceof \DateTime) {
                        $donnerstag_bis = $mitarbeiter->getDonnerstagBis()->format("H:i");
                    }
                    $freitag_von = '';
                    if ($mitarbeiter->getFreitagVon() instanceof \DateTime) {
                        $freitag_von = $mitarbeiter->getFreitagVon()->format("H:i");
                    }
                    $freitag_bis = '';
                    if ($mitarbeiter->getFreitagBis() instanceof \DateTime) {
                        $freitag_bis = $mitarbeiter->getFreitagBis()->format("H:i");
                    }
                    $samstag_von = '';
                    if ($mitarbeiter->getSamstagVon() instanceof \DateTime) {
                        $samstag_von = $mitarbeiter->getSamstagVon()->format("H:i");
                    }
                    $samstag_bis = '';
                    if ($mitarbeiter->getSamstagBis() instanceof \DateTime) {
                        $samstag_bis = $mitarbeiter->getSamstagBis()->format("H:i");
                    }
                    $sonntag_von = '';
                    if ($mitarbeiter->getSonntagVon() instanceof \DateTime) {
                        $sonntag_von = $mitarbeiter->getSonntagVon()->format("H:i");
                    }
                    $sonntag_bis = '';
                    if ($mitarbeiter->getSonntagBis() instanceof \DateTime) {
                        $sonntag_bis = $mitarbeiter->getSonntagBis()->format("H:i");
                    }

                    $geburtsdatum = '';
                    if ($mitarbeiter->getGeburtsdatum() instanceof \DateTime) {
                        $geburtsdatum = $mitarbeiter->getGeburtsdatum()->format("d.m.Y");
                    }

                    $values = [
                        'id'                    => $mitarbeiter->getID(),
                        'personalnummer'        => $mitarbeiter->getPersonalnummer(),
                        'geschlecht'            => $mitarbeiter->getGeschlecht(),
                        'vorname'               => $mitarbeiter->getVorname(),
                        'nachname'              => $mitarbeiter->getNachname(),
                        'telefon1'              => $mitarbeiter->getTelefon1(),
                        'telefon2'              => $mitarbeiter->getTelefon2(),
                        'emailadresse'          => $mitarbeiter->getEmailadresse(),
                        'strasse'               => $mitarbeiter->getStrasse(),
                        'hausnummer'            => $mitarbeiter->getHausnummer(),
                        'adresszusatz'          => $mitarbeiter->getAdresszusatz(),
                        'postleitzahl'          => $mitarbeiter->getPostleitzahl(),
                        'ort'                   => $mitarbeiter->getOrt(),
                        'geburtsdatum'          => $geburtsdatum,
                        'iban'                  => $mitarbeiter->getIBAN(),
                        'bic'                   => $mitarbeiter->getBIC(),
                        'eintritt'              => $eintritt,
                        'austritt'              => $austritt,
                        'befristung'            => $befristung,
                        'befristung1'           => $befristung1,
                        'befristung2'           => $befristung2,
                        'befristung3'           => $befristung3,
                        'jahresurlaub'          => $mitarbeiter->getJahresurlaub() == 0 ? '' : $mitarbeiter->getJahresurlaub(),
                        'resturlaub_vorjahr'    => $mitarbeiter->getResturlaubVorjahr() == 0 ? '' : $mitarbeiter->getResturlaubVorjahr(),
                        'urlaubstage_genommen'  => '',
                        'urlaubstage_uebrig'    => '',
                        'tab'                   => $tab,
                        'stamm'                 => $stamm,
                        'springer'              => $springer,
                        'sperre'                => $sperre,
                        'montag_von'            => $montag_von,
                        'montag_bis'            => $montag_bis,
                        'dienstag_von'          => $dienstag_von,
                        'dienstag_bis'          => $dienstag_bis,
                        'mittwoch_von'          => $mittwoch_von,
                        'mittwoch_bis'          => $mittwoch_bis,
                        'donnerstag_von'        => $donnerstag_von,
                        'donnerstag_bis'        => $donnerstag_bis,
                        'freitag_von'           => $freitag_von,
                        'freitag_bis'           => $freitag_bis,
                        'samstag_von'           => $samstag_von,
                        'samstag_bis'           => $samstag_bis,
                        'sonntag_von'           => $sonntag_von,
                        'sonntag_bis'           => $sonntag_bis,
                        'montag_von_error'      => $montag_von_error,
                        'montag_bis_error'      => $montag_bis_error,
                        'dienstag_von_error'    => $dienstag_von_error,
                        'dienstag_bis_error'    => $dienstag_bis_error,
                        'mittwoch_von_error'    => $mittwoch_von_error,
                        'mittwoch_bis_error'    => $mittwoch_bis_error,
                        'donnerstag_von_error'  => $donnerstag_von_error,
                        'donnerstag_bis_error'  => $donnerstag_bis_error,
                        'freitag_von_error'     => $freitag_von_error,
                        'freitag_bis_error'     => $freitag_bis_error,
                        'samstag_von_error'     => $samstag_von_error,
                        'samstag_bis_error'     => $samstag_bis_error,
                        'sonntag_von_error'     => $sonntag_von_error,
                        'sonntag_bis_error'     => $sonntag_bis_error,
                        'abteilungsfreigaben'   => $abteilungsfreigaben
                    ];
                    if ($this->current_user->getUsergroup()->hasRight('notizen')) {
                        $values['notizen_allgemein'] = $mitarbeiter->getNotizenAllgemein();
                        $values['notizen_januar'] =$mitarbeiter->getNotizenJanuar();
                        $values['notizen_februar'] = $mitarbeiter->getNotizenFebruar();
                        $values['notizen_maerz'] = $mitarbeiter->getNotizenMaerz();
                        $values['notizen_april'] = $mitarbeiter->getNotizenApril();
                        $values['notizen_mai'] = $mitarbeiter->getNotizenMai();
                        $values['notizen_juni'] = $mitarbeiter->getNotizenJuni();
                        $values['notizen_juli'] = $mitarbeiter->getNotizenJuli();
                        $values['notizen_august'] = $mitarbeiter->getNotizenAugust();
                        $values['notizen_september'] = $mitarbeiter->getNotizenSeptember();
                        $values['notizen_oktober'] = $mitarbeiter->getNotizenOktober();
                        $values['notizen_november'] = $mitarbeiter->getNotizenNovember();
                        $values['notizen_dezember'] = $mitarbeiter->getNotizenDezember();
                    }
                    $this->smarty_vars['values'] = $values;

                    $mitarbeiterliste = [];
                    $alle_mitarbeiter = \ttact\Models\MitarbeiterModel::findAll($this->db);
                    foreach ($alle_mitarbeiter as $m) {
                        $mitarbeiterliste[] = [
                            'personalnummer' => $m->getPersonalnummer(),
                            'vorname' => $m->getVorname(),
                            'nachname' => $m->getNachname()
                        ];
                    }
                    $this->smarty_vars['mitarbeiterliste'] = $mitarbeiterliste;

                    // get data for Lohnkonfiguration
                    if ($this->current_user->getUsergroup()->hasRight('vertragliches')) {
                        $lohnkonfigurationsliste = [];
                        $lohnkonfigurationen = \ttact\Models\LohnkonfigurationModel::findByMitarbeiter($this->db, $mitarbeiter->getID());
                        foreach ($lohnkonfigurationen as $lohnkonfiguration) {
                            $gueltig_ab = '00.00.0000';
                            if ($lohnkonfiguration->getGueltigAb() instanceof \DateTime) {
                                $gueltig_ab = $lohnkonfiguration->getGueltigAb()->format("d.m.Y");
                            }
                            if ($this->company == 'tps') {
                                $lohnkonfigurationsliste[] = [
                                    'id' => $lohnkonfiguration->getID(),
                                    'gueltig_ab' => $gueltig_ab,
                                    'wochenstunden' => $lohnkonfiguration->getWochenstunden(),
                                    'lohn' => $lohnkonfiguration->getSollLohn()
                                ];
                            } else {
                                $tarif = '';
                                if ($lohnkonfiguration->getTarif() instanceof \ttact\Models\TarifModel) {
                                    $tarif = $lohnkonfiguration->getTarif()->getBezeichnung();
                                }
                                $lohnkonfigurationsliste[] = [
                                    'id' => $lohnkonfiguration->getID(),
                                    'gueltig_ab' => $gueltig_ab,
                                    'tarif' => $tarif,
                                    'wochenstunden' => $lohnkonfiguration->getWochenstunden(),
                                    'lohn' => $lohnkonfiguration->getSollLohn()
                                ];
                            }
                        }
                        $this->smarty_vars['lohnkonfigurationsliste'] = $lohnkonfigurationsliste;

                        if ($this->company != 'tps') {
                            // Aktueller Lohnberechnungsmonat
                            $lohnberechnungsmonat = new \DateTime("now");
                            $lohnberechnungsmonat_tag = (int)$lohnberechnungsmonat->format("d");
                            if ($lohnberechnungsmonat_tag <= 15) {
                                $lohnberechnungsmonat->sub(new \DateInterval("P0000-01-00T00:00:00"));
                            }
                            $lohnberechnungsmonat->setDate((int)$lohnberechnungsmonat->format('Y'), (int)$lohnberechnungsmonat->format('m'), 1);
                            $monate = [
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
                            $lohnberechnungsmonat_bezeichnung = $monate[(int)$lohnberechnungsmonat->format('m')];
                            $this->smarty_vars['aktuelle_lohndaten'] = [];
                            $this->smarty_vars['aktuelle_lohndaten']['monatsbezeichnung'] = $lohnberechnungsmonat_bezeichnung;
                            $this->smarty_vars['aktuelle_lohndaten']['jahr'] = $lohnberechnungsmonat->format('Y');

                            $lohnkonfiguration_model = \ttact\Models\LohnkonfigurationModel::findForMitarbeiterDate($this->db, $mitarbeiter->getID(), $lohnberechnungsmonat);
                            if ($lohnkonfiguration_model instanceof \ttact\Models\LohnkonfigurationModel) {
                                if ($lohnkonfiguration_model->getTarif() instanceof \ttact\Models\TarifModel) {
                                    $tariflohnbetrag_model = \ttact\Models\TariflohnbetragModel::findByTarifAndDatum($this->db, $lohnkonfiguration_model->getTarif()->getID(), $lohnberechnungsmonat);
                                    if ($tariflohnbetrag_model instanceof \ttact\Models\TariflohnbetragModel) {
                                        $mehr_als_9_monate_beschaeftigt = false;
                                        $mehr_als_12_monate_beschaeftigt = false;
                                        if ($mitarbeiter->getEintritt() instanceof \DateTime) {
                                            $monatsanfang_minus_9_monate = clone $lohnberechnungsmonat;
                                            $monatsanfang_minus_9_monate->sub(new \DateInterval("P0000-09-00T00:00:00"));
                                            if ($mitarbeiter->getEintritt() <= $monatsanfang_minus_9_monate) {
                                                $mehr_als_9_monate_beschaeftigt = true;
                                            }
                                            $monatsanfang_minus_12_monate = clone $lohnberechnungsmonat;
                                            $monatsanfang_minus_12_monate->sub(new \DateInterval("P0001-00-00T00:00:00"));
                                            if ($mitarbeiter->getEintritt() <= $monatsanfang_minus_12_monate) {
                                                $mehr_als_12_monate_beschaeftigt = true;
                                            }
                                        }

                                        $zuschlag_9_monate = 0;
                                        $zuschlag_12_monate = 0;
                                        $uebertarifliche_zulage = 0;

                                        $lohnsatz = $tariflohnbetrag_model->getLohn();
                                        if ($mehr_als_12_monate_beschaeftigt) {
                                            $zuschlag_12_monate = $lohnsatz * 0.03;
                                        } elseif ($mehr_als_9_monate_beschaeftigt) {
                                            $zuschlag_9_monate = $lohnsatz * 0.015;
                                        }

                                        if ($lohnkonfiguration_model->getSollLohn() > ($tariflohnbetrag_model->getLohn() + $zuschlag_9_monate + $zuschlag_12_monate)) {
                                            $uebertarifliche_zulage = $lohnkonfiguration_model->getSollLohn() - ($tariflohnbetrag_model->getLohn() + $zuschlag_9_monate + $zuschlag_12_monate);
                                        }

                                        // Tarifbezeichnung
                                        $this->smarty_vars['aktuelle_lohndaten']['tarifbezeichnung'] = $lohnkonfiguration_model->getTarif()->getBezeichnung();

                                        // Tariflohn
                                        $this->smarty_vars['aktuelle_lohndaten']['tariflohn'] = $tariflohnbetrag_model->getLohn();

                                        // Zuschlag 9 Monate
                                        $this->smarty_vars['aktuelle_lohndaten']['zuschlag_9_monate'] = $zuschlag_9_monate;

                                        // Zuschlag 12 Monate
                                        $this->smarty_vars['aktuelle_lohndaten']['zuschlag_12_monate'] = $zuschlag_12_monate;

                                        // übertarifliche Zulage
                                        $this->smarty_vars['aktuelle_lohndaten']['uebertarifliche_zulage'] = $uebertarifliche_zulage;

                                        // Gesamtlohn
                                        $this->smarty_vars['aktuelle_lohndaten']['gesamtlohn'] = $tariflohnbetrag_model->getLohn() + $zuschlag_9_monate + $zuschlag_12_monate + $uebertarifliche_zulage;
                                    }
                                }
                            }
                        }
                    }

                    // get data for tagessoll
                    if ($this->current_user->getUsergroup()->hasRight('tagessoll')) {
                        $tagessollliste = [];
                        $tagessoll_models = \ttact\Models\TagessollModel::findByMitarbeiter($this->db, $mitarbeiter->getID());
                        foreach ($tagessoll_models as $tagessoll_model) {
                            if ($this->company == 'tps') {
                                $tagessollliste[] = [
                                    'id' => $tagessoll_model->getID(),
                                    'jahr' => $tagessoll_model->getJahr(),
                                    'monat' => $tagessoll_model->getMonat(),
                                    'tagessoll_allgemein' => $tagessoll_model->getTagessoll()
                                ];
                            } else {
                                $tagessollliste[] = [
                                    'id' => $tagessoll_model->getID(),
                                    'jahr' => $tagessoll_model->getJahr(),
                                    'monat' => $tagessoll_model->getMonat(),
                                    'tagessoll_allgemein' => $tagessoll_model->getTagessoll(),
                                    'tagessoll_montag' => $tagessoll_model->getTagessollMontag(),
                                    'tagessoll_dienstag' => $tagessoll_model->getTagessollDienstag(),
                                    'tagessoll_mittwoch' => $tagessoll_model->getTagessollMittwoch(),
                                    'tagessoll_donnerstag' => $tagessoll_model->getTagessollDonnerstag(),
                                    'tagessoll_freitag' => $tagessoll_model->getTagessollFreitag(),
                                    'tagessoll_samstag' => $tagessoll_model->getTagessollSamstag(),
                                    'tagessoll_sonntag' => $tagessoll_model->getTagessollSonntag()
                                ];
                            }
                        }
                        $this->smarty_vars['tagessollliste'] = $tagessollliste;
                    }

                    // get data for tagessoll
                    if ($this->company != 'tps') {
                        if ($this->current_user->getUsergroup()->hasRight('arbeitszeitkonto')) {
                            $arbeitszeitkontoliste = [];
                            $arbeitszeitkonto_models = \ttact\Models\ArbeitszeitkontoModel::findByMitarbeiter($this->db, $mitarbeiter->getID());
                            foreach ($arbeitszeitkonto_models as $arbeitszeitkonto_model) {
                                $arbeitszeitkontoliste[] = [
                                    'id' => $arbeitszeitkonto_model->getID(),
                                    'jahr' => $arbeitszeitkonto_model->getJahr(),
                                    'monat' => $arbeitszeitkonto_model->getMonat(),
                                    'stunden' => $arbeitszeitkonto_model->getStunden()
                                ];
                            }
                            $this->smarty_vars['arbeitszeitkontoliste'] = $arbeitszeitkontoliste;
                        }
                    }

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

                    // get data for Abteilungen
                    $abteilungen = \ttact\Models\AbteilungModel::findAll($this->db);
                    $abteilungsliste = [];
                    foreach ($abteilungen as $a) {
                        $abteilungsliste[$a->getID()] = [
                            'id' => $a->getID(),
                            'bezeichnung' => $a->getBezeichnung()
                        ];
                    }
                    ksort($abteilungsliste);
                    $this->smarty_vars['abteilungsliste'] = $abteilungsliste;

                    // get data for Lohnbuchungen
                    if ($this->current_user->getUsergroup()->hasRight('lohnbuchungen')) {
                        $lohnbuchungen = \ttact\Models\LohnbuchungModel::findByMitarbeiter($this->db, $mitarbeiter->getID());
                        $lohnbuchungsliste = [];
                        foreach ($lohnbuchungen as $lohnbuchung) {
                            $buchungsmonat = '';
                            if ($lohnbuchung->getDatum() instanceof \DateTime) {
                                $buchungsmonat = $lohnbuchung->getDatum()->format("Y-m");
                            }
                            $benutzer = '';
                            if ($lohnbuchung->getUser() instanceof \ttact\Models\UserModel) {
                                $benutzer = $lohnbuchung->getUser()->getName();
                            }
                            $zeit = '';
                            if ($lohnbuchung->getZeitpunkt() instanceof \DateTime) {
                                $zeit = $lohnbuchung->getZeitpunkt()->format("d.m.Y") . " um " . $lohnbuchung->getZeitpunkt()->format("H:i") . " Uhr";
                            }

                            $lohnbuchungsliste[] = ['id' => $lohnbuchung->getID(), 'buchungsmonat' => $buchungsmonat, 'lohnart' => $lohnbuchung->getLohnart(), 'wert' => $lohnbuchung->getWert(), 'faktor' => $lohnbuchung->getFaktor(), 'bezeichnung' => $lohnbuchung->getBezeichnung(), 'benutzer' => $benutzer, 'zeit' => $zeit];
                        }
                        $this->smarty_vars['lohnbuchungsliste'] = $lohnbuchungsliste;
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

    public function kalender()
    {
        $redirect = true;

        if (isset($this->params[0])) {
            if ($this->user_input->isPositiveInteger($this->params[0])) {
                $mitarbeiter = \ttact\Models\MitarbeiterModel::findByPersonalnummer($this->db, $this->user_input->getOnlyNumbers($this->params[0]));
                if ($mitarbeiter instanceof \ttact\Models\MitarbeiterModel) {
                    $redirect = false;

                    $date = new \DateTime('now');
                    $date->setDate((int) $date->format("Y"), (int) $date->format("m"), 1);
                    $date->setTime(0, 0, 0);

                    if (isset($this->params[1]) && isset($this->params[2])) {
                        $year = (int) $this->params[1];
                        $month = (int) $this->params[2];

                        if ($year >= 1000 && $year <= 9999 && $month >= 1 && $month <= 12) {
                            $date->setDate($year, $month, 1);
                        }
                    }

                    $this->smarty_vars['values'] = [
                        'personalnummer' => $mitarbeiter->getPersonalnummer(),
                        'vorname' => $mitarbeiter->getVorname(),
                        'nachname' => $mitarbeiter->getNachname(),
                        'year' => $date->format("Y"),
                        'month' => $date->format("m")
                    ];

                    $mitarbeiterliste = [];
                    $alle_mitarbeiter = \ttact\Models\MitarbeiterModel::findAll($this->db);
                    foreach ($alle_mitarbeiter as $m) {
                        $mitarbeiterliste[] = [
                            'personalnummer' => $m->getPersonalnummer(),
                            'vorname' => $m->getVorname(),
                            'nachname' => $m->getNachname()
                        ];
                    }
                    $this->smarty_vars['mitarbeiterliste'] = $mitarbeiterliste;

                    $kundenliste = [];
                    $alle_kunden = \ttact\Models\KundeModel::findAll($this->db);
                    foreach ($alle_kunden as $k) {
                        $kundenliste[] = [
                            'id' => $k->getID(),
                            'kundennummer' => $k->getKundennummer(),
                            'name' => $k->getName()
                        ];
                    }
                    $this->smarty_vars['kundenliste'] = $kundenliste;

                    // template settings
                    $this->template = 'main';
                }
            }
        }

        if ($redirect) {
            $this->misc_utils->redirect('mitarbeiter', 'index', 'fehler');
        }
    }

    public function ajax()
    {
        if ($this->user_input->getPostParameter('type') == 'get') {
            $mitarbeiter_id = (int) $this->user_input->getOnlyNumbers($this->user_input->getPostParameter('mitarbeiter'));
            $mitarbeiter_model = \ttact\Models\MitarbeiterModel::findByPersonalnummer($this->db, $mitarbeiter_id);
            if ($mitarbeiter_model instanceof \ttact\Models\MitarbeiterModel) {
                $von = new \DateTime($this->user_input->getPostParameter('start') . " 00:00:00");
                $bis = new \DateTime($this->user_input->getPostParameter('end') . " 00:00:00");
                if ($von instanceof \DateTime && $bis instanceof \DateTime) {
                    // Kalendereinträge
                    $kalendereintraege = \ttact\Models\KalendereintragModel::findByStartEndMitarbeiter($this->db, $von, $bis, $mitarbeiter_model->getID());
                    foreach ($kalendereintraege as $kalendereintrag) {
                        $start = $kalendereintrag->getVon();
                        $end = $kalendereintrag->getBis();
                        $end->add(new \DateInterval("P0000-00-01T00:00:00"));
                        $end->setTime(0, 0, 0);

                        $color = '';
                        $title = '';

                        switch ($kalendereintrag->getType()) {
                            case "krank_bezahlt":
                                $color = 'red';
                                $title = 'Krank';
                                break;
                            case "urlaub_bezahlt":
                                $color = 'orange';
                                $title = 'Urlaub';
                                break;
                            case "kind_krank":
                                $color = 'red';
                                $title = 'Kind krank';
                                break;
                            case "weiterbildung":
                                $color = 'orange';
                                $title = 'Weiterbildung';
                                break;
                            case "frei":
                                $color = 'blue';
                                $title = 'Frei';
                                break;
                            case "krank_unbezahlt":
                                $color = '#CC00CC';
                                $title = 'Krank ohne Bezahlung';
                                break;
                            case "unentschuldigt_fehlen":
                                $color = 'red';
                                $title = 'unentschuldigt fehlen';
                                break;
                            case "feiertag_bezahlt":
                                $color = 'blue';
                                $title = 'Feiertag';
                                break;
                            case "fehlzeit":
                                $color = '#99FF00';
                                $title = 'Fehlzeit';
                                break;
                            case "unbekannt":
                                $color = '#333333';
                                $title = 'unbekannt';
                                break;
                            case "urlaub_genehmigt":
                                $color = '#330033';
                                $title = 'Urlaub genehmigt';
                                break;
                            case "krank":
                                $color = '#0CD';
                                $title = 'Krank ohne LFZ';
                                break;
                            case "urlaub_unbezahlt":
                                $color = '#0CD';
                                $title = 'unbezahlter Urlaub';
                                break;
                            case "benachbarten_feiertag_bezahlen":
                                $color = 'black';
                                $title = 'Benachb. Feiert. bez.';
                                break;
                            default:
                                $color = 'darkblue';
                                $title = 'Ungültige Kategorie';
                                break;
                        }

                        $this->smarty_vars['data'][] = [
                            'id' => $kalendereintrag->getID(),
                            'von' => $kalendereintrag->getVon()->format("d.m.Y"),
                            'bis' => $kalendereintrag->getBis()->format("d.m.Y"),
                            'type' => $kalendereintrag->getType(),
                            'bezeichnung' => $kalendereintrag->getTitel(),
                            'title' => $title,
                            'allDay' => true,
                            'start' => $start->format("Y-m-d") . "T" . $start->format("H:i:s"),
                            'end' => $end->format("Y-m-d") . "T" . $end->format("H:i:s"),
                            'textColor' => 'white',
                            'backgroundColor' => $color,
                            'borderColor' => $color
                        ];
                    }
                    // Stundenimporteinträge
                    $stundenimporteintrage = \ttact\Models\StundenimporteintragModel::findByStartEndMitarbeiter($this->db, $von, $bis, $mitarbeiter_model->getID());
                    foreach ($stundenimporteintrage as $stundenimporteintrag) {
                        $this->smarty_vars['data'][] = [
                            'id' => $stundenimporteintrag->getID(),
                            'datum' => $stundenimporteintrag->getVon()->format("d.m.Y"),
                            'von_uhrzeit' => $stundenimporteintrag->getVon()->format("H:i"),
                            'bis_uhrzeit' => $stundenimporteintrag->getBis()->format("H:i"),
                            'pause' => $stundenimporteintrag->getPause()->format("%H:%I"),
                            'kunde' => $stundenimporteintrag->getKunde()->getID(),
                            'type' => 'stundenimporteintrag',

                            'title' => 'Import: Kunde ' . $stundenimporteintrag->getKunde()->getKundennummer(),
                            'start' => $stundenimporteintrag->getVon()->format("Y-m-d") . "T" . $stundenimporteintrag->getVon()->format("H:i:s"),
                            'end' => $stundenimporteintrag->getBis()->format("Y-m-d") . "T" . $stundenimporteintrag->getBis()->format("H:i:s"),
                            'textColor' => 'white',
                            'backgroundColor' => 'black',
                            'borderColor' => 'black'
                        ];
                    }
                    // Schichten
                    $schichten = \ttact\Models\AuftragModel::findByStartEndMitarbeiter($this->db, $this->current_user, $von, $bis, $mitarbeiter_model->getID());
                    foreach ($schichten as $schicht) {
                        $start = $schicht->getVon();
                        $end = $schicht->getBis();

                        $this->smarty_vars['data'][] = [
                            'title' => 'Kunde ' . $schicht->getKunde()->getKundennummer(),
                            'start' => $start->format("Y-m-d") . "T" . $start->format("H:i:s"),
                            'end' => $end->format("Y-m-d") . "T" . $end->format("H:i:s"),
                            'textColor' => 'white',
                            'backgroundColor' => 'green',
                            'borderColor' => 'green',
                            'url' => '/schichten/planer/' . $schicht->getVon()->format("Y") . '/' . $schicht->getVon()->format("W") . '/' . $schicht->getKunde()->getKundennummer()
                        ];
                    }
                    $this->smarty_vars['data']['status'] = 'success';
                    $this->smarty_vars['data']['no_status'] = true;
                } else {
                    $this->smarty_vars['data']['status'] = 'error';
                }
            } else {
                $this->smarty_vars['data']['status'] = 'error';
            }
        } elseif ($this->user_input->getPostParameter('type') == 'set') {
            $error = true;

            $kalendereintrag_id = (int) $this->user_input->getOnlyNumbers($this->user_input->getPostParameter("id"));
            $kalendereintrag = \ttact\Models\KalendereintragModel::findByID($this->db, $kalendereintrag_id);
            if ($kalendereintrag instanceof \ttact\Models\KalendereintragModel) {
                $art_is_valid = false;

                switch ($this->user_input->getPostParameter('art')) {
                    case "krank_bezahlt":
                    case "urlaub_bezahlt":
                    case "kind_krank":
                    case "weiterbildung":
                    case "frei":
                    case "krank_unbezahlt":
                    case "unentschuldigt_fehlen":
                    case "feiertag_bezahlt":
                    case "fehlzeit":
                    case "unbekannt":
                    case "urlaub_genehmigt":
                    case "krank":
                    case "urlaub_unbezahlt":
                    case "benachbarten_feiertag_bezahlen":
                        $art_is_valid = true;
                    default:
                        break;
                }

                if ($art_is_valid) {
                    if ($this->user_input->isDate($this->user_input->getPostParameter('von'))) {
                        if ($this->user_input->isDate($this->user_input->getPostParameter('bis'))) {
                            $von = \DateTime::createFromFormat("d.m.Y", $this->user_input->getPostParameter('von'));
                            $von->setTime(0, 0, 0);
                            $bis = \DateTime::createFromFormat("d.m.Y", $this->user_input->getPostParameter('bis'));
                            $bis->setTime(0, 0, 0);

                            if ($von instanceof \DateTime && $bis instanceof \DateTime) {
                                if ($von->format("d.m.Y") == $this->user_input->getPostParameter('von') && $bis->format("d.m.Y") == $this->user_input->getPostParameter('bis')) {
                                    if ($von->format("Y-m-d") == $bis->format("Y-m-d") || $von <= $bis) {
                                        if ($kalendereintrag->setVon($von->format("Y-m-d")) && $kalendereintrag->setBis($bis->format("Y-m-d")) && $kalendereintrag->setType($this->user_input->getPostParameter('art')) && $kalendereintrag->setTitel($this->user_input->getPostParameter('bezeichnung'))) {
                                            $error = false;
                                            $this->smarty_vars['data']['status'] = 'success';
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
            }
        } elseif ($this->user_input->getPostParameter('type') == 'set_stundenimporteintrag') {
            $error = true;

            $stundenimporteintrag_id = $this->user_input->getOnlyNumbers($this->user_input->getPostParameter('id'));
            $stundenimporteintrag = \ttact\Models\StundenimporteintragModel::findByID($this->db, $stundenimporteintrag_id);
            if ($stundenimporteintrag instanceof \ttact\Models\StundenimporteintragModel) {
                $kundennummer = (int) $this->user_input->getOnlyNumbers($this->user_input->getPostParameter('kunde'));
                $kunde = \ttact\Models\KundeModel::findByID($this->db, $kundennummer);
                if ($kunde instanceof \ttact\Models\KundeModel) {
                    if ($this->user_input->isDate($this->user_input->getPostParameter('datum'))) {
                        $datum = \DateTime::createFromFormat("d.m.Y", $this->user_input->getPostParameter('datum'));
                        $datum->setTime(0, 0, 0);

                        if ($datum instanceof \DateTime) {
                            if ($datum->format("d.m.Y") == $this->user_input->getPostParameter('datum')) {
                                $von = $this->user_input->getOnlyNumbers($this->user_input->getPostParameter("von"));
                                $bis = $this->user_input->getOnlyNumbers($this->user_input->getPostParameter("bis"));
                                if (strlen($von) == 4 && preg_match('/^[0-2][0-9][0-5][0-9]/', $von) && strlen($bis) == 4 && preg_match('/^[0-2][0-9][0-5][0-9]/', $bis)) {
                                    $save_von = new \DateTime($datum->format("Y-m-d") . " " . substr($von, 0, 2) . ":" . substr($von, 2, 2) . ":00");
                                    $save_bis = new \DateTime($datum->format("Y-m-d") . " " . substr($bis, 0, 2) . ":" . substr($bis, 2, 2) . ":00");
                                    if ($save_von instanceof \DateTime && $save_bis instanceof \DateTime) {
                                        if ($save_bis < $save_von) {
                                            $save_bis->add(new \DateInterval("P0000-00-01T00:00:00"));
                                        }

                                        $pause = $this->user_input->getOnlyNumbers($this->user_input->getPostParameter("pause"));
                                        if (strlen($pause) == 4 && preg_match('/^[0-9][0-9][0-5][0-9]/', $pause)) {
                                            $save_pause = new \DateInterval("P0000-00-00T" . substr($pause, 0, 2) . ":" . substr($pause, 2, 2) . ":00");

                                            if ($save_pause instanceof \DateInterval) {
                                                if ($stundenimporteintrag->setKundeID($kunde->getID()) && $stundenimporteintrag->setVon($save_von->format("Y-m-d H:i:s")) && $stundenimporteintrag->setBis($save_bis->format("Y-m-d H:i:s")) && $stundenimporteintrag->setPause($save_pause->format("%H:%I") . ":00")) {
                                                    $error = false;
                                                    $this->smarty_vars['data']['status'] = 'success';
                                                }
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
            }
        } elseif ($this->user_input->getPostParameter('type') == 'new') {
            $error = true;

            $mitarbeiter_personalnummer = (int) $this->user_input->getOnlyNumbers($this->user_input->getPostParameter("mitarbeiter"));
            $mitarbeiter = \ttact\Models\MitarbeiterModel::findByPersonalnummer($this->db, $mitarbeiter_personalnummer);
            if ($mitarbeiter instanceof \ttact\Models\MitarbeiterModel) {
                $art_is_valid = false;

                switch ($this->user_input->getPostParameter('art')) {
                    case "krank_bezahlt":
                    case "urlaub_bezahlt":
                    case "kind_krank":
                    case "weiterbildung":
                    case "frei":
                    case "krank_unbezahlt":
                    case "unentschuldigt_fehlen":
                    case "feiertag_bezahlt":
                    case "fehlzeit":
                    case "unbekannt":
                    case "urlaub_genehmigt":
                    case "krank":
                    case "urlaub_unbezahlt":
                    case "benachbarten_feiertag_bezahlen":
                        $art_is_valid = true;
                    default:
                        break;
                }

                if ($art_is_valid) {
                    if ($this->user_input->isDate($this->user_input->getPostParameter('von'))) {
                        if ($this->user_input->isDate($this->user_input->getPostParameter('bis'))) {
                            $von = \DateTime::createFromFormat("d.m.Y", $this->user_input->getPostParameter('von'));
                            $von->setTime(0, 0, 0);
                            $bis = \DateTime::createFromFormat("d.m.Y", $this->user_input->getPostParameter('bis'));
                            $bis->setTime(0, 0, 0);

                            if ($von instanceof \DateTime && $bis instanceof \DateTime) {
                                if ($von->format("d.m.Y") == $this->user_input->getPostParameter('von') && $bis->format("d.m.Y") == $this->user_input->getPostParameter('bis')) {
                                    if ($von->format("Y-m-d") == $bis->format("Y-m-d") || $von <= $bis) {
                                        $data = [
                                            'mitarbeiter_id' => $mitarbeiter->getID(),
                                            'von' => $von->format("Y-m-d"),
                                            'bis' => $bis->format("Y-m-d"),
                                            'titel' => trim($this->user_input->getPostParameter('bezeichnung')),
                                            'type' => $this->user_input->getPostParameter('art')
                                        ];

                                        $kalendereintrag_model = \ttact\Models\KalendereintragModel::createNew($this->db, $data);
                                        if ($kalendereintrag_model instanceof \ttact\Models\KalendereintragModel) {
                                            $error = false;
                                            $this->smarty_vars['data']['status'] = 'success';
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
            }
        } elseif ($this->user_input->getPostParameter('type') == 'new_stundenimporteintrag') {
            $error = true;

            $mitarbeiter_personalnummer = (int) $this->user_input->getOnlyNumbers($this->user_input->getPostParameter("mitarbeiter"));
            $mitarbeiter = \ttact\Models\MitarbeiterModel::findByPersonalnummer($this->db, $mitarbeiter_personalnummer);
            if ($mitarbeiter instanceof \ttact\Models\MitarbeiterModel) {
                $kundennummer = (int) $this->user_input->getOnlyNumbers($this->user_input->getPostParameter('kunde'));
                $kunde = \ttact\Models\KundeModel::findByID($this->db, $kundennummer);
                if ($kunde instanceof \ttact\Models\KundeModel) {
                    if ($this->user_input->isDate($this->user_input->getPostParameter('datum'))) {
                        $datum = \DateTime::createFromFormat("d.m.Y", $this->user_input->getPostParameter('datum'));
                        $datum->setTime(0, 0, 0);

                        if ($datum instanceof \DateTime) {
                            if ($datum->format("d.m.Y") == $this->user_input->getPostParameter('datum')) {
                                $von = $this->user_input->getOnlyNumbers($this->user_input->getPostParameter("von"));
                                $bis = $this->user_input->getOnlyNumbers($this->user_input->getPostParameter("bis"));
                                if (strlen($von) == 4 && preg_match('/^[0-2][0-9][0-5][0-9]/', $von) && strlen($bis) == 4 && preg_match('/^[0-2][0-9][0-5][0-9]/', $bis)) {
                                    $save_von = new \DateTime($datum->format("Y-m-d") . " " . substr($von, 0, 2) . ":" . substr($von, 2, 2) . ":00");
                                    $save_bis = new \DateTime($datum->format("Y-m-d") . " " . substr($bis, 0, 2) . ":" . substr($bis, 2, 2) . ":00");
                                    if ($save_von instanceof \DateTime && $save_bis instanceof \DateTime) {
                                        if ($save_bis < $save_von) {
                                            $save_bis->add(new \DateInterval("P0000-00-01T00:00:00"));
                                        }

                                        $pause = $this->user_input->getOnlyNumbers($this->user_input->getPostParameter("pause"));
                                        if (strlen($pause) == 4 && preg_match('/^[0-9][0-9][0-5][0-9]/', $pause)) {
                                            $save_pause = new \DateInterval("P0000-00-00T" . substr($pause, 0, 2) . ":" . substr($pause, 2, 2) . ":00");

                                            if ($save_pause instanceof \DateInterval) {
                                                $data = [
                                                    'mitarbeiter_id' => $mitarbeiter->getID(),
                                                    'kunde_id' => $kunde->getID(),
                                                    'von' => $save_von->format("Y-m-d H:i:s"),
                                                    'bis' => $save_bis->format("Y-m-d H:i:s"),
                                                    'pause' => $save_pause->format("%H:%I")
                                                ];

                                                $stundenimporteintrag = \ttact\Models\StundenimporteintragModel::createNew($this->db, $data);
                                                if ($stundenimporteintrag instanceof \ttact\Models\StundenimporteintragModel) {
                                                    $error = false;
                                                    $this->smarty_vars['data']['status'] = 'success';
                                                }
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
            }
        } elseif ($this->user_input->getPostParameter('type') == 'delete') {
            $kalendereintrag_id = (int) $this->user_input->getOnlyNumbers($this->user_input->getPostParameter("id"));
            $kalendereintrag = \ttact\Models\KalendereintragModel::findByID($this->db, $kalendereintrag_id);
            if ($kalendereintrag instanceof \ttact\Models\KalendereintragModel) {
                if ($kalendereintrag->delete()) {
                    $this->smarty_vars['data']['status'] = 'success';
                } else {
                    $this->smarty_vars['data']['status'] = 'error';
                }
            }
        } elseif ($this->user_input->getPostParameter('type') == 'delete_stundenimporteintrag') {
            $stundenimporteintrag_id = (int) $this->user_input->getOnlyNumbers($this->user_input->getPostParameter("id"));
            $stundenimporteintrag = \ttact\Models\StundenimporteintragModel::findByID($this->db, $stundenimporteintrag_id);
            if ($stundenimporteintrag instanceof \ttact\Models\StundenimporteintragModel) {
                if ($stundenimporteintrag->delete()) {
                    $this->smarty_vars['data']['status'] = 'success';
                } else {
                    $this->smarty_vars['data']['status'] = 'error';
                }
            }
        } elseif ($this->user_input->getPostParameter('type') == 'get_tagessoll') {
            $error = true;

            $mitarbeiter_id = (int) $this->user_input->getOnlyNumbers($this->user_input->getPostParameter('mitarbeiter'));
            $mitarbeiter_model = \ttact\Models\MitarbeiterModel::findByPersonalnummer($this->db, $mitarbeiter_id);
            if ($mitarbeiter_model instanceof \ttact\Models\MitarbeiterModel) {
                $jahr = (int) $this->user_input->getOnlyNumbers($this->user_input->getPostParameter('jahr'));
                $monat = (int) $this->user_input->getOnlyNumbers($this->user_input->getPostParameter('monat'));

                if ($jahr >= 1000 && $jahr <= 9999 && $monat >= 1 && $monat <= 12) {
                    // Allgemein
                    $this->smarty_vars['data']['allgemein']['eintritt'] = $mitarbeiter_model->getEintritt() instanceof \DateTime ? $mitarbeiter_model->getEintritt()->format("d.m.Y") : '--';
                    $this->smarty_vars['data']['allgemein']['austritt'] = $mitarbeiter_model->getAustritt() instanceof \DateTime ? $mitarbeiter_model->getAustritt()->format("d.m.Y") : '--';
                    $monatsanfang = new \DateTime("now");
                    $monatsanfang->setDate($jahr, $monat, 1);
                    $monatsanfang->setTime(0, 0, 0);
                    $lohnkonfiguration = \ttact\Models\LohnkonfigurationModel::findForMitarbeiterDate($this->db, $mitarbeiter_model->getID(), $monatsanfang);
                    $wochenstunden = 0;
                    if ($lohnkonfiguration instanceof \ttact\Models\LohnkonfigurationModel) {
                        $this->smarty_vars['data']['allgemein']['wochenstunden'] = number_format((float) $lohnkonfiguration->getWochenstunden(), 2, ',', '.');
                        $wochenstunden = $lohnkonfiguration->getWochenstunden();
                    } else {
                        $this->smarty_vars['data']['allgemein']['wochenstunden'] = '--';
                    }
                    $wochenstunden_tagessoll = $wochenstunden / 6;

                    // Prev & Next Mitarbeiter
                    $this->smarty_vars['data']['mitarbeiter']['prev'] = $mitarbeiter_model->getPersonalnummer();
                    $this->smarty_vars['data']['mitarbeiter']['next'] = $mitarbeiter_model->getPersonalnummer();
                    $prev = \ttact\Models\MitarbeiterModel::findPrevLohnberechnung($this->db, $mitarbeiter_model->getPersonalnummer(), $monatsanfang);
                    if ($prev instanceof \ttact\Models\MitarbeiterModel) {
                        $this->smarty_vars['data']['mitarbeiter']['prev'] = $prev->getPersonalnummer();
                    }
                    $next = \ttact\Models\MitarbeiterModel::findNextLohnberechnung($this->db, $mitarbeiter_model->getPersonalnummer(), $monatsanfang);
                    if ($next instanceof \ttact\Models\MitarbeiterModel) {
                        $this->smarty_vars['data']['mitarbeiter']['next'] = $next->getPersonalnummer();
                    }

                    // Tagessoll
                    $tagessoll_model = \ttact\Models\TagessollModel::findByJahrMonatMitarbeiter($this->db, $jahr, $monat, $mitarbeiter_model->getID());
                    $tagessoll = 0;
                    if ($this->company != 'tps') {
                        $wochentagssoll = [
                            1 => 0,
                            2 => 0,
                            3 => 0,
                            4 => 0,
                            5 => 0,
                            6 => 0,
                            7 => 0
                        ];
                    }
                    if ($tagessoll_model instanceof \ttact\Models\TagessollModel) {
                        $this->smarty_vars['data']['tagessoll']['alle'] = number_format($tagessoll_model->getTagessoll(), 2, ',', '.');
                        if ($this->company != 'tps') {
                            $this->smarty_vars['data']['tagessoll']['montag'] = number_format($tagessoll_model->getTagessollMontag(), 2, ',', '.');
                            $this->smarty_vars['data']['tagessoll']['dienstag'] = number_format($tagessoll_model->getTagessollDienstag(), 2, ',', '.');
                            $this->smarty_vars['data']['tagessoll']['mittwoch'] = number_format($tagessoll_model->getTagessollMittwoch(), 2, ',', '.');
                            $this->smarty_vars['data']['tagessoll']['donnerstag'] = number_format($tagessoll_model->getTagessollDonnerstag(), 2, ',', '.');
                            $this->smarty_vars['data']['tagessoll']['freitag'] = number_format($tagessoll_model->getTagessollFreitag(), 2, ',', '.');
                            $this->smarty_vars['data']['tagessoll']['samstag'] = number_format($tagessoll_model->getTagessollSamstag(), 2, ',', '.');
                            $this->smarty_vars['data']['tagessoll']['sonntag'] = number_format($tagessoll_model->getTagessollSonntag(), 2, ',', '.');
                        }
                        $tagessoll = $tagessoll_model->getTagessoll();
                        if ($this->company != 'tps') {
                            $wochentagssoll = [
                                1 => $tagessoll_model->getTagessollMontag(),
                                2 => $tagessoll_model->getTagessollDienstag(),
                                3 => $tagessoll_model->getTagessollMittwoch(),
                                4 => $tagessoll_model->getTagessollDonnerstag(),
                                5 => $tagessoll_model->getTagessollFreitag(),
                                6 => $tagessoll_model->getTagessollSamstag(),
                                7 => $tagessoll_model->getTagessollSonntag()
                            ];
                        }
                    } else {
                        $this->smarty_vars['data']['tagessoll']['alle'] = '--';
                        if ($this->company != 'tps') {
                            $this->smarty_vars['data']['tagessoll']['montag'] = '--';
                            $this->smarty_vars['data']['tagessoll']['dienstag'] = '--';
                            $this->smarty_vars['data']['tagessoll']['mittwoch'] = '--';
                            $this->smarty_vars['data']['tagessoll']['donnerstag'] = '--';
                            $this->smarty_vars['data']['tagessoll']['freitag'] = '--';
                            $this->smarty_vars['data']['tagessoll']['samstag'] = '--';
                            $this->smarty_vars['data']['tagessoll']['sonntag'] = '--';
                        }
                    }

                    if ($this->company != 'tps') {
                        // AZK dieser Monat
                        $azk_dieser_monat_model = \ttact\Models\ArbeitszeitkontoModel::findByYearMonthMitarbeiter($this->db, $jahr, $monat, $mitarbeiter_model->getID());
                        if ($azk_dieser_monat_model instanceof \ttact\Models\ArbeitszeitkontoModel) {
                            $this->smarty_vars['data']['azk']['dieser_monat'] = number_format($azk_dieser_monat_model->getStunden(), 2, ',', '.');
                        } else {
                            $this->smarty_vars['data']['azk']['dieser_monat'] = '--';
                        }

                        // AZK letzter Monat
                        $letzter_monat = new \DateTime("now");
                        $letzter_monat->setDate($jahr, $monat, 1);
                        $letzter_monat->sub(new \DateInterval("P0000-01-00T00:00:00"));
                        $azk_letzter_monat_model = \ttact\Models\ArbeitszeitkontoModel::findByYearMonthMitarbeiter($this->db, (int) $letzter_monat->format("Y"), (int) $letzter_monat->format("m"), $mitarbeiter_model->getID());
                        if ($azk_letzter_monat_model instanceof \ttact\Models\ArbeitszeitkontoModel) {
                            $this->smarty_vars['data']['azk']['letzter_monat'] = number_format($azk_letzter_monat_model->getStunden(), 2, ',', '.');
                        } else {
                            $this->smarty_vars['data']['azk']['letzter_monat'] = '--';
                        }
                    }

                    // Stunden Schichten
                    $stunden_schichten = 0;

                    $monatsende = clone $monatsanfang;
                    $monatsende->setDate((int) $monatsende->format("Y"), (int) $monatsende->format("m"), (int) $monatsende->format("t"));
                    $monatsende->setTime(23, 59, 59);
                    $schichten = \ttact\Models\AuftragModel::findByStartEndMitarbeiterLohnberechnung($this->db, $this->current_user, $monatsanfang, $monatsende, $mitarbeiter_model->getID());
                    foreach ($schichten as $schicht) {
                        $stunden_schichten += $schicht->getHours();
                    }
                    $this->smarty_vars['data']['stunden']['schichten'] = number_format($stunden_schichten, 2, ',', '.');

                    // Stunden Import
                    $stunden_import = 0;

                    $monatsende = clone $monatsanfang;
                    $monatsende->setDate((int) $monatsende->format("Y"), (int) $monatsende->format("m"), (int) $monatsende->format("t"));
                    $monatsende->setTime(23, 59, 59);
                    $stundenimporteintraege = \ttact\Models\StundenimporteintragModel::findByStartEndMitarbeiter($this->db, $monatsanfang, $monatsende, $mitarbeiter_model->getID());
                    foreach ($stundenimporteintraege as $stundenimporteintrag) {
                        $stunden_import += $stundenimporteintrag->getSeconds() / 3600;
                    }
                    $this->smarty_vars['data']['stunden']['import'] = number_format($stunden_import, 2, ',', '.');

                    // Stunden Urlaub
                    $urlaubsstunden = 0;
                    $urlaubskalendereintraege = \ttact\Models\KalendereintragModel::findByStartEndTypeMitarbeiter($this->db, $monatsanfang, $monatsende, $mitarbeiter_model->getID(), 'urlaub_bezahlt');
                    $monat_bis = clone $monatsanfang;
                    $monat_bis->add(new \DateInterval("P0000-01-00T00:00:00"));
                    $period_monat = new Period($monatsanfang, $monat_bis);
                    foreach ($urlaubskalendereintraege as $kalendereintrag) {
                        $urlaub_bis = $kalendereintrag->getBis();
                        $urlaub_bis->add(new \DateInterval("P0000-00-01T00:00:00"));
                        $urlaub_bis->setTime(0, 0, 0);
                        $period_urlaubskalendereintrag = new Period($kalendereintrag->getVon(), $urlaub_bis);

                        if ($period_monat->overlaps($period_urlaubskalendereintrag)) {
                            $period_urlaub = $period_monat->intersect($period_urlaubskalendereintrag);
                            foreach ($period_urlaub->getDatePeriod(new \DateInterval("P0000-00-01T00:00:00")) as $day) {
                                if ($day->format("N") != 7) {
                                    if ($this->company == 'tps') {
                                        $urlaubsstunden += $wochenstunden_tagessoll;
                                    } else {
                                        $urlaubsstunden += $wochenstunden_tagessoll > $tagessoll ? $wochenstunden_tagessoll : $tagessoll;
                                    }
                                }
                            }
                        }
                    }
                    if ($tagessoll_model instanceof \ttact\Models\TagessollModel) {
                        $this->smarty_vars['data']['stunden']['urlaub'] = number_format($urlaubsstunden, 2, ',', '.');
                    } else {
                        $this->smarty_vars['data']['stunden']['urlaub'] = '--';
                    }

                    // Stunden Krank
                    $lohnfortzahlungsstunden = 0;
                    $krankheitskalendereintraege = \ttact\Models\KalendereintragModel::findByStartEndTypeMitarbeiter($this->db, $monatsanfang, $monatsende, $mitarbeiter_model->getID(), 'krank_bezahlt');
                    foreach ($krankheitskalendereintraege as $kalendereintrag) {
                        $krank_bis = $kalendereintrag->getBis();
                        $krank_bis->add(new \DateInterval("P0000-00-01T00:00:00"));
                        $krank_bis->setTime(0, 0, 0);
                        $period_krankheitskalendereintrag = new Period($kalendereintrag->getVon(), $krank_bis);

                        if ($period_monat->overlaps($period_krankheitskalendereintrag)) {
                            $period_krank = $period_monat->intersect($period_krankheitskalendereintrag);
                            foreach ($period_krank->getDatePeriod(new \DateInterval("P0000-00-01T00:00:00")) as $day) {
                                if ($day->format("N") != 7) {
                                    if ($this->company == 'tps') {
                                        $lohnfortzahlungsstunden += $wochenstunden_tagessoll;
                                    } else {
                                        $lohnfortzahlungsstunden += $wochenstunden_tagessoll > $tagessoll ? $wochenstunden_tagessoll : $tagessoll;
                                    }
                                }
                            }
                        }
                    }
                    if ($tagessoll_model instanceof \ttact\Models\TagessollModel) {
                        $this->smarty_vars['data']['stunden']['krank'] = number_format($lohnfortzahlungsstunden, 2, ',', '.');
                    } else {
                        $this->smarty_vars['data']['stunden']['krank'] = '--';
                    }

                    // Stunden Fehlzeiten
                    if ($this->company != 'tps') {
                        $fehlzeitenstunden = 0;
                        $fehlzeitenkalendereintraege = \ttact\Models\KalendereintragModel::findByStartEndMitarbeiter($this->db, $monatsanfang, $monatsende, $mitarbeiter_model->getID());
                        foreach ($fehlzeitenkalendereintraege as $kalendereintrag) {
                            if ($kalendereintrag->getType() == 'fehlzeit' || $kalendereintrag->getType() == 'kind_krank' || $kalendereintrag->getType() == 'krank' || $kalendereintrag->getType() == 'unentschuldigt_fehlen' || $kalendereintrag->getType() == 'krank_unbezahlt' || $kalendereintrag->getType() == 'urlaub_unbezahlt') {
                                $krank_bis = $kalendereintrag->getBis();
                                $krank_bis->add(new \DateInterval("P0000-00-01T00:00:00"));
                                $krank_bis->setTime(0, 0, 0);
                                $period_krankheitskalendereintrag = new Period($kalendereintrag->getVon(), $krank_bis);

                                if ($period_monat->overlaps($period_krankheitskalendereintrag)) {
                                    $period_krank = $period_monat->intersect($period_krankheitskalendereintrag);
                                    foreach ($period_krank->getDatePeriod(new \DateInterval("P0000-00-01T00:00:00")) as $day) {
                                        if ($day->format("N") != 7) {
                                            $fehlzeitenstunden += $tagessoll;
                                        }
                                    }
                                }
                            }
                        }
                    }

                    // Stunden Feiertag
                    if ($this->company != 'tps') {
                        $feiertagsstunden = 0;
                        $bezahlte_kalendereintraege = \ttact\Models\KalendereintragModel::findByStartEndTypeMitarbeiter($this->db, $monatsanfang, $monatsende, $mitarbeiter_model->getID(), 'feiertag_bezahlt');
                        foreach ($bezahlte_kalendereintraege as $kalendereintrag) {
                            $kalendereintrag_bis = $kalendereintrag->getBis();
                            $kalendereintrag_bis->add(new \DateInterval("P0000-00-01T00:00:00"));
                            $kalendereintrag_bis->setTime(0, 0, 0);
                            $period_kalendereintrag = new Period($kalendereintrag->getVon(), $kalendereintrag_bis);

                            if ($period_monat->overlaps($period_kalendereintrag)) {
                                $period_kalendereintrag_intersection = $period_monat->intersect($period_kalendereintrag);
                                foreach ($period_kalendereintrag_intersection->getDatePeriod(new \DateInterval("P0000-00-01T00:00:00")) as $day) {
                                    if ($day->format("N") != 7) {
                                        $prev_day = new \DateTime($day->format("Y-m-d H:i:s"));
                                        $prev_day->sub(new \DateInterval("P0000-00-01T00:00:00"));
                                        while ($prev_day->format("N") == 7 || count(\ttact\Models\KalendereintragModel::findAllByTypeDateMitarbeiter($this->db, 'feiertag_bezahlt', $prev_day, $mitarbeiter_model->getID())) > 0) {
                                            $prev_day->sub(new \DateInterval("P0000-00-01T00:00:00"));
                                        }
                                        $next_day = new \DateTime($day->format("Y-m-d H:i:s"));
                                        $next_day->add(new \DateInterval("P0000-00-01T00:00:00"));
                                        while ($next_day->format("N") == 7 || count(\ttact\Models\KalendereintragModel::findAllByTypeDateMitarbeiter($this->db, 'feiertag_bezahlt', $next_day, $mitarbeiter_model->getID())) > 0) {
                                            $next_day->add(new \DateInterval("P0000-00-01T00:00:00"));
                                        }

                                        $feiertag_bezahlen = false;

                                        $prev_day_is_UrlaubKrankSchicht = false;
                                        if (count(\ttact\Models\AuftragModel::findByStartEndMitarbeiter($this->db, $this->current_user, new \DateTime($prev_day->format("Y-m-d") . " 00:00:00"), new \DateTime($prev_day->format("Y-m-d") . " 23:59:59"), $mitarbeiter_model->getID())) > 0) {
                                            $prev_day_is_UrlaubKrankSchicht = true;
                                        } elseif (count(\ttact\Models\KalendereintragModel::findAllByTypeDateMitarbeiter($this->db, 'urlaub_bezahlt', $prev_day, $mitarbeiter_model->getID())) > 0) {
                                            $prev_day_is_UrlaubKrankSchicht = true;
                                        } elseif (count(\ttact\Models\KalendereintragModel::findAllByTypeDateMitarbeiter($this->db, 'krank_bezahlt', $prev_day, $mitarbeiter_model->getID())) > 0) {
                                            $prev_day_is_UrlaubKrankSchicht = true;
                                        } elseif (count(\ttact\Models\StundenimporteintragModel::findByStartEndMitarbeiter($this->db, new \DateTime($prev_day->format("Y-m-d") . " 00:00:00"), new \DateTime($prev_day->format("Y-m-d") . " 23:59:59"), $mitarbeiter_model->getID())) > 0) {
                                            $prev_day_is_UrlaubKrankSchicht = true;
                                        } elseif (count(\ttact\Models\KalendereintragModel::findAllByTypeDateMitarbeiter($this->db, 'benachbarten_feiertag_bezahlen', $prev_day, $mitarbeiter_model->getID())) > 0) {
                                            $feiertag_bezahlen = true;
                                        }

                                        $next_day_is_UrlaubKrankSchicht = false;
                                        if (count(\ttact\Models\AuftragModel::findByStartEndMitarbeiter($this->db, $this->current_user, new \DateTime($next_day->format("Y-m-d") . " 00:00:00"), new \DateTime($next_day->format("Y-m-d") . " 23:59:59"), $mitarbeiter_model->getID())) > 0) {
                                            $next_day_is_UrlaubKrankSchicht = true;
                                        } elseif (count(\ttact\Models\KalendereintragModel::findAllByTypeDateMitarbeiter($this->db, 'urlaub_bezahlt', $next_day, $mitarbeiter_model->getID())) > 0) {
                                            $next_day_is_UrlaubKrankSchicht = true;
                                        } elseif (count(\ttact\Models\KalendereintragModel::findAllByTypeDateMitarbeiter($this->db, 'krank_bezahlt', $next_day, $mitarbeiter_model->getID())) > 0) {
                                            $next_day_is_UrlaubKrankSchicht = true;
                                        } elseif (count(\ttact\Models\StundenimporteintragModel::findByStartEndMitarbeiter($this->db, new \DateTime($next_day->format("Y-m-d") . " 00:00:00"), new \DateTime($next_day->format("Y-m-d") . " 23:59:59"), $mitarbeiter_model->getID())) > 0) {
                                            $next_day_is_UrlaubKrankSchicht = true;
                                        } elseif (count(\ttact\Models\KalendereintragModel::findAllByTypeDateMitarbeiter($this->db, 'benachbarten_feiertag_bezahlen', $next_day, $mitarbeiter_model->getID())) > 0) {
                                            $feiertag_bezahlen = true;
                                        }

                                        if ($prev_day_is_UrlaubKrankSchicht && $next_day_is_UrlaubKrankSchicht) {
                                            $feiertag_bezahlen = true;
                                        }

                                        if ($feiertag_bezahlen) {
                                            $feiertagsstunden += $wochentagssoll[$day->format("N")];
                                        }
                                    }
                                }
                            }
                        }
                        if ($tagessoll_model instanceof \ttact\Models\TagessollModel) {
                            $this->smarty_vars['data']['stunden']['feiertag'] = number_format($feiertagsstunden, 2, ',', '.');
                        }
                        else {
                            $this->smarty_vars['data']['stunden']['feiertag'] = '--';
                        }
                    }

                    // Insgesamt
                    $ist = $stunden_schichten + $lohnfortzahlungsstunden + $urlaubsstunden + $stunden_import;
                    if ($this->company != 'tps') {
                        $ist += $feiertagsstunden;
                    }
                    if ($tagessoll_model instanceof \ttact\Models\TagessollModel) {
                        $this->smarty_vars['data']['stunden']['insgesamt'] = number_format($ist, 2, ',', '.');
                    } else {
                        $this->smarty_vars['data']['stunden']['insgesamt'] = '--';
                    }

                    if ($tagessoll_model instanceof \ttact\Models\TagessollModel && $lohnkonfiguration instanceof \ttact\Models\LohnkonfigurationModel) {
                        // Soll
                        $wochenstunden = (float) $lohnkonfiguration->getWochenstunden();

                        $soll = 0;
                        $anteiliger_monat = false;
                        $anteil_anfang = null;
                        $anteil_ende = null;
                        if ($mitarbeiter_model->getEintritt() > $monatsanfang) {
                            $anteiliger_monat = true;
                            $anteil_anfang = $mitarbeiter_model->getEintritt();
                            $anteil_ende = clone $monatsende;
                            if ($mitarbeiter_model->getAustritt() instanceof \DateTime) {
                                if ($mitarbeiter_model->getAustritt() < $monatsende) {
                                    $anteil_ende = $mitarbeiter_model->getAustritt();
                                }
                            }
                        } else {
                            if ($mitarbeiter_model->getAustritt() instanceof \DateTime) {
                                if ($mitarbeiter_model->getAustritt() < $monatsende) {
                                    $anteiliger_monat = true;
                                    $anteil_anfang = clone $monatsanfang;
                                    $anteil_ende = $mitarbeiter_model->getAustritt();
                                }
                            }
                        }
                        if ($anteiliger_monat) {
                            $workdays_total = 0;
                            $workdays = 0;

                            $temp_datetime = clone $monatsanfang;
                            while ($temp_datetime->format("m") == $monatsanfang->format("m")) {
                                if ($temp_datetime->format("N") != 7) {
                                    $workdays_total++;
                                }
                                $temp_datetime->add(new \DateInterval("P0000-00-01T00:00:00"));
                            }
                            $temp_datetime = clone $anteil_anfang;
                            while ($temp_datetime <= $anteil_ende) {
                                if ($temp_datetime->format("N") != 7) {
                                    $workdays++;
                                }
                                $temp_datetime->add(new \DateInterval("P0000-00-01T00:00:00"));
                            }

                            $soll = (($wochenstunden * 4.333) / $workdays_total) * $workdays;
                            if ($this->company != 'tps') {
                                $soll -= $fehlzeitenstunden;
                            }
                        } else {
                            $soll = $wochenstunden * 4.333;
                            if ($this->company != 'tps') {
                                $soll -= $fehlzeitenstunden;
                            }
                        }

                        $this->smarty_vars['data']['stunden']['soll'] = number_format($soll, 2, ',', '.');
                        if ($this->company != 'tps') {
                            $soll_mehrarbeit = $soll * 1.15;
                            $this->smarty_vars['data']['stunden']['mehrarbeit'] = number_format($soll_mehrarbeit, 2, ',', '.');
                        }

                        // Differenz
                        $this->smarty_vars['data']['differenz']['insgesamt'] = number_format($soll - $ist, 2, ',', '.');
                        if ($this->company != 'tps') {
                            $this->smarty_vars['data']['differenz']['mehrarbeit'] = number_format($soll_mehrarbeit - $stunden_schichten, 2, ',', '.');
                        }
                    } else {
                        // Soll
                        $this->smarty_vars['data']['stunden']['soll'] = '--';
                        if ($this->company != 'tps') {
                            $this->smarty_vars['data']['stunden']['mehrarbeit'] = '--';
                        }

                        // Differenz
                        $this->smarty_vars['data']['differenz']['insgesamt'] = '--';
                        if ($this->company != 'tps') {
                            $this->smarty_vars['data']['differenz']['mehrarbeit'] = '--';
                        }
                    }

                    // template settings
                    $this->smarty_vars['data']['status'] = 'success';
                    $error = false;
                }
            }

            if ($error) {
                $this->smarty_vars['data']['status'] = 'error';
            }
        } else {
            $this->smarty_vars['data']['status'] = 'error';
        }

        $this->template = 'ajax';
    }

    public function datenabgleich()
    {
        $error = "";
        $success = "";
        if (isset($this->params[0])) {
            if ($this->params[0] == "erfolgreich") {
                $success = "Der Datenabgleich wurde erfolgreich durchgeführt.";
            }
        }

        if (isset($_FILES['datei'])) {
            if ($_FILES["datei"]["error"] > 0) {
                $error = "Beim Hochladen der Datei ist ein Fehler aufgetreten.";
            } else {
                $extension = strtolower(pathinfo($_FILES['datei']['name'], PATHINFO_EXTENSION));
                if ($extension != 'csv') {
                    $error = "Die hochgeladene Datei ist keine .csv-Datei.";
                } elseif ($_FILES['datei']['size'] > 1024000) {
                    $error = "Die hochgeladene Datei darf nicht größer als 1 MB sein.";
                } else {
                    $file = file($_FILES['datei']['tmp_name']);
                    if (count($file) > 0) {
                        unset($file[0]);
                        foreach ($file as &$row) {
                            $row = utf8_encode($row);
                            $array = explode(';', $row);
                            if (count($array) != 18) {
                                $error = "Etwas stimmt mit der Anzahl der exportierten Spalten nicht.";
                                break;
                            } elseif (!$this->user_input->getOnlyNumbers($array[0]) == $array[0]) {
                                $error = "Etwas stimmt mit der Datei nicht: die Personalnummer beinhaltet auch nichtnumerische Zeichen.";
                                break;
                            } else {
                                foreach ($array as &$col) {
                                    $col = filter_var(trim($col, " \t\n\r\0\x0B "), FILTER_SANITIZE_STRING);
                                }

                                $mitarbeiter = \ttact\Models\MitarbeiterModel::findByPersonalnummer($this->db, (int) $this->user_input->getOnlyNumbers($array[0]));
                                if ($mitarbeiter instanceof \ttact\Models\MitarbeiterModel) {
                                    // Anrede
                                    if ($array[1] == 'Herrn') {
                                        $mitarbeiter->setGeschlecht('männlich');
                                    } elseif ($array[1] == 'Frau') {
                                        $mitarbeiter->setGeschlecht('weiblich');
                                    }
                                    // Vorname
                                    $mitarbeiter->setVorname($array[2]);
                                    // Nachname
                                    $mitarbeiter->setNachname($array[3]);
                                    // Strasse
                                    $mitarbeiter->setStrasse($array[4]);
                                    // Hausnummer
                                    $mitarbeiter->setHausnummer($array[5]);
                                    // Adresszusatz
                                    $mitarbeiter->setAdresszusatz($array[6]);
                                    // PLZ
                                    $this->user_input->getOnlyNumbers($mitarbeiter->setPostleitzahl($array[7]));
                                    // Ort
                                    $mitarbeiter->setOrt($array[8]);
                                    // Geburtsdatum
                                    $geburtsdatum = \DateTime::createFromFormat('d.m.Y', $array[9]);
                                    if ($geburtsdatum instanceof \DateTime) {
                                        $mitarbeiter->setGeburtsdatum($geburtsdatum->format("Y-m-d"));
                                    } else {
                                        $mitarbeiter->setGeburtsdatum('0000-00-00');
                                    }
                                    // IBAN
                                    $mitarbeiter->setIBAN($array[10]);
                                    // BIC
                                    $mitarbeiter->setBIC($array[11]);
                                    // Abw. Kontoinhaber
                                    $mitarbeiter->setAbweichenderKontoinhaber($array[12]);
                                    // Eintritt
                                    $eintritt = \DateTime::createFromFormat('d.m.Y', $array[13]);
                                    if ($eintritt instanceof \DateTime) {
                                        $mitarbeiter->setEintritt($eintritt->format("Y-m-d"));
                                    } else {
                                        $mitarbeiter->setEintritt('0000-00-00');
                                    }
                                    // Austritt
                                    /*$austritt = \DateTime::createFromFormat('d.m.Y', $array[14]);
                                    if ($austritt instanceof \DateTime) {
                                        $mitarbeiter->setAustritt($austritt->format("Y-m-d"));
                                    } else {
                                        $mitarbeiter->setAustritt('0000-00-00');
                                    }*/
                                    // Jahresurlaub
                                    $mitarbeiter->setJahresurlaub((float) str_replace(',', '.', $array[15]));
                                    // Resturlaub Vorjahr
                                    $mitarbeiter->setResturlaubVorjahr((float) str_replace(',', '.', $array[16]));
                                    // Sozialversicherungsnummer
                                    $mitarbeiter->setSozialversicherungsnummer($array[17]);
                                } else {
                                    $error .= "Die Agenda-Personalnummer <kbd>" . $this->user_input->getOnlyNumbers($array[0]) . "</kbd> existiert nicht in der Datenbank.<br>";
                                }
                            }
                        }
                    } else {
                        $error = "Etwas stimmt mit der Anzahl der exportierten Zeilen nicht.";
                    }
                }
            }
        }

        // display error message
        if ($error != "") {
            $this->smarty_vars['error'] = $error;
        } elseif ($success != "") {
            $this->smarty_vars['success'] = $success;
        }

        $this->template = 'main';
    }

    public function pdfsplitter()
    {
        $this->misc_utils->sendTextHeader();

        if (isset($this->params[0])) {
            if ($this->params[0] == 'Sh5XCW86RwZLJ63D') {
                $alle_mitarbeiter = \ttact\Models\MitarbeiterModel::findAll($this->db);
                foreach ($alle_mitarbeiter as $mitarbeiter) {
                    echo utf8_decode(trim($mitarbeiter->getSozialversicherungsnummer(), " \t\n\r\0\x0B ")) . "|" . utf8_decode($mitarbeiter->getNachname()) . ', ' . utf8_decode($mitarbeiter->getVorname()) . "|" . utf8_decode($mitarbeiter->getNachname()) . ' ' . utf8_decode($mitarbeiter->getVorname()) . '|' . utf8_decode($mitarbeiter->getEmailadresse()) . '|' . utf8_decode($mitarbeiter->getNachname()) . ' ' . utf8_decode($mitarbeiter->getVorname()) . PHP_EOL;
                }
            }
        }

        $this->template = 'blank';
    }

    public function equalpayAPS()
    {
        $i = [
            'action' => $this->user_input->getPostParameter('action')
        ];

        if ($i['action'] == 'exportieren') {
            $this->misc_utils->sendCSVHeader($this->user_input->getPostParameter('filename') . ".csv");
            $array = $this->user_input->getArrayPostParameter("export");
            echo 'Mitarbeiter Name;Filiale;Qualifikationen;Zeitraum;Tätigkeit' . PHP_EOL;
            foreach ($array as $row) {
                #echo \utf8_decode($row) . PHP_EOL;
                echo $row . PHP_EOL;
            }

            $this->template = 'blank';
        } else {
            $liste = [];

            $now = new \DateTime("now");
            $now->setTime(0, 0, 0);

            $monatsanfang = clone $now;
            $monatsanfang->setDate((int) $now->format('Y'), (int) $now->format('m'), 1);
            $monatsanfang->setTime(0, 0, 0);

            $now_minus_6_months = clone $now;
            $now_minus_6_months->sub(new \DateInterval("P0000-06-00T00:00:00"));

            $now_minus_9_months = clone $now;
            $now_minus_9_months->sub(new \DateInterval("P0000-09-00T00:00:00"));

            $statistiken = [];
            $i = 0;

            $temp_liste = [];

            $mitarbeiter = \ttact\Models\MitarbeiterModel::findAllLohnberechnungEintrittBefore($this->db, $monatsanfang, $now_minus_6_months);
            foreach ($mitarbeiter as $m) {
                $von = $m->getEintritt();
                $bis = $now;

                if ($m->getEintritt() <= $now_minus_9_months) {
                    // Eintritt war vor 9 Monaten oder länger
                    $von = $now_minus_9_months;
                }

                $statistik = \ttact\Models\AuftragModel::findCountByStartEndMitarbeiter($this->db, $von, $bis, $m->getID());

                if (isset($statistik[0])) {
                    $top_count = $statistik[0][0];
                    $top_kunde = $statistik[0][1];

                    $sum_count = 0;
                    foreach ($statistik as $s) {
                        $sum_count += $s[0];
                    }

                    $prozentsatz = ($top_count / $sum_count) * 100;
                    $class = '';

                    $equalpay = '';
                    if ($prozentsatz >= 95) {
                        if ($m->getEintritt() <= $now_minus_9_months) {
                            $equalpay = 'ab sofort';
                            $class = 'danger';
                        } else {
                            $eintritt_plus_9_months = clone $m->getEintritt();
                            $eintritt_plus_9_months->add(new \DateInterval("P0000-09-00T00:00:00"));
                            $equalpay = 'ab ' . $eintritt_plus_9_months->format("d.m.Y") . ' seit 9 Monaten beschäftigt';
                            $class = 'warning';
                        }
                    } elseif ($prozentsatz >= 90) {
                        if ($m->getEintritt() <= $now_minus_9_months) {
                            $equalpay = 'länger als 9 Monate beschäftigt';
                            $class = 'warning';
                        } else {
                            $eintritt_plus_9_months = clone $m->getEintritt();
                            $eintritt_plus_9_months->add(new \DateInterval("P0000-09-00T00:00:00"));
                            $equalpay = 'ab ' . $eintritt_plus_9_months->format("d.m.Y") . ' seit 9 Monaten beschäftigt';
                            $class = 'warning';
                        }
                    } else {
                        if ($m->getEintritt() <= $now_minus_9_months) {
                            $equalpay = 'länger als 9 Monate beschäftigt';
                            $class = 'success';
                        } else {
                            $eintritt_plus_9_months = clone $m->getEintritt();
                            $eintritt_plus_9_months->add(new \DateInterval("P0000-09-00T00:00:00"));
                            $equalpay = 'ab ' . $eintritt_plus_9_months->format("d.m.Y") . ' seit 9 Monaten beschäftigt';
                            if ($prozentsatz >= 80) {
                                $class = 'warning';
                            } else {
                                $class = 'success';
                            }
                        }
                    }

                    $prozentsaetze['satz' . $i] = $prozentsatz;
                    $temp_liste['satz' . $i] = [
                        'personalnummer' => $m->getPersonalnummer(),
                        'vorname' => $m->getVorname(),
                        'nachname' => $m->getNachname(),
                        'kunde' => $top_kunde->getKundennummer(),
                        'prozentsatz' => number_format($prozentsatz, 2, ',', ''),
                        'equalpay' => $equalpay,
                        'class' => $prozentsatz < 70 ? 'success' : $class,
                        'export' => ''
                    ];
                    $i++;
                }
            }

            arsort($prozentsaetze);

            foreach ($prozentsaetze as $key => $value) {
                $liste[] = $temp_liste[$key];
            }

            $this->smarty_vars['liste'] = $liste;

            $now = new \DateTime("now");

            $this->smarty_vars['values'] = [
                'filename' => 'equalpay_stand_' . $now->format('d.m.Y')
            ];

            // template settings
            $this->template = 'main';
        }
    }
}
