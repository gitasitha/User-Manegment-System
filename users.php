<?php session_start();?>
<?php require_once('inc/connection.php'); ?>
<?php require_once('inc/functions.php'); ?>
<?php if(!isset($_SESSION['userId'])){
    header('Location:index.php');
    }
    
    $user_list='';
    $search='';
    //getting the list of ussers
    if(isset($_GET['search'])){
        $search=mysqli_real_escape_string($connection,$_GET['search']);
        $qurey="SELECT * FROM user WHERE(first_name LIKE '%{$search}%' OR last_name LIKE '%{$search}%' OR email LIKE '%{$search}%') AND is_deleted=0 ORDER BY first_name";
    }else{
        
        $qurey="SELECT * FROM user WHERE is_deleted=0 ORDER BY first_name";
    }
    $users=mysqli_query($connection,$qurey);

    if($users){

        while($user=mysqli_fetch_assoc($users)){
            $user_list.= "<tr>";
            $user_list.="<td>{$user['first_name']}</td>";
            $user_list.="<td>{$user['last_name']}</td>";
            $user_list.="<td>{$user['last_login']}</td>";
            $user_list.="<td><a href=\"modify-user.php?user_id={$user['id']}\">Edit</td>";
            $user_list .= "<td><a href=\"delete-user.php?user_id={$user['id']}\" 
						onclick=\"return confirm('Are you sure is delete {$user['first_name']} {$user['last_name']}?');\">Delete</a></td>";
            $user_list.= "</tr>";

        }
    }else{
        echo"Database aquery error";
    }

    
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body>      
    <header>
        <div class="appname">User Manegment System</div>
        <div class="loggedin"> Welcome <?php echo $_SESSION['first_name']." ".$_SESSION['last_name']." ";?><a href="logout.php">LogOut</a></div>
    </header>
    <main>
        <h1>Users <span><a href="add-user.php">+ Add New</a> | <a href="users.php">Refresh</a></span></h1>

        <p>
            <div class="search">
                <form action="users.php" method="get"> 
                    <input type="text" name="search" placeholder="Type First Name,Last Name or Email and Press Enter" value="<?php echo $search?>" autofocus>
                </form>
           
        </div>
    </p>

        <table class="masterlist">
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Last Login</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
            <?php echo $user_list; ?>
        </table>
    </main>
    
</body>
</html> 