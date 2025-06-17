

<?php

/**
 * User Review Function
 *
 * This function allows you to create, read, update, and delete user reviews.
 * It demonstrates basic database interactions and data sanitization.
 *
 * @param string $product_id The ID of the product the review is for.
 * @param string $user_id The ID of the user writing the review.
 * @param string $rating The rating given by the user (e.g., 1-5).
 * @param string $comment The user's comment.
 * @param string $db_connection A database connection object.
 *
 * @return array An array containing the result of the operation (e.g., success/failure message).
 */
function create_user_review(string $product_id, string $user_id, string $rating, string $comment, PDO $db_connection)
{
    // Validate and sanitize inputs (important for security!)
    $product_id = filter_var($product_id, FILTER_SANITIZE_STRING);
    $user_id = filter_var($user_id, FILTER_SANITIZE_STRING);
    $rating = filter_var($rating, FILTER_VALIDATE_INT); // Ensure rating is an integer
    $comment = filter_var($comment, FILTER_SANITIZE_STRING);

    // Check if validation passed
    if (empty($product_id) || empty($user_id) || empty($rating) || empty($comment)) {
        return ['success' => false, 'message' => 'All fields are required.'];
    }

    // Validate rating range (example: 1-5)
    if ($rating < 1 || $rating > 5) {
        return ['success' => false, 'message' => 'Rating must be between 1 and 5.'];
    }

    // Data preparation for SQL query
    $sql = "INSERT INTO reviews (product_id, user_id, rating, comment) VALUES (:product_id, :user_id, :rating, :comment)";
    $stmt = $db_connection->prepare($sql);

    // Bind parameters
    $stmt->bindParam(':product_id', $product_id);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':rating', $rating);
    $stmt->bindParam(':comment', $comment);

    // Execute the query
    if ($stmt->execute()) {
        return ['success' => true, 'message' => 'Review created successfully!'];
    } else {
        return ['success' => false, 'message' => 'Error creating review: ' . print_r($stmt->errorInfo(), true)];
    }
}


// Example usage (assuming you have a database connection established)
// This is just an example; you'll need to replace with your actual database connection.
//  and the table setup.

/*
$db_connection = new PDO("mysql:host=localhost;dbname=your_database_name", "username", "password");

// Example: Creating a review
$review_result = create_user_review('123', '456', 4, 'Great product!', $db_connection);
print_r($review_result);

// Example: Error handling
$review_result = create_user_review('', '456', 4, 'Great product!', $db_connection);
print_r($review_result);
*/

?>
