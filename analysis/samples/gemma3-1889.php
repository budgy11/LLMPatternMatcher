

<?php

/**
 * User Review Function
 *
 * This function allows you to add, retrieve, and display user reviews for a product or service.
 *
 * @param string $productId The unique identifier of the product or service.
 * @param string $username The username of the user submitting the review.
 * @param string $rating  The rating given by the user (e.g., 1-5 stars).
 * @param string $comment The user's review comment.
 * @param array $dbConnection  An existing database connection object.
 * @return array|false  An array of reviews or false on error.
 */
function add_user_review(string $productId, string $username, string $rating, string $comment, array $dbConnection) {
  // Input Validation (Very Important!)
  if (empty($productId) || empty($username) || empty($rating) || empty($comment)) {
    return false; // Invalid input
  }

  if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
    return false; // Invalid username (only alphanumeric and underscore allowed)
  }

  if (!preg_match('/^[1-5][0-9]*$/', $rating)) {
    return false; // Rating must be between 1 and 5
  }

  // Sanitize inputs (Prevent SQL Injection - KEY STEP!)
  $productId = $dbConnection->real_escape_string($productId);
  $username = $dbConnection->real_escape_string($username);
  $rating = $dbConnection->real_escape_string($rating);
  $comment = $dbConnection->real_escape_string($comment);

  // Construct the SQL query
  $sql = "INSERT INTO reviews (product_id, username, rating, comment) VALUES ('$productId', '$username', '$rating', '$comment')";

  // Execute the query
  if ($dbConnection->query($sql) === TRUE) {
    return true; // Success
  } else {
    // Handle error
    error_log("Error adding review: " . $dbConnection->error);
    return false;
  }
}


/**
 * Get all user reviews for a product.
 *
 * @param string $productId The unique identifier of the product.
 * @param array $dbConnection  An existing database connection object.
 * @return array|false An array of reviews or false on error.
 */
function get_user_reviews(string $productId, array $dbConnection) {
    $sql = "SELECT * FROM reviews WHERE product_id = '$productId'";
    $result = $dbConnection->query($sql);

    if ($result === FALSE) {
        error_log("Error querying reviews: " . $dbConnection->error);
        return false;
    }

    $reviews = array();
    while ($row = $result->fetch_assoc()) {
        $reviews[] = $row;
    }

    return $reviews;
}

/**
 * Display User Reviews (for demonstration purposes)
 *
 * This is a helper function to display reviews.  In a real application,
 * this would be handled by your template engine.
 *
 * @param array $reviews An array of review objects.
 */
function display_user_reviews(array $reviews) {
  echo "<h2>User Reviews for Product ID: " . $productId . "</h2>";
  if (empty($reviews)) {
    echo "<p>No reviews yet.</p>";
    return;
  }

  foreach ($reviews as $review) {
    echo "<div>";
    echo "<strong>Username:</strong> " . $review['username'] . "<br>";
    echo "<strong>Rating:</strong> " . $review['rating'] . " stars<br>";
    echo "<strong>Comment:</strong> " . $review['comment'] . "<br>";
    echo "</div>";
  }
}

// Example Usage (Demonstration)
// *** IMPORTANT: Replace these with your actual database connection ***
$db_host = 'localhost';
$db_user = 'your_db_user';
$db_password = 'your_db_password';
$db_name = 'your_db_name';

$dbConnection = new mysqli($db_host, $db_user, $db_password, $db_name);

if ($dbConnection->connect_error) {
    die("Connection failed: " . $dbConnection->connect_error);
}

// Add a review (replace with actual product ID and user)
$review_result = add_user_review('product_123', 'john_doe', '4', 'Great product, highly recommended!', $dbConnection);

if ($review_result) {
  echo "<p>Review added successfully!</p>";
} else {
  echo "<p>Error adding review.</p>";
}

// Get all reviews for product_123
$all_reviews = get_user_reviews('product_123', $dbConnection);

if ($all_reviews) {
    display_user_reviews($all_reviews, $dbConnection);
} else {
    echo "<p>Error retrieving reviews.</p>";
}


// Close the database connection
$dbConnection->close();

?>
