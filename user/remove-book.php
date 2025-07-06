<?php
    include('../includes/dbconnection.php');
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
        $id = intval($_POST['id']);
        $query = "DELETE FROM bookmark WHERE id = $id";
        $result = mysqli_query($conn, $query);

        if ($result) {
            echo 'success';
        } else {
            echo 'error';
        }
    } else {
        echo 'invalid';
    }
?>
