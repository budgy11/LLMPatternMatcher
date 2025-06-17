

<?php

/**
 * User Review Function
 *
 * This function allows you to store and display user reviews for a product or item.
 * It includes basic validation and output formatting.
 *
 * @param string $productId The unique identifier of the product/item.
 * @param string $username The username of the reviewing user.
 * @param string $rating  The rating given by the user (e.g., 1-5).  Must be numeric.
 * @param string $comment  The user's review comment.
 *
 * @return bool True on successful saving, False on failure (e.g., invalid input).
 */
function saveUserReview(string $productId, string $username, string $rating, string $comment) {
    // --- Input Validation ---
    if (empty($productId)) {
        error_log("Error: Product ID cannot be empty.");  // Log for debugging
        return false;
    }

    if (empty($username)) {
        error_log("Error: Username cannot be empty.");
        return false;
    }

    if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
        error_log("Error: Rating must be a number between 1 and 5.");
        return false;
    }

    if (empty($comment)) {
        error_log("Error: Comment cannot be empty.");
        return false;
    }

    // --- Data Sanitization (Important for security) ---
    $productId = filter_var($productId, FILTER_SANITIZE_STRING);  // Escape HTML and other characters
    $username = filter_var($username, FILTER_SANITIZE_STRING);
    $rating = (int)$rating; // Cast to integer for safe storage
    $comment = filter_var($comment, FILTER_SANITIZE_STRING);

    // --- Data Storage (Example - Using a simple array.  In a real app, use a database.) ---
    $reviews = loadReviews($productId);  // Assuming a function to load reviews
    if ($reviews) {
        $review = [
            'productId' => $productId,
            'username' => $username,
            'rating' => $rating,
            'comment' => $comment,
            'timestamp' => time() // Add a timestamp for ordering/management
        ];
        $reviews[] = $review;
    } else {
        $reviews = [$review];
    }


    // --- Save the review ---
    saveReviews($reviews); // Assuming a function to save reviews to storage.

    return true;
}


/**
 * Placeholder Functions (Replace with your actual implementation)
 */
function loadReviews(string $productId) {
    // Replace this with your logic to retrieve reviews for the product.
    // This is just a dummy implementation.
    // Ideally, you'd fetch this from a database.
    return []; // Return an empty array
}

function saveReviews(array $reviews) {
    // Replace this with your logic to save the reviews to your storage.
    // This is just a dummy implementation.
    // In a real application, you would likely save this data to a database.
    echo "Saving reviews: 
";
    print_r($reviews);
}


// --- Example Usage ---
$productID = "prod123";
$userName = "JohnDoe";
$rating = 4;
$reviewText = "Great product, would recommend!";

if (saveUserReview($productID, $userName, $rating, $reviewText)) {
    echo "Review submitted successfully!
";
} else {
    echo "Error submitting review.
";
}

// Example with invalid rating:
$invalidRating = 6;
$result = saveUserReview($productID, $userName, $invalidRating, $reviewText);
if(!$result){
    echo "Invalid rating test passed
";
}
?>
