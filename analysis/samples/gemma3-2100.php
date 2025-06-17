

<?php

/**
 * This function creates a user review object with basic information.
 *
 * @param string $username  The name of the user submitting the review.
 * @param string $rating    The rating given by the user (e.g., 1-5).
 * @param string $comment   The user's written comment about the item/service.
 *
 * @return array An array containing the user review data.
 */
function createReview(string $username, string $rating, string $comment): array
{
  // Validate input (add more validation as needed for your application)
  if (empty($username)) {
    throw new InvalidArgumentException("Username cannot be empty.");
  }

  if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
    throw new InvalidArgumentException("Rating must be a number between 1 and 5.");
  }

  if (empty($comment)) {
    throw new InvalidArgumentException("Comment cannot be empty.");
  }


  return [
    'username' => $username,
    'rating' => $rating,
    'comment' => $comment,
    'timestamp' => date('Y-m-d H:i:s'), // Add timestamp for tracking
  ];
}


// Example Usage:
try {
  $review1 = createReview("JohnDoe", 4, "Great product!  Works exactly as advertised.");
  echo "Review 1: " . print_r($review1, true) . PHP_EOL;

  $review2 = createReview("JaneSmith", 5, "Excellent service, highly recommend!");
  echo "Review 2: " . print_r($review2, true) . PHP_EOL;

  // Example of an invalid review (to demonstrate validation)
  // $invalidReview = createReview("", 3, "This is a comment.");  // Throws an exception

} catch (InvalidArgumentException $e) {
  echo "Error creating review: " . $e->getMessage() . PHP_EOL;
}

?>
