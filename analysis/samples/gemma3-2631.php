

<?php

/**
 * User Review Function
 *
 * This function allows users to submit and display reviews for a product or service.
 *
 * @param string $productName The name of the product or service being reviewed.
 * @param string $reviewText The text of the review submitted by the user.
 * @param string $username The username of the user submitting the review.
 * @param string $rating (optional) A rating from 1 to 5 (default 5).
 *
 * @return array An array containing:
 *   - 'success': true if the review was successfully submitted, false otherwise.
 *   - 'message': A message to display to the user (e.g., success or error message).
 *   - 'review': The submitted review data (product, username, rating, review text).
 */
function submitReview(string $productName, string $reviewText, string $username, string $rating = '5'): array
{
  // Basic input validation - This is CRUCIAL in a real application
  if (empty($productName)) {
    return ['success' => false, 'message' => 'Product name cannot be empty.', 'review' => []];
  }
  if (empty($reviewText)) {
    return ['success' => false, 'message' => 'Review text cannot be empty.', 'review' => []];
  }
  if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
    return ['success' => false, 'message' => 'Rating must be a number between 1 and 5.', 'review' => []];
  }

  //  In a real application, you would:
  //  1. Save the review to a database.
  //  2. Generate a unique ID for the review.
  //  3.  Store the ID, product name, username, rating, and review text in the database.

  // Simulate database saving for demonstration purposes
  $reviewId = generateUniqueId(); // Replace with your actual ID generation function
  $reviewData = [
    'product_name' => $productName,
    'username' => $username,
    'rating' => $rating,
    'review_text' => $reviewText,
    'id' => $reviewId
  ];

  return ['success' => true, 'message' => 'Review submitted successfully!', 'review' => $reviewData];
}


/**
 * Generates a unique ID (Placeholder - Replace with a robust ID generation mechanism)
 *
 * This is a very simple example.  In a production environment, use something like UUIDs.
 */
function generateUniqueId(): string
{
    return uniqid(); // Returns a unique ID based on timestamp and random data
}

// ------------------- Example Usage -------------------
// Example 1: Successful submission
$reviewResult = submitReview('Awesome Widget', 'This widget is amazing!', 'JohnDoe', '4');
print_r($reviewResult);

// Example 2: Invalid rating
$reviewResult = submitReview('Another Product', 'Good product.', 'JaneSmith', '6');
print_r($reviewResult);

// Example 3: Empty review text
$reviewResult = submitReview('Something', '', 'PeterPan');
print_r($reviewResult);
?>
