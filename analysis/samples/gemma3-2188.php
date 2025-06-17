

<?php

/**
 * User Review Function
 *
 * This function allows users to submit reviews for a product or service.
 * It includes input validation and sanitization to prevent common vulnerabilities.
 *
 * @param string $productName The name of the product or service being reviewed.
 * @param string $reviewerName The name of the reviewer.
 * @param string $reviewText The user's review text.
 * @param int $rating  The rating given by the reviewer (1-5).
 * @return array|false  An array containing the review details on success, or false on failure.
 */
function createReview(string $productName, string $reviewerName, string $reviewText, int $rating) {
  // Input Validation
  if (empty($productName)) {
    return false; // Product name cannot be empty
  }

  if (empty($reviewerName)) {
    return false; // Reviewer name cannot be empty
  }

  if (empty($reviewText)) {
    return false; // Review text cannot be empty
  }

  if ($rating < 1 || $rating > 5) {
    return false; // Rating must be between 1 and 5
  }

  // Sanitize Input - IMPORTANT for security
  $productName = trim($productName);  // Remove leading/trailing whitespace
  $productName = htmlspecialchars($productName); // Prevents XSS attacks
  $reviewerName = trim($reviewerName);
  $reviewerName = htmlspecialchars($reviewerName);
  $reviewText = trim($reviewText);
  $reviewText = htmlspecialchars($reviewText);


  // Store Review (Replace this with your database logic)
  // This is a placeholder - adapt to your database setup
  $review = [
    'product_name' => $productName,
    'reviewer_name' => $reviewerName,
    'review_text' => $reviewText,
    'rating' => $rating,
    'date' => date('Y-m-d H:i:s') // Add timestamp for tracking
  ];

  // Example:  Write to a simple file (for demonstration only)
  $file = 'reviews.txt';
  $reviewString = $file . "
" . json_encode($review) . "
";
  file_put_contents($file, $reviewString, FILE_APPEND);


  return $review; // Return the review data
}


// --- Example Usage ---
$product = "Awesome Gadget";
$user = "John Doe";
$comment = "This is a fantastic gadget!  I highly recommend it.";
$rating = 4;

$reviewResult = createReview($product, $user, $comment, $rating);

if ($reviewResult !== false) {
  echo "Review created successfully!  Review Data: " . json_encode($reviewResult) . "
";
} else {
  echo "Error creating review. Please check your input.
";
}



// Example with invalid input:
$reviewResult = createReview("", "Jane Doe", "Invalid review", 3); // Empty product name
if ($reviewResult === false) {
    echo "Invalid input detected!
";
}
?>
