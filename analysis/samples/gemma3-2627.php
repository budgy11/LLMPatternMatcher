

<?php

/**
 * User Review Function
 *
 * This function handles adding, retrieving, and displaying user reviews.
 *
 * @param string $productId The ID of the product being reviewed.
 * @param string $userId The ID of the user submitting the review.
 * @param string $rating  The rating given by the user (e.g., 1-5).
 * @param string $comment The user's review comment.
 * @param array $dbConnection  Database connection object (optional - defaults to null)
 *
 * @return array An array containing:
 *               - 'success': True if the review was successfully added, false otherwise.
 *               - 'message': A message indicating the status of the review operation.
 *               - 'review': The newly created review object (if successful).
 */
function create_user_review(string $productId, string $userId, string $rating, string $comment, ?array $dbConnection = null) {
  // Input validation -  Essential for security and data integrity
  if (empty($productId) || empty($userId) || empty($rating) || empty($comment)) {
    return [
      'success' => false,
      'message' => 'All fields are required.',
      'review' => null
    ];
  }

  if (!is_numeric($rating) || (int)$rating < 1 || (int)$rating > 5) {
    return [
      'success' => false,
      'message' => 'Rating must be a number between 1 and 5.',
      'review' => null
    ];
  }


  // Data Sanitization - VERY IMPORTANT to prevent SQL injection and XSS attacks
  $productId = $dbConnection ? $dbConnection->real_escape_string($productId) : htmlspecialchars($productId);
  $userId = $dbConnection ? $dbConnection->real_escape_string($userId) : htmlspecialchars($userId);
  $rating = (int)$rating; // Cast to integer for database usage
  $comment = $dbConnection ? $dbConnection->real_escape_string($comment) : htmlspecialchars($comment);


  // Database interaction - Example using a simple database setup
  $review = [
    'product_id' => $productId,
    'user_id' => $userId,
    'rating' => $rating,
    'comment' => $comment,
    'created_at' => date('Y-m-d H:i:s') // Timestamp for record creation
  ];

  //  Replace this with your actual database query
  $result = mysqli_query($dbConnection, "INSERT INTO reviews (product_id, user_id, rating, comment, created_at) VALUES ('$productId', '$userId', '$rating', '$comment', '$review[created_at]')");

  if (mysqli_query($dbConnection, "INSERT INTO reviews (product_id, user_id, rating, comment, created_at) VALUES ('$productId', '$userId', '$rating', '$comment', '$review[created_at]')")) {  // Example query, adapt to your schema
    return [
      'success' => true,
      'message' => 'Review added successfully.',
      'review' => $review
    ];
  } else {
    return [
      'success' => false,
      'message' => 'Error adding review: ' . mysqli_error($dbConnection),
      'review' => null
    ];
  }
}


// Example Usage (replace with your actual database connection)
// Create a dummy database connection for this example
$dbConnection = mysqli_connect("localhost", "username", "password", "database_name");

if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
}

// Example 1: Successful Review
$review_data = create_user_review("123", "user123", 4, "Great product!", $dbConnection);
print_r($review_data);

// Example 2:  Invalid Rating
$review_data = create_user_review("456", "user456", 6, "Awesome!", $dbConnection);
print_r($review_data);

// Example 3: Empty Fields
$review_data = create_user_review("", "user789", 3, "Okay.", $dbConnection);
print_r($review_data);


mysqli_close($dbConnection); // Close the database connection
?>
