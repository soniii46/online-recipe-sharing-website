<?php
    session_start();
    if (!isset($_SESSION['uname']))
        {
            header("Location: ../login.php");
            exit();
        }      
?>

<?php 
    include('../includes/dbconnection.php');
    $checkID = $_SESSION['uname'];
?>


<?php 

    if(isset($_POST['submit'])) {
        $Username = $row['Username'];
        $User_email =$row['User_email'];

        $query = "UPDATE userinfo SET Username = '$Username', User_email = '$User_email' WHERE Username = '$checkID'";

        if (mysqli_query($conn, $query)) {
            echo '<script language="javascript">'.'alert("Peronal details updated successfully")'.'</script>';
            header('Location: user-dashboard.php');
        } else {
            echo "Error updating record: " . mysqli_error($conn);
        }

        
        
    }

    /* referance from www.w3schools.com*/
?>