<?php
trait DentistTrait {
    function validateEmail($email) {
        $regex = '/^(?!(?:(?:[^@]+@)|(?:.+\.\.))[a-z0-9!#$%&\'*+\/=?^_`{|}~-]+@(?:(?:[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?\.)+[a-z0-9][a-z0-9-]{0,61}[a-z0-9]|(?:[a-z0-9-]+\.)?[a-z0-9]{2,})(?<!\.)$/iD';

        if (preg_match($regex, $email)) {
            return true;
        } else {
            return false;
        }
    }
}
?>