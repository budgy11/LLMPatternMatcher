

<?php

/**
 * User Review Function
 *
 * This function allows you to create, read, update, and delete user reviews for a product or item.
 *
 * @param string $productId The ID of the product or item the review is for.
 * @param string $userEmail The email address of the user submitting the review.
 * @param string $reviewText The text content of the review.
 * @param int $rating  The rating given for the product (1-5).
 * @param int $userId (Optional) The ID of the user submitting the review.  If not provided, an ID will be generated.
 *
 * @return array An array containing:
 *   - 'success': True if the review was created successfully, False otherwise.
 *   - 'message': A message indicating the outcome of the operation.
 *   - 'reviewId': The ID of the newly created review (if successful), or null.
 */
function createReview(string $productId, string $userEmail, string $reviewText, int $rating, int $userId = null): array
{
    // Validation (Example - you should expand this)
    if (empty($productId)) {
        return ['success' => false, 'message' => 'Product ID cannot be empty.', 'reviewId' => null];
    }
    if (empty($userEmail)) {
        return ['success' => false, 'message' => 'User email cannot be empty.', 'reviewId' => null];
    }
    if (empty($reviewText)) {
        return ['success' => false, 'message' => 'Review text cannot be empty.', 'reviewId' => null];
    }
    if ($rating < 1 || $rating > 5) {
        return ['success' => false, 'message' => 'Rating must be between 1 and 5.', 'reviewId' => null];
    }

    // Database interaction (Replace with your actual database connection)
    try {
        // Simulate database connection and insertion
        $db = new PDO('mysql:host=localhost;dbname=your_database_name', 'your_db_user', 'your_db_password'); // Replace with your credentials
        $stmt = $db->prepare("INSERT INTO reviews (productId, userEmail, reviewText, rating, userId) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$productId, $userEmail, $reviewText, $rating, $userId]);
        $reviewId = $db->lastInsertId();

        return ['success' => true, 'message' => 'Review created successfully.', 'reviewId' => $reviewId];

    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Database error: ' . $e->getMessage(), 'reviewId' => null];
    }
}


// Example Usage:
$productId = '123';
$userEmail = 'test@example.com';
$reviewText = 'This is a fantastic product!';
$rating = 4;

$result = createReview($productId, $userEmail, $reviewText, $rating);

if ($result['success']) {
    echo "Review created successfully! Review ID: " . $result['reviewId'] . "<br>";
} else {
    echo "Error creating review: " . $result['message'] . "<br>";
}

?>
