<?php session_start();?>
<?php require_once('inc/connection.php'); ?>
<?php require_once('inc/functions.php'); ?>

<?php
// checking if a user in logged in
if(!isset($_SESSION['userId'])){
    header('Location:index.php');
    }

if(isset($_GET["user_id"])){

    //Getting the user information
    $user_id= mysqli_real_escape_string($connection,$_GET["user_id"]);

    if($user_id == $_SESSION['userId']){
        //Should not delete current user
        header('Location: users.php?err = Cannot _delere_current_user');
    }else{
        $query="UPDATE user SET is_deleted=1 WHERE id={$user_id} LIMIT 1";
        $result= mysqli_query($connection,$query);
        if($result){
            header("Location: users.php?msg=user_deleted");
        }else{
            header("Location: users.php?msg=delete_faild");
        }
    }
}else{
    header('Location: users.php');
}

