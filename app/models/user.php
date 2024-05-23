<?php

abstract class User {
    abstract protected function login($email, $password);
    abstract protected function isEmailExists($email);
}

?>