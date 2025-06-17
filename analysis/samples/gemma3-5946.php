

function searchDatabase($search_term, $db_host, $db_name, $db_user, $db_password) {
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the statement
    $stmt = $conn->prepare("SELECT * FROM your_table_name WHERE your_column_name LIKE ?");
    $stmt->bind_param("s", '%' . $search_term . '%'); // 's' indicates a string parameter
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Output results
        echo "<form method='get' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>
              <input type='text' name='search' value='" . htmlspecialchars($search_term) . "' placeholder='Search...'>
              <button type='submit'>Search</button>
              </form>";
        echo "<br>";
        echo "<ul>";
        while ($row = $result->fetch_assoc()) {
            echo "<li>" . htmlspecialchars($row["your_column_name"]) . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No results found for '" . htmlspecialchars($search_term) . "'.</p>";
    }

    $stmt->close();
    $conn->close();
}


<?php

// Assuming you have a database connection established (e.g., mysqli)
// Example:
// $conn = new mysqli("localhost", "username", "password", "database_name");
// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }

// Set up the search bar form
?>
