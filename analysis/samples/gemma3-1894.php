

<?php

/**
 * This function creates a user review object and handles basic validation.
 *
 * @param string $name The reviewer's name.
 * @param string $rating A numerical rating (e.g., 1-5).
 * @param string $comment The reviewer's comment.
 *
 * @return array|false An associative array representing the review if valid,
 *                     false otherwise.
 */
function createReview(string $name, string $rating, string $comment): array|false
{
    // Validate input
    if (empty($name)) {
        error_log("Review: Empty name provided.");  // Log for debugging
        return false;
    }

    if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
        error_log("Review: Invalid rating provided.  Must be a number between 1 and 5.");
        return false;
    }

    if (empty($comment)) {
        error_log("Review: Empty comment provided.");
        return false;
    }


    // Create the review object
    $review = [
        'name' => $name,
        'rating' => $rating,
        'comment' => $comment,
        'date' => date('Y-m-d H:i:s') // Add the date/time of the review.
    ];

    return $review;
}


/**
 * Example Usage
 */

// Valid review
$review1 = createReview("Alice Smith", 4, "Great product!  Highly recommend.");
if ($review1) {
    echo "Review 1:
";
    print_r($review1);
    echo "
";
} else {
    echo "Review 1 creation failed.
";
}

// Invalid review - empty name
$review2 = createReview("", 5, "Good!");
if ($review2) {
    echo "Review 2:
";
    print_r($review2);
    echo "
";
} else {
    echo "Review 2 creation failed.
";
}

// Invalid review - invalid rating
$review3 = createReview("Bob Johnson", 6, "Okay.");
if ($review3) {
    echo "Review 3:
";
    print_r($review3);
    echo "
";
} else {
    echo "Review 3 creation failed.
";
}

// Valid review with no comment
$review4 = createReview("Charlie Brown", 3, "");
if ($review4) {
    echo "Review 4:
";
    print_r($review4);
    echo "
";
} else {
    echo "Review 4 creation failed.
";
}
?>
