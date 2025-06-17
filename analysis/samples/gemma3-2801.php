

<?php

/**
 * User Review Function
 *
 * This function allows you to create and display user reviews for a product.
 *
 * @param string $productId The ID of the product being reviewed.
 * @param string $username The username of the user submitting the review.
 * @param string $rating  The rating given by the user (e.g., 1-5).
 * @param string $comment The user's review comment.
 * @param array $reviews  An existing array of reviews for the product (optional).
 *
 * @return array  The updated array of reviews.
 */
function createAndDisplayReview(string $productId, string $username, string $rating, string $comment, array &$reviews = []) {
  // Validation (Simple - extend as needed)
  if (empty($rating) || !is_numeric($rating) || intval($rating) < 1 || intval($rating) > 5) {
    echo "<p style='color: red;'>Invalid rating. Rating must be a number between 1 and 5.</p>";
    return $reviews; // Return existing reviews - don't add invalid ones
  }

  if (empty($comment)) {
    echo "<p style='color: red;'>Please enter a review comment.</p>";
    return $reviews;
  }
  
  // Create the review object (could be an array, object, or custom class)
  $review = [
    'productId' => $productId,
    'username' => $username,
    'rating' => $rating,
    'comment' => $comment,
    'date' => date('Y-m-d H:i:s') // Add a timestamp for the review
  ];

  // Add the review to the array
  $reviews[] = $review;

  // Display the review (optional - you can remove this and handle display elsewhere)
  echo "<div class='review'>";
  echo "<p><strong>Username:</strong> " . htmlspecialchars($username) . "</p>";
  echo "<p><strong>Rating:</strong> " . htmlspecialchars($rating) . "</p>";
  echo "<p><strong>Comment:</strong> " . htmlspecialchars($comment) . "</p>";
  echo "<p><strong>Date:</strong> " . htmlspecialchars($review['date']) . "</p>";
  echo "</div>";
  
  return $reviews;
}


// Example usage:
$productReviews = [];

// Create a review
$productReviews = createAndDisplayReview("product123", "JohnDoe", 4, "Great product!  I highly recommend it.", $productReviews);

// Create another review
$productReviews = createAndDisplayReview("product123", "JaneSmith", 5, "Excellent value for money.", $productReviews);

// Display all reviews
echo "<h2>Product Reviews for Product 123:</h2>";
foreach ($productReviews as $review) {
  echo $review['comment'] . "<br>";
}

?>
