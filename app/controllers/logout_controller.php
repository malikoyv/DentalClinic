<?php
session_start(); // Rozpoczęcie sesji

// Wyczyszczenie tablicy sesji
$_SESSION = array();

// Zniszczenie sesji
session_destroy();

// Przekierowanie użytkownika do strony głównej
header("location: index.php");
exit; // Zakończenie skryptu
