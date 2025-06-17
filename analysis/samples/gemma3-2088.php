

<?php

/**
 * User Review Function
 *
 * This function allows users to submit and display reviews.
 *
 * @param string $productId The ID of the product being reviewed.
 * @param string $username   The username of the reviewer.
 * @param string $rating    The rating (e.g., 1-5).
 * @param string $comment   The review text.
 *
 * @return array|string  An array containing the review data, or a string if an error occurred.
 */
function createReview(string $productId, string $username, string $rating, string $comment) {
  // Input validation - Basic example, expand as needed
  if (empty($productId) || empty($username) || empty($rating) || empty($comment)) {
    return "Error: All fields are required.";
  }

  if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
    return "Error: Rating must be a number between 1 and 5.";
  }

  // Data sanitization - Use proper escaping for database safety
  $productId = filter_var($productId, FILTER_SANITIZE_STRING); // Escape special chars
  $username = filter_var($username, FILTER_SANITIZE_STRING);
  $rating = (int)$rating; // Convert to integer
  $comment = filter_var($comment, FILTER_SANITIZE_STRING);


  // --- Database interaction -  Replace with your database connection and query
  // This is a placeholder, and you need to adapt it to your setup.

  $db_host = "localhost";
  $db_name = "your_database_name";
  $db_user = "your_username";
  $db_password = "your_password";


  try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  } catch (PDOException $e) {
    return "Error connecting to database: " . $e->getMessage();
  }


  $stmt = $pdo->prepare("INSERT INTO reviews (product_id, username, rating, comment) VALUES (:product_id, :username, :rating, :comment)");
  $stmt->bindParam(':product_id', $productId);
  $stmt->bindParam(':username', $username);
  $stmt->bindParam(':rating', $rating);
  $stmt->bindParam(':comment', $comment);

  if ($stmt->execute()) {
    return "Review submitted successfully!";
  } else {
    return "Error submitting review: " . $stmt->errorInfo()[2];
  }

  // --- End Database Interaction ---
}

// Example Usage (for demonstration - not integrated with a database)
// $reviewResult = createReview("123", "JohnDoe", 4, "Great product!");
// echo $reviewResult; // Output:  "Review submitted successfully!"  (if successful)

// OR

// $reviewResult = createReview("456", "JaneSmith", "Two", "Okay, but could be better.");
// echo $reviewResult; // Output: "Error: Rating must be a number between 1 and 5."
?>
