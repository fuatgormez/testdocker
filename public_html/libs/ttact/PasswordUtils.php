<?php

namespace ttact;

/**
 * PasswordUtils short summary.
 *
 * PasswordUtils description.
 *
 * @version 1.0
 * @author Mian
 */
class PasswordUtils
{
    public function hash($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public function isCorrect($password, $hash)
    {
        return password_verify($password, $hash);
    }
}
