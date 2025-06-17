

<?php

/**
 * User Review Function
 *
 * This function takes user review data and stores it in a database.
 * It also handles basic validation and error reporting.
 *
 * @param string $username The name of the user submitting the review.
 * @param string $reviewText The content of the review.
 * @param int $product_id The ID of the product the review is for.
 * @param int $rating  The rating provided by the user (1-5).
 * @return bool True on successful submission, false on failure.
 */
function submit_review(string $username, string $reviewText, int $product_id, int $rating) {
  // **Validation** -  Crucial for security and data integrity
  if (empty($username)) {
    error_log("Error: Username cannot be empty.");  // Log the error for admin review
    return false;
  }

  if (empty($reviewText)) {
    error_log("Error: Review text cannot be empty.");
    return false;
  }

  if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
    error_log("Error: Rating must be a number between 1 and 5.");
    return false;
  }

  // **Database Interaction -  Replace with your actual database connection**
  try {
    // Connect to your database (replace with your database connection details)
    $db = new PDO('mysql:host=localhost;dbname=your_database_name', 'your_username', 'your_password');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable exception handling

    // Prepare the SQL statement
    $stmt = $db->prepare("INSERT INTO reviews (user_name, review_text, product_id, rating) VALUES (:username, :review, :prod_id, :rate)");

    // Bind the parameters
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':review', $reviewText);
    $stmt->bindParam(':prod_id', $product_id);
    $stmt->bindParam(':rate', $rating);

    // Execute the statement
    $stmt->execute();

    // Return success
    return true;

  } catch (PDOException $e) {
    // Handle database errors - Log for debugging
    error_log("Database Error: " . $e->getMessage());
    return false;
  }
}

// **Example Usage**
$username = "John Doe";
$reviewText = "This product is amazing! I highly recommend it.";
$product_id = 123;
$rating = 5;

if (submit_review($username, $reviewText, $product_id, $rating)) {
  echo "Review submitted successfully!";
} else {
  echo "Error submitting review.";
}


?>
