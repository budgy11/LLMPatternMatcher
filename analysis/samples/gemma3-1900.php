

<?php

/**
 * User Review Function
 *
 * This function allows users to submit and display reviews for a given product.
 *
 * @param string $productId The ID of the product to review.
 * @param string $productName The name of the product.
 * @param array $reviews  An array of review data.  Each item in this array should be an associative array
 *                      with the keys 'user', 'rating' (1-5), and 'comment'.
 * @return array|string  A string containing the review display or an error message.
 */
function displayReviews(string $productId, string $productName, array $reviews)
{
  // Input Validation - Essential for security and data integrity
  if (empty($productId) || empty($productName) || empty($reviews)) {
    return "Error: Product ID, Product Name, and Reviews are required.";
  }

  // Validate review data -  Example of data validation. You can expand this.
  foreach ($reviews as $review) {
    if (!isset($review['user'], $review['rating'], $review['comment'])) {
      return "Error: Review data must contain 'user', 'rating', and 'comment' keys.";
    }

    if (!is_numeric($review['rating']) || $review['rating'] < 1 || $review['rating'] > 5) {
      return "Error: Rating must be a number between 1 and 5.";
    }
  }


  // Build the review display string
  $reviewDisplay = "<h2>Reviews for {$productName} (ID: {$productId})</h2>";
  $reviewDisplay .= "<ul class='reviews'>";
  foreach ($reviews as $review) {
    $reviewDisplay .= "<li>";
    $reviewDisplay .= "<div class='review-user'>" . htmlspecialchars($review['user']) . "</div>"; // Escape for security
    $reviewDisplay .= "<div class='review-rating'>Rating: " . $review['rating'] . "</div>";
    $reviewDisplay .= "<div class='review-comment'>" . htmlspecialchars($review['comment']) . "</div>";
    $reviewDisplay .= "</li>";
  }
  $reviewDisplay .= "</ul>";

  return $reviewDisplay;
}



// Example Usage:
$productId = "123";
$productName = "Awesome Gadget";

// Sample Reviews
$reviews = [
  [
    'user' => "John Doe",
    'rating' => 5,
    'comment' => "This product is amazing!  Highly recommended."
  ],
  [
    'user' => "Jane Smith",
    'rating' => 4,
    'comment' => "Great product, but could be a little cheaper."
  ],
  [
    'user' => "Peter Jones",
    'rating' => 3,
    'comment' => "It's okay, does the job."
  ]
];


// Display the reviews
$reviewOutput = displayReviews($productId, $productName, $reviews);
echo $reviewOutput;

// Example of an error case
$errorOutput = displayReviews("", "Bad Product", []);
echo "<br><br>Error Output:<br>" . $errorOutput;
?>
