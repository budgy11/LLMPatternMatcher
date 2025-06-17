

<?php

// Assuming you have a database connection established (e.g., $db)

// Function to handle the search query
function search($search_term, $table_name, $searchable_columns)
{
  global $db; // Access the global database connection

  $query = "SELECT * FROM " . $table_name . " WHERE ";

  $conditions = [];
  $params = [];

  foreach ($searchable_columns as $column)
  {
    // Sanitize the search term (important for security!)
    $search_term = $db->real_escape_string($search_term);

    // Add the condition to the query
    $conditions[] = "`" . $column . "`" . " LIKE '%" . $search_term . "%'";
    $params[] = $search_term; // Store the search term for parameter binding
  }

  $query .= implode(" OR ", $conditions);

  // Execute the query
  $result = $db->query($query);

  if ($result->num_rows > 0)
  {
    // Return the results as an array
    $rows = [];
    while ($row = $result->fetch_assoc())
    {
      $rows[] = $row;
    }
    return $rows;
  }
  else
  {
    return []; // Return an empty array if no results are found
  }
}


// Example Usage:

// 1.  Define the table and searchable columns
$table_name = 'products';
$searchable_columns = ['name', 'description', 'category']; // Columns to search in


// 2. Get the search term from the user (e.g., from an input field)
$search_term = $_GET['search'] ?? ''; // Use $_GET or $_POST, depending on where you're getting the input
//  $_POST['search']  //  If you're using POST instead of GET


// 3.  Call the search function
$results = search($search_term, $table_name, $searchable_columns);

// 4. Display the results
if (count($results) > 0)
{
  echo "<h2>Search Results for: " . htmlspecialchars($search_term) . "</h2>"; // Sanitize output
  echo "<table border='1'>";
  echo "<thead><tr><th>" . $table_name . "</th></tr></thead>";
  echo "<tbody>";
  foreach ($results as $row)
  {
    echo "<tr>";
    foreach ($row as $key => $value)
    {
      echo "<td>" . htmlspecialchars($value) . "</td>"; // Sanitize output
    }
    echo "</tr>";
  }
  echo "</tbody>";
  echo "</table>";
}
else
{
  echo "<p>No results found for: " . htmlspecialchars($search_term) . "</p>"; // Sanitize output
}


?>
