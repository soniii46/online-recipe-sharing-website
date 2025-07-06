<?php
  include('../includes/dbconnection.php');
?>

<?php 

$Permalink = $_GET['Permalink'];
$query = "DELETE from product WHERE Permalink='$Permalink'";
$data = mysqli_query($conn, $query);
    if ($data){
        echo "<script>
        if (confirm('Do you want to delete it?')) {
            alert('Deleted successfully!!!');
            window.location.href = 'viewProduct1.php';
        }
      </script>";
        
    }

    else {
        echo "<script> alert('Error!!!')</script>";

    }
?>