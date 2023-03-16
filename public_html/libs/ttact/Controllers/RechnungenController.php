<?php

namespace ttact\Controllers;

use League\Period\Period;
use Dompdf\Dompdf;

class RechnungenController extends Controller
{
    public function anzeigen()
    {
        $error = "";
        $success = "";

        // array with all input data
        $i = [
            'jahr' => '',
            'monat' => '',
			'rfilter' => '',
            'rechnung_data' => '',
            'rechnungsposten_data' => '',
            'action' => ''
        ];
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
        foreach ($i as $key => $value) {
            $i[$key] = $this->user_input->getPostParameter($key);
        }
        $i['jahr'] = (int) $this->user_input->getOnlyNumbers($i['jahr']);
        $i['monat'] = (int) $this->user_input->getOnlyNumbers($i['monat']);

        if ($i['action'] == 'exportieren') {
            $this->misc_utils->sendCSVHeader($this->user_input->getPostParameter('filename') . ".csv");
            $array = $this->user_input->getArrayPostParameter("export");
            echo 'Umsatz in Euro;Steuerschlüssel;Gegenkonto;Beleg1;Beleg2;Datum;Konto;Kost1;Kost2;Skonto in Euro;Buchungstext;Umsatzsteuer-ID;Zusatzart;Zusatzinformation' . PHP_EOL;
            foreach ($array as $row) {
                echo $row . PHP_EOL;
            }

            $this->template = 'blank';
        } else {
            // save Rechnung from /rechnungen/erstellen if rechnung_data and rechnungsposten_data are provided
            if ($i['rechnung_data'] != '' && $i['rechnungsposten_data'] != '') {
                $rechnung_data = unserialize(base64_decode($i['rechnung_data']));
                $rechnungsposten_data = unserialize(base64_decode($i['rechnungsposten_data']));

                if (is_array($rechnung_data) && is_array($rechnungsposten_data)) {
                    if (count($rechnung_data) > 0 && count($rechnungsposten_data) > 0) {
                        $rechnung_model = \ttact\Models\RechnungModel::createNew($this->db, $rechnung_data);
                        $rechnung_error = false;
                        if ($rechnung_model instanceof \ttact\Models\RechnungModel) {
                            $rechnungsposten_models = [];
                            foreach ($rechnungsposten_data as $data) {
                                $data['rechnung_id'] = $rechnung_model->getID();
                                $rechnungsposten_model = \ttact\Models\RechnungspostenModel::createNew($this->db, $data);
                                if ($rechnungsposten_model instanceof \ttact\Models\RechnungspostenModel) {
                                    $rechnungsposten_models[] = $rechnungsposten_model;
                                } else {
                                    $rechnung_model->delete();
                                    foreach ($rechnungsposten_models as $rechnungsposten_model) {
                                        $rechnungsposten_model->delete();
                                    }
                                    $error = "Die Rechnung konnte nicht gespeichert werden.";
                                    break;
                                }
                            }
                        } else {
                            $error = "Die Rechnung konnte nicht gespeichert werden.";
                        }

                        if (!$rechnung_error) {
                            $success = 'Die Rechnung wurde erfolgreich gespeichert.';
                            $i['jahr'] = (int)$rechnung_model->getZeitraumVon()->format('Y');
                            $i['monat'] = (int)$rechnung_model->getZeitraumVon()->format('m');
                        }
                    }
                }
            }


            // show error from /rechnungen/bearbeiten
            if (isset($this->params[0])) {
                $this->smarty_vars['error'] = "Die Rechnung konnte nicht gefunden werden. ";
            }

            // set jahr & monat to today if not given
            $heute = new \DateTime("now");
            $jahr = &$i['jahr'];
            $monat = &$i['monat'];
            if ($jahr == 0 || !($jahr > 1000 && $jahr <= 9999)) {
                $jahr = $heute->format("Y");
            }
            if ($monat == 0 || !($monat >= 1 && $monat <= 12)) {
                $monat = (int)$heute->format("m");
                if ($monat == 1) {
                    $jahr--;
                    $monat = 12;
                } else {
                    $monat--;
                }
            }

            // get Rechnungsliste
            $rechnungsliste = [];
            $total_netto = 0;
            $total_brutto = 0;

            $monat_datetime = new \DateTime();
            $monat_datetime->setDate($jahr, $monat, 1);

            #Rechnung status $rechnung_status: 0 ->unpaid , 1->paid, 2->cancel
            if (isset($i['rfilter'])) {
                if ($i['rfilter'] == 1) {
                    $rechnung_models = \ttact\Models\RechnungModel::findUnBezahltamByMonth($this->db, $monat_datetime);
                } else if ($i['rfilter'] == 2) {
                    $rechnung_models = \ttact\Models\RechnungModel::findBezahltamByMonth($this->db, $monat_datetime);
                } else if ($i['rfilter'] == 3) {
                    $rechnung_models = \ttact\Models\RechnungModel::findStornoByMonth($this->db, $monat_datetime);
                } else {
                    $rechnung_models = \ttact\Models\RechnungModel::findAllByMonth($this->db, $monat_datetime);
                }
            } else {
                $rechnung_models = \ttact\Models\RechnungModel::findAllByMonth($this->db, $monat_datetime);
            }

            foreach ($rechnung_models as $rechnung_model) {
                $rechnung_status = 0;
                $stornofaktor = 1;
                if (($rechnung_model->getBezahltAm() instanceof \DateTime)) {
                    $rechnung_status = 1;
                }
                if (($rechnung_model->getStornierungsdatum() instanceof \DateTime)) {
                    $rechnung_status = 2;
                    $stornofaktor = -1;
                }

                if (!($rechnung_model->getStornierungsdatum() instanceof \DateTime)) {
                    $total_netto += $rechnung_model->getNettobetrag();
                    $total_brutto += $rechnung_model->getBruttobetrag();
                }

                //$export = number_format($stornofaktor * ($rechnung_model->getBruttobetrag() + $rechnung_model->getKassendifferenz()), 2, ',', '') . ';;8400;' . $rechnung_model->getZeitraumVon()->format('y') . '-' . $rechnung_model->getRechnungsnummer() . ';;' . $rechnung_model->getRechnungsdatum()->format('d.m.Y') . ';10' . $rechnung_model->getKunde()->getKundennummer() . ';;;;' . $rechnung_model->getKunde()->getName() . ';;;';
                $export = number_format($stornofaktor * ($rechnung_model->getBruttobetrag() + $rechnung_model->getKassendifferenz()), 2, ',', '') . ';;4400;' . $rechnung_model->getZeitraumVon()->format('y') . '-' . $rechnung_model->getRechnungsnummer() . ';;' . $rechnung_model->getRechnungsdatum()->format('d.m.Y') . ';10' . $rechnung_model->getKunde()->getKundennummer() . ';;;;' . $rechnung_model->getKunde()->getName() . ';;;';
                if ($rechnung_model->getKassendifferenz() > 0) {
                    $export .= PHP_EOL;
                    $export .= number_format($stornofaktor * (-1) * $rechnung_model->getKassendifferenz(), 2, ',', '') . ';;6304;' . $rechnung_model->getZeitraumVon()->format('y') . '-' . $rechnung_model->getRechnungsnummer() . ';;' . $rechnung_model->getRechnungsdatum()->format('d.m.Y') . ';10' . $rechnung_model->getKunde()->getKundennummer() . ';;;;' . $rechnung_model->getKunde()->getName() . ';;;';
                    //$export .= number_format($stornofaktor * (-1) * $rechnung_model->getKassendifferenz(), 2, ',', '') . ';;4905;' . $rechnung_model->getZeitraumVon()->format('y') . '-' . $rechnung_model->getRechnungsnummer() . ';;' . $rechnung_model->getRechnungsdatum()->format('d.m.Y') . ';10' . $rechnung_model->getKunde()->getKundennummer() . ';;;;' . $rechnung_model->getKunde()->getName() . ';;;';
                }

                $rechnungsliste[] = [
                    'rechnung_id' => $rechnung_model->getID(),
                    'rechnungsnummer' => $rechnung_model->getRechnungsnummerWithYear(),
                    'datum' => (($rechnung_model->getRechnungsdatum() instanceof \DateTime) ? $rechnung_model->getRechnungsdatum()->format('d.m.Y') : ''),
                    'kunde' => (($rechnung_model->getKunde() instanceof \ttact\Models\KundeModel) ? $rechnung_model->getKunde()->getKundennummer() : ''),
                    'brutto' => number_format($rechnung_model->getBruttobetrag(), 2, ',', '.'),
                    'netto' => number_format($rechnung_model->getNettobetrag(), 2, ',', '.'),
                    'zahlungsziel' => (($rechnung_model->getZahlungsziel() instanceof \DateTime) ? $rechnung_model->getZahlungsziel()->format('d.m.Y') : ''),
                    'kommentar' => $rechnung_model->getKommentar(),
                    'stornierungsdatum' => (($rechnung_model->getStornierungsdatum() instanceof \DateTime) ? $rechnung_model->getStornierungsdatum()->format('d.m.Y') : ''),
                    'bezahltam' => (($rechnung_model->getBezahltAm() instanceof \DateTime) ? $rechnung_model->getBezahltAm()->format('d.m.Y') : ''),
                    'rechnung_status' => $rechnung_status,
                    'export' => $export
                ];
            }

            if (count($rechnungsliste) > 0) {
                $this->smarty_vars['rechnungsliste'] = $rechnungsliste;
                $this->smarty_vars['total_netto'] = number_format($total_netto, 2, ',', '.');
                $this->smarty_vars['total_brutto'] = number_format($total_brutto, 2, ',', '.');
                $this->smarty_vars['leistungszeitraum'] = $monate[(int)$monat_datetime->format('m')] . ' ' . $monat_datetime->format('Y');
            }

            // export file name
            $this->smarty_vars['filename'] = 'export_' . $this->company . '_' . $jahr . '_' . $monat;

            // display error message
            if ($error == "" && $success != "") {
                $this->smarty_vars['success'] = $success;
            }
            if ($error != "") {
                $this->smarty_vars['error'] = $error;
            }

            // fill values into the form
            $this->smarty_vars['values'] = $i;

            // template settings
            $this->template = 'main';
        }
    }
	
	public function ajax()
    {

        switch ($this->user_input->getPostParameter('type')) {
            case "kommentar":
				$this->smarty_vars['data'] = $this->setKommentar();				
				break;
			case "bezahltam":
				$this->smarty_vars['data'] = $this->setBezahltam();				
				break;
			case "stornierung":
				$this->smarty_vars['data'] = $this->setStornierung();				
				break;
			default:
                $this->smarty_vars['data']['status'] = 'error1';
                break;
        }

        // template settings
        $this->template = 'ajax';
    }
	
	private function setKommentar()
    {
		$data = [];
		$rechnung_id = (int) $this->user_input->getOnlyNumbers($this->user_input->getPostParameter('rechnung_id'));
		
		$kommentar = $this->user_input->getPostParameter('kommentar');
		$rechnung_model = \ttact\Models\RechnungModel::findByID($this->db, $rechnung_id);
		if ($rechnung_model instanceof \ttact\Models\RechnungModel) {
			$rechnung_model->setKommentar($kommentar);
			$data['status'] = 'success';
			$data['data']['message'] = 'Der Kommentar wurde gespeichert.';
		} else {
			$data['status'] = 'error';
            $data['data']['message'] = "Der Kommentar konnte nicht gespeichert werden.";
		}
		return $data;
	}

    private function setBezahltam()
    {
		$data = [];
		$rechnung_id = (int) $this->user_input->getOnlyNumbers($this->user_input->getPostParameter('rechnung_id'));
		$datum = $this->user_input->getPostParameter('datum');
		if (!$this->user_input->isDate($datum)) {
			$data['status'] = 'error';
            $data['data']['message'] = "Das Datum für 'bezahlt am' ist ungültig.";
			return $data;
		}
		
		$rechnung_model = \ttact\Models\RechnungModel::findByID($this->db, $rechnung_id);
		if ($rechnung_model instanceof \ttact\Models\RechnungModel) {
			$bezahlt_datum = \DateTime::createFromFormat("d.m.Y", $datum);
			$rechnung_model->setBezahltAm($bezahlt_datum->format("Y-m-d"));
			$data['status'] = 'success';
			$data['data']['message'] = "Das Datum für 'bezahlt am' wurde gespeichert.";
			$data['data']['content'] = '<a role="button" href="#" class="btn btn-success btn-block btn-sm" data-toggle="popover" title="Bestätigen" data-trigger="click"   id="popover" data-placement="top">bezahlt am '.$bezahlt_datum->format("d.m.Y").'</a>';
			
		} else {
			$data['status'] = 'error';
            $data['data']['message'] = "Das Datum für 'bezahlt am' konnte nicht gespeichert werden.";
		}
		return $data;
	}

    private function setStornierung()
    {
		$data = [];
		$rechnung_id = (int) $this->user_input->getOnlyNumbers($this->user_input->getPostParameter('rechnung_id'));
		
		$rechnung_model = \ttact\Models\RechnungModel::findByID($this->db, $rechnung_id);
		if ($rechnung_model instanceof \ttact\Models\RechnungModel) {
			$datum = new \DateTime("now");
			$rechnung_model->setStornierungsdatum($datum->format("Y-m-d"));
			$data['status'] = 'success';
			$data['data']['content'] = '<a role="button" href="#" class="btn btn-warning btn-block btn-sm">Stornierung am '.$datum->format("d.m.Y").'</a>';
			$data['data']['message'] = 'Die Rechnung wurde erfolgreich storniert.';
		} else {
			$data['status'] = 'error';
            $data['data']['message'] = 'Die Rechnung konnte nicht storniert werden.';
		}
		return $data;
	}

    public function erstellen()
    {
        $error = "";
        $success = "";

        // array with all input data
        $i = [
            'von'                           => '',
            'bis'                           => '',
            'kunde'                         => '',
            'rechnungsdatum'                => '',
            'leistungszeitraum'             => '',
            'zahlungsziel'                  => '',
            'kassendifferenz'               => ''
        ];
        foreach ($i as $key => $value) {
            $i[$key] = $this->user_input->getPostParameter($key);
        }
        $i['kassendifferenz'] = (float) str_replace(',', '.', $i['kassendifferenz']);
        $i['kunde'] = (int) $this->user_input->getOnlyNumbers($i['kunde']);

        $rechnung_data = [];
        $rechnungsposten_data = [];
        $rechnung_ready_to_save = false;

        // check and save
        if ($i['von'] != '' || $i['bis'] != '' || $i['kunde'] != '' || $i['rechnungsdatum'] != '' || $i['zahlungsziel'] != '') {
            if ($this->user_input->isDate($i['von'])) {
                $rechnung_von = \DateTime::createFromFormat('d.m.Y', $i['von']);
                if ($rechnung_von instanceof \DateTime) {
                    if ($this->user_input->isDate($i['bis'])) {
                        $rechnung_bis = \DateTime::createFromFormat('d.m.Y', $i['bis']);
                        if ($rechnung_bis instanceof \DateTime) {
                            if ($i['kunde'] != 0) {
                                $kunde = \ttact\Models\KundeModel::findByID($this->db, $i['kunde']);
                                if ($kunde instanceof \ttact\Models\KundeModel) {
                                    if ($this->user_input->isDate($i['rechnungsdatum'])) {
                                        $rechnungsdatum = \DateTime::createFromFormat('d.m.Y', $i['rechnungsdatum']);
                                        if ($rechnungsdatum instanceof \DateTime) {
                                            if ($this->user_input->isDate($i['zahlungsziel'])) {
                                                $zahlungsziel = \DateTime::createFromFormat('d.m.Y', $i['zahlungsziel']);
                                                if ($zahlungsziel instanceof \DateTime) {
                                                    $schichten = \ttact\Models\AuftragModel::findByStartEndKundeRechnungen($this->db, $this->current_user, $rechnung_von, $rechnung_bis, $kunde->getID());
                                                    $lohndaten = [];
                                                    $alle_abgeschlossen = true;
                                                    foreach ($schichten as $schicht) {
                                                        $continue = true;

                                                        if ($this->company == 'tps') {
                                                            if ($schicht->getAbteilung()->getPalettenabteilung()) {
                                                                $continue = false;
                                                            }
                                                        }

                                                        if ($continue) {
                                                            if ($schicht->getStatus() != "archiviert") {
                                                                $alle_abgeschlossen = false;
                                                            } else {
                                                                $abteilung_id = $schicht->getAbteilung()->getID();
                                                                $abteilung_bezeichnung = $schicht->getAbteilung()->getBezeichnung();

                                                                $von = $schicht->getVon();
                                                                $bis = $schicht->getBis();

                                                                $stunden_feiertag = 0;
                                                                $stunden_sonntag = 0;
                                                                $stunden_nacht = 0;
                                                                $stunden_normal = 0;

                                                                if (!isset($lohndaten[$abteilung_id])) {
                                                                    $preis_feiertag = 0;
                                                                    $preis_sonntag = 0;
                                                                    $preis_nacht = 0;
                                                                    $preis_normal = 0;

                                                                    $kundenkondition = \ttact\Models\KundenkonditionModel::findForKundeAbteilungDate($this->db, $kunde->getID(), $abteilung_id, $von);
                                                                    if ($kundenkondition instanceof \ttact\Models\KundenkonditionModel) {
                                                                        $preis_normal = round((float)$kundenkondition->getPreis(), 2);
                                                                        $preis_sonntag = round($preis_normal * (($kundenkondition->getSonntagszuschlag() + 100) / 100), 2);
                                                                        $preis_feiertag = round($preis_normal * (($kundenkondition->getFeiertagszuschlag() + 100) / 100), 2);
                                                                        $preis_nacht = round($preis_normal * (($kundenkondition->getNachtzuschlag() + 100) / 100), 2);
                                                                    }

                                                                    $lohndaten[$abteilung_id] = [
                                                                        'kundenkondition' => $kundenkondition,
                                                                        'normal' => [
                                                                            'leistungsart' => $abteilung_bezeichnung,
                                                                            'menge' => 0,
                                                                            'einzelpreis' => $preis_normal
                                                                        ],
                                                                        'sonntag' => [
                                                                            'leistungsart' => $abteilung_bezeichnung . ' Sonntag',
                                                                            'menge' => 0,
                                                                            'einzelpreis' => $preis_sonntag
                                                                        ],
                                                                        'feiertag' => [
                                                                            'leistungsart' => $abteilung_bezeichnung . ' Feiertag',
                                                                            'menge' => 0,
                                                                            'einzelpreis' => $preis_feiertag
                                                                        ],
                                                                        'nacht' => [
                                                                            'leistungsart' => $abteilung_bezeichnung . ' Nacht',
                                                                            'menge' => 0,
                                                                            'einzelpreis' => $preis_nacht
                                                                        ]
                                                                    ];
                                                                }

                                                                if ($von->format("Y-m-d") == $bis->format("Y-m-d")) {
                                                                    // von.tag = bis.tag
                                                                    $this->calculateHours($von, $bis, $this->db, $schicht, $stunden_feiertag, $stunden_sonntag, $stunden_nacht, $stunden_normal, $lohndaten, $abteilung_id);
                                                                } else {
                                                                    // von.tag != bis.tag
                                                                    $von1 = $von;
                                                                    $bis1 = clone $bis;
                                                                    $bis1->setTime(0, 0, 0);
                                                                    $von2 = clone $bis1;
                                                                    $bis2 = $bis;

                                                                    $this->calculateHours($von1, $bis1, $this->db, $schicht, $stunden_feiertag, $stunden_sonntag, $stunden_nacht, $stunden_normal, $lohndaten, $abteilung_id);
                                                                    $this->calculateHours($von2, $bis2, $this->db, $schicht, $stunden_feiertag, $stunden_sonntag, $stunden_nacht, $stunden_normal, $lohndaten, $abteilung_id);
                                                                }

                                                                // Pause
                                                                $pause_std = round($schicht->getPauseSeconds() / 3600, 2);
                                                                $stunden = [&$stunden_feiertag, &$stunden_sonntag, &$stunden_nacht, &$stunden_normal];
                                                                rsort($stunden);
                                                                $stunden[0] -= $pause_std;

                                                                $lohndaten[$abteilung_id]['feiertag']['menge'] += $stunden_feiertag;
                                                                $lohndaten[$abteilung_id]['sonntag']['menge'] += $stunden_sonntag;
                                                                $lohndaten[$abteilung_id]['nacht']['menge'] += $stunden_nacht;
                                                                $lohndaten[$abteilung_id]['normal']['menge'] += $stunden_normal;
                                                            }
                                                        }
                                                    }

                                                    if ($this->company == 'tps') {
                                                        $paletten = \ttact\Models\PaletteModel::findByStartEndKunde($this->db, $rechnung_von, $rechnung_bis, $kunde->getID());
                                                        foreach ($paletten as $palette) {
                                                            if ($palette->getAbteilung()->getPalettenabteilung()) {
                                                                $abteilung_id = $palette->getAbteilung()->getID();
                                                                $abteilung_bezeichnung = $palette->getAbteilung()->getBezeichnung();

                                                                if (!isset($lohndaten[$abteilung_id])) {
                                                                    $preis_normal = 0;

                                                                    $kundenkondition = \ttact\Models\KundenkonditionModel::findForKundeAbteilungDate($this->db, $kunde->getID(), $abteilung_id, $palette->getDatum());
                                                                    if ($kundenkondition instanceof \ttact\Models\KundenkonditionModel) {
                                                                        $preis_normal = round((float) $kundenkondition->getPreis(), 2);
                                                                    }

                                                                    $lohndaten[$abteilung_id] = [
                                                                        'kundenkondition' => $kundenkondition,
                                                                        'normal' => [
                                                                            'leistungsart' => $abteilung_bezeichnung,
                                                                            'menge' => 0,
                                                                            'einzelpreis' => $preis_normal
                                                                        ],
                                                                        'sonntag' => [
                                                                            'leistungsart' => $abteilung_bezeichnung . ' Sonntag',
                                                                            'menge' => 0,
                                                                            'einzelpreis' => 0
                                                                        ],
                                                                        'feiertag' => [
                                                                            'leistungsart' => $abteilung_bezeichnung . ' Feiertag',
                                                                            'menge' => 0,
                                                                            'einzelpreis' => 0
                                                                        ],
                                                                        'nacht' => [
                                                                            'leistungsart' => $abteilung_bezeichnung . ' Nacht',
                                                                            'menge' => 0,
                                                                            'einzelpreis' => 0
                                                                        ]
                                                                    ];
                                                                }

                                                                $lohndaten[$abteilung_id]['normal']['menge'] += $palette->getAnzahl();
                                                            }
                                                        }
                                                    }

                                                    if ($alle_abgeschlossen) {
                                                        // Rechnungsposten
                                                        $nettobetrag = 0;
                                                        foreach ($lohndaten as $l) {
                                                            if ($l['normal']['menge'] > 0) {
                                                                $rechnungsposten_data[] = [
                                                                    'leistungsart' => $l['normal']['leistungsart'],
                                                                    'menge' => round($l['normal']['menge'], 2),
                                                                    'einzelpreis' => round($l['normal']['einzelpreis'], 2),
                                                                    'gesamtpreis' => round($l['normal']['menge'] * $l['normal']['einzelpreis'], 2)
                                                                ];
                                                                $nettobetrag += round($l['normal']['menge'] * $l['normal']['einzelpreis'], 2);
                                                            }
                                                            if ($l['feiertag']['menge'] > 0) {
                                                                $rechnungsposten_data[] = [
                                                                    'leistungsart' => $l['feiertag']['leistungsart'],
                                                                    'menge' => round($l['feiertag']['menge'], 2),
                                                                    'einzelpreis' => round($l['feiertag']['einzelpreis'], 2),
                                                                    'gesamtpreis' => round($l['feiertag']['menge'] * $l['feiertag']['einzelpreis'], 2)
                                                                ];
                                                                $nettobetrag += round($l['feiertag']['menge'] * $l['feiertag']['einzelpreis'], 2);
                                                            }
                                                            if ($l['sonntag']['menge'] > 0) {
                                                                $rechnungsposten_data[] = [
                                                                    'leistungsart' => $l['sonntag']['leistungsart'],
                                                                    'menge' => round($l['sonntag']['menge'], 2),
                                                                    'einzelpreis' => round($l['sonntag']['einzelpreis'], 2),
                                                                    'gesamtpreis' => round($l['sonntag']['menge'] * $l['sonntag']['einzelpreis'], 2)
                                                                ];
                                                                $nettobetrag += round($l['sonntag']['menge'] * $l['sonntag']['einzelpreis'], 2);
                                                            }
                                                            if ($l['nacht']['menge'] > 0) {
                                                                $rechnungsposten_data[] = [
                                                                    'leistungsart' => $l['nacht']['leistungsart'],
                                                                    'menge' => round($l['nacht']['menge'], 2),
                                                                    'einzelpreis' => round($l['nacht']['einzelpreis'], 2),
                                                                    'gesamtpreis' => round($l['nacht']['menge'] * $l['nacht']['einzelpreis'], 2)
                                                                ];
                                                                $nettobetrag += round($l['nacht']['menge'] * $l['nacht']['einzelpreis'], 2);
                                                            }
                                                        }
                                                        $this->smarty_vars['rechnungsliste'] = $rechnungsposten_data;

                                                        // Rechnungssumme
                                                        $gesamtbetrag = [];
                                                        $gesamtbetrag[] = [
                                                            'name' => 'Nettobetrag',
                                                            'betrag' => number_format($nettobetrag, 2, ',', '.') . ' €'
                                                        ];

                                                        $mehrwertsteuer = $nettobetrag * 0.19;
                                                        $gesamtbetrag[] = [
                                                            'name' => 'zzgl. 19% USt.',
                                                            'betrag' => number_format($mehrwertsteuer, 2, ',', '.') . ' €'
                                                        ];

                                                        if ($i['kassendifferenz'] > 0) {
                                                            $bruttobetrag = $nettobetrag * 1.19 - round($i['kassendifferenz'], 2);

                                                            $gesamtbetrag[] = [
                                                                'name' => 'Zwischensumme Brutto',
                                                                'betrag' => number_format($nettobetrag * 1.19, 2, ',', '.') . ' €'
                                                            ];
                                                            $gesamtbetrag[] = [
                                                                'name' => 'Ausgleich Kassendifferenz',
                                                                'betrag' => '- ' . number_format($i['kassendifferenz'], 2, ',', '.') . ' €'
                                                            ];
                                                            $gesamtbetrag[] = [
                                                                'name' => 'Gesamtbetrag Brutto',
                                                                'betrag' => number_format($bruttobetrag, 2, ',', '.') . ' €'
                                                            ];
                                                        } else {
                                                            $bruttobetrag = $nettobetrag * 1.19;

                                                            $gesamtbetrag[] = [
                                                                'name' => 'Gesamtbetrag Brutto',
                                                                'betrag' => number_format($bruttobetrag, 2, ',', '.') . ' €'
                                                            ];
                                                        }
                                                        $this->smarty_vars['gesamtbetrag'] = $gesamtbetrag;

                                                        // PDF Daten erstellen
                                                        $rechnungsnummer = 1;
                                                        $letzte_rechnung = \ttact\Models\RechnungModel::findLastByYear($this->db, $rechnung_von);
                                                        if ($letzte_rechnung instanceof \ttact\Models\RechnungModel) {
                                                            $rechnungsnummer = $letzte_rechnung->getRechnungsnummer() + 1;
                                                        }

                                                        $rechnung_data = [
                                                            'kunde_id' => $kunde->getID(),
                                                            'stornierungsdatum' => '0000-00-00',
                                                            'bezahlt_am' => '0000-00-00',
                                                            'nettobetrag' => round($nettobetrag, 2),
                                                            'mehrwertsteuer' => round($mehrwertsteuer, 2),
                                                            'bruttobetrag' => round($bruttobetrag, 2),
                                                            'rechnungsdatum' => $rechnungsdatum->format('Y-m-d'),
                                                            'rechnungsnummer' => $rechnungsnummer,
                                                            'zeitraum_von' => $rechnung_von->format('Y-m-d'),
                                                            'zeitraum_bis' => $rechnung_bis->format('Y-m-d'),
                                                            'zahlungsziel' => $zahlungsziel->format('Y-m-d'),
                                                            'kassendifferenz' => round($i['kassendifferenz'], 2),
                                                            'alternative_anrede' => '',
                                                            'kommentar' => '',
                                                            'alte_rechnung_id' => ''
                                                        ];

                                                        $rechnung_ready_to_save = true;
                                                    } else {
                                                        $error = "Im ausgewählten Zeitraum existieren nicht abgeschlossene Schichten.";
                                                    }
                                                } else {
                                                    $error = "Das Zahlungsziel ist ungültig.";
                                                }
                                            } else {
                                                $error = "Das Zahlungsziel ist ungültig.";
                                            }
                                        } else {
                                            $error = "Das Rechnungsdatum ist ungültig.";
                                        }
                                    } else {
                                        $error = "Das Rechnungsdatum ist ungültig.";
                                    }
                                } else {
                                    $error = "Der Kunde ist ungültig.";
                                }
                            } else {
                                $error = "Der Kunde ist ungültig.";
                            }
                        } else {
                            $error = "Das Bis-Datum ist ungültig.";
                        }
                    } else {
                        $error = "Das Bis-Datum ist ungültig.";
                    }
                } else {
                    $error = "Das Von-Datum ist ungültig.";
                }
            } else {
                $error = "Das Von-Datum ist ungültig.";
            }
        }

        // display error message
        if ($error == "" && $success != "") {
            $success = "Die Änderungen wurden erfolgreich vorgenommen.";
        }
        if ($error != "") {
            $this->smarty_vars['error'] = $error;
        }

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
        $this->smarty_vars['kundenliste'] = $kundenliste;

        // fill values into the form
        $rechnungsdatum = new \DateTime("now");
        $zahlungsziel = new \DateTime("now");
        $zahlungsziel->add(new \DateInterval("P0000-00-10T00:00:00"));
        $von = new \DateTime("now");
        $von->sub(new \DateInterval("P0000-01-00T00:00:00"));
        $von->setDate((int) $von->format("Y"), (int) $von->format("m"), 1);
        $bis = clone $von;
        $bis->setDate((int) $bis->format("Y"), (int) $bis->format("m"), (int) $bis->format("t"));
        $values = [
            'von'                       => $i['von'] != "" ? $i['von'] : $von->format("d.m.Y"),
            'bis'                       => $i['bis'] != "" ? $i['bis'] : $bis->format("d.m.Y"),
            'kunde'                     => $i['kunde'] == 0 ? '' : $i['kunde'],
            'rechnungsdatum'            => $i['rechnungsdatum'] == '' ? $rechnungsdatum->format("d.m.Y") : $i['rechnungsdatum'],
            'leistungszeitraum'         => $i['leistungszeitraum'],
            'zahlungsziel'              => $i['zahlungsziel'] == '' ? $zahlungsziel->format("d.m.Y") : $i['zahlungsziel'],
            'kassendifferenz'           => $i['kassendifferenz'] == 0 ? '' : $i['kassendifferenz']
        ];
        $this->smarty_vars['values'] = $values;

        $this->smarty_vars['rechnung_data'] = base64_encode(serialize($rechnung_data));
        $this->smarty_vars['rechnungsposten_data'] = base64_encode(serialize($rechnungsposten_data));
        $this->smarty_vars['rechnung_ready_to_save'] = $rechnung_ready_to_save;

        // template settings
        $this->template = 'main';
    }
	
	public function pdf()
    {
        $rechnung_model = \null;
        $rechnungsposten_models = [];

        if (isset($this->params[0])) {
            if ($this->user_input->isPositiveInteger($this->params[0])) {
                $rechnung_id = (int) $this->params[0];
                $rechnung_model = \ttact\Models\RechnungModel::findByID($this->db, $rechnung_id);
                $rechnungsposten_models = \ttact\Models\RechnungspostenModel::findAllByRechnung($this->db, $rechnung_id);
            }
        } else {
            $rechnung_data = $this->user_input->getPostParameter('rechnung_data');
            $rechnungsposten_data = $this->user_input->getPostParameter('rechnungsposten_data');
            if ($rechnung_data != '' && $rechnungsposten_data != '') {
                $rechnung_data = unserialize(base64_decode($rechnung_data));
                $rechnungsposten_data = unserialize(base64_decode($rechnungsposten_data));

                if (is_array($rechnung_data) && is_array($rechnungsposten_data)) {
                    if (count($rechnung_data) > 0 && count($rechnungsposten_data) > 0) {
                        $rechnung_model = \ttact\Models\RechnungModel::createDummyObjectWithoutSaving($this->db, $rechnung_data);
                        if ($rechnung_model instanceof \ttact\Models\RechnungModel) {
                            $rechnungsposten_models = [];
                            foreach ($rechnungsposten_data as $data) {
                                $data['rechnung_id'] = $rechnung_model->getID();
                                $rechnungsposten_model = \ttact\Models\RechnungspostenModel::createDummyObjectWithoutSaving($this->db, $data);
                                if ($rechnungsposten_model instanceof \ttact\Models\RechnungspostenModel) {
                                    $rechnungsposten_models[] = $rechnungsposten_model;
                                } else {
                                    $rechnung_model = \null;
                                    $rechnungsposten_models = [];
                                    break;
                                }
                            }
                        }
                    }
                }
            }
        }

        if ($rechnung_model instanceof \ttact\Models\RechnungModel) {
            $dompdf = new Dompdf();

            // {{$smarty_vars.rechnunglogo}}
            $this->smarty_vars['rechnunglogo'] = '';
			if ($this->company == 'aps') {
                $this->smarty_vars['rechnunglogo'] = '<img src="assets/img/ANDROM1.png" width="275mm" height="55mm">';
                if ($rechnung_model->getAlteRechnungID() != '') {
			        if ($rechnung_model->getAlteRechnungID() <= 1881) {
                        $this->smarty_vars['rechnunglogo'] = '<img src="assets/img/androm-logo.jpg" width="190mm" height="60mm">';
                    } elseif ($rechnung_model->getAlteRechnungID() <= 2714) {
                        $this->smarty_vars['rechnunglogo'] = '<img src="assets/img/androm_logo_briefkopf_ende_neu.png" width="275mm" height="65mm">';
                    }
                }
			} elseif ($this->company == 'tps') {
                $this->smarty_vars['rechnunglogo'] = '<img src="assets/img/ttact_service1.png" width="185mm" height="53mm">';
                if ($rechnung_model->getAlteRechnungID() != '') {
                    if ($rechnung_model->getAlteRechnungID() <= 707) {
                        $this->smarty_vars['rechnunglogo'] = '<img src="assets/img/ttactlogo.png" width="295mm" height="38mm">';
                    } elseif ($rechnung_model->getAlteRechnungID() <= 1137) {
                        $this->smarty_vars['rechnunglogo'] = '<img src="assets/img/ttact_logo_briefkopf_ende_neu.png" width="190mm" height="65mm">';
                    }
                }
            } elseif ($this->company == 'cps') {
                $this->smarty_vars['rechnunglogo'] = '<img src="assets/img/ttact_gmbh_cps.png" width="185mm" height="53mm">';
            }

            // {{$smarty_vars.absenderadresse}}
            $this->smarty_vars['absenderadresse'] = '';
            if ($this->company == 'aps') {
                $this->smarty_vars['absenderadresse'] = 'ANDROM Personalservice GmbH - Breitenbachstraße 10, 13509 - Berlin';
            }elseif ($this->company == 'tps') {
                $this->smarty_vars['absenderadresse'] = 'tt act Service GmbH - Breitenbachstraße 10,13509 - Berlin';
            }elseif ($this->company == 'cps') {
                $this->smarty_vars['absenderadresse'] = 'tt act GmbH - Breitenbachstraße 10,13509 - Berlin';
            }

            // {{$smarty_vars.rechnungsanschrift}}
			$kunde = ($rechnung_model->getKunde() instanceof \ttact\Models\KundeModel) ? $rechnung_model->getKunde() : '';
			$rechnungsanschrift = ($kunde->getRechnungsanschrift() != '') ? $kunde->getRechnungsanschrift() : $kunde->getName().'<br>'. $kunde->getStrasse().'<br>'.$kunde->getPostleitzahl().' '.$kunde->getOrt();
            $this->smarty_vars['rechnungsanschrift'] = str_replace(PHP_EOL, '<br>', $rechnungsanschrift);
            if ($kunde->getRechnungsanschrift() != '') {
                $this->smarty_vars['rechnungsanschrift'] = str_replace(PHP_EOL, '<br>', $kunde->getRechnungsanschrift());
            } else {
                $this->smarty_vars['rechnungsanschrift'] =
                    $kunde->getName() . '<br>' .
                    $kunde->getStrasse() . '<br>' .
                    $kunde->getPostleitzahl() . ' ' . $kunde->getOrt();
            }

            // {{$smarty_vars.rechnungsdatum}}
            $this->smarty_vars['rechnungsdatum'] = $rechnung_model->getRechnungsdatum()->format('d.m.Y');

            // {{$smarty_vars.kostenstelle}}
            $this->smarty_vars['kostenstelle'] = $kunde->getRechnungszusatz();

            // {{$smarty_vars.rechnungsnummer}}
            $this->smarty_vars['rechnungsnummer'] = $rechnung_model->getRechnungsnummerWithYear();

            // {{$smarty_vars.leistungszeitraum}}
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
            $this->smarty_vars['leistungszeitraum'] = $monate[(int) $rechnung_model->getZeitraumVon()->format('m')] . ' ' . $rechnung_model->getZeitraumVon()->format('Y');

            // {{$smarty_vars.kundennummer}}
            $this->smarty_vars['kundennummer'] = $kunde->getKundennummer();

            // {{$smarty_vars.rechnungtitle}}
            $this->smarty_vars['rechnungtitle'] = ($rechnung_model->getStornierungsdatum() instanceof \DateTime) ? 'Stornorechnung' : 'Rechnung';

            // {{$smarty_vars.anrede}}
            if ($rechnung_model->getAlternativeAnrede() != '') {
                $this->smarty_vars['anrede'] = $rechnung_model->getAlternativeAnrede();
            } else {
                $this->smarty_vars['anrede'] = 'Sehr geehrte Damen und Herren,';
            }

            // {{$smarty_vars.zeitraum_von}}
            $this->smarty_vars['zeitraum_von'] = $rechnung_model->getZeitraumVon()->format('d.m.Y');

            // {{$smarty_vars.zeitraum_bis}}
            $this->smarty_vars['zeitraum_bis'] = $rechnung_model->getZeitraumBis()->format('d.m.Y');

            // {{$smarty_vars.zahlungsziel}}
            $this->smarty_vars['zahlungsziel'] = $rechnung_model->getZahlungsziel()->format('d.m.Y');

            // {{$smarty_vars.inhaber}}
            $this->smarty_vars['inhaber'] = '';
            $this->smarty_vars['inhabertitel'] = '';
            if ($this->company == 'aps') {
                $owner_change_date = \DateTime::createFromFormat('Y-m-d', '2018-10-01');
                if ($rechnung_model->getZeitraumVon() < $owner_change_date) {
                    $this->smarty_vars['inhaber'] = 'Kerstin Lehmberg';
                    $this->smarty_vars['inhabertitel'] = 'Geschäftsführerin';
                } else {
                    $this->smarty_vars['inhaber'] = 'Hakan Kinaci';
                    $this->smarty_vars['inhabertitel'] = 'Geschäftsführer';
                }
            } elseif ($this->company == 'tps') {
                $this->smarty_vars['inhaber'] = 'Hakan Kinaci';
                $this->smarty_vars['inhabertitel'] = 'Geschäftsführer';
            } elseif ($this->company == 'cps') {
                $this->smarty_vars['inhaber'] = 'Canan Kinaci';
                $this->smarty_vars['inhabertitel'] = 'Geschäftsführerin';
            }

            // {{$smarty_vars.umsatzsteuerid}}
            $this->smarty_vars['umsatzsteuerid'] = '';
            if ($this->company == 'aps') {
                $this->smarty_vars['umsatzsteuerid'] = '27 / 208 / 31014';
            } elseif ($this->company == 'tps') {
                $this->smarty_vars['umsatzsteuerid'] = '27 / 564 / 31320';
            } elseif ($this->company == 'cps') {
                $this->smarty_vars['umsatzsteuerid'] = '27 / 564 / 32734';
            }

            // {{$smarty_vars.handelsregisternummer}}
            $this->smarty_vars['handelsregisternummer'] = '';
            if ($this->company == 'aps') {
                $this->smarty_vars['handelsregisternummer'] = 'HRB 143506 B';
            } elseif ($this->company == 'tps') {
                $this->smarty_vars['handelsregisternummer'] = 'HRB 146034 B';
            } elseif ($this->company == 'cps') {
                $this->smarty_vars['handelsregisternummer'] = 'HRB 145151 B';
            }

            // {{$smarty_vars.kontonummer}}
            $this->smarty_vars['kontonummer'] = '';
            if ($this->company == 'aps') {
                $this->smarty_vars['kontonummer'] = '2420828007';
            } elseif ($this->company == 'tps') {
                $this->smarty_vars['kontonummer'] = '2417967004';
            } elseif ($this->company == 'cps') {
                $this->smarty_vars['kontonummer'] = '2538782010';
            }

            // {{$smarty_vars.iban}}
            $this->smarty_vars['iban'] = '';
            if ($this->company == 'aps') {
                $this->smarty_vars['iban'] = 'DE41 1009 0000 2420 8280 07';
            } elseif ($this->company == 'tps') {
                $this->smarty_vars['iban'] = 'DE08 1009 0000 2417 9670 04';
            } elseif ($this->company == 'cps') {
                $this->smarty_vars['iban'] = 'DE86 1009 0000 2538 7820 10';
            }

            // {{$smarty_vars.rechnungsliste}}
            $this->smarty_vars['rechnungsliste'] = [];
            $postennummer = 1;
            foreach ($rechnungsposten_models as $rechnungsposten_model) {
                if ($rechnungsposten_model instanceof \ttact\Models\RechnungspostenModel) {
                    $einzelpreis = $rechnungsposten_model->getEinzelpreis();
                    $gesamtpreis = $rechnungsposten_model->getGesamtpreis();
                    if ($rechnung_model->getStornierungsdatum() instanceof \DateTime) {
                        $einzelpreis = $einzelpreis * (-1);
                        $gesamtpreis = $gesamtpreis * (-1);
                    }

                    $this->smarty_vars['rechnungsliste'][] = [
                        'postennummer' => $postennummer,
                        'leistungsart' => $rechnungsposten_model->getLeistungsart(),
                        'menge' => number_format($rechnungsposten_model->getMenge(), 2, ',', '.'),
                        'einzelpreis' => number_format($einzelpreis, 2, ',', '.'),
                        'gesamtpreis' => number_format($gesamtpreis, 2, ',', '.'),
                    ];
                    $postennummer++;
                }
            }

            // {{$smarty_vars.gesamtbetrag}}
            $nettobetrag = $rechnung_model->getNettobetrag();
            $mehrwertsteuer = $rechnung_model->getMehrwertsteuer();
            $bruttobetrag = $rechnung_model->getBruttobetrag();
            if ($rechnung_model->getStornierungsdatum() instanceof \DateTime) {
                $nettobetrag = $nettobetrag * (-1);
                $mehrwertsteuer = $mehrwertsteuer * (-1);
                $bruttobetrag = $bruttobetrag * (-1);
            }

            $this->smarty_vars['gesamtbetrag'] = [];
            $this->smarty_vars['gesamtbetrag'][] = [
                'bezeichnung' => 'Nettobetrag',
                'betrag' => number_format($nettobetrag, 2, ',', '.')
            ];
            $this->smarty_vars['gesamtbetrag'][] = [
                'bezeichnung' => 'zzgl. 19% USt.',
                'betrag' => number_format($mehrwertsteuer, 2, ',', '.')
            ];
            if ($rechnung_model->getKassendifferenz() > 0) {
                $zwischensumme_brutto = $rechnung_model->getNettobetrag() + $rechnung_model->getMehrwertsteuer();
                $kassendifferenz = $rechnung_model->getKassendifferenz() * (-1);
                if ($rechnung_model->getStornierungsdatum() instanceof \DateTime) {
                    $zwischensumme_brutto = $zwischensumme_brutto * (-1);
                    $kassendifferenz = $kassendifferenz * (-1);
                }

                $display_kassendifferenz = number_format($kassendifferenz, 2, ',', '.');
                if ($rechnung_model->getStornierungsdatum() instanceof \DateTime) {
                    $display_kassendifferenz = '+' . $display_kassendifferenz;
                }

                $this->smarty_vars['gesamtbetrag'][] = [
                    'bezeichnung' => 'Zwischensumme Brutto',
                    'betrag' => number_format($zwischensumme_brutto, 2, ',', '.')
                ];
                $this->smarty_vars['gesamtbetrag'][] = [
                    'bezeichnung' => 'Ausgleich Kassendifferenz',
                    'betrag' => $display_kassendifferenz
                ];
            }
            $this->smarty_vars['gesamtbetrag'][] = [
                'bezeichnung' => 'Zu zahlender Gesamtbetrag Brutto',
                'betrag' => number_format($bruttobetrag, 2, ',', '.')
            ];

            // PDF settings
            $this->smarty->assign('smarty_vars', $this->smarty_vars, true);

            if (file_exists($this->smarty->getTemplateDir()[0] . 'main/Rechnungen/pdf.' . $this->company . '.tpl')) {
                $dompdf->loadHtml($this->smarty->fetch('main/Rechnungen/pdf.' . $this->company . '.tpl'));
            } elseif (file_exists($this->smarty->getTemplateDir()[0] . 'main/Rechnungen/pdf.tpl')) {
                $dompdf->loadHtml($this->smarty->fetch('main/Rechnungen/pdf.tpl'));
            }

            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $dompdf->stream('Rechnung ' . $rechnung_model->getRechnungsnummerWithYear() . '.pdf', array('Attachment' => 0));

            $this->template = 'blank';
        } else {
            $this->misc_utils->redirect('rechnungen', 'anzeigen', 'fehler');
        }
	}

    public function bearbeiten()
    {
        $redirect = true;

        // array with all input data
        $i = [
            'delete_and_update' => '',
            'update' => '',
            'create_and_update' => '',
            'leistungsart' => '',
            'menge' => '',
            'einzelpreis' => '',
            'rechnungsposten_id' => '',
            'rechnung_data' => '',
            'rechnungsposten_data' => ''
        ];
        foreach ($i as $key => $value) {
            $i[$key] = $this->user_input->getPostParameter($key);
        }
        $i['rechnungsposten_id'] = (int) $this->user_input->getOnlyNumbers($i['rechnungsposten_id']);
        $i['menge'] = (float) str_replace(',', '.', $i['menge']);
        $i['einzelpreis'] = (float) str_replace(',', '.', $i['einzelpreis']);

        if ($i['rechnung_data'] != '' && $i['rechnungsposten_data'] != '') {
            $rechnung_data = unserialize(base64_decode($i['rechnung_data']));
            $rechnungsposten_data = unserialize(base64_decode($i['rechnungsposten_data']));

            if (is_array($rechnung_data) && is_array($rechnungsposten_data)) {
                if (count($rechnung_data) > 0 && count($rechnungsposten_data) > 0) {
                    $redirect = false;

                    if ($i['delete_and_update'] == 'true') {
                        if (isset($rechnungsposten_data[$i['rechnungsposten_id']])) {
                            unset($rechnungsposten_data[$i['rechnungsposten_id']]);
                            array_values($rechnungsposten_data);
                        } else {
                            // error
                        }
                    } elseif ($i['update'] == 'true') {
                        if (isset($rechnungsposten_data[$i['rechnungsposten_id']])) {
                            $rechnungsposten_data[$i['rechnungsposten_id']]['leistungsart'] = $i['leistungsart'];
                            $rechnungsposten_data[$i['rechnungsposten_id']]['menge'] = $i['menge'];
                            $rechnungsposten_data[$i['rechnungsposten_id']]['einzelpreis'] = $i['einzelpreis'];
                            $rechnungsposten_data[$i['rechnungsposten_id']]['gesamtpreis'] = round($i['menge'] * $i['einzelpreis'], 2);
                            array_values($rechnungsposten_data);
                        } else {
                            // error
                        }
                    } elseif ($i['create_and_update'] == 'true') {
                        $rechnungsposten_data[] = [
                            'leistungsart' => $i['leistungsart'],
                            'menge' => $i['menge'],
                            'einzelpreis' => $i['einzelpreis'],
                            'gesamtpreis' => round($i['menge'] * $i['einzelpreis'], 2)
                        ];
                        array_values($rechnungsposten_data);
                    }

                    $nettobetrag = 0;
                    foreach ($rechnungsposten_data as $rechnungsposten) {
                        $nettobetrag += round($rechnungsposten['menge'] * $rechnungsposten['einzelpreis'], 2);
                    }

                    $mehrwertsteuer = $nettobetrag * 0.19;
                    if ($rechnung_data['kassendifferenz'] > 0) {
                        $bruttobetrag = $nettobetrag * 1.19 - round($rechnung_data['kassendifferenz'], 2);
                    } else {
                        $bruttobetrag = $nettobetrag * 1.19;
                    }

                    $rechnung_data['nettobetrag'] = round($nettobetrag, 2);
                    $rechnung_data['mehrwertsteuer'] = round($mehrwertsteuer, 2);
                    $rechnung_data['bruttobetrag'] = round($bruttobetrag, 2);

                    $zeitraum_von_datetime = new \DateTime($rechnung_data['zeitraum_von'] . ' 00:00:00');
                    $this->smarty_vars['values'] = [
                        'rechnungsnummer' => $zeitraum_von_datetime->format('Y') . ' - ' . $rechnung_data['rechnungsnummer'],
                        'rechnung_data' => base64_encode(serialize($rechnung_data)),
                        'rechnungsposten_data' => base64_encode(serialize($rechnungsposten_data))
                    ];

                    $rechnungsposten = [];
                    foreach ($rechnungsposten_data as $rechnungsposten_id => $posten) {
                        $rechnungsposten[] = [
                            'leistungsart' => $posten['leistungsart'],
                            'menge' => $posten['menge'],
                            'einzelpreis' => $posten['einzelpreis'],
                            'rechnungsposten_id' => $rechnungsposten_id
                        ];
                    }
                    $this->smarty_vars['rechnungsposten'] = $rechnungsposten;
                } else {
                    // error
                }
            } else {
                // error
            }
        } else {
            // error
        }

        if ($redirect) {
            $this->misc_utils->redirect('rechnungen', 'anzeigen', 'fehler');
        } else {
            $this->template = 'main';
        }
    }

    private function calculateHours(&$von, &$bis, &$db, &$schicht, &$stunden_feiertag, &$stunden_sonntag, &$stunden_nacht, &$stunden_normal, &$lohndaten, &$abteilung_id) {
        $feiertag = \ttact\Models\KalendereintragModel::findByTypeDateMitarbeiter($db, 'feiertag_bezahlt', $von, $schicht->getMitarbeiter()->getID());
        if ($feiertag instanceof \ttact\Models\KalendereintragModel) {
            // Feiertagsstunden ermitteln
            $stunden_feiertag += round(($bis->getTimestamp() - $von->getTimestamp()) / 3600, 2);
        } elseif ($von->format("N") == 7) {
            // Sonntagsstunden ermitteln
            $stunden_sonntag += round(($bis->getTimestamp() - $von->getTimestamp()) / 3600, 2);
        } else {
            // Nacht- und Tagstunden ermitteln
            $has_nachtstunden = false;

            if ($lohndaten[$abteilung_id]['kundenkondition'] instanceof \ttact\Models\KundenkonditionModel) {
                if ($lohndaten[$abteilung_id]['kundenkondition']->getNachtVon() instanceof \DateTime && $lohndaten[$abteilung_id]['kundenkondition']->getNachtBis() instanceof \DateTime) {
                    $nacht1_von = new \DateTime($von->format("Y-m-d") . " " . $lohndaten[$abteilung_id]['kundenkondition']->getNachtVon()->format("H:i") . ":00");
                    $nacht1_bis = new \DateTime($von->format("Y-m-d") . " " . $lohndaten[$abteilung_id]['kundenkondition']->getNachtBis()->format("H:i") . ":00");

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

                    $stunden_normal += round(($bis->getTimestamp() - $von->getTimestamp()) / 3600, 2) - round($nachtsekunden / 3600, 2);
                    $stunden_nacht += round($nachtsekunden / 3600, 2);
                    $has_nachtstunden = true;
                }
            }

            if (!$has_nachtstunden) {
                $stunden_normal += round(($bis->getTimestamp() - $von->getTimestamp()) / 3600, 2);
            }
        }
    }
}
