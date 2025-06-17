
<!DOCTYPE html>
<html>
<head>
    <title>Product Search</title>
</head>
<body>

    <h1>Product Search</h1>

    <form method="GET" action="">
        <input type="text" name="search" placeholder="Enter search term">
        <input type="submit" value="Search">
    </form>

    <?php
    if ($searchTerm) {
        if ($result->num_rows > 0) {
            echo "<h2>Search Results for: " . $searchTerm . "</h2>";
            echo "<ul>";
            while ($row = $result->fetch_assoc()) {
                echo "<li>" . $row['name'] . " (ID: " . $row['id'] . ")</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>No products found matching '" . $searchTerm . "'</p>";
        }
    }
    ?>
