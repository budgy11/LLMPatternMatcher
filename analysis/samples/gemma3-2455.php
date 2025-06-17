

<?php

/**
 * This function creates a user review object and handles basic validation.
 *
 * @param string $username The username of the reviewer.
 * @param string $rating  The rating given by the user (e.g., 1-5).
 * @param string $comment The user's comment about the reviewed item.
 * @return array|false  An associative array containing the review data on success, or false on failure.
 */
function createReview(string $username, string $rating, string $comment)
{
    // Validate input
    if (empty($username)) {
        error_log("Error: Username cannot be empty.");  // Log the error
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

    // Create the review object
    $review = [
        'username' => $username,
        'rating'   => (int)$rating, // Convert rating to integer
        'comment'  => $comment,
        'date'     => date("Y-m-d H:i:s"), // Add timestamp for review creation
        'status'   => 'pending' // Default status
    ];

    return $review;
}


// Example Usage:

// Create a valid review
$reviewData = createReview("JohnDoe", 4, "Great product!  Highly recommend.");

if ($reviewData) {
    echo "Review created successfully:
";
    print_r($reviewData);
} else {
    echo "Review creation failed.
";
}

// Create an invalid review (empty username)
$reviewData = createReview("", 3, "Okay product.");
if ($reviewData) {
    echo "Review created successfully:
";
    print_r($reviewData);
} else {
    echo "Review creation failed (invalid input).
";
}


// Create an invalid review (invalid rating)
$reviewData = createReview("JaneSmith", 6, "Good, but could be better.");
if ($reviewData) {
    echo "Review created successfully:
";
    print_r($reviewData);
} else {
    echo "Review creation failed (invalid input).
";
}

// Create an invalid review (empty comment)
$reviewData = createReview("PeterJones", 5, "");
if ($reviewData) {
    echo "Review created successfully:
";
    print_r($reviewData);
} else {
    echo "Review creation failed (invalid input).
";
}

?>
