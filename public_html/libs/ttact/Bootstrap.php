<?php

namespace ttact;

/**
 * Bootstrap short summary.
 *
 * Bootstrap description.
 *
 * @version 1.0
 * @author Mian
 */
class Bootstrap
{
    private $db;
    private $smarty;
    private $session_id;
    private $controller;
    private $action;
    private $current_session;
    private $current_user;
    private $params;
    private $user_input;
    private $misc_utils;
    private $password_utils;
    private $company;

    public function __construct(Database $db, \Smarty $smarty, string $session_id, UserInput $user_input, MiscUtils $misc_utils, PasswordUtils $password_utils, String $company)
    {
        $this->db = $db;
        $this->smarty = $smarty;
        $this->session_id = $session_id;
        $this->user_input = $user_input;
        $this->params = [];
        $this->misc_utils = $misc_utils;
        $this->password_utils = $password_utils;
        $this->company = $company;

        // default controller
        $this->controller = 'Login';

        // default action
        $this->action = 'index';

        $not_logged_in = true;

        $this->current_session = Models\SessionModel::findCurrentSession($this->db, $this->session_id);
        if ($this->current_session instanceof Models\SessionModel) {
            if ($this->current_session->isRecent()) {
                if ($this->current_session->updateLastUpdate()) {
                    $this->current_user = Models\UserModel::findCurrentUser($this->db, $this->current_session);
                    if ($this->current_user instanceof Models\UserModel) {
                        if ($this->current_user->getUsergroup() instanceof Models\UsergroupModel) {
                            if ($this->user_input->getDesiredControllerName() == '') {
                                $this->controller = 'Startseite';
                                $this->action = 'index';
                            } elseif ($this->user_input->getDesiredActionName() == '') {
                                $this->controller = $this->user_input->getDesiredControllerName();
                                $this->action = 'index';
                            } else {
                                $this->controller = $this->user_input->getDesiredControllerName();
                                $this->action = $this->user_input->getDesiredActionName();
                            }

                            $forbidden = false;

                            switch ($this->controller) {
                                case 'Abteilungen':
                                    if (!$this->current_user->getUsergroup()->hasRight('abteilungen')) {
                                        goto forbidden;
                                    }
                                    break;
                                case 'Auftraege':
                                    if (!$this->current_user->getUsergroup()->hasRight('auftraege_alle_kunden') && !$this->current_user->getUsergroup()->hasRight('auftraege_bestimmte_kunden')) {
                                        goto forbidden;
                                    }
                                    break;
                                case 'Benutzer':
                                    if ($this->action != 'abmelden') {
                                        if ($this->action == 'passwort') {
                                            if (!$this->current_user->getUsergroup()->hasRight('eigenes_passwort_aendern')) {
                                                goto forbidden;
                                            }
                                        } else {
                                            if (!$this->current_user->getUsergroup()->hasRight('benutzer_stufe1') && !$this->current_user->getUsergroup()->hasRight('benutzer_stufe2') && !$this->current_user->getUsergroup()->hasRight('benutzer_stufe3') && !$this->current_user->getUsergroup()->hasRight('benutzer_stufe4') && !$this->current_user->getUsergroup()->hasRight('benutzer_stufe5')) {
                                                goto forbidden;
                                            }
                                        }
                                    }
                                    break;
                                case 'Berechnungen':
                                    if ($this->action == 'lohn') {
                                        if (!$this->current_user->getUsergroup()->hasRight('berechnungen_lohn')) {
                                            goto forbidden;
                                        }
                                    } elseif ($this->action == 'uebersicht') {
                                        if (!$this->current_user->getUsergroup()->hasRight('berechnungen_lohn')) {
                                            goto forbidden;
                                        }
                                    } elseif ($this->action == 'stunden') {
                                        if (!$this->current_user->getUsergroup()->hasRight('berechnungen_stunden_bestimmte_kunden') && !$this->current_user->getUsergroup()->hasRight('berechnungen_stunden_alle_kunden')) {
                                            goto forbidden;
                                        }
                                    } elseif ($this->action == 'ajax') {
                                        if (!$this->current_user->getUsergroup()->hasRight('berechnungen_lohn') && !$this->current_user->getUsergroup()->hasRight('berechnungen_stunden_bestimmte_kunden') && !$this->current_user->getUsergroup()->hasRight('berechnungen_stunden_alle_kunden')) {
                                            goto forbidden;
                                        }
                                    } else {
                                        goto forbidden;
                                    }
                                    break;
                                case 'Dokument':
                                    if ($this->action == 'bearbeiten') {
                                        if (!$this->current_user->getUsergroup()->hasRight('dokumente_alle_kunden')) {
                                            goto forbidden;
                                        }
                                    } elseif ($this->action == 'anzeigen') {
                                        if (!$this->current_user->getUsergroup()->hasRight('dokumente_alle_kunden') && !$this->current_user->getUsergroup()->hasRight('dokumente_einsehen_bestimmte_kunden')) {
                                            goto forbidden;
                                        }
                                    } elseif ($this->action == 'loeschen') {
                                        if (!$this->current_user->getUsergroup()->hasRight('dokumente_alle_kunden')) {
                                            goto forbidden;
                                        }
                                    } else {
                                        goto forbidden;
                                    }
                                    break;
                                case 'Kunden':
                                    if ($this->action == 'mitarbeiterliste') {
                                        if (!$this->current_user->getUsergroup()->hasRight('mitarbeiterliste')) {
                                            goto forbidden;
                                        }
                                    } elseif ($this->action == 'dokumente') {
                                        if (!$this->current_user->getUsergroup()->hasRight('dokumente_alle_kunden') && !$this->current_user->getUsergroup()->hasRight('dokumente_einsehen_bestimmte_kunden')) {
                                            goto forbidden;
                                        }
                                    } elseif (!$this->current_user->getUsergroup()->hasRight('kundendaten')) {
                                        goto forbidden;
                                    }
                                    break;
                                case 'Kundenkondition':
                                    if (!$this->current_user->getUsergroup()->hasRight('kundendaten') || !$this->current_user->getUsergroup()->hasRight('preise')) {
                                        goto forbidden;
                                    }
                                    break;
                                case 'Lohnbuchung':
                                    if (!$this->current_user->getUsergroup()->hasRight('mitarbeiter')) {
                                        goto forbidden;
                                    }
                                    break;
                                case 'Lohnkonfiguration':
                                    if (!$this->current_user->getUsergroup()->hasRight('mitarbeiter')) {
                                        goto forbidden;
                                    }
                                    break;
                                case 'Tagessoll':
                                    if (!$this->current_user->getUsergroup()->hasRight('tagessoll')) {
                                        goto forbidden;
                                    }
                                    break;
                                case 'Mitarbeiter':
                                    if (!$this->current_user->getUsergroup()->hasRight('mitarbeiter')) {
                                        goto forbidden;
                                    } else {
                                        if ($this->action == 'notizen') {
                                            if (!$this->current_user->getUsergroup()->hasRight('notizen')) {
                                                goto forbidden;
                                            }
                                        }
                                    }
                                    break;
                                case 'Rechnungen':
                                    if (!$this->current_user->getUsergroup()->hasRight('rechnungen')) {
                                        goto forbidden;
                                    }
                                    break;
                                case 'Schichten':
                                    if (!$this->current_user->getUsergroup()->hasRight('schichtplaner_bestimmte_kunden') && !$this->current_user->getUsergroup()->hasRight('schichtplaner_alle_kunden')) {
                                        goto forbidden;
                                    }
                                    break;
                                case 'Tarife':
                                    if (!$this->current_user->getUsergroup()->hasRight('tarife')) {
                                        goto forbidden;
                                    }
                                    break;
                                case 'Tariflohnbetrag':
                                    if (!$this->current_user->getUsergroup()->hasRight('tarife')) {
                                        goto forbidden;
                                    }
                                    break;
                                case 'Startseite':
                                    break;
                                default:
                                    forbidden:
                                    if (!$this->current_user->getUsergroup()->hasRight('benutzer_stufe5')) {
                                        $forbidden = true;
                                    }
                            }

                            if ($forbidden) {
                                $this->controller = 'Fehler';
                                $this->action = 'index';
                            }

                            $this->params = $this->user_input->getURLParameters();
                            $not_logged_in = false;
                        } else {
                            // something went terribly wrong
                            $this->controller = 'Login';
                            $this->action = 'index';
                            $this->current_session->delete();
                        }
                    } else {
                        // something went terribly wrong
                        $this->controller = 'Login';
                        $this->action = 'index';
                        $this->current_session->delete();
                    }
                } else {
                    // session time could not be extended
                    $this->controller = 'Login';
                    $this->action = 'index';
                    $this->current_session->delete();
                }
            } else {
                $this->controller = 'Login';
                $this->action = 'index';
                if (!$this->current_session->delete()) {
                    // session expired but could not be deleted.
                } else {
                    // session expired and deleted
                }
            }
        } else {
            // login
            $this->controller = 'Login';
            $this->action = 'index';
        }

        if ($not_logged_in && $this->user_input->getDesiredActionName() == 'ajax') {
            $this->controller = 'Fehler';
            $this->action = 'ajax';
            $this->params = ['not_logged_in'];
        } elseif ($not_logged_in && $this->user_input->getDesiredControllerName() == 'Mitarbeiter' && $this->user_input->getDesiredActionName() == 'pdfsplitter') {
            $this->controller = 'Mitarbeiter';
            $this->action = 'pdfsplitter';
            $this->params = $this->user_input->getURLParameters();
        }
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getPageContent()
    {
        $controller = $this->createController();
        return $controller->getPageContent();
    }

    private function createController()
    {
        $desired_controller_class = 'ttact\\Controllers\\' . $this->controller . 'Controller';

        if (class_exists($desired_controller_class)) {
            // desired controller class does exist
            if (in_array('ttact\\Controllers\\Controller', class_parents($desired_controller_class))) {
                // controller class is a child of the base controller class
                if (method_exists($desired_controller_class, $this->action . strtoupper($this->company)) || method_exists($desired_controller_class, $this->action)) {
                    return new $desired_controller_class($this->db, $this->action, $this->params, $this->smarty, $this->current_user, $this->user_input, $this->misc_utils, $this->session_id, $this->current_session, $this->password_utils, $this->company);
                }
            }
        }
        if ($this->action == 'ajax') {
            return new Controllers\FehlerController($this->db, 'ajax' , $this->params, $this->smarty, $this->current_user, $this->user_input, $this->misc_utils, $this->session_id, $this->current_session, $this->password_utils, $this->company);
        }
        return new Controllers\FehlerController($this->db, 'index' , $this->params, $this->smarty, $this->current_user, $this->user_input, $this->misc_utils, $this->session_id, $this->current_session, $this->password_utils, $this->company);
    }
}
