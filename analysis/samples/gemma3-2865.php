

<?php

/**
 * User Review Function
 *
 * This function allows you to store and display user reviews for a given product.
 * It includes basic validation and data sanitization.
 *
 * @param string $product_id The unique identifier of the product.
 * @param string $username The username of the reviewer.
 * @param string $rating  The rating given by the user (e.g., 1-5).
 * @param string $comment The user's review comment.
 *
 * @return bool True on success, false on failure.
 */
function storeUserReview(string $product_id, string $username, string $rating, string $comment): bool
{
    // Input validation and sanitization
    if (empty($product_id) || empty($username) || empty($rating) || empty($comment)) {
        error_log("Error: Product ID, username, rating, and comment cannot be empty.");
        return false;
    }

    if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        error_log("Error: Username must only contain alphanumeric characters and underscores.");
        return false;
    }

    if (!preg_match('/^[1-5][0-9]*$/', $rating)) {
        error_log("Error: Rating must be a number between 1 and 5.");
        return false;
    }

    if (strlen($comment) > 500) { // Limit comment length
        error_log("Error: Comment exceeds the maximum length (500 characters).");
        return false;
    }


    // Database interaction (example using a simple array for demonstration)
    $review = [
        'product_id' => $product_id,
        'username' => $username,
        'rating' => $rating,
        'comment' => $comment,
        'date' => date('Y-m-d H:i:s'), // Add timestamp for better organization
    ];

    // You would typically replace this with your database insertion logic
    $reviews = loadReviewsFromDatabase($product_id); // Assuming a function to load reviews
    $reviews[] = $review;

    // Save the review to the database (replace with your actual database insertion)
    if (!saveReviewToDatabase($review)) {
        error_log("Error: Failed to save review to database.");
        return false;
    }

    return true;
}


/**
 * Placeholder functions for database interaction
 */
function loadReviewsFromDatabase(string $product_id) {
    //  In a real application, this would query the database
    //  and return the existing reviews for that product.
    return [];
}

function saveReviewToDatabase(array $review) {
    // In a real application, this would insert the review into the database.
    //  This is a placeholder to demonstrate the integration.
    //  You would use your database connection and query to insert the review.
    //  For example:
    //  $db = new DatabaseConnection();
    //  $stmt = $db->prepare("INSERT INTO reviews (product_id, username, rating, comment, date) VALUES (?, ?, ?, ?, ?)");
    //  $stmt->bind_param("ssisss", $review['product_id'], $review['username'], $review['rating'], $review['comment'], $review['date']);
    //  $stmt->execute();
    //  $stmt->close();
    return true; // Placeholder, always return true
}


// Example Usage
$productId = "P123";
$reviewerName = "JohnDoe";
$rating = 4;
$commentText = "Great product!  I would definitely recommend it.";

if (storeUserReview($productId, $reviewerName, $rating, $commentText)) {
    echo "Review submitted successfully!";
} else {
    echo "Review submission failed. Check the error log for details.";
}

?>
