<?php
require_once "pdo.php";
session_start();

if ( ! isset($_SESSION['email']) )
		 die('Not logged in');
   

if(isset($_POST['cancel']))
    {
        header("Location: index.php");
        return;
    }

if ( isset($_POST['profile_id'] )&& isset($_POST['Delete'])  ) {
    $sql = "DELETE FROM profile WHERE profile_id = :zip";
    echo "<pre>\n$sql\n</pre>\n";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':zip' => $_GET['profile_id']));
    $_SESSION['success'] = "Profile deleted";
        header("Location: index.php");
        return;
}
$sql = "SELECT first_name, last_name, profile_id from profile WHERE profile_id = :id" ;
        
        $stmt = $pdo -> prepare ($sql);
        $stmt->execute (array( ':id' => $_GET['profile_id'] ) );
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row === false)
            {
                $_SESSION['error'] = "BAD VALUE!";
                header( 'Location: index.php');
                return;
            }
        $fn = htmlentities($row['first_name']);
        $ln = htmlentities($row['last_name']);             
        $profile_id = htmlentities($row['profile_id']);     


?>
<!DOCTYPE html>
<html>
<head>
    <?php require_once "header.php"; ?>
</head>
<body>
    <div class="container">
    <h2>Delete A Profile</h2>
    <form method="post">
        <p>Are you sure you want to delete the profile of <?= htmlentities($fn) ." ". htmlentities($ln)?>?
        <input type="hidden" name="profile_id" value = "<?= $profile_id?>"></p>
        <div class = "form-group">
            <input type="submit" class="btn btn-success" name = "Delete" value="Delete" />
            <input type="submit" class="btn btn-danger" name = "cancel" value="Cancel"/>
        </div>
    </form>
    </div>
</body>
</html>
