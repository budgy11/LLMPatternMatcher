  <input type="submit" value="Search">
</form>


// Example using a prepared statement - replace with your database library's function
$search_term = $_POST['search_term'] ?? '';

$query = "SELECT * FROM " . $table_name . " WHERE ";

$conditions = [];
foreach ($search_columns as $column) {
  $conditions[] = "`" . $column . "`" . " LIKE ?";
}

$query = implode(" OR ", $conditions);

// Assuming $conn->prepare() and $conn->execute() are your database library functions
$stmt = $conn->prepare($query);

if ($stmt) {
    $params = [];
    foreach ($search_columns as $column) {
        $params[] = "%" . $search_term . "%";
    }
    $stmt->execute($params);
} else {
    // Handle error if prepare() fails
    echo "Error preparing query.";
}

$results = $stmt->get_result();


<!DOCTYPE html>
<html>
<head>
  <title>Search Bar Example</title>
</head>
<body>

  <h1>Search Example</h1>

  <form method="GET" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
