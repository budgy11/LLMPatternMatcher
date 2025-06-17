

<?php

/**
 * User Review Function
 *
 * This function allows you to store and display user reviews for a product.
 *
 * @param string $product_id The ID of the product.
 * @param string $user_name The name of the user submitting the review.
 * @param string $rating  The user's rating (e.g., 1-5).
 * @param string $comment The user's review comment.
 * @param array $db_connection  A connection object to your database (e.g., MySQLi).
 *
 * @return array An array containing the success status and any error messages.
 */
function saveUserReview(string $product_id, string $user_name, string $rating, string $comment, array $db_connection) {
  // Validate input (basic example - expand for more robust validation)
  if (empty($product_id) || empty($user_name) || empty($rating) || empty($comment)) {
    return ['success' => false, 'message' => 'All fields are required.'];
  }

  if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
    return ['success' => false, 'message' => 'Rating must be a number between 1 and 5.'];
  }


  // Sanitize input to prevent SQL injection (VERY IMPORTANT!)
  $product_id = $db_connection->real_escape_string($product_id);
  $user_name = $db_connection->real_escape_string($user_name);
  $rating = (int)$rating; //Convert to integer
  $comment = $db_connection->real_escape_string($comment);

  // SQL Query
  $sql = "INSERT INTO reviews (product_id, user_name, rating, comment) VALUES ('$product_id', '$user_name', '$rating', '$comment')";

  if ($db_connection->query($sql) === TRUE) {
    return ['success' => true, 'message' => 'Review submitted successfully!'];
  } else {
    return ['success' => false, 'message' => 'Error submitting review: ' . $db_connection->error];
  }
}


/**
 * Function to retrieve all reviews for a product.
 *
 * @param string $product_id The ID of the product.
 * @param array $db_connection A connection object to your database.
 *
 * @return array An array of reviews.  Returns an empty array if no reviews.
 */
function getProductReviews(string $product_id, array $db_connection) {
  $sql = "SELECT * FROM reviews WHERE product_id = '$product_id'";
  $result = $db_connection->query($sql);

  if ($result->num_rows > 0) {
    $reviews = [];
    while($row = $result->fetch_assoc()) {
      $reviews[] = $row;
    }
    return $reviews;
  } else {
    return [];
  }
}


// Example Usage (Illustrative - Adapt to your database setup)

//  Dummy Database Connection (Replace with your actual connection)
$db_connection = new mysqli("localhost", "username", "password", "database_name");
if ($db_connection->connect_error) {
  die("Connection failed: " . $db_connection->connect_error);
}

// Example 1: Save a review
$review_result = saveUserReview("123", "John Doe", 4, "Great product!  Highly recommend.", $db_connection);
if ($review_result['success']) {
  echo "Review saved successfully: " . $review_result['message'] . "
";
} else {
  echo "Error saving review: " . $review_result['message'] . "
";
}


// Example 2: Get reviews for product ID "123"
$reviews = getProductReviews("123", $db_connection);

if (!empty($reviews)) {
    echo "Reviews for product 123:
";
    foreach ($reviews as $review) {
        echo "User: " . $review['user_name'] . ", Rating: " . $review['rating'] . ", Comment: " . $review['comment'] . "
";
    }
} else {
    echo "No reviews found for product 123.
";
}


// Close the database connection (important!)
$db_connection->close();
?>
