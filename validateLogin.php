<?php
session_start();

// If user is already logged in, redirect them to the home page
if (isset($_SESSION['uname'])) {
    header('Location: index.php');
    exit;
}

include('includes/dbconnection.php');

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = $_POST['uname'];
    $password = $_POST['password'];

    // Ensure fields are not empty
    if (!empty($username) && !empty($password)) {
        // Prepare a SQL query with placeholders to prevent SQL injection
        $query = "SELECT * FROM userinfo WHERE Username = ? LIMIT 1";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $username); // Bind the username as a string
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if a user was found
        if ($result && $result->num_rows == 1) {
            $user_data = $result->fetch_assoc();
            
            // Verify password
            if (password_verify($password, $user_data['User_password'])) {
                $_SESSION['uname'] = $username;
                $_SESSION['logged_in'] = true;
                
                // Redirect based on user role (admin or regular user)
                if ($username === "admin") {
                    header("Location: admin/dash.php");
                } else {
                    header("Location: index.php");
                }
                exit;
            } else {
                echo "<script>
                alert('Incorrect Username or Password');
                window.location.href = 'login.php';
                </script>";
                exit;
            }
        } else {
            echo "<script>
            alert('Incorrect Username or Password');
            window.location.href = 'login.php';
            </script>";
            exit;
        }
    } else {
        echo "<script>
        alert('Username or Password cannot be empty');
        window.location.href = 'login.php';
        </script>";
        exit;
    }
}
?>
