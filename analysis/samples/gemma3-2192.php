

<?php

/**
 * User Review Function
 *
 * This function creates a review object with details like user, rating, and review text.
 * It also provides basic validation to ensure data is provided and in a reasonable format.
 *
 * @param string $username The username of the reviewer.
 * @param int    $rating   The rating given (1-5).
 * @param string $reviewText The user's review text.
 *
 * @return array|false Returns an associative array representing the review 
 *                      on success, or false on failure.
 */
function createReview(string $username, int $rating, string $reviewText): array|false
{
    // Validate inputs
    if (empty($username)) {
        error_log("Error: Username cannot be empty.");
        return false;
    }

    if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
        error_log("Error: Rating must be a number between 1 and 5.");
        return false;
    }

    if (empty($reviewText)) {
        error_log("Error: Review text cannot be empty.");
        return false;
    }

    // Create the review object
    $review = [
        'username' => $username,
        'rating'   => $rating,
        'review'   => $reviewText,
        'date'     => date("Y-m-d H:i:s"), // Add a timestamp for tracking
    ];

    return $review;
}


// Example Usage:
$review1 = createReview("JohnDoe", 5, "Excellent product! Highly recommended.");
if ($review1) {
    echo "Review 1:
";
    print_r($review1);
    // You would typically save this review to a database here.
} else {
    echo "Review 1 creation failed.
";
}

$review2 = createReview("JaneSmith", 3, "It was okay.  Nothing special.");
if ($review2) {
    echo "
Review 2:
";
    print_r($review2);
} else {
    echo "
Review 2 creation failed.
";
}

$review3 = createReview("", 4, "Good!");  // Example with invalid input
if ($review3) {
    echo "
Review 3:
";
    print_r($review3);
} else {
    echo "
Review 3 creation failed (as expected).
";
}


?>
