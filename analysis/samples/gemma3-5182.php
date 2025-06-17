
</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $conn)

// Function to handle the search query
function search_data($conn, $search_term) {
  // Sanitize the input to prevent SQL injection
  $search_term = $conn->real_escape_string($search_term); 

  // Construct the SQL query
  $query = "SELECT * FROM your_table_name WHERE your_column_name LIKE '%" . $search_term . "%'";

  // Execute the query
  $result = $conn->query($query);

  // Prepare the results array
  $results = array();
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $results[] = $row;
    }
  }

  return $results;
}

// Get the search term from the form (assuming a form with an input field named 'search')
if (isset($_POST['search']) && !empty($_POST['search'])) {
  $search_term = $_POST['search'];

  // Call the search function
  $results = search_data($conn, $search_term);

  // Output the results (e.g., display in a table)
  echo "<table border='1'>";
  echo "<thead><tr><th>" . "Column 1 Header" . "</th><th>" . "Column 2 Header" . "</th></tr></thead>"; // Replace with your actual column names
  echo "<tbody>";
  foreach ($results as $row) {
    echo "<tr>";
    foreach ($row as $value) {
      echo "<td>" . $value . "</td>";
    }
    echo "</tr>";
  }
  echo "</tbody></table>";
}

?>
