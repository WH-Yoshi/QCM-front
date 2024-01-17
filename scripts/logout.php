<?php
session_start();
require('examcheck.php');
session_unset();
session_destroy();
echo json_encode(['message' => 'Déconnexion réussie']);
header("Location: ../connection.php");
exit();
