

<?php

/**
 * User Review Function
 *
 * This function allows you to create, read, update, and delete user reviews.
 * It provides basic functionality for managing reviews and includes error handling.
 *
 * @param string $productId The ID of the product the review is for.
 * @param string $username The username of the reviewer.
 * @param string $rating  The rating (e.g., 1-5).
 * @param string $comment The review comment.
 * @param string $reviewId (Optional) The ID of the review to update.  If not provided, a new review is created.
 * @param string $dbConnection  A database connection object.
 *
 * @return array An array containing:
 *   - 'success': true if the operation was successful, false otherwise.
 *   - 'message': A message indicating the outcome of the operation.
 *   - 'reviewId': The ID of the newly created or updated review.
 *
 */
function create_update_review(
    $productId,
    $username,
    $rating,
    $comment,
    $reviewId = null,
    $dbConnection
) {
    $success = false;
    $message = '';
    $reviewId = null;


    // Validate inputs (basic validation - you should add more robust validation)
    if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
        return ['success' => false, 'message' => 'Invalid rating. Rating must be a number between 1 and 5.', 'reviewId' => null];
    }
    if (empty($username)) {
        return ['success' => false, 'message' => 'Username cannot be empty.', 'reviewId' => null];
    }
    if (empty($comment)) {
        return ['success' => false, 'message' => 'Comment cannot be empty.', 'reviewId' => null];
    }


    // Prepare the SQL query
    $sql = "INSERT INTO reviews (productId, username, rating, comment) VALUES (?, ?, ?, ?) ";

    if ($reviewId !== null) {
        $sql .= " ON DUPLICATE KEY UPDATE comment = ? "; // Update existing review
    }

    $stmt = $dbConnection->prepare($sql);

    if ($stmt === false) {
        return ['success' => false, 'message' => 'Database preparation error.', 'reviewId' => null];
    }

    $values = array($productId, $username, $rating, $comment);

    if ($stmt->execute($values)) {
        $reviewId = $dbConnection->lastInsertId(); // Get the ID of the newly inserted row
        $success = true;
        $message = 'Review created/updated successfully.';
    } else {
        $success = false;
        $message = 'Error creating/updating review: ' . print_r($stmt->error, true);
    }

    return ['success' => $success, 'message' => $message, 'reviewId' => $reviewId];
}


// Example usage (assuming you have a database connection)
//  You'll need to replace this with your actual database connection
//  and create a table named 'reviews' with columns:
//  - productId (INT)
//  - username (VARCHAR)
//  - rating (INT)
//  - comment (TEXT)
//  - id (INT, PRIMARY KEY, AUTO_INCREMENT)

/*
$dbConnection = new PDO('mysql:host=localhost;dbname=your_database', 'your_username', 'your_password');

$reviewData = create_update_review(
    123,  // Product ID
    'john_doe', // Username
    4,   // Rating
    'Great product! I highly recommend it.',
    null  // No review ID to update
);

if ($reviewData['success']) {
    echo "Review created with ID: " . $reviewData['reviewId'] . "<br>";
} else {
    echo "Error: " . $reviewData['message'] . "<br>";
}
*/

?>
