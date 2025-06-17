

<?php

/**
 * User Review Function
 *
 * This function allows you to generate a formatted user review string
 * based on the provided review data.
 *
 * @param array $reviewData An associative array containing review data:
 *   - `user`: The username of the reviewer (string).
 *   - `rating`: The rating given by the reviewer (integer, 1-5).
 *   - `comment`: The reviewer's comment (string).
 *   - `date`: (Optional) The date of the review (string, formatted as YYYY-MM-DD).
 *
 * @return string A formatted user review string, or an error message if the
 *                input data is invalid.
 */
function generateUserReview(array $reviewData) {
    // Input validation - crucial for security and data integrity
    if (!isset($reviewData['user']) || !is_string($reviewData['user'])) {
        return "Error: Invalid user name.";
    }
    if (!isset($reviewData['rating']) || !is_int($reviewData['rating']) || $reviewData['rating'] < 1 || $reviewData['rating'] > 5) {
        return "Error: Invalid rating. Rating must be an integer between 1 and 5.";
    }
    if (!isset($reviewData['comment']) || !is_string($reviewData['comment'])) {
        return "Error: Invalid comment.";
    }
    if (isset($reviewData['date']) && !is_string($reviewData['date'])) {
        return "Error: Invalid date format.  Date must be a string.";
    }
    // Default date format if not provided
    $date = $reviewData['date'] ?? date("Y-m-d");

    // Generate the review string
    $review = sprintf(
        "**%s** - %s stars
",
        $reviewData['user'],
        $reviewData['rating']
    );

    $review .= $reviewData['comment'] . "
";
    $review .= "Date: " . $date;

    return $review;
}


// Example Usage:

$review1 = generateUserReview(['user' => 'Alice', 'rating' => 4, 'comment' => 'Great product, highly recommended!', 'date' => '2023-10-26']);
echo $review1 . "

";

$review2 = generateUserReview(['user' => 'Bob', 'rating' => 3, 'comment' => 'It was okay.', 'date' => '2023-10-27']);
echo $review2 . "

";

$review3 = generateUserReview(['user' => 'Charlie', 'rating' => 5, 'comment' => 'Fantastic!']);
echo $review3 . "

";

// Example with invalid data:
$review4 = generateUserReview(['rating' => 6, 'comment' => 'Bad']);
echo $review4 . "

"; // This will produce an error message.

$review5 = generateUserReview(['user' => 'David', 'rating' => 2, 'comment' => 'Terrible']);
echo $review5 . "

"; // Another example of error handling.

?>
