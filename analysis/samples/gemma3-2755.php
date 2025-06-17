

<?php

/**
 * User Review Function
 *
 * This function allows users to submit and display user reviews for a product or item.
 * It handles input validation, sanitization, and database interaction.
 *
 * @param string $product_id The ID of the product to review.
 * @param string $name       The name of the user submitting the review.
 * @param string $comment   The user's review comment.
 * @param string $db_connection The established database connection object.
 *
 * @return array An array containing:
 *   - 'success': True if the review was successfully submitted, false otherwise.
 *   - 'message':  A message indicating the outcome of the submission.
 *   - 'review_id': The ID of the newly created review (if successful).
 */
function submit_review(string $product_id, string $name, string $comment, object $db_connection) {
  // Input validation (basic - extend for more robust validation)
  if (empty($product_id) || empty($name) || empty($comment)) {
    return [
      'success' => false,
      'message' => 'All fields are required.',
    ];
  }

  // Sanitize input (important to prevent SQL injection)
  $name = filter_var($name, FILTER_SANITIZE_STRING);
  $comment = filter_var($comment, FILTER_SANITIZE_STRING);


  // Prepare SQL query (using prepared statements for security)
  $sql = "INSERT INTO reviews (product_id, user_name, review_text) VALUES (?, ?, ?)";
  $stmt = $db_connection->prepare($sql);

  if ($stmt === false) {
    // Handle database error
    return [
      'success' => false,
      'message' => 'Database error: ' . print_r($db_connection->error, true),
    ];
  }

  // Bind parameters
  $stmt->bind_param("ss", $name, $comment);

  // Execute query
  if (!$stmt->execute()) {
    // Handle database error
    return [
      'success' => false,
      'message' => 'Database error: ' . print_r($stmt->error, true),
    ];
  }

  // Get the ID of the newly inserted review
  $review_id = $db_connection->insert_id;

  // Close the statement
  $stmt->close();

  return [
    'success' => true,
    'message' => 'Review submitted successfully!',
    'review_id' => $review_id,
  ];
}


/**
 * Function to retrieve reviews for a product
 *
 * @param int $product_id The ID of the product.
 * @param object $db_connection The established database connection.
 *
 * @return array An array containing:
 *   - 'reviews': An array of review objects (or an empty array if no reviews).
 *   - 'success': True if reviews were retrieved successfully, false otherwise.
 *   - 'message': A message indicating the outcome.
 */
function get_reviews(int $product_id, object $db_connection) {
    $sql = "SELECT * FROM reviews WHERE product_id = ?";
    $stmt = $db_connection->prepare($sql);

    if ($stmt === false) {
        return [
            'reviews' => [],
            'success' => false,
            'message' => 'Database error: ' . print_r($db_connection->error, true),
        ];
    }

    $stmt->bind_param("i", $product_id); // 'i' indicates an integer parameter

    $stmt->execute();

    $reviews = [];
    $result = $stmt->get_result();

    if ($result === false) {
      return [
          'reviews' => [],
          'success' => false,
          'message' => 'Error retrieving reviews: ' . print_r($stmt->error, true),
      ];
    }
    
    while ($row = $result->fetch_assoc()) {
        $reviews[] = $row;
    }

    $stmt->close();

    return [
        'reviews' => $reviews,
        'success' => true,
    ];
}



// Example Usage (Simulated Database Connection - Replace with your actual connection)
// This is just for demonstration.  You'll need to set up your database connection.

// Mock Database Connection Object (Replace with your actual database connection)
$db_connection = new stdClass();
$db_connection->connect = function() {
    // Simulate a successful connection.  In a real application, this would handle the connection.
    return true;
};
$db_connection->query = function($sql) {
    // Simulate a query execution.
    return true;
};
$db_connection->insert_id = 123; // Simulate a review ID



// Submit a review
$review_result = submit_review(1, "John Doe", "This is a great product!", $db_connection);
print_r($review_result);


// Retrieve reviews for product 1
$reviews_result = get_reviews(1, $db_connection);
print_r($reviews_result);
?>
