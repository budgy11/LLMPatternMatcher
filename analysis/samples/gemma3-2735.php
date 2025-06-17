

<?php

/**
 * User Review Function
 *
 * This function creates a review object from user input and allows for
 * basic validation and sanitization.
 *
 * @param string $username The username of the reviewer.
 * @param string $rating  The rating given by the user (e.g., 1-5).
 * @param string $comment The user's review comment.
 * @return array|false  An array containing the review data, or false on error.
 */
function createReview($username, $rating, $comment)
{
    // Input Validation and Sanitization
    if (empty($username)) {
        error_log("Error: Username cannot be empty.");  // Log for debugging
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

    // Sanitize inputs to prevent XSS attacks
    $username = filter_var($username, FILTER_SANITIZE_STRING);
    $comment = filter_var($comment, FILTER_SANITIZE_STRING);


    // Create the review data array
    $review = [
        'username' => $username,
        'rating' => (int)$rating, // Convert rating to integer
        'comment' => $comment
    ];

    return $review;
}

// Example Usage:
$reviewData = createReview("JohnDoe", 4, "Great product, highly recommend!");

if ($reviewData) {
    echo "Review Created Successfully:
";
    print_r($reviewData);
} else {
    echo "Error creating review. Check logs for details.
";
}


// Example showing an error case:
$badReview = createReview("", 3, "This is bad!");
if ($badReview) {
  echo "This shouldn't print.
";
} else {
   echo "Error case handled correctly.
";
}
?>
