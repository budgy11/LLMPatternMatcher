

<?php

/**
 * User Review Function
 *
 * This function allows you to create, read, update, and delete user reviews.
 * It's designed to be used with a database.  For demonstration purposes,
 * we'll assume a simple database connection and table structure.
 *
 * @param string $productId The ID of the product the review is for.
 * @param string $username The username of the reviewer.
 * @param string $rating  The rating given by the reviewer (e.g., 1-5).
 * @param string $comment The review comment.
 * @param string $db_connection A database connection object.
 *
 * @return int|bool  The ID of the newly created review if successful, or false on error.
 */
function createReview(string $productId, string $username, string $rating, string $comment, $db_connection)
{
    // Sanitize and validate inputs (IMPORTANT!)
    $productId = filter_var($productId, FILTER_SANITIZE_NUMBER_INT);
    $username = filter_var($username, FILTER_SANITIZE_STRING);
    $rating = filter_var($rating, FILTER_VALIDATE_INT, array('min' => 1, 'max' => 5));
    $comment = filter_var($comment, FILTER_SANITIZE_STRING);

    if (!$productId || !$username || !$rating || !$comment) {
        error_log("Error: Missing required review fields."); // Log for debugging
        return false;
    }

    if (!is_numeric($productId)) {
        error_log("Error: Invalid product ID.");
        return false;
    }

    if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        error_log("Error: Invalid username (only alphanumeric and underscores allowed).");
        return false;
    }


    // Prepare SQL statement (Use prepared statements to prevent SQL injection!)
    $sql = "INSERT INTO reviews (product_id, username, rating, comment) 
            VALUES (:product_id, :username, :rating, :comment)";

    // Prepare the statement
    $stmt = $db_connection->prepare($sql);

    if (!$stmt) {
        error_log("Error preparing statement.");
        return false;
    }

    // Bind parameters
    $stmt->bindParam(':product_id', $productId);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':rating', $rating);
    $stmt->bindParam(':comment', $comment);

    // Execute the statement
    if (!$stmt->execute()) {
        error_log("Error executing statement: " . $stmt->error);
        return false;
    }

    // Get the ID of the newly created review
    $reviewId = $stmt->insert_id;

    // Close the statement
    $stmt->close();

    return $reviewId;
}

/**
 * Get Reviews for a Product
 *
 * Retrieves all reviews for a given product.
 *
 * @param int $productId The ID of the product.
 * @param $db_connection A database connection object.
 *
 * @return array An array of review objects, or an empty array if no reviews are found.
 */
function getReviewsForProduct(int $productId, $db_connection)
{
    $sql = "SELECT * FROM reviews WHERE product_id = :product_id";
    $stmt = $db_connection->prepare($sql);

    if (!$stmt) {
        error_log("Error preparing statement.");
        return [];
    }

    $stmt->bindParam(':product_id', $productId);
    $stmt->execute();

    $reviews = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $reviews[] = $row;
    }

    $stmt->close();
    return $reviews;
}


/**
 * Update Review
 *
 * Updates an existing review.
 *
 * @param int $reviewId The ID of the review to update.
 * @param string $rating The new rating.
 * @param string $comment The new comment.
 * @param $db_connection A database connection object.
 *
 * @return bool True on success, false on failure.
 */
function updateReview(int $reviewId, string $rating, string $comment, $db_connection)
{
    $sql = "UPDATE reviews SET rating = :rating, comment = :comment WHERE id = :id";
    $stmt = $db_connection->prepare($sql);

    if (!$stmt) {
        error_log("Error preparing statement.");
        return false;
    }

    $stmt->bindParam(':id', $reviewId);
    $stmt->bindParam(':rating', $rating);
    $stmt->bindParam(':comment', $comment);

    if (!$stmt->execute()) {
        error_log("Error executing statement: " . $stmt->error);
        return false;
    }

    return true;
}


/**
 * Delete Review
 *
 * Deletes a review.
 *
 * @param int $reviewId The ID of the review to delete.
 * @param $db_connection A database connection object.
 *
 * @return bool True on success, false on failure.
 */
function deleteReview(int $reviewId, $db_connection)
{
    $sql = "DELETE FROM reviews WHERE id = :id";
    $stmt = $db_connection->prepare($sql);

    if (!$stmt) {
        error_log("Error preparing statement.");
        return false;
    }

    $stmt->bindParam(':id', $reviewId);

    if (!$stmt->execute()) {
        error_log("Error executing statement: " . $stmt->error);
        return false;
    }

    return true;
}


// Example Usage (Illustrative - requires database setup)
// Create a database connection (replace with your actual connection details)
// $db_connection = new PDO("mysql:host=localhost;dbname=your_database", "your_username", "your_password");
// $db_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable exception handling for errors

// Create a review
// $reviewId = createReview(123, "JohnDoe", 4, "Great product!", $db_connection);
// if ($reviewId) {
//     echo "Review created with ID: " . $reviewId . "<br>";
// } else {
//     echo "Error creating review.<br>";
// }

// Get reviews for product 123
// $reviews = getReviewsForProduct(123, $db_connection);
// echo "Reviews for product 123:<br>";
// foreach ($reviews as $review) {
//     echo "Product ID: " . $review['product_id'] . ", Username: " . $review['username'] . ", Rating: " . $review['rating'] . ", Comment: " . $review['comment'] . "<br>";
// }

// Update a review
// if (updateReview(1, 5, "Excellent!", $db_connection)) {
//     echo "Review updated successfully.<br>";
// } else {
//     echo "Error updating review.<br>";
// }

// Delete a review
// if (deleteReview(1, $db_connection)) {
//     echo "Review deleted successfully.<br>";
// } else {
//     echo "Error deleting review.<br>";
// }

?>
