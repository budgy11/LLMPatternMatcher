

<?php

/**
 * User Review Function
 *
 * This function allows you to create, read, update, and delete user reviews.
 * It utilizes a simple database to store reviews.  For a production environment, 
 * you'd want a more robust database and potentially input validation and sanitization.
 *
 * @param string $product_id The ID of the product the review is for.
 * @param string $user_id    The ID of the user submitting the review.
 * @param string $rating     The rating given (e.g., 1-5).
 * @param string $comment    The review text.
 * @return array          An array containing the review ID, success status, and optional error message.
 */
function createReview(string $product_id, string $user_id, string $rating, string $comment)
{
    // Database connection (Replace with your actual database connection)
    $db = new PDO('mysql:host=localhost;dbname=your_database_name', 'your_username', 'your_password');

    // Input Validation (Basic example - enhance for production)
    if (!preg_match('/^[1-5][0-9]*$/', $rating)) {
        return ['id' => null, 'success' => false, 'message' => 'Invalid rating. Rating must be a number between 1 and 5.'];
    }
    if (empty($comment)) {
        return ['id' => null, 'success' => false, 'message' => 'Comment cannot be empty.'];
    }

    try {
        // Prepare the SQL statement
        $stmt = $db->prepare("INSERT INTO reviews (product_id, user_id, rating, comment) VALUES (?, ?, ?, ?)");

        // Bind parameters to prevent SQL injection
        $stmt->bindParam(1, $product_id);
        $stmt->bindParam(2, $user_id);
        $stmt->bindParam(3, $rating);
        $stmt->bindParam(4, $comment);

        // Execute the statement
        $stmt->execute();

        // Get the ID of the inserted row
        $review_id = $db->lastInsertId();

        return ['id' => $review_id, 'success' => true, 'message' => 'Review created successfully.'];

    } catch (PDOException $e) {
        // Handle database errors
        return ['id' => null, 'success' => false, 'message' => 'Database error: ' . $e->getMessage()];
    }
}


/**
 * Retrieve a single review
 *
 * @param int $review_id The ID of the review to retrieve.
 * @return array An array containing the review data or an empty array if not found.
 */
function getReview(int $review_id) {
  // Database connection (Replace with your actual database connection)
  $db = new PDO('mysql:host=localhost;dbname=your_database_name', 'your_username', 'your_password');

  try {
    $stmt = $db->prepare("SELECT * FROM reviews WHERE id = ?");
    $stmt->bindParam(1, $review_id);
    $stmt->execute();

    $review = $stmt->fetch(PDO::FETCH_ASSOC);

    return $review ?: []; // Return empty array if no review found
  } catch (PDOException $e) {
    return ['id' => null, 'success' => false, 'message' => 'Database error: ' . $e->getMessage()];
  }
}


/**
 * Update a review
 *
 * @param int $review_id The ID of the review to update.
 * @param string $rating The new rating.
 * @param string $comment The new comment.
 * @return array An array containing the updated review data or an error message.
 */
function updateReview(int $review_id, string $rating, string $comment) {
    // Database connection (Replace with your actual database connection)
    $db = new PDO('mysql:host=localhost;dbname=your_database_name', 'your_username', 'your_password');

    // Input Validation (Basic - enhance for production)
    if (!preg_match('/^[1-5][0-9]*$/', $rating)) {
        return ['success' => false, 'message' => 'Invalid rating. Rating must be a number between 1 and 5.'];
    }
    if (empty($comment)) {
        return ['success' => false, 'message' => 'Comment cannot be empty.'];
    }

    try {
        $stmt = $db->prepare("UPDATE reviews SET rating = ?, comment = ? WHERE id = ?");
        $stmt->bindParam(1, $rating);
        $stmt->bindParam(2, $comment);
        $stmt->bindParam(3, $review_id);

        $stmt->execute();

        return ['success' => true, 'message' => 'Review updated successfully.'];

    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Database error: ' . $e->getMessage()];
    }
}


/**
 * Delete a review
 *
 * @param int $review_id The ID of the review to delete.
 * @return array An array indicating success or failure.
 */
function deleteReview(int $review_id) {
    // Database connection (Replace with your actual database connection)
    $db = new PDO('mysql:host=localhost;dbname=your_database_name', 'your_username', 'your_password');

    try {
        $stmt = $db->prepare("DELETE FROM reviews WHERE id = ?");
        $stmt->bindParam(1, $review_id);
        $stmt->execute();

        return ['success' => true, 'message' => 'Review deleted successfully.'];

    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Database error: ' . $e->getMessage()];
    }
}


// Example Usage (After creating the 'reviews' table)
// $newReview = createReview(1, 123, 4, 'This is a great product!');
// print_r($newReview);

// $review = getReview(1);
// print_r($review);

// $updatedReview = updateReview(1, 5, 'Excellent product, highly recommended.');
// print_r($updatedReview);

// $deletedReview = deleteReview(1);
// print_r($deletedReview);
?>
