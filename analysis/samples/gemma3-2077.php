

<?php

/**
 * User Review Function
 *
 * This function allows you to create, display, and manage user reviews
 * for a given product or item.
 *
 * @param string $product_id The ID of the product or item the review is for.
 * @param string $user_id    The ID of the user submitting the review.
 * @param string $rating    The rating given by the user (e.g., 1-5).
 * @param string $comment   The user's review comment.
 * @param array  $db_connection  Database connection object (optional, if not provided, creates a dummy one).
 *
 * @return array An array containing:
 *   - 'success': True if the review was created successfully, False otherwise.
 *   - 'message': A message indicating the result of the operation.
 *   - 'review_id': The ID of the newly created review, if successful.
 */
function create_user_review(string $product_id, string $user_id, string $rating, string $comment, $db_connection = null)
{
    $success = false;
    $message = '';
    $review_id = null;

    // Database connection (create dummy if not provided)
    if ($db_connection === null) {
        $db_connection = new stdClass(); // Dummy database object
        $db_connection->connection = "localhost";
        $db_connection->username = "your_username";
        $db_connection->password = "your_password";
        $db_connection->database = "your_database";
    }


    try {
        // Validate inputs (basic validation - expand as needed)
        if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
            throw new Exception("Invalid rating. Rating must be a number between 1 and 5.");
        }

        if (empty($comment)) {
            throw new Exception("Comment cannot be empty.");
        }

        // Prepare the SQL query
        $sql = "INSERT INTO reviews (product_id, user_id, rating, comment)
                VALUES (:product_id, :user_id, :rating, :comment)";

        // Prepare the statement
        $stmt = $db_connection->connection->prepare($sql);

        // Bind the parameters
        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':rating', $rating);
        $stmt->bindParam(':comment', $comment);

        // Execute the query
        $stmt->execute();

        // Get the ID of the newly created review
        $review_id = $db_connection->connection->lastInsertId();


        $success = true;
        $message = "Review created successfully!";

    } catch (Exception $e) {
        $message = "Error creating review: " . $e->getMessage();
        error_log($e->getMessage()); // Log the error for debugging
    }

    return ['success' => $success, 'message' => $message, 'review_id' => $review_id];
}


// Example Usage:
//  This is just for demonstration, replace with your actual database connection details.

// Sample database setup (for demonstration)
//  In a real application, you would connect to your actual database.
// $db_connection = new PDO("mysql:host=localhost;dbname=your_database", "your_username", "your_password");

$review_data = create_user_review(
    'product123',
    'user456',
    3,
    'Great product!  Highly recommended.',
    $db_connection // Pass your database connection object
);

if ($review_data['success']) {
    echo "Review created with ID: " . $review_data['review_id'] . "<br>";
    echo "Message: " . $review_data['message'] . "<br>";
} else {
    echo "Failed to create review. Message: " . $review_data['message'] . "<br>";
}
?>
