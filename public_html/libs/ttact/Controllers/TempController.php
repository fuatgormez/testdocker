<?php

namespace ttact\Controllers;

// use League\Period\Period;

class TempController extends Controller
{
    public function neuekalendereintraege()
    {
        $alle_mitarbeiter = \ttact\Models\MitarbeiterModel::findAllLohnberechnung($this->db, new \DateTime("2019-04-01 00:00:00"));

        foreach ($alle_mitarbeiter as $mitarbeiter) {
            $data = [
                'mitarbeiter_id' => $mitarbeiter->getID(),
                'von' => '2019-04-19',
                'bis' => '2019-04-19',
                'titel' => '',
                'type' => 'feiertag_bezahlt'
            ];

            $kalendereintrag = \ttact\Models\KalendereintragModel::createNew($this->db, $data);

            if ($kalendereintrag instanceof \ttact\Models\KalendereintragModel) {
                // all good
            } else {
                echo "Fehler bei MA " . $mitarbeiter->getPersonalnummer() . "<br>";
            }
        }

        foreach ($alle_mitarbeiter as $mitarbeiter) {
            $data = [
                'mitarbeiter_id' => $mitarbeiter->getID(),
                'von' => '2019-04-22',
                'bis' => '2019-04-22',
                'titel' => '',
                'type' => 'feiertag_bezahlt'
            ];

            $kalendereintrag = \ttact\Models\KalendereintragModel::createNew($this->db, $data);

            if ($kalendereintrag instanceof \ttact\Models\KalendereintragModel) {
                // all good
            } else {
                echo "Fehler bei MA " . $mitarbeiter->getPersonalnummer() . "<br>";
            }
        }

        $this->template = 'blank';
    }

    /*
    public function lohnerhoehungTPS()
    {
        $datetime_model_januar = new \DateTime('2019-01-01 00:00:00');

        foreach (\ttact\Models\MitarbeiterModel::findAllLohnberechnung($this->db, $datetime_model_januar) as $mitarbeiter) {
            $erstellen = false;
            $wochenstunden = 0;

            $lohnkonfiguration_model = \ttact\Models\LohnkonfigurationModel::findForMitarbeiterDate($this->db, $mitarbeiter->getID(), $datetime_model_januar);
            if ($lohnkonfiguration_model instanceof \ttact\Models\LohnkonfigurationModel) {
                $soll_lohn = (float) $lohnkonfiguration_model->getSollLohn();
                $wochenstunden = (float) $lohnkonfiguration_model->getWochenstunden();

                if ($soll_lohn < 9.19) {
                    if ($lohnkonfiguration_model->getGueltigAb() instanceof \DateTime) {
                        if ($lohnkonfiguration_model->getGueltigAb()->format('Y-m-d') == '2019-01-01') {
                            // update Lohnkonfiguration
                            if (!$lohnkonfiguration_model->setSollLohn(9.19)) {
                                echo $mitarbeiter->getPersonalnummer . ": Die Lohnkonfiguration konnte nicht aktualisiert werden!<br>";
                            }
                        } else {
                            // create new Lohnkonfiguration
                            $erstellen = true;
                        }
                    } else {
                        // create new Lohnkonfiguration
                        $erstellen = true;
                    }
                }
            } else {
                // create new Lohnkonfiguration
                $erstellen = true;
            }

            if ($erstellen) {
                $data = [
                    'gueltig_ab'        => '2019-01-01',
                    'mitarbeiter_id'    => $mitarbeiter->getID(),
                    'wochenstunden'     => $wochenstunden,
                    'soll_lohn'         => 9.19
                ];

                $lohnkonfiguration_model = \ttact\Models\LohnkonfigurationModel::createNew($this->db, $data);
                if ($lohnkonfiguration_model instanceof \ttact\Models\LohnkonfigurationModel) {
                    // success
                } else {
                    echo $mitarbeiter->getPersonalnummer . ": Die Lohnkonfiguration konnte nicht erstellt werden!<br>";
                }
            }
        }

        $this->template = 'blank';
    }

    public function liste()
    {
        if ($this->user_input->getPostParameter('action') == 'exportieren') {
            $this->misc_utils->sendCSVHeader($this->user_input->getPostParameter('filename') . ".csv");
            $array = $this->user_input->getArrayPostParameter("export");
            echo mb_convert_encoding('Personalnr.;Nachname;Vorname;Tarif;Tariflohn alt;Tariflohn neu;Zuschl. 9 Mon.;Zuschl. 12 Mon.;übertarifl. Zul. alt;übertarifl. Zul. neu', 'UTF-16LE', 'UTF-8') . PHP_EOL;
            foreach ($array as $row) {
                echo mb_convert_encoding(base64_decode($row), 'UTF-16LE', 'UTF-8') . PHP_EOL;
            }

            $this->template = 'blank';
        } else {
            $error = "";

            $liste = [];

            $datetime_model_dezember = new \DateTime('2018-12-01 00:00:00');
            $datetime_model_januar = new \DateTime('2019-01-01 00:00:00');

            foreach (\ttact\Models\MitarbeiterModel::findAllLohnberechnung($this->db, $datetime_model_januar) as $mitarbeiter) {
                $lohnkonfiguration_model = \ttact\Models\LohnkonfigurationModel::findForMitarbeiterDate($this->db, $mitarbeiter->getID(), $datetime_model_januar);
                if ($lohnkonfiguration_model instanceof \ttact\Models\LohnkonfigurationModel) {
                    $tariflohnbetrag_model_dezember = \ttact\Models\TariflohnbetragModel::findByTarifAndDatum($this->db, $lohnkonfiguration_model->getTarif()->getID(), $datetime_model_dezember);
                    $tariflohnbetrag_model_januar = \ttact\Models\TariflohnbetragModel::findByTarifAndDatum($this->db, $lohnkonfiguration_model->getTarif()->getID(), $datetime_model_januar);
                    if ($tariflohnbetrag_model_dezember instanceof \ttact\Models\TariflohnbetragModel && $tariflohnbetrag_model_januar instanceof \ttact\Models\TariflohnbetragModel) {
                        $mehr_als_9_monate_beschaeftigt = false;
                        $mehr_als_12_monate_beschaeftigt = false;
                        if ($mitarbeiter->getEintritt() instanceof \DateTime) {
                            $monatsanfang_minus_9_monate = clone $datetime_model_januar;
                            $monatsanfang_minus_9_monate->sub(new \DateInterval("P0000-09-00T00:00:00"));
                            if ($mitarbeiter->getEintritt() <= $monatsanfang_minus_9_monate) {
                                $mehr_als_9_monate_beschaeftigt = true;
                            }
                            $monatsanfang_minus_12_monate = clone $datetime_model_januar;
                            $monatsanfang_minus_12_monate->sub(new \DateInterval("P0001-00-00T00:00:00"));
                            if ($mitarbeiter->getEintritt() <= $monatsanfang_minus_12_monate) {
                                $mehr_als_12_monate_beschaeftigt = true;
                            }
                        }

                        $zuschlag_9_monate = 0;
                        $zuschlag_12_monate = 0;
                        $uebertarifliche_zulage_alt = 0;
                        $uebertarifliche_zulage_neu = 0;

                        $lohnsatz = $tariflohnbetrag_model_januar->getLohn();
                        if ($mehr_als_12_monate_beschaeftigt) {
                            $zuschlag_12_monate = $lohnsatz * 0.03;
                        } elseif ($mehr_als_9_monate_beschaeftigt) {
                            $zuschlag_9_monate = $lohnsatz * 0.015;
                        }

                        if ($lohnkonfiguration_model->getSollLohn() > ($tariflohnbetrag_model_dezember->getLohn() + $zuschlag_9_monate + $zuschlag_12_monate)) {
                            $uebertarifliche_zulage_alt = $lohnkonfiguration_model->getSollLohn() - ($tariflohnbetrag_model_dezember->getLohn() + $zuschlag_9_monate + $zuschlag_12_monate);
                        }
                        if ($lohnkonfiguration_model->getSollLohn() > ($tariflohnbetrag_model_januar->getLohn() + $zuschlag_9_monate + $zuschlag_12_monate)) {
                            $uebertarifliche_zulage_neu = $lohnkonfiguration_model->getSollLohn() - ($tariflohnbetrag_model_januar->getLohn() + $zuschlag_9_monate + $zuschlag_12_monate);
                        }

                        // add to list
                        $liste[] = [
                            'personalnummer' => $mitarbeiter->getPersonalnummer(),
                            'vorname' => $mitarbeiter->getVorname(),
                            'nachname' => $mitarbeiter->getNachname(),
                            'tarif' => $lohnkonfiguration_model->getTarif()->getBezeichnung(),
                            'tariflohn_alt' => $tariflohnbetrag_model_dezember->getLohn(),
                            'tariflohn_neu' => $tariflohnbetrag_model_januar->getLohn(),
                            'zuschlag_9_monate' => $zuschlag_9_monate,
                            'zuschlag_12_monate' => $zuschlag_12_monate,
                            'uebertarifliche_zulage_alt' => $uebertarifliche_zulage_alt,
                            'uebertarifliche_zulage_neu' => $uebertarifliche_zulage_neu,
                            'export' => base64_encode(
                                $mitarbeiter->getPersonalnummer() . ';' .
                                $mitarbeiter->getNachname() . ';' .
                                $mitarbeiter->getVorname() . ';' .
                                $lohnkonfiguration_model->getTarif()->getBezeichnung() . ';' .
                                number_format($tariflohnbetrag_model_dezember->getLohn(), 2, ',', '') . ';' .
                                number_format($tariflohnbetrag_model_januar->getLohn(), 2, ',', '') . ';' .
                                number_format($zuschlag_9_monate, 2, ',', '') . ';' .
                                number_format($zuschlag_12_monate, 2, ',', '') . ';' .
                                number_format($uebertarifliche_zulage_alt, 2, ',', '') . ';' .
                                number_format($uebertarifliche_zulage_neu, 2, ',', '')
                            )
                        ];
                    } else {
                        $error .= 'Personalnummer ' . $mitarbeiter->getPersonalnummer() . ': Tariflohn fehlt, Mitarbeiter wird nicht angezeigt<br>';
                    }
                } else {
                    $error .= 'Personalnummer ' . $mitarbeiter->getPersonalnummer() . ': Lohnkonfiguration fehlt, Mitarbeiter wird nicht angezeigt<br>';
                }
            }

            $now = new \DateTime('now');
            $this->smarty_vars['filename'] = 'Liste_' . $now->format('Y_m_d') . '';
            $this->smarty_vars['liste'] = $liste;
            $this->smarty_vars['error'] = $error;
            $this->template = 'main';
        }
    }

    public function listeTPS()
    {
        if ($this->user_input->getPostParameter('action') == 'exportieren') {
            $this->misc_utils->sendCSVHeader($this->user_input->getPostParameter('filename') . ".csv");
            $array = $this->user_input->getArrayPostParameter("export");
            echo mb_convert_encoding('Personalnr.;Nachname;Vorname;Stundenlohn', 'UTF-16LE', 'UTF-8') . PHP_EOL;
            foreach ($array as $row) {
                echo mb_convert_encoding(base64_decode($row), 'UTF-16LE', 'UTF-8') . PHP_EOL;
            }

            $this->template = 'blank';
        } else {
            $error = "";

            $liste = [];

            $datetime_model_januar = new \DateTime('2019-01-01 00:00:00');

            foreach (\ttact\Models\MitarbeiterModel::findAllLohnberechnung($this->db, $datetime_model_januar) as $mitarbeiter) {
                $lohnkonfiguration_model = \ttact\Models\LohnkonfigurationModel::findForMitarbeiterDate($this->db, $mitarbeiter->getID(), $datetime_model_januar);
                if ($lohnkonfiguration_model instanceof \ttact\Models\LohnkonfigurationModel) {
                    $soll_lohn = (float) $lohnkonfiguration_model->getSollLohn();

                    // add to list
                    $liste[] = [
                        'personalnummer' => $mitarbeiter->getPersonalnummer(),
                        'vorname' => $mitarbeiter->getVorname(),
                        'nachname' => $mitarbeiter->getNachname(),
                        'stundenlohn' => $soll_lohn,
                        'export' => base64_encode(
                            $mitarbeiter->getPersonalnummer() . ';' .
                            $mitarbeiter->getNachname() . ';' .
                            $mitarbeiter->getVorname() . ';' .
                            number_format($soll_lohn, 2, ',', '')
                        )
                    ];
                } else {
                    $error .= 'Personalnummer ' . $mitarbeiter->getPersonalnummer() . ': Lohnkonfiguration fehlt, Mitarbeiter wird nicht angezeigt<br>';
                }
            }

            $now = new \DateTime('now');
            $this->smarty_vars['filename'] = 'Liste_' . $now->format('Y_m_d') . '';
            $this->smarty_vars['liste'] = $liste;
            if ($error != "") {
                $this->smarty_vars['error'] = $error;
            }
            $this->template = 'main';
        }
    }

    public function unterzeichnungsdatumrahmenvertrag()
    {
        $error = "";
        $success = "";
        if (isset($this->params[0])) {
            if ($this->params[0] == "erfolgreich") {
                $success = "Der Import wurde erfolgreich durchgeführt.";
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
                        foreach ($file as &$row) {
                            $row = utf8_encode($row);
                            $array = explode(';', $row);
                            if (count($array) != 2) {
                                $error = "Etwas stimmt mit der Anzahl der exportierten Spalten nicht.";
                                break;
                            } elseif (!$this->user_input->getOnlyNumbers($array[0]) == $array[0]) {
                                $error = "Etwas stimmt mit der Datei nicht: die Kundennummer beinhaltet auch nichtnumerische Zeichen.";
                                break;
                            } else {
                                foreach ($array as &$col) {
                                    $col = filter_var(trim($col), FILTER_SANITIZE_STRING);
                                }

                                $kunde = \ttact\Models\KundeModel::findByKundennummer($this->db, (int) $this->user_input->getOnlyNumbers($array[0]));
                                if ($kunde instanceof \ttact\Models\KundeModel) {
                                    $datum = \DateTime::createFromFormat('Y-m-d', $array[1]);
                                    if (datum instanceof \DateTime) {
                                        $kunde->setUnterzeichnungsdatumRahmenvertrag($datum->format('Y-m-d'));
                                    }
                                } else {
                                    $error .= "Die Kundennummer <kbd>" . $this->user_input->getOnlyNumbers($array[0]) . "</kbd> existiert nicht in der Datenbank.<br>";
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
                        foreach ($file as &$row) {
                            $row = utf8_encode($row);
                            $array = explode(';', $row);
                            if (count($array) != 4) {
                                $error = "Etwas stimmt mit der Anzahl der exportierten Spalten nicht.";
                                break;
                            } elseif (!$this->user_input->getOnlyNumbers($array[0]) == $array[0]) {
                                $error = "Etwas stimmt mit der Datei nicht: die Personalnummer beinhaltet auch nichtnumerische Zeichen.";
                                break;
                            } else {
                                foreach ($array as &$col) {
                                    $col = filter_var(trim($col), FILTER_SANITIZE_STRING);
                                }

                                $mitarbeiter = \ttact\Models\MitarbeiterModel::findByPersonalnummer($this->db, (int) $this->user_input->getOnlyNumbers($array[0]));
                                if ($mitarbeiter instanceof \ttact\Models\MitarbeiterModel) {
                                    $tagessoll_model = \ttact\Models\TagessollModel::findByJahrMonatMitarbeiter($this->db, (int) $array[1], (int) $array[2], $mitarbeiter->getID());
                                    if (!$tagessoll_model instanceof \ttact\Models\TagessollModel) {
                                        $data = [
                                            'mitarbeiter_id' => $mitarbeiter->getID(),
                                            'jahr' => (int) $array[1],
                                            'monat' => (int) $array[2],
                                            'tagessoll' => (float) $array[3],
                                            'tagessoll_montag' => (float) $array[3],
                                            'tagessoll_dienstag' => (float) $array[3],
                                            'tagessoll_mittwoch' => (float) $array[3],
                                            'tagessoll_donnerstag' => (float) $array[3],
                                            'tagessoll_freitag' => (float) $array[3],
                                            'tagessoll_samstag' => (float) $array[3],
                                            'tagessoll_sonntag' => (float) $array[3]
                                        ];
                                        $tagessoll_model = \ttact\Models\TagessollModel::createNew($this->db, $data);
                                        if (!$tagessoll_model instanceof \ttact\Models\TagessollModel) {
                                            echo "Achtung: Für den Mitarbeiter " . $mitarbeiter->getPersonalnummer() . " konnte ein Tagessoll-Eintrag nicht angelegt werden.<br>";
                                        }
                                    } else {
                                        $error .= "Achtung! Ein Tagessoll-Model des Mitarbeiters " . $this->user_input->getOnlyNumbers($array[0]) . " existiert bereits!<br>";
                                    }
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

    public function taylan()
    {
        $e=sqrt(2*14);
        echo $e;

        $this->template = 'blank';
    }

    public function decode()
    {
        $string = "YTozOntzOjE0OiJyZWNobnVuZ2JldHJhZyI7YTozOntzOjU6Im5ldHRvIjtkOjI0MDAuMDcwMDAwMDAwMDAwMjtzOjY6ImJydXR0byI7ZDoyODU2LjA4MzMwMDAwMDAwMDI7czo0OiJtd3N0IjtkOjQ1Ni4wMTMzMDAwMDAwMDAwMjt9czoxNToicmVjaG51bmdzcG9zdGVuIjthOjQ6e2k6MDthOjQ6e3M6MTI6ImxlaXN0dW5nc2FydCI7czo5OiJUaWVma8O8aGwiO3M6NToibWVuZ2UiO2Q6MTY7czoxMToiZWluemVscHJlaXMiO2Q6MTcuMTQ5OTk5OTk5OTk5OTk5O3M6MTE6Imdlc2FtdHByZWlzIjtkOjI3NC4zOTk5OTk5OTk5OTk5ODt9aToxO2E6NDp7czoxMjoibGVpc3R1bmdzYXJ0IjtzOjU6Ikthc3NlIjtzOjU6Im1lbmdlIjtkOjQ3LjI1O3M6MTE6ImVpbnplbHByZWlzIjtkOjE3LjE0OTk5OTk5OTk5OTk5OTtzOjExOiJnZXNhbXRwcmVpcyI7ZDo4MTAuMzQwMDAwMDAwMDAwMDM7fWk6MjthOjQ6e3M6MTI6ImxlaXN0dW5nc2FydCI7czoxMToiS2Fzc2UgTmFjaHQiO3M6NToibWVuZ2UiO2Q6NTUuNzU7czoxMToiZWluemVscHJlaXMiO2Q6MjEuNDQwMDAwMDAwMDAwMDAxO3M6MTE6Imdlc2FtdHByZWlzIjtkOjExOTUuMjg7fWk6MzthOjQ6e3M6MTI6ImxlaXN0dW5nc2FydCI7czo5OiJWb3J6aWVoZW4iO3M6NToibWVuZ2UiO2Q6NztzOjExOiJlaW56ZWxwcmVpcyI7ZDoxNy4xNDk5OTk5OTk5OTk5OTk7czoxMToiZ2VzYW10cHJlaXMiO2Q6MTIwLjA1O319czoxMDoic3RhbW1kYXRlbiI7YToxNDp7czo0OiJydm9uIjtzOjEwOiIyMy4wNy4yMDE4IjtzOjQ6InJiaXMiO3M6MTA6IjMxLjA3LjIwMTgiO3M6Njoicmt1bmRlIjtpOjEwMTtzOjIxOiJya2Fzc2VuZGlmZmVyZW56X25hbWUiO3M6MDoiIjtzOjE2OiJya2Fzc2VuZGlmZmVyZW56IjtzOjA6IiI7czoyMDoicmthc3NlbmRpZmZlcmVuel92b24iO3M6NjoiYnJ1dHRvIjtzOjU6InJkYXRlIjtzOjEwOiIwMS4wOC4yMDE4IjtzOjE2OiJycmVjaG51bmdzbnVtbWVyIjtzOjA6IiI7czoxODoicmxlaXN0dW5nc3plaXRyYXVtIjtzOjk6Ikp1bGkgMjAxOCI7czoxMzoicnphaGx1bmdzemllbCI7czoxMDoiMTEuMDguMjAxOCI7czoxMzoiYWt0dWFsaXNpZXJlbiI7czowOiIiO3M6NzoienBvc3RlbiI7YTo0OntzOjEyOiJsZWlzdHVuZ3NhcnQiO2E6MTp7aTowO2k6MDt9czoxNjoibGVpc3R1bmdzYXJ0dGV4dCI7YToxOntpOjA7czowOiIiO31zOjU6Im1lbmdlIjthOjE6e2k6MDtzOjA6IiI7fXM6MjoiZXAiO2E6MTp7aTowO3M6MDoiIjt9fXM6MTE6ImFkZHJlY2hudW5nIjtzOjA6IiI7czo3OiJyYW5yZWRlIjtzOjA6IiI7fX0=";
        print_r(unserialize(base64_decode($string)));

        $this->template = 'blank';
    }


    public function liste()
    {
        if ($this->user_input->getPostParameter('action') == 'exportieren') {
            $this->misc_utils->sendCSVHeader($this->user_input->getPostParameter('filename') . ".csv");
            $array = $this->user_input->getArrayPostParameter("export");
            echo mb_convert_encoding('Personalnr.;Name des MA;Beschäftigungsbeginn im Unternehmen;ggf. Beschäftigungsende;Tätigkeit;Überlassung von;Überlassung bis;Entleiherunternehmen (letzte beiden ausreichend);Welcher TV-BZ/Mindestlohn (falls angewandt)', 'UTF-16LE', 'UTF-8') . PHP_EOL;
            foreach ($array as $row) {
                echo mb_convert_encoding(base64_decode($row), 'UTF-16LE', 'UTF-8') . PHP_EOL;
            }

            $this->template = 'blank';
        } else {
            $alle_mitarbeiter = [];
            $result = $this->db->getRowsQuery("SELECT * FROM mitarbeiter WHERE eintritt != '0000-00-00' AND (austritt = '0000-00-00' OR austritt >= '2017-06-01')");
            foreach ($result as $model_data) {
                $alle_mitarbeiter[] = new \ttact\Models\MitarbeiterModel($this->db, $model_data);
            }

            $liste = [];
            foreach ($alle_mitarbeiter as $mitarbeiter) {
                $result = $this->db->getRowsQuery("SELECT COUNT(auftrag_id) as count FROM auftrag WHERE mitarbeiter_id = '" . $mitarbeiter->getID() . "'");
                if ($result[0]['count'] > 0) {
                    // Abteilungsnamen
                    $result = $this->db->getRowsQuery("SELECT auftrag.abteilung_id, abteilung.bezeichnung FROM auftrag, abteilung WHERE auftrag.abteilung_id = abteilung.abteilung_id AND auftrag.mitarbeiter_id = '" . $mitarbeiter->getID() . "' GROUP BY auftrag.abteilung_id");
                    $alle_abteilungsnamen = [];
                    foreach ($result as $row) {
                        $alle_abteilungsnamen[] = $row['bezeichnung'];
                    }

                    // Kundenbetriebe
                    $result = $this->db->getRowsQuery("SELECT * FROM auftrag WHERE status = 'archiviert' AND mitarbeiter_id = '" . $mitarbeiter->getID() . "' AND in_rechnung_stellen = 1 ORDER BY von DESC");
                    $kundenbetriebe = [];
                    foreach ($result as $row) {
                        if (count($kundenbetriebe) < 2 && !key_exists($row['kunde_id'], $kundenbetriebe)) {
                            $kunde_model = \ttact\Models\KundeModel::findByID($this->db, $row['kunde_id']);
                            $kundenbetriebe[$row['kunde_id']] = str_replace(PHP_EOL, '<br>', $kunde_model->getRechnungsanschrift());
                        }
                    }

                    // Tarif
                    $tarif = '';
                    $lohnkonfiguration_model = \ttact\Models\LohnkonfigurationModel::findForMitarbeiterDate($this->db, $mitarbeiter->getID(), new \DateTime('now'));
                    if ($lohnkonfiguration_model instanceof \ttact\Models\LohnkonfigurationModel) {
                        $tarif_model = $lohnkonfiguration_model->getTarif();
                        if ($tarif_model instanceof \ttact\Models\TarifModel) {
                            $tarif = $tarif_model->getBezeichnung();
                        }
                    }

                    // add to list
                    $liste[] = [
                        'personalnummer' => $mitarbeiter->getPersonalnummer(),
                        'vorname' => $mitarbeiter->getVorname(),
                        'nachname' => $mitarbeiter->getNachname(),
                        'eintritt' => $mitarbeiter->getEintritt() instanceof \DateTime ? $mitarbeiter->getEintritt()->format('d.m.Y') : '',
                        'austritt' => $mitarbeiter->getAustritt() instanceof \DateTime ? $mitarbeiter->getAustritt()->format('d.m.Y') : '',
                        'abteilungen' => implode(', ', $alle_abteilungsnamen),
                        'kunden' => implode('<br><br>', $kundenbetriebe),
                        'tarif' => $tarif,
                        'export' => base64_encode($mitarbeiter->getPersonalnummer() . ';' . $mitarbeiter->getVorname() . ' ' . $mitarbeiter->getNachname() . ';' . ($mitarbeiter->getEintritt() instanceof \DateTime ? $mitarbeiter->getEintritt()->format('d.m.Y') : '') . ';' . ($mitarbeiter->getAustritt() instanceof \DateTime ? $mitarbeiter->getAustritt()->format('d.m.Y') : '') . ';' . implode(', ', $alle_abteilungsnamen) . ';' . ($mitarbeiter->getEintritt() instanceof \DateTime ? $mitarbeiter->getEintritt()->format('d.m.Y') : '') . ';' . ($mitarbeiter->getAustritt() instanceof \DateTime ? $mitarbeiter->getAustritt()->format('d.m.Y') : '') . ';"' . implode(PHP_EOL . PHP_EOL, str_replace('<br>', PHP_EOL, $kundenbetriebe)) . '";' . $tarif)
                    ];
                }
            }

            $now = new \DateTime('now');
            $this->smarty_vars['filename'] = 'Liste_' . $now->format('Y_m_d') . '';
            $this->smarty_vars['liste'] = $liste;
            $this->template = 'main';
        }
    }

    public function excelliste()
    {
        $monatsanfang = new \DateTime("2017-10-01 00:00:00");
        $alle_mitarbeiter = \ttact\Models\MitarbeiterModel::findAllLohnberechnung($this->db, $monatsanfang);

        foreach ($alle_mitarbeiter as $mitarbeiter) {
            $lohnkonfiguration = \ttact\Models\LohnkonfigurationModel::findForMitarbeiterDate($this->db, $mitarbeiter->getID(), $monatsanfang);
            if ($lohnkonfiguration instanceof \ttact\Models\LohnkonfigurationModel) {
                if ($lohnkonfiguration->getWochenstunden() > 11.5) {
                    $urlaubstage_genommen = 0;
                    $anfang = new \DateTime("2017-01-01 00:00:00");
                    $ende = new \DateTime("2017-12-31 23:59:59");
                    $kalendereintraege = \ttact\Models\KalendereintragModel::findByStartEndTypeMitarbeiter($this->db, $anfang, $ende, $mitarbeiter->getID(), 'urlaub_bezahlt');
                    $period = new Period($anfang, $ende);
                    foreach ($kalendereintraege as $kalendereintrag) {
                        $kalendereintrag_bis = $kalendereintrag->getBis();
                        $kalendereintrag_bis->add(new \DateInterval("P0000-00-01T00:00:00"));
                        $kalendereintrag_bis->setTime(0, 0, 0);
                        $period_kalendereintrag = new Period($kalendereintrag->getVon(), $kalendereintrag_bis);

                        if ($period->overlaps($period_kalendereintrag)) {
                            $period_urlaub = $period->intersect($period_kalendereintrag);
                            foreach ($period_urlaub->getDatePeriod(new \DateInterval("P0000-00-01T00:00:00")) as $day) {
					            if ($day->format("N") != 7) {
						            $urlaubstage_genommen++;
					            }
				            }
			            }
                    }
                    echo $mitarbeiter->getPersonalnummer();
                    echo ';';
                    echo $mitarbeiter->getNachname();
                    echo ';';
                    echo $mitarbeiter->getVorname();
                    echo ';';
                    echo $mitarbeiter->getJahresurlaub() + $mitarbeiter->getResturlaubVorjahr();
                    echo ';';
                    echo $urlaubstage_genommen;
                    echo ';';
                    echo ($mitarbeiter->getJahresurlaub() + $mitarbeiter->getResturlaubVorjahr()) - $urlaubstage_genommen;
                    echo PHP_EOL;
                }
            }
        }

        $this->template = 'blank';
    }

    public function rechnungen()
    {
        if (isset($this->params[0]))  	{ $monat=$this->params[0]; 	} else { $monat=date("m"); }
        if (isset($this->params[1]))  		{ $jahr=$this->params[1]; 		} else { $jahr=date("Y"); }

        $i = $this->params[0];
        if($i==1) { $msuchname = 'Januar'; }
        if($i==2) { $msuchname = 'Februar'; }
        if($i==3) { $msuchname = 'März'; }
        if($i==4) { $msuchname = 'April'; }
        if($i==5) { $msuchname = 'Mai'; }
        if($i==6) { $msuchname = 'Juni'; }
        if($i==7) { $msuchname = 'Juli'; }
        if($i==8) { $msuchname = 'August'; }
        if($i==9) { $msuchname = 'September'; }
        if($i==10) { $msuchname = 'Oktober'; }
        if($i==11) { $msuchname = 'November'; }
        if($i==12) { $msuchname = 'Dezember'; }

        #error_reporting(E_ALL);
        #ini_set('display_errors', 1); // Wenn fertig auf 0!
        //header("Content-type: text/x-csv");
        //if($_SERVER['SERVER_NAME'] == 'tps2.c-multimedia.de') {
        //    header("Content-Disposition: attachment; filename=tps_rlist".$jahr."-".$monat.".csv");
        //} else {
	        header("Content-Disposition: attachment; filename=aps_rlist".$jahr."-".$monat.".csv");
        //}
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        header("Expires: 0");


        //require('../libs/config.php');
        //require('../libs/mysql.class.php');
        //require('../libs/function.php');

        //session_name(CONFIG_SESSION);
        //session_start();
        $zeilennummer=0;
        $GLOBALS['mysql'] = new \mysql('liluye.han-solo.net', 'aps', 'dJqQQWss2Bj9rzhv', 'aps');
        echo(utf8_decode("Umsatz in Euro;Steuerschlüssel;Gegenkonto;Beleg1;Beleg2;Datum;Konto;Kost1;Kost2;Skonto in Euro;Buchungstext;Umsatzsteuer-ID;Zusatzart;Zusatzinformation"));
        echo("\n");

        $rechnungen= $GLOBALS['mysql']->query("SELECT * FROM rechnung WHERE rLeistungszeitraum = '".$msuchname." ".$jahr."' AND import=0 AND vorschau=0 ORDER BY rKunde");
        while($row = $rechnungen->fetch_assoc()) {

	        $redata = base64_decode($row['rInhalt']); $redata = unserialize($redata);

	        if($row['rStorno'] != 0) {
		        $gsummenetto	=$redata['rechnungbetrag']['netto'];
		        $gsummebrutto	=$redata['rechnungbetrag']['brutto'];
		        if(isset($redata['stammdaten']['rkassendifferenz_von']) && $redata['stammdaten']['rkassendifferenz_von'] == 'brutto') {
			        $gsummebrutto = $redata['rechnungbetrag']['brutto_alt'];
		        }

		        $rdate = explode(".",$row['rDatum']);

		        $rdate2 = $rdate[0].'.'.$rdate['1'];
		        if($rdate['1'] > $monat) {
			        $rdate2 = date('t',mktime(0,0,0,$monat,1,$rdate['1'])).'.'.$monat;
		        }

		        $srdate = explode(".",$row['rStronoDatum']);

		        $srdate2 = $srdate[0].'.'.$srdate['1'];
		        if($srdate['1'] > $monat) {
			        $srdate2 = date('t',mktime(0,0,0,$monat,1,$srdate['1'])).'.'.$monat;
		        }
		        if($row['rKunde'] != 0) {
			        $kunde= $GLOBALS['mysql']->query_single("SELECT * FROM kunden WHERE kundenID = '".$row['rKunde']."'");
		        //http://aps2.c-multimedia.de/export/rechlist.php?monat=12&jahr=2015



			        if(strlen(substr($row['rNr'],2)) > 6) {
				        echo number_format($gsummebrutto,2,",","").';;4400;'.str_replace(" ","",substr($row['rNr'],3)).';;'.$rdate2.';10'.$kunde['kundennummer'].';;;;'.$kunde['name'].';;;;';
				        echo("\n");
				        echo '-'.number_format($gsummebrutto,2,",","").';20;4400;'.str_replace(" ","",substr($row['rNr'],3)).';;'.$srdate2.';10'.$kunde['kundennummer'].';;;;'.$kunde['name'].';;;;';
				        echo("\n");
				        if(isset($redata['stammdaten']['rkassendifferenz_von']) && $redata['stammdaten']['rkassendifferenz_von'] == 'brutto') {
					        echo '-'.number_format($redata['stammdaten']['rkassendifferenz'],2,",","").';;6851;'.str_replace(" ","",substr($row['rNr'],3)).';;'.$rdate2.';10'.$kunde['kundennummer'].';;;;'.$kunde['name'].';;;;';
				        echo("\n");
				        echo number_format($redata['stammdaten']['rkassendifferenz'],2,",","").';20;6851;'.str_replace(" ","",substr($row['rNr'],3)).';;'.$rdate2.';10'.$kunde['kundennummer'].';;;;'.$kunde['name'].';;;;';
				        echo("\n");
				        }
			        } else {
				        echo number_format($gsummebrutto,2,",","").';;4400;'.str_replace(" ","",substr($row['rNr'],2)).';;'.$rdate2.';10'.$kunde['kundennummer'].';;;;'.$kunde['name'].';;;;';
				        echo("\n");
				        echo '-'.number_format($gsummebrutto,2,",","").';20;4400;'.str_replace(" ","",substr($row['rNr'],2)).';;'.$srdate2.';10'.$kunde['kundennummer'].';;;;'.$kunde['name'].';;;;';
				        echo("\n");
				        if(isset($redata['stammdaten']['rkassendifferenz_von']) && $redata['stammdaten']['rkassendifferenz_von'] == 'brutto') {
					        echo '-'.number_format($redata['stammdaten']['rkassendifferenz'],2,",","").';;6851;'.str_replace(" ","",substr($row['rNr'],2)).';;'.$rdate2.';10'.$kunde['kundennummer'].';;;;'.$kunde['name'].';;;;';
				        echo("\n");
				        echo number_format($redata['stammdaten']['rkassendifferenz'],2,",","").';20;6851;'.str_replace(" ","",substr($row['rNr'],2)).';;'.$rdate2.';10'.$kunde['kundennummer'].';;;;'.$kunde['name'].';;;;';
				        echo("\n");
				        }
			        }


		        }

	        } else {
		        $gsummenetto	=$redata['rechnungbetrag']['netto'];
		        $gsummebrutto	=$redata['rechnungbetrag']['brutto'];
		        if(isset($redata['stammdaten']['rkassendifferenz_von']) && $redata['stammdaten']['rkassendifferenz_von'] == 'brutto') {
			        $gsummebrutto = $redata['rechnungbetrag']['brutto_alt'];
		        }

		        $rdate = explode(".",$row['rDatum']);

		        $rdate2 = $rdate[0].'.'.$rdate['1'];
		        if($rdate['1'] > $monat) {
			        $rdate2 = date('t',mktime(0,0,0,$monat,1,$rdate['1'])).'.'.$monat;
		        }
		        if($row['rKunde'] != 0) {
			        $kunde= $GLOBALS['mysql']->query_single("SELECT * FROM kunden WHERE kundenID = '".$row['rKunde']."'");
		        //echo '###########'.strlen($row['rNr']).'#######';
			        if(strlen($row['rNr']) > 10){
				        echo number_format($gsummebrutto,2,",","").';;4400;'.str_replace(" ","",substr($row['rNr'],3)).';;'.$rdate2.';10'.$kunde['kundennummer'].';;;;'.$kunde['name'].';;;;';
				        echo("\n");
				        if(isset($redata['stammdaten']['rkassendifferenz_von']) && $redata['stammdaten']['rkassendifferenz_von'] == 'brutto') {
					        echo '-'.number_format($redata['stammdaten']['rkassendifferenz'],2,",","").';;6851;'.str_replace(" ","",substr($row['rNr'],3)).';;'.$rdate2.';10'.$kunde['kundennummer'].';;;;'.$kunde['name'].';;;;';
					        echo("\n");
				        }

			        } else {
				        echo number_format($gsummebrutto,2,",","").';;4400;'.str_replace(" ","",substr($row['rNr'],2)).';;'.$rdate2.';10'.$kunde['kundennummer'].';;;;'.$kunde['name'].';;;;';
				        echo("\n");
				        if(isset($redata['stammdaten']['rkassendifferenz_von']) && $redata['stammdaten']['rkassendifferenz_von'] == 'brutto') {
					        echo '-'.number_format($redata['stammdaten']['rkassendifferenz'],2,",","").';;6851;'.str_replace(" ","",substr($row['rNr'],2)).';;'.$rdate2.';10'.$kunde['kundennummer'].';;;;'.$kunde['name'].';;;;';
					        echo("\n");
				        }
			        }
		        }
	        }
        }

        $this->template = 'blank';
    }

    public function index()
    {
        //$schicht_von = new \DateTime("2017-07-03 17:15:00");
        //$schicht_bis = new \DateTime("2017-07-04 01:30:00");

        //$nacht1_von = new \DateTime("2017-07-02 23:00:00");
        //$nacht1_bis = new \DateTime("2017-07-03 06:00:00");

        //$nacht2_von = new \DateTime("2017-07-03 23:00:00");
        //$nacht2_bis = new \DateTime("2017-07-04 06:00:00");

        //$period_schicht = new Period($schicht_von, $schicht_bis);
        //$period_nacht1 = new Period($nacht1_von, $nacht1_bis);
        //$period_nacht2 = new Period($nacht2_von, $nacht2_bis);

        //$tagstunden = 0;
        //$nachtstunden = 0;

        //if ($period_schicht->overlaps($period_nacht1)) {
        //    $nachtstunden += round($period_schicht->intersect($period_nacht1)->getTimestampInterval() / 3600, 2);
        //}
        //if ($period_schicht->overlaps($period_nacht2)) {
        //    $nachtstunden += round($period_schicht->intersect($period_nacht2)->getTimestampInterval() / 3600, 2);
        //}

        //$tagstunden = round($period_schicht->getTimestampInterval() / 3600, 2) - $nachtstunden;

        //echo "Tagstunden: " . $tagstunden . "<br>";
        //echo "Nachtstunden: " . $nachtstunden;

        $now = new \DateTime("now");
        $now_plus_10_minutes = clone $now;
        $now_plus_10_minutes->add(new \DateInterval("P0000-00-00T00:10:00"));

        while ($now < $now_plus_10_minutes) {
            $now = new \DateTime("now");
        }

        $this->template = 'blank';
    }

    public function index2()
    {
        $array = unserialize(base64_decode('YTozOntzOjE1OiJyZWNobnVuZ3NiZXRyYWciO2E6Mzp7czo1OiJuZXR0byI7ZDo2MTE2LjI2O3M6NjoiYnJ1dHRvIjtkOjcyNzguMzQ5NDtzOjQ6Im13c3QiO2Q6MTE2Mi4wODk0O31zOjE1OiJyZWNobnVuZ3Nwb3N0ZW4iO2E6Mzp7aTowO2E6NDp7czoxMjoibGVpc3R1bmdzYXJ0IjtzOjU6Ikthc3NlIjtzOjU6Im1lbmdlIjtkOjE1NS41O3M6MTE6ImVpbnplbHByZWlzIjtkOjE1Ljk1O3M6MTE6Imdlc2FtdHByZWlzIjtkOjI0ODAuMjM7fWk6MTthOjQ6e3M6MTI6ImxlaXN0dW5nc2FydCI7czoxMToiS2Fzc2UgTmFjaHQiO3M6NToibWVuZ2UiO2Q6MTY4Ljc1O3M6MTE6ImVpbnplbHByZWlzIjtkOjE5Ljk0O3M6MTE6Imdlc2FtdHByZWlzIjtkOjMzNjQuODg7fWk6MjthOjQ6e3M6MTI6ImxlaXN0dW5nc2FydCI7czo0OiJLb2xvIjtzOjU6Im1lbmdlIjtkOjE3O3M6MTE6ImVpbnplbHByZWlzIjtkOjE1Ljk1O3M6MTE6Imdlc2FtdHByZWlzIjtkOjI3MS4xNTt9fXM6MTA6InN0YW1tZGF0ZW4iO2E6MTQ6e3M6NDoicnZvbiI7czoxMDoiMDEuMDcuMjAxNyI7czo0OiJyYmlzIjtzOjEwOiIzMS4wNy4yMDE3IjtzOjY6InJrdW5kZSI7aToxMDE7czoyMToicmthc3NlbmRpZmZlcmVuel9uYW1lIjtzOjA6IiI7czoxNjoicmthc3NlbmRpZmZlcmVueiI7czowOiIiO3M6MjA6InJrYXNzZW5kaWZmZXJlbnpfdm9uIjtzOjY6ImJydXR0byI7czo1OiJyZGF0ZSI7czoxMDoiMDguMDguMjAxNyI7czoxNjoicnJlY2hudW5nc251bW1lciI7czowOiIiO3M6MTg6InJsZWlzdHVuZ3N6ZWl0cmF1bSI7czo5OiJKdWxpIDIwMTciO3M6MTM6InJ6YWhsdW5nc3ppZWwiO3M6MTA6IjE4LjA4LjIwMTciO3M6MTM6ImFrdHVhbGlzaWVyZW4iO3M6MDoiIjtzOjc6Inpwb3N0ZW4iO2E6NDp7czoxMjoibGVpc3R1bmdzYXJ0IjthOjE6e2k6MDtpOjA7fXM6MTY6ImxlaXN0dW5nc2FydHRleHQiO2E6MTp7aTowO3M6MDoiIjt9czo1OiJtZW5nZSI7YToxOntpOjA7czowOiIiO31zOjI6ImVwIjthOjE6e2k6MDtzOjA6IiI7fX1zOjExOiJhZGRyZWNobnVuZyI7czowOiIiO3M6NzoicmFucmVkZSI7czowOiIiO319'));
        //$array['stammdaten']['zposten'] = [];
        //echo base64_encode(serialize($array));
        print_r($array);
        $this->template = 'blank';
    }

    public function index3()
    {
        $alle_mitarbeiter = \ttact\Models\MitarbeiterModel::findAllTemp($this->db);
        foreach ($alle_mitarbeiter as $mitarbeiter) {
            $gesamtlohn = 0;
            $lohnkonfiguration = \ttact\Models\LohnkonfigurationModel::findForMitarbeiterDate($this->db, $mitarbeiter->getID(), new \DateTime("2017-07-01 00:00:00"));
            if ($lohnkonfiguration instanceof \ttact\Models\LohnkonfigurationModel) {
                $gesamtlohn = $lohnkonfiguration->getSollLohn() == '' ? 0 : $lohnkonfiguration->getSollLohn();
            }
            echo $mitarbeiter->getPersonalnummer() . ";" . $mitarbeiter->getNachname() . ";" . $mitarbeiter->getVorname() . ";" . $gesamtlohn . "<br>";
        }

        $this->template = 'blank';
    }

    public function index4()
    {
        $schichten = \ttact\Models\AuftragModel::findAll($this->db, $this->current_user);
        foreach ($schichten as $schicht) {
            if ($schicht->getVon()->format("Y-m-d") != $schicht->getBis()->format("Y-m-d")) {
                $auftrag_log = \ttact\Models\AuftragLogModel::findTemp($this->db, $this->current_user, $schicht->getID());
                if ($auftrag_log instanceof \ttact\Models\AuftragLogModel) {
                    echo $schicht->getID() . ";" . $schicht->getVon()->format("Y-m-d H:i:s") . ";" . $schicht->getBis()->format("Y-m-d H:i:s") . "<br>";
                }
            }
        }
        $this->template = 'blank';
    }

    public function index2()
    {
        //$array = unserialize(base64_decode('YTozOntzOjE0OiJyZWNobnVuZ2JldHJhZyI7YTozOntzOjU6Im5ldHRvIjtkOjYxMTYuMjU7czo2OiJicnV0dG8iO2Q6NzI3OC4zNDAwMDAwMDAwMDAxO3M6NDoibXdzdCI7ZDoxMTYyLjA5MDAwMDAwMDAwMDE7fXM6MTU6InJlY2hudW5nc3Bvc3RlbiI7YTozOntpOjA7YTo0OntzOjEyOiJsZWlzdHVuZ3NhcnQiO3M6NDoiS29sbyI7czo1OiJtZW5nZSI7ZDoxNztzOjExOiJlaW56ZWxwcmVpcyI7czo1OiIxNS45NSI7czoxMToiZ2VzYW10cHJlaXMiO2Q6MjcxLjE0OTk5OTk5OTk5OTk4O31pOjE7YTo0OntzOjEyOiJsZWlzdHVuZ3NhcnQiO3M6MTE6Ikthc3NlIE5hY2h0IjtzOjU6Im1lbmdlIjtkOjE2OC43NTtzOjExOiJlaW56ZWxwcmVpcyI7czo1OiIxOS45NCI7czoxMToiZ2VzYW10cHJlaXMiO2Q6MzM2NC44NzU7fWk6MjthOjQ6e3M6MTI6ImxlaXN0dW5nc2FydCI7czo1OiJLYXNzZSI7czo1OiJtZW5nZSI7ZDoxNTUuNTtzOjExOiJlaW56ZWxwcmVpcyI7czo1OiIxNS45NSI7czoxMToiZ2VzYW10cHJlaXMiO2Q6MjQ4MC4yMjQ5OTk5OTk5OTk5O319czoxMDoic3RhbW1kYXRlbiI7YToxMzp7czo0OiJydm9uIjtzOjEwOiIwMS4wNy4yMDE3IjtzOjQ6InJiaXMiO3M6MTA6IjMxLjA3LjIwMTciO3M6Njoicmt1bmRlIjtzOjM6IjEwMSI7czoyMToicmthc3NlbmRpZmZlcmVuel9uYW1lIjtzOjA6IiI7czoxNjoicmthc3NlbmRpZmZlcmVueiI7czowOiIiO3M6MjA6InJrYXNzZW5kaWZmZXJlbnpfdm9uIjtzOjU6Im5ldHRvIjtzOjU6InJkYXRlIjtzOjEwOiIwNi4wOC4yMDE3IjtzOjE2OiJycmVjaG51bmdzbnVtbWVyIjtzOjA6IiI7czoxODoicmxlaXN0dW5nc3plaXRyYXVtIjtzOjk6Ikp1bGkgMjAxNyI7czoxMzoicnphaGx1bmdzemllbCI7czoxMDoiMTYuMDguMjAxNyI7czoxMzoiYWt0dWFsaXNpZXJlbiI7czowOiIiO3M6NzoienBvc3RlbiI7YTo0OntzOjEyOiJsZWlzdHVuZ3NhcnQiO2E6MTp7aTowO3M6MToiMCI7fXM6MTY6ImxlaXN0dW5nc2FydHRleHQiO2E6MTp7aTowO3M6MDoiIjt9czo1OiJtZW5nZSI7YToxOntpOjA7czowOiIiO31zOjI6ImVwIjthOjE6e2k6MDtzOjA6IiI7fX1zOjc6InJhbnJlZGUiO3M6MjM6IlNlaHIgZ2VlaHJ0ZSBGcmF1IElkZW4sIjt9fQ======'));
        //print_r($array);
        //
        //$d = new \DateTime('0000-00-00 00:00:00');
        //print_r($d);

        //$aps_db = new \ttact\Database('aps2.c-multimedia.de', 'aps', 'dJqQQWss2Bj9rzhv', 'aps');
        //$array = $aps_db->getFirstRow('ZuteilungPersonal', ['Nummer' => '1563']);

        //\var_dump($array);

        //if ($array['Abteilung1'] == '') {
        //    echo "hi";
        //}

        //$ma = $aps_db->getRows('mitarbeiter');
        //foreach ($ma as $m) {
        //    $z = $aps_db->getRows('ZuteilungPersonal', [], ['Nummer' => $m['Personalnr']]);
        //    if (count($z) != 1) {
        //        echo "Mitarbeiter-ID: " . $m['Id'] . ", Personalnummer: " . $m['Personalnr'] . " | " . count($z) . "<br>";
        //        //echo $m['Personalnr'] . "<br>";
        //    }
        //}

        //$array = unserialize('a:5:{i:0;a:8:{s:6:"abdate";i:1427839200;s:7:"EinGrup";s:2:"E1";s:6:"TarifL";s:4:"8.20";s:6:"UeberT";s:4:"0.30";s:4:"Lohn";s:4:"8.50";s:4:"WSdt";s:4:"0.00";s:6:"fLohn1";s:1:"0";s:6:"fLohn2";s:4:"0.00";}i:1;a:8:{s:6:"abdate";i:1464732000;s:7:"EinGrup";s:2:"E1";s:6:"TarifL";s:4:"8.76";s:6:"UeberT";s:4:"0.00";s:4:"Lohn";s:4:"8.76";s:4:"WSdt";s:4:"0.00";s:6:"fLohn1";s:1:"0";s:6:"fLohn2";s:4:"0.00";}i:2;a:8:{s:6:"abdate";i:1483225200;s:7:"EinGrup";s:2:"E1";s:6:"TarifL";s:4:"9.11";s:6:"UeberT";s:4:"0.00";s:4:"Lohn";s:4:"9.11";s:4:"WSdt";s:5:"10.00";s:6:"fLohn1";s:1:"0";s:6:"fLohn2";s:4:"0.00";}i:3;a:8:{s:6:"abdate";i:1488322800;s:7:"EinGrup";s:2:"E1";s:6:"TarifL";s:4:"9.18";s:6:"UeberT";s:4:"0.00";s:4:"Lohn";s:4:"9.18";s:4:"WSdt";s:5:"10.00";s:6:"fLohn1";s:1:"0";s:6:"fLohn2";s:4:"0.00";}i:4;a:8:{s:6:"abdate";i:1490997600;s:7:"EinGrup";s:2:"E1";s:6:"TarifL";s:4:"9.18";s:6:"UeberT";s:4:"0.00";s:4:"Lohn";s:4:"9.18";s:4:"WSdt";s:2:"11";s:6:"fLohn1";s:1:"0";s:6:"fLohn2";s:0:"";}}');
        //print_r($array);

        //$print_data = [];
        //$z = $aps_db->getRows('ZuteilungPersonal');
        //foreach ($z as $z_row) {
        //    $print_data[] = [
        //        'personalnummer'        => $z_row['Nummer'],
        //        'gueltig_ab'            => '0000-00-00',
        //        'wochenstunden'         => $z_row['WSdt'],
        //        'tarif'                 => $z_row['EinGrup'],
        //        'tariflohn'             => $z_row['TarifL'],
        //        'uebertariflicher_lohn' => $z_row['UeberT'],
        //        'gesamtlohn'            => $z_row['Lohn']
        //    ];

        //    if ($z_row['inhalt'] != '') {
        //        $array = unserialize(base64_decode($z_row['inhalt']));
        //        if (count($array) > 0) {
        //            foreach ($array as $a) {
        //                if (is_integer($a['abdate'])) {
        //                    $gueltig_ab = new \DateTime();
        //                    $gueltig_ab->setTimestamp($a['abdate']);
        //                    $gueltig_ab = $gueltig_ab->format('Y-m-d');
        //                } else {
        //                    $gueltig_ab = '';
        //                }

        //                if (!isset($a['WSdt'])) {
        //                    $a['WSdt'] = '';
        //                }

        //                $print_data[] = [
        //                    'personalnummer'        => $z_row['Nummer'],
        //                    'gueltig_ab'            => $gueltig_ab,
        //                    'wochenstunden'         => $a['WSdt'],
        //                    'tarif'                 => $a['EinGrup'],
        //                    'tariflohn'             => $a['TarifL'],
        //                    'uebertariflicher_lohn' => $a['UeberT'],
        //                    'gesamtlohn'            => $a['Lohn']
        //                ];
        //            }
        //        }
        //    }
        //}

        //echo "personalnummer;gueltig_ab;wochenstunden;tarif;tariflohn;uebertariflicher_lohn;gesamtlohn<br>";
        //foreach ($print_data as $p) {
        //    echo '"' . $p['personalnummer'] . '";"' . $p['gueltig_ab'] . '";"' . $p['wochenstunden'] . '";"' . $p['tarif'] . '";"' . $p['tariflohn'] . '";"' . $p['uebertariflicher_lohn'] . '";"' . $p['gesamtlohn'] . '"<br>';
        //}
        //$seconds = 1.2 * 3600;
        //$dateinterval = new \DateInterval("PT" . $seconds . "S");
        //echo $dateinterval->format("%s");
        //gmdate
        //$test = new \DateTime();
        //$test->setISODate(2017, 53, 1);
        //echo $test->format("Y-m-d");

        //echo '<a href="mailto:?subject=Schichtplan&body=Sehr geehrter Herr Rahimic%2C%0D%0A%0D%0Abitte notieren Sie sich folgende Schichten%3A%0D%0A%0D%0AMontag%2C 31.07.2017 von 19%3A15 bis 00%3A15 Uhr%0D%0AEdeka Daniela Iden e.K.%0D%0AWilhelmsruher Damm 231%2C 13435 Berlin Wittenau%0D%0A%0D%0ADienstag%2C 01.08.2017 von 19%3A15 bis 00%3A15 Uhr%0D%0AEdeka Daniela Iden e.K.%0D%0AWilhelmsruher Damm 231%2C 13435 Berlin Wittenau%0D%0A%0D%0AMittwoch%2C 02.08.2017 von 19%3A15 bis 00%3A15 Uhr%0D%0AEdeka Daniela Iden e.K.%0D%0AWilhelmsruher Damm 231%2C 13435 Berlin Wittenau%0D%0A%0D%0ADonnerstag%2C 03.08.2017 von 18%3A15 bis 22%3A15 Uhr%0D%0AEdeka Ute Bestvater e.K.%0D%0AZabel-Kr%C3%BCger-Damm 25%2C 13469 Berlin Waidmannslust%0D%0A%0D%0ASamstag%2C 05.08.2017 von 18%3A15 bis 22%3A15 Uhr%0D%0AEdeka Ute Bestvater e.K.%0D%0AZabel-Kr%C3%BCger-Damm 25%2C 13469 Berlin Waidmannslust%0D%0A%0D%0ABitte denken Sie unbedingt daran%2C den Erhalt dieser Nachricht kurz zu best%C3%A4tigen. Vielen Dank.%0D%0A%0D%0AMit freundlichen Gr%C3%BC%C3%9Fen%2C%0D%0AIhre Dispo">Email</a>';

        //$test = '1,97,44964';
        //$test = (float) str_replace(',', '.', $test);
        //echo $test;

        //$auftraege = \ttact\Models\AuftragModel::findAll($this->db, $this->current_user);
        //foreach ($auftraege as $auftrag) {
        //    if ($auftrag->getVon()->format("Y-m-d") != $auftrag->getBis()->format("Y-m-d")) {
        //        echo $auftrag->getID() . ", ";
        //    }
        //}

        $this->template = 'blank';
    }

    public function transfer()
    {
        $aps_db = new \ttact\Database('aps2.c-multimedia.de', 'aps', 'dJqQQWss2Bj9rzhv', 'aps');

        //********************************************
        $aid_to_id = [];
        $counter = 1;
        $aps_abteilungen = $aps_db->getRows('abteilung', ['name', 'aid'], [], ['aid']);
        foreach ($aps_abteilungen as $row) {
            $aid_to_id[$row['aid']] = $counter;
            $counter++;
        }
        //********************************************
        $week = new \DateTime("now");

        $aps_auftraege = $aps_db->getRows('auftraege', ['kunde', 'abteilung', 'mid', 'y', 'kw', 'tag', 'von', 'bis'], ['y' => '2017', 'kw' => ltrim($week->format("W"), '0')]);
        foreach ($aps_auftraege as $row) {
            $aps_kunde = $aps_db->getFirstRow('kunden', ['kundenID' => $row['kunde']], ['kundennummer']);
            $ttact_kunde = \ttact\Models\KundeModel::findByKundennummer($this->db, $aps_kunde['kundennummer']);
            if ($ttact_kunde instanceof \ttact\Models\KundeModel) {
                $von = new \DateTime("now");
                $von->setTimestamp($row['von']);
                $von->setISODate($row['y'], $row['kw'], $row['tag']);

                $bis = new \DateTime("now");
                $bis->setTimestamp($row['bis']);
                $bis->setISODate($row['y'], $row['kw'], $row['tag']);

                if ($bis < $von) {
                    $bis->add(new \DateInterval("P0000-00-01T00:00:00"));
                }

                $data = [
                    'kunde_id' => $ttact_kunde->getID(),
                    'abteilung_id' => $aid_to_id[$row['abteilung']],
                    'mitarbeiter_id' => '',
                    'von' => $von->format("Y-m-d H:i:s"),
                    'bis' => $bis->format("Y-m-d H:i:s"),
                    'user_id' => $this->current_user->getID()
                ];

                if ($row['mid'] != "0") {
                    $aps_mitarbeiter = $aps_db->getFirstRow('mitarbeiter', ['Id' => $row['mid']], ['Personalnr']);
                    $ttact_mitarbeiter = \ttact\Models\MitarbeiterModel::findByPersonalnummer($this->db, $aps_mitarbeiter['Personalnr']);
                    if ($ttact_mitarbeiter instanceof \ttact\Models\MitarbeiterModel) {
                        $data['mitarbeiter_id'] = $ttact_mitarbeiter->getID();
                    }
                }

                \ttact\Models\AuftragModel::createNew($this->db, $this->current_user, $data);
            }
        }

        $this->template = 'blank';
    }

    public function liste()
    {
        $aps_db = new \ttact\Database('digehe.han-solo.net', 'tps', 'jNfXW4LGAnQ69t4C', 'tps');
        //$aps_db = new \ttact\Database('liluye.han-solo.net', 'aps', 'dJqQQWss2Bj9rzhv', 'aps');

        $return_mitarbeiter = [];

        $aktive_mitarbeiter = $aps_db->getRows('mitarbeiter', ['Id', 'Personalnr', 'Familienname', 'Vorname', 'Geburtsdatum'], ['Inaktiv' => 0]);

        foreach ($aktive_mitarbeiter as $mitarbeiter) {
            $auftraege = $aps_db->getRows('auftraege', ['aID'], ['y' => '2017', 'kunde' => '122', 'mid' => $mitarbeiter['Id']]);
            if (count($auftraege) > 0) {
                $return_mitarbeiter[] = [
                    'personalnummer' => $mitarbeiter['Personalnr'],
                    'vorname' => $mitarbeiter['Vorname'],
                    'nachname' => $mitarbeiter['Familienname'],
                    'geburtsdatum' => $mitarbeiter['Geburtsdatum']
                ];
            }
        }

        $this->smarty_vars['liste'] = $return_mitarbeiter;

        $this->template = 'main';
    }

    public function liste2()
    {
        //$db = new \ttact\Database('digehe.han-solo.net', 'tps', 'jNfXW4LGAnQ69t4C', 'tps');
        $db = new \ttact\Database('liluye.han-solo.net', 'aps', 'dJqQQWss2Bj9rzhv', 'aps');

        $return_mitarbeiter = [];

        $aktive_mitarbeiter = $db->query("SELECT * FROM mitarbeiter, mitarbeiter_intern WHERE mitarbeiter.Id = mitarbeiter_intern.MitarbeiterId AND (mitarbeiter_intern.Austrittsdatum = '0000-00-00' OR mitarbeiter_intern.Austrittsdatum > '2017-08-31')");

        foreach ($aktive_mitarbeiter as $mitarbeiter) {
            $auftraege = $db->getRows('auftraege', [], ['y' => '2017', 'kw' => '30', 'mid' => $mitarbeiter['Id'], 'deletetime' => '0']);
            if (count($auftraege) == 0) {
                $letztes_mal_gearbeitet = '';

                $abfrage = $db->getFirstRow('auftraege', ['mid' => $mitarbeiter['Id'], 'deletetime' => '0'], [], ['y DESC', 'kw DESC', 'tag DESC'], '');
                if (isset($abfrage['aID'])) {
                    $kunde = $db->getFirstRow('kunden', ['kundenID' => $abfrage['kunde']]);

                    $datetime = new \DateTime();
                    $datetime->setISODate($abfrage['y'], $abfrage['kw'], $abfrage['tag']);
                    $letztes_mal_gearbeitet = $datetime->format("Y-m-d") . " für den Kunden " . $kunde['kundennummer'];
                }

                $return_mitarbeiter[] = [
                    'personalnummer' => $mitarbeiter['Personalnr'],
                    'vorname' => $mitarbeiter['Vorname'],
                    'nachname' => $mitarbeiter['Familienname'],
                    'letztes_mal_gearbeitet' => $letztes_mal_gearbeitet
                ];
            }
        }

        $this->smarty_vars['liste'] = $return_mitarbeiter;

        $this->template = 'main';
    }

    public function liste3()
    {
        $db = new \ttact\Database('digehe.han-solo.net', 'tps', 'jNfXW4LGAnQ69t4C', 'tps');
        //$db = new \ttact\Database('liluye.han-solo.net', 'aps', 'dJqQQWss2Bj9rzhv', 'aps');

        $return_mitarbeiter = [];

        $aktive_mitarbeiter = $db->query("SELECT * FROM mitarbeiter, mitarbeiter_intern WHERE mitarbeiter.Id = mitarbeiter_intern.MitarbeiterId AND (mitarbeiter_intern.Austrittsdatum = '0000-00-00' OR mitarbeiter_intern.Austrittsdatum > '2017-08-31')");

        foreach ($aktive_mitarbeiter as $mitarbeiter) {
            $auftraege = $db->getRows('auftraege', [], ['y' => '2017', 'kw' => '30', 'mid' => $mitarbeiter['Id'], 'deletetime' => '0']);
            if (count($auftraege) == 0) {
                $letztes_mal_gearbeitet = '';

                $abfrage = $db->getFirstRow('auftraege', ['mid' => $mitarbeiter['Id'], 'deletetime' => '0'], [], ['y DESC', 'kw DESC', 'tag DESC'], '');
                if (isset($abfrage['aID'])) {
                    $kunde = $db->getFirstRow('kunden', ['kundenID' => $abfrage['kunde']]);

                    $datetime = new \DateTime();
                    $datetime->setISODate($abfrage['y'], $abfrage['kw'], $abfrage['tag']);
                    $letztes_mal_gearbeitet = $datetime->format("Y-m-d") . " für den Kunden " . $kunde['kundennummer'];
                }

                $return_mitarbeiter[] = [
                    'personalnummer' => $mitarbeiter['Personalnr'],
                    'vorname' => $mitarbeiter['Vorname'],
                    'nachname' => $mitarbeiter['Familienname'],
                    'letztes_mal_gearbeitet' => $letztes_mal_gearbeitet
                ];
            }
        }

        $this->smarty_vars['liste'] = $return_mitarbeiter;

        $this->template = 'main';
    }

    public function test()
    {
        $database_output = "00:30:00";

        $test = new \DateInterval("P0000-00-00T" . $database_output);

        echo $test->format("%h:%i");

        $this->template = 'blank';
    }

    public function liste()
    {
        $tps_db = new \ttact\Database('digehe.han-solo.net', 'tps', 'jNfXW4LGAnQ69t4C', 'tps');

        $ausscheidende_mitarbeiter = $tps_db->getRowsQuery("SELECT mitarbeiter.Id, mitarbeiter.Personalnr, mitarbeiter.Anrede, mitarbeiter.Familienname, mitarbeiter.Vorname, mitarbeiter_intern.Eintrittsdatum, mitarbeiter_intern.Austrittsdatum FROM mitarbeiter, mitarbeiter_intern WHERE mitarbeiter.Id = mitarbeiter_intern.MitarbeiterId AND mitarbeiter_intern.Austrittsdatum = '2017-08-03'");

        foreach ($ausscheidende_mitarbeiter as $row) {
            $still_active = false;

            $schichten = $tps_db->getRowsQuery("SELECT * FROM auftraege WHERE mid = '".$row['Id']."' AND y = 2017 AND kw >= 31 AND kw <= 35");
            if (count($schichten) > 0) {
                $still_active = true;
            } else {
                $kalendereintraege = $tps_db->getRowsQuery("SELECT * FROM mitarbeiter_kalender WHERE userID = '".$row['Id']."' AND date_start LIKE '2017-07%'");
                if (count($kalendereintraege) > 0) {
                    $still_active = true;
                }
            }

            if ($still_active) {
                echo $row['Personalnr'] . ", ";
            }
        }

        $this->template = 'blank';
    }

    public function index5()
    {
        $alle_mitarbeiter = \ttact\Models\MitarbeiterModel::findAll($this->db);
        foreach ($alle_mitarbeiter as $mitarbeiter) {
            $eintritt = $mitarbeiter->getEintritt();
            $austritt = $mitarbeiter->getAustritt();

            if ($eintritt instanceof \DateTime) {

            }
        }

        $this->template = 'blank';
    }

    public function index6()
    {
        $period = new Period(new \DateTime("2017-07-01 00:00:00"), new \DateTime("2017-07-31 00:00:00"));
        foreach ($period->getDatePeriod(new \DateInterval("P0000-00-01T00:00:00")) as $day) {
            echo $day->format("Y-m-d H:i:s") . "<br>";
        }
        $this->template = 'blank';
    }

    public function index7()
    {
        $monatsanfang = new \DateTime("2017-07-01 00:00:00");

        $alle_mitarbeiter = \ttact\Models\MitarbeiterModel::findAllLohnberechnung($this->db);
        foreach ($alle_mitarbeiter as $mitarbeiter) {
            $tagessoll_berechnen = false;

            if ($mitarbeiter->getAustritt() instanceof \DateTime) {
                if ($mitarbeiter->getAustritt() >= $monatsanfang) {
                    $tagessoll_berechnen = true;
                }
            } else {
                $tagessoll_berechnen = true;
            }

            if ($tagessoll_berechnen && $mitarbeiter->getEintritt() instanceof \DateTime) {
                // April
                $tagessoll_april = \ttact\Models\TagessollModel::findByJahrMonatMitarbeiter($this->db, 2017, 4, $mitarbeiter->getID());
                if ($tagessoll_april instanceof \ttact\Models\TagessollModel) {
                    echo "Achtung: Für den Mitarbeiter " . $mitarbeiter->getPersonalnummer() . " existiert für 2017-04 bereits ein Tagessoll-Eintrag.<br>";
                } else {
                    $data = [
                        'mitarbeiter_id' => $mitarbeiter->getID(),
                        'jahr' => 2017,
                        'monat' => 4,
                        'tagessoll' => -1,
                        'tagessoll_montag' => -1,
                        'tagessoll_dienstag' => -1,
                        'tagessoll_mittwoch' => -1,
                        'tagessoll_donnerstag' => -1,
                        'tagessoll_freitag' => -1,
                        'tagessoll_samstag' => -1,
                        'tagessoll_sonntag' => -1
                    ];
                    $tagessoll_april = \ttact\Models\TagessollModel::createNew($this->db, $data);
                    if (!$tagessoll_april instanceof \ttact\Models\TagessollModel) {
                        echo "Achtung: Für den Mitarbeiter " . $mitarbeiter->getPersonalnummer() . " konnte für 2017-04 kein Tagessoll-Eintrag angelegt werden.<br>";
                    }
                }

                // Mai
                $tagessoll_mai = \ttact\Models\TagessollModel::findByJahrMonatMitarbeiter($this->db, 2017, 5, $mitarbeiter->getID());
                if ($tagessoll_mai instanceof \ttact\Models\TagessollModel) {
                    echo "Achtung: Für den Mitarbeiter " . $mitarbeiter->getPersonalnummer() . " existiert für 2017-05 bereits ein Tagessoll-Eintrag.<br>";
                } else {
                    $data = [
                        'mitarbeiter_id' => $mitarbeiter->getID(),
                        'jahr' => 2017,
                        'monat' => 5,
                        'tagessoll' => -1,
                        'tagessoll_montag' => -1,
                        'tagessoll_dienstag' => -1,
                        'tagessoll_mittwoch' => -1,
                        'tagessoll_donnerstag' => -1,
                        'tagessoll_freitag' => -1,
                        'tagessoll_samstag' => -1,
                        'tagessoll_sonntag' => -1
                    ];
                    $tagessoll_mai = \ttact\Models\TagessollModel::createNew($this->db, $data);
                    if (!$tagessoll_mai instanceof \ttact\Models\TagessollModel) {
                        echo "Achtung: Für den Mitarbeiter " . $mitarbeiter->getPersonalnummer() . " konnte für 2017-05 kein Tagessoll-Eintrag angelegt werden.<br>";
                    }
                }

                // Juni
                $tagessoll_juni = \ttact\Models\TagessollModel::findByJahrMonatMitarbeiter($this->db, 2017, 6, $mitarbeiter->getID());
                if ($tagessoll_juni instanceof \ttact\Models\TagessollModel) {
                    echo "Achtung: Für den Mitarbeiter " . $mitarbeiter->getPersonalnummer() . " existiert für 2017-06 bereits ein Tagessoll-Eintrag.<br>";
                } else {
                    $data = [
                        'mitarbeiter_id' => $mitarbeiter->getID(),
                        'jahr' => 2017,
                        'monat' => 6,
                        'tagessoll' => -1,
                        'tagessoll_montag' => -1,
                        'tagessoll_dienstag' => -1,
                        'tagessoll_mittwoch' => -1,
                        'tagessoll_donnerstag' => -1,
                        'tagessoll_freitag' => -1,
                        'tagessoll_samstag' => -1,
                        'tagessoll_sonntag' => -1
                    ];
                    $tagessoll_juni = \ttact\Models\TagessollModel::createNew($this->db, $data);
                    if (!$tagessoll_juni instanceof \ttact\Models\TagessollModel) {
                        echo "Achtung: Für den Mitarbeiter " . $mitarbeiter->getPersonalnummer() . " konnte für 2017-06 kein Tagessoll-Eintrag angelegt werden.<br>";
                    }
                }
            }
        }
        $this->template = 'blank';
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
                        foreach ($file as &$row) {
                            $row = utf8_encode($row);
                            $array = explode(';', $row);
                            if (count($array) != 2) {
                                $error = "Etwas stimmt mit der Anzahl der exportierten Spalten nicht.";
                                break;
                            } elseif (!$this->user_input->getOnlyNumbers($array[0]) == $array[0]) {
                                $error = "Etwas stimmt mit der Datei nicht: die Personalnummer beinhaltet auch nichtnumerische Zeichen.";
                                break;
                            } else {
                                foreach ($array as &$col) {
                                    $col = filter_var(trim($col), FILTER_SANITIZE_STRING);
                                }

                                $mitarbeiter = \ttact\Models\MitarbeiterModel::findByPersonalnummer($this->db, (int) $this->user_input->getOnlyNumbers($array[0]));
                                if ($mitarbeiter instanceof \ttact\Models\MitarbeiterModel) {
                                    $azk_model = \ttact\Models\ArbeitszeitkontoModel::findByYearMonthMitarbeiter($this->db, 2017, 7, $mitarbeiter->getID());
                                    if (!$azk_model instanceof \ttact\Models\ArbeitszeitkontoModel) {
                                        $data = [
                                            'mitarbeiter_id' => $mitarbeiter->getID(),
                                            'jahr' => 2017,
                                            'monat' => 7,
                                            'stunden' => (float) $array[1]
                                        ];
                                        $azk_model = \ttact\Models\ArbeitszeitkontoModel::createNew($this->db, $data);
                                        if (!$azk_model instanceof \ttact\Models\ArbeitszeitkontoModel) {
                                            echo "Achtung: Für den Mitarbeiter " . $mitarbeiter->getPersonalnummer() . " konnte ein AZK-Eintrag nicht angelegt werden.<br>";
                                        }
                                    } elseif(!$azk_model->setStunden((float) $array[1])) {
                                        $error .= "Achtung! AZK des Mitarbeiters " . $this->user_input->getOnlyNumbers($array[0]) . " konnte nicht aktualisiert werden!<br>";
                                    }
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

    public function index11()
    {
        $stunden = 0;

        $schichten = \ttact\Models\AuftragModel::findByStartEndMitarbeiterLohnberechnung($this->db, $this->current_user, new \DateTime("2017-08-01 00:00:00"), new \DateTime("2017-08-31 23:59:59"), 860);
        foreach ($schichten as $schicht) {
            $stunden += $schicht->getHours();
        }

        echo $stunden;
        $this->template = 'blank';
    }

    public function tempo()
    {
        echo print_r(unserialize(base64_decode("YTozOntzOjE0OiJyZWNobnVuZ2JldHJhZyI7YTozOntzOjU6Im5ldHRvIjtkOjY0My41O3M6NjoiYnJ1dHRvIjtkOjc2NS43Njk5OTk5OTk5OTk5ODtzOjQ6Im13c3QiO2Q6MTIyLjI2OTk5OTk5OTk5OTk4O31zOjE1OiJyZWNobnVuZ3Nwb3N0ZW4iO2E6MTp7aTowO2E6NDp7czoxMjoibGVpc3R1bmdzYXJ0IjtzOjc6IlN0dW5kZW4iO3M6NToibWVuZ2UiO3M6NToiMzkuMDAiO3M6MTE6ImVpbnplbHByZWlzIjtzOjU6IjE2LjUwIjtzOjExOiJnZXNhbXRwcmVpcyI7ZDo2NDMuNTt9fXM6MTA6InN0YW1tZGF0ZW4iO2E6MTA6e3M6NDoicnZvbiI7czoxMDoiMDguMDEuMjAxOCI7czo0OiJyYmlzIjtzOjEwOiIxMy4wMS4yMDE4IjtzOjY6InJrdW5kZSI7czozOiIxMzgiO3M6NToicmRhdGUiO3M6MTA6IjE3LjAxLjIwMTgiO3M6MTY6InJyZWNobnVuZ3NudW1tZXIiO3M6MDoiIjtzOjE4OiJybGVpc3R1bmdzemVpdHJhdW0iO3M6MTE6IkphbnVhciAyMDE4IjtzOjEzOiJyemFobHVuZ3N6aWVsIjtzOjEwOiIyNy4wMS4yMDE4IjtzOjc6Inpwb3N0ZW4iO2E6NDp7czoxMjoibGVpc3R1bmdzYXJ0IjthOjE6e2k6MDtzOjE6IjAiO31zOjE2OiJsZWlzdHVuZ3NhcnR0ZXh0IjthOjE6e2k6MDtzOjA6IiI7fXM6NToibWVuZ2UiO2E6MTp7aTowO3M6MDoiIjt9czoyOiJlcCI7YToxOntpOjA7czowOiIiO319czoxMToiYWRkcmVjaG51bmciO3M6MDoiIjtzOjc6InJhbnJlZGUiO3M6Mjc6IlNlaHIgZ2VlaHJ0ZXIgSGVyciBOaWVtYW5uLCI7fX0=")));
        $this->template = 'blank';
    }

    public function kundenbeschraenkungen()
    {
        foreach (\ttact\Models\UserModel::findAll($this->db) as $user) {
            if ($user->kunde_id != '') {
                $data = [
                    'user_id' => $user->getID(),
                    'kunde_id' => $user->kunde_id
                ];

                $kundenbeschraenkung = \ttact\Models\KundenbeschraenkungModel::createNew($this->db, $data);
                if (!$kundenbeschraenkung instanceof \ttact\Models\KundenbeschraenkungModel) {
                    echo 'Fehler<br>';
                }
            }
        }

        $this->template = 'blank';
    }

    public function tarif()
    {
        $tariflohnbetrag = \ttact\Models\TariflohnbetragModel::findByTarifAndDatum($this->db, 1, new \DateTime('2018-07-04'));

        var_dump($tariflohnbetrag);

        $this->template = 'blank';
    }

    public function kundenkondition() {
        $kundenkondition = \ttact\Models\KundenkonditionModel::findForKundeAbteilungDate($this->db, 19, 4, new \DateTime('2018-05-01 00:00:00'));

        var_dump($kundenkondition);

        $this->template = 'blank';
    }

    public function decrypt()
    {
        echo base64_decode(strrev(hex2bin('3d3d516343746d4d6d6c315669563362')));

        $this->template = 'blank';
    }
    */

    public function phpinfo()
    {
        phpinfo();
        $this->template = 'blank';
    }
}
