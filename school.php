<?php
require_once "pdo.php" ;
define('DB_SERVER', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', 'root');
define('DB_NAME', 'demo');

if (isset($_GET['term'])){

$stmt = $pdo->prepare('SELECT name FROM institution WHERE name LIKE :prefix');
        $stmt->execute(array( ':prefix' => $_GET['term']."%"));
        $retval = array();
        while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
        $retval[] = $row['name'];
        }

        echo (json_encode($retval , JSON_PRETTY_PRINT));

      }

?>