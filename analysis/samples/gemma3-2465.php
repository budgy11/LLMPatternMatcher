

<?php

/**
 * User Review Function
 *
 * This function allows users to submit and display reviews for a product.
 * It handles input validation, sanitization, and displays the review.
 *
 * @param string $product_id The ID of the product being reviewed.
 * @param string $review_text The text of the review submitted by the user.
 * @param string $username The username of the reviewer (for display).
 * @param array $reviews An array to store reviews (optional, for persistent storage).
 * @return array|string  Returns an array containing review data, or an error message as a string.
 */
function create_review(string $product_id, string $review_text, string $username, array &$reviews = []) {

  // Input Validation & Sanitization
  if (empty($review_text)) {
    return "Error: Review text cannot be empty.";
  }

  $review_text = trim($review_text); // Remove leading/trailing whitespace
  $review_text = htmlspecialchars($review_text); // Protect against XSS attacks
  $review_id = time(); // Generate a unique ID for the review

  // Review Data
  $review = [
    'id' => $review_id,
    'product_id' => $product_id,
    'username' => $username,
    'review_text' => $review_text,
    'timestamp' => date('Y-m-d H:i:s')
  ];

  // Store the Review (Example: using an array - can be adapted to a database)
  $reviews[$review_id] = $review;

  return $review; // Return the created review data
}


// Example Usage:

// Simulate a product ID
$product_id = '123';

// User Input (Replace with actual form input)
$review_text = $_POST['review_text']; // Assuming the review text is submitted via a form
$username = $_POST['username'];

// Create the review
$review_data = create_review($product_id, $review_text, $username);

// Handle the result
if (is_array($review_data)) {
  // Review was successfully created
  echo "<h2>Review Submitted!</h2>";
  echo "<p><strong>Username:</strong> " . htmlspecialchars($username) . "</p>";
  echo "<p><strong>Review:</strong> " . htmlspecialchars($review_data['review_text']) . "</p>";
  echo "<p><strong>Timestamp:</strong> " . $review_data['timestamp'] . "</p>";
  // Add the review to a display (e.g., echo $review_data['id'] to get the review ID)
} else {
  // An error occurred
  echo "<p>Error: " . $review_data . "</p>";
}



?>
