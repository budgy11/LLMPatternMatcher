

<?php

/**
 * User Review Function
 *
 * This function allows you to create and manage user reviews for a given product/item.
 * It includes basic validation, saving the review to a database, and
 * generates a unique ID for the review.
 *
 * @param string $product_id  The ID of the product/item the review is for.
 * @param string $user_name   The name of the user submitting the review.
 * @string $rating         The rating given by the user (e.g., 1-5 stars).
 * @string $comment        The user's review comment.
 * @return int|false  The ID of the newly created review, or false on failure.
 */
function create_review(string $product_id, string $user_name, string $rating, string $comment) {

  // Validation: Simple checks to ensure data integrity.  Expand as needed.
  if (empty($product_id) || empty($user_name) || empty($rating) || empty($comment)) {
    return false; // Return false for invalid input
  }

  if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
    return false; // Ensure rating is within a valid range
  }

  // ***  Database Interaction (Replace with your actual database connection details) ***
  // Assuming you have a database connection established named $dbConnection
  // and a 'reviews' table with columns: id (INT, PRIMARY KEY), product_id (INT), user_name (VARCHAR), rating (INT), comment (TEXT)

  try {
    // Prepare the SQL statement
    $sql = "INSERT INTO reviews (product_id, user_name, rating, comment)
            VALUES (:product_id, :user_name, :rating, :comment)";

    // Prepare the statement
    $stmt = $dbConnection->prepare($sql);

    // Bind the parameters
    $stmt->bindParam(':product_id', $product_id);
    $stmt->bindParam(':user_name', $user_name);
    $stmt->bindParam(':rating', $rating);
    $stmt->bindParam(':comment', $comment);

    // Execute the statement
    $result = $stmt->execute();

    // Get the last inserted ID
    $review_id = $dbConnection->lastInsertId();

    // Close the statement
    $stmt->close();

    return $review_id;

  } catch (PDOException $e) {
    // Handle database errors (e.g., connection issues, SQL syntax errors)
    error_log("Database Error: " . $e->getMessage()); // Log the error for debugging
    return false;
  }
}


// Example Usage (Assuming you have a $dbConnection object)
// In a real application, $dbConnection would be established elsewhere (e.g., through a configuration file).

//  Create a dummy database connection for demonstration purposes
//$dbConnection = new PDO("mysql:host=localhost;dbname=your_database_name", "username", "password");

// Example review creation
$product_id = "123";
$user_name = "John Doe";
$rating = 4;
$comment = "Great product, highly recommended!";

$review_id = create_review($product_id, $user_name, $rating, $comment);

if ($review_id) {
  echo "Review created successfully! Review ID: " . $review_id . "
";
} else {
  echo "Failed to create review.
";
}


?>
