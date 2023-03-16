<?php

namespace ttact\Controllers;

use ttact\Models\KundeModel;

class BenutzerController extends Controller
{
    public function index()
    {
        if (isset($this->params[0])) {
            $this->smarty_vars['error'] = "Der Benutzer konnte nicht gefunden werden.";
        }

        $erlaubte_stufen = [];
        if ($this->current_user->getUsergroup()->hasRight('benutzer_stufe0')) {
            $erlaubte_stufen[] = 6;
        }
        if ($this->current_user->getUsergroup()->hasRight('benutzer_stufe1')) {
            $erlaubte_stufen[] = 1;
        }
        if ($this->current_user->getUsergroup()->hasRight('benutzer_stufe2')) {
            $erlaubte_stufen[] = 2;
        }
        if ($this->current_user->getUsergroup()->hasRight('benutzer_stufe3')) {
            $erlaubte_stufen[] = 3;
        }
        if ($this->current_user->getUsergroup()->hasRight('benutzer_stufe4')) {
            $erlaubte_stufen[] = 4;
        }
        if ($this->current_user->getUsergroup()->hasRight('benutzer_stufe5')) {
            $erlaubte_stufen[] = 5;
        }

        $benutzerliste = [];
        $alle_benutzer = \ttact\Models\UserModel::findAll($this->db);
        foreach ($alle_benutzer as $benutzer) {
            if (in_array($benutzer->getUsergroup()->getID(), $erlaubte_stufen)) {
                $benutzerliste[] = [
                    'id' => $benutzer->getID(),
                    'benutzername' => $benutzer->getUsername(),
                    'name' => $benutzer->getName(),
                    'stufe' => $benutzer->getUsergroup()->getBezeichnung(),
                    'aktiv' => $benutzer->isEnabled() ? 'ja' : 'nein'
                ];
            }
        }
        $this->smarty_vars['benutzerliste'] = $benutzerliste;

        $this->template = 'main';
    }

    public function bearbeiten()
    {
        $redirect = true;

        if (isset($this->params[0])) {
            if ($this->user_input->isPositiveInteger($this->params[0])) {
                $benutzer = \ttact\Models\UserModel::findByID($this->db, $this->user_input->getOnlyNumbers($this->params[0]));
                if ($benutzer instanceof \ttact\Models\UserModel) {
                    $erlaubte_stufen = [];
                    if ($this->current_user->getUsergroup()->hasRight('benutzer_stufe0')) {
                        $erlaubte_stufen[] = 6;
                    }
                    if ($this->current_user->getUsergroup()->hasRight('benutzer_stufe1')) {
                        $erlaubte_stufen[] = 1;
                    }
                    if ($this->current_user->getUsergroup()->hasRight('benutzer_stufe2')) {
                        $erlaubte_stufen[] = 2;
                    }
                    if ($this->current_user->getUsergroup()->hasRight('benutzer_stufe3')) {
                        $erlaubte_stufen[] = 3;
                    }
                    if ($this->current_user->getUsergroup()->hasRight('benutzer_stufe4')) {
                        $erlaubte_stufen[] = 4;
                    }
                    if ($this->current_user->getUsergroup()->hasRight('benutzer_stufe5')) {
                        $erlaubte_stufen[] = 5;
                    }

                    if (in_array($benutzer->getUsergroup()->getID(), $erlaubte_stufen)) {
                        $redirect = false;

                        $error = "";
                        $success = "";

                        // array with all input data
                        $i = [
                            'benutzername'              => '',
                            'name'                      => '',
                            'passwort_neu'              => '',
                            'passwort_neu_bestaetigen'  => '',
                            'benutzergruppe'            => '',
                            'aktiv'                     => ''
                        ];
                        foreach ($i as $key => $value) {
                            $i[$key] = $this->user_input->getPostParameter($key);
                        }

                        $i['kundenbeschraenkungen'] = $this->user_input->getArrayPostParameter('kundenbeschraenkungen');

                        $i['benutzername'] = strtolower($i['benutzername']);
                        $i['benutzergruppe'] = (int) $i['benutzergruppe'];

                        // check and save
                        if ($i['benutzername'] != "" && $i['benutzername'] != $benutzer->getUsername()) {
                            // check if username is taken
                            $test = \ttact\Models\UserModel::findByUsername($this->db, $i['benutzername']);
                            if ($test instanceof \ttact\Models\UserModel) {
                                $error .= "Der Benutzername ist bereits vergeben. ";
                            } else {
                                if ($benutzer->setUsername($i['benutzername'])) {
                                    $success .= "Der Benutzername wurde erfolgreich geändert. ";
                                } else {
                                    $error .= "Beim Speichern des Benutzernamens ist ein technischer Fehler aufgetreten. ";
                                }
                            }
                        }

                        if ($i['name'] != "" && $i['name'] != $benutzer->getName()) {
                            if ($benutzer->setName($i['name'])) {
                                $success .= "Der Name wurde erfolgreich geändert. ";
                            } else {
                                $error .= "Beim Speichern des Namens ist ein technischer Fehler aufgetreten. ";
                            }
                        }

                        $old_active = $benutzer->isEnabled() ? '1' : '0';
                        if ($i['aktiv'] != "" && $i['aktiv'] != $old_active && ($i['aktiv'] == '1' || $i['aktiv'] == '0')) {
                            if ($benutzer->setEnabled($i['aktiv'])) {
                                $success .= "Der Status wurde erfolgreich geändert. ";
                            } else {
                                $error .= "Beim Speichern des Status ist ein technischer Fehler aufgetreten. ";
                            }
                        }

                        if ($i['passwort_neu'] != '' || $i['passwort_neu_bestaetigen'] != '') {
                            if ($i['passwort_neu'] != '' && $i['passwort_neu_bestaetigen'] != '') {
                                if ($i['passwort_neu'] == $i['passwort_neu_bestaetigen']) {
                                    if (strlen($i['passwort_neu']) >= 8) {
                                        if ($benutzer->setPassword($this->password_utils->hash($i['passwort_neu']))) {
                                            $success = "Das Passwort wurde erfolgreich geändert.";
                                        } else {
                                            $error = "Beim Speichern des Passworts ist ein Fehler aufgetreten.";
                                        }
                                    } else {
                                        $error = "Das neue Passwort muss mindestens 8 Zeichen lang sein.";
                                    }
                                } else {
                                    $error = "Das neue Passwort wurde nicht korrekt wiederholt.";
                                }
                            } else {
                                $error = "Bitte füllen Sie alle Felder aus.";
                            }
                        }

                        // USERGROUP
                        $usergroup_changed = false;
                        $old_usergroup = $benutzer->getUsergroup();
                        $new_usergroup = \ttact\Models\UsergroupModel::findByID($this->db, $i['benutzergruppe']);
                        $new_usergroup_valid = false;
                        if ($new_usergroup instanceof \ttact\Models\UsergroupModel) {
                            if (in_array($new_usergroup->getID(), $erlaubte_stufen)) {
                                $new_usergroup_valid = true;
                            }
                        }
                        if ($i['benutzergruppe'] != $old_usergroup->getID() && $i['benutzergruppe'] != '') {
                            $usergroup_changed = true;
                        }

                        // KUNDE
                        $kundenbeschraenkungen_changed = false;
                        $old_kundenbeschraenkungen = $benutzer->getKundenbeschraenkungen();
                        $old_kunden_valid = true;
                        $old_kunden_ids = [];
                        foreach ($old_kundenbeschraenkungen as $k) {
                            $old_kunden_ids[] = $k->getKunde()->getID();
                            if (!$k->getKunde() instanceof KundeModel) {
                                $old_kunden_valid = false;
                            }
                        }
                        if ($old_kunden_valid) {
                            if (count($old_kundenbeschraenkungen) == 0) {
                                $old_kunden_valid = false;
                            }
                        }
                        $new_kunden_valid = true;
                        $new_kunden = [];
                        foreach ($i['kundenbeschraenkungen'] as $k) {
                            $new_kunde = KundeModel::findByID($this->db, $k);
                            if ($new_kunde instanceof KundeModel) {
                                $new_kunden[] = $new_kunde;
                            } else {
                                $new_kunden_valid = false;
                            }
                        }
                        if ($new_kunden_valid) {
                            if (count($new_kunden) == 0) {
                                $new_kunden_valid = false;
                            }
                        }
                        sort($old_kunden_ids);
                        sort($i['kundenbeschraenkungen']);

                        if ($i['kundenbeschraenkungen'] != $old_kunden_ids && count($i['kundenbeschraenkungen']) > 0) {
                            $kundenbeschraenkungen_changed = true;
                        }

                        // actual USERGROUP and KUNDE validations + changes
                        if ($usergroup_changed && $kundenbeschraenkungen_changed) {
                            if ($new_usergroup_valid) {
                                if ($new_usergroup->getID() == 6 || $new_usergroup->getID() == 1) {
                                    if ($new_kunden_valid) {
                                        // save new_usergroup
                                        if ($benutzer->setUsergroupID($new_usergroup->getID())) {
                                            $success .= "Die Benutzergruppe wurde erfolgreich geändert. ";
                                        } else {
                                            $error .= "Beim Speichern der Benutzergruppe ist ein technischer Fehler aufgetreten. ";
                                        }
                                        // save new_kunde
                                        if ($benutzer->setKundenbeschraenkungen($new_kunden)) {
                                            $success .= "Die Kundenbeschränkung wurde erfolgreich geändert. ";
                                        } else {
                                            $error .= "Beim Speichern der Kundenbeschränkung ist ein technischer Fehler aufgetreten. ";
                                        }
                                    } else {
                                        // error: Benutzergruppen 0 und 1 brauchen Kundenbeschränkung
                                        $error .= "Benutzer der Benutzergruppen 0 und 1 müssen eine Kundenbeschränkung besitzen. ";
                                    }
                                } else {
                                    if ($new_kunden_valid) {
                                        // error: Kundenbeschränkung nur bei Benutzergruppen 0 und 1
                                        $error .= "Nur Benutzer der Benutzergruppen 0 und 1 können eine Kundenbeschränkung besitzen. ";
                                    } else {
                                        // save new_usergroup
                                        if ($benutzer->setUsergroupID($new_usergroup->getID())) {
                                            $success .= "Die Benutzergruppe wurde erfolgreich geändert. ";
                                        } else {
                                            $error .= "Beim Speichern der Benutzergruppe ist ein technischer Fehler aufgetreten. ";
                                        }
                                        // set kunde = null
                                        if ($benutzer->setKundenbeschraenkungen([])) {
                                            $success .= "Die Kundenbeschränkung wurde erfolgreich geändert. ";
                                        } else {
                                            $error .= "Beim Speichern der Kundenbeschränkung ist ein technischer Fehler aufgetreten. ";
                                        }
                                    }
                                }
                            } else {
                                // error: Die neue Benutzergruppe ist ungültig
                                $error .= "Der ausgewählte Kunde für die Kundenbeschränkung ist ungültig. ";
                            }
                        } elseif ($usergroup_changed && !$kundenbeschraenkungen_changed) {
                            if ($new_usergroup_valid) {
                                if ($new_usergroup->getID() == 6 || $new_usergroup->getID() == 1) {
                                    if ($old_kunden_valid) {
                                        // save new_usergroup
                                        if ($benutzer->setUsergroupID($new_usergroup->getID())) {
                                            $success .= "Die Benutzergruppe wurde erfolgreich geändert. ";
                                        } else {
                                            $error .= "Beim Speichern der Benutzergruppe ist ein technischer Fehler aufgetreten. ";
                                        }
                                    } else {
                                        // error: Benutzergruppen 0 und 1 brauchen Kundenbeschränkung
                                        $error .= "Benutzer der Benutzergruppen 0 und 1 müssen eine Kundenbeschränkung besitzen. ";
                                    }
                                } else {
                                    if ($new_kunden_valid) {
                                        // error: Kundenbeschränkung nur bei Benutzergruppen 0 und 1
                                        $error .= "Nur Benutzer der Benutzergruppen 0 und 1 können eine Kundenbeschränkung besitzen. ";
                                    } else {
                                        // save new_usergroup
                                        if ($benutzer->setUsergroupID($new_usergroup->getID())) {
                                            $success .= "Die Benutzergruppe wurde erfolgreich geändert. ";
                                        } else {
                                            $error .= "Beim Speichern der Benutzergruppe ist ein technischer Fehler aufgetreten. ";
                                        }
                                        // set kunde = null
                                        if ($benutzer->setKundenbeschraenkungen([])) {
                                            $success .= "Die Kundenbeschränkung wurde erfolgreich geändert. ";
                                        } else {
                                            $error .= "Beim Speichern der Kundenbeschränkung ist ein technischer Fehler aufgetreten. ";
                                        }
                                    }
                                }
                            } else {
                                // error: Die neue Benutzergruppe ist ungültig
                                $error .= "Die ausgewählte Benutzergruppe ist ungültig. ";
                            }
                        } elseif (!$usergroup_changed && $kundenbeschraenkungen_changed) {
                            if ($old_usergroup->getID() == 6 || $old_usergroup->getID() == 1) {
                                if ($new_kunden_valid) {
                                    // save kunde
                                    if ($benutzer->setKundenbeschraenkungen($new_kunden)) {
                                        $success .= "Die Kundenbeschränkung wurde erfolgreich geändert. ";
                                    } else {
                                        $error .= "Beim Speichern der Kundenbeschränkung ist ein technischer Fehler aufgetreten. ";
                                    }
                                } else {
                                    // error: Benutzergruppen 0 und 1 brauchen Kundenbeschränkung
                                    $error .= "Benutzer der Benutzergruppen 0 und 1 müssen eine Kundenbeschränkung besitzen. ";
                                }
                            } else {
                                if ($new_kunden_valid) {
                                    // error: Kundenbeschränkung nur bei Benutzergruppen 0 und 1
                                    $error .= "Nur Benutzer der Benutzergruppen 0 und 1 können eine Kundenbeschränkung besitzen. ";
                                } else {
                                    // set kunde = null
                                    if ($benutzer->setKundenbeschraenkungen([])) {
                                        $success .= "Die Kundenbeschränkung wurde erfolgreich geändert. ";
                                    } else {
                                        $error .= "Beim Speichern der Kundenbeschränkung ist ein technischer Fehler aufgetreten. ";
                                    }
                                }
                            }
                        } else { // !$usergroup_changed && !$kunde_changed
                            // do nothing
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
                        $kunden_ids = [];
                        foreach ($benutzer->getKundenbeschraenkungen() as $kundenbeschraenkung) {
                            $kunden_ids[] = $kundenbeschraenkung->getKunde()->getID();
                        }

                        $values = [
                            'id'                    => $benutzer->getID(),
                            'benutzername'          => $benutzer->getUsername(),
                            'name'                  => $benutzer->getName(),
                            'kundenbeschraenkungen' => $kunden_ids,
                            'benutzergruppe'        => $benutzer->getUsergroup()->getID(),
                            'aktiv'                 => $benutzer->isEnabled() ? 'ja' : 'nein'
                        ];
                        $this->smarty_vars['values'] = $values;

                        $benutzergruppen = [];
                        foreach ($erlaubte_stufen as $erlaubte_stufe) {
                            $usergroup_model = \ttact\Models\UsergroupModel::findByID($this->db, $erlaubte_stufe);
                            if ($usergroup_model instanceof \ttact\Models\UsergroupModel) {
                                $benutzergruppen[] = [
                                    'id' => $usergroup_model->getID(),
                                    'bezeichnung' => $usergroup_model->getBezeichnung()
                                ];
                            }
                        }
                        $this->smarty_vars['benutzergruppen'] = $benutzergruppen;

                        $kundenliste = [];
                        $alle_kunden = \ttact\Models\KundeModel::findAll($this->db);
                        foreach ($alle_kunden as $kunde) {
                            $kundenliste[] = [
                                'id' => $kunde->getID(),
                                'kundennummer' => $kunde->getKundennummer(),
                                'name' => $kunde->getName()
                            ];
                        }
                        $this->smarty_vars['kundenliste'] = $kundenliste;

                        // template settings
                        $this->template = 'main';
                    } else {
                        $this->template = '404';
                    }
                }
            }
        }

        if ($redirect) {
            $this->misc_utils->redirect('benutzer', 'index', 'fehler');
        }
    }

    public function erstellen()
    {
        $erlaubte_stufen = [];
        if ($this->current_user->getUsergroup()->hasRight('benutzer_stufe0')) {
            $erlaubte_stufen[] = 6;
        }
        if ($this->current_user->getUsergroup()->hasRight('benutzer_stufe1')) {
            $erlaubte_stufen[] = 1;
        }
        if ($this->current_user->getUsergroup()->hasRight('benutzer_stufe2')) {
            $erlaubte_stufen[] = 2;
        }
        if ($this->current_user->getUsergroup()->hasRight('benutzer_stufe3')) {
            $erlaubte_stufen[] = 3;
        }
        if ($this->current_user->getUsergroup()->hasRight('benutzer_stufe4')) {
            $erlaubte_stufen[] = 4;
        }
        if ($this->current_user->getUsergroup()->hasRight('benutzer_stufe5')) {
            $erlaubte_stufen[] = 5;
        }

        $error = "";
        $success = "";
        if (isset($this->params[0])) {
            if ($this->params[0] == "erfolgreich") {
                $success = "Der Benutzer wurde erfolgreich angelegt.";
            }
        }

        // array with all input data
        $i = [
            'benutzername'              => '',
            'name'                      => '',
            'passwort_neu'              => '',
            'passwort_neu_bestaetigen'  => '',
            'benutzergruppe'            => '',
            'kunde'                     => ''
        ];
        foreach ($i as $key => $value) {
            $i[$key] = $this->user_input->getPostParameter($key);
        }
        $i['benutzername'] = strtolower($i['benutzername']);
        $i['benutzergruppe'] = (int) $i['benutzergruppe'];
        $i['kunde'] = (int) $i['kunde'];

        $usergroup_model = \ttact\Models\UsergroupModel::findByID($this->db, $i['benutzergruppe']);

        // check and save
        if (($i['benutzername'] != "") && ($i['name'] != "") && ($i['passwort_neu'] != "") && ($i['passwort_neu_bestaetigen'] != "")) {
            $test = \ttact\Models\UserModel::findByUsername($this->db, $i['benutzername']);
            if ($test instanceof \ttact\Models\UserModel) {
                $error = "Der Benutzername ist bereits vergeben.";
            } elseif ($i['passwort_neu'] != $i['passwort_neu_bestaetigen']) {
                $error = "Das Passwort wurde nicht korrekt wiederholt.";
            } elseif (strlen($i['passwort_neu']) < 8) {
                $error = "Das Passwort muss mindestens 8 Zeichen lang sein.";
            } elseif (!in_array($i['benutzergruppe'], $erlaubte_stufen)) {
                $error = "Es ist ein technischer Fehler aufgetreten.";
            } elseif (!($usergroup_model instanceof \ttact\Models\UsergroupModel)) {
                $error = "Es ist ein technischer Fehler aufgetreten.";
            } else {
                $problem = true;
                $kunde_id = '';
                if ($i['benutzergruppe'] == 6 || $i['benutzergruppe'] == 1) {
                    $kunde_model = \ttact\Models\KundeModel::findByID($this->db, $i['kunde']);
                    if ($kunde_model instanceof \ttact\Models\KundeModel) {
                        $problem = false;
                        $kunde_id = $kunde_model->getID();
                    } else {
                        $error = "Bitte geben Sie eine Kundenbeschränkung ein.";
                    }
                } elseif ($i['kunde'] > 0) {
                    $error = "Kundeneinschränkungen darf es nur bei den Benutzergruppen 0 und 1 geben!";
                } else {
                    $problem = false;
                }
                if (!$problem) {
                    // save the data and check if it worked
                    $data = [
                        'username' => $i['benutzername'],
                        'password' => $this->password_utils->hash($i['passwort_neu']),
                        'name' => $i['name'],
                        'usergroup_id' => $i['benutzergruppe'],
                        'kunde_id' => $kunde_id
                    ];
                    $new = \ttact\Models\UserModel::createNew($this->db, $data);
                    if ($new instanceof \ttact\Models\UserModel) {
                        $this->misc_utils->redirect('benutzer', 'erstellen', 'erfolgreich');
                    } else {
                        $error = "Beim Anlegen des Benutzers ist ein Fehler aufgetreten.";
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
        if ($error != "") {
            $this->smarty_vars['error'] = $error;
            $this->smarty_vars['values'] = $i;
        } elseif ($success != "") {
            $this->smarty_vars['success'] = $success;
        }

        $benutzergruppen = [];
        foreach ($erlaubte_stufen as $erlaubte_stufe) {
            $usergroup_model = \ttact\Models\UsergroupModel::findByID($this->db, $erlaubte_stufe);
            if ($usergroup_model instanceof \ttact\Models\UsergroupModel) {
                $benutzergruppen[] = [
                    'id' => $usergroup_model->getID(),
                    'bezeichnung' => $usergroup_model->getBezeichnung()
                ];
            }
        }
        $this->smarty_vars['benutzergruppen'] = $benutzergruppen;

        $kundenliste = [];
        $alle_kunden = \ttact\Models\KundeModel::findAll($this->db);
        foreach ($alle_kunden as $kunde) {
            $kundenliste[] = [
                'id' => $kunde->getID(),
                'kundennummer' => $kunde->getKundennummer(),
                'name' => $kunde->getName()
            ];
        }
        $this->smarty_vars['kundenliste'] = $kundenliste;

        // template settings
        $this->template = 'main';
    }

    public function abmelden()
    {
        if (!$this->current_session->delete()) {
            // session expired but could not be deleted.
        } else {
            // session expired and deleted
        }
        $this->misc_utils->redirect();
    }

    public function passwort()
    {
        $error = "";
        $success = "";

        // array with all input data
        $i = [
            'passwort_neu'              => '',
            'passwort_neu_bestaetigen'  => ''
        ];
        foreach ($i as $key => $value) {
            $i[$key] = $this->user_input->getPostParameter($key);
        }

        if ($i['passwort_neu'] != '' || $i['passwort_neu_bestaetigen'] != '') {
            if ($i['passwort_neu'] != '' && $i['passwort_neu_bestaetigen'] != '') {
                if ($i['passwort_neu'] == $i['passwort_neu_bestaetigen']) {
                    if (strlen($i['passwort_neu']) >= 8) {
                        if ($this->current_user->setPassword($this->password_utils->hash($i['passwort_neu']))) {
                            $success = "Das Passwort wurde erfolgreich geändert.";
                        } else {
                            $error = "Beim Speichern des Passworts ist ein Fehler aufgetreten.";
                        }
                    } else {
                        $error = "Das neue Passwort muss mindestens 8 Zeichen lang sein.";
                    }
                } else {
                    $error = "Das neue Passwort wurde nicht korrekt wiederholt.";
                }
            } else {
                $error = "Bitte füllen Sie alle Felder aus.";
            }
        }

        // fill values into the form
        if ($error != "") {
            $this->smarty_vars['error'] = $error;
        }
        if ($success != "") {
            $this->smarty_vars['success'] = $success;
        }

        // template settings
        $this->template = 'main';
    }
}
