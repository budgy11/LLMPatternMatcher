

<?php

/**
 * User Review Function
 *
 * This function takes a product ID, a user's review, and a rating,
 * and saves them to a database.
 *
 * @param int $product_id The ID of the product being reviewed.
 * @param string $review The user's review text.
 * @param int $rating The user's rating (e.g., 1-5).
 * @param string $db_connection A database connection object.
 *
 * @return bool True if the review was saved successfully, false otherwise.
 */
function save_user_review(int $product_id, string $review, int $rating, PDO $db_connection) {

  // Input validation - crucial for security and data integrity
  if (!is_int($product_id) || $product_id <= 0) {
    error_log("Invalid product_id: " . $product_id); // Log for debugging
    return false;
  }

  if (empty($review)) {
    error_log("Review cannot be empty.");
    return false;
  }

  if (!is_int($rating) || $rating < 1 || $rating > 5) {
    error_log("Invalid rating: " . $rating);
    return false;
  }

  // Sanitize data to prevent SQL injection
  $review = $db_connection->quote($review);  // Use PDO's quote() function for secure escaping

  // Construct the SQL query
  $sql = "INSERT INTO reviews (product_id, review, rating) VALUES (:product_id, :review, :rating)";

  // Prepare the statement
  $stmt = $db_connection->prepare($sql);

  // Bind the parameters
  $stmt->bindParam(':product_id', $product_id);
  $stmt->bindParam(':review', $review);
  $stmt->bindParam(':rating', $rating);

  // Execute the query
  try {
    $stmt->execute();
    return true;
  } catch (PDOException $e) {
    error_log("Error saving review: " . $e->getMessage());
    return false;
  }
}


/**
 * Example Usage (Illustrative - Adapt to your database setup)
 */

// Example Database Connection (Replace with your actual connection)
$host = 'localhost';
$dbname = 'your_database_name';
$user = 'your_database_user';
$password = 'your_database_password';

try {
    $db_connection = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $db_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Example review
$product_id = 123;
$review_text = "This product is fantastic!";
$rating = 5;

if (save_user_review($product_id, $review_text, $rating, $db_connection)) {
  echo "Review saved successfully!";
} else {
  echo "Failed to save review.";
}
?>
