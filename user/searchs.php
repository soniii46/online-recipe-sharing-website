<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    
</head>
<body>
<?php
include('../includes/dbconnection.php');

if (isset($_POST['search'])) {
    $search = mysqli_real_escape_string($conn, $_POST['search']);

    $query = "SELECT * FROM product WHERE Title LIKE '%$search%' OR R_description LIKE '%$search%' LIMIT 5";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<div class="search-item">';
            echo '<a href="view-recipe.php?Permalink=' . $row['Permalink'] . '">';
            // echo '<img src="../images/' . $row['Recipe_image'] . '" alt="Recipe Image" style="width: 50px; height: 50px; margin-right: 10px;">';
            echo '<span>' . $row['Title'] . '</span>';
            echo '</a>';
            echo '</div>';
        }
    } else {
        echo '<p>No recipes found.</p>';
    }
}
?>
</body>
</html>


