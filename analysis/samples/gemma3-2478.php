

<?php

/**
 * User Review Function
 *
 * This function takes user input for a review and generates a formatted review string.
 *
 * @param string $username The username of the reviewer.
 * @param string $rating A numeric rating from 1 to 5.
 * @param string $comment The user's review comment.
 *
 * @return string A formatted review string.
 */
function generateReview(string $username, int $rating, string $comment): string
{
  // Input validation -  Important for security and data integrity
  if (!is_string($username) || empty($username)) {
    return "Error: Username cannot be empty.";
  }
  if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
    return "Error: Rating must be a number between 1 and 5.";
  }
  if (empty($comment)) {
    return "Error: Comment cannot be empty.";
  }


  // Build the review string
  $review = "Review by: " . htmlspecialchars($username) . "
";
  $review .= "Rating: " . $rating . "/5
";
  $review .= "Comment: " . htmlspecialchars($comment) . "
";
  $review .= "---";

  return $review;
}

// Example Usage:
$username = "JohnDoe";
$rating = 4;
$comment = "Great product, highly recommended!";

$review = generateReview($username, $rating, $comment);
echo $review . "
";

// Example with invalid input:
$invalidReview = generateReview("", 3, "This is a review.");
echo $invalidReview . "
";

$anotherInvalid = generateReview("Jane", 6, "Awesome!");
echo $anotherInvalid . "
";


?>
