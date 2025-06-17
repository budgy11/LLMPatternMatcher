    <input type="text" name="search_term" placeholder="Enter search term">
    <button type="submit">Search</button>
  </form>

</body>
</html>


function search($searchTerm) {
  // This is just a placeholder. Replace with your actual search logic.
  // Example:  Search through an array of strings
  $myArray = array(
    "apple",
    "banana",
    "orange",
    "grapefruit",
    "apple pie"
  );

  $results = array();
  foreach ($myArray as $item) {
    if (stripos($item, $searchTerm) !== false) { // Case-insensitive search
      $results[] = $item;
    }
  }
  return $results;
}


function search($searchTerm) {
  $conn = mysqli_connect("your_db_host", "your_db_user", "your_db_password", "your_db_name");
  if (mysqli_connect_errno($conn)) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    return false;
  }

  $sql = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";  // Using LIKE for partial matches

  $result = mysqli_query($conn, $sql);

  if ($result) {
    $results = array();
    while ($row = mysqli_fetch_assoc($result)) {
      $results[] = $row['name'];  // Or any other relevant field
    }
    mysqli_free_result($result); // Free the result set
    return $results;
  } else {
    return false;
  }

  mysqli_close($conn);
}


function search($searchTerm) {
  $conn = mysqli_connect("your_db_host", "your_db_user", "your_db_password", "your_db_name");

  if (mysqli_connect_errno($conn)) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    return false;
  }

  $stmt = mysqli_prepare($conn, "SELECT * FROM products WHERE name LIKE ?"); // Prepare the statement
  if ($stmt) {
    mysqli_stmt_bind_param($stmt, "s", $searchTerm);  // Bind the parameter (type: string)
    mysqli_stmt_execute($stmt);                // Execute the statement

    $result = mysqli_stmt_get_result($stmt);
    $results = array();
    while ($row = mysqli_fetch_assoc($result)) {
      $results[] = $row['name'];
    }
    mysqli_free_result($result);
    mysqli_stmt_close($stmt);
  } else {
    echo "Error preparing statement: " . mysqli_error($conn);
  }
  mysqli_close($conn);
  return $results;
}


<?php

// Assume you have a database connection established.
// This example uses a simplified database connection for demonstration.
// Replace this with your actual database connection code.
// For example:
// $db = new mysqli("localhost", "username", "password", "database_name");
// if ($db->connect_error) {
//     die("Connection failed: " . $db->connect_error);
// }

// Sample Data (for demonstration purposes only - replace with your database query)
$data = [
    ["id" => 1, "name" => "Apple"],
    ["id" => 2, "name" => "Banana"],
    ["id" => 3, "name" => "Orange"],
    ["id" => 4, "name" => "Grape"],
];

// Get the search term from the request
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term (important for security)
$searchTerm = htmlspecialchars(trim($searchTerm));


// Perform the search
$searchResults = [];
foreach ($data as $item) {
    // Case-insensitive search
    if (stripos($item['name'], $searchTerm) !== false) {
        $searchResults[] = $item;
    }
}

?>
