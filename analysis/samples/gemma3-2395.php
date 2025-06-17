

<?php

/**
 * User Review Function
 *
 * This function allows users to submit reviews for a product or service.
 * It includes basic validation and sanitization.
 *
 * @param string $productName  The name of the product or service being reviewed.
 * @param string $reviewText   The user's review text.
 * @param int   $rating       The rating given by the user (1-5).
 * @return array  An array containing a success/error message.
 */
function submit_review(string $productName, string $reviewText, int $rating): array
{
  // Input Validation
  if (empty($productName)) {
    return ['success' => false, 'message' => 'Product name cannot be empty.'];
  }
  if (empty($reviewText)) {
    return ['success' => false, 'message' => 'Review text cannot be empty.'];
  }
  if ($rating < 1 || $rating > 5) {
    return ['success' => false, 'message' => 'Rating must be between 1 and 5.'];
  }

  // Sanitize input (basic -  consider more robust sanitization for production)
  $productName = trim($productName);
  $reviewText = trim($reviewText);
  $reviewText = htmlspecialchars($reviewText); // Prevents XSS attacks

  // Here you would typically save the review to a database.
  //  For this example, we'll just simulate a save.

  // Example database save (replace with your actual database logic)
  $review_data = [
    'product_name' => $productName,
    'review_text' => $reviewText,
    'rating' => $rating,
    'submission_timestamp' => time()
  ];

  // In a real application, you'd insert this data into your database.
  // For example:
  // $db->insert('reviews', $review_data);

  // Simulate a successful save
  return ['success' => true, 'message' => 'Review submitted successfully!'];
}


// --- Example Usage ---
// Simulate getting data from a form submission.
$product = $_POST['product_name'];
$comment = $_POST['review_text'];
$stars = $_POST['rating'];

// Validate and process the form data
$result = submit_review($product, $comment, $stars);

// Display the result
if ($result['success']) {
  echo "<p style='color: green;'>$result[message]</p>";
} else {
  echo "<p style='color: red;'>Error: $result[message]</p>";
}

?>
