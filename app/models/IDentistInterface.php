<?php
interface IDentistInterface {
    function create();
    function getDentistById($dentist_id);
    function readAll();
    function delete($id);
    function updateProfile($id, $firstName, $lastName, $email, $specialization);
    function isAdministrator($id);
    function isEmailUsedByAnotherDentist($id, $email);
}
?>