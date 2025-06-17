

<?php

/**
 * User Review Function
 *
 * This function allows you to create and display user reviews for a product.
 *
 * @param string $productId The unique identifier of the product.
 * @param string $username The name of the user submitting the review.
 * @param string $reviewText The content of the review.
 * @param int $rating (Optional) Rating from 1-5. Defaults to 0 if not provided.
 *
 * @return array An array containing the review data (success/failure, review ID, review text, rating, and timestamp).
 *               Returns an error message if the review creation fails.
 */
function createReview(string $productId, string $username, string $reviewText, int $rating = 0) {
  // Validation (basic - expand as needed)
  if (empty($productId)) {
    return ['success' => false, 'message' => 'Product ID cannot be empty.'];
  }
  if (empty($username)) {
    return ['success' => false, 'message' => 'Username cannot be empty.'];
  }
  if (empty($reviewText)) {
    return ['success' => false, 'message' => 'Review text cannot be empty.'];
  }

  //  You would typically store reviews in a database here.
  //  This is a simplified example.

  $reviewId = uniqid(); // Generate a unique ID for the review
  $timestamp = time();

  // Simulate saving to a database (replace with your actual database interaction)
  $review = [
    'reviewId' => $reviewId,
    'productId' => $productId,
    'username' => $username,
    'reviewText' => $reviewText,
    'rating' => $rating,
    'timestamp' => $timestamp
  ];

  // Store the review in a data structure (for demonstration)
  //  In a real application, you would save this to a database.
  //  Example:
  //  saveReviewToDatabase($review);
  
  return ['success' => true, 'reviewId' => $reviewId, 'reviewText' => $reviewText, 'rating' => $rating, 'timestamp' => $timestamp];
}

/**
 *  Example usage:
 */

// Create a review
$review = createReview('product123', 'John Doe', 'This is a great product!');
if ($review['success']) {
  echo "Review created successfully! Review ID: " . $review['reviewId'] . "<br>";
  echo "Review Text: " . $review['reviewText'] . "<br>";
  echo "Rating: " . $review['rating'] . "<br>";
  echo "Timestamp: " . $review['timestamp'] . "<br>";
} else {
  echo "Error creating review: " . $review['message'] . "<br>";
}

// Create a review with a rating
$review2 = createReview('product456', 'Jane Smith', 'Excellent value for money!', 5);
if ($review2['success']) {
  echo "Review created successfully! Review ID: " . $review2['reviewId'] . "<br>";
} else {
  echo "Error creating review: " . $review2['message'] . "<br>";
}


// Example with error handling
$invalidReview = createReview('', 'Test User', 'Invalid review');
if(!$invalidReview['success']) {
    echo "Error creating review: " . $invalidReview['message'] . "<br>";
}
?>
