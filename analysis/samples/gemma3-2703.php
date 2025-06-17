

<?php

/**
 * User Review Function
 *
 * This function allows you to store and display user reviews for a product or item.
 *
 * @param string $product_id The unique identifier for the product being reviewed.
 * @param string $user_name The name of the user submitting the review.
 * @param string $review_text The content of the review.
 * @param int $rating The rating given by the user (e.g., 1-5).
 * @param string $db_connection A valid database connection object.
 *
 * @return array An array containing:
 *   - 'success': True if the review was successfully saved, false otherwise.
 *   - 'message': A message indicating the result of the operation (e.g., 'Review saved', 'Error saving review').
 *   - 'review_id': The ID of the newly created review (if successful).
 */
function save_user_review(string $product_id, string $user_name, string $review_text, int $rating, object $db_connection) {
  // Validate inputs (basic example - enhance for production)
  if (empty($product_id) || empty($user_name) || empty($review_text) || $rating < 1 || $rating > 5) {
    return [
      'success' => false,
      'message' => 'Invalid input. Please check your review details.',
    ];
  }

  // Prepare SQL statement (use prepared statements for security!)
  $sql = "INSERT INTO reviews (product_id, user_name, review_text, rating) 
          VALUES (?, ?, ?, ?)";

  // Use prepared statement to prevent SQL injection
  $stmt = $db_connection->prepare($sql);
  $stmt->bind_param("ssis", $product_id, $user_name, $review_text, $rating);  //Correctly using string type
  $result = $stmt->execute();

  // Check if the query executed successfully
  if ($result) {
    // Get the last inserted ID
    $review_id = $db_connection->insert_id;

    return [
      'success' => true,
      'message' => 'Review saved successfully!',
      'review_id' => $review_id,
    ];
  } else {
    // Handle database errors
    $error_info = $stmt->error;
    return [
      'success' => false,
      'message' => 'Error saving review: ' . $error_info,
    ];
  }

  // Close the statement (important!)
  $stmt->close();
}



/**
 * Display User Reviews
 *
 * Retrieves and displays all user reviews for a given product.
 *
 * @param string $product_id The ID of the product.
 * @param object $db_connection A valid database connection object.
 *
 * @return array An array of review objects, or an empty array if no reviews found.
 */
function get_product_reviews(string $product_id, object $db_connection) {
    $sql = "SELECT * FROM reviews WHERE product_id = ?";
    $stmt = $db_connection->prepare($sql);
    $stmt->bind_param("s", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $reviews = [];
    while ($row = $result->fetch_assoc()) {
        $reviews[] = $row;
    }

    $stmt->close();
    return $reviews;
}


// Example Usage (assuming you have a database connection $conn)

//Create a dummy DB connection (replace with your real connection)
class DummyDBConnection {}
$conn = new DummyDBConnection();  //This is a placeholder
$conn->query("CREATE TABLE IF NOT EXISTS reviews (id INT AUTO_INCREMENT PRIMARY KEY, product_id VARCHAR(255) NOT NULL, user_name VARCHAR(255) NOT NULL, review_text TEXT, rating INT)");

// Save a review
$review_result = save_user_review('product123', 'John Doe', 'Great product!', 5, $conn);

if ($review_result['success']) {
  echo "Review saved! Review ID: " . $review_result['review_id'] . "<br>";
} else {
  echo "Error saving review: " . $review_result['message'] . "<br>";
}

//Get all reviews for product123
$reviews = get_product_reviews('product123', $conn);

echo "<h3>Reviews for product123:</h3>";
if (empty($reviews)) {
    echo "<p>No reviews found.</p>";
} else {
    echo "<ul>";
    foreach ($reviews as $review) {
        echo "<li><strong>User:</strong> " . $review['user_name'] . "<br>";
        echo "<strong>Rating:</strong> " . $review['rating'] . "<br>";
        echo "<strong>Review:</strong> " . $review['review_text'] . "</li>";
    }
    echo "</ul>";
}

// Clean up (important for development - remove in production)
$conn->query("DROP TABLE IF EXISTS reviews");

?>
