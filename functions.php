<?php 

    function validatedata(){

        if ( strlen($_POST['first_name']) == 0 || strlen($_POST['last_name']) == 0 || strlen($_POST['email']) == 0|| strlen($_POST['headline']) == 0 || strlen($_POST['summary']) == 0)
                {
                    return "All values are required";
                }
            
            
            if (strpos( $_POST['email'] , '@' ) == false)
                {
                    return "Email must have an at-sign (@)";
                }
        return true;
    
}


function validatepos() {
    for($i=1; $i<=9; $i++) {
      if ( ! isset($_POST['year'.$i]) ) continue;
      if ( ! isset($_POST['desc'.$i]) ) continue;
  
      $year = $_POST['year'.$i];
      $desc = $_POST['desc'.$i];
  
      if ( strlen($year) == 0 || strlen($desc) == 0 ) {
        return "All fields are required";
      }
  
      if ( ! is_numeric($year) ) {
        return "Position year must be numeric";
      }
    }
    return true;
  }

  function addedu ($pdo , $profile_id){
    $rank = 1;
    for($i=1 ; $i <= 9 ; $i+=1)
        {
            if( ! isset($_POST['year'.$i]) ) continue;
            if( ! isset($_POST['desc'.$i]) ) continue;

            $year = $_POST['year'.$i];
            $desc = $_POST['desc'.$i];

            $stmt = $pdo->prepare('INSERT INTO position
            (profile_id, rank, year, description)
            VALUES ( :pid, :rank , :year, :desc)');

            $stmt->execute(array(
            ':pid' => $profile_id,
            ':rank' => $rank,
            ':year' => $year,
            ':desc' => $desc ));

            $rank++;
        }
}

  
  function validateeducation() {
    for($i=1; $i<=9; $i++) {
      if ( ! isset($_POST['edu_year'.$i]) ) continue;
      if ( ! isset($_POST['edu_school'.$i]) ) continue;
  
      $year = $_POST['edu_year'.$i];
      $school = $_POST['edu_school'.$i];
  
      if ( strlen($year) == 0 || strlen($school) == 0 ) {
        return "All fields are required";
      }
  
      if ( ! is_numeric($year) ) {
        return "Position year must be numeric";
      }
    }
    return true;
  }

  function addedu ($pdo , $profile_id){
    $rank = 1;
    for($i=1 ; $i <= 9 ; $i+=1)
        {
            if( ! isset($_POST['year'.$i]) ) continue;
            if( ! isset($_POST['desc'.$i]) ) continue;

            $year = $_POST['year'.$i];
            $desc = $_POST['desc'.$i];

            $stmt = $pdo->prepare('INSERT INTO position
            (profile_id, rank, year, description)
            VALUES ( :pid, :rank , :year, :desc)');

            $stmt->execute(array(
            ':pid' => $profile_id,
            ':rank' => $rank,
            ':year' => $year,
            ':desc' => $desc ));

            $rank++;
        }
}



function checkRemoteFile($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        // don't download content
        curl_setopt($ch, CURLOPT_NOBODY, 1);
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    
        $result = curl_exec($ch);
        curl_close($ch);
        if($result !== FALSE)
        {
            return true;
            curl_close($ch);
        }
        else
        {
            return false;
            curl_close($ch);
        }
    
    }

    
?>