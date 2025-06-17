

<?php

/**
 * User Review Function
 *
 * This function allows you to create, display, and manage user reviews.
 *
 * @param string $productName The name of the product being reviewed.
 * @param string $userId The ID of the user submitting the review.
 * @param string $rating The rating given by the user (e.g., 1-5).
 * @param string $comment The user's comment about the product.
 * @param array $reviews (Optional) -  An array of existing reviews for this product.
 *
 * @return array  An updated array of reviews including the new review.
 */
function createAndManageReviews(string $productName, string $userId, string $rating, string $comment, array &$reviews = []): array
{
  // Input validation -  Important for security and data integrity
  if (empty($productName)) {
    return $reviews; // Return empty array if product name is empty
  }

  if (empty($userId)) {
    return $reviews; // Return empty array if user ID is empty
  }

  if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
    return $reviews; // Return empty array if rating is invalid
  }

  if (empty($comment)) {
      return $reviews; // Return empty array if comment is empty
  }

  // Add the new review
  $newReview = [
    'userId' => $userId,
    'rating' => $rating,
    'comment' => $comment,
    'timestamp' => time() // Add timestamp for tracking
  ];
  $reviews[] = $newReview;

  // Sort reviews by timestamp (newest first) - optional but useful
  usort($reviews, function ($a, $b) {
    return $b['timestamp'] - $a['timestamp'];
  });

  return $reviews;
}

// Example Usage:

// Initialize an empty array of reviews
$productReviews = [];

// Create a new review
$updatedReviews = createAndManageReviews('Awesome Gadget', 'user123', 4, 'This gadget is fantastic! I love it.', $productReviews);

// Display the updated reviews
echo "<h2>Reviews for Awesome Gadget:</h2>";
echo "<ul>";
foreach ($updatedReviews as $review) {
  echo "<li><strong>User:</strong> " . htmlspecialchars($review['userId']) . "<br>";
  echo "<strong>Rating:</strong> " . $review['rating'] . "<br>";
  echo "<strong>Comment:</strong> " . htmlspecialchars($review['comment']) . "<br>";
  echo "<strong>Timestamp:</strong> " . date("Y-m-d H:i:s", $review['timestamp']) . "</li>";
}
echo "</ul>";


// Another example:
$productReviews2 = [];
$updatedReviews2 = createAndManageReviews('Another Product', 'user456', 5, 'Great product, highly recommended!', $productReviews2);

echo "<h2>Reviews for Another Product:</h2>";
echo "<ul>";
foreach ($updatedReviews2 as $review) {
  echo "<li><strong>User:</strong> " . htmlspecialchars($review['userId']) . "<br>";
  echo "<strong>Rating:</strong> " . $review['rating'] . "<br>";
  echo "<strong>Comment:</strong> " . htmlspecialchars($review['comment']) . "<br>";
  echo "<strong>Timestamp:</strong> " . date("Y-m-d H:i:s", $review['timestamp']) . "</li>";
}
echo "</ul>";



?>
