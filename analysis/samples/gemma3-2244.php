

<?php

/**
 * User Review Function
 *
 * This function handles the creation, retrieval, and updating of user reviews.
 *
 * @param string $productId The ID of the product the review is for.
 * @param string $userId The ID of the user submitting the review.
 * @param string $rating The rating given by the user (e.g., 1-5).
 * @param string $comment The user's review comment.
 * @param int $reviewId (Optional) The ID of the review to update. If not provided,
 *                          a new review is created.
 *
 * @return array An array containing the review ID (if a new review was created)
 *               or the updated review ID, or an error message if something went wrong.
 */
function createOrUpdateReview(string $productId, string $userId, string $rating, string $comment, int $reviewId = 0)
{
    // Input Validation - VERY IMPORTANT
    if (empty($productId) || empty($userId) || empty($rating) || empty($comment)) {
        return ['error' => 'All fields are required.'];
    }

    if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
        return ['error' => 'Rating must be a number between 1 and 5.'];
    }

    // Database connection (replace with your actual connection details)
    $db = new PDO('mysql:host=localhost;dbname=your_database_name', 'your_username', 'your_password');

    try {
        // Prepare the SQL query
        $sql = "INSERT INTO reviews (product_id, user_id, rating, comment) VALUES (:product_id, :user_id, :rating, :comment) 
                ON DUPLICATE KEY UPDATE comment = :comment";

        // Prepare the statement
        $stmt = $db->prepare($sql);

        // Bind the parameters
        $stmt->bindParam(':product_id', $productId);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':rating', $rating);
        $stmt->bindParam(':comment', $comment);

        // Execute the statement
        $stmt->execute();

        // Return the review ID if a new review was created, otherwise return the updated ID.
        if ($reviewId == 0) {
            $reviewId = $db->lastInsertId();
            return $reviewId;
        } else {
            return $reviewId;
        }


    } catch (PDOException $e) {
        // Handle database errors
        error_log("Database Error: " . $e->getMessage()); // Log for debugging
        return ['error' => 'Database error: ' . $e->getMessage()];
    }
}

// Example Usage:

// Create a new review
$newReviewId = createOrUpdateReview('product123', 'user456', '4', 'Great product!');

if (isset($newReviewId) && $newReviewId > 0) {
    echo "New review created with ID: " . $newReviewId . "<br>";
} else {
    echo "Error creating new review.<br>";
    if (isset($_POST['error'])) {
        echo "Error: " . $_POST['error'] . "<br>";
    }
}

// Update an existing review
$updatedReviewId = createOrUpdateReview('product123', 'user456', '5', 'Even better than I thought!');

if (isset($updatedReviewId)) {
    echo "Review updated with ID: " . $updatedReviewId . "<br>";
} else {
    echo "Error updating review.<br>";
    if (isset($_POST['error'])) {
        echo "Error: " . $_POST['error'] . "<br>";
    }
}

// Example form (for submitting reviews)
?>
