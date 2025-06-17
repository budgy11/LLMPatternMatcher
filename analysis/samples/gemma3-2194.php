

<?php

/**
 * User Review Function
 *
 * This function allows users to submit and display reviews for a product.
 *
 * @param string $productName The name of the product being reviewed.
 * @param string $reviewText The review text submitted by the user.
 * @param array $user_data An associative array containing user data like username and email (optional).
 *
 * @return array An array containing:
 *   - 'success': True if the review was successfully submitted, false otherwise.
 *   - 'message':  A message indicating the status of the review (success or error).
 *   - 'review': The submitted review text.
 */
function submit_review(string $productName, string $reviewText, array $user_data = []) {
  // Input validation - Basic checks to prevent vulnerabilities
  if (empty($productName)) {
    return ['success' => false, 'message' => 'Product name cannot be empty.', 'review' => $reviewText];
  }
  if (empty($reviewText)) {
    return ['success' => false, 'message' => 'Review text cannot be empty.', 'review' => $reviewText];
  }

  //  More robust validation could be added here, such as:
  //  - Checking review length
  //  - Sanitizing the review text to prevent XSS attacks

  // Store the review - Replace this with your actual database logic
  // This is a placeholder - You'll need to connect to your database
  // and use your database credentials.
  $review_id = generate_unique_id(); // Generates a unique ID for the review
  $timestamp = date("Y-m-d H:i:s");
  $stored_review = [
    'review_id' => $review_id,
    'product_name' => $productName,
    'review_text' => $reviewText,
    'user_name' => isset($user_data['username']) ? $user_data['username'] : 'Anonymous',
    'timestamp' => $timestamp,
  ];


  //  Example:  Store to a simple array for demonstration purposes.
  //  In a real application, you'd use a database.
  //  $reviews[] = $stored_review;



  //  Success
  return ['success' => true, 'message' => 'Review submitted successfully!', 'review' => $reviewText];
}


/**
 * Generates a unique ID for a review.  This is a simplified example.
 *  In a production environment, use a more robust ID generation method (UUID, etc.)
 *
 * @return string  A unique ID.
 */
function generate_unique_id() {
    return uniqid();
}



// --- Example Usage ---

// Example 1: Successful submission
$result = submit_review('Awesome T-Shirt', 'This shirt is fantastic! Great quality and fit.');
print_r($result);

// Example 2:  Empty product name
$result = submit_review('', 'A good review');
print_r($result);

// Example 3:  Empty review text
$result = submit_review('Cool Gadget', '');
print_r($result);

?>
