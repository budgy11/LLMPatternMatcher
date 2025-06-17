

<?php

/**
 * User Review Function
 *
 * This function allows you to store and display user reviews for a product or item.
 *
 * @param string $productName The name of the product or item being reviewed.
 * @param string $userId      The ID of the user submitting the review.
 * @param string $reviewText  The text of the review submitted by the user.
 * @param int    $rating      The rating given by the user (e.g., 1-5).
 * @param array $dbConnection  (Optional) Database connection object. If not provided, uses a default one.
 *
 * @return array  An array containing the review ID, product name, user ID, review text, and rating.
 *                Returns an empty array if the review fails to save.
 */
function saveReview(string $productName, string $userId, string $reviewText, int $rating, $dbConnection = null) {

  // Validate input (very basic - you'd want to add more robust validation)
  if (empty($productName) || empty($reviewText) || $rating < 1 || $rating > 5) {
    error_log("Invalid review data submitted."); // Log the error (important for debugging)
    return [];
  }

  // If no database connection is provided, create a default one.  
  // Replace with your actual database credentials.  This is just for demonstration.
  if ($dbConnection === null) {
    $dbConnection = new PDO('mysql:host=localhost;dbname=your_database_name', 'your_username', 'your_password');
  }

  try {
    // Prepare the SQL statement
    $stmt = $dbConnection->prepare(
      "INSERT INTO reviews (product_name, user_id, review_text, rating) VALUES (?, ?, ?, ?)"
    );

    // Bind parameters to prevent SQL injection
    $stmt->execute([$productName, $userId, $reviewText, $rating]);

    // Get the ID of the newly inserted review
    $reviewId = $dbConnection->lastInsertId();

    return [
      'review_id' => $reviewId,
      'product_name' => $productName,
      'user_id' => $userId,
      'review_text' => $reviewText,
      'rating' => $rating
    ];

  } catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage()); // Log the error
    // Handle the error appropriately (e.g., display a user-friendly error message)
    return [];
  }
}

/**
 * Display Reviews Function
 *
 * This function retrieves and displays reviews for a given product.
 *
 * @param string $productName The name of the product to retrieve reviews for.
 * @param array $dbConnection  Database connection object.
 *
 * @return array An array containing an empty array if no reviews are found,
 *               or an array of review objects.
 */
function displayReviews(string $productName, $dbConnection) {

  try {
    $stmt = $dbConnection->prepare("SELECT * FROM reviews WHERE product_name = ?");
    $stmt->execute([$productName]);
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $reviews;

  } catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    return [];
  }
}

// Example Usage (Illustrative - Replace with your actual database and data)
// Note: This requires a database set up with a 'reviews' table.

// Example 1: Saving a review
$review = saveReview('Awesome Gadget', 'user123', 'Great product! I love it.', 5);

if (!empty($review)) {
  echo "Review saved successfully! Review ID: " . $review['review_id'] . "<br>";
} else {
  echo "Failed to save review.";
}

// Example 2: Displaying reviews for 'Awesome Gadget'
$reviews = displayReviews('Awesome Gadget', $dbConnection); // Use your actual database connection

if (empty($reviews)) {
  echo "No reviews found for 'Awesome Gadget'.";
} else {
  echo "<h2>Reviews for 'Awesome Gadget':</h2>";
  foreach ($reviews as $review) {
    echo "<p><strong>User:</strong> " . $review['user_id'] . "<br>";
    echo "<strong>Rating:</strong> " . $review['rating'] . "<br>";
    echo "<strong>Review:</strong> " . $review['review_text'] . "</p>";
  }
}
?>
