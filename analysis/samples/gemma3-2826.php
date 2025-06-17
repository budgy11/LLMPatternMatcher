

<?php

/**
 * User Review Function
 *
 * This function creates a basic user review structure, 
 * storing the user's review, rating, and optionally, a comment.
 *
 * @param string $user_name The name of the user submitting the review.
 * @param int    $rating    The user's rating (e.g., 1-5).  Must be an integer.
 * @param string $comment  (Optional)  A comment from the user.
 * @return array  An array containing the review data.
 */
function create_user_review(string $user_name, int $rating, string $comment = '') {
    // Input Validation - Important!
    if (!is_string($user_name)) {
        throw new InvalidArgumentException("User name must be a string.");
    }
    if (!is_int($rating) || $rating < 1 || $rating > 5) {
        throw new InvalidArgumentException("Rating must be an integer between 1 and 5.");
    }

    // Create the review data array
    $review = [
        'user_name' => $user_name,
        'rating'    => $rating,
        'comment'   => $comment
    ];

    return $review;
}

// Example Usage:
try {
    $review1 = create_user_review("John Doe", 5, "Great product!  Highly recommended.");
    echo "Review 1:
";
    print_r($review1);
    echo "

";

    $review2 = create_user_review("Jane Smith", 3);
    echo "Review 2:
";
    print_r($review2);
    echo "

";

    // Example of error handling - incorrect rating
    try {
        $review3 = create_user_review("Peter Jones", 6);
        echo "Review 3 (This won't execute):
";
        print_r($review3);
    } catch (InvalidArgumentException $e) {
        echo "Error creating review: " . $e->getMessage() . "
";
    }

} catch (InvalidArgumentException $e) {
    echo "General error creating review: " . $e->getMessage() . "
";
}

?>
