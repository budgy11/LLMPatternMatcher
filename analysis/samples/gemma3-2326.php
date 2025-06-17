

<?php

/**
 * User Review Function
 *
 * This function allows users to submit reviews for a product or service.
 * It handles input validation, sanitization, and stores the review data.
 *
 * @param string $productId  The ID of the product or service being reviewed.
 * @param string $reviewerName The name of the reviewer.
 * @param string $reviewText  The text of the review.
 * @param int    $rating     The rating given by the reviewer (1-5).
 *
 * @return bool  True on success, false on failure.
 */
function submitReview(string $productId, string $reviewerName, string $reviewText, int $rating)
{
  // Validate inputs
  if (empty($productId)) {
    error_log("Error: Product ID cannot be empty.");
    return false;
  }

  if (empty($reviewerName)) {
    error_log("Error: Reviewer Name cannot be empty.");
    return false;
  }

  if (empty($reviewText)) {
    error_log("Error: Review Text cannot be empty.");
    return false;
  }

  if ($rating < 1 || $rating > 5) {
    error_log("Error: Rating must be between 1 and 5.");
    return false;
  }

  // Sanitize inputs (important for security - prevent XSS)
  $productId = filter_var($productId, FILTER_SANITIZE_STRING); // Sanitize to prevent HTML injection
  $reviewerName = filter_var($reviewerName, FILTER_SANITIZE_STRING);
  $reviewText = filter_var($reviewText, FILTER_SANITIZE_STRING);

  // Validate sanitized inputs - important to ensure data is still valid after sanitization
  if (empty($productId) || empty($reviewerName) || empty($reviewText)) {
      error_log("Error: Sanitized input is empty after processing.");
      return false;
  }

  // Store the review data (Replace this with your database logic)
  $reviewData = [
    'product_id' => $productId,
    'reviewer_name' => $reviewerName,
    'review_text' => $reviewText,
    'rating' => $rating,
    'timestamp' => time() // Add a timestamp for record keeping
  ];

  // Example: Save to a file (for demonstration)
  $filename = "reviews_" . $productId . ".txt";
  $dataString = json_encode($reviewData);
  file_put_contents($filename, $dataString . PHP_EOL, FILE_APPEND);

  // In a real application, you would save this to a database
  // For example:
  // $db = new PDO('mysql:host=localhost;dbname=your_database_name', 'username', 'password');
  // $stmt = $db->prepare("INSERT INTO reviews (product_id, reviewer_name, review_text, rating, timestamp) VALUES (?, ?, ?, ?, ?)");
  // $stmt->execute([$productId, $reviewerName, $reviewText, $rating, time()]);

  return true;
}

// Example Usage:
$productId = "product123";
$reviewerName = "John Doe";
$reviewText = "This product is amazing!  I highly recommend it.";
$rating = 4;

if (submitReview($productId, $reviewerName, $reviewText, $rating)) {
  echo "Review submitted successfully!";
} else {
  echo "Review submission failed.";
}

?>
