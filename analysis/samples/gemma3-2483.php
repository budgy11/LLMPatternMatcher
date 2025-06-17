

<?php

/**
 * This function generates a user review based on input data.
 *
 * @param string $name The name of the user.
 * @param string $rating A numerical rating (e.g., 1-5).
 * @param string $comment An optional comment from the user.
 *
 * @return string A formatted user review string.
 */
function generateUserReview(string $name, int $rating, string $comment = "") {
    // Input Validation (Important for security and robustness)
    if (!is_string($name)) {
        return "Error: User name must be a string.";
    }
    if (!is_int($rating) || $rating < 1 || $rating > 5) {
        return "Error: Rating must be an integer between 1 and 5.";
    }


    $review = "Review by: " . $name . "
";
    $review .= "Rating: " . $rating . "/5
";
    if (!empty($comment)) {
        $review .= "Comment: " . $comment . "
";
    }
    return $review;
}



// Example Usage:
$user1Review = generateUserReview("Alice", 4, "Great product!  I love it.");
echo $user1Review . "

";

$user2Review = generateUserReview("Bob", 3);
echo $user2Review . "

";

$user3Review = generateUserReview("Charlie", 5, "Excellent value for money!");
echo $user3Review . "

";


// Example of handling invalid input:
$invalidReview = generateUserReview(123, 2, "This is bad"); // Invalid user name
echo $invalidReview . "

";

$invalidRatingReview = generateUserReview("David", 7, "Too high!"); // Invalid rating
echo $invalidRatingReview . "

";


?>
