

<?php
// Assuming you have a database connection established elsewhere (e.g., in a configuration file)

// Function to search the database
function searchDatabase($searchTerm) {
    // Replace with your database connection details
    $host = "localhost";
    $username = "your_username";
    $password = "your_password";
    $database = "your_database";

    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $query = "SELECT * FROM your_table WHERE title LIKE '%" . $searchTerm . "%'";  // Use LIKE for partial matches
    $result = $conn->query($query);

    $rows = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $rows[] = $row['title']; // Assuming 'title' is the column you want to display
        }
    }

    $conn->close();
    return $rows;
}


//  (The rest of the code from the first example - displaySearchForm,  etc.  should be here too)

if (isset($_GET['q'])) {
  $searchTerm = $_GET['q'];

  $results = searchDatabase($searchTerm);
  echo "<p>You searched for: " . $searchTerm . "</p>";
  echo "<ul>";
  foreach ($results as $result) {
    echo "<li>" . $result . "</li>";
  }
  echo "</ul>";
}
?>
