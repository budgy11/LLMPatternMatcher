
<!--  CSS for styling the reviews (optional) -->
<style>
.review {
    border: 1px solid #ccc;
    padding: 10px;
    margin-bottom: 10px;
}

.new-review {
    border: 1px solid green;
    padding: 10px;
    margin-bottom: 10px;
}
</style>


<?php

/**
 * User Review Function
 *
 * This function allows you to store and display user reviews.
 *
 * @param string $productId The ID of the product being reviewed.
 * @param string $userName The name of the user submitting the review.
 * @param string $reviewText The text of the review.
 * @param int $rating (optional) The rating given by the user (1-5). Defaults to 0.
 * @return array|string  Returns an array of reviews if successful, or an error message string if not.
 */
function storeUserReview(string $productId, string $userName, string $reviewText, int $rating = 0) {
  // Input validation - essential for security and data integrity
  if (empty($productId)) {
    return "Error: Product ID cannot be empty.";
  }
  if (empty($userName)) {
    return "Error: User Name cannot be empty.";
  }
  if (empty($reviewText)) {
    return "Error: Review Text cannot be empty.";
  }
  if ($rating < 1 || $rating > 5) {
    return "Error: Rating must be between 1 and 5.";
  }

  // Sanitize input - important to prevent XSS and SQL injection vulnerabilities
  $productId = filter_var($productId, FILTER_SANITIZE_STRING);
  $userName = filter_var($userName, FILTER_SANITIZE_STRING);
  $reviewText = filter_var($reviewText, FILTER_SANITIZE_STRING);

  //  You'd typically use a database here.  This is a placeholder.
  //  Replace this with your actual database interaction logic.
  $reviews = []; // Initialize an array to store reviews.  Could be expanded to include timestamps, etc.

  $reviews[] = [
    'productId' => $productId,
    'userName' => $userName,
    'reviewText' => $reviewText,
    'rating' => $rating,
    'timestamp' => time() // Add a timestamp for tracking.
  ];

  // Log the review (optional - for debugging and auditing)
  // logMessage("New review stored: " . $reviewText);

  return $reviews; // Return the array of reviews (or the single review if you just want one).
}



/**
 * Example Usage
 */

// Store a review
$reviews = storeUserReview('product123', 'John Doe', 'Great product! Highly recommended.');
if (is_array($reviews)) {
  echo "Review stored successfully:
";
  print_r($reviews);
} else {
  echo $reviews; // Output the error message
}



// Example with validation errors
$reviews = storeUserReview('', 'Jane Smith', 'This is bad.');
if (is_array($reviews)) {
  echo $reviews;
} else {
  echo $reviews;
}

// Example with validation errors (rating outside range)
$reviews = storeUserReview('product456', 'Peter Jones', 'Excellent service', 7);
if (is_array($reviews)) {
  echo $reviews;
} else {
  echo $reviews;
}
?>
