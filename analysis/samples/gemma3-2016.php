

<?php

/**
 * User Review Function
 *
 * This function allows users to submit and display reviews for a product or item.
 *
 * @param string $productId  The ID of the product being reviewed.
 * @param string $username   The name of the user submitting the review.
 * @param string $reviewText The text of the review.
 * @param array $reviews     (Optional) An array to store all reviews.
 * @return array           The updated reviews array.
 */
function addOrDisplayReview(string $productId, string $username, string $reviewText, array &$reviews = []) {
  // Validation (Simple - could be expanded for more robust validation)
  if (empty($username) || empty($reviewText)) {
    return $reviews; // Return existing reviews if input is invalid
  }

  // Create review object
  $review = [
    'productId' => $productId,
    'username' => $username,
    'reviewText' => $reviewText,
    'timestamp' => time() // Optional: add timestamp for ordering
  ];

  // Add the review to the array
  $reviews[] = $review;

  return $reviews;
}

/**
 * Display Reviews
 *
 * This function displays a list of reviews for a given product ID.
 *
 * @param array $reviews An array of review objects.
 * @param string $productId The ID of the product to display reviews for.
 *
 */
function displayReviews(array $reviews, string $productId) {
  echo "<h2>Reviews for Product ID: " . $productId . "</h2>";

  if (empty($reviews)) {
    echo "<p>No reviews yet.</p>";
    return;
  }

  echo "<ul>";
  foreach ($reviews as $review) {
    echo "<li>";
    echo "<strong>Username:</strong> " . htmlspecialchars($review['username']) . "<br>"; // Escape for security
    echo "<strong>Review:</strong> " . htmlspecialchars($review['reviewText']) . "<br>";
    echo "<strong>Date:</strong> " . date('Y-m-d H:i:s', $review['timestamp']) . "</li>";
  }
  echo "</ul>";
}


// Example Usage:
// Initialize the reviews array (optional, but good for demonstration)
$productReviews = [];

// Add some reviews
$productReviews = addOrDisplayReview("product123", "John Doe", "Great product!  Highly recommended.", $productReviews);
$productReviews = addOrDisplayReview("product123", "Jane Smith", "Good value for money.", $productReviews);
$productReviews = addOrDisplayReview("product456", "Peter Jones", "Disappointed with the quality.", $productReviews);

// Display the reviews
displayReviews($productReviews, "product123");

// Display the reviews for a different product
displayReviews($productReviews, "product456");

?>
