<?php 
require_once "pdo.php";
session_start();

$stmt = $pdo->query("SELECT first_name, last_name, email, headline, summary , image , profile_id , user_id FROM profile");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);





?>


<!DOCTYPE html>
<html>
<head>
    <?php require_once "header.php"; ?>
</head>
<body>
    <div class="container">
    
    <h1>Welcome to Profiles Database</h1>
    <?php
        if(isset($_SESSION['success'])){
            echo('<div class="alert alert-success" role="alert">'.htmlentities($_SESSION['success'])."</div>\n");
            unset($_SESSION['success']);
            }
        if(isset($_SESSION['error'])){
            echo('<div class="alert alert-danger" role="alert">'.htmlentities($_SESSION['error'])."</div>\n");
            unset($_SESSION['error']);
    }
    ?>
    <?php 
        if ( ! isset($_SESSION['email']))
                echo ('<p><a href="login.php">'."Please log in</a></p>");
        else {
            echo("<br>");
            echo("<a href= 'add.php'> Add New Entry </a><br>");
            echo("<a href= 'logout.php'> Logout </a>");

        }
                    echo("<table border='1' class= 'table table-striped table-bordered'>");
                        echo "<tr><th>";
                        echo("Image ");
                        echo("</th><th>");
                        echo("Name ");
                        echo("</th><th>");
                        echo("Headline");
                        echo("</th><th>");
                        echo("Action");
                        echo("</th></tr>");

                    foreach ( $rows as $row ) 
                        {
                        echo "<tr><td>";
                        if($row['image'] !== NULL)
                            {
                                        $imageData = base64_encode(file_get_contents($row['image']));
                                        echo '<img src="data:image/jpeg;base64,'.$imageData.'">';
                            }
                        echo("</td><td>");
                        echo('<a href= "view.php?profile_id='.$row['profile_id'].' ">' . htmlentities($row["first_name"]). " " .htmlentities($row["last_name"]) . '</a>');
                        echo("</td><td>");
                        echo(htmlentities($row['headline']). " ");
                        echo("</td><td>");
                        if (isset($_SESSION['user_id'])){
                            if($_SESSION['user_id'] == $row['user_id'])
                            {
                                echo('<form method="post"><input type="hidden" ');
                                echo('name="profile_id" value="'.$row['profile_id'].'">'."\n");
                                echo('<a href = "edit.php?profile_id='.$row['profile_id'] . ' ">EDIT</a> / ');        
                                echo('<a href = "delete.php?profile_id='.$row['profile_id'] . ' ">DELETE</a> ');
                                echo("\n</form>\n");
                                echo("</td></tr>\n");
                            }
                        }
                        else{
                            echo("</td></tr>\n");
                        }
           
                    }
                    
    
    ?>
</table>
    </div>
    <script src="jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
   
</body>
</html>

