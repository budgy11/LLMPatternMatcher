

<?php

/**
 * User Review Function
 *
 * This function allows you to create, retrieve, update, and delete user reviews
 * for a specific product.
 *
 * @param string $productId The ID of the product to review.
 * @param string $username The username of the reviewer.
 * @param string $rating The rating given by the user (e.g., 1-5).
 * @param string $comment The user's review comment.
 *
 * @return array  An array containing the result of the operation.  Returns an empty array on failure.
 */
function createReview(string $productId, string $username, string $rating, string $comment)
{
    // Input Validation (Important for security and data integrity)
    if (empty($productId) || empty($username) || empty($rating) || empty($comment)) {
        return []; // Return empty array for invalid input
    }

    if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
        return []; // Invalid rating
    }

    //  Database Connection (Replace with your actual connection details)
    $db = new PDO("mysql:host=localhost;dbname=your_database_name", "your_username", "your_password");

    try {
        // Prepare the SQL statement to prevent SQL injection
        $stmt = $db->prepare("INSERT INTO reviews (product_id, username, rating, comment) 
                             VALUES (:product_id, :username, :rating, :comment)");

        // Bind parameters
        $stmt->bindParam(':product_id', $productId);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':rating', $rating);
        $stmt->bindParam(':comment', $comment);

        // Execute the statement
        $stmt->execute();

        return ['success' => true, 'message' => 'Review created successfully!'];

    } catch (PDOException $e) {
        // Handle database errors
        return ['success' => false, 'message' => 'Error creating review: ' . $e->getMessage()];
    }
}


/**
 * Get Reviews for a Product
 *
 * Retrieves all reviews for a given product.
 *
 * @param string $productId The ID of the product.
 *
 * @return array An array containing the reviews.  Returns an empty array if no reviews are found.
 */
function getReviews(string $productId)
{
    // Database Connection (Replace with your actual connection details)
    $db = new PDO("mysql:host=localhost;dbname=your_database_name", "your_username", "your_password");

    try {
        // Prepare the SQL statement
        $stmt = $db->prepare("SELECT * FROM reviews WHERE product_id = :product_id");
        $stmt->bindParam(':product_id', $productId);
        $stmt->execute();

        // Fetch all results
        $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $reviews;

    } catch (PDOException $e) {
        // Handle database errors
        return [];
    }
}


/**
 * Update a Review
 *
 * Updates an existing review.
 *
 * @param int $reviewId The ID of the review to update.
 * @param string $rating The new rating.
 * @param string $comment The new comment.
 *
 * @return array An array containing the result of the operation.
 */
function updateReview(int $reviewId, string $rating, string $comment)
{
    // Database Connection
    $db = new PDO("mysql:host=localhost;dbname=your_database_name", "your_username", "your_password");

    try {
        $stmt = $db->prepare("UPDATE reviews SET rating = :rating, comment = :comment WHERE id = :id");
        $stmt->bindParam(':id', $reviewId);
        $stmt->bindParam(':rating', $rating);
        $stmt->bindParam(':comment', $comment);

        $stmt->execute();

        return ['success' => true, 'message' => 'Review updated successfully!'];

    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Error updating review: ' . $e->getMessage()];
    }
}

/**
 * Delete a Review
 *
 * Deletes a review.
 *
 * @param int $reviewId The ID of the review to delete.
 *
 * @return array An array containing the result of the operation.
 */
function deleteReview(int $reviewId)
{
    // Database Connection
    $db = new PDO("mysql:host=localhost;dbname=your_database_name", "your_username", "your_password");

    try {
        $stmt = $db->prepare("DELETE FROM reviews WHERE id = :id");
        $stmt->bindParam(':id', $reviewId);
        $stmt->execute();

        return ['success' => true, 'message' => 'Review deleted successfully!'];

    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Error deleting review: ' . $e->getMessage()];
    }
}
?>
