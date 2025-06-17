

<?php

/**
 * User Review Function
 *
 * This function allows you to create, retrieve, update, and delete user reviews.
 * It provides basic data sanitization and validation.
 *
 * @param string $productId The ID of the product being reviewed.
 * @param string $user_id The ID of the user submitting the review.
 * @param string $rating The rating given by the user (e.g., 1-5).
 * @param string $comment The user's review comment.
 * @param array  $db_connection  Database connection object (optional, for external connection)
 *
 * @return array An array containing:
 *   - 'success': True if the operation was successful, False otherwise.
 *   - 'message': A message indicating the result of the operation.
 *   - 'review_id': The ID of the newly created review (if creation was successful).
 *   - 'review': The review object (if creation or update was successful).
 */
function create_user_review(
    string $productId,
    string $user_id,
    string $rating,
    string $comment,
    array $db_connection = null // Optional database connection
) {
    // Validation and Sanitization (Important for security!)
    $productId = filter_var($productId, FILTER_SANITIZE_STRING);
    $user_id = filter_var($user_id, FILTER_SANITIZE_STRING);
    $rating = filter_var($rating, FILTER_VALIDATE_INT, array("min" => 1, "max" => 5));
    $comment = filter_var($comment, FILTER_SANITIZE_STRING);

    if (empty($productId) || empty($user_id) || empty($rating) || empty($comment)) {
        return [
            'success' => false,
            'message' => 'Missing required fields.',
            'review_id' => null,
            'review' => null,
        ];
    }

    // Prepare the SQL query
    $sql = "INSERT INTO reviews (product_id, user_id, rating, comment) VALUES (?, ?, ?, ?)";

    // Prepare the statement
    $stmt = $db_connection->prepare($sql);

    if (!$stmt) {
        return [
            'success' => false,
            'message' => 'Error preparing statement.',
            'review_id' => null,
            'review' => null,
        ];
    }

    // Bind parameters
    $stmt->bind_param("sii", $productId, $user_id, $rating);

    // Execute the query
    if (!$stmt->execute()) {
        return [
            'success' => false,
            'message' => 'Error executing query: ' . $stmt->error,
            'review_id' => null,
            'review' => null,
        ];
    }

    // Get the last inserted ID
    $review_id = $db_connection->insert_id;

    // Get the newly created review (optional - can be returned separately if needed)
    $review = [
        'review_id' => $review_id,
        'product_id' => $productId,
        'user_id' => $user_id,
        'rating' => $rating,
        'comment' => $comment,
    ];

    // Close the statement
    $stmt->close();

    return [
        'success' => true,
        'message' => 'Review created successfully.',
        'review_id' => $review_id,
        'review' => $review,
    ];
}


/**
 * Example Usage (Illustrative - assumes a database connection is already established)
 */
// Example Database Connection (Replace with your actual connection details)
$db_connection = new mysqli("localhost", "username", "password", "database_name");

if ($db_connection->connect_error) {
    die("Connection failed: " . $db_connection->connect_error);
}

// Create a review
$review_result = create_user_review(
    "123", // Product ID
    "user123", // User ID
    3,       // Rating
    "Great product!", // Comment
);

if ($review_result['success']) {
    echo "Review created successfully! ID: " . $review_result['review_id'] . "<br>";
} else {
    echo "Error creating review: " . $review_result['message'] . "<br>";
}
// Close the connection (Important!)
$db_connection->close();

?>
