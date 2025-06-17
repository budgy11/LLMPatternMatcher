

<?php

/**
 * User Review Function
 *
 * This function allows you to store and display user reviews for a product or item.
 *
 * @param string $product_id The unique identifier for the product.
 * @param string $username The name of the user submitting the review.
 * @param string $rating The rating given by the user (e.g., 1-5).
 * @param string $comment The user's review text.
 * @param string $db_connection (Optional) - A database connection object. Defaults to null.
 *
 * @return array|false An array containing the review ID, success status, and a message.
 *                     Returns false on error.
 */
function create_user_review(string $product_id, string $username, string $rating, string $comment, $db_connection = null)
{
  // Input Validation (Basic - Enhance as needed)
  if (empty($product_id) || empty($username) || empty($rating) || empty($comment)) {
    return false; // Return false for empty inputs
  }

  if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
    return false; // Invalid rating
  }

  // Database connection - Use a real connection in a production environment
  if ($db_connection === null) {
    // Placeholder for database connection - Implement your database connection logic here
    // This example uses a fake connection object
    $db_connection = new stdClass();
    $db_connection->connect = function() use ($db_connection) {
      // Simulate a successful connection
      return true;
    };
  }

  try {
    // Connect to the database (using the fake connection here)
    $db_connection->connect();

    // Prepare the SQL statement
    $sql = "INSERT INTO reviews (product_id, username, rating, comment) VALUES (?, ?, ?, ?)";

    // Prepare the statement (more secure than using echo/print)
    $stmt = $db_connection->prepare($sql); // Assumes $db_connection has a prepare() method

    // Bind the parameters
    $stmt->bind_param("siss", $product_id, $username, $rating, $comment);

    // Execute the statement
    $result = $stmt->execute();

    // Check for errors
    if ($result === false) {
      // Handle database errors (e.g., log errors)
      error_log("Database error: " . $stmt->error);
      return false;
    }

    // Get the ID of the newly inserted row
    $review_id = $db_connection->lastInsertId();

    // Close the statement
    $stmt->close();

    return ['review_id' => $review_id, 'success' => true, 'message' => "Review submitted successfully!"];

  } catch (Exception $e) {
    // Handle exceptions (e.g., connection errors)
    error_log("Exception: " . $e->getMessage());
    return false;
  } finally {
    // Close the database connection - Important to release resources
    if (isset($db_connection) && $db_connection->close) {
        $db_connection->close();
    }
  }
}



// Example Usage (Illustrative - Requires database setup)
// Assuming you have a 'reviews' table in your database with columns:
// product_id (string), username (string), rating (int), comment (text)

// Create a sample review
$review = create_user_review("prod123", "John Doe", 4, "Great product, highly recommended!", null);

if ($review) {
  echo "Review created successfully. Review ID: " . $review['review_id'] . "<br>";
  echo "Status: " . ($review['success'] ? "Success" : "Failed") . "<br>";
  echo "Message: " . $review['message'] . "<br>";
} else {
  echo "Error creating review. Check logs for details.";
}

?>
