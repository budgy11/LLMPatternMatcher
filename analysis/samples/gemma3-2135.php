

<?php

/**
 * User Review Function
 *
 * This function creates and stores a user review in a database.
 *
 * @param string $username The name of the user submitting the review.
 * @param string $rating A rating from 1 to 5.
 * @param string $comment The user's review comment.
 * @param int $product_id The ID of the product being reviewed.
 * @return bool True if the review was created successfully, false otherwise.
 */
function createReview(string $username, string $rating, string $comment, int $product_id)
{
    // Validate Input - Important for security and data integrity
    if (empty($username) || empty($rating) || empty($comment) || $product_id <= 0) {
        error_log("Invalid review data received: Username, rating, and comment cannot be empty, product ID must be positive.");  // Log for debugging
        return false;
    }

    if (!preg_match('/^[a-zA-Z\s]+$/', $comment)) {
        error_log("Invalid comment format.  Only letters and spaces are allowed.");
        return false;
    }

    if (!preg_match('/^[1-5]$/', $rating)) {
      error_log("Rating must be a number between 1 and 5.");
      return false;
    }

    // Database connection (Replace with your actual database credentials)
    $db_host = 'localhost';
    $db_user = 'your_db_user';
    $db_password = 'your_db_password';
    $db_name = 'your_db_name';

    try {
        $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERR_EXCEPTION);
    } catch (PDOException $e) {
        error_log("Database connection failed: " . $e->getMessage());
        return false;
    }

    // Prepare the SQL query
    $sql = "INSERT INTO reviews (username, rating, comment, product_id) VALUES (:username, :rating, :comment, :product_id)";

    // Prepare the statement
    $stmt = $pdo->prepare($sql);

    // Bind the parameters
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':rating', $rating);
    $stmt->bindParam(':comment', $comment);
    $stmt->bindParam(':product_id', $product_id);

    // Execute the query
    try {
        $stmt->execute();
        return true;
    } catch (PDOException $e) {
        error_log("Error executing SQL query: " . $e->getMessage());
        return false;
    } finally {
        $pdo = null; // Close the database connection
    }
}


// Example Usage (for demonstration purposes - don't use directly in your application)
// $success = createReview("John Doe", 5, "Great product! Highly recommend.", 123);
// if ($success) {
//     echo "Review created successfully!";
// } else {
//     echo "Failed to create review.";
// }

?>
