<?php

namespace ttact\Controllers;

abstract class Controller
{
    protected $db;
    protected $action;
    protected $params;
    protected $smarty;
    protected $current_user;
    protected $user_input;
    protected $smarty_vars;
    protected $template;
    protected $misc_utils;
    protected $session_id;
    protected $current_session;
    protected $password_utils;
    protected $company;

    public function __construct(\ttact\Database $db, string $action, array $params, \Smarty $smarty, $current_user, \ttact\UserInput $user_input, \ttact\MiscUtils $misc_utils, string $session_id, $current_session, \ttact\PasswordUtils $password_utils, String $company)
    {
        $this->db = $db;
        $this->action = $action;
        $this->params = $params;
        $this->smarty = $smarty;
        $this->current_user = $current_user;
        $this->user_input = $user_input;
        $this->smarty_vars = [];
        $this->template = '404';
        $this->misc_utils = $misc_utils;
        $this->session_id = $session_id;
        $this->current_session = $current_session;
        $this->password_utils = $password_utils;
        $this->company = $company;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getPageContent()
    {
        /**
         * Execute respective action before the page is rendered in the next step.
         */
        $action = $this->action;
        if (method_exists($this, $this->action . strtoupper($this->company))) {
            $method_name = $this->action . strtoupper($this->company);
            $this->$method_name();
        } else {
            $this->$action();
        }

        /**
         * Render the page
         */
        switch ($this->template) {
            case 'main':
                $controller = explode('\\', get_class($this));
                $controller = str_replace('Controller', '', $controller[count($controller) - 1]);

                if (file_exists($this->smarty->getTemplateDir()[0] . 'main/' . $controller . '/' . $this->action . '.' . $this->company . '.tpl')) {
                    $content = 'views/main/' . $controller . '/' . $this->action . '.' . $this->company . '.tpl';
                } elseif (file_exists($this->smarty->getTemplateDir()[0] . 'main/' . $controller . '/' . $this->action . '.tpl')) {
                    $content = 'views/main/' . $controller . '/' . $this->action . '.tpl';
                } else {
                    goto error;
                }

                // custom CSS
                if (file_exists(__DIR__ . '/../../../assets/custom.' . $this->company . '.css')) {
                    $this->smarty_vars['path_to_company_css'] = '/assets/custom.' . $this->company . '.css';
                }

                // custom JS
                if (file_exists(__DIR__ . '/../../../assets/custom.' . $this->company . '.js')) {
                    $this->smarty_vars['path_to_company_js'] = '/assets/custom.' . $this->company . '.js';
                }

                // Favicon
                if (file_exists(__DIR__ . '/../../../assets/favicon.' . $this->company . '.png')) {
                    $this->smarty_vars['path_to_favicon'] = '/assets/favicon.' . $this->company . '.png';
                }

                $this->smarty_vars['content'] = $content;
                $this->smarty_vars['current_user']['full_name'] = $this->current_user->getName();
                $this->smarty_vars['current_user']['usergroup'] = $this->current_user->getUsergroup();
                $kundenbeschraenkungen = $this->current_user->getKundenbeschraenkungen();
                $this->smarty_vars['current_user']['kundennummern'] = [];
                if (count($kundenbeschraenkungen) > 0) {
                    foreach ($kundenbeschraenkungen as $kundenbeschraenkung) {
                        $this->smarty_vars['current_user']['kundennummern'][] = $kundenbeschraenkung->getKunde()->getKundennummer();
                    }
                }



                break;


            case 'ajax':
                $error = true;

                if (isset($this->smarty_vars['data'])) {
                    if (is_array($this->smarty_vars['data'])) {
                        if (isset($this->smarty_vars['data']['status'])) {
                            if ($this->smarty_vars['data']['status'] == 'success' || $this->smarty_vars['data']['status'] == 'not_logged_in' || $this->smarty_vars['data']['status'] == 'error') {
                                $error = false;
                            }
                        }
                    }
                }

                if ($error) {
                    $this->smarty_vars['data'] = ['status' => 'error'];
                } elseif (isset($this->smarty_vars['data']['no_status'])) {
                    unset($this->smarty_vars['data']['status']);
                    unset($this->smarty_vars['data']['no_status']);
                }

                $this->smarty_vars['data'] = json_encode($this->smarty_vars['data']);
                header('Content-Type: application/json');
                break;
            case 'login':
                if (file_exists(__DIR__ . '/../../../assets/custom.' . $this->company . '.css')) {
                    $this->smarty_vars['path_to_company_css'] = '/assets/custom.' . $this->company . '.css';
                }

                if (file_exists(__DIR__ . '/../../../assets/favicon.' . $this->company . '.png')) {
                    $this->smarty_vars['path_to_favicon'] = '/assets/favicon.' . $this->company . '.png';
                }
                break;
            case 'blank':
                break;
            case '404':
                error:
                $this->template = '404';
                if (file_exists(__DIR__ . '/../../../assets/favicon.' . $this->company . '.png')) {
                    $this->smarty_vars['path_to_favicon'] = '/assets/favicon.' . $this->company . '.png';
                }
                break;
            default:
                goto error;
        }

        // be able to access the company within Smarty templates
        $this->smarty_vars['company'] = $this->company;

        // determine the software title
        if ($this->company == '') {
            $this->smarty_vars['software_name'] = 'XXX';
        } else {
            $this->smarty_vars['software_name'] = strtoupper($this->company);
        }

        $this->smarty->assign('smarty_vars', $this->smarty_vars, true);

        if (file_exists($this->smarty->getTemplateDir()[0] . $this->template . '.' . $this->company . '.tpl')) {
            return $this->smarty->fetch($this->template . '.' . $this->company . '.tpl');
        } else {
            return $this->smarty->fetch($this->template . '.tpl');
        }
    }
}
