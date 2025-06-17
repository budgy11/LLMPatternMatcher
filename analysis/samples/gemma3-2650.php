

<?php

/**
 * User Review Function
 *
 * This function allows users to submit and view reviews for a product or service.
 *
 * @param string $product_id The ID of the product or service being reviewed.
 * @param string $user_name The name of the user submitting the review.
 * @param string $review_text The text of the review.
 * @param int $rating (Optional)  The rating given by the user (1-5). Defaults to 0.
 * @return array An array containing the review data, or an error message if the submission fails.
 */
function submitReview(string $product_id, string $user_name, string $review_text, int $rating = 0) {

  // Validation - Basic input validation.  Expand as needed for your application.
  if (empty($product_id)) {
    return ['error' => 'Product ID cannot be empty.'];
  }
  if (empty($user_name)) {
    return ['error' => 'User name cannot be empty.'];
  }
  if (empty($review_text)) {
    return ['error' => 'Review text cannot be empty.'];
  }
  if ($rating < 1 || $rating > 5) {
    return ['error' => 'Rating must be between 1 and 5.'];
  }

  // In a real application, you would save this data to a database.
  // This example just simulates the storage.
  $review = [
    'product_id' => $product_id,
    'user_name' => $user_name,
    'review_text' => $review_text,
    'rating' => $rating,
    'submission_timestamp' => date('Y-m-d H:i:s')
  ];

  // Simulate saving the review to a file (replace with database insertion)
  file_put_contents('reviews.txt', json_encode($review) . PHP_EOL, FILE_APPEND);

  return $review;
}


/**
 *  Get all Reviews
 *
 *  Retrieves all reviews for a given product.  In a production system,
 *  this would query a database.
 *
 * @param string $product_id The ID of the product to retrieve reviews for.
 * @return array An array of review objects, or an empty array if no reviews are found.
 */
function getReviews(string $product_id) {
  // Simulate reading reviews from a file
  $reviews = [];
  $file = fopen('reviews.txt', 'r');
  if ($file) {
    while (($line = fgets($file)) !== false) {
      $review = json_decode($line, true);
      if ($review && $review['product_id'] == $product_id) {
        $reviews[] = $review;
      }
    }
    fclose($file);
  }
  return $reviews;
}



// Example Usage
// Submit a review
$review_data = submitReview('product123', 'John Doe', 'Great product! Highly recommended.', 5);

if (isset($review_data['error'])) {
  echo "Error submitting review: " . $review_data['error'] . "
";
} else {
  echo "Review submitted successfully: " . json_encode($review_data) . "
";
}

// Get all reviews for product123
$all_reviews = getReviews('product123');

if (empty($all_reviews)) {
  echo "No reviews found for product123.
";
} else {
  echo "Reviews for product123:
";
  foreach ($all_reviews as $review) {
    echo "  - User: " . $review['user_name'] . ", Rating: " . $review['rating'] . ", Text: " . $review['review_text'] . "
";
  }
}


?>
