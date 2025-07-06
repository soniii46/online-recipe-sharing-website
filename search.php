<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
    #result {
    background: #fff;
    border: 1px solid #ddd;
    max-height: 300px;
    overflow-y: auto;
    width: 300px;
    position:relative;
    left:80%
    z-index: 1000;
    display: none;
}
.search-item {
    padding: 10px;
    border-bottom: 1px solid #ddd;
    cursor: pointer;
}

.search-item:hover {
    background: #f7f7f7;
}

.search-item img {
    vertical-align: middle;
}

.search-item a {
    text-decoration: none;
    color: #333;
    display: flex;
    align-items: center;
}
</style>
</head>
<body>
<?php
include('includes/dbconnection.php');

if (isset($_POST['search'])) {
    $search = mysqli_real_escape_string($conn, $_POST['search']);

    $query = "SELECT * FROM product WHERE Title LIKE '%$search%' OR R_description LIKE '%$search%' LIMIT 5";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<div class="search-item">';
            echo '<a href="user/view-recipe.php?Permalink=' . $row['Permalink'] . '">';
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


