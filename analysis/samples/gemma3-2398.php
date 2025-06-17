

<?php

/**
 * User Review Function
 *
 * This function allows users to submit and view reviews for a product or service.
 *
 * @param string $productId The unique identifier for the product or service.
 * @param string $reviewerName The name of the reviewer.
 * @param string $reviewText The text of the review.
 * @param int $rating (optional) The rating given by the reviewer (1-5). Defaults to 0.
 * @return array|string  Returns an array with the review data if successful, or an error message string.
 */
function submitReview(string $productId, string $reviewerName, string $reviewText, int $rating = 0) {

  // Input Validation - Important for security and data integrity
  if (empty($productId)) {
    return "Error: Product ID cannot be empty.";
  }
  if (empty($reviewerName)) {
    return "Error: Reviewer Name cannot be empty.";
  }
  if (empty($reviewText)) {
    return "Error: Review Text cannot be empty.";
  }
  if ($rating < 1 || $rating > 5) {
    return "Error: Rating must be between 1 and 5.";
  }

  // Data Sanitization -  Prevent XSS and SQL Injection (Basic)
  $reviewerName = htmlspecialchars($reviewerName); // Escape HTML entities
  $reviewText = htmlspecialchars($reviewText);

  // In a real application, you'd store this data in a database.
  // This example simulates storing it in an array for simplicity.
  $review = [
    'productId' => $productId,
    'reviewerName' => $reviewerName,
    'reviewText' => $reviewText,
    'rating' => $rating,
    'timestamp' => time() // Add a timestamp for tracking
  ];

  // Store the review.  In a real app, this would insert into a database.
  // For demonstration, we'll just print the review data.
  echo "<pre>";
  print_r($review);
  echo "</pre>";

  return $review; // Returns the review data for processing (e.g., display)
}


// --- Example Usage ---

// Submit a review
$reviewResult = submitReview("product123", "John Doe", "Great product!  I love it!", 5);

if (is_array($reviewResult)) {
  echo "<p>Review submitted successfully: " . $reviewResult['reviewerName'] . " gave a rating of " . $reviewResult['rating'] . ".</p>";
} else {
  echo "<p>" . $reviewResult . "</p>"; // Display the error message
}


// Submit another review with a different rating
$reviewResult2 = submitReview("product456", "Jane Smith", "It's okay.", 3);

if (is_array($reviewResult2)) {
  echo "<p>Review submitted successfully: " . $reviewResult2['reviewerName'] . " gave a rating of " . $reviewResult2['rating'] . ".</p>";
} else {
  echo "<p>" . $reviewResult2 . "</p>";
}


// Example showing error handling
$errorResult = submitReview("", "Error Test", "Bad Input");
echo "<p>" . $errorResult . "</p>";
?>
