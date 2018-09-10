<?php 
    session_start();
    require_once "pdo.php" ;
    require_once "functions.php";

   /* if ( ! isset($_SESSION['email']) )
		 die('Not logged in');
   */

    if ( isset($_POST['cancel']) ) {
        header('Location: index.php');
        return;
    }
    if (isset($_POST['first_name']) && isset($_POST['last_name']) && 
    isset($_POST['email']) && isset($_POST['headline']) &&
    isset($_POST['summary']) ) {

            $validate_data = validatedata();
            if (is_string($validate_data))
                {
                    $_SESSION['add_failure'] = $validate_data ;
                    header("Location: add.php");
                    return;
                }

            
            $msg = validatepos();
            if (is_string($msg))
                {
                    $_SESSION['add_failure'] = $msg ;
                    header("Location: add.php");
                    return;
                }
            

        if (strlen($_POST['img']) > 0 )
            {
                if(checkRemoteFile($_POST['img']))
                    {
                        $stmt = $pdo->prepare('INSERT INTO profile
                        (user_id, first_name, last_name, email, headline, summary , image)
                        VALUES ( :uid, :fn, :ln, :em, :he, :su , :im)');

                        $stmt->execute(array(
                        ':uid' => $_SESSION['user_id'],
                        ':fn' => $_POST['first_name'],
                        ':ln' => $_POST['last_name'],
                        ':em' => $_POST['email'],
                        ':he' => $_POST['headline'],
                        ':su' => $_POST['summary'],
                        ':im' => $_POST['img']));

                        $profile_id = $pdo->lastInsertId();
                        
                        addpos($pdo , $profile_id);


                        $_SESSION['success'] = "Profile Added";
                        header("Location: index.php");
                        return;
                    }
                else 
                    {
                        $_SESSION['add_failure'] = "Wrong URL";
                        header("Location: add.php");
                        return;
                    }
            }
        else
            {
                $stmt = $pdo->prepare('INSERT INTO profile
                        (user_id, first_name, last_name, email, headline, summary , image)
                        VALUES ( :uid, :fn, :ln, :em, :he, :su , :im)');

                        $stmt->execute(array(
                        ':uid' => $_SESSION['user_id'],
                        ':fn' => $_POST['first_name'],
                        ':ln' => $_POST['last_name'],
                        ':em' => $_POST['email'],
                        ':he' => $_POST['headline'],
                        ':su' => $_POST['summary'],
                        ':im' => NULL ));

                        $profile_id = $pdo->lastInsertId();
                        
                        addpos($pdo , $profile_id);

                        $_SESSION['success'] = "Profile Added";
                        header("Location: index.php");
                        return;
                
            }
        }
    


?>

<!DOCTYPE html>
<html>
<head>
    <?php require_once "header.php"; ?>
</head>
<body>



<div class="container" style = "width: 70%;" id = "forum"> 
<h1>Adding PROFILE FOR <?= htmlentities($_SESSION['email'])?></h1>
<?php 
            if (isset($_SESSION['add_failure']))
                {
                    echo('<div class="alert alert-danger" role="alert">'.htmlentities($_SESSION['add_failure'])."</div>\n");
                    unset($_SESSION['add_failure']);
                }
        ?>
            <form method="POST">
                <div class="form-group">
                    <label>First Name: </label>
                    <input type="text" class="form-control" name="first_name"/>
                </div>
                <div class="form-group">
                        <label>Last Name: </label>
                        <input type="text" class="form-control" name="last_name"/>
                </div>
                <div class="form-group">
                        <label>Email: </label>
                        <input type="email" class="form-control" name="email"/>
                </div>
                <div class="form-group">
                        <label>Headline: </label>
                        <input type="text" class="form-control" name="headline"/>
                </div>
                <div class="form-group">
                        <label>Summary: </label>
                        <textarea name="summary" class="form-control" rows="3" cols="50"></textarea>
                </div>
                <div class="form-group">
                        <label>Image: </label>
                        <input type="text" class="form-control" name="img"/>
                </div>
                <div class="form-group">
                        <label>Education: </label>
                        <input type="submit" class="btn btn-default"  id="addedu" value = "+"/>
                </div>
                <div id= "education_fields" class= "form-group"></div>

                <div class="form-group">
                        <label>Positions: </label>
                        <input type="submit" class="btn btn-default"  id="addpos" value = "+"/>
                </div>
                <div id= "position_fields" class= "form-group"></div>

                <div class="form-group">
                        <input type="submit" class="btn btn-success" value="Add" />
                        <input type="submit" class="btn btn-success" value="Cancel"/>
                </div>

            </form>

<script>
    $('#forum').height($(window).height() + "px");
    var count_pos = 1;
    var count_edu = 1 ;
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
            <textarea name="desc'+count_pos+'" class="form-control" rows="2" cols="80" style = "margin-top : 10px;"></textarea>\
            <input type="button" class="btn btn-danger" value="-" \
                onclick="$(\'#position'+count_pos+'\').remove();return false;"></p> \
            </div>');

            count_pos+=1;
    });
    $("#addedu").click (function(event){
        event.preventDefault();
        if(count_edu > 9){
            alert("Maximum of nine enteries exceeded!");
            return;
        }
        var source = $("#edu_template").html();
        $("#education_fields").append(source.replace(/@COUNT@/g,count_edu));

            count_edu+=1;
    /*    $('.school').autocomplete({
                source : "school.php"
            });*/
    });
   /* $('.school').autocomplete({
                source : "school.php"
            });*/

    });

</script>
<script id= "edu_template" type = "text">
    <div id = "edu@COUNT@">
            <p>Year: <input type="text" class="form-control" name="edu_year@COUNT@" value="" /> 
            <p>School: <input type="text" class="form-control" name="edu_school@COUNT@" value="" class = "school"/>
            <input type="button" class="btn btn-danger" value="-" onclick="$('#edu@COUNT@').remove();return false;"></p> 
    </div>
</script>
    </div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
