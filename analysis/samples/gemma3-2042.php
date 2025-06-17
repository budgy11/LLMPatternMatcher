

<?php

/**
 * User Review Function
 *
 * This function allows you to store and display user reviews for a product.
 * It includes basic input validation and error handling.
 *
 * @param string $product_id The ID of the product the review is for.
 * @param string $username The username of the reviewer.
 * @param string $rating  The rating given by the user (e.g., 1-5 stars).
 * @param string $comment The user's review comment.
 * @param string $db_connection A valid database connection object.
 *
 * @return bool True on success, false on failure.
 */
function storeUserReview(string $product_id, string $username, string $rating, string $comment, PDO $db_connection) {
  // Input Validation -  Crucial for security and data integrity
  if (empty($product_id) || empty($username) || empty($rating) || empty($comment)) {
    error_log("Error: Missing required fields for review."); // Log for debugging - prevent info display
    return false;
  }

  if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
    error_log("Error: Invalid username - only alphanumeric characters and underscores allowed.");
    return false;
  }

  if ($rating < 1 || $rating > 5) {
    error_log("Error: Invalid rating - must be between 1 and 5.");
    return false;
  }
  
  // Sanitize inputs -  Important!  Prevent SQL injection
  $product_id = $db_connection->quote($product_id);
  $username = $db_connection->quote($username);
  $rating = (int)$rating;  //Cast to integer to ensure proper storage
  $comment = $db_connection->quote($comment);

  // SQL Query -  Use prepared statements to prevent SQL injection
  $sql = "INSERT INTO reviews (product_id, username, rating, comment) VALUES (:product_id, :username, :rating, :comment)";
  $stmt = $db_connection->prepare($sql);

  if ($stmt->execute([
    ':product_id' => $product_id,
    ':username' => $username,
    ':rating' => $rating,
    ':comment' => $comment
  ]) === false) {
    error_log("Error: Failed to insert review: " . print_r($stmt->errorInfo(), true)); //Detailed error logging
    return false;
  }

  return true; // Success
}



/**
 * Displays a list of user reviews for a product.
 *
 * @param PDO $db_connection A valid database connection object.
 * @param int $product_id The ID of the product to retrieve reviews for.
 */
function displayUserReviews(PDO $db_connection, int $product_id) {
  // Query to get reviews for a product
  $sql = "SELECT * FROM reviews WHERE product_id = :product_id";
  $stmt = $db_connection->prepare($sql);
  $stmt->bindParam(':product_id', $product_id);
  $stmt->execute();

  $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

  if (empty($reviews)) {
    echo "<p>No reviews yet.</p>";
  } else {
    echo "<h2>Reviews for Product ID: " . $product_id . "</h2>";
    echo "<table>";
    echo "<thead><tr><th>Username</th><th>Rating</th><th>Comment</th></tr></thead>";
    echo "<tbody>";
    foreach ($reviews as $review) {
      echo "<tr>";
      echo "<td>" . htmlspecialchars($review['username']) . "</td>";  // Escape output for security
      echo "<td>" . $review['rating'] . "</td>";
      echo "<td>" . htmlspecialchars($review['comment']) . "</td>";
      echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";
  }
}

// Example Usage (Illustrative - Replace with your database setup)

// Create a dummy PDO connection (replace with your actual connection)
$db_connection = new PDO("mysql:host=localhost;dbname=your_database_name", "username", "password");

// Example Product ID
$product_id = 123;

// Simulate a user review submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $rating = $_POST["rating"];
  $comment = $_POST["comment"];

  if (storeUserReview($product_id, $username, $rating, $comment, $db_connection)) {
    echo "<p>Review submitted successfully!</p>";
  } else {
    echo "<p>Error submitting review.</p>";
  }
}

//Display reviews for the product
displayUserReviews($db_connection, $product_id);

?>
