<?php
include('includes/dbconnection.php');

if (isset($_GET['token'])) {
    $token = mysqli_real_escape_string($conn, $_GET['token']);

    $sql = "SELECT * FROM userinfo WHERE verification_token='$token'";
    $res = mysqli_query($conn, $sql);

    if ($res && mysqli_num_rows($res) > 0) {
        $user = mysqli_fetch_assoc($res);

        // Mark the user as verified
        $sql = "UPDATE userinfo SET is_verified=1, verification_token=NULL WHERE verification_token='$token'";
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Email verified successfully!');</script>";
            echo "<script>window.location.href='login.php';</script>";
        } else {
            echo "<script>alert('Verification failed. Please try again.');</script>";
        }
    } else {
        echo "<script>alert('Invalid or expired token.');</script>";
    }
} else {
    echo "<script>alert('No verification token provided.');</script>";
}

mysqli_close($conn);
?>
