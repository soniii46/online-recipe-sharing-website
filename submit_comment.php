<?php
session_start();
include('includes/dbconnection.php');

if (isset($_POST['RecipeID'], $_POST['comment'], $_POST['rating']) && isset($_SESSION['uname'])) {
    $RecipeID = $_POST['RecipeID'];
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);
    $rating = (int)$_POST['rating'];
    $username = $_SESSION['uname'];
    $timestamp = date('Y-m-d H:i:s');

    // Get user ID
    $user_query = "SELECT UserID FROM userinfo WHERE Username = '$username'";
    $user_result = mysqli_query($conn, $user_query);
    $user = mysqli_fetch_assoc($user_result);
    $userID = $user['UserID'];

    // Insert comment and rating
    $comment_query = "INSERT INTO Comment (Username, RecipeID, Comment_desc, Comment_timestamp) 
                      VALUES ('$userID', '$RecipeID', '$comment', '$timestamp')";
    $rating_query = "INSERT INTO Rate (Username, RecipeID, Rating) 
                     VALUES ('$userID', '$RecipeID', '$rating')";

    if (mysqli_query($conn, $comment_query) && mysqli_query($conn, $rating_query)) {
        echo 'success';
    } else {
        echo 'error';
    }
} else {
    echo 'error';
}
?>
