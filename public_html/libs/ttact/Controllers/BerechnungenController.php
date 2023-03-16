<?php

namespace ttact\Controllers;

use League\Period\Period;

class BerechnungenController extends Controller
{
    public function uebersichtTPS()
    {
        $this->template = '404';
    }

    public function uebersicht()
    {
        if ($this->user_input->getPostParameter('action') == 'exportieren') {
            $this->misc_utils->sendCSVHeader($this->user_input->getPostParameter('filename') . ".csv");
            $array = $this->user_input->getArrayPostParameter("export");
            echo mb_convert_encoding('Personalnr.;Nachname;Vorname;Eintritt;Austritt;Wochenstunden;Stunden Krankheit;Stunden Urlaub;Stunden Feiertag;Stunden Import;Stunden Schichten;Stunden Insgesamt;Stunden Soll;AZK Vormonat;AZK aktuell;', 'UTF-16LE', 'UTF-8') . PHP_EOL;
            foreach ($array as $row) {
                echo mb_convert_encoding(base64_decode($row), 'UTF-16LE', 'UTF-8') . PHP_EOL;
            }

            $this->template = 'blank';
        } else {
            $error = "";

            $now = new \DateTime("now");
            $jahr = (int) $now->format("Y");
            $monat = (int) $now->format("m");
            if ($monat == 1) {
                $jahr--;
                $monat = 12;
            } else {
                $monat--;
            }
            // -----------
            $i = [
                'jahr'  => '',
                'monat' => '',
                'action' => ''
            ];
            foreach ($i as $key => $value) {
                $i[$key] = $this->user_input->getPostParameter($key);
            }
            $i['jahr'] = (int) $this->user_input->getOnlyNumbers($i['jahr']);
            $i['monat'] = (int) $this->user_input->getOnlyNumbers($i['monat']);

            if ($i['monat'] != '') {
                if ($i['jahr'] >= 1000 && $i['jahr'] <= 9999 && $i['monat'] >= 1 && $i['monat'] <= 12) {
                    $liste = [];

                    $jahr = &$i['jahr'];
                    $monat = &$i['monat'];

                    // Monatsanfang
                    $monatsanfang = new \DateTime("now");
                    $monatsanfang->setDate($jahr, $monat, 1);
                    $monatsanfang->setTime(0, 0, 0);

                    // Monatsende
                    $monatsende = clone $monatsanfang;
                    $monatsende->setDate((int)$monatsende->format("Y"), (int)$monatsende->format("m"), (int)$monatsende->format("t"));
                    $monatsende->setTime(23, 59, 59);

                    // Period Monat
                    $monat_bis = clone $monatsanfang;
                    $monat_bis->add(new \DateInterval("P0000-01-00T00:00:00"));
                    $period_monat = new Period($monatsanfang, $monat_bis);

                    // Alle relevanten Mitarbeiter
                    foreach (\ttact\Models\MitarbeiterModel::findAllLohnberechnung($this->db, $monatsanfang) as $mitarbeiter) {
                        // Tagessoll
                        $tagessoll_model = \ttact\Models\TagessollModel::findByJahrMonatMitarbeiter($this->db, $jahr, $monat, $mitarbeiter->getID());
                        $tagessoll = 0;
                        $wochentagssoll = [
                            1 => 0,
                            2 => 0,
                            3 => 0,
                            4 => 0,
                            5 => 0,
                            6 => 0,
                            7 => 0
                        ];
                        if ($tagessoll_model instanceof \ttact\Models\TagessollModel) {
                            $tagessoll = $tagessoll_model->getTagessoll();
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

                        // Eintrittsdatum
                        $eintritt = '';
                        if ($mitarbeiter->getEintritt() instanceof \DateTime) {
                            $eintritt = $mitarbeiter->getEintritt()->format('d.m.Y');
                        }

                        // Austrittsdatum
                        $austritt = '';
                        if ($mitarbeiter->getAustritt() instanceof \DateTime) {
                            $austritt = $mitarbeiter->getAustritt()->format('d.m.Y');
                        }

                        // Wochenstunden
                        $wochenstunden = 0;
                        $lohnkonfiguration = \ttact\Models\LohnkonfigurationModel::findForMitarbeiterDate($this->db, $mitarbeiter->getID(), $monatsanfang);
                        if ($lohnkonfiguration instanceof \ttact\Models\LohnkonfigurationModel) {
                            $wochenstunden = $lohnkonfiguration->getWochenstunden();
                        }
                        $wochenstunden_tagessoll = $wochenstunden / 6;

                        // Stunden Krank
                        $stunden_krankheit = 0;
                        $krankheitskalendereintraege = \ttact\Models\KalendereintragModel::findByStartEndTypeMitarbeiter($this->db, $monatsanfang, $monatsende, $mitarbeiter->getID(), 'krank_bezahlt');
                        foreach ($krankheitskalendereintraege as $kalendereintrag) {
                            $krank_bis = $kalendereintrag->getBis();
                            $krank_bis->add(new \DateInterval("P0000-00-01T00:00:00"));
                            $krank_bis->setTime(0, 0, 0);
                            $period_krankheitskalendereintrag = new Period($kalendereintrag->getVon(), $krank_bis);
                            if ($period_monat->overlaps($period_krankheitskalendereintrag)) {
                                $period_krank = $period_monat->intersect($period_krankheitskalendereintrag);
                                foreach ($period_krank->getDatePeriod(new \DateInterval("P0000-00-01T00:00:00")) as $day) {
                                    if ($day->format("N") != 7) {
                                        $stunden_krankheit += $wochenstunden_tagessoll > $tagessoll ? $wochenstunden_tagessoll : $tagessoll;
                                    }
                                }
                            }
                        }

                        // Stunden Urlaub
                        $stunden_urlaub = 0;
                        $urlaubskalendereintraege = \ttact\Models\KalendereintragModel::findByStartEndTypeMitarbeiter($this->db, $monatsanfang, $monatsende, $mitarbeiter->getID(), 'urlaub_bezahlt');
                        foreach ($urlaubskalendereintraege as $kalendereintrag) {
                            $urlaub_bis = $kalendereintrag->getBis();
                            $urlaub_bis->add(new \DateInterval("P0000-00-01T00:00:00"));
                            $urlaub_bis->setTime(0, 0, 0);
                            $period_urlaubskalendereintrag = new Period($kalendereintrag->getVon(), $urlaub_bis);
                            if ($period_monat->overlaps($period_urlaubskalendereintrag)) {
                                $period_urlaub = $period_monat->intersect($period_urlaubskalendereintrag);
                                foreach ($period_urlaub->getDatePeriod(new \DateInterval("P0000-00-01T00:00:00")) as $day) {
                                    if ($day->format("N") != 7) {
                                        $stunden_urlaub += $wochenstunden_tagessoll > $tagessoll ? $wochenstunden_tagessoll : $tagessoll;
                                    }
                                }
                            }
                        }

                        // Stunden Feiertag
                        $stunden_feiertag = 0;
                        $bezahlte_kalendereintraege = \ttact\Models\KalendereintragModel::findByStartEndTypeMitarbeiter($this->db, $monatsanfang, $monatsende, $mitarbeiter->getID(), 'feiertag_bezahlt');
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
                                        while ($prev_day->format("N") == 7 || count(\ttact\Models\KalendereintragModel::findAllByTypeDateMitarbeiter($this->db, 'feiertag_bezahlt', $prev_day, $mitarbeiter->getID())) > 0) {
                                            $prev_day->sub(new \DateInterval("P0000-00-01T00:00:00"));
                                        }
                                        $next_day = new \DateTime($day->format("Y-m-d H:i:s"));
                                        $next_day->add(new \DateInterval("P0000-00-01T00:00:00"));
                                        while ($next_day->format("N") == 7 || count(\ttact\Models\KalendereintragModel::findAllByTypeDateMitarbeiter($this->db, 'feiertag_bezahlt', $next_day, $mitarbeiter->getID())) > 0) {
                                            $next_day->add(new \DateInterval("P0000-00-01T00:00:00"));
                                        }

                                        $feiertag_bezahlen = false;

                                        $prev_day_is_UrlaubKrankSchicht = false;
                                        if (count(\ttact\Models\AuftragModel::findByStartEndMitarbeiter($this->db, $this->current_user, new \DateTime($prev_day->format("Y-m-d") . " 00:00:00"), new \DateTime($prev_day->format("Y-m-d") . " 23:59:59"), $mitarbeiter->getID())) > 0) {
                                            $prev_day_is_UrlaubKrankSchicht = true;
                                        } elseif (count(\ttact\Models\KalendereintragModel::findAllByTypeDateMitarbeiter($this->db, 'urlaub_bezahlt', $prev_day, $mitarbeiter->getID())) > 0) {
                                            $prev_day_is_UrlaubKrankSchicht = true;
                                        } elseif (count(\ttact\Models\KalendereintragModel::findAllByTypeDateMitarbeiter($this->db, 'krank_bezahlt', $prev_day, $mitarbeiter->getID())) > 0) {
                                            $prev_day_is_UrlaubKrankSchicht = true;
                                        } elseif (count(\ttact\Models\StundenimporteintragModel::findByStartEndMitarbeiter($this->db, new \DateTime($prev_day->format("Y-m-d") . " 00:00:00"), new \DateTime($prev_day->format("Y-m-d") . " 23:59:59"), $mitarbeiter->getID())) > 0) {
                                            $prev_day_is_UrlaubKrankSchicht = true;
                                        } elseif (count(\ttact\Models\KalendereintragModel::findAllByTypeDateMitarbeiter($this->db, 'benachbarten_feiertag_bezahlen', $prev_day, $mitarbeiter->getID())) > 0) {
                                            $feiertag_bezahlen = true;
                                        }

                                        $next_day_is_UrlaubKrankSchicht = false;
                                        if (count(\ttact\Models\AuftragModel::findByStartEndMitarbeiter($this->db, $this->current_user, new \DateTime($next_day->format("Y-m-d") . " 00:00:00"), new \DateTime($next_day->format("Y-m-d") . " 23:59:59"), $mitarbeiter->getID())) > 0) {
                                            $next_day_is_UrlaubKrankSchicht = true;
                                        } elseif (count(\ttact\Models\KalendereintragModel::findAllByTypeDateMitarbeiter($this->db, 'urlaub_bezahlt', $next_day, $mitarbeiter->getID())) > 0) {
                                            $next_day_is_UrlaubKrankSchicht = true;
                                        } elseif (count(\ttact\Models\KalendereintragModel::findAllByTypeDateMitarbeiter($this->db, 'krank_bezahlt', $next_day, $mitarbeiter->getID())) > 0) {
                                            $next_day_is_UrlaubKrankSchicht = true;
                                        } elseif (count(\ttact\Models\StundenimporteintragModel::findByStartEndMitarbeiter($this->db, new \DateTime($next_day->format("Y-m-d") . " 00:00:00"), new \DateTime($next_day->format("Y-m-d") . " 23:59:59"), $mitarbeiter->getID())) > 0) {
                                            $next_day_is_UrlaubKrankSchicht = true;
                                        } elseif (count(\ttact\Models\KalendereintragModel::findAllByTypeDateMitarbeiter($this->db, 'benachbarten_feiertag_bezahlen', $next_day, $mitarbeiter->getID())) > 0) {
                                            $feiertag_bezahlen = true;
                                        }

                                        if ($prev_day_is_UrlaubKrankSchicht && $next_day_is_UrlaubKrankSchicht) {
                                            $feiertag_bezahlen = true;
                                        }

                                        if ($feiertag_bezahlen) {
                                            $stunden_feiertag += $wochentagssoll[$day->format("N")];
                                        }
                                    }
                                }
                            }
                        }

                        // Stunden Import
                        $stunden_import = 0;
                        $stundenimporteintraege = \ttact\Models\StundenimporteintragModel::findByStartEndMitarbeiter($this->db, $monatsanfang, $monatsende, $mitarbeiter->getID());
                        foreach ($stundenimporteintraege as $stundenimporteintrag) {
                            $stunden_import += $stundenimporteintrag->getSeconds() / 3600;
                        }

                        // Stunden Schichten
                        $stunden_schichten = 0;
                        $schichten = \ttact\Models\AuftragModel::findByStartEndMitarbeiterLohnberechnung($this->db, $this->current_user, $monatsanfang, $monatsende, $mitarbeiter->getID());
                        foreach ($schichten as $schicht) {
                            $stunden_schichten += $schicht->getHours();
                        }

                        // Stunden Insgesamt
                        $stunden_insgesamt = $stunden_krankheit + $stunden_urlaub + $stunden_feiertag + $stunden_import + $stunden_schichten;

                        // AZK Vormonat
                        $azk_vormonat = 0;
                        $letzter_monat = clone $monatsanfang;
                        $letzter_monat->sub(new \DateInterval("P0000-01-00T00:00:00"));
                        $azk_letzter_monat_model = \ttact\Models\ArbeitszeitkontoModel::findByYearMonthMitarbeiter($this->db, (int)$letzter_monat->format("Y"), (int)$letzter_monat->format("m"), $mitarbeiter->getID());
                        if ($azk_letzter_monat_model instanceof \ttact\Models\ArbeitszeitkontoModel) {
                            $azk_vormonat = $azk_letzter_monat_model->getStunden();
                        }

                        // AZK Aktueller Monat
                        $azk_aktuell = 0;
                        $azk_dieser_monat_model = \ttact\Models\ArbeitszeitkontoModel::findByYearMonthMitarbeiter($this->db, $jahr, $monat, $mitarbeiter->getID());
                        if ($azk_dieser_monat_model instanceof \ttact\Models\ArbeitszeitkontoModel) {
                            $azk_aktuell = $azk_dieser_monat_model->getStunden();
                        }

                        // Stunden Fehlzeit
                        $stunden_fehlzeit = 0;
                        $fehlzeitenkalendereintraege = \ttact\Models\KalendereintragModel::findByStartEndMitarbeiter($this->db, $monatsanfang, $monatsende, $mitarbeiter->getID());
                        foreach ($fehlzeitenkalendereintraege as $kalendereintrag) {
                            if ($kalendereintrag->getType() == 'fehlzeit' || $kalendereintrag->getType() == 'kind_krank' || $kalendereintrag->getType() == 'krank' || $kalendereintrag->getType() == 'unentschuldigt_fehlen' || $kalendereintrag->getType() == 'urlaub_genehmigt' || $kalendereintrag->getType() == 'krank_unbezahlt' || $kalendereintrag->getType() == 'urlaub_unbezahlt') {
                                $krank_bis = $kalendereintrag->getBis();
                                $krank_bis->add(new \DateInterval("P0000-00-01T00:00:00"));
                                $krank_bis->setTime(0, 0, 0);
                                $period_krankheitskalendereintrag = new Period($kalendereintrag->getVon(), $krank_bis);
                                if ($period_monat->overlaps($period_krankheitskalendereintrag)) {
                                    $period_krank = $period_monat->intersect($period_krankheitskalendereintrag);
                                    foreach ($period_krank->getDatePeriod(new \DateInterval("P0000-00-01T00:00:00")) as $day) {
                                        if ($day->format("N") != 7) {
                                            $stunden_fehlzeit += $tagessoll;
                                        }
                                    }
                                }
                            }
                        }

                        // Stunden Soll
                        $stunden_soll = 0;
                        $anteiliger_monat = false;
                        $anteil_anfang = null;
                        $anteil_ende = null;
                        if ($mitarbeiter->getEintritt() > $monatsanfang) {
                            $anteiliger_monat = true;
                            $anteil_anfang = $mitarbeiter->getEintritt();
                            $anteil_ende = clone $monatsende;
                            if ($mitarbeiter->getAustritt() instanceof \DateTime) {
                                if ($mitarbeiter->getAustritt() < $monatsende) {
                                    $anteil_ende = $mitarbeiter->getAustritt();
                                }
                            }
                        } elseif ($mitarbeiter->getAustritt() instanceof \DateTime) {
                            if ($mitarbeiter->getAustritt() < $monatsende) {
                                $anteiliger_monat = true;
                                $anteil_anfang = clone $monatsanfang;
                                $anteil_ende = $mitarbeiter->getAustritt();
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

                            $stunden_soll = (($wochenstunden * 4.333) / $workdays_total) * $workdays - $stunden_fehlzeit;
                        } else {
                            $stunden_soll = $wochenstunden * 4.333 - $stunden_fehlzeit;
                        }

                        // Liste
                        $liste[] = [
                            'personalnummer' => $mitarbeiter->getPersonalnummer(),
                            'vorname' => $mitarbeiter->getVorname(),
                            'nachname' => $mitarbeiter->getNachname(),
                            'eintritt' => $eintritt,
                            'austritt' => $austritt,
                            'wochenstunden' => $wochenstunden,
                            'stunden_krankheit' => $stunden_krankheit,
                            'stunden_urlaub' => $stunden_urlaub,
                            'stunden_feiertag' => $stunden_feiertag,
                            'stunden_import' => $stunden_import,
                            'stunden_schichten' => $stunden_schichten,
                            'stunden_insgesamt' => $stunden_insgesamt,
                            'stunden_soll' => $stunden_soll,
                            'azk_vormonat' => $azk_vormonat,
                            'azk_aktuell' => $azk_aktuell,
                            'export' => base64_encode(
                                $mitarbeiter->getPersonalnummer() . ';' .
                                $mitarbeiter->getNachname() . ';' .
                                $mitarbeiter->getVorname() . ';' .
                                $eintritt . ';' .
                                $austritt . ';' .
                                number_format($wochenstunden, 2, ',', '') . ';' .
                                number_format($stunden_krankheit, 2, ',', '') . ';' .
                                number_format($stunden_urlaub, 2, ',', '') . ';' .
                                number_format($stunden_feiertag, 2, ',', '') . ';' .
                                number_format($stunden_import, 2, ',', '') . ';' .
                                number_format($stunden_schichten, 2, ',', '') . ';' .
                                number_format($stunden_insgesamt, 2, ',', '') . ';' .
                                number_format($stunden_soll, 2, ',', '') . ';' .
                                number_format($azk_vormonat, 2, ',', '') . ';' .
                                number_format($azk_aktuell, 2, ',', '')
                            )
                        ];
                    }

                    $this->smarty_vars['liste'] = $liste;
                } else {
                    $error = "Bitte geben Sie ein gültiges Jahr und einen gültigen Monat an.";
                }
            }

            // -----------
            $this->smarty_vars['values'] = [
                'jahr'  => $jahr,
                'monat' => $monat,
                'filename' => 'uebersichtsliste_' . $jahr . '_' . $monat
            ];

            if ($error != "") {
                $this->smarty_vars['error'] = $error;
            }
            $this->template = 'main';
        }
    }

    public function stunden()
    {
        $error = "";
        $success = "";

        $i = [
            'von'  => '',
            'bis' => '',
            'kunde' => '',
            'abteilung' => '',
            'mitarbeiter' => '',
            'action' => ''
        ];
        foreach ($i as $key => $value) {
            $i[$key] = $this->user_input->getPostParameter($key);
        }

        if ($i['action'] == 'exportieren') {
            $this->misc_utils->sendCSVHeader($this->user_input->getPostParameter('filename') . ".csv");
            $array = $this->user_input->getArrayPostParameter("export");
            echo 'Personalnr.;Nachname;Vorname;Kundennummer;Kundenname;Abteilung;Datum;Von;Bis;Pause;Stunden' . PHP_EOL;
            foreach ($array as $row) {
                echo utf8_decode($row) . PHP_EOL;
            }

            $this->template = 'blank';
        } else {
            $erlaubte_kunden_ids = [];

            $alle_kunden = [];
            if ($this->current_user->getUsergroup()->hasRight('berechnungen_stunden_alle_kunden')) {
                $alle_kunden = \ttact\Models\KundeModel::findAll($this->db);
            } elseif ($this->current_user->getUsergroup()->hasRight('berechnungen_stunden_bestimmte_kunden')) {
                $alle_kundenbeschraenkungen = \ttact\Models\KundenbeschraenkungModel::findAllByUserID($this->db, $this->current_user->getID());
                foreach ($alle_kundenbeschraenkungen as $kundenbeschraenkung) {
                    if ($kundenbeschraenkung->getKunde() instanceof \ttact\Models\KundeModel) {
                        $alle_kunden[] = $kundenbeschraenkung->getKunde();
                        $erlaubte_kunden_ids[] = $kundenbeschraenkung->getKunde()->getID();
                    }
                }
            }
            $kundenliste = [];
            foreach ($alle_kunden as $kunde) {
                $kundenliste[] = [
                    'id' => $kunde->getID(),
                    'kundennummer' => $kunde->getKundennummer(),
                    'name' => $kunde->getName()
                ];
            }
            $this->smarty_vars['kundenliste'] = $kundenliste;

            if ($i['von'] != '' || $i['bis'] != '' || $i['kunde'] != '' || $i['abteilung'] != '' || $i['mitarbeiter'] != '') {
                if ($this->user_input->isDate($i['von'])) {
                    $von = \DateTime::createFromFormat('d.m.Y', $i['von']);
                    if ($von instanceof \DateTime) {
                        if ($this->user_input->isDate($i['bis'])) {
                            $bis = \DateTime::createFromFormat('d.m.Y', $i['bis']);
                            if ($bis instanceof \DateTime) {
                                // Liste
                                $liste = [];

                                // Monatsanfang
                                $von->setTime(0, 0, 0);

                                // Monatsende
                                $bis->setTime(23, 59, 59);

                                $kunde = null;
                                if ($i['kunde'] != '') {
                                    $kunde = \ttact\Models\KundeModel::findByID($this->db, $i['kunde']);
                                }

                                $abteilung = null;
                                if ($i['abteilung'] != '') {
                                    $abteilung = \ttact\Models\AbteilungModel::findByID($this->db, $i['abteilung']);
                                }

                                $mitarbeiter = null;
                                if ($i['mitarbeiter'] != '') {
                                    $mitarbeiter = \ttact\Models\MitarbeiterModel::findByID($this->db, $i['mitarbeiter']);
                                }

                                $schichten = [];

                                if ($this->current_user->getUsergroup()->hasRight('berechnungen_stunden_alle_kunden') || ($this->current_user->getUsergroup()->hasRight('berechnungen_stunden_bestimmte_kunden') && count($erlaubte_kunden_ids) > 0)) {
                                    if ($kunde instanceof \ttact\Models\KundeModel) {
                                        $erlaubte_kunden_ids = [$kunde->getID()];
                                    }

                                    if (count($erlaubte_kunden_ids) == 0 && $abteilung == null && $mitarbeiter == null) {
                                        $schichten = \ttact\Models\AuftragModel::findByStartEndLohnberechnung($this->db, $this->current_user, $von, $bis);
                                    } elseif (count($erlaubte_kunden_ids) > 0 && $abteilung == null && $mitarbeiter == null) {
                                        $schichten = \ttact\Models\AuftragModel::findByStartEndKundenLohnberechnung($this->db, $this->current_user, $von, $bis, $erlaubte_kunden_ids);
                                    } elseif (count($erlaubte_kunden_ids) == 0 && $abteilung != null && $mitarbeiter == null) {
                                        $schichten = \ttact\Models\AuftragModel::findByStartEndAbteilungLohnberechnung($this->db, $this->current_user, $von, $bis, $abteilung->getID());
                                    } elseif (count($erlaubte_kunden_ids) == 0 && $abteilung == null && $mitarbeiter != null) {
                                        $schichten = \ttact\Models\AuftragModel::findByStartEndMitarbeiterLohnberechnung($this->db, $this->current_user, $von, $bis, $mitarbeiter->getID());
                                    } elseif (count($erlaubte_kunden_ids) > 0 && $abteilung != null && $mitarbeiter == null) {
                                        $schichten = \ttact\Models\AuftragModel::findByStartEndKundenAbteilungLohnberechnung($this->db, $this->current_user, $von, $bis, $erlaubte_kunden_ids, $abteilung->getID());
                                    } elseif (count($erlaubte_kunden_ids) > 0 && $abteilung == null && $mitarbeiter != null) {
                                        $schichten = \ttact\Models\AuftragModel::findByStartEndKundenMitarbeiterLohnberechnung($this->db, $this->current_user, $von, $bis, $erlaubte_kunden_ids, $mitarbeiter->getID());
                                    } elseif (count($erlaubte_kunden_ids) == 0 && $abteilung != null && $mitarbeiter != null) {
                                        $schichten = \ttact\Models\AuftragModel::findByStartEndAbteilungMitarbeiterLohnberechnung($this->db, $this->current_user, $von, $bis, $abteilung->getID(), $mitarbeiter->getID());
                                    } elseif (count($erlaubte_kunden_ids) > 0 && $abteilung != null && $mitarbeiter != null) {
                                        $schichten = \ttact\Models\AuftragModel::findByStartEndKundenAbteilungMitarbeiterLohnberechnung($this->db, $this->current_user, $von, $bis, $erlaubte_kunden_ids, $abteilung->getID(), $mitarbeiter->getID());
                                    } else {
                                        // error
                                    }
                                }

                                $nicht_abgeschlossene_schichten = false;

                                foreach ($schichten as $id => $schicht) {
                                    if ($schicht->getStatus() != 'archiviert') {
                                        $nicht_abgeschlossene_schichten = true;
                                        unset($schichten[$id]);
                                    }
                                }
                                $schichten = array_values($schichten);

                                if ($nicht_abgeschlossene_schichten) {
                                    $error = "<strong>Achtung:</strong> Im ausgewählten Zeitraum existieren Schichten, die nicht als abgeschlossen markiert sind. Diese werden für die Stundenberechnung ignoriert!<br>";
                                }

                                if (count($schichten) > 0) {
                                    $schichten_stunden = [];

                                    foreach ($schichten as $id => $schicht) {
                                        $liste[] = [
                                            'personalnummer' => $schicht->getMitarbeiter()->getPersonalnummer(),
                                            'nachname' => $schicht->getMitarbeiter()->getNachname(),
                                            'vorname' => $schicht->getMitarbeiter()->getVorname(),
                                            'kundennummer' => $schicht->getKunde()->getKundennummer(),
                                            'kundenname' => $schicht->getKunde()->getName(),
                                            'abteilung' => $schicht->getAbteilung()->getBezeichnung(),
                                            'datum' => $schicht->getVon()->format('d.m.Y'),
                                            'von' => $schicht->getVon()->format('H:i'),
                                            'bis' => $schicht->getBis()->format('H:i'),
                                            'pause' => $schicht->getPause()->format('%H:%I'),
                                            'stunden' => number_format($schicht->getHours(), 2, ',', '.'),
                                            'export' => $schicht->getMitarbeiter()->getPersonalnummer() . ';' . $schicht->getMitarbeiter()->getNachname() . ';' . $schicht->getMitarbeiter()->getVorname() . ';' . $schicht->getKunde()->getKundennummer() . ';' . $schicht->getKunde()->getName() . ';' . $schicht->getAbteilung()->getBezeichnung() . ';' . $schicht->getVon()->format('d.m.Y') . ';' . $schicht->getVon()->format('H:i') . ';' . $schicht->getBis()->format('H:i') . ';' . $schicht->getPause()->format('%H:%I') . ';' . number_format($schicht->getHours(), 2, ',', '.'),
                                            'insgesamt' => false
                                        ];

                                        if (!isset($schichten_stunden[$schicht->getMitarbeiter()->getID()])) {
                                            $schichten_stunden[$schicht->getMitarbeiter()->getID()] = 0;
                                        }
                                        $schichten_stunden[$schicht->getMitarbeiter()->getID()] += $schicht->getHours();

                                        if (!isset($schichten[$id + 1]) || (isset($schichten[$id + 1]) && $schichten[$id + 1]->getMitarbeiter()->getPersonalnummer() != $schicht->getMitarbeiter()->getPersonalnummer())) {
                                            $liste[] = [
                                                'personalnummer' => $schicht->getMitarbeiter()->getPersonalnummer(),
                                                'nachname' => $schicht->getMitarbeiter()->getNachname(),
                                                'vorname' => $schicht->getMitarbeiter()->getVorname(),
                                                'kundennummer' => '',
                                                'kundenname' => '',
                                                'abteilung' => '',
                                                'datum' => '',
                                                'von' => '',
                                                'bis' => '',
                                                'pause' => '',
                                                'stunden' => number_format($schichten_stunden[$schicht->getMitarbeiter()->getID()], 2, ',', '.'),
                                                'export' => '',
                                                'insgesamt' => true
                                            ];
                                        }
                                    }
                                } else {
                                    // es gibt keine Schichten anzuzeigen
                                }

                                $this->smarty_vars['liste'] = $liste;
                            } else {
                                // error
                            }
                        } else {
                            // error
                        }
                    } else {
                        // error
                    }
                } else {
                    // error
                }
            }

            // display error message
            if ($error == "" && $success != "") {
                $this->smarty_vars['success'] = $success;
            }
            if ($error != "") {
                $this->smarty_vars['error'] = rtrim($error, '<br>');
            }

            $date = new \DateTime("now");

            $this->smarty_vars['values'] = [
                'von'  => $i['von'],
                'bis' => $i['bis'],
                'kunde' => $i['kunde'],
                'mitarbeiter' => $i['mitarbeiter'],
                'abteilung' => $i['abteilung'],
                'filename' => 'stunden_' . $date->format("m") . "_" . $date->format("Y") . '.csv'
            ];

            // template settings
            $this->template = 'main';
        }
    }

    public function lohn()
    {
        $error = "";
        $success = "";

        $i = [
            'jahr'  => '',
            'monat' => '',
            'action' => ''
        ];
        foreach ($i as $key => $value) {
            $i[$key] = $this->user_input->getPostParameter($key);
        }
        $i['jahr'] = (int) $this->user_input->getOnlyNumbers($i['jahr']);
        $i['monat'] = (int) $this->user_input->getOnlyNumbers($i['monat']);
        if ($this->company != 'tps') {
            $i['fehltage_speichern'] = $this->user_input->getArrayPostParameter('fehltage_speichern');
            $i['anzahl_fehltage'] = $this->user_input->getArrayPostParameter('anzahl_fehltage');
        }

        if ($i['action'] == 'exportieren') {
            $this->misc_utils->sendCSVHeader($this->user_input->getPostParameter('filename') . ".csv");
            $array = $this->user_input->getArrayPostParameter("export");
            echo 'Personalnr.;Lohnart;Lohnsatz;Wert;Kostenstelle' . PHP_EOL;
            foreach ($array as $row) {
                echo $row . PHP_EOL;
            }

            $this->template = 'blank';
        } else {
            $now = new \DateTime("now");
            $jahr = (int) $now->format("Y");
            $monat = (int) $now->format("m");
            if ($monat == 1) {
                $jahr--;
                $monat = 12;
            } else {
                $monat--;
            }

            if ($i['jahr'] >= 1000 && $i['jahr'] <= 9999 && $i['monat'] >= 1 && $i['monat'] <= 12) {
                $jahr = &$i['jahr'];
                $monat = &$i['monat'];

                // Liste
                $liste = [];

                if ($this->company != 'tps') {
                    // Fehlzeiten
                    $fehlzeitenliste = [];
                }

                // Monatsanfang
                $monatsanfang = new \DateTime("now");
                $monatsanfang->setDate($jahr, $monat, 1);
                $monatsanfang->setTime(0, 0, 0);

                // Monatsende
                $monatsende = new \DateTime("now");
                $monatsende->setDate($jahr, $monat, (int) $monatsanfang->format("t"));
                $monatsende->setTime(23, 59, 59);

                $limit = clone $monatsanfang;
                $limit->add(new \DateInterval('P0000-01-00T00:00:00'));
                $limit->setDate($limit->format('Y'), $limit->format('m'), 15);
                if ($now <= $limit) {
                    if ($this->company != 'tps') {
                        // Fehlzeiten buchen
                        if ($i['action'] == 'fehltage_speichern') {
                            foreach ($i['fehltage_speichern'] as $personalnummer) {
                                $personalnummer = (int)$personalnummer;
                                if (isset($i['anzahl_fehltage'][$personalnummer])) {
                                    $mitarbeiter = \ttact\Models\MitarbeiterModel::findByPersonalnummer($this->db, $personalnummer);
                                    if ($mitarbeiter instanceof \ttact\Models\MitarbeiterModel) {
                                        $anzahl_fehltage = (int)$i['anzahl_fehltage'][$personalnummer];
                                        if ($anzahl_fehltage > 0) {
                                            $anzahl_gebuchter_fehltage = 0;

                                            $temp_day = clone $monatsanfang;
                                            while ($temp_day <= $monatsende && $anzahl_gebuchter_fehltage < $anzahl_fehltage) {
                                                if ($temp_day->format("N") != 7) {
                                                    $temp_day_start = $temp_day;
                                                    $temp_day_end = clone $temp_day;
                                                    $temp_day_end->setTime(23, 59, 59);

                                                    if (count(\ttact\Models\AuftragModel::findByStartEndMitarbeiter($this->db, $this->current_user, $temp_day_start, $temp_day_end, $mitarbeiter->getID())) == 0) {
                                                        if (count(\ttact\Models\KalendereintragModel::findByStartEndMitarbeiterLohnrelevant($this->db, $temp_day_start, $temp_day_end, $mitarbeiter->getID())) == 0) {
                                                            $data = ['mitarbeiter_id' => $mitarbeiter->getID(), 'von' => $temp_day_start->format("Y-m-d"), 'bis' => $temp_day_start->format("Y-m-d"), 'titel' => '', 'type' => 'fehlzeit'];
                                                            $kalendereintrag_model = \ttact\Models\KalendereintragModel::createNew($this->db, $data);
                                                            if ($kalendereintrag_model instanceof \ttact\Models\KalendereintragModel) {
                                                                $anzahl_gebuchter_fehltage++;
                                                            }
                                                        }
                                                    }
                                                }

                                                $temp_day->add(new \DateInterval("P0000-00-01T00:00:00"));
                                            }

                                            if ($anzahl_gebuchter_fehltage != $anzahl_fehltage) {
                                                if ($anzahl_gebuchter_fehltage == 0) {
                                                    $error .= "<strong>Achtung:</strong> Für den Mitarbeiter " . $personalnummer . " konnte kein einziger Fehltag von " . $anzahl_fehltage . " Fehltage(n) gebucht werden.<br>";
                                                }
                                                else {
                                                    $error .= "<strong>Achtung:</strong> Für den Mitarbeiter " . $personalnummer . " konnten nur " . $anzahl_gebuchter_fehltage . " von " . $anzahl_fehltage . " Fehltage(n) gebucht werden.<br>";
                                                }
                                            }
                                        }
                                        else {
                                            $error .= "<strong>Achtung:</strong> Für den Mitarbeiter " . $personalnummer . " wurde '0' als Anzahl der zu buchenden Fehltage eingegeben. Es wurden keine Änderungen vorgenommen.<br>";
                                        }
                                    }
                                    else {
                                        $error .= "<strong>Achtung:</strong> Für den Mitarbeiter " . $personalnummer . " gab es beim Speichern der Fehltage technische Schwierigkeiten. Es wurden keine Änderungen vorgenommen.<br>";
                                    }
                                }
                                else {
                                    $error .= "<strong>Achtung:</strong> Für den Mitarbeiter " . $personalnummer . " gab es beim Speichern der Fehltage technische Schwierigkeiten. Es wurden keine Änderungen vorgenommen.<br>";
                                }
                            }
                        }
                    }

                    // BEGIN Schleife: Alle Mitarbeiter
                    $alle_mitarbeiter = \ttact\Models\MitarbeiterModel::findAllLohnberechnung($this->db, $monatsanfang);
                    foreach ($alle_mitarbeiter as $mitarbeiter) {
                        if ($mitarbeiter->getPersonalnummer() == 2075) {
                            $test = false;
                        }

                        /*******************************
                         * 1. SCHICHTEN
                         *******************************/
                        $schichten_stunden = 0;
                        if ($this->company != 'tps') {
                            $aktueller_monat_wochentagsstunden = [
                                1 => 0,
                                2 => 0,
                                3 => 0,
                                4 => 0,
                                5 => 0,
                                6 => 0,
                                7 => 0
                            ];
                        }
                        $schichten_nachtstunden = 0;
                        $zuschlagsfaktor_nacht = 0.25;
                        if ($this->company == 'tps') {
                            $zuschlagsfaktor_nacht = 0.15;
                        }
                        $schichten_feiertagsstunden = 0;
                        $zuschlagsfaktor_feiertag = 1;
                        $schichten_sonntagsstunden = 0;
                        $zuschlagsfaktor_sonntag = 0.5;

                        if ($this->company != 'tps') {
                            $einsatzbezogene_zulage_stunden = 0;
                        }

                        // BEGIN Schleife: Alle Schichten des Mitarbeiters im Abrechnungsmonat
                        $schichten = \ttact\Models\AuftragModel::findByStartEndMitarbeiterLohnberechnung($this->db, $this->current_user, $monatsanfang, $monatsende, $mitarbeiter->getID());
                        foreach ($schichten as $schicht) {
                            if ($schicht->getStatus() == "archiviert") {
                                // Anfang und Ende der aktuellen Schicht in der Schleife
                                $von = $schicht->getVon();
                                $bis = $schicht->getBis();

                                // Stunden nach Art für die aktuelle Schicht
                                $stunden_feiertag = 0;
                                $stunden_sonntag = 0;
                                $stunden_nacht = 0;
                                $stunden_normal = 0;

                                // Zuschläge nach Art für die aktuelle Schicht
                                $zuschlagsfaktor_feiertag = 2;
                                $zuschlagsfaktor_sonntag = 1.5;
                                $zuschlagsfaktor_nacht = 0.25;
                                $nacht_von = '23:00';
                                $nacht_bis = '06:00';
                                if ($this->company == 'tps') {
                                    $zuschlagsfaktor_nacht = 0.15;
                                    $nacht_von = '00:00';
                                    $nacht_bis = '06:00';
                                }
                                $kundenkondition = \ttact\Models\KundenkonditionModel::findForKundeAbteilungDate($this->db, $schicht->getKunde()->getID(), $schicht->getAbteilung()->getID(), $von);
                                if ($kundenkondition instanceof \ttact\Models\KundenkonditionModel) {
                                    if ($this->company != 'aps') {
                                        $zuschlagsfaktor_sonntag = ($kundenkondition->getSonntagszuschlag() + 100) / 100;
                                        $zuschlagsfaktor_feiertag = ($kundenkondition->getFeiertagszuschlag() + 100) / 100;
                                        //$zuschlagsfaktor_nacht = $kundenkondition->getNachtzuschlag() / 100;
                                    }
                                    if ($kundenkondition->getNachtVon() instanceof \DateTime && $kundenkondition->getNachtBis() instanceof \DateTime) {
                                        $nacht_von = $kundenkondition->getNachtVon()->format("H:i");
                                        $nacht_bis = $kundenkondition->getNachtBis()->format("H:i");
                                    } else {
                                        if ($this->company == 'tps') {
                                            $error .= "<strong>Achtung:</strong> Für den Kunden <strong>".$schicht->getKunde()->getKundennummer()."</strong> existieren für die Abteilung <strong>".$schicht->getAbteilung()->getBezeichnung()."</strong> keine gültigen Zeiten für Nachtarbeit! Für die Berechnung werden nun die Standardnachtarbeitszeiten von 0 bis 6 Uhr genutzt!<br>";
                                        } else {
                                            $error .= "<strong>Achtung:</strong> Für den Kunden <strong>".$schicht->getKunde()->getKundennummer()."</strong> existieren für die Abteilung <strong>".$schicht->getAbteilung()->getBezeichnung()."</strong> keine gültigen Zeiten für Nachtarbeit! Für die Berechnung werden nun die Standardnachtarbeitszeiten von 23 bis 6 Uhr genutzt!<br>";
                                        }
                                    }
                                } else {
                                    if ($this->company == 'tps') {
                                        $error .= "<strong>Achtung:</strong> Für den Kunden <strong>".$schicht->getKunde()->getKundennummer()."</strong> existieren für die Abteilung <strong>".$schicht->getAbteilung()->getBezeichnung()."</strong> keine gültigen Konditionen! Für die betroffenen Schichten werden den Mitarbeitern die Standardzuschläge gezahlt (Feiertag 100%, Sonntag 50%, Nachtschicht 15% von 0 Uhr bis 6 Uhr)!<br>";
                                    } else {
                                        $error .= "<strong>Achtung:</strong> Für den Kunden <strong>".$schicht->getKunde()->getKundennummer()."</strong> existieren für die Abteilung <strong>".$schicht->getAbteilung()->getBezeichnung()."</strong> keine gültigen Konditionen! Für die betroffenen Schichten werden den Mitarbeitern die Standardzuschläge gezahlt (Feiertag 100%, Sonntag 50%, Nachtschicht 25% von 23 Uhr bis 6 Uhr)!<br>";
                                    }
                                }

                                // Stunden nach Art berechnen
                                if ($von->format("Y-m-d") == $bis->format("Y-m-d")) {
                                    // von.tag = bis.tag
                                    $this->calculateHours($this->db, $von, $bis, $mitarbeiter, $stunden_feiertag, $stunden_sonntag, $stunden_nacht, $stunden_normal, $nacht_von, $nacht_bis);
                                } else {
                                    // von.tag != bis.tag
                                    $von1 = $von;
                                    $bis1 = clone $bis;
                                    $bis1->setTime(0, 0, 0);
                                    $von2 = clone $bis1;
                                    $bis2 = $bis;

                                    $this->calculateHours($this->db, $von1, $bis1, $mitarbeiter, $stunden_feiertag, $stunden_sonntag, $stunden_nacht, $stunden_normal, $nacht_von, $nacht_bis);
                                    $this->calculateHours($this->db, $von2, $bis2, $mitarbeiter, $stunden_feiertag, $stunden_sonntag, $stunden_nacht, $stunden_normal, $nacht_von, $nacht_bis);
                                }

                                // Pause abziehen
                                $pause_std = ($schicht->getPauseSeconds() / 3600);
                                $stunden = [&$stunden_feiertag, &$stunden_sonntag, &$stunden_nacht, &$stunden_normal];
                                rsort($stunden);
                                $stunden[0] -= $pause_std;

                                // Stunden speichern
                                $schichten_stunden += $stunden_normal + $stunden_feiertag + $stunden_sonntag + $stunden_nacht;
                                if ($this->company != 'tps') {
                                    $aktueller_monat_wochentagsstunden[$von->format("N")] += $stunden_normal + $stunden_feiertag + $stunden_sonntag + $stunden_nacht;
                                }

                                $schichten_nachtstunden += $stunden_nacht;
                                $schichten_feiertagsstunden += $stunden_feiertag;
                                $schichten_sonntagsstunden += $stunden_sonntag;
                            } else {
                                $error .= "<strong>Achtung:</strong> Im ausgewählten Zeitraum existieren Schichten, die nicht als abgeschlossen markiert sind. Diese werden für die Lohnberechnung ignoriert!<br>";
                            }
                        }
                        // END Schleife: Alle Schichten des Mitarbeiters im Abrechnungsmonat

                        // BEGINN Schleife: Alle Stundenimporteinträge des Mitarbeiters im Abrechnungsmonat
                        $stundenimporteintraege = \ttact\Models\StundenimporteintragModel::findByStartEndMitarbeiter($this->db, $monatsanfang, $monatsende, $mitarbeiter->getID());
                        foreach ($stundenimporteintraege as $stundenimporteintrag) {
                            // Anfang und Ende der aktuellen Schicht in der Schleife
                            $von = $stundenimporteintrag->getVon();
                            $bis = $stundenimporteintrag->getBis();

                            // Stunden nach Art für die aktuelle Schicht
                            $stunden_feiertag = 0;
                            $stunden_sonntag = 0;
                            $stunden_nacht = 0;
                            $stunden_normal = 0;

                            // Zuschläge nach Art für die aktuelle Schicht
                            $zuschlagsfaktor_feiertag = 2;
                            $zuschlagsfaktor_sonntag = 1.5;
                            $zuschlagsfaktor_nacht = 0.25;
                            $nacht_von = '23:00';
                            $nacht_bis = '06:00';
                            if ($this->company == 'tps') {
                                $zuschlagsfaktor_nacht = 0.15;
                                $nacht_von = '00:00';
                                $nacht_bis = '06:00';
                            }

                            // Stunden nach Art berechnen
                            if ($von->format("Y-m-d") == $bis->format("Y-m-d")) {
                                // von.tag = bis.tag
                                $this->calculateHours($this->db, $von, $bis, $mitarbeiter, $stunden_feiertag, $stunden_sonntag, $stunden_nacht, $stunden_normal, $nacht_von, $nacht_bis);
                            } else {
                                // von.tag != bis.tag
                                $von1 = $von;
                                $bis1 = clone $bis;
                                $bis1->setTime(0, 0, 0);
                                $von2 = clone $bis1;
                                $bis2 = $bis;

                                $this->calculateHours($this->db, $von1, $bis1, $mitarbeiter, $stunden_feiertag, $stunden_sonntag, $stunden_nacht, $stunden_normal, $nacht_von, $nacht_bis);
                                $this->calculateHours($this->db, $von2, $bis2, $mitarbeiter, $stunden_feiertag, $stunden_sonntag, $stunden_nacht, $stunden_normal, $nacht_von, $nacht_bis);
                            }

                            // Pause abziehen
                            $pause_std = ($stundenimporteintrag->getPauseSeconds() / 3600);
                            $stunden = [&$stunden_feiertag, &$stunden_sonntag, &$stunden_nacht, &$stunden_normal];
                            rsort($stunden);
                            $stunden[0] -= $pause_std;

                            // Stunden speichern
                            $schichten_stunden += $stunden_normal + $stunden_feiertag + $stunden_sonntag + $stunden_nacht;
                            if ($this->company != 'tps') {
                                $aktueller_monat_wochentagsstunden[$von->format("N")] += $stunden_normal + $stunden_feiertag + $stunden_sonntag + $stunden_nacht;
                            }

                            $schichten_nachtstunden += $stunden_nacht;
                            $schichten_feiertagsstunden += $stunden_feiertag;
                            $schichten_sonntagsstunden += $stunden_sonntag;
                        }
                        // END Schleife: Alle Stundenimporteinträge des Mitarbeiters im Abrechnungsmonat

                        $normalstunden_lohnart = '';
                        $normalstunden_bezeichnung = '';

                        $nachtstunden_lohnart = '';
                        $nachtstunden_bezeichnung = '';

                        $sonntagsstunden_lohnart = '';
                        $sonntagsstunden_bezeichnung = '';

                        $urlaubsentgeld_lohnart = '';
                        $urlaubsentgeld_bezeichnung = '';

                        $auszahlung_resturlaub_lohnart = '';
                        $auszahlung_resturlaub_bezeichnung = '';

                        if ($this->company != 'tps') {
                            $feiertagsstunden_lohnart = '';
                            $feiertagsstunden_bezeichnung = '';

                            $sonderzahlung_urlaub_lohnart = '';
                            $sonderzahlung_urlaub_bezeichnung = '';

                            $sonderzahlung_weihnachten_lohnart = '';
                            $sonderzahlung_weihnachten_bezeichnung = '';
                        }

                        $lohnsatz = 0;
                        if ($this->company != 'tps') {
                            $einsatzbezogene_zulage = 0;
                            $einsatzbezogene_zulage_lohnart = '';
                            $einsatzbezogene_zulage_bezeichnung = '';
                        }
                        $uebertarifliche_zulage = 0;
                        $wochenstunden = 0;
                        $wochenstunden_tagessoll = 0;

                        if ($this->company != 'tps') {
                            $mehr_als_9_monate_beschaeftigt = false;
                            $mehr_als_12_monate_beschaeftigt = false;
                            if ($mitarbeiter->getEintritt() instanceof \DateTime) {
                                $monatsanfang_minus_9_monate = clone $monatsanfang;
                                $monatsanfang_minus_9_monate->sub(new \DateInterval("P0000-09-00T00:00:00"));
                                if ($mitarbeiter->getEintritt() <= $monatsanfang_minus_9_monate) {
                                    $mehr_als_9_monate_beschaeftigt = true;
                                }
                                $monatsanfang_minus_12_monate = clone $monatsanfang;
                                $monatsanfang_minus_12_monate->sub(new \DateInterval("P0001-00-00T00:00:00"));
                                if ($mitarbeiter->getEintritt() <= $monatsanfang_minus_12_monate) {
                                    $mehr_als_12_monate_beschaeftigt = true;
                                }
                            }
                        }

                        $lohnkonfiguration = \ttact\Models\LohnkonfigurationModel::findForMitarbeiterDate($this->db, $mitarbeiter->getID(), $monatsanfang);
                        if ($lohnkonfiguration instanceof \ttact\Models\LohnkonfigurationModel) {
                            $wochenstunden = (float) $lohnkonfiguration->getWochenstunden();
                            $wochenstunden_tagessoll = $wochenstunden / 6;
                            if ($wochenstunden == 0 && $schichten_stunden > 0) {
                                $error .= "<strong>Achtung:</strong> Für den Mitarbeiter ".$mitarbeiter->getPersonalnummer()." ist in der Lohnkonfiguration keine Wochenstundenanzahl eingegeben worden. Für die Lohnarten wird davon ausgegangen, dass der Mitarbeiter ein Minijobber ist." . ($this->company == 'tps' ? ' Urlaubs- und Krankheitstage werden nicht ausgezahlt.' : '') . "<br>";
                            }

                            if ($wochenstunden >= 12) {
                                if ($this->company == 'tps') {
                                    $normalstunden_lohnart = 1;
                                    $normalstunden_bezeichnung = 'Stundenlohn';

                                    $nachtstunden_lohnart = 8006;
                                    $nachtstunden_bezeichnung = 'Nachtzuschlag 15%';

                                    $sonntagsstunden_lohnart = 102;
                                    $sonntagsstunden_bezeichnung = 'Sonntagszuschlag 50%';

                                    $urlaubsentgeld_lohnart = 4;
                                    $urlaubsentgeld_bezeichnung = 'Urlaubsentgeld';

                                    $auszahlung_resturlaub_lohnart = 56;
                                    $auszahlung_resturlaub_bezeichnung = 'Urlaubsabgeltung';
                                } else {
                                    $normalstunden_lohnart = 1015;
                                    $normalstunden_bezeichnung = 'Tariflohn';

                                    $nachtstunden_lohnart = 507;
                                    $nachtstunden_bezeichnung = 'Nachtzuschlag 25%';

                                    $sonntagsstunden_lohnart = 102;
                                    $sonntagsstunden_bezeichnung = 'Sonntagszuschlag 50%';

                                    $urlaubsentgeld_lohnart = 4;
                                    $urlaubsentgeld_bezeichnung = 'Urlaubsentgeld';

                                    $auszahlung_resturlaub_lohnart = 56;
                                    $auszahlung_resturlaub_bezeichnung = 'Urlaubsabgeltung';

                                    $feiertagsstunden_lohnart = 3;
                                    $feiertagsstunden_bezeichnung = 'Feiertagsstunden';

                                    $sonderzahlung_urlaub_lohnart = 53;
                                    $sonderzahlung_urlaub_bezeichnung = 'Urlaubsgeld Arbeiter';

                                    $sonderzahlung_weihnachten_lohnart = 51;
                                    $sonderzahlung_weihnachten_bezeichnung = 'Weihnachtsgeld Arbeiter';
                                }
                            } else {
                                if ($this->company == 'tps') {
                                    $normalstunden_lohnart = 22;
                                    $normalstunden_bezeichnung = 'Minijob Stundenlohn';

                                    $nachtstunden_lohnart = 1000;
                                    $nachtstunden_bezeichnung = 'Minijob Nachtzuschlag 15%';

                                    $sonntagsstunden_lohnart = 1009;
                                    $sonntagsstunden_bezeichnung = 'Minijob Sonntagsz. 50%';

                                    $urlaubsentgeld_lohnart = 1005;
                                    $urlaubsentgeld_bezeichnung = 'Minijob Urlaubsentgeld';

                                    $auszahlung_resturlaub_lohnart = 1056;
                                    $auszahlung_resturlaub_bezeichnung = 'Urlaubsabgeltung';
                                } else {
                                    $normalstunden_lohnart = 22;
                                    $normalstunden_bezeichnung = 'Minijob Stundenlohn';

                                    $nachtstunden_lohnart = 1007;
                                    $nachtstunden_bezeichnung = 'Minijob Nachtzuschlag 25%';

                                    $sonntagsstunden_lohnart = 1009;
                                    $sonntagsstunden_bezeichnung = 'Minijob Sonntagsz. 50%';

                                    $urlaubsentgeld_lohnart = 1005;
                                    $urlaubsentgeld_bezeichnung = 'Minijob Urlaubsentgeld';

                                    $auszahlung_resturlaub_lohnart = 1056;
                                    $auszahlung_resturlaub_bezeichnung = 'Urlaubsabgeltung';

                                    $feiertagsstunden_lohnart = 1010;
                                    $feiertagsstunden_bezeichnung = 'Minijob Feiertag';

                                    $sonderzahlung_urlaub_lohnart = 1011;
                                    $sonderzahlung_urlaub_bezeichnung = 'Minijob Urlaubsgeld';

                                    $sonderzahlung_weihnachten_lohnart = 51;
                                    $sonderzahlung_weihnachten_bezeichnung = 'Weihnachtsgeld Arbeiter';
                                }
                            }
                            if ($this->company == 'tps') {
                                $lohnsatz = $lohnkonfiguration->getSollLohn();
                            } else {
                                if ($lohnkonfiguration->getTarif() instanceof \ttact\Models\TarifModel) {
                                    $tariflohnbetrag = \ttact\Models\TariflohnbetragModel::findByTarifAndDatum($this->db, $lohnkonfiguration->getTarif()->getID(), $monatsanfang);
                                    if ($tariflohnbetrag instanceof \ttact\Models\TariflohnbetragModel) {
                                        $lohnsatz = $tariflohnbetrag->getLohn();
                                        if ($mehr_als_12_monate_beschaeftigt) {
                                            $einsatzbezogene_zulage = $lohnsatz * 0.03;
                                            $einsatzbezogene_zulage_lohnart = 2020;//1020;
                                            $einsatzbezogene_zulage_bezeichnung = 'einsatzbez. Zulage 3,0%';
                                        } elseif ($mehr_als_9_monate_beschaeftigt) {
                                            $einsatzbezogene_zulage = $lohnsatz * 0.015;
                                            $einsatzbezogene_zulage_lohnart = 1019;
                                            $einsatzbezogene_zulage_bezeichnung = 'einsatzbez. Zulage 1,5%';
                                        }
                                        if ($lohnkonfiguration->getSollLohn() > $lohnsatz) {
                                            $uebertarifliche_zulage = $lohnkonfiguration->getSollLohn() - $lohnsatz - $einsatzbezogene_zulage;
                                        }
                                    } elseif ($schichten_stunden > 0) {
                                        $error .= "<strong>Achtung:</strong> Für den Mitarbeiter " . $mitarbeiter->getPersonalnummer() . " ist zum hinterlegten Tarif kein gültiger Tariflohnbetrag hinterlegt! Die Stunden werden unten angezeigt, aber mit einem Lohnsatz von 0€ nicht bezahlt.<br>";
                                    }
                                } else {
                                    $lohnsatz = $lohnkonfiguration->getSollLohn();
                                    if ($lohnkonfiguration->getSollLohn() <= 0 && $schichten_stunden > 0) {
                                        $error .= "<strong>Achtung:</strong> Für den Mitarbeiter " . $mitarbeiter->getPersonalnummer() . " ist in der Lohnkonfiguration weder ein gültiger Tarif noch ein alternativer Lohnsatz (in der Lohnkonfiguration das Feld <strong>Gesamtlohn/Std</strong>) hinterlegt! Die Stunden werden unten angezeigt, aber mit einem Lohnsatz von 0€ nicht bezahlt.<br>";
                                    }
                                }
                            }
                        } elseif ($schichten_stunden > 0) {
                            $error .= "<strong>Achtung:</strong> Für den Mitarbeiter ".$mitarbeiter->getPersonalnummer()." ist keine gültige Lohnkonfiguration hinterlegt! Für die Berechnung wird angenommen, dass der Mitarbeiter NICHT sozialversicherungspflichtig ist.<br>";
                        }

                        if ($schichten_stunden > 0) {
                            $liste[$mitarbeiter->getPersonalnummer()][] = [
                                'personalnummer' => $mitarbeiter->getPersonalnummer(),
                                'vorname' => $mitarbeiter->getVorname(),
                                'nachname' => $mitarbeiter->getNachname(),
                                'lohnart' => $normalstunden_lohnart,
                                'bezeichnung' => $normalstunden_bezeichnung,
                                'anzahl' => number_format($schichten_stunden, 2, ',', '.'),
                                'lohnsatz' => number_format($lohnsatz, 2, ',', '.'),
                                'betrag' => number_format($schichten_stunden * $lohnsatz, 2, ',', '.'),
                                'export' => $mitarbeiter->getPersonalnummer() . ';' . $normalstunden_lohnart . ';' . number_format($lohnsatz, 2, ',', '') . ';' . number_format($schichten_stunden, 2, ',', '') . ';'
                            ];
                            if ($this->company != 'tps') {
                                $einsatzbezogene_zulage_stunden += $schichten_stunden;
                            }
                        }

                        if ($this->company == 'tps') {
                            if ($schichten_nachtstunden > 0) {
                                $liste[$mitarbeiter->getPersonalnummer()][] = [
                                    'personalnummer' => $mitarbeiter->getPersonalnummer(),
                                    'vorname' => $mitarbeiter->getVorname(),
                                    'nachname' => $mitarbeiter->getNachname(),
                                    'lohnart' => $nachtstunden_lohnart,
                                    'bezeichnung' => $nachtstunden_bezeichnung,
                                    'anzahl' => number_format($schichten_nachtstunden, 2, ',', '.'),
                                    'lohnsatz' => number_format($lohnsatz + $uebertarifliche_zulage, 2, ',', '.'),
                                    'betrag' => number_format($schichten_nachtstunden * ($lohnsatz + $uebertarifliche_zulage) * $zuschlagsfaktor_nacht, 2, ',', '.'),
                                    'export' => $mitarbeiter->getPersonalnummer() . ';' . $nachtstunden_lohnart . ';' . number_format($lohnsatz + $uebertarifliche_zulage, 2, ',', '') . ';' . number_format($schichten_nachtstunden, 2, ',', '') . ';'
                                ];
                            }

                            if ($schichten_sonntagsstunden > 0) {
                                $liste[$mitarbeiter->getPersonalnummer()][] = [
                                    'personalnummer' => $mitarbeiter->getPersonalnummer(),
                                    'vorname' => $mitarbeiter->getVorname(),
                                    'nachname' => $mitarbeiter->getNachname(),
                                    'lohnart' => $sonntagsstunden_lohnart,
                                    'bezeichnung' => $sonntagsstunden_bezeichnung,
                                    'anzahl' => number_format($schichten_sonntagsstunden, 2, ',', '.'),
                                    'lohnsatz' => number_format($lohnsatz + $uebertarifliche_zulage, 2, ',', '.'),
                                    'betrag' => number_format($schichten_sonntagsstunden * ($lohnsatz + $uebertarifliche_zulage) * $zuschlagsfaktor_sonntag, 2, ',', '.'),
                                    'export' => $mitarbeiter->getPersonalnummer() . ';' . $sonntagsstunden_lohnart . ';' . number_format($lohnsatz + $uebertarifliche_zulage, 2, ',', '') . ';' . number_format($schichten_sonntagsstunden, 2, ',', '') . ';'
                                ];
                            }
                        } else {
                            if ($schichten_nachtstunden > 0) {
                                $liste[$mitarbeiter->getPersonalnummer()][] = [
                                    'personalnummer' => $mitarbeiter->getPersonalnummer(),
                                    'vorname' => $mitarbeiter->getVorname(),
                                    'nachname' => $mitarbeiter->getNachname(),
                                    'lohnart' => $nachtstunden_lohnart,
                                    'bezeichnung' => $nachtstunden_bezeichnung,
                                    'anzahl' => number_format($schichten_nachtstunden, 2, ',', '.'),
                                    'lohnsatz' => number_format($lohnsatz + $uebertarifliche_zulage + $einsatzbezogene_zulage, 2, ',', '.'),
                                    'betrag' => number_format($schichten_nachtstunden * ($lohnsatz + $uebertarifliche_zulage + $einsatzbezogene_zulage) * $zuschlagsfaktor_nacht, 2, ',', '.'),
                                    'export' => $mitarbeiter->getPersonalnummer() . ';' . $nachtstunden_lohnart . ';' . number_format($lohnsatz + $uebertarifliche_zulage + $einsatzbezogene_zulage, 2, ',', '') . ';' . number_format($schichten_nachtstunden, 2, ',', '') . ';'
                                ];
                            }

                            if ($schichten_sonntagsstunden > 0) {
                                $liste[$mitarbeiter->getPersonalnummer()][] = [
                                    'personalnummer' => $mitarbeiter->getPersonalnummer(),
                                    'vorname' => $mitarbeiter->getVorname(),
                                    'nachname' => $mitarbeiter->getNachname(),
                                    'lohnart' => $sonntagsstunden_lohnart,
                                    'bezeichnung' => $sonntagsstunden_bezeichnung,
                                    'anzahl' => number_format($schichten_sonntagsstunden, 2, ',', '.'),
                                    'lohnsatz' => number_format($lohnsatz + $uebertarifliche_zulage + $einsatzbezogene_zulage, 2, ',', '.'),
                                    'betrag' => number_format($schichten_sonntagsstunden * ($lohnsatz + $uebertarifliche_zulage + $einsatzbezogene_zulage) * $zuschlagsfaktor_sonntag, 2, ',', '.'),
                                    'export' => $mitarbeiter->getPersonalnummer() . ';' . $sonntagsstunden_lohnart . ';' . number_format($lohnsatz + $uebertarifliche_zulage + $einsatzbezogene_zulage, 2, ',', '') . ';' . number_format($schichten_sonntagsstunden, 2, ',', '') . ';'
                                ];
                            }
                        }

                        if ($schichten_feiertagsstunden > 0) {
                            $liste[$mitarbeiter->getPersonalnummer()][] = [
                                'personalnummer' => $mitarbeiter->getPersonalnummer(),
                                'vorname' => $mitarbeiter->getVorname(),
                                'nachname' => $mitarbeiter->getNachname(),
                                'lohnart' => 8011,
                                'bezeichnung' => 'Feiertagszuschlag 100%',
                                'anzahl' => number_format($schichten_feiertagsstunden, 2, ',', '.'),
                                'lohnsatz' => number_format($lohnsatz + $uebertarifliche_zulage, 2, ',', '.'),
                                'betrag' => number_format($schichten_feiertagsstunden * ($lohnsatz + $uebertarifliche_zulage) * $zuschlagsfaktor_feiertag, 2, ',', '.'),
                                'export' => $mitarbeiter->getPersonalnummer() . ';' . 8011 . ';' . number_format($lohnsatz + $uebertarifliche_zulage, 2, ',', '') . ';' . number_format($schichten_feiertagsstunden, 2, ',', '') . ';'
                            ];
                        }

                        /*******************************
                         * 2. LOHNBUCHUNGEN
                         *******************************/
                        $lohnbuchungsstunden = 0;

                        $lohnbuchungen = \ttact\Models\LohnbuchungModel::findByYearMonthMitarbeiter($this->db, $monatsanfang->format("Y"), $monatsanfang->format("m"), $mitarbeiter->getID());
                        foreach ($lohnbuchungen as $lohnbuchung) {
                            $lohnbuchungslohnart = (int) $lohnbuchung->getLohnart();
                            if ($lohnbuchungslohnart == 22 || $lohnbuchungslohnart == 1015 || $lohnbuchungslohnart == 8413) {
                                $lohnbuchungsstunden += (float) $lohnbuchung->getWert();
                            }

                            $liste[$mitarbeiter->getPersonalnummer()][] = [
                                'personalnummer' => $mitarbeiter->getPersonalnummer(),
                                'vorname' => $mitarbeiter->getVorname(),
                                'nachname' => $mitarbeiter->getNachname(),
                                'lohnart' => $lohnbuchung->getLohnart(),
                                'bezeichnung' => $lohnbuchung->getBezeichnung(),
                                'anzahl' => number_format($lohnbuchung->getWert(), 2, ',', '.'),
                                'lohnsatz' => $lohnbuchung->getFaktor() > 0 ? number_format($lohnbuchung->getFaktor(), 2, ',', '.') : '',
                                'betrag' => $lohnbuchung->getFaktor() > 0 ? number_format($lohnbuchung->getWert() * $lohnbuchung->getFaktor(), 2, ',', '.') : '',
                                'export' => $mitarbeiter->getPersonalnummer() . ';' . $lohnbuchung->getLohnart() . ';' . ($lohnbuchung->getFaktor() > 0 ? number_format($lohnbuchung->getFaktor(), 2, ',', '') : '') . ';' . number_format($lohnbuchung->getWert(), 2, ',', '') . ';'
                            ];
                        }

                        /*******************************
                         * 3. TAGESSOLL
                         *******************************/
                        $tagessoll = 0;
                        $wochentagssoll = [
                            1 => 0,
                            2 => 0,
                            3 => 0,
                            4 => 0,
                            5 => 0,
                            6 => 0,
                            7 => 0
                        ];

                        $refzeitraum_monat1_stunden = 0;
                        $refzeitraum_monat1_wochentagsstunden = [
                            1 => 0,
                            2 => 0,
                            3 => 0,
                            4 => 0,
                            5 => 0,
                            6 => 0,
                            7 => 0
                        ];
                        $refzeitraum_monat2_stunden = 0;
                        $refzeitraum_monat2_wochentagsstunden = [
                            1 => 0,
                            2 => 0,
                            3 => 0,
                            4 => 0,
                            5 => 0,
                            6 => 0,
                            7 => 0
                        ];
                        $refzeitraum_monat3_stunden = 0;
                        $refzeitraum_monat3_wochentagsstunden = [
                            1 => 0,
                            2 => 0,
                            3 => 0,
                            4 => 0,
                            5 => 0,
                            6 => 0,
                            7 => 0
                        ];

                        // Referenzzeitraum, Monat 1 (Abrechnungsmonat minus drei)
                        $referenzmonat1_anfang = clone $monatsanfang;
                        $referenzmonat1_anfang->sub(new \DateInterval("P0000-03-00T00:00:00"));

                        $referenzmonat2_anfang = clone $monatsanfang;
                        $referenzmonat2_anfang->sub(new \DateInterval("P0000-02-00T00:00:00"));

                        $referenzmonat3_anfang = clone $monatsanfang;
                        $referenzmonat3_anfang->sub(new \DateInterval("P0000-01-00T00:00:00"));

                        $theoretisch = true;

                        if ($mitarbeiter->getEintritt() <= $referenzmonat1_anfang) {
                            // alle drei Monate nach praktischem Ist berechnen
                            $theoretisch = false;
                        }

                        // alle drei Monate richtig berechnen
                        $this->calculateRefzeitraumHours($this->db, $this->current_user, $referenzmonat1_anfang, $mitarbeiter, $error, $refzeitraum_monat1_stunden, $wochenstunden, $refzeitraum_monat1_wochentagsstunden, $theoretisch);
                        $this->calculateRefzeitraumHours($this->db, $this->current_user, $referenzmonat2_anfang, $mitarbeiter, $error, $refzeitraum_monat2_stunden, $wochenstunden, $refzeitraum_monat2_wochentagsstunden, $theoretisch);
                        $this->calculateRefzeitraumHours($this->db, $this->current_user, $referenzmonat3_anfang, $mitarbeiter, $error, $refzeitraum_monat3_stunden, $wochenstunden, $refzeitraum_monat3_wochentagsstunden, $theoretisch);

                        // Tagessoll berechnen und speichern
                        $tagessoll = (($refzeitraum_monat1_stunden + $refzeitraum_monat2_stunden + $refzeitraum_monat3_stunden) / 13) / 6;
                        if ($this->company != 'tps') {
                            foreach ($wochentagssoll as $wochentag => &$soll) {
                                if ($theoretisch) {
                                    $soll = $tagessoll;
                                } else {
                                    $stunden = $refzeitraum_monat1_wochentagsstunden[$wochentag] + $refzeitraum_monat2_wochentagsstunden[$wochentag] + $refzeitraum_monat3_wochentagsstunden[$wochentag];
                                    $anzahl_wochentag = 0;
                                    $temp_day = clone $monatsanfang;
                                    $temp_day->sub(new \DateInterval("P0000-03-00T00:00:00"));
                                    while ($temp_day->format("N") != $wochentag) {
                                        $temp_day->add(new \DateInterval("P0000-00-01T00:00:00"));
                                    }
                                    while ($temp_day < $monatsanfang) {
                                        $anzahl_wochentag++;
                                        $temp_day->add(new \DateInterval("P0000-00-07T00:00:00"));
                                    }
                                    $soll = $stunden / $anzahl_wochentag;
                                }
                            }
                        }

                        $tagessoll_model = \ttact\Models\TagessollModel::findByJahrMonatMitarbeiter($this->db, (int) $monatsanfang->format("Y"), (int) $monatsanfang->format("m"), $mitarbeiter->getID());
                        if ($tagessoll_model instanceof \ttact\Models\TagessollModel) {
                            if ($this->company == 'tps') {
                                if (!$tagessoll_model->setTagessoll($tagessoll)) {
                                    $error .= "<strong>Achtung:</strong> Für den Mitarbeiter ".$mitarbeiter->getPersonalnummer()." konnte das Tagessoll nicht aktualisiert werden.<br>";
                                }
                            } else {
                                if (!$tagessoll_model->setTagessoll($tagessoll) || !$tagessoll_model->setTagessollMontag($wochentagssoll[1]) || !$tagessoll_model->setTagessollDienstag($wochentagssoll[2]) || !$tagessoll_model->setTagessollMittwoch($wochentagssoll[3]) || !$tagessoll_model->setTagessollDonnerstag($wochentagssoll[4]) || !$tagessoll_model->setTagessollFreitag($wochentagssoll[5]) || !$tagessoll_model->setTagessollSamstag($wochentagssoll[6]) || !$tagessoll_model->setTagessollSonntag($wochentagssoll[7])) {
                                    $error .= "<strong>Achtung:</strong> Für den Mitarbeiter ".$mitarbeiter->getPersonalnummer()." konnte das Tagessoll nicht aktualisiert werden.<br>";
                                }
                            }
                        } else {
                            if ($this->company == 'tps') {
                                $data = [
                                    'mitarbeiter_id' => $mitarbeiter->getID(),
                                    'jahr' => (int) $monatsanfang->format("Y"),
                                    'monat' => (int) $monatsanfang->format("m"),
                                    'tagessoll' => $tagessoll
                                ];
                            } else {
                                $data = [
                                    'mitarbeiter_id' => $mitarbeiter->getID(),
                                    'jahr' => (int) $monatsanfang->format("Y"),
                                    'monat' => (int) $monatsanfang->format("m"),
                                    'tagessoll' => $tagessoll,
                                    'tagessoll_montag' => $wochentagssoll[1],
                                    'tagessoll_dienstag' => $wochentagssoll[2],
                                    'tagessoll_mittwoch' => $wochentagssoll[3],
                                    'tagessoll_donnerstag' => $wochentagssoll[4],
                                    'tagessoll_freitag' => $wochentagssoll[5],
                                    'tagessoll_samstag' => $wochentagssoll[6],
                                    'tagessoll_sonntag' => $wochentagssoll[7]
                                ];
                            }
                            $tagessoll_model = \ttact\Models\TagessollModel::createNew($this->db, $data);
                            if (!$tagessoll_model instanceof \ttact\Models\TagessollModel) {
                                $error .= "<strong>Achtung:</strong> Für den Mitarbeiter ".$mitarbeiter->getPersonalnummer()." konnte das Tagessoll nicht gespeichert werden.<br>";
                            }
                        }

                        /*******************************
                         * 4. URLAUBSTAGE MTL GENOMMEN
                         *******************************/
                        $urlaubstage_mtl_genommen = 0;
                        $urlaubsstunden = 0;
                        $urlaubskalendereintraege = \ttact\Models\KalendereintragModel::findByStartEndTypeMitarbeiter($this->db, $monatsanfang, $monatsende, $mitarbeiter->getID(), 'urlaub_bezahlt');
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
                                        $urlaubstage_mtl_genommen++;
                                        if ($this->company == 'tps') {
                                            $urlaubsstunden += $wochenstunden_tagessoll;
                                        } else {
                                            $urlaubsstunden += $wochenstunden_tagessoll > $tagessoll ? $wochenstunden_tagessoll : $tagessoll;
                                            $aktueller_monat_wochentagsstunden[$day->format("N")] += $tagessoll;
                                        }
                                    }
                                }
                            }
                        }
                        if ($urlaubstage_mtl_genommen > 0) {
                            if ($this->company != 'tps') {
                                $liste[$mitarbeiter->getPersonalnummer()][] = [
                                    'personalnummer' => $mitarbeiter->getPersonalnummer(),
                                    'vorname' => $mitarbeiter->getVorname(),
                                    'nachname' => $mitarbeiter->getNachname(),
                                    'lohnart' => 161,
                                    'bezeichnung' => 'Urlaubstage mtl. genommen',
                                    'anzahl' => number_format($urlaubstage_mtl_genommen, 2, ',', ''),
                                    'lohnsatz' => '',
                                    'betrag' => '',
                                    'export' => $mitarbeiter->getPersonalnummer() . ';' . 161 . ';' . '' . ';' . number_format($urlaubstage_mtl_genommen, 2, ',', '') . ';'
                                ];
                            }
                        }

                        /*******************************
                         * 5. URLAUBSENTGELD
                         *******************************/
                        if ($urlaubsstunden > 0) {
                            $liste[$mitarbeiter->getPersonalnummer()][] = [
                                'personalnummer' => $mitarbeiter->getPersonalnummer(),
                                'vorname' => $mitarbeiter->getVorname(),
                                'nachname' => $mitarbeiter->getNachname(),
                                'lohnart' => $urlaubsentgeld_lohnart,
                                'bezeichnung' => $urlaubsentgeld_bezeichnung,
                                'anzahl' => number_format($urlaubsstunden, 2, ',', '.'),
                                'lohnsatz' => number_format($lohnsatz, 2, ',', '.'),
                                'betrag' => number_format($urlaubsstunden * $lohnsatz, 2, ',', '.'),
                                'export' => $mitarbeiter->getPersonalnummer() . ';' . $urlaubsentgeld_lohnart . ';' . number_format($lohnsatz, 2, ',', '') . ';' . number_format($urlaubsstunden, 2, ',', '') . ';'
                            ];
                            if ($this->company != 'tps') {
                                $einsatzbezogene_zulage_stunden += $urlaubsstunden;
                            }
                        }

                        /*******************************
                         * 6. FEIERTAGSSTUNDEN
                         *******************************/
                        if ($this->company != 'tps') {
                            $feiertagsstunden = 0;
                            $bezahlte_kalendereintraege = \ttact\Models\KalendereintragModel::findByStartEndTypeMitarbeiter($this->db, $monatsanfang, $monatsende, $mitarbeiter->getID(), 'feiertag_bezahlt');
                            $wochentage = [
                                1 => 'Montag',
                                2 => 'Dienstag',
                                3 => 'Mittwoch',
                                4 => 'Donnerstag',
                                5 => 'Freitag',
                                6 => 'Samstag',
                                7 => 'Sonntag'
                            ];
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
                                            while ($prev_day->format("N") == 7 || count(\ttact\Models\KalendereintragModel::findByTypeDateMitarbeiter($this->db, 'feiertag_bezahlt', $prev_day, $mitarbeiter->getID())) > 0) {
                                                $prev_day->sub(new \DateInterval("P0000-00-01T00:00:00"));
                                            }
                                            $next_day = new \DateTime($day->format("Y-m-d H:i:s"));
                                            $next_day->add(new \DateInterval("P0000-00-01T00:00:00"));
                                            while ($next_day->format("N") == 7 || count(\ttact\Models\KalendereintragModel::findByTypeDateMitarbeiter($this->db, 'feiertag_bezahlt', $next_day, $mitarbeiter->getID())) > 0) {
                                                $next_day->add(new \DateInterval("P0000-00-01T00:00:00"));
                                            }

                                            $feiertag_bezahlen = false;

                                            $prev_day_is_UrlaubKrankSchicht = false;
                                            if (count(\ttact\Models\AuftragModel::findByStartEndMitarbeiter($this->db, $this->current_user, new \DateTime($prev_day->format("Y-m-d") . " 00:00:00"), new \DateTime($prev_day->format("Y-m-d") . " 23:59:59"), $mitarbeiter->getID())) > 0) {
                                                $prev_day_is_UrlaubKrankSchicht = true;
                                            } elseif (count(\ttact\Models\KalendereintragModel::findAllByTypeDateMitarbeiter($this->db, 'urlaub_bezahlt', $prev_day, $mitarbeiter->getID())) > 0) {
                                                $prev_day_is_UrlaubKrankSchicht = true;
                                            } elseif (count(\ttact\Models\KalendereintragModel::findAllByTypeDateMitarbeiter($this->db, 'krank_bezahlt', $prev_day, $mitarbeiter->getID())) > 0) {
                                                $prev_day_is_UrlaubKrankSchicht = true;
                                            } elseif (count(\ttact\Models\StundenimporteintragModel::findByStartEndMitarbeiter($this->db, new \DateTime($prev_day->format("Y-m-d") . " 00:00:00"), new \DateTime($prev_day->format("Y-m-d") . " 23:59:59"), $mitarbeiter->getID())) > 0) {
                                                $prev_day_is_UrlaubKrankSchicht = true;
                                            } elseif (count(\ttact\Models\KalendereintragModel::findAllByTypeDateMitarbeiter($this->db, 'benachbarten_feiertag_bezahlen', $prev_day, $mitarbeiter->getID())) > 0) {
                                                $feiertag_bezahlen = true;
                                            }

                                            $next_day_is_UrlaubKrankSchicht = false;
                                            if (count(\ttact\Models\AuftragModel::findByStartEndMitarbeiter($this->db, $this->current_user, new \DateTime($next_day->format("Y-m-d") . " 00:00:00"), new \DateTime($next_day->format("Y-m-d") . " 23:59:59"), $mitarbeiter->getID())) > 0) {
                                                $next_day_is_UrlaubKrankSchicht = true;
                                            } elseif (count(\ttact\Models\KalendereintragModel::findAllByTypeDateMitarbeiter($this->db, 'urlaub_bezahlt', $next_day, $mitarbeiter->getID())) > 0) {
                                                $next_day_is_UrlaubKrankSchicht = true;
                                            } elseif (count(\ttact\Models\KalendereintragModel::findAllByTypeDateMitarbeiter($this->db, 'krank_bezahlt', $next_day, $mitarbeiter->getID())) > 0) {
                                                $next_day_is_UrlaubKrankSchicht = true;
                                            } elseif (count(\ttact\Models\StundenimporteintragModel::findByStartEndMitarbeiter($this->db, new \DateTime($next_day->format("Y-m-d") . " 00:00:00"), new \DateTime($next_day->format("Y-m-d") . " 23:59:59"), $mitarbeiter->getID())) > 0) {
                                                $next_day_is_UrlaubKrankSchicht = true;
                                            } elseif (count(\ttact\Models\KalendereintragModel::findAllByTypeDateMitarbeiter($this->db, 'benachbarten_feiertag_bezahlen', $next_day, $mitarbeiter->getID())) > 0) {
                                                $feiertag_bezahlen = true;
                                            }

                                            if ($prev_day_is_UrlaubKrankSchicht && $next_day_is_UrlaubKrankSchicht) {
                                                $feiertag_bezahlen = true;
                                            }

                                            if ($feiertag_bezahlen) {
                                                $feiertagsstunden += $wochentagssoll[$day->format("N")];
                                                $aktueller_monat_wochentagsstunden[$day->format("N")] += $wochentagssoll[$day->format("N")];
                                            }
                                        }
                                    }
                                }
                            }

                            if ($feiertagsstunden > 0) {
                                $liste[$mitarbeiter->getPersonalnummer()][] = [
                                    'personalnummer' => $mitarbeiter->getPersonalnummer(),
                                    'vorname' => $mitarbeiter->getVorname(),
                                    'nachname' => $mitarbeiter->getNachname(),
                                    'lohnart' => $feiertagsstunden_lohnart,
                                    'bezeichnung' => $feiertagsstunden_bezeichnung,
                                    'anzahl' => number_format($feiertagsstunden, 2, ',', '.'),
                                    'lohnsatz' => number_format($lohnsatz, 2, ',', '.'),
                                    'betrag' => number_format($feiertagsstunden * $lohnsatz, 2, ',', '.'),
                                    'export' => $mitarbeiter->getPersonalnummer() . ';' . $feiertagsstunden_lohnart . ';' . number_format($lohnsatz, 2, ',', '') . ';' . number_format($feiertagsstunden, 2, ',', '') . ';'
                                ];
                                if ($this->company != 'tps') {
                                    $einsatzbezogene_zulage_stunden += $feiertagsstunden;
                                }
                            }
                        }

                        /*******************************
                         * 7. LOHNFORTZAHLUNG
                         *******************************/
                        $lohnfortzahlungsstunden = 0;
                        $bezahlte_kalendereintraege = \ttact\Models\KalendereintragModel::findByStartEndTypeMitarbeiter($this->db, $monatsanfang, $monatsende, $mitarbeiter->getID(), 'krank_bezahlt');
                        foreach ($bezahlte_kalendereintraege as $kalendereintrag) {
                            $kalendereintrag_bis = $kalendereintrag->getBis();
                            $kalendereintrag_bis->add(new \DateInterval("P0000-00-01T00:00:00"));
                            $kalendereintrag_bis->setTime(0, 0, 0);
                            $period_kalendereintrag = new Period($kalendereintrag->getVon(), $kalendereintrag_bis);

                            if ($period_monat->overlaps($period_kalendereintrag)) {
                                $period_kalendereintrag_intersection = $period_monat->intersect($period_kalendereintrag);
                                foreach ($period_kalendereintrag_intersection->getDatePeriod(new \DateInterval("P0000-00-01T00:00:00")) as $day) {
                                    if ($day->format("N") != 7) {
                                        if ($this->company == 'tps') {
                                            $lohnfortzahlungsstunden += $wochenstunden_tagessoll;
                                        } else {
                                            $lohnfortzahlungsstunden += $wochenstunden_tagessoll > $tagessoll ? $wochenstunden_tagessoll : $tagessoll;
                                            $einsatzbezogene_zulage_stunden += $wochenstunden_tagessoll > $tagessoll ? $wochenstunden_tagessoll : $tagessoll;
                                            $aktueller_monat_wochentagsstunden[$day->format("N")] += $wochenstunden_tagessoll > $tagessoll ? $wochenstunden_tagessoll : $tagessoll;
                                        }
                                    }
                                }
                            }
                        }

                        /*******************************
                         * 8. ÜBERTARIFLICHE ZULAGE
                         *******************************/
                        if ($this->company != 'tps') {
                            if (round($uebertarifliche_zulage, 2) > 0 && ($schichten_stunden + $urlaubsstunden + $feiertagsstunden + $lohnfortzahlungsstunden) > 0) {
                                $liste[$mitarbeiter->getPersonalnummer()][] = [
                                    'personalnummer' => $mitarbeiter->getPersonalnummer(),
                                    'vorname' => $mitarbeiter->getVorname(),
                                    'nachname' => $mitarbeiter->getNachname(),
                                    'lohnart' => 2013,
                                    // 'lohnart' => 1013,
                                    'bezeichnung' => 'Übertarifliche Zulage',
                                    'anzahl' => number_format($schichten_stunden + $urlaubsstunden + $feiertagsstunden + $lohnfortzahlungsstunden, 2, ',', '.'),
                                    'lohnsatz' => number_format($uebertarifliche_zulage, 2, ',', '.'),
                                    'betrag' => number_format(($schichten_stunden + $urlaubsstunden + $feiertagsstunden + $lohnfortzahlungsstunden) * $uebertarifliche_zulage, 2, ',', '.'),
                                    'export' => $mitarbeiter->getPersonalnummer() . ';' . 2013 . ';' . number_format($uebertarifliche_zulage, 2, ',', '') . ';' . number_format($schichten_stunden + $urlaubsstunden + $feiertagsstunden + $lohnfortzahlungsstunden, 2, ',', '') . ';'
                                    // 'export' => $mitarbeiter->getPersonalnummer() . ';' . 1013 . ';' . number_format($uebertarifliche_zulage, 2, ',', '') . ';' . number_format($schichten_stunden + $urlaubsstunden + $feiertagsstunden + $lohnfortzahlungsstunden, 2, ',', '') . ';'
                                ];
                            }
                        }

                        /*******************************
                         * 9. ARBEITSZEITKONTO
                         *******************************/
                        if ($this->company != 'tps') {
                            // Stunden Fehlzeiten
                            $fehlzeitenstunden = 0;
                            $fehlzeitenkalendereintraege = \ttact\Models\KalendereintragModel::findByStartEndMitarbeiter($this->db, $monatsanfang, $monatsende, $mitarbeiter->getID());
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
                            // Monatliches Soll
                            $monats_soll = 0;
                            $anteiliger_monat = false;
                            $anteil_anfang = null;
                            $anteil_ende = null;
                            if ($mitarbeiter->getEintritt() > $monatsanfang) {
                                $anteiliger_monat = true;
                                $anteil_anfang = $mitarbeiter->getEintritt();
                                $anteil_ende = clone $monatsende;
                                if ($mitarbeiter->getAustritt() instanceof \DateTime) {
                                    if ($mitarbeiter->getAustritt() < $monatsende) {
                                        $anteil_ende = $mitarbeiter->getAustritt();
                                    }
                                }
                            } else {
                                if ($mitarbeiter->getAustritt() instanceof \DateTime) {
                                    if ($mitarbeiter->getAustritt() < $monatsende) {
                                        $anteiliger_monat = true;
                                        $anteil_anfang = clone $monatsanfang;
                                        $anteil_ende = $mitarbeiter->getAustritt();
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

                                $monats_soll = (($wochenstunden * 4.333) / $workdays_total) * $workdays - $fehlzeitenstunden;
                            } else {
                                $monats_soll = $wochenstunden * 4.333 - $fehlzeitenstunden;
                            }

                            $monats_ist = $schichten_stunden + $feiertagsstunden + $urlaubsstunden + $lohnfortzahlungsstunden + $lohnbuchungsstunden;

                            $azk_prev_month = 0;
                            $azk_this_month = 0;

                            $prev_month = clone $monatsanfang;
                            $prev_month->sub(new \DateInterval("P0000-01-00T00:00:00"));
                            $azk_model_prev_month = \ttact\Models\ArbeitszeitkontoModel::findByYearMonthMitarbeiter($this->db, (int) $prev_month->format("Y"), (int) $prev_month->format("m"), $mitarbeiter->getID());
                            if ($azk_model_prev_month instanceof \ttact\Models\ArbeitszeitkontoModel) {
                                $azk_prev_month = (float) $azk_model_prev_month->getStunden();
                            } elseif ($mitarbeiter->getEintritt() < $monatsanfang) {
                                $error .= "<strong>Achtung:</strong> Für den Mitarbeiter ".$mitarbeiter->getPersonalnummer()." ist kein AZK-Wert für ".$prev_month->format("Y-m")." vorhanden. Das Arbeitszeitkonto wurde auf den errechneten Wert des aktuellen Monats gesetzt. Stunden aus vergangenen Monaten gehen verloren.<br>";
                            }

                            $mitarbeiter_tritt_aus = false;
                            if ($mitarbeiter->getAustritt() instanceof \DateTime) {
                                if ($mitarbeiter->getAustritt() <= $monatsende) {
                                    $mitarbeiter_tritt_aus = true;
                                }
                            }

                            $mitarbeiter_wechselt_zu_minijob = false;
                            if ($wochenstunden >= 12) {
                                $naechster_monat_anfang = clone $monatsanfang;
                                $naechster_monat_anfang->add(new \DateInterval("P0000-01-00T00:00:00"));
                                $naechster_monat_lohnkonfiguration = \ttact\Models\LohnkonfigurationModel::findForMitarbeiterDate($this->db, $mitarbeiter->getID(), $naechster_monat_anfang);
                                if ($naechster_monat_lohnkonfiguration instanceof \ttact\Models\LohnkonfigurationModel) {
                                    if ($naechster_monat_lohnkonfiguration->getWochenstunden() < 12) {
                                        $mitarbeiter_wechselt_zu_minijob = true;
                                    }
                                }
                            }

                            if ($wochenstunden < 12) {
                                $azk_this_month = 0;
                            } elseif ($monats_ist == $monats_soll) {
                                if ($mitarbeiter_tritt_aus || $mitarbeiter_wechselt_zu_minijob) {
                                    if ($azk_prev_month < 0) {
                                        $liste[$mitarbeiter->getPersonalnummer()][] = [
                                            'personalnummer' => $mitarbeiter->getPersonalnummer(),
                                            'vorname' => $mitarbeiter->getVorname(),
                                            'nachname' => $mitarbeiter->getNachname(),
                                            'lohnart' => 410,
                                            'bezeichnung' => 'Manueller Zugang AZ-Konto',
                                            'anzahl' => '-' . number_format($azk_prev_month, 2, ',', '.'),
                                            'lohnsatz' => number_format($lohnsatz, 2, ',', '.'),
                                            'betrag' => '-' . number_format($azk_prev_month * $lohnsatz, 2, ',', '.'),
                                            'export' => $mitarbeiter->getPersonalnummer() . ';' . 410 . ';' . number_format($lohnsatz, 2, ',', '') . ';' . number_format($azk_prev_month, 2, ',', '') . ';'
                                        ];
                                    } else {
                                        $liste[$mitarbeiter->getPersonalnummer()][] = [
                                            'personalnummer' => $mitarbeiter->getPersonalnummer(),
                                            'vorname' => $mitarbeiter->getVorname(),
                                            'nachname' => $mitarbeiter->getNachname(),
                                            'lohnart' => 411,
                                            'bezeichnung' => 'Manueller Abgang AZ-Konto',
                                            'anzahl' => number_format($azk_prev_month, 2, ',', '.'),
                                            'lohnsatz' => number_format($lohnsatz, 2, ',', '.'),
                                            'betrag' => number_format($azk_prev_month * $lohnsatz, 2, ',', '.'),
                                            'export' => $mitarbeiter->getPersonalnummer() . ';' . 411 . ';' . number_format($lohnsatz, 2, ',', '') . ';' . number_format($azk_prev_month, 2, ',', '') . ';'
                                        ];
                                    }

                                    $azk_this_month = 0;
                                } else {
                                    $azk_this_month = $azk_prev_month;
                                }
                            } elseif ($monats_ist > $monats_soll) {
                                if ($mitarbeiter_tritt_aus || $mitarbeiter_wechselt_zu_minijob) {
                                    $auszuzahlende_stunden = $azk_prev_month + $monats_ist - $monats_soll;

                                    if ($auszuzahlende_stunden < 0) {
                                        $liste[$mitarbeiter->getPersonalnummer()][] = [
                                            'personalnummer' => $mitarbeiter->getPersonalnummer(),
                                            'vorname' => $mitarbeiter->getVorname(),
                                            'nachname' => $mitarbeiter->getNachname(),
                                            'lohnart' => 410,
                                            'bezeichnung' => 'Manueller Zugang AZ-Konto',
                                            'anzahl' => number_format($auszuzahlende_stunden, 2, ',', '.'),
                                            'lohnsatz' => number_format($lohnsatz, 2, ',', '.'),
                                            'betrag' => number_format($auszuzahlende_stunden * $lohnsatz, 2, ',', '.'),
                                            'export' => $mitarbeiter->getPersonalnummer() . ';' . 410 . ';' . number_format($lohnsatz, 2, ',', '') . ';' . number_format(abs($auszuzahlende_stunden), 2, ',', '') . ';'
                                        ];
                                    } else {
                                        $liste[$mitarbeiter->getPersonalnummer()][] = [
                                            'personalnummer' => $mitarbeiter->getPersonalnummer(),
                                            'vorname' => $mitarbeiter->getVorname(),
                                            'nachname' => $mitarbeiter->getNachname(),
                                            'lohnart' => 411,
                                            'bezeichnung' => 'Manueller Abgang AZ-Konto',
                                            'anzahl' => number_format($auszuzahlende_stunden, 2, ',', '.'),
                                            'lohnsatz' => number_format($lohnsatz, 2, ',', '.'),
                                            'betrag' => number_format($auszuzahlende_stunden * $lohnsatz, 2, ',', '.'),
                                            'export' => $mitarbeiter->getPersonalnummer() . ';' . 411 . ';' . number_format($lohnsatz, 2, ',', '') . ';' . number_format($auszuzahlende_stunden, 2, ',', '') . ';'
                                        ];
                                    }

                                    $azk_this_month = 0;
                                } else {
                                    $liste[$mitarbeiter->getPersonalnummer()][] = [
                                        'personalnummer' => $mitarbeiter->getPersonalnummer(),
                                        'vorname' => $mitarbeiter->getVorname(),
                                        'nachname' => $mitarbeiter->getNachname(),
                                        'lohnart' => 410,
                                        'bezeichnung' => 'Manueller Zugang AZ-Konto',
                                        'anzahl' => '-' . number_format($monats_ist - $monats_soll, 2, ',', '.'),
                                        'lohnsatz' => number_format($lohnsatz, 2, ',', '.'),
                                        'betrag' => '-' . number_format(($monats_ist - $monats_soll) * $lohnsatz, 2, ',', '.'),
                                        'export' => $mitarbeiter->getPersonalnummer() . ';' . 410 . ';' . number_format($lohnsatz, 2, ',', '') . ';' . number_format($monats_ist - $monats_soll, 2, ',', '') . ';'
                                    ];

                                    $azk_this_month = $azk_prev_month + $monats_ist - $monats_soll;
                                }
                            } elseif ($monats_ist < $monats_soll) {
                                if ($mitarbeiter_tritt_aus || $mitarbeiter_wechselt_zu_minijob) {
                                    $auszuzahlende_stunden = $azk_prev_month - ($monats_soll - $monats_ist);

                                    if ($auszuzahlende_stunden < 0) {
                                        $liste[$mitarbeiter->getPersonalnummer()][] = [
                                            'personalnummer' => $mitarbeiter->getPersonalnummer(),
                                            'vorname' => $mitarbeiter->getVorname(),
                                            'nachname' => $mitarbeiter->getNachname(),
                                            'lohnart' => 410,
                                            'bezeichnung' => 'Manueller Zugang AZ-Konto',
                                            'anzahl' => number_format($auszuzahlende_stunden, 2, ',', '.'),
                                            'lohnsatz' => number_format($lohnsatz, 2, ',', '.'),
                                            'betrag' => number_format($auszuzahlende_stunden * $lohnsatz, 2, ',', '.'),
                                            'export' => $mitarbeiter->getPersonalnummer() . ';' . 410 . ';' . number_format($lohnsatz, 2, ',', '') . ';' . number_format(abs($auszuzahlende_stunden), 2, ',', '') . ';'
                                        ];
                                    } else {
                                        $liste[$mitarbeiter->getPersonalnummer()][] = [
                                            'personalnummer' => $mitarbeiter->getPersonalnummer(),
                                            'vorname' => $mitarbeiter->getVorname(),
                                            'nachname' => $mitarbeiter->getNachname(),
                                            'lohnart' => 411,
                                            'bezeichnung' => 'Manueller Abgang AZ-Konto',
                                            'anzahl' => number_format($auszuzahlende_stunden, 2, ',', '.'),
                                            'lohnsatz' => number_format($lohnsatz, 2, ',', '.'),
                                            'betrag' => number_format($auszuzahlende_stunden * $lohnsatz, 2, ',', '.'),
                                            'export' => $mitarbeiter->getPersonalnummer() . ';' . 411 . ';' . number_format($lohnsatz, 2, ',', '') . ';' . number_format($auszuzahlende_stunden, 2, ',', '') . ';'
                                        ];
                                    }

                                    $azk_this_month = 0;
                                } else {
                                    if ($wochenstunden >= 12) {
                                        $liste[$mitarbeiter->getPersonalnummer()][] = [
                                            'personalnummer' => $mitarbeiter->getPersonalnummer(),
                                            'vorname' => $mitarbeiter->getVorname(),
                                            'nachname' => $mitarbeiter->getNachname(),
                                            'lohnart' => 411,
                                            'bezeichnung' => 'Manueller Abgang AZ-Konto',
                                            'anzahl' => number_format($monats_soll - $monats_ist, 2, ',', '.'),
                                            'lohnsatz' => number_format($lohnsatz, 2, ',', '.'),
                                            'betrag' => number_format(($monats_soll - $monats_ist) * $lohnsatz, 2, ',', '.'),
                                            'export' => $mitarbeiter->getPersonalnummer() . ';' . 411 . ';' . number_format($lohnsatz, 2, ',', '') . ';' . number_format($monats_soll - $monats_ist, 2, ',', '') . ';'
                                        ];
                                    }

                                    $azk_this_month = $azk_prev_month - ($monats_soll - $monats_ist);
                                }
                            }

                            $azk_model_this_month = \ttact\Models\ArbeitszeitkontoModel::findByYearMonthMitarbeiter($this->db, (int) $monatsanfang->format("Y"), (int) $monatsanfang->format("m"), $mitarbeiter->getID());
                            if ($azk_model_this_month instanceof \ttact\Models\ArbeitszeitkontoModel) {
                                if (!$azk_model_this_month->setStunden((float) $azk_this_month)) {
                                    $error .= "<strong>Achtung:</strong> Für den Mitarbeiter ".$mitarbeiter->getPersonalnummer()." konnte der AZK-Wert für diesen Monat nicht gespeichert werden.<br>";
                                }
                            } else {
                                $data = [
                                    'mitarbeiter_id' => $mitarbeiter->getID(),
                                    'jahr' => (int) $monatsanfang->format("Y"),
                                    'monat' => (int) $monatsanfang->format("m"),
                                    'stunden' => (float) $azk_this_month
                                ];
                                $azk_model_this_month = \ttact\Models\ArbeitszeitkontoModel::createNew($this->db, $data);
                                if (!$azk_model_this_month instanceof \ttact\Models\ArbeitszeitkontoModel) {
                                    $error .= "<strong>Achtung:</strong> Für den Mitarbeiter ".$mitarbeiter->getPersonalnummer()." konnte der AZK-Wert für diesen Monat nicht gespeichert werden.<br>";
                                }
                            }
                        }

                        /*******************************
                         * 10. MEHRARBEITSZUSCHLÄGE
                         *******************************/
                        if ($this->company != 'tps') {
                            if ($schichten_stunden > 0) {
                                $max = $monats_soll * 1.15;
                                if ($schichten_stunden > $max) {
                                    $mehrarbeit_stunden = $schichten_stunden - $max;

                                    if ($mehrarbeit_stunden > 0) {
                                        $liste[$mitarbeiter->getPersonalnummer()][] = [
                                            'personalnummer' => $mitarbeiter->getPersonalnummer(),
                                            'vorname' => $mitarbeiter->getVorname(),
                                            'nachname' => $mitarbeiter->getNachname(),
                                            'lohnart' => 8008,
                                            'bezeichnung' => 'Entgeld für Mehrarbeit',
                                            'anzahl' => number_format($mehrarbeit_stunden, 2, ',', '.'),
                                            'lohnsatz' => number_format($lohnsatz + $uebertarifliche_zulage, 2, ',', '.'),
                                            'betrag' => number_format($mehrarbeit_stunden * 0.25 * ($lohnsatz + $uebertarifliche_zulage), 2, ',', '.'),
                                            'export' => $mitarbeiter->getPersonalnummer() . ';' . 8008 . ';;' . number_format($mehrarbeit_stunden * 0.25 * ($lohnsatz + $uebertarifliche_zulage), 2, ',', '') . ';'
                                        ];
                                        $einsatzbezogene_zulage_stunden += $mehrarbeit_stunden * 0.25;
                                    }
                                }
                            }
                        }

                        /*******************************
                         * 11. AUSZAHLUNG RESTURLAUB
                         *******************************/
                        if ($mitarbeiter->getAustritt() instanceof \DateTime) {
                            $austrittsdatum_inclusive = $mitarbeiter->getAustritt();
                            if ($austrittsdatum_inclusive <= $monatsende) {
                                $urlaubstage_genommen_abrechnungsjahr = 0;

                                // urlaub_bezahlt without Sundays
                                $jahresanfang = new \DateTime($monatsanfang->format("Y") . "-01-01 00:00:00");
                                $austrittsdatum_exclusive = clone $austrittsdatum_inclusive;
                                $austrittsdatum_exclusive->add(new \DateInterval("P0000-00-01T00:00:00"));
                                $period_monat = new Period($jahresanfang, $austrittsdatum_exclusive);
                                $bezahlte_kalendereintraege = \ttact\Models\KalendereintragModel::findByStartEndTypeMitarbeiter($this->db, $jahresanfang, $austrittsdatum_inclusive, $mitarbeiter->getID(), 'urlaub_bezahlt');
                                foreach ($bezahlte_kalendereintraege as $kalendereintrag) {
                                    $kalendereintrag_bis = $kalendereintrag->getBis();
                                    $kalendereintrag_bis->add(new \DateInterval("P0000-00-01T00:00:00"));
                                    $kalendereintrag_bis->setTime(0, 0, 0);
                                    $period_kalendereintrag = new Period($kalendereintrag->getVon(), $kalendereintrag_bis);

                                    if ($period_monat->overlaps($period_kalendereintrag)) {
                                        $period_kalendereintrag_intersection = $period_monat->intersect($period_kalendereintrag);
                                        foreach ($period_kalendereintrag_intersection->getDatePeriod(new \DateInterval("P0000-00-01T00:00:00")) as $day) {
                                            if ($day->format("N") != 7) {
                                                $urlaubstage_genommen_abrechnungsjahr++;
                                            }
                                        }
                                    }
                                }

                                $jahresurlaubsanspruch = $mitarbeiter->getJahresurlaub() + $mitarbeiter->getResturlaubVorjahr();
                                $resturlaubstage = $jahresurlaubsanspruch - $urlaubstage_genommen_abrechnungsjahr;

                                if ($resturlaubstage != 0) {
                                    if ($this->company != 'tps') {
                                        $tagessatz = $wochenstunden_tagessoll > $tagessoll ? $wochenstunden_tagessoll : $tagessoll;

                                        $liste[$mitarbeiter->getPersonalnummer()][] = [
                                            'personalnummer' => $mitarbeiter->getPersonalnummer(),
                                            'vorname' => $mitarbeiter->getVorname(),
                                            'nachname' => $mitarbeiter->getNachname(),
                                            'lohnart' => $auszahlung_resturlaub_lohnart,
                                            'bezeichnung' => $auszahlung_resturlaub_bezeichnung,
                                            'anzahl' => number_format($resturlaubstage * $tagessatz, 2, ',', '.'),
                                            'lohnsatz' => number_format($lohnsatz + $uebertarifliche_zulage, 2, ',', '.'),
                                            'betrag' => number_format($resturlaubstage * $tagessatz * ($lohnsatz + $uebertarifliche_zulage), 2, ',', '.'),
                                            'export' => $mitarbeiter->getPersonalnummer() . ';' . $auszahlung_resturlaub_lohnart . ';;' . number_format($resturlaubstage * $tagessatz * ($lohnsatz + $uebertarifliche_zulage), 2, ',', '') . ';'
                                        ];
                                        $einsatzbezogene_zulage_stunden += $resturlaubstage * $tagessatz;

                                        $liste[$mitarbeiter->getPersonalnummer()][] = [
                                            'personalnummer' => $mitarbeiter->getPersonalnummer(),
                                            'vorname' => $mitarbeiter->getVorname(),
                                            'nachname' => $mitarbeiter->getNachname(),
                                            'lohnart' => 'PT02',
                                            'bezeichnung' => 'Alle Urlaubsansprüche [...] erledigt!!',
                                            'anzahl' => '',
                                            'lohnsatz' => '',
                                            'betrag' => '',
                                            'export' => $mitarbeiter->getPersonalnummer() . ';' . 'PT02' . ';' . '' . ';' . '' . ';'
                                        ];
                                    }
                                }
                            }
                        }

                        /*******************************
                         * 12. TAGESSOLL NEXT MONTH
                         *******************************/
                        $next_month_theoretisch = true;

                        if ($mitarbeiter->getEintritt() <= $referenzmonat2_anfang) {
                            $next_month_theoretisch = false;
                        }

                        if ($this->company == 'tps') {
                            $tagessoll_next_month = (($refzeitraum_monat2_stunden + $refzeitraum_monat3_stunden + $schichten_stunden + $lohnfortzahlungsstunden + $urlaubsstunden + $lohnbuchungsstunden) / 13) / 6;
                        } else {
                            $tagessoll_next_month = (($refzeitraum_monat2_stunden + $refzeitraum_monat3_stunden + $schichten_stunden + $lohnfortzahlungsstunden + $urlaubsstunden + $feiertagsstunden + $lohnbuchungsstunden) / 13) / 6;
                        }
                        if ($next_month_theoretisch) {
                            $tagessoll_next_month = (($refzeitraum_monat2_stunden + $refzeitraum_monat3_stunden + ($wochenstunden * 4.333)) / 13) / 6;
                        }
                        if ($this->company != 'tps') {
                            $wochentagssoll_next_month = [
                                1 => 0,
                                2 => 0,
                                3 => 0,
                                4 => 0,
                                5 => 0,
                                6 => 0,
                                7 => 0
                            ];
                            foreach ($wochentagssoll_next_month as $wochentag => &$soll) {
                                if ($next_month_theoretisch) {
                                    $soll = $tagessoll_next_month;
                                } else {
                                    $stunden = $refzeitraum_monat2_wochentagsstunden[$wochentag] + $refzeitraum_monat3_wochentagsstunden[$wochentag] + $aktueller_monat_wochentagsstunden[$wochentag];
                                    $anzahl_wochentag = 0;
                                    $temp_day = clone $monatsanfang;
                                    $temp_day->sub(new \DateInterval("P0000-02-00T00:00:00"));
                                    while ($temp_day->format("N") != $wochentag) {
                                        $temp_day->add(new \DateInterval("P0000-00-01T00:00:00"));
                                    }
                                    while ($temp_day <= $monatsende) {
                                        $anzahl_wochentag++;
                                        $temp_day->add(new \DateInterval("P0000-00-07T00:00:00"));
                                    }
                                    $soll = $stunden / $anzahl_wochentag;
                                }
                            }
                        }

                        $monatsanfang_next_month = clone $monatsanfang;
                        $monatsanfang_next_month->add(new \DateInterval("P0000-01-00T00:00:00"));
                        $tagessoll_model = \ttact\Models\TagessollModel::findByJahrMonatMitarbeiter($this->db, (int) $monatsanfang_next_month->format("Y"), (int) $monatsanfang_next_month->format("m"), $mitarbeiter->getID());
                        if ($tagessoll_model instanceof \ttact\Models\TagessollModel) {
                            if ($this->company == 'tps') {
                                if (!$tagessoll_model->setTagessoll($tagessoll_next_month)) {
                                    $error .= "<strong>Achtung:</strong> Für den Mitarbeiter ".$mitarbeiter->getPersonalnummer()." konnte das Tagessoll für den nächsten Monat nicht aktualisiert werden.<br>";
                                }
                            } else {
                                if (!$tagessoll_model->setTagessoll($tagessoll_next_month) || !$tagessoll_model->setTagessollMontag($wochentagssoll_next_month[1]) || !$tagessoll_model->setTagessollDienstag($wochentagssoll_next_month[2]) || !$tagessoll_model->setTagessollMittwoch($wochentagssoll_next_month[3]) || !$tagessoll_model->setTagessollDonnerstag($wochentagssoll_next_month[4]) || !$tagessoll_model->setTagessollFreitag($wochentagssoll_next_month[5]) || !$tagessoll_model->setTagessollSamstag($wochentagssoll_next_month[6]) || !$tagessoll_model->setTagessollSonntag($wochentagssoll_next_month[7])) {
                                    $error .= "<strong>Achtung:</strong> Für den Mitarbeiter ".$mitarbeiter->getPersonalnummer()." konnte das Tagessoll für den nächsten Monat nicht aktualisiert werden.<br>";
                                }
                            }
                        } else {
                            if ($this->company == 'tps') {
                                $data = [
                                    'mitarbeiter_id' => $mitarbeiter->getID(),
                                    'jahr' => (int) $monatsanfang_next_month->format("Y"),
                                    'monat' => (int) $monatsanfang_next_month->format("m"),
                                    'tagessoll' => $tagessoll_next_month
                                ];
                            } else {
                                $data = [
                                    'mitarbeiter_id' => $mitarbeiter->getID(),
                                    'jahr' => (int) $monatsanfang_next_month->format("Y"),
                                    'monat' => (int) $monatsanfang_next_month->format("m"),
                                    'tagessoll' => $tagessoll_next_month,
                                    'tagessoll_montag' => $wochentagssoll_next_month[1],
                                    'tagessoll_dienstag' => $wochentagssoll_next_month[2],
                                    'tagessoll_mittwoch' => $wochentagssoll_next_month[3],
                                    'tagessoll_donnerstag' => $wochentagssoll_next_month[4],
                                    'tagessoll_freitag' => $wochentagssoll_next_month[5],
                                    'tagessoll_samstag' => $wochentagssoll_next_month[6],
                                    'tagessoll_sonntag' => $wochentagssoll_next_month[7]
                                ];
                            }
                            $tagessoll_model = \ttact\Models\TagessollModel::createNew($this->db, $data);
                            if (!$tagessoll_model instanceof \ttact\Models\TagessollModel) {
                                $error .= "<strong>Achtung:</strong> Für den Mitarbeiter ".$mitarbeiter->getPersonalnummer()." konnte das Tagessoll für den nächsten Monat nicht gespeichert werden.<br>";
                            }
                        }

                        /*******************************
                         * 13. URLAUBS- UND WEIHNACHTSGELD
                         *******************************/
                        if ($this->company != 'tps') {
                            if (((int) $monatsanfang->format("m")) == 6 || ((int) $monatsanfang->format("m")) == 11) {
                                $stichtag = clone $monatsanfang;
                                $stichtag->setDate((int) $stichtag->format("Y"), (int) $stichtag->format("m"), 30);
                                $stichtag_minus_6_monate = clone $stichtag;
                                $stichtag_minus_6_monate->sub(new \DateInterval("P0000-06-00T00:00:00"));
                                if ($mitarbeiter->getEintritt() <= $stichtag_minus_6_monate) {
                                    $bleibt_weitere_3_monate = false;
                                    if ($mitarbeiter->getAustritt() instanceof \DateTime) {
                                        $monatsanfang_plus_4_monate = clone $monatsanfang;
                                        $monatsanfang_plus_4_monate->add(new \DateInterval("P0000-04-00T00:00:00"));
                                        if ($mitarbeiter->getAustritt() >= $monatsanfang_plus_4_monate) {
                                            $bleibt_weitere_3_monate = true;
                                        }
                                    } else {
                                        $bleibt_weitere_3_monate = true;
                                    }
                                    if ($bleibt_weitere_3_monate) {
                                        // Sonderzahlung durchführen
                                        $anteil_sonderzahlung = ($wochenstunden <= 35) ? ($wochenstunden / 35) : 1;
                                        $hoehe_sonderzahlung = 0;

                                        $stichtag_minus_3_jahre = clone $monatsanfang;
                                        $stichtag_minus_3_jahre->sub(new \DateInterval("P0003-00-00T00:00:00"));

                                        $stichtag_minus_5_jahre = clone $monatsanfang;
                                        $stichtag_minus_5_jahre->sub(new \DateInterval("P0005-00-00T00:00:00"));

                                        if ($mitarbeiter->getEintritt() <= $stichtag_minus_5_jahre) {
                                            $hoehe_sonderzahlung = 300 * $anteil_sonderzahlung;
                                        } elseif ($mitarbeiter->getEintritt() <= $stichtag_minus_3_jahre) {
                                            $hoehe_sonderzahlung = 200 * $anteil_sonderzahlung;
                                        } else {
                                            $hoehe_sonderzahlung = 150 * $anteil_sonderzahlung;
                                        }

                                        $lohnart = 0;
                                        $bezeichnung = 0;
                                        if (((int) $monatsanfang->format("m")) == 6) {
                                            $lohnart = $sonderzahlung_urlaub_lohnart;
                                            $bezeichnung = $sonderzahlung_urlaub_bezeichnung;
                                        } else {
                                            $lohnart = $sonderzahlung_weihnachten_lohnart;
                                            $bezeichnung = $sonderzahlung_weihnachten_bezeichnung;
                                        }

                                        $liste[$mitarbeiter->getPersonalnummer()][] = [
                                            'personalnummer' => $mitarbeiter->getPersonalnummer(),
                                            'vorname' => $mitarbeiter->getVorname(),
                                            'nachname' => $mitarbeiter->getNachname(),
                                            'lohnart' => $lohnart,
                                            'bezeichnung' => $bezeichnung,
                                            'anzahl' => '',
                                            'lohnsatz' => '',
                                            'betrag' => number_format($hoehe_sonderzahlung, 2, ',', '.'),
                                            'export' => $mitarbeiter->getPersonalnummer() . ';' . $lohnart . ';' . '' . ';' . number_format($hoehe_sonderzahlung, 2, ',', '') . ';'
                                        ];
                                    }
                                }
                            }
                        }

                        /*******************************
                         * 14. FEHLZEITENBUCHUNGEN VORSCHLAGEN
                         *******************************/
                        if ($this->company != 'tps') {
                            $neuer_azk_stand = 0;
                            $veraenderung = 0;

                            if ($monats_ist == $monats_soll) {
                                $neuer_azk_stand = $azk_prev_month;
                            } elseif ($monats_ist > $monats_soll) {
                                $neuer_azk_stand = $azk_prev_month + ($monats_ist - $monats_soll);
                                $veraenderung = $monats_ist - $monats_soll;
                            } elseif ($monats_ist < $monats_soll) {
                                $neuer_azk_stand = $azk_prev_month - ($monats_soll - $monats_ist);
                                $veraenderung = $monats_ist - $monats_soll;
                            }

                            $fehlzeitenliste[$mitarbeiter->getPersonalnummer()] = [
                                'alter_azk' => number_format($azk_prev_month, 2, ',', '.'),
                                'veraenderung' => number_format($veraenderung, 2, ',', '.'),
                                'neuer_azk' => number_format($neuer_azk_stand, 2, ',', '.'),
                                'mini_oder_sv' => $wochenstunden >= 12 ? 'SV-pflichtig' : 'Minijob'
                            ];

                            if ($neuer_azk_stand < 0) {
                                $freie_kalendertage = 0;
                                $benoetigte_fehlzeiten_tage = 0;
                                $benoetigte_fehlzeiten_stunden = 0;
                                if ($wochenstunden < 12) {
                                    // Minijobber
                                    $benoetigte_fehlzeiten_stunden = abs($neuer_azk_stand);
                                } else {
                                    // SV-pflichtiger
                                    if ($neuer_azk_stand < -10) {
                                        $benoetigte_fehlzeiten_stunden = abs($neuer_azk_stand) - 10;
                                    }
                                }
                                if ($benoetigte_fehlzeiten_stunden > $fehlzeitenstunden) {
                                    $benoetigte_fehlzeiten_stunden -= $fehlzeitenstunden;
                                } else {
                                    $benoetigte_fehlzeiten_stunden = 0;
                                }

                                if ($tagessoll > 0) {
                                    $benoetigte_fehlzeiten_tage = ceil($benoetigte_fehlzeiten_stunden / $tagessoll);
                                }
                                $buchen_fehlzeiten_tage = 0;
                                $temp_day = clone $monatsanfang;
                                while ($temp_day <= $monatsende) {
                                    if ($temp_day->format("N") != 7) {
                                        $temp_day_start = $temp_day;
                                        $temp_day_end = clone $temp_day;
                                        $temp_day_end->setTime(23, 59, 59);

                                        if (count(\ttact\Models\AuftragModel::findByStartEndMitarbeiter($this->db, $this->current_user, $temp_day_start, $temp_day_end, $mitarbeiter->getID())) == 0) {
                                            if (count(\ttact\Models\KalendereintragModel::findByStartEndMitarbeiterLohnrelevant($this->db, $temp_day_start, $temp_day_end, $mitarbeiter->getID())) == 0) {
                                                $freie_kalendertage++;
                                            }
                                        }
                                    }

                                    $temp_day->add(new \DateInterval("P0000-00-01T00:00:00"));
                                }

                                if ($benoetigte_fehlzeiten_tage <= $freie_kalendertage) {
                                    $buchen_fehlzeiten_tage = $benoetigte_fehlzeiten_tage;
                                } else {
                                    $buchen_fehlzeiten_tage = $freie_kalendertage;
                                }

                                $fehlzeitenliste[$mitarbeiter->getPersonalnummer()]['benoetigte_fehlzeiten_stunden'] = number_format($benoetigte_fehlzeiten_stunden, 2, ',', '.');
                                $fehlzeitenliste[$mitarbeiter->getPersonalnummer()]['vorhandene_fehlzeiten_stunden'] = number_format($fehlzeitenstunden, 2, ',', '.');
                                $fehlzeitenliste[$mitarbeiter->getPersonalnummer()]['tagessoll'] = number_format($tagessoll, 2, ',', '.');
                                $fehlzeitenliste[$mitarbeiter->getPersonalnummer()]['benoetigte_fehlzeiten_tage'] = number_format($benoetigte_fehlzeiten_tage, 2, ',', '.');
                                $fehlzeitenliste[$mitarbeiter->getPersonalnummer()]['freie_kalendertage'] = number_format($freie_kalendertage, 2, ',', '.');
                                $fehlzeitenliste[$mitarbeiter->getPersonalnummer()]['buchen_fehlzeiten_tage'] = number_format($buchen_fehlzeiten_tage, 2, ',', '.');
                                $fehlzeitenliste[$mitarbeiter->getPersonalnummer()]['buchen_fehlzeiten_tage_value'] = $buchen_fehlzeiten_tage;
                                $fehlzeitenliste[$mitarbeiter->getPersonalnummer()]['fehltage_speichern'] = $buchen_fehlzeiten_tage > 0 ? true : false;
                            }
                        }

                        /*******************************
                         * 15. EINSATZBEZOGENE ZULAGE
                         *******************************/
                        if ($this->company != 'tps') {
                            if ($einsatzbezogene_zulage_stunden > 0 && round($einsatzbezogene_zulage_stunden * $einsatzbezogene_zulage, 2) > 0) {
                                $liste[$mitarbeiter->getPersonalnummer()][] = [
                                    'personalnummer' => $mitarbeiter->getPersonalnummer(),
                                    'vorname' => $mitarbeiter->getVorname(),
                                    'nachname' => $mitarbeiter->getNachname(),
                                    'lohnart' => $einsatzbezogene_zulage_lohnart,
                                    'bezeichnung' => $einsatzbezogene_zulage_bezeichnung,
                                    'anzahl' => number_format($einsatzbezogene_zulage_stunden, 2, ',', '.'),
                                    'lohnsatz' => number_format($einsatzbezogene_zulage, 2, ',', '.'),
                                    'betrag' => number_format($einsatzbezogene_zulage_stunden * $einsatzbezogene_zulage, 2, ',', '.'),
                                    'export' => $mitarbeiter->getPersonalnummer() . ';' . $einsatzbezogene_zulage_lohnart . ';' . number_format($einsatzbezogene_zulage, 2, ',', '') . ';' . number_format($einsatzbezogene_zulage_stunden, 2, ',', '') . ';'
                                ];
                            }
                        }
                    }
                    // END Schleife: Alle Mitarbeiter

                    ksort($liste);
                    $this->smarty_vars['liste'] = $liste;
                    if ($this->company != 'tps') {
                        $this->smarty_vars['fehlzeitenliste'] = $fehlzeitenliste;
                    }

                    // Lohnartstatistiken
                    $lohnartstatistiken = [];
                    foreach ($liste as $arr2) {
                        foreach ($arr2 as $row) {
                            if (isset($lohnartstatistiken[$row['lohnart']])) {
                                $lohnartstatistiken[$row['lohnart']]['anzahl'] += (float) str_replace(',', '.', str_replace('.', '', $row['anzahl']));
                                $lohnartstatistiken[$row['lohnart']]['betrag'] += (float) str_replace(',', '.', str_replace('.', '', $row['betrag']));
                            } else {
                                $lohnartstatistiken[$row['lohnart']]['anzahl'] = (float) str_replace(',', '.', str_replace('.', '', $row['anzahl']));
                                $lohnartstatistiken[$row['lohnart']]['betrag'] = (float) str_replace(',', '.', str_replace('.', '', $row['betrag']));
                            }
                        }
                    }
                    ksort($lohnartstatistiken);
                    $this->smarty_vars['lohnartstatistiken'] = $lohnartstatistiken;
                } else {
                    $error = 'Die Lohnberechnung eines Monats kann nur bis zum 15. des nächsten Monats durchgeführt werden.';
                }
            }

            // display error message
            if ($error == "" && $success != "") {
                $this->smarty_vars['success'] = $success;
            }
            if ($error != "") {
                $this->smarty_vars['error'] = rtrim($error, '<br>');
            }

            // fill values into the form
            $date = new \DateTime();
            $date->setDate($jahr, $monat, 1);
            $date->setTime(0, 0, 0);

            $filename = 'Lohn32_29_' . $date->format("m") . "_" . $date->format("Y") . '.csv';
            if ($this->company == 'tps') {
                $filename = 'Lohn32_10_' . $date->format("m") . "_" . $date->format("Y") . '.csv';
            }

            $this->smarty_vars['values'] = [
                'jahr'  => $jahr,
                'monat' => $monat,
                'filename' => $filename
            ];

            // template settings
            $this->template = 'main';
        }
    }

    public function ajax()
    {
        if ($this->user_input->getPostParameter('von') != '' || $this->user_input->getPostParameter('bis') != '' || $this->user_input->getPostParameter('kunde') != '') {
            $von = \DateTime::createFromFormat('d.m.Y', $this->user_input->getPostParameter('von'));
            if ($von instanceof \DateTime) {
                if ($von->format('d.m.Y') == $this->user_input->getPostParameter('von')) {
                    $bis = \DateTime::createFromFormat('d.m.Y', $this->user_input->getPostParameter('bis'));
                    if ($bis instanceof \DateTime) {
                        if ($bis->format('d.m.Y') == $this->user_input->getPostParameter('bis')) {
                            $error = false;

                            $kunden = [];
                            if ($this->current_user->getUsergroup()->hasRight('berechnungen_stunden_bestimmte_kunden')) {
                                $alle_kundenbeschraenkungen = \ttact\Models\KundenbeschraenkungModel::findAllByUserID($this->db, $this->current_user->getID());
                                foreach ($alle_kundenbeschraenkungen as $kundenbeschraenkung) {
                                    if ($kundenbeschraenkung->getKunde() instanceof \ttact\Models\KundeModel) {
                                        $kunden[] = $kundenbeschraenkung->getKunde()->getID();
                                    }
                                }
                            }

                            $kunde = \ttact\Models\KundeModel::findByID($this->db, (int) $this->user_input->getOnlyNumbers($this->user_input->getPostParameter('kunde')));
                            if ($kunde instanceof \ttact\Models\KundeModel) {
                                if ($this->current_user->getUsergroup()->hasRight('berechnungen_stunden_alle_kunden')) {
                                    $kunden[] = $kunde->getID();
                                } elseif ($this->current_user->getUsergroup()->hasRight('berechnungen_stunden_bestimmte_kunden')) {
                                    if (in_array($kunde->getID(), $kunden)) {
                                        $kunden = [$kunde->getID()];
                                    } else {
                                        $error = true;
                                    }
                                } else {
                                    $error = true;
                                }
                            }

                            if (!$error) {
                                $abteilungsliste = [];
                                $mitarbeiterliste = [];

                                $schichten = [];

                                if (count($kunden) > 0) {
                                    $schichten = \ttact\Models\AuftragModel::findByStartEndKundenLohnberechnung($this->db, $this->current_user, $von, $bis, $kunden);
                                } elseif ($this->current_user->getUsergroup()->hasRight('berechnungen_stunden_alle_kunden')) {
                                    $schichten = \ttact\Models\AuftragModel::findByStartEndLohnberechnung($this->db, $this->current_user, $von, $bis);
                                } else {
                                    // error
                                }

                                $abteilungen = [];
                                $mitarbeiter = [];
                                foreach ($schichten as $schicht) {
                                    if ($schicht->getMitarbeiter() instanceof \ttact\Models\MitarbeiterModel) {
                                        $mitarbeiter[$schicht->getMitarbeiter()->getPersonalnummer()] = $schicht->getMitarbeiter();
                                    }
                                    if ($schicht->getAbteilung() instanceof \ttact\Models\AbteilungModel) {
                                        $abteilungen[$schicht->getAbteilung()->getID()] = $schicht->getAbteilung();
                                    }
                                }
                                ksort($mitarbeiter);
                                ksort($abteilungen);

                                foreach($abteilungen as $abteilung) {
                                    $abteilungsliste[] = [
                                        'id' => $abteilung->getID(),
                                        'bezeichnung' => $abteilung->getBezeichnung()
                                    ];
                                }
                                foreach($mitarbeiter as $mitarbeiter) {
                                    $mitarbeiterliste[] = [
                                        'id' => $mitarbeiter->getID(),
                                        'personalnummer' => $mitarbeiter->getPersonalnummer(),
                                        'vorname' => $mitarbeiter->getVorname(),
                                        'nachname' => $mitarbeiter->getNachname()
                                    ];
                                }

                                $this->smarty_vars['data']['abteilungen'] = $abteilungsliste;
                                $this->smarty_vars['data']['mitarbeiter'] = $mitarbeiterliste;

                                $this->smarty_vars['data']['status'] = 'success';
                            }
                        }
                    }
                }
            }
        }

        $this->template = 'ajax';
    }

    private function calculateHours(&$db, &$von, &$bis, &$mitarbeiter, &$stunden_feiertag, &$stunden_sonntag, &$stunden_nacht, &$stunden_normal, &$nacht_von, &$nacht_bis)
    {
        $feiertag = \ttact\Models\KalendereintragModel::findByTypeDateMitarbeiter($db, 'feiertag_bezahlt', $von, $mitarbeiter->getID());
        if ($feiertag instanceof \ttact\Models\KalendereintragModel) {
            // Feiertagsstunden ermitteln
            $stunden_feiertag += ($bis->getTimestamp() - $von->getTimestamp()) / 3600;
        } elseif ($von->format("N") == 7) {
            // Sonntagsstunden ermitteln
            $stunden_sonntag += ($bis->getTimestamp() - $von->getTimestamp()) / 3600;
        } else {
            // Nacht- und Tagstunden ermitteln
            $nacht1_von = new \DateTime($von->format("Y-m-d") . " " . $nacht_von . ":00");
            $nacht1_bis = new \DateTime($von->format("Y-m-d") . " " . $nacht_bis . ":00");

            $period_schicht = new Period($von, $bis);

            $nachtsekunden = 0;

            if ($nacht1_bis < $nacht1_von) {
                // z.B. 23:00 - 06:00
                $nacht2_von = clone $nacht1_von;
                $nacht1_von->sub(new \DateInterval("P0000-00-01T00:00:00"));
                $nacht2_bis = clone $nacht1_bis;
                $nacht2_bis->add(new \DateInterval("P0000-00-01T00:00:00"));

                $period_nacht1 = new Period($nacht1_von, $nacht1_bis);
                $period_nacht2 = new Period($nacht2_von, $nacht2_bis);

                if ($period_schicht->overlaps($period_nacht2)) {
                    $nachtsekunden += $period_schicht->intersect($period_nacht2)->getTimestampInterval();
                }
            } else {
                // z.B. 00:00 - 06:00
                $period_nacht1 = new Period($nacht1_von, $nacht1_bis);
            }

            if ($period_schicht->overlaps($period_nacht1)) {
                $nachtsekunden += $period_schicht->intersect($period_nacht1)->getTimestampInterval();
            }

            $stunden_normal += (($bis->getTimestamp() - $von->getTimestamp()) / 3600) - ($nachtsekunden / 3600);
            $stunden_nacht += ($nachtsekunden / 3600);
        }
    }

    private function calculateRefzeitraumHours(&$db, &$current_user, &$anfang, \ttact\Models\MitarbeiterModel &$mitarbeiter, &$error, &$stunden, &$wochenstunden, &$stunden_per_wochentag, bool $theoretisch)
    {
        $ende_inclusive = clone $anfang;
        $ende_inclusive->setDate((int) $ende_inclusive->format("Y"), (int) $ende_inclusive->format("m"), (int) $ende_inclusive->format("t"));
        $ende_inclusive->setTime(23, 59, 59);
        $ende_exclusive = clone $anfang;
        $ende_exclusive->add(new \DateInterval("P0000-01-00T00:00:00"));
        if ($theoretisch) {
            $stunden = $wochenstunden * 4.333;
        } else {
            $this->calculateRefzeitraumHoursInternal($db, $current_user, $anfang, $ende_inclusive, $ende_exclusive, $mitarbeiter, $error, $stunden, $wochenstunden, $stunden_per_wochentag);
        }
    }

    private function calculateRefzeitraumHoursInternal(&$db, &$current_user, &$anfang, &$ende_inclusive, &$ende_exclusive, \ttact\Models\MitarbeiterModel &$mitarbeiter, &$error, &$stunden, $wochenstunden, &$stunden_per_wochentag)
    {
        $wochenstunden_tagessoll = $wochenstunden / 6;

		// Schichten
		$schichten = \ttact\Models\AuftragModel::findByStartEndMitarbeiterLohnberechnung($db, $current_user, $anfang, $ende_inclusive, $mitarbeiter->getID());
		foreach ($schichten as $schicht) {
			$stunden += $schicht->getSeconds() / 3600;
            if ($this->company != 'tps') {
                $stunden_per_wochentag[$schicht->getVon()->format("N")] += $schicht->getSeconds() / 3600;
            }
		}

        // Lohnbuchungsstunden
        $lohnbuchungen = \ttact\Models\LohnbuchungModel::findByYearMonthMitarbeiter($this->db, $anfang->format("Y"), $anfang->format("m"), $mitarbeiter->getID());
        foreach ($lohnbuchungen as $lohnbuchung) {
            $lohnbuchungslohnart = (int) $lohnbuchung->getLohnart();
            if ($lohnbuchungslohnart == 22 || $lohnbuchungslohnart == 1015) {
                $wert = (float) $lohnbuchung->getWert();
                $stunden += $wert;
                if ($this->company != 'tps') {
                    $stunden_durch_sechs = $wert / 6;
                    for ($i = 1; $i <= 6; $i++) {
                        $stunden_per_wochentag[$i] += $stunden_durch_sechs;
                    }
                }
            }
        }

		// Tagessoll
		$tagessoll_model = \ttact\Models\TagessollModel::findByJahrMonatMitarbeiter($db, (int) $anfang->format("Y"), (int) $anfang->format("m"), $mitarbeiter->getID());
		if (!$tagessoll_model instanceof \ttact\Models\TagessollModel) {
			$error .= "<strong>Achtung:</strong> Für den Mitarbeiter ".$mitarbeiter->getPersonalnummer()." konnte das Tagessoll nicht korrekt berechnet werden, da das Tagessoll für " . $anfang->format("Y-m") . " fehlt. Die betroffenen Urlaubs-, Krankheits- und Feiertage wurden für die Berechnung ignoriert.<br>";
		}

		// Kalendereinträge
		$period_monat = new Period($anfang, $ende_exclusive);
		    // Krankheitstage
		    $bezahlte_kalendereintraege = \ttact\Models\KalendereintragModel::findByStartEndTypeMitarbeiter($db, $anfang, $ende_inclusive, $mitarbeiter->getID(), 'krank_bezahlt');
		    foreach ($bezahlte_kalendereintraege as $kalendereintrag) {
			    $kalendereintrag_bis = $kalendereintrag->getBis();
			    $kalendereintrag_bis->add(new \DateInterval("P0000-00-01T00:00:00"));
			    $kalendereintrag_bis->setTime(0, 0, 0);
			    $period_kalendereintrag = new Period($kalendereintrag->getVon(), $kalendereintrag_bis);

			    if ($period_monat->overlaps($period_kalendereintrag)) {
				    $period_kalendereintrag_intersection = $period_monat->intersect($period_kalendereintrag);
				    foreach ($period_kalendereintrag_intersection->getDatePeriod(new \DateInterval("P0000-00-01T00:00:00")) as $day) {
					    if ($day->format("N") != 7) {
						    if ($tagessoll_model instanceof \ttact\Models\TagessollModel) {
							    $stunden += $tagessoll_model->getTagessoll();
                                if ($this->company != 'tps') {
                                    $stunden_per_wochentag[$day->format("N")] += $wochenstunden_tagessoll > $tagessoll_model->getTagessoll() ? $wochenstunden_tagessoll : $tagessoll_model->getTagessoll();
                                }
						    }
					    }
				    }
			    }
		    }
		    // Urlaubstage
		    $bezahlte_kalendereintraege = \ttact\Models\KalendereintragModel::findByStartEndTypeMitarbeiter($db, $anfang, $ende_inclusive, $mitarbeiter->getID(), 'urlaub_bezahlt');
		    foreach ($bezahlte_kalendereintraege as $kalendereintrag) {
			    $kalendereintrag_bis = $kalendereintrag->getBis();
			    $kalendereintrag_bis->add(new \DateInterval("P0000-00-01T00:00:00"));
			    $kalendereintrag_bis->setTime(0, 0, 0);
			    $period_kalendereintrag = new Period($kalendereintrag->getVon(), $kalendereintrag_bis);

                if ($period_monat->overlaps($period_kalendereintrag)) {
				    $period_kalendereintrag_intersection = $period_monat->intersect($period_kalendereintrag);
				    foreach ($period_kalendereintrag_intersection->getDatePeriod(new \DateInterval("P0000-00-01T00:00:00")) as $day) {
					    if ($day->format("N") != 7) {
						    if ($tagessoll_model instanceof \ttact\Models\TagessollModel) {
							    $stunden += $tagessoll_model->getTagessoll();
                                if ($this->company != 'tps') {
                                    $stunden_per_wochentag[$day->format("N")] += $wochenstunden_tagessoll > $tagessoll_model->getTagessoll() ? $wochenstunden_tagessoll : $tagessoll_model->getTagessoll();
                                }
						    }
					    }
				    }
			    }
		    }
		    // Feiertage
            if ($this->company != 'tps') {
                $bezahlte_kalendereintraege = \ttact\Models\KalendereintragModel::findByStartEndTypeMitarbeiter($db, $anfang, $ende_inclusive, $mitarbeiter->getID(), 'feiertag_bezahlt');
                $wochentage = [
                    1 => 'Montag',
                    2 => 'Dienstag',
                    3 => 'Mittwoch',
                    4 => 'Donnerstag',
                    5 => 'Freitag',
                    6 => 'Samstag',
                    7 => 'Sonntag'
                ];
                foreach ($bezahlte_kalendereintraege as $kalendereintrag) {
                    $kalendereintrag_bis = $kalendereintrag->getBis();
                    $kalendereintrag_bis->add(new \DateInterval("P0000-00-01T00:00:00"));
                    $kalendereintrag_bis->setTime(0, 0, 0);
                    $period_kalendereintrag = new Period($kalendereintrag->getVon(), $kalendereintrag_bis);

                    if ($period_monat->overlaps($period_kalendereintrag)) {
                        $period_kalendereintrag_intersection = $period_monat->intersect($period_kalendereintrag);
                        foreach ($period_kalendereintrag_intersection->getDatePeriod(new \DateInterval("P0000-00-01T00:00:00")) as $day) {
                            if ($day->format("N") != 7) {
                                if ($tagessoll_model instanceof \ttact\Models\TagessollModel) {
                                    $prev_day = new \DateTime($day->format("Y-m-d H:i:s"));
                                    $prev_day->sub(new \DateInterval("P0000-00-01T00:00:00"));
                                    if ($prev_day->format("N") == 7) {
                                        $prev_day->sub(new \DateInterval("P0000-00-01T00:00:00"));
                                    }
                                    $next_day = new \DateTime($day->format("Y-m-d H:i:s"));
                                    $next_day->add(new \DateInterval("P0000-00-01T00:00:00"));
                                    if ($next_day->format("N") == 7) {
                                        $next_day->add(new \DateInterval("P0000-00-01T00:00:00"));
                                    }

                                    $feiertag_bezahlen = false;

                                    $prev_day_is_UrlaubKrankSchicht = false;
                                    if (count(\ttact\Models\AuftragModel::findByStartEndMitarbeiter($this->db, $this->current_user, new \DateTime($prev_day->format("Y-m-d") . " 00:00:00"), new \DateTime($prev_day->format("Y-m-d") . " 23:59:59"), $mitarbeiter->getID())) > 0) {
                                        $prev_day_is_UrlaubKrankSchicht = true;
                                    } elseif (count(\ttact\Models\KalendereintragModel::findAllByTypeDateMitarbeiter($this->db, 'urlaub_bezahlt', $prev_day, $mitarbeiter->getID())) > 0) {
                                        $prev_day_is_UrlaubKrankSchicht = true;
                                    } elseif (count(\ttact\Models\KalendereintragModel::findAllByTypeDateMitarbeiter($this->db, 'krank_bezahlt', $prev_day, $mitarbeiter->getID())) > 0) {
                                        $prev_day_is_UrlaubKrankSchicht = true;
                                    } elseif (count(\ttact\Models\StundenimporteintragModel::findByStartEndMitarbeiter($this->db, new \DateTime($prev_day->format("Y-m-d") . " 00:00:00"), new \DateTime($prev_day->format("Y-m-d") . " 23:59:59"), $mitarbeiter->getID())) > 0) {
                                        $prev_day_is_UrlaubKrankSchicht = true;
                                    } elseif (count(\ttact\Models\KalendereintragModel::findAllByTypeDateMitarbeiter($this->db, 'benachbarten_feiertag_bezahlen', $prev_day, $mitarbeiter->getID())) > 0) {
                                        $feiertag_bezahlen = true;
                                    }

                                    $next_day_is_UrlaubKrankSchicht = false;
                                    if (count(\ttact\Models\AuftragModel::findByStartEndMitarbeiter($this->db, $this->current_user, new \DateTime($next_day->format("Y-m-d") . " 00:00:00"), new \DateTime($next_day->format("Y-m-d") . " 23:59:59"), $mitarbeiter->getID())) > 0) {
                                        $next_day_is_UrlaubKrankSchicht = true;
                                    } elseif (count(\ttact\Models\KalendereintragModel::findAllByTypeDateMitarbeiter($this->db, 'urlaub_bezahlt', $next_day, $mitarbeiter->getID())) > 0) {
                                        $next_day_is_UrlaubKrankSchicht = true;
                                    } elseif (count(\ttact\Models\KalendereintragModel::findAllByTypeDateMitarbeiter($this->db, 'krank_bezahlt', $next_day, $mitarbeiter->getID())) > 0) {
                                        $next_day_is_UrlaubKrankSchicht = true;
                                    } elseif (count(\ttact\Models\StundenimporteintragModel::findByStartEndMitarbeiter($this->db, new \DateTime($next_day->format("Y-m-d") . " 00:00:00"), new \DateTime($next_day->format("Y-m-d") . " 23:59:59"), $mitarbeiter->getID())) > 0) {
                                        $next_day_is_UrlaubKrankSchicht = true;
                                    } elseif (count(\ttact\Models\KalendereintragModel::findAllByTypeDateMitarbeiter($this->db, 'benachbarten_feiertag_bezahlen', $next_day, $mitarbeiter->getID())) > 0) {
                                        $feiertag_bezahlen = true;
                                    }

                                    if ($prev_day_is_UrlaubKrankSchicht && $next_day_is_UrlaubKrankSchicht) {
                                        $feiertag_bezahlen = true;
                                    }

                                    if ($feiertag_bezahlen) {
                                        $method = 'getTagessoll' . $wochentage[$day->format("N")];
                                        $stunden += $tagessoll_model->$method();
                                        $stunden_per_wochentag[$day->format("N")] += $tagessoll_model->$method();
                                    }
                                }
                            }
                        }
                    }
                }
            }
    }
}
