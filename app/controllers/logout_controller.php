<?php
session_start(); // Rozpoczęcie sesji

// Wyczyszczenie tablicy sesji
$_SESSION = array();

// Zniszczenie sesji
session_destroy();

// Przekierowanie użytkownika do strony głównej
header("location: ../views/index.php");
exit; // Zakończenie skryptu