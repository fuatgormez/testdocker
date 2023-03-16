<?php

namespace ttact;

/**
 * UserInput short summary.
 *
 * UserInput description.
 *
 * @version 1.0
 * @author Mian
 */
class UserInput
{
    private $global_var_GET;
    private $global_var_POST;
    private $desired_controller_name;
    private $desired_action_name;
    private $url_parameters;

    public function __construct(array $global_var_GET, array $global_var_POST)
    {
        $this->global_var_GET = $global_var_GET;
        $this->global_var_POST = $global_var_POST;

        $this->desired_controller_name = "";
        $this->desired_action_name = "";
        $this->url_parameters = [];

        if (isset($this->global_var_GET['url'])) {
            if ($this->global_var_GET['url'] != '' && !is_array($this->global_var_GET['url'])) {
                $url = explode('/', filter_var(rtrim($this->global_var_GET['url'], '/'), FILTER_SANITIZE_URL));
                if (isset($url[0])) {
                    // desired controller is given
                    $this->desired_controller_name = ucfirst(strtolower($url[0]));
                    unset($url[0]);
                    if (isset($url[1])) {
                        // desired action is given
                        $this->desired_action_name = strtolower($url[1]);
                        unset($url[1]);
                        if (isset($url[2])) {
                            // additional parameters are given
                            $this->url_parameters = array_values($url);
                        }
                    }
                }
            }
        }
    }

    public function getPostParameter(string $name)
    {
        if (isset($this->global_var_POST[$name])) {
            return filter_var(trim($this->global_var_POST[$name]), FILTER_SANITIZE_STRING);
        }
        return "";
    }

    public function getArrayPostParameter(string $name) {
        $return = [];

        if (isset($this->global_var_POST[$name])) {
            if (is_array($this->global_var_POST[$name])) {
                foreach ($this->global_var_POST[$name] as $index => $value) {
                    if (is_array($value)) {
                        foreach ($value as $index2 => $value2) {
                            $return[$index][$index2] = filter_var(trim($value2), FILTER_SANITIZE_STRING);
                        }
                    } else {
                        $return[$index] = filter_var(trim($value), FILTER_SANITIZE_STRING);
                    }
                }
            }
        }

        return $return;
    }

    public function getDesiredControllerName()
    {
        return $this->desired_controller_name;
    }

    public function getDesiredActionName()
    {
        return $this->desired_action_name;
    }

    public function getURLParameters()
    {
        return $this->url_parameters;
    }

    public function isPositiveInteger($input)
    {
        return filter_var($input, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    }

    public function isPostleitzahl($input)
    {
        return filter_var($input, FILTER_VALIDATE_INT, ['options' => ['min_range' => 10000, 'max_range' => 99999]]);
    }

    /**
     * @return integer
     */
    public function getOnlyNumbers($input)
    {
        return preg_replace("/[^0-9]/", "", $input);
    }

    public function isEmailadresse($input)
    {
        return filter_var(str_replace(["ä", "ü", "ö", "Ä", "Ü", "Ö"], "x", $input), FILTER_VALIDATE_EMAIL);
    }

    public function isDate($input)
    {
        if (preg_match("/[0-3][0-9][.][0-1][0-9][.][1-2][0-9][0-9][0-9]/", $input)) {
            $parts = explode('.', $input);
            if (isset($parts[0]) && isset($parts[1]) && isset($parts[2])) {
                $tag = (int) $parts[0];
                $monat = (int) $parts[1];
                $jahr = (int) $parts[2];
                $test = new \DateTime("now");
                $test->setDate($jahr, $monat, $tag);
                if ($test->format("d.m.Y") == $input) {
                    return true;
                }
            }
        }
        return false;
    }
}
