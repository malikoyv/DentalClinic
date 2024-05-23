<?php

interface IPatientInterface {
    function addNewPatient($firstName, $lastName, $email, $password);
    function isEmailUsedByAnotherPatient($patientId, $email);
    function updateProfile($patientId, $firstName, $lastName, $email);
    function changePassword($patientId, $currentPassword, $newPassword);
}

?>