

<?php

/**
 * User Review Function
 *
 * This function allows users to submit and display reviews for a given product.
 *
 * @param string $productId The unique identifier of the product.
 * @param string $productName The name of the product.
 * @param string $reviewerName The name of the reviewer.
 * @param string $reviewText The review text.
 * @param array $reviews An optional array of existing reviews to display.
 *
 * @return array An array containing the existing reviews and the new review.
 */
function createAndDisplayReviews(string $productId, string $productName, string $reviewerName, string $reviewText, array &$reviews = []) {

  // Validate input (basic) - Enhance as needed for production environments
  if (empty($productId) || empty($productName) || empty($reviewerName) || empty($reviewText)) {
    return ['error' => 'All fields are required.'];
  }

  // Create the new review
  $newReview = [
    'productId' => $productId,
    'reviewerName' => $reviewerName,
    'reviewText' => $reviewText,
    'timestamp' => date('Y-m-d H:i:s')  // Add a timestamp for each review
  ];

  // Add the new review to the array
  $reviews[] = $newReview;

  // Sort reviews by timestamp (most recent first) - optional
  usort($reviews, function($a, $b) {
    return $b['timestamp'] <=> $a['timestamp'];
  });

  return $reviews;
}


// --- Example Usage ---

// Example 1:  Creating a new review
$productID = '12345';
$productName = 'Awesome Gadget';
$reviewer = 'John Doe';
$review = 'This gadget is fantastic!  I love it.';

$updatedReviews = createAndDisplayReviews($productID, $productName, $reviewer, $review);

if (isset($updatedReviews['error'])) {
  echo "Error: " . $updatedReviews['error'] . "<br>";
} else {
  echo "Product: " . $productName . "<br>";
  echo "Reviews:<br>";
  foreach ($updatedReviews as $review) {
    echo "Reviewer: " . $review['reviewerName'] . "<br>";
    echo "Review Text: " . $review['reviewText'] . "<br>";
    echo "Timestamp: " . $review['timestamp'] . "<br><br>";
  }
}


echo "<br>--- Example 2: Empty Review ---";
$productID = '67890';
$productName = 'Another Product';
$reviewer = 'Jane Smith';
$review = ''; // Empty review

$updatedReviews = createAndDisplayReviews($productID, $productName, $reviewer, $review);

if (isset($updatedReviews['error'])) {
  echo "Error: " . $updatedReviews['error'] . "<br>";
} else {
  echo "Product: " . $productName . "<br>";
  echo "Reviews:<br>";
  foreach ($updatedReviews as $review) {
    echo "Reviewer: " . $review['reviewerName'] . "<br>";
    echo "Review Text: " . $review['reviewText'] . "<br>";
    echo "Timestamp: " . $review['timestamp'] . "<br><br>";
  }
}
?>
