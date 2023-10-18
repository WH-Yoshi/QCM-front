<?php 
try {
    $db = new PDO('mysql:host=127.0.0.1;dbname=testQCM2', 'techweb', 'Tigrou007=');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error : ". $e->getMessage());
}
?>