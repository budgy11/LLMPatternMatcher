

<?php

/**
 * User Review Function - A simple example.
 *
 * This function takes a user's review, rating, and optionally, their ID.
 * It validates the inputs and returns a formatted review string.
 *
 * @param string $review The user's review text.
 * @param int    $rating The user's rating (e.g., 1-5).
 * @param string|int $userId The user's ID (optional).  Defaults to a generic ID.
 *
 * @return string A formatted review string, or an error message if validation fails.
 */
function create_review(string $review, int $rating, $userId = null) {
    // Input validation - Essential for security and data integrity
    if (empty($review)) {
        return "Error: Review cannot be empty.";
    }

    if ($rating < 1 || $rating > 5) {
        return "Error: Rating must be between 1 and 5.";
    }

    // Optional user ID handling
    if ($userId === null) {
        $userId = "Anonymous User";
    } elseif (!is_numeric($userId)) {
        return "Error: User ID must be a number or a valid user ID.";
    }

    // Build the review string
    $review_string = "Review by: " . $userId . "
";
    $review_string .= "Rating: " . $rating . "/5
";
    $review_string .= $review . "
";
    $review_string .= "---"; // Separator

    return $review_string;
}

// --- Example Usage ---

// Example 1:  Basic review
$review1 = create_review("This product is amazing!  I highly recommend it.", 5, 123);
echo $review1 . "

";

// Example 2: Review with a different user ID
$review2 = create_review("Great value for the money.", 4, "user456");
echo $review2 . "

";

// Example 3:  Invalid rating
$review3 = create_review("It could be better...", 0);
echo $review3 . "

";

// Example 4: Empty review
$review4 = create_review("", 3);
echo $review4 . "

";

// Example 5:  Using a numeric user ID
$review5 = create_review("The instructions were unclear.", 2, 9876543);
echo $review5 . "

";

?>
