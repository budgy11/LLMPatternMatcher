

<?php

/**
 * User Review Function
 *
 * This function allows you to store user reviews for a given item (e.g., product, movie, etc.).
 *
 * @param string $item_id The unique identifier of the item being reviewed.
 * @param string $user_id The unique identifier of the user submitting the review.
 * @param string $rating The rating given by the user (e.g., 1-5 stars).
 * @param string $comment The user's comment or review text.
 * @param string $db_connection  (Optional) -  Database connection object. If not provided, attempts to create one.
 *
 * @return bool True if the review was successfully added, false otherwise.
 */
function add_user_review(string $item_id, string $user_id, string $rating, string $comment, $db_connection = null) {

  // Validate inputs (you can expand this with more rigorous validation)
  if (empty($item_id) || empty($user_id) || empty($rating) || empty($comment)) {
    return false;
  }
  if (!is_numeric($rating) || (int)$rating < 1 || (int)$rating > 5) { // Check for valid rating
    return false;
  }


  // Use provided database connection, or create one if none is provided
  if ($db_connection === null) {
    // Attempt to connect to a database (adjust connection details)
    $db_connection = new PDO("mysql:host=localhost;dbname=your_database_name", "your_username", "your_password");
  }


  try {
    // Prepare the SQL statement
    $stmt = $db_connection->prepare(
      "INSERT INTO reviews (item_id, user_id, rating, comment)
       VALUES (:item_id, :user_id, :rating, :comment)"
    );

    // Bind parameters (safer than string concatenation)
    $stmt->bindParam(':item_id', $item_id);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':rating', $rating);
    $stmt->bindParam(':comment', $comment);

    // Execute the statement
    $result = $stmt->execute();

    // Check for success
    return $result;

  } catch (PDOException $e) {
    // Handle database errors (e.g., log the error, display a generic message)
    error_log("Error adding review: " . $e->getMessage()); // Log the error
    return false;
  }
}


// Example Usage:
$item_id = "product123";
$user_id = "user456";
$rating = 4;
$comment = "Great product!  Works as expected.";

if (add_user_review($item_id, $user_id, $rating, $comment)) {
  echo "Review successfully added!
";
} else {
  echo "Failed to add review.
";
}


// Example with a database connection already established (for testing/demo)
// (In a real application, this would come from your application's setup)
$db = new PDO("mysql:host=localhost;dbname=your_database_name", "your_username", "your_password");

if (add_user_review($item_id, $user_id, $rating, $comment, $db)) {
  echo "Review successfully added (using existing connection)!
";
} else {
  echo "Failed to add review (using existing connection).
";
}


?>
