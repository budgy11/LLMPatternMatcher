

<?php

/**
 * User Review Function
 *
 * This function allows you to add, retrieve, and display user reviews for a product.
 *
 * @param string $product_id  The ID of the product for which reviews are being added/retrieved.
 * @param string $username    The username of the user submitting the review.
 * @param string $rating      The rating given by the user (e.g., 1-5).
 * @param string $comment     The review comment.
 * @param string $db_connection  A database connection object.  Crucial for interacting with the database.
 *
 * @return array  An array containing the review data on success, or an error message on failure.
 */
function add_user_review(string $product_id, string $username, string $rating, string $comment, object $db_connection) {
  // Validate input - essential for security and data integrity
  if (empty($product_id) || empty($username) || empty($rating) || empty($comment)) {
    return ['error' => 'All fields are required.'];
  }

  if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
    return ['error' => 'Invalid username.  Only alphanumeric characters and underscores are allowed.'];
  }

  if (!preg_match('/^[1-5]{1,2}$/', $rating)) {
    return ['error' => 'Rating must be a number between 1 and 5.'];
  }


  // Prepare the SQL query - crucial for preventing SQL injection
  $sql = "INSERT INTO reviews (product_id, username, rating, comment) 
          VALUES (?, ?, ?, ?)";

  // Use prepared statements - **VERY IMPORTANT** for security
  $stmt = $db_connection->prepare($sql);

  if ($stmt === false) {
    return ['error' => 'Failed to prepare statement: ' . $db_connection->error];
  }

  // Bind the parameters
  $stmt->bind_param("s", $product_id, $username, $rating);

  // Execute the query
  if ($stmt->execute() === false) {
    return ['error' => 'Query failed: ' . $stmt->error];
  }

  // Clean up
  $stmt->close();

  return ['success' => 'Review added successfully!'];
}


/**
 * Retrieves all reviews for a product.
 *
 * @param string $product_id The ID of the product.
 * @param object $db_connection A database connection object.
 * @return array  An array of review data, or an error message if no reviews are found or an error occurs.
 */
function get_reviews_for_product(string $product_id, object $db_connection) {
  $sql = "SELECT * FROM reviews WHERE product_id = ? ORDER BY rating DESC";
  $stmt = $db_connection->prepare($sql);

  if ($stmt === false) {
    return ['error' => 'Failed to prepare statement: ' . $db_connection->error];
  }

  $stmt->bind_param("s", $product_id);

  $result = $stmt->execute();

  if ($result === false) {
    return ['error' => 'Query failed: ' . $stmt->error];
  }

  $reviews = [];
  $stmt->bind_result($id, $username, $rating, $comment); // Bind result variables

  while ($stmt->fetch()) {
    $review = [
      'id' => $id,
      'username' => $username,
      'rating' => $rating,
      'comment' => $comment
    ];
    $reviews[] = $review;
  }

  $stmt->close();
  return $reviews;
}


// Example Usage (Illustrative - replace with your database connection)
//  Important:  This is just a demonstration.  You'll need to set up your database connection properly.

/*
// Assuming you have a database connection object called $conn

$conn = new mysqli("localhost", "username", "password", "database_name");

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Add a review
$review_result = add_user_review("123", "JohnDoe", "5", "Excellent product!", $conn);
print_r($review_result);

// Get reviews for product "123"
$reviews = get_reviews_for_product("123", $conn);
print_r($reviews);
*/


?>
