<?php 
try {
    $db = new PDO('mysql:host=192.168.45.206;dbname=testQCM2', 'techweb', 'Tigrou007=');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error: ". $e->getMessage());
}