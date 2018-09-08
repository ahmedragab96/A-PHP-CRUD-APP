<?php // Do not put any HTML above this line
require_once "pdo.php" ;
session_start();
if ( isset($_POST['cancel'] ) ) {
    // Redirect the browser to game.php
    header("Location: index.php");
    return;
}

$salt = 'XyZzy12*_';
//$stored_hash = 'a8609e8d62c043243c4e201cbb342862';  // Pw is meow123


if ( isset($_POST['email']) && isset($_POST['pass']) ) {

    unset($_SESSION["email"]) ;

    if ( strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1 ) {
        $_SESSION['error'] = "User name and password are required";
        header("Location: login.php");
        return;
        } 
    
    $check = hash('md5', $salt.$_POST['pass']);

    $stmt = $pdo->prepare('SELECT user_id, name FROM users

    WHERE email = :em AND password = :pw');
    
    $stmt->execute(array( ':em' => $_POST['email'], ':pw' => $check));
    
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ( $row !== false ) {

        $_SESSION['email'] = $row['name'];
        
        $_SESSION['user_id'] = $row['user_id'];
        
        // Redirect the browser to index.php
        
        header("Location: index.php");
        
        return;
    }
    else {
        $_SESSION['error'] = "Incorrect Password";
        header("Location: login.php");
        return;
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Ahmed Ragab</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link rel="stylesheet" 
    href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" 
    integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" 
    crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" 
    href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" 
    integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" 
    crossorigin="anonymous">

    <script>
            function doValidate() {
                console.log('Validating...');
                try {
                    addr = document.getElementById('email').value;
                    pw = document.getElementById('id_1723').value;
                    console.log("Validating addr="+addr+" pw="+pw);
                    if (addr == null || addr == "" || pw == null || pw == "") {
                        alert("Both fields must be filled out");
                        return false;
                    }
                    if ( addr.indexOf('@') == -1 ) {
                        alert("Invalid email address");
                        return false;
                    }
                    return true;
                } catch(e) {
                    return false;
                }
                return false;
            }
    </script>
</head>
<body>
<div class="container">
<h1>Please Log In</h1>
<?php
// Note triple not equals and think how badly double
// not equals would work here...
if ( isset($_SESSION['error']) ) {
  echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
  unset($_SESSION['error']);
}
?>
<form method="POST" action = "login.php">
    <label for="nam">Email</label>
    <input type="text" name="email" id="email"><br/>
        <label for="id_1723">Password</label>
        <input type="text" name="pass" id="id_1723"><br/>
    <input type="submit" onclick = "return doValidate();" value="Log In">
    <input type="submit" name="cancel" value="Cancel">
</form>
<p>
For a password hint, view source and find a password hint
in the HTML comments.
<!-- Hint: The password is the three character name of the 
programming language used in this class (all lower case) 
followed by 123. -->
</p>
</div>
</body>
</html>