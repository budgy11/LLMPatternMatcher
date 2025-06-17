

<?php

/**
 * User Review Function
 *
 * This function allows you to collect and display user reviews for a product or item.
 * It includes validation, sanitization, and basic display formatting.
 *
 * @param string $productId The unique identifier for the product/item.
 * @param array $reviews An array of review objects, each with:
 *                       - $name:  The user's name.
 *                       - $rating:  A number from 1 to 5.
 *                       - $comment: The user's review text.
 * @return string A formatted string containing the reviews.
 */
function displayUserReviews(string $productId, array $reviews): string
{
  // Validate input - basic check for review array
  if (empty($reviews)) {
    return "<p>No reviews yet.</p>";
  }

  $output = "<div class='user-reviews'>";
  $output .= "<h3>Reviews for Product ID: " . htmlspecialchars($productId) . "</h3>";

  foreach ($reviews as $review) {
    // Validate review data - more robust validation is recommended in production
    if (
      !is_string($review['name']) ||
      !is_int($review['rating']) ||
      !is_string($review['comment']) ||
      $review['rating'] < 1 || $review['rating'] > 5
    ) {
      continue; // Skip invalid reviews.  Log errors for debugging.
    }

    $output .= "<div class='review'>";
    $output .= "<p><strong>" . htmlspecialchars($review['name']) . ":</strong> " . htmlspecialchars($review['comment']) . "</p>";
    $output .= "<p>Rating: " . htmlspecialchars($review['rating']) . "</p>";
    $output .= "</div>";
  }

  $output .= "</div>";
  return $output;
}


// Example Usage:
// Simulate some reviews
$reviews = [
  [
    'name' => 'John Doe',
    'rating' => 4,
    'comment' => 'Great product!  Easy to use and works perfectly.',
  ],
  [
    'name' => 'Jane Smith',
    'rating' => 5,
    'comment' => 'Excellent value for the price.  Highly recommended!',
  ],
  [
    'name' => 'Peter Jones',
    'rating' => 3,
    'comment' => 'It was okay, but could be better. The instructions were unclear.',
  ],
];

// Display the reviews
$reviewOutput = displayUserReviews('PROD-123', $reviews);
echo $reviewOutput;

?>
