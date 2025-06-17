

<?php

/**
 * User Review Function
 *
 * This function allows you to create, read, update, and delete user reviews.
 *
 * @param string $productId The ID of the product the review is for.
 * @param string $userEmail The email address of the user submitting the review.
 * @param string $reviewText The text content of the review.
 * @param int $rating The rating given for the product (e.g., 1-5).
 * @param int $userId (Optional) The ID of the user submitting the review.  If not provided, a new user is created.
 *
 * @return array Returns an array with the following keys:
 *   - 'success': True if the review was created/updated successfully, false otherwise.
 *   - 'message': A message describing the result of the operation.
 *   - 'reviewId': The ID of the newly created review (if success).
 *   - 'review': The review data (if success).
 */
function create_user_review(
    string $productId,
    string $userEmail,
    string $reviewText,
    int $rating,
    int $userId = null // Optional: User ID
) {
    // Input validation (Example - adjust as needed)
    if (empty($productId)) {
        return [
            'success' => false,
            'message' => 'Product ID cannot be empty.',
            'reviewId' => null,
            'review' => null,
        ];
    }

    if (empty($userEmail)) {
        return [
            'success' => false,
            'message' => 'User email cannot be empty.',
            'reviewId' => null,
            'review' => null,
        ];
    }

    if (empty($reviewText)) {
        return [
            'success' => false,
            'message' => 'Review text cannot be empty.',
            'reviewId' => null,
            'review' => null,
        ];
    }

    if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
        return [
            'success' => false,
            'message' => 'Rating must be a number between 1 and 5.',
            'reviewId' => null,
            'review' => null,
        ];
    }


    // ---  Database Interaction - Replace with your actual database logic ---
    // This is a simplified example using placeholders.  You should
    // use prepared statements and proper error handling in a real application.

    $db = new PDO('mysql:host=localhost;dbname=your_database_name', 'your_username', 'your_password');

    try {
        // Check if the user already exists
        $userExists = $db->prepare("SELECT id FROM users WHERE email = :email");
        $userExists->bindParam(':email', $userEmail);
        $userExists->execute();
        $userRow = $userRow->fetch(PDO::FETCH_ASSOC);

        if ($userRow) {
            $userId = $userRow['id']; // Use existing user ID
        } else {
            // Create a new user
            $stmt = $db->prepare("INSERT INTO users (email) VALUES (:email)");
            $stmt->bindParam(':email', $userEmail);
            $stmt->execute();
            $userId = $db->lastInsertId(); // Get the ID of the newly inserted user
        }


        // Create the review
        $stmt = $db->prepare("INSERT INTO reviews (product_id, user_id, review_text, rating) VALUES (:product_id, :user_id, :review_text, :rating)");
        $stmt->bindParam(':product_id', $productId);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':review_text', $reviewText);
        $stmt->bindParam(':rating', $rating);
        $stmt->execute();
        $reviewId = $db->lastInsertId();


        return [
            'success' => true,
            'message' => 'Review created successfully.',
            'reviewId' => $reviewId,
            'review' => [
                'id' => $reviewId,
                'product_id' => $productId,
                'user_id' => $userId,
                'review_text' => $reviewText,
                'rating' => $rating,
                'user_email' => $userEmail, // Include email for easy retrieval
            ],
        ];

    } catch (PDOException $e) {
        // Handle database errors
        return [
            'success' => false,
            'message' => 'Database error: ' . $e->getMessage(),
            'reviewId' => null,
            'review' => null,
        ];
    }
}


// Example Usage:
// $result = create_user_review('123', 'test@example.com', 'This is a great product!', 5);
// print_r($result);

?>
