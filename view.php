<?php
require_once "pdo.php" ;
session_start();

$stmt = $pdo->prepare('SELECT first_name , last_name , summary , headline , email , user_id FROM profile

    WHERE profile_id = :id');
    
    $stmt->execute(array( ':id' => $_GET['profile_id']));
    
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row !== false)
        {
            $fn = $row['first_name'];
            $ln = $row['last_name'];
            $em = $row['email'];
            $he = $row['headline'];
            $su = $row['summary'];
        }

        $stmt = $pdo->prepare('SELECT year, description FROM position WHERE profile_id = :pid');

        $stmt->execute(array( ':pid' => $_GET['profile_id']));

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html>
<head>
    <?php require_once "header.php"; ?>
</head>
<body>
<div class = "container" style = "width: 75%; height: 100px;">
    <h1> Profile Information for <?= htmlentities($fn) ." ". htmlentities($ln)?></h1>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">First Name:</h3>
        </div>
        <div class="panel-body">
            <?=htmlentities($fn);?>
        </div>
    </div>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">Last Name:</h3>
        </div>
        <div class="panel-body">
            <?=htmlentities($ln);?>
        </div>
    </div>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">Email: </h3>
        </div>
        <div class="panel-body">
            <?=htmlentities($em);?>
        </div>
    </div>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">Headline: </h3>
        </div>
        <div class="panel-body">
            <?=htmlentities($he);?>
        </div>
    </div>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">Summary: </h3>
        </div>
        <div class="panel-body">
            <?=htmlentities($su);?>
        </div>
    </div>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">Positions: </h3>
        </div>
        <div class="panel-body">
            <?php
                    echo "<ul>";
                    foreach ( $rows as $pos ) {
                        echo("<li>");
                        echo(htmlentities($pos['year'])). " :";
                        echo(htmlentities($pos['description']));
                        echo("</li>\n");
                    }
                    echo("</ul>\n"); 
            ?>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
            <script src="js/bootstrap.min.js"></script>

</body>
</html>