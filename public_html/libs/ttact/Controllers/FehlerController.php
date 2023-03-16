<?php

namespace ttact\Controllers;

class FehlerController extends Controller
{
    public function index()
    {
        $this->template = '404';
    }

    public function ajax()
    {
        $this->smarty_vars['data'] = ['status' => 'error'];

        if (isset($this->params[0])) {
            if ($this->params[0] == 'not_logged_in') {
                $this->smarty_vars['data'] = ['status' => 'not_logged_in'];
            }
        }

        $this->template = 'ajax';
    }
}
