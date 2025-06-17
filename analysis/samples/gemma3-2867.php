

<?php

/**
 * User Review Function
 *
 * This function allows users to submit and display reviews for a given product or item.
 *
 * @param string $productId The unique identifier for the product or item.
 * @param string $userName The name of the user submitting the review.
 * @param string $reviewText The text content of the review.
 * @param array $reviews  (Optional) An array of existing reviews.  If not provided, an empty array is created.
 * @return array An array containing the updated array of reviews, with the new review added.
 */
function addReview(string $productId, string $userName, string $reviewText, array $reviews = []): array
{
  // Input validation - basic checks, can be expanded
  if (empty($productId)) {
    throw new InvalidArgumentException("Product ID cannot be empty.");
  }
  if (empty($userName)) {
    throw new InvalidArgumentException("User name cannot be empty.");
  }
  if (empty($reviewText)) {
    throw new InvalidArgumentException("Review text cannot be empty.");
  }

  // Create a new review object.  You might want to use a more sophisticated object.
  $review = [
    'productId' => $productId,
    'userName' => $userName,
    'reviewText' => $reviewText,
    'timestamp' => time() // Add a timestamp for organization
  ];

  // Add the review to the array
  $reviews[] = $review;

  return $reviews;
}


/**
 * Function to display reviews for a given product.
 *
 * @param string $productId The product ID.
 * @param array $reviews An array of review objects.
 */
function displayReviews(string $productId, array $reviews)
{
  echo "<h2>Reviews for Product ID: " . $productId . "</h2>";

  if (empty($reviews)) {
    echo "<p>No reviews yet.</p>";
    return;
  }

  echo "<ul>";
  foreach ($reviews as $review) {
    echo "<li>";
    echo "<strong>" . $review['userName'] . ":</strong> " . htmlspecialchars($review['reviewText']) . "<br>";
    echo "Timestamp: " . date('Y-m-d H:i:s', $review['timestamp']);
    echo "</li>";
  }
  echo "</ul>";
}



// --- Example Usage ---

// Initial Reviews (Start with an empty array)
$productReviews = [];

// Add some reviews
try {
    $productReviews = addReview("PROD123", "Alice", "Great product, highly recommended!");
    $productReviews = addReview("PROD123", "Bob", "Works as expected.", $productReviews);
    $productReviews = addReview("PROD456", "Charlie", "Could be better, but decent.", $productReviews);
} catch (InvalidArgumentException $e) {
    echo "Error: " . $e->getMessage() . "<br>";
}


// Display the reviews
displayReviews("PROD123", $productReviews);
displayReviews("PROD456", $productReviews);
?>
