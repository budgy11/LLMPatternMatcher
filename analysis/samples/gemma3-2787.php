

<?php

/**
 * User Review Function
 *
 * This function allows users to submit and view reviews for a product.
 *
 * @param string $productId The ID of the product being reviewed.
 * @param string $reviewText The text of the review submitted by the user.
 * @param string $username The username of the reviewer.
 * @param array $reviews An array of existing reviews for the product (optional).
 *
 * @return array An array containing the updated reviews, or an error message if submission fails.
 */
function submitReview(string $productId, string $reviewText, string $username, array &$reviews = []): array {
  // Input validation (you can expand on this)
  if (empty($reviewText)) {
    return ['error' => 'Review text cannot be empty.'];
  }

  // Create the review object
  $review = [
    'productId' => $productId,
    'username' => $username,
    'reviewText' => $reviewText,
    'timestamp' => time() // Add a timestamp for sorting/filtering
  ];

  // Add the review to the array
  $reviews[] = $review;

  return $reviews;
}

/**
 * Display Reviews Function
 *
 * This function displays a list of reviews for a product.
 *
 * @param array $reviews An array of reviews for the product.
 *
 * @return string An HTML string containing the list of reviews, or an error message if no reviews are found.
 */
function displayReviews(array $reviews): string {
  if (empty($reviews)) {
    return "<p>No reviews yet. Be the first!</p>";
  }

  $html = '<ul>';
  foreach ($reviews as $review) {
    $html .= '<li><strong>' . htmlspecialchars($review['username']) . ':</strong> ' . htmlspecialchars($review['reviewText']) . ' (' . date('Y-m-d H:i:s', $review['timestamp']) . ')</li>';
  }
  $html .= '</ul>';

  return $html;
}



// --- Example Usage ---

// Initial Reviews (optional - you'd typically get this from a database)
$productReviews = [];

// Submit a review
$newReviews = submitReview('product123', 'This is a fantastic product!', 'JohnDoe');
print_r($newReviews); // Output: Array ( [0] => Array ( [productId] => product123 [username] => JohnDoe [reviewText] => This is a fantastic product! [timestamp] => 1678886400 ) )

// Submit another review
$newReviews = submitReview('product123', 'Great value for money.', 'JaneSmith');
print_r($newReviews);

// Display the reviews
$reviewHTML = displayReviews($newReviews);
echo $reviewHTML;


// Demonstrating error handling
$errorResult = submitReview('product456', '', 'TestUser');
print_r($errorResult);

?>
