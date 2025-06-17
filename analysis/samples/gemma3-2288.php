

<?php

/**
 * User Review Function
 *
 * This function allows users to submit and display reviews for a product.
 *
 * @param string $productId The ID of the product being reviewed.
 * @param string $productName The name of the product.
 * @param string $reviewText The text of the review submitted by the user.
 * @param string $username The username of the user submitting the review.
 * @param array  $reviews An optional array of existing reviews to populate the display.  If empty, a new array is created.
 *
 * @return array An array containing the reviews.  Returns an empty array if an error occurs.
 */
function createAndDisplayReview(string $productId, string $productName, string $reviewText, string $username, array $reviews = []) {

  // Input validation - crucial for security and data integrity
  if (empty($reviewText)) {
    return []; // Return empty array if review text is empty
  }
  if (strlen($reviewText) > 1000) { // Simple length limit (adjust as needed)
    return []; // Return empty array if review text is too long
  }

  // Create the review object (you might want a more sophisticated Review class)
  $review = [
    'productId' => $productId,
    'productName' => $productName,
    'username' => $username,
    'reviewText' => $reviewText,
    'timestamp' => date('Y-m-d H:i:s') // Add timestamp for organization
  ];

  // Add the new review to the existing array
  $reviews[] = $review;

  return $reviews;
}

/**
 * Displays the reviews for a given product.
 *
 * @param array $reviews An array of review objects.
 *
 * @return string HTML output of the reviews.  Returns an empty string if there are no reviews.
 */
function displayReviews(array $reviews) {
  if (empty($reviews)) {
    return '<p>No reviews yet.</p>';
  }

  $html = '<ul>';
  foreach ($reviews as $review) {
    $html .= '<li>';
    $html .= '<p><strong>' . htmlspecialchars($review['username']) . ':</strong> ' . htmlspecialchars($review['reviewText']) . '</p>';
    $html .= '<p><em>' . htmlspecialchars($review['timestamp']) . '</p>';
    $html .= '</li>';
  }
  $html .= '</ul>';
  return $html;
}


// Example Usage:

// Initial Review
$productID = '123';
$productName = 'Awesome Widget';
$reviewText = 'This widget is fantastic!  It works perfectly.';
$username = 'JohnDoe';
$reviews = createAndDisplayReview($productID, $productName, $reviewText, $username);

// Display the reviews
echo "<h2>Reviews for " . $productName . "</h2>";
echo displayReviews($reviews);


// Adding another review
$reviews = createAndDisplayReview($productID, $productName, 'Another great product!', 'JaneSmith');
echo "<h2>More Reviews for " . $productName . "</h2>";
echo displayReviews($reviews);

?>
