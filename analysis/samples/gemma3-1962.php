

<?php

/**
 * User Review Function
 *
 * This function allows you to store and display user reviews for a given item.
 *
 * @param string $item_id  The ID of the item being reviewed.  Used to identify the review.
 * @param string $user_name The name of the user submitting the review.
 * @param string $review_text The text of the review submitted by the user.
 * @param int    $rating   The rating given by the user (e.g., 1-5 stars).
 * @param int    $db_connection  An established database connection object.
 *
 * @return array An array containing the success status and a message.
 */
function storeUserReview(
    string $item_id,
    string $user_name,
    string $review_text,
    int    $rating,
    $db_connection
) {
    // Input validation - important for security and data integrity
    if (empty($item_id)) {
        return ['success' => false, 'message' => 'Item ID cannot be empty.'];
    }
    if (empty($user_name)) {
        return ['success' => false, 'message' => 'User name cannot be empty.'];
    }
    if (empty($review_text)) {
        return ['success' => false, 'message' => 'Review text cannot be empty.'];
    }
    if ($rating < 1 || $rating > 5) {
        return ['success' => false, 'message' => 'Rating must be between 1 and 5.'];
    }

    // Prepare the SQL query
    $sql = "INSERT INTO reviews (item_id, user_name, review_text, rating)
            VALUES (?, ?, ?, ?)";

    // Prepare the statement
    $stmt = $db_connection->prepare($sql);

    if ($stmt === false) {
        // Handle database error - crucial for debugging
        return ['success' => false, 'message' => 'Database error preparing statement: ' . $db_connection->errorInfo()[2]];
    }

    // Bind the parameters
    $stmt->bind_param("ssis", $item_id, $user_name, $review_text, $rating);

    // Execute the statement
    if (!$stmt->execute()) {
        // Handle database error
        return ['success' => false, 'message' => 'Error executing statement: ' . $db_connection->errorInfo()[2]];
    }

    // Close the statement
    $stmt->close();

    return ['success' => true, 'message' => 'Review submitted successfully!'];
}

/**
 * Example usage:
 */

// Assuming you have a database connection named $conn

// Example 1: Successful Submission
$review_result = storeUserReview(
    'product_123',
    'John Doe',
    'Great product! I highly recommend it.',
    4,
    $conn
);
echo "<pre>";
print_r($review_result);
echo "</pre>";

// Example 2:  Failed due to empty review text
$review_result = storeUserReview(
    'product_456',
    'Jane Smith',
    '',
    3,
    $conn
);
echo "<pre>";
print_r($review_result);
echo "</pre>";


?>
