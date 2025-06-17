

<?php

/**
 * User Review Function
 *
 * This function allows you to create, read, update, and delete user reviews.
 * It includes basic validation and error handling.
 *
 * @param string $productId The ID of the product being reviewed.
 * @param string $userEmail The email address of the user submitting the review.
 * @param string $rating   The rating given by the user (e.g., 1-5).
 * @param string $comment  The user's review comment.
 * @param array $dbConnection  A database connection object. (Optional, for database interaction)
 *
 * @return array An array containing the results:
 *   - 'success': True if the operation was successful, False otherwise.
 *   - 'message': A message indicating the outcome of the operation.
 *   - 'data': The review data (if successful).
 */
function createReview(
    string $productId,
    string $userEmail,
    string $rating,
    string $comment,
    array $dbConnection = null // Allow passing in a database connection
) {
    // Basic Validation
    if (empty($productId) || empty($userEmail) || empty($rating) || empty($comment)) {
        return [
            'success' => false,
            'message' => 'All fields are required.',
            'data' => null,
        ];
    }

    if (!filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
        return [
            'success' => false,
            'message' => 'Invalid email format.',
            'data' => null,
        ];
    }

    if (!preg_match('/^[1-5]+$/', $rating)) {
        return [
            'success' => false,
            'message' => 'Rating must be a number between 1 and 5.',
            'data' => null,
        ];
    }

    // Example: Storing in a database (adjust to your database schema)
    try {
        $stmt = $dbConnection->prepare(
            "INSERT INTO reviews (product_id, user_email, rating, comment, created_at)
             VALUES (:product_id, :user_email, :rating, :comment, NOW())"
        );

        $stmt->bindParam(':product_id', $productId);
        $stmt->bindParam(':user_email', $userEmail);
        $stmt->bindParam(':rating', $rating);
        $stmt->bindParam(':comment', $comment);

        $stmt->execute();
        $reviewId = $stmt->lastInsertId();

        return [
            'success' => true,
            'message' => 'Review created successfully.',
            'data' => [
                'review_id' => $reviewId,
                'product_id' => $productId,
                'user_email' => $userEmail,
                'rating' => $rating,
                'comment' => $comment,
                'created_at' => date('Y-m-d H:i:s'), //format for display
            ],
        ];

    } catch (Exception $e) {
        // Handle database errors
        return [
            'success' => false,
            'message' => 'Database error: ' . $e->getMessage(),
            'data' => null,
        ];
    }
}


// Example Usage (Simulated Database Connection)
// This example assumes you have a database connection object named $dbConnection
// In a real application, you would obtain this connection from your framework or connection library.

//Simulated database connection for testing
//$dbConnection = new PDO("mysql:host=localhost;dbname=your_database_name", "your_username", "your_password");

// Example 1: Successful Review Creation
$reviewData = createReview(
    '123',
    'test@example.com',
    '4',
    'Great product!',
    $dbConnection // Pass the database connection
);

print_r($reviewData);

// Example 2: Error - Invalid Email Format
$reviewData = createReview(
    '456',
    'invalid-email',
    '3',
    'Bad review',
    $dbConnection
);

print_r($reviewData);
?>
