

<?php

/**
 * User Review Function
 *
 * This function allows you to store, display, and potentially manage user reviews for a product or item.
 *
 * @param string $productId The unique identifier for the product or item being reviewed.
 * @param string $username The username of the reviewer.
 * @param string $rating  The rating given by the user (e.g., 1-5).  Should be validated.
 * @param string $comment The user's review comment.
 * @param array $dbConnection  (Optional) Database connection object.  If not provided, assumes a connection exists.
 *
 * @return array|false  Returns an array with the review ID and success status.  Returns false on error.
 */
function storeUserReview(string $productId, string $username, string $rating, string $comment, $dbConnection = null) {
    // Input Validation - Very important!
    if (empty($productId)) {
        error_log("Error: Product ID cannot be empty."); // Log for debugging
        return false;
    }

    if (empty($username)) {
        error_log("Error: Username cannot be empty.");
        return false;
    }

    if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        error_log("Error: Invalid Username.  Only alphanumeric characters and underscores are allowed.");
        return false;
    }

    if (!preg_match('/^[1-5][0-9]*$/', $rating)) {
        error_log("Error: Invalid Rating.  Must be a number between 1 and 5.");
        return false;
    }

    if (empty($comment)) {
        error_log("Error: Comment cannot be empty.");
        return false;
    }

    if (strlen($comment) > 1000) {
        error_log("Error: Comment exceeds maximum length of 1000 characters.");
        return false;
    }



    // --- Database Interaction ---
    $reviewId = null;
    try {
        if ($dbConnection === null) {
            // Assume a connection is already established (e.g., in a global scope)
            $db = new PDO("mysql:host=localhost;dbname=your_database_name", "your_username", "your_password");
        } else {
            $db = $dbConnection;
        }


        // Prepare the SQL statement
        $stmt = $db->prepare("INSERT INTO reviews (product_id, username, rating, comment) VALUES (:product_id, :username, :rating, :comment)");

        // Bind the parameters
        $stmt->bindParam(':product_id', $productId);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':rating', $rating);
        $stmt->bindParam(':comment', $comment);

        // Execute the statement
        $stmt->execute();

        $reviewId = $db->lastInsertId();

        return ['review_id' => $reviewId, 'success' => true];

    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return false; // Handle database errors appropriately (e.g., display an error message)
    }
}



// Example Usage (Illustrative)
// Assuming you have a database connection object named $db
// $db = new PDO("mysql:host=localhost;dbname=your_database_name", "your_username", "your_password");


// $reviewResult = storeUserReview("123", "john_doe", 4, "Great product!  Highly recommended.", $db);

// if ($reviewResult) {
//     echo "Review submitted successfully! Review ID: " . $reviewResult['review_id'];
// } else {
//     echo "Error submitting review.";
// }

?>
