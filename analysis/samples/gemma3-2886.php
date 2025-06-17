

<?php

/**
 * User Review Function
 *
 * This function takes user input and generates a basic user review.
 * It can be customized to add more features like rating, sentiment analysis,
 * or integration with a database.
 *
 * @param string $username The username of the reviewer.
 * @param string $reviewText The text of the review.
 * @return string A formatted user review string.
 */
function generateUserReview(string $username, string $reviewText): string
{
    // Validate inputs (Optional, but recommended)
    if (empty($username)) {
        return "Error: Username cannot be empty.";
    }
    if (empty($reviewText)) {
        return "Error: Review text cannot be empty.";
    }

    // Format the review
    $review = "Review by: " . $username . "
";
    $review .= "Review Text: " . $reviewText . "
";
    $review .= "--- End of Review ---";

    return $review;
}


// Example Usage:
$username = "JohnDoe";
$reviewText = "This product is amazing! I highly recommend it.";
$review = generateUserReview($username, $reviewText);
echo $review . PHP_EOL;

$username = "JaneSmith";
$reviewText = "The service was slow, but the staff were friendly.";
$review = generateUserReview($username, $reviewText);
echo $review . PHP_EOL;


//Example with invalid input
$username = "";
$reviewText = "This is a review.";
$review = generateUserReview($username, $reviewText);
echo $review . PHP_EOL; // This will output the error message.

?>
