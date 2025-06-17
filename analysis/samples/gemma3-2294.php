

<?php

/**
 * User Review Function
 *
 * This function allows users to submit reviews for a product or service.
 * It includes input validation, sanitization, and basic data storage.
 *
 * @param string $product_id The ID of the product or service being reviewed.
 * @param string $user_name The name of the user submitting the review.
 * @param string $review_text The text of the review.
 * @param int    $rating     The rating (1-5).
 *
 * @return bool|string  Returns true on success, or an error message as a string on failure.
 */
function submit_review(string $product_id, string $user_name, string $review_text, int $rating) {

  // Input Validation -  Crucial for security and data integrity
  if (empty($product_id)) {
    return "Error: Product ID cannot be empty.";
  }
  if (empty($user_name)) {
    return "Error: User name cannot be empty.";
  }
  if (empty($review_text)) {
    return "Error: Review text cannot be empty.";
  }

  if ($rating < 1 || $rating > 5) {
    return "Error: Rating must be between 1 and 5.";
  }

  // Sanitize Input - Prevent common vulnerabilities (SQL injection, XSS, etc.)
  $product_id = filter_var($product_id, FILTER_SANITIZE_STRING); // Basic sanitization - adjust as needed
  $user_name = filter_var($user_name, FILTER_SANITIZE_STRING);
  $review_text = filter_var($review_text, FILTER_SANITIZE_STRING);

  // Data Validation -  Check that sanitized data is valid.
  if (!ctype_alnum($user_name)) {
    return "Error: User name must contain only letters and numbers.";
  }

  //  Database Interaction - Replace with your actual database code
  try {
    // Establish database connection (replace with your connection details)
    $db = new PDO('mysql:host=localhost;dbname=your_database_name', 'your_username', 'your_password'); // Example
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable exceptions

    // Prepare the SQL statement
    $stmt = $db->prepare("INSERT INTO reviews (product_id, user_name, review_text, rating) VALUES (:product_id, :user_name, :review_text, :rating)");

    // Bind parameters
    $stmt->bindParam(':product_id', $product_id);
    $stmt->bindParam(':user_name', $user_name);
    $stmt->bindParam(':review_text', $review_text);
    $stmt->bindParam(':rating', $rating);

    // Execute the statement
    $stmt->execute();

    return true; // Success
  } catch (PDOException $e) {
    // Handle database errors
    return "Error: Database error - " . $e->getMessage();
  }
}

// Example Usage:
$product_id = "123";
$user_name = "John Doe";
$review_text = "This product is amazing!";
$rating = 5;

$result = submit_review($product_id, $user_name, $review_text, $rating);

if ($result === true) {
  echo "Review submitted successfully!";
} else {
  echo $result;  // Display the error message
}
?>
