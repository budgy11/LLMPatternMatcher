

<?php

/**
 * User Review Function
 *
 * This function creates a user review object, validating the input
 * and providing a simple way to store review data.
 *
 * @param string $username  The name of the user leaving the review.
 * @param string $rating   The rating given by the user (e.g., 1-5).
 * @param string $comment  The user's review comment.
 *
 * @return array|false  An array containing the review data (username, rating, comment)
 *                     on success, or false on failure (e.g., invalid rating).
 */
function createReview(string $username, string $rating, string $comment): array|false
{
    // Input validation
    $rating = (int) $rating; // Convert rating to integer

    if ($rating < 1 || $rating > 5) {
        return false; // Invalid rating
    }

    if (empty($username) || empty($comment)) {
        return false; // Username and comment are required
    }

    // Create the review object
    $review = [
        'username' => $username,
        'rating' => $rating,
        'comment' => $comment
    ];

    return $review;
}


// Example Usage:

// Successful review
$review1 = createReview("John Doe", 4, "Great product!  I highly recommend it.");
if ($review1) {
    echo "Review 1: " . json_encode($review1) . "
";
} else {
    echo "Review 1 failed to create.
";
}

// Failed review - Invalid rating
$review2 = createReview("Jane Smith", 6, "Good product.");
if ($review2) {
    echo "Review 2: " . json_encode($review2) . "
";
} else {
    echo "Review 2 failed to create (invalid rating).
";
}

// Failed review - Missing comment
$review3 = createReview("Peter Jones", 3, "");
if ($review3) {
    echo "Review 3: " . json_encode($review3) . "
";
} else {
    echo "Review 3 failed to create (missing comment).
";
}

// Successfully created review
$review4 = createReview("Alice Brown", 5, "Excellent service and fast delivery!");
if ($review4) {
    echo "Review 4: " . json_encode($review4) . "
";
} else {
    echo "Review 4 failed to create.
";
}


?>
