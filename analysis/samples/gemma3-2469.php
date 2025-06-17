

<?php

/**
 * User Review Function
 *
 * This function allows you to create, read, update, and delete user reviews.
 * It includes basic validation and error handling.
 *
 * @param string $productId  The ID of the product being reviewed.
 * @param string $username   The username of the reviewer.
 * @param string $rating     The rating given by the reviewer (e.g., 1-5).
 * @param string $comment    The review comment.
 * @param string $db_connection  Database connection object.
 *
 * @return array An array containing the result of the operation (success/failure and message).
 *               Returns ['success' => true, 'message' => 'Review created successfully'] on success.
 *               Returns ['success' => false, 'message' => 'Error message'] on failure.
 */
function create_review(string $productId, string $username, string $rating, string $comment, $db_connection) {
  // Input Validation
  if (empty($productId) || empty($username) || empty($rating) || empty($comment)) {
    return ['success' => false, 'message' => 'All fields are required.'];
  }

  if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
    return ['success' => false, 'message' => 'Username must contain only letters, numbers, and underscores.'];
  }

  if (!preg_match('/^[1-5]$/', $rating)) {
    return ['success' => false, 'message' => 'Rating must be a number between 1 and 5.'];
  }

  // Sanitize Inputs - Best practice to prevent SQL injection
  $productId = $db_connection->real_escape_string($productId);
  $username = $db_connection->real_escape_string($username);
  $rating = $db_connection->real_escape_string($rating);
  $comment = $db_connection->real_escape_string($comment);

  // SQL Query
  $sql = "INSERT INTO reviews (product_id, username, rating, comment) VALUES ('$productId', '$username', '$rating', '$comment')";

  // Execute Query
  if ($db_connection->query($sql) === TRUE) {
    return ['success' => true, 'message' => 'Review created successfully.'];
  } else {
    return ['success' => false, 'message' => 'Error creating review: ' . $db_connection->error];
  }
}


/**
 * Example Usage (For demonstration purposes - replace with your database connection)
 */

//Simulated Database Connection (replace with your actual database connection)
$db_connection = new PDO('mysql:host=localhost;dbname=your_database_name', 'your_username', 'your_password');

// Example 1: Successful creation
$result1 = create_review('123', 'JohnDoe', '5', 'Great product!  Highly recommended.', $db_connection);
print_r($result1);

// Example 2:  Validation error
$result2 = create_review('', 'JaneSmith', '3', 'Good value.', $db_connection);
print_r($result2);

// Example 3:  Error (Simulated database error)
$db_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
try {
  $db_connection->query("INSERT INTO products (name) VALUES ('Invalid Product Name')");
} catch(PDOException $e) {
  echo "Error: " . $e->getMessage();
}
?>
