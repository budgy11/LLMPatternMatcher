        <input type="text" name="search_term" placeholder="Enter search term">
        <button type="submit">Search</button>
    </form>

</body>
</html>


<?php

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the search term from the form
    $searchTerm = htmlspecialchars($_POST["search_term"]);

    // Sanitize the search term
    $searchTerm = trim($searchTerm);

    // Example:  Using prepared statements (replace with your database details)
    $db = new mysqli("localhost", "username", "password", "database_name");

    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }

    // Prepare the statement
    $sql = "SELECT * FROM products WHERE name LIKE ?";
    $stmt = $db->prepare($sql);

    if ($stmt) {
        $searchTerm = "%" . $searchTerm . "%"; // Add wildcards for LIKE
        $stmt->bind_param("s", $searchTerm); // "s" indicates a string parameter

        $result = $stmt->execute();

        if ($result) {
            // Fetch the results
            $rows = $stmt->fetch_all(MYSQLI_ASSOC);

            if ($rows) {
                echo "<ul>";
                foreach ($rows as $row) {
                    echo "<li>" . $row["name"] . " - " . $row["description"] . "</li>";
                }
                echo "</ul>";
            } else {
                echo "No results found for '" . $searchTerm . "'";
            }
        } else {
            echo "Error executing query: " . $db->error;
        }

        $stmt->close();
    } else {
        echo "Error preparing statement.";
    }


    $db->close();

}
?>
