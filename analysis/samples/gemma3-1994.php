

<?php

/**
 * User Review Function
 *
 * This function allows you to create, read, update, and delete user reviews.
 *
 * @param string $productName The name of the product the review is for.
 * @param string $userEmail The email address of the user submitting the review.
 * @param string $reviewText The text of the review.
 * @param array $reviewData (Optional) An associative array containing review data
 *                         (e.g., ['rating' => 5, 'timestamp' => time()])
 *
 * @return array An array containing the result of the operation.  Returns an error message
 *               on failure.  Returns a success message and review ID on success.
 */
function create_review(string $productName, string $userEmail, string $reviewText, array $reviewData = [])
{
  // Validate inputs (Basic Example - Expand for more robust validation)
  if (empty($productName)) {
    return ['status' => 'error', 'message' => 'Product name cannot be empty.'];
  }
  if (empty($userEmail)) {
    return ['status' => 'error', 'message' => 'User email cannot be empty.'];
  }
  if (empty($reviewText)) {
    return ['status' => 'error', 'message' => 'Review text cannot be empty.'];
  }

  // Sanitize inputs -  IMPORTANT:  Always sanitize user inputs!
  $productName = filter_var($productName, FILTER_SANITIZE_STRING);
  $reviewText = filter_var($reviewText, FILTER_SANITIZE_STRING);

  // Simulate a database insert.  Replace with your actual database logic.
  // This is just a placeholder example.
  $reviewId = generate_unique_id();  // Replace with your unique ID generation
  $timestamp = time();

  $review = [
    'product_name' => $productName,
    'user_email' => $userEmail,
    'review_text' => $reviewText,
    'rating' => isset($reviewData['rating']) ? $reviewData['rating'] : null,
    'timestamp' => $timestamp,
    'review_id' => $reviewId,
  ];

  // In a real application, you would save this data to a database.
  // For this example, we just log the review.
  log_review($review);

  return ['status' => 'success', 'message' => 'Review created successfully.', 'review_id' => $reviewId];
}


/**
 * Simulates generating a unique ID.
 *  Replace with your actual unique ID generation logic.
 * @return string
 */
function generate_unique_id() {
    return bin2hex(random_bytes(16)); // A simple way to generate a unique ID
}


/**
 * Simulates logging a review to a file.
 *  Replace this with your actual logging mechanism.
 * @param array $review
 */
function log_review(array $review) {
    // This is just a placeholder.  Replace with your logging code.
    file_put_contents('review_log.txt', "New Review: " . json_encode($review) . "
", FILE_APPEND);
}

// --- Example Usage ---
// Example 1: Create a review
$result = create_review('Awesome Gadget', 'test@example.com', 'Great product!');
print_r($result);

// Example 2: Create a review with a rating
$result = create_review('Another Product', 'user@email.net', 'Good but could be better.', ['rating' => 4]);
print_r($result);
?>
