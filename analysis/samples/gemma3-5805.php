  <button type="submit">Search</button>
</form>


<?php

// Assuming you have a database connection established (e.g., mysqli)
// $db = new mysqli("localhost", "username", "password", "database_name");
// if ($db->connect_error) {
//   die("Connection failed: " . $db->connect_error);
// }


// Example: Assuming you're searching in a table called "products" with a column "name"
// and you've received the search term from a form input named "search_term"

// Get the search term from the form (e.g., a form field named "search_term")
$search_term = isset($_POST['search_term']) ? $_POST['search_term'] : ''; // Check if the form has been submitted
$search_term = htmlspecialchars(trim($search_term)); // Sanitize the input - essential!


//  Escape the search term for SQL queries.  This prevents SQL injection vulnerabilities.
//  This example uses mysqli_real_escape_string, but use prepared statements if possible.
//  Prepared statements are the safest option.
//  Use the appropriate escaping method for your database system (e.g., PDO, etc.)
// $search_term = $db->real_escape_string($search_term);


// Build the SQL query with the search term
$sql = "SELECT * FROM products WHERE name LIKE '%" . $search_term . "%'";

// Execute the query
// $result = $db->query($sql);  // Uncomment when using mysqli
//  or
//  if(version_compare($db->version, "5.0.3", '>=')) {
//    $result = $db->query($sql);
//  } else {
//    $result = $db->query($sql);
//  }



// Display the search bar and results

echo "<form method='post' action=''>";  //  'action' should point to the same script
echo "Search: <input type='text' name='search_term' value='" . $search_term . "'><input type='submit' value='Search'>";
echo "</form>";

echo "<br>";

if ($result) {
    if ($result->num_rows > 0) {
        echo "<ul>";
        while ($row = $result->fetch_assoc()) {  // Or $row = $result->fetch_object() if you prefer objects
            echo "<li>" . $row["name"] . " - " . $row["description"] . "</li>"; // Replace with your actual column names
        }
        echo "</ul>";
    } else {
        echo "No results found.";
    }
} else {
    echo "Error executing query.";
}


// Close the database connection (important!)
// $db->close();
?>
