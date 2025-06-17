
</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $conn)

// Get the search term from the form submission
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term to prevent SQL injection and XSS attacks
$searchTerm = htmlspecialchars(trim($searchTerm)); 

//  Option 1: Simple Search (Directly in the Database) - NOT RECOMMENDED for production
/*
if ($searchTerm) {
    $query = "SELECT * FROM your_table WHERE title LIKE '%" . $searchTerm . "%' OR description LIKE '%" . $searchTerm . "%'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        echo "<form method='get' style='margin-bottom: 10px;'>";
        echo "<input type='text' name='search' placeholder='Search...'>";
        echo "<button type='submit'>Search</button>";
        echo "</form>";

        echo "<ul>";
        while ($row = $result->fetch_assoc()) {
            echo "<li><a href='details.php?id=" . $row['id'] . "'>" . $row['title'] . "</a></li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No results found for '" . $searchTerm . "'</p>";
    }
}
*/


// Option 2:  More Robust Search with Prepared Statements (Recommended)
// This prevents SQL injection vulnerabilities

if ($searchTerm) {
    $query = "SELECT * FROM your_table WHERE title LIKE ? OR description LIKE ?";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $searchTermEscaped = "%" . $searchTerm . "%"; // Add wildcards for LIKE
        $stmt->bind_param("ss", $searchTermEscaped, $searchTermEscaped);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<form method='get' style='margin-bottom: 10px;'>";
            echo "<input type='text' name='search' placeholder='Search...'>";
            echo "<button type='submit'>Search</button>";
            echo "</form>";

            echo "<ul>";
            while ($row = $result->fetch_assoc()) {
                echo "<li><a href='details.php?id=" . $row['id'] . "'>" . $row['title'] . "</a></li>";
            }
            echo "</ul>";
        } else {
            echo "<p>No results found for '" . $searchTerm . "'</p>";
        }

        $stmt->close(); // Close the statement
    } else {
        echo "<p>Error preparing statement.</p>";
    }
} else {
    // If no search term is provided, display the form
    echo "<form method='get' style='margin-bottom: 10px;'>";
    echo "<input type='text' name='search' placeholder='Search...'>";
    echo "<button type='submit'>Search</button>";
    echo "</form>";
}
?>
