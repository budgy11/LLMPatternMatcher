
</body>
</html>


<?php
// ... (Database connection as before) ...

// Get the search term from the search bar
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$searchTerm = htmlspecialchars($searchTerm);

if ($searchTerm) {
    // Prepared statement -  This is the key for security
    $stmt = $conn->prepare("SELECT * FROM your_table WHERE title LIKE %s", "%");
    $stmt->bind_param("s", $searchTerm); // "s" indicates a string parameter
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div>";
            echo "Title: " . htmlspecialchars($row["title"]) . "<br>";
            echo "Description: " . htmlspecialchars($row["description"]) . "<br>";
            echo "</div>";
        }
    } else {
        echo "<p>No results found.</p>";
    }

    $stmt->close(); // Close the statement
}
?>
