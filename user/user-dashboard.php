<?php 
session_start();

if (!isset($_SESSION['uname'])) {
    header("Location: ../login.php");
    exit();
}
?>
<?php
    include('../includes/dbconnection.php');
    $checkID = $_SESSION['uname'];
    /*$UserID = $_GET['UserID'];*/
    $sql = "SELECT * FROM userinfo WHERE Username = '$checkID' ";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_assoc($result)){
            $Username = $row['Username'];
            $User_email =$row['User_email'];
        }
    }

// // Fetch user details (optional)
// $userName = $_SESSION['uname'];
// $sql = "SELECT * FROM userinfo WHERE Username = ?";
// $stmt = $conn->prepare($sql);
// $stmt->bind_param("i", $userName);
// $stmt->execute();
// $user = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="../CSS/style.css">
    <link rel="stylesheet" href="../CSS/user.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>

<?php include('uheader.php'); ?>
<div class = grid>

        <div class="subMenu">
                    <ul>
                        <li><a href="user-dashboard.php">Dashboard</a></li>
                        <li><a href="bookmarked-recipes.php">Bookmarked recipes</a></li>
                        <li><a href="view-order.php">Ordered History</a></li>
                       
                    </ul>
        </div>
        <main>
            <div class = "accountDetails">
                <div class = topic>
                    Account details
                    <hr>
                </div>
                <form name="updateAccountDetails" onsubmit = "return validateRegisterDetails()" action="updateUser.php" method="POST" enctype="multipart/form-data">

                    <label for="Username"> Username </label> <br>
                    <input type="text" name="Username" value="<?php echo $Username; ?>" required> 
                    <br> 

                    <label for="User_email"> Email </label> <br>
                    <input type="text" name="User_email" value="<?php echo $User_email; ?>" required>
                    <br>

                    <!-- <input id = "button" type="submit" name="submit" class="submit_button" value="Update"> -->
                </form>
                <!-- <button name="cancel" class="btn" onclick="return confirm('Do you want to cancel it?');"><a href="user-dashboard.php">Cancel</a></button>
            -->
            </div> 
        
        </main>  
    </div>
</div>
<?php include('../includes/footer.php'); ?>
</body>
</html>
