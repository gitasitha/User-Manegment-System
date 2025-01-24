<?php session_start();?>
<?php require_once('inc/connection.php'); ?>
<?php require_once('inc/functions.php'); ?>

<?php
if(!isset($_SESSION['userId'])){
    header('Location:index.php');
    }

$errors=array();
$user_id="";
$first_name="";
$last_name= "";
$email="";


if(isset($_GET["user_id"])){

    //Getting the user information
    $user_id= mysqli_real_escape_string($connection,$_GET["user_id"]);
    $query= "SELECT * FROM user WHERE id={$user_id}  LIMIT 1";

    $resultSet= mysqli_query($connection,$query);

    if($resultSet){
        if(mysqli_num_rows($resultSet) == 1){
            //user found
            $result=mysqli_fetch_assoc($resultSet);
            $first_name=$result['first_name'];
            $last_name= $result['last_name'];
            $email=$result['email'];
        }else{
            //user not found
            header("Location: users.php?err=user_not_found");
        }
    }else{
    //Query unsuccessfull 
    header("Location users.php?err=Query faild");
    }
}

if(isset($_POST['submit'])){

    $user_id=$_POST['user_id'];
    $password=$_POST['password'];
    

    $req_fields= array('password');

    foreach($req_fields as $field){

        if(empty(trim($_POST[$field]))){

            $errors[]=$field .'is requrd';
        }
    }
    $max_length_field= array('password'=>40);

    foreach($max_length_field as $field=>$max_length){

        if(strlen(trim($_POST[$field])) > $max_length){

            $errors[]=$field .' is must be less than '.$max_length.' charactor';
        }
    }

    if(empty($errors)){
		$password=mysqli_real_escape_string($connection,$_POST['password']);		
		$hashed_password=sha1($password);	

        $query="UPDATE user SET password='{$hashed_password}' WHERE id = {$user_id} LIMIT 1";

        $resultSet=mysqli_query($connection,$query);
        if($resultSet){
            header('Location: users.php?user_modified=true');
            // echo"Added Successfully!";
        }else{
            $errors[]= 'faild to change the password';
        }
	}


}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body>      
    <header>
        <div class="appname">User Manegment System</div>
        <div class="loggedin"> Welcome <?php echo $_SESSION['first_name']." ".$_SESSION['last_name']." ";?><a href="logout.php">LogOut</a></div>
    </header>
    <main>
        <h1>Change Password<span><a href="users.php"> < Back to Users</a></span></h1>

        <?php
        
        if(!empty($errors)){

            echo "<div class=errormsg>";
            echo "<b>There were error's on your form Errors on your from</b><br>";
            foreach($errors as $error){
                    echo $error."<br>";
                    
            }

            echo "</div>";
        }
        
        ?>
         
       <form action="change-password.php" method="post" class="userform">
        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
        

        <p>
            <label for="">First Name</label>
            <input type="text"name="first_name" <?php echo "value={$first_name}"?> disabled>
        </p>
        <p>
            <label for="">Last Name</label>
            <input type="text" name="last_name"<?php echo "value={$last_name}"?> disabled>
        </p>
        <p>
            <label for="">Email</label>
            <input type="text" name="email"<?php echo "value={$email}"?> disabled>
        </p>
        <p>
            <label for="">New Password</label>
           <input type="password" name="password">
            
        </p>
        <p>
            <label for="">&nbsp;</label>
            <button type="submit" name="submit"  >Change Password</button>
        </p>
        <!-- <p>
            <label for="">&nbsp;</label>
            <button type="reset" name="reset">Clear</button>
        </p> -->



       </form>

    </main>
    
</body>
</html> 