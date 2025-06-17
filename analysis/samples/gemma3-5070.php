

<?php

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the search term from the form
    $searchTerm = htmlspecialchars($_POST["search_term"]);

    // Sanitize the search term (important for security)
    $searchTerm = trim($searchTerm);  // Remove leading/trailing whitespace

    //  You can add more validation and sanitization here if needed,
    //  depending on your specific requirements.  For example,
    //  you might want to limit the length of the search term.

    // Do something with the search term.  This is where you'd typically
    // connect to a database or perform a search on your data.
    // For this example, we'll just display it.

    // Example:  Assume you have a database connection established
    // $db = new mysqli("localhost", "username", "password", "database_name");

    // If the database connection is successful:
    // if ($db->connect_error) {
    //     die("Connection failed: " . $db->connect_error);
    // }

    // Example query (replace with your actual search logic)
    // $sql = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";
    // $result = $db->query($sql);

    // if ($result->num_rows > 0) {
    //     echo "<ul>";
    //     while($row = $result->fetch_assoc()) {
    //         echo "<li>" . $row["name"] . " - " . $row["description"] . "</li>";
    //     }
    //     echo "</ul>";
    // } else {
    //     echo "No results found for '" . $searchTerm . "'";
    // }

    // Close the database connection (very important!)
    // $db->close();

    echo "You searched for: " . $searchTerm;
}
?>
