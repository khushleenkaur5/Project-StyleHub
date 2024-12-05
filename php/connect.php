<?php

/********w********
 * Name: Khushleen Kaur
 * Date: Nov 21, 2024
 * Description: Establishes a connection to the database using PDO. 
 ****************/

 if (!defined('DB_DSN')) {
    define('DB_DSN', 'mysql:host=localhost;dbname=stylehub;charset=utf8');
 }
 if (!defined('DB_USER')) {
    define('DB_USER' , 'khushleen131');
 }
 if (!defined('DB_PASS')) {
    define('DB_PASS', 'khushik13@');
 }

 try {
    $pdo = new PDO(DB_DSN, DB_USER, DB_PASS);
 } catch (PDOException $e) {
    print "Error: " . $e->getMessage();
    die();
 }
 ?>