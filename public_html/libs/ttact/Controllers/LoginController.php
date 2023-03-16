<?php

namespace ttact\Controllers;

class LoginController extends Controller
{
    public function index()
    {
        $error = true;

        $uname = $this->user_input->getPostParameter('username');
        $pword = $this->user_input->getPostParameter('password');

        if ($uname != '') {
            if ($pword != '') {
                $this->current_user = \ttact\Models\UserModel::findByCredentials($this->db, $this->password_utils, $uname, $pword);
                if ($this->current_user instanceof \ttact\Models\UserModel) {
                    // create session
                    $now = new \DateTime("now");
                    $data_for_new_session = [
                        'session' => $this->session_id,
                        'user_id' => $this->current_user->getID(),
                        'create_time' => $now->format("Y-m-d H:i:s"),
                        'last_update' => $now->format("Y-m-d H:i:s")
                    ];
                    $current_session = \ttact\Models\SessionModel::createNew($this->db, $data_for_new_session);
                    if ($current_session instanceof \ttact\Models\SessionModel) {
                        // redirect to Startseite
                        $this->misc_utils->redirect();
                    }
                }
            }
        } else {
            $error = false;
        }

        if ($error) {
            $this->smarty_vars['error'] = '<strong>Login fehlgeschlagen:</strong> Ungültige Zugangsdaten.';
        }

        $now = new \DateTime("now");
        if ($now->format("Y") > 2017) {
            $this->smarty_vars['datum'] = "2017 - " . $now->format("Y");
        } else {
            $this->smarty_vars['datum'] = "2017";
        }

        $this->template = 'login';
    }
}
