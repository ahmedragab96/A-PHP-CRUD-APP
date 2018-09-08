<?php // Do not put any HTML above this line
    require_once "pdo.php" ;
    session_start();
    require_once "functions.php";

    
        $stmt = $pdo->prepare("SELECT * FROM position WHERE profile_id = :pid ORDER BY rank");
        $stmt->execute (array( ':pid' => $_REQUEST['profile_id']));
        $positions = array();
        while ( $row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $positions [] = $row ;
        }



    if ( ! isset($_SESSION['email']) )
		 die('Not logged in');
   
    if(isset($_POST['first_name']) 
    || isset($_POST['last_name'] )|| isset($_POST['email']) || isset($_POST['headline']) || isset($_POST['summary']) || isset($_POST['profile_id']))
        {
  
            $validate_edit = validatedata();
            if (is_string($validate_edit))
                {
                    $_SESSION['edit_failure'] = $validate_edit;
                    header("Location: edit.php?profile_id=".$_REQUEST['profile_id']);
                    return;
                }
                if (strlen($_POST['img']) > 0 )
                    {
                        if(checkRemoteFile($_POST['img']))
                            {
                                $sql = "UPDATE profile SET first_name = :first ,
                                last_name = :last , headline = :head , email = :email ,
                                summary = :summary , image = :img WHERE profile_id = :profile_id" ;
                                $stmt = $pdo -> prepare ($sql);
                                $stmt->execute (array(
                                    ':first' => $_POST['first_name'] ,
                                    ':last' => $_POST['last_name'] ,
                                    ':email' => $_POST['email'] ,
                                    ':head' => $_POST['headline'],
                                    ':summary' => $_POST['summary'],
                                    ':img' => $_POST['img'],
                                    ':profile_id' => $_POST['profile_id']));

                                $stmt = $pdo->prepare('DELETE FROM Position WHERE profile_id=:pid');
                                $stmt->execute(array( ':pid' => $_REQUEST['profile_id']));

                                addpos($pdo , $_REQUEST['profile_id']);


                                $_SESSION['success'] = "Record edited";
                                header("Location: index.php");
                                return;
                            }
                        else 
                            {
                                $_SESSION['edit_failure'] = "Wrong URL";
                                header("Location: edit.php?profile_id=".$_REQUEST['profile_id']);
                                return;
                            }
                    }
                else
                    {
                        $sql = "UPDATE profile SET first_name = :first ,
                        last_name = :last , headline = :head , email = :email ,
                        summary = :summary , image = :img WHERE profile_id = :profile_id" ;
                        $stmt = $pdo -> prepare ($sql);
                        $stmt->execute (array(
                            ':first' => $_POST['first_name'] ,
                            ':last' => $_POST['last_name'] ,
                            ':email' => $_POST['email'] ,
                            ':head' => $_POST['headline'],
                            ':summary' => $_POST['summary'],
                            ':img' => NULL ,
                            ':profile_id' => $_POST['profile_id']));


                            $stmt = $pdo->prepare('DELETE FROM Position WHERE profile_id=:pid');
                            $stmt->execute(array( ':pid' => $_REQUEST['profile_id']));

                            addpos($pdo ,  $_REQUEST['profile_id']);

                        $_SESSION['success'] = "Record edited";
                        header("Location: index.php");
                        return;
                        
                    }
            
        
        }
        $sql = "SELECT * from profile WHERE profile_id = :id" ;
        
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
        $email = htmlentities($row['email']);
        $headline = htmlentities($row['headline']);                
        $summary = htmlentities($row['summary']); 
        $img = htmlentities($row['image']); 
        $profile_id = htmlentities($row['profile_id']);
?>

<!DOCTYPE html>
<html>
    <head>
        <?php require_once "header.php"; ?>
    </head>
    <body>
        
        <div class = "container">
        
        <div class="col-md-6 col-md-offset-3">
        <h1>Editing Profile for <?= htmlentities($_SESSION['email'])?></h1>
        <?php 
            if (isset($_SESSION['edit_failure']))
                {
                    echo('<div class="alert alert-danger" role="alert">'.htmlentities($_SESSION['edit_failure'])."</div>\n");
                    unset($_SESSION['edit_failure']);
                }
        ?>
        <form method="POST">
                <div class="form-group">
                    <label>First Name: </label>
                    <input type="text" class="form-control" name="first_name"  value = "<?= $fn ?>"/>
                </div>
                <div class="form-group">
                        <label>Last Name: </label>
                        <input type="text" class="form-control" name="last_name" value = "<?= $ln ?>"/>
                </div>
                <div class="form-group">
                        <label>Email: </label>
                        <input type="email" class="form-control" name="email" value = "<?= $email ?>"/>
                </div>
                <div class="form-group">
                        <label>Headline: </label>
                        <input type="text" class="form-control" name="headline" value = "<?= $headline ?>"/>
                </div>
                <div class="form-group">
                        <label>Summary: </label>
                        <textarea name="summary" class="form-control" rows="8" cols="80"> <?= $summary ?></textarea>
                </div>
                <div class="form-group">
                        <label>Image: </label>
                        <input type="text" class="form-control" name="img" value = "<?= $img ?>"/>
                </div>
                <div class="form-group">
                        <label>Positions: </label>
                        <input type="submit" class="btn btn-default"  id="addpos" value = "+"/>
                </div>
                <div id= "position_fields" class= "form-group">
                <?php 
                    $pos = 1;
                    foreach ( $positions as $pos_edit )
                        {
                            echo ('<div id="position'.$pos_edit['rank'].'">'); 
                            echo('Year: <input type="text" class="form-control" name="year'.$pos_edit['rank'].'" value="' .$pos_edit['year'].'"/>');
                            echo('<textarea name="desc'.$pos_edit['rank'].'" class="form-control" rows="2" cols="80" style = "margin-top : 10px; ">'.$pos_edit['description'].'</textarea>
                            ');
                            echo ('<input type="button" class="btn btn-danger" value="-" ');
                            echo('onclick="$(\'#position'.$pos_edit['rank'].'\').remove();return false;"></div> ');
                            $pos +=1;
                        }
                ?>
                </div>
                <div class="form-group">
                        <input type="hidden" class="form-control" name = "profile_id" value="<?= $profile_id?>"/>
                </div>
                <div class="form-group">
                        <input type="submit" class="btn btn-success" value="Save" />
                        <input type="submit" class="btn btn-danger" value="Cancel"/>
                </div>

            </form>
            </div>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
            <script src="js/bootstrap.min.js"></script>

        <script>
    var count_pos = <?= $pos ?>;
    $(document).ready(function(){
    $("#addpos").click (function(event){
        event.preventDefault();
        if(count_pos > 9){
            alert("Maximum of nine enteries exceeded!");
            return;
        }
        

        $("#position_fields").append(
        '<div id="position'+count_pos+'"> \
            <p>Year: <input type="text" class="form-control" name="year'+count_pos+'" value="" /> \
            <textarea name="desc'+count_pos+'" class="form-control" rows="8" cols="80" style = "margin-top : 10px;"></textarea>\
            <input type="button" class="btn btn-danger" value="-" \
                onclick="$(\'#position'+count_pos+'\').remove();return false;"></p> \
            </div>');

            count_pos+=1;
    });

    });

</script>
    </body>
</html>