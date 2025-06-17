
<!DOCTYPE html>
<html>
<head>
    <title>Search Products</title>
</head>
<body>

    <h1>Search Products</h1>

    <form method="post" action="">
        <input type="text" name="searchTerm" placeholder="Enter search term">
        <button type="submit">Search</button>
    </form>

    <?php
    if ($result->num_rows > 0) {
        echo "<ul>";
        while ($row = $result->fetch_assoc()) {
            echo "<li>" . $row['name'] . " - " . $row['description'] . "</li>"; // Assuming you have a 'description' column
        }
        echo "</ul>";
    } else {
        echo "<p>No products found.</p>";
    }
    ?>
