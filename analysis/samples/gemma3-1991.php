

<?php

/**
 * User Review Function
 *
 * This function allows you to store and display user reviews for a product or service.
 *
 * @param string $productId The ID of the product or service being reviewed.
 * @param string $username The username of the reviewer.
 * @param string $rating  The rating given by the reviewer (e.g., 1-5).  Must be an integer.
 * @param string $comment The review comment.
 * @param string $dbConnection  (Optional) - Database connection object.  If not provided, it will attempt to connect.
 *
 * @return array An array containing:
 *   - 'success': True if the review was successfully saved, false otherwise.
 *   - 'message': A message indicating the success or failure of the operation.
 */
function saveUserReview(string $productId, string $username, int $rating, string $comment, $dbConnection = null)
{
  $success = false;
  $message = "Error saving review.";

  // Attempt to connect to the database if no connection is provided.
  if ($dbConnection === null) {
    // Replace with your database connection details
    $dbHost = "localhost";
    $dbName = "your_database_name";
    $dbUser = "your_database_user";
    $dbPassword = "your_database_password";

    try {
      $dbConnection = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4", $dbUser, $dbPassword);
      $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
      error_log("Database connection error: " . $e->getMessage()); // Log the error for debugging
      $message = "Failed to connect to the database.  Check your database credentials.";
    }
  }


  if ($dbConnection) {
    try {
      // Construct the SQL query
      $sql = "INSERT INTO reviews (product_id, user_name, rating, comment)
              VALUES (:product_id, :user_name, :rating, :comment)";

      // Prepare the statement
      $stmt = $dbConnection->prepare($sql);

      // Bind the parameters
      $stmt->bindParam(':product_id', $productId);
      $stmt->bindParam(':user_name', $username);
      $stmt->bindParam(':rating', $rating);
      $stmt->bindParam(':comment', $comment);

      // Execute the query
      $stmt->execute();

      $success = true;
      $message = "Review saved successfully!";

    } catch (PDOException $e) {
      error_log("Database error: " . $e->getMessage());
      $message = "Failed to save review.  Check your database schema and data.";
    }
  }

  return ['success' => $success, 'message' => $message];
}

// Example Usage:

// Assuming you have a database connection object called $conn
//$conn = new PDO("mysql:host=localhost;dbname=your_database_name;charset=utf8mb4", "your_database_user", "your_database_password");
//$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Example 1: Saving a review with a database connection
//$reviewResult = saveUserReview("123", "JohnDoe", 5, "Great product!", $conn);
//print_r($reviewResult);


// Example 2:  Saving a review without explicitly creating a connection
//$reviewResult = saveUserReview("456", "JaneSmith", 3, "It was okay.", null); //Uses default database connection
//print_r($reviewResult);

?>
