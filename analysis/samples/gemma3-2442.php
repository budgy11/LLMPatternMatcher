

<?php

/**
 * User Review Function - Simple Implementation
 *
 * This function takes user input and outputs a formatted user review.
 * It's a simplified example and can be expanded to include
 * more features like database storage, rating systems, etc.
 *
 * @param string $username      The name of the user.
 * @param string $reviewText    The text of the user's review.
 * @param int    $rating        (Optional) The rating given by the user (1-5). Defaults to 0.
 *
 * @return string Formatted review string.
 */
function generateUserReview(string $username, string $reviewText, int $rating = 0) {
  // Input validation - Basic, can be expanded
  if (empty($username)) {
    return "Error: Username cannot be empty.";
  }
  if (empty($reviewText)) {
    return "Error: Review text cannot be empty.";
  }

  // Create the review string
  $review = "Review by: " . $username . "
";
  $review .= "Rating: " . $rating . " stars
";
  $review .= "Review: " . $reviewText . "
";

  return $review;
}

// Example Usage:
$username = "John Doe";
$review = "This product is amazing! I highly recommend it.";
$rating = 5;

$formattedReview = generateUserReview($username, $review, $rating);
echo $formattedReview . PHP_EOL;

// Another example with default rating:
$username = "Jane Smith";
$review = "The product was okay, but could be better.";
$formattedReview = generateUserReview($username, $review);
echo $formattedReview . PHP_EOL;

// Example with error handling (empty username):
$username = "";
$review = "This is a review.";
$formattedReview = generateUserReview($username, $review);
echo $formattedReview . PHP_EOL;

?>
