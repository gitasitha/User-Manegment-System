<?php session_start();?>
<?php require_once('inc/connection.php'); ?>
<?php require_once('inc/functions.php'); ?>

<?php
if(!isset($_SESSION['userId'])){
    header('Location:index.php');
    }

$errors=array();
$first_name="";
$last_name= "";
$email="";
$password= "";

if(isset($_POST['submit'])){
    $first_name=$_POST['first_name'];
    $last_name=$_POST['last_name'];
    $email=$_POST['email'];
    $password=$_POST['password'];

    $req_fields= array('first_name','last_name','email','password');

    foreach($req_fields as $field){

        if(empty(trim($_POST[$field]))){

            $errors[]=$field .' is required';
        }
    }
    $max_length_field= array('first_name'=>50,'last_name'=>100,'email'=>100,'password'=>40);

    foreach($max_length_field as $field=>$max_length){

        if(strlen(trim($_POST[$field])) > $max_length){

            $errors[]=$field .' is must be less than '.$max_length.' charactor';
        }
    }

    if(!is_email($_POST['email'])){

        $errors[]='Email address is invalid';
    }

    $email=mysqli_real_escape_string($connection,$_POST['email']);

    $query="SELECT * FROM user WHERE email = '{$email}' LIMIT 1";

    $resultSet=mysqli_query($connection,$query);

    if(mysqli_num_rows($resultSet)==1){

        $errors[]='Email Address Already Exists';
    }
    if(empty($errors)){
		$first_name=mysqli_real_escape_string($connection,$_POST['first_name']);		
		$last_name=mysqli_real_escape_string($connection,$_POST['last_name']);		
		$password=mysqli_real_escape_string($connection,$_POST['password']);		
        $hashedPassd=sha1($password);
        
        $query="INSERT INTO user(first_name,last_name,email,password,is_deleted)values('{$first_name}','{$last_name}','{$email}','{$hashedPassd}',0)";

        $resultSet=mysqli_query($connection,$query);
        if($resultSet){
            header('Location: users.php?user_added=true');
            // echo"Added Successfully!";
        }else{
            $errors[]= 'faild to add the new recode';
        }
	}


}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New User</title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body>      
    <header>
        <div class="appname">User Manegment System</div>
        <div class="loggedin"> Welcome <?php echo $_SESSION['first_name']." ".$_SESSION['last_name']." ";?><a href="logout.php">LogOut</a></div>
    </header>
    <main>
        <h1>Add New User <span><a href="users.php"> < Back to Users</a></span></h1>

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
         
       <form action="add-user.php" method="post" class="userform">
        

        <p>
            <label for="">First Name</label>
            <input type="text"name="first_name" <?php echo "value={$first_name}"?>>
        </p>
        <p>
            <label for="">Last Name</label>
            <input type="text" name="last_name"<?php echo "value={$last_name}"?>>
        </p>
        <p>
            <label for="">Email</label>
            <input type="text" name="email"<?php echo "value={$email}"?>>
        </p>
        <p>
            <label for="">Password</label>
            <input type="password" name="password">
        </p>
        <p>
            <label for="">&nbsp;</label>
            <button type="submit" name="submit"  >Save</button>
        </p>
        <!-- <p>
            <label for="">&nbsp;</label>
            <button type="reset" name="reset">Clear</button>
        </p> -->



       </form>

    </main>
    
</body>
</html> 