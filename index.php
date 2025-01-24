<?php session_start();?>
<?php require_once('inc/connection.php'); ?>
<?php require_once('inc/functions.php'); ?>
<?php
// cheack for form submiton
if(isset($_POST['submit'])){
    $errors=array();
    
    // cheack if the username password has entered
    if(!isset($_POST['email']) || strlen(trim($_POST['email'])) < 1){
        $errors[] = 'Username is missing or invalid!';
    }

    if(!isset($_POST['password']) || strlen(trim($_POST['password'])) < 1){
        $errors[] = 'Password is missing or invalid!';
    }
    
    
    // chrack if there are any errors in the form
    if(empty($errors)){
    
        // save user name and password into varaible
        $email=mysqli_real_escape_string($connection,$_POST['email']);
        $password=mysqli_real_escape_string($connection,$_POST['password']);
        $hashedPassd=sha1($password); 
        // prepare databases query
        $qurey = "SELECT * FROM user WHERE email='{$email}' AND  password ='{$hashedPassd}'
        LIMIT 1";
        $resultSet = mysqli_query($connection,$qurey);
    
        // cheack if the user is valide 
        if($resultSet){
            //Query succsessful
            if(mysqli_num_rows($resultSet)== 1){
                //Valid user found
                $user = mysqli_fetch_array($resultSet);
                $_SESSION["userId"] = $user["id"];
                $_SESSION["first_name"] = $user["first_name"];
                $_SESSION["last_name"] = $user["last_name"];
                //updating last log in
                $query="UPDATE user SET last_login=now()";
                $query.="WHERE id={$_SESSION["userId"]} LIMIT 1";
                $resultSet=mysqli_query($connection,$query);
                if(!$resultSet){
                    echo "Database query falid";
                }

                // redirect to users.php
                header('Location: users.php');
            }
            else{
                $errors[] = "Userename or Password invalid";
                }
        }
        else{
            $errors[] = "Database query failde";
        }

    
        
    
        // if not, display the error
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In-User Manegment System </title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
    <div class="login">
        <form action="index.php" method="post">
            <fieldset>
                <legend><h1>Log In</h1></legend>
                <?php

                if(isset($errors) && !empty($errors)){
                    echo'<p class="error">You have entered an invalid username or password</p>';
                }
                ?>
                <?php
                  if(isset($_GET['logout'])){
                    echo'<p class="info">You have succesffully logged out from system!</p>';
                  }
                ?>

                <p>
                    <label for="">User Name:</label>
                    <input type="text" name="email" id="" placeholder="Email Addrress" >
                </p>
                <p>
                    <label for="">Password:</label>
                    <input type="password" name="password" id="" placeholder="Password">
                </p>
                <p>
                    <button type="submit" name="submit">Log In</button>
                </p>
            </fieldset>
        </form>
    </div>
</body>
</html>

<?php mysqli_close($connection); ?>