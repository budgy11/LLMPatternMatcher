

<?php

// Add validation to the constructor
function generateUserReviewReview($reviewId, $userId, $productId, $rating, $comment) {
    if ($rating < 1 || $rating > 5) {
        throw new InvalidArgumentException("Rating must be between 1 and 5.");
    }

    $review = new UserReview($reviewId, $userId, $productId, $rating, $comment);
    return generateUserReviewReview($review);
}


<?php

/**
 * User Review Function
 *
 * This function allows users to submit and display reviews.
 *
 * @param string $username The username of the reviewer.
 * @param string $rating The rating given (e.g., 1-5).  Must be an integer.
 * @param string $comment The review text.
 * @param array $review_data An optional array to store reviews in (e.g., database).
 *
 * @return array|string Returns an array of reviews or a string message on error.
 */
function create_review(string $username, int $rating, string $comment, array &$review_data = []) {
  // Validate inputs - Basic validation, can be expanded
  if (empty($username)) {
    return "Username cannot be empty.";
  }

  if ($rating < 1 || $rating > 5) {
    return "Rating must be between 1 and 5.";
  }

  if (empty($comment)) {
    return "Comment cannot be empty.";
  }

  // Create review data
  $review = [
    'username' => $username,
    'rating' => $rating,
    'comment' => $comment,
    'date' => date('Y-m-d H:i:s') // Add timestamp
  ];

  // Store review -  Example using an array.  Replace with database integration.
  $review_data[] = $review;

  return "Review submitted successfully!";
}


// Example Usage:

// Simulate a database (replace with actual database connection)
$reviews = [];

// Create some reviews
$result1 = create_review("JohnDoe", 5, "Great product! Highly recommend.", $reviews);
echo $result1 . "
";
echo "Current Reviews:
";
print_r($reviews);

$result2 = create_review("JaneSmith", 4, "Good value for money.", $reviews);
echo $result2 . "
";
echo "Current Reviews:
";
print_r($reviews);

$result3 = create_review("PeterJones", 3, "It's okay, nothing special.", $reviews);
echo $result3 . "
";
echo "Current Reviews:
";
print_r($reviews);

$result4 = create_review("", 2, "Bad experience", $reviews); //Test empty username
echo $result4 . "
";
echo "Current Reviews:
";
print_r($reviews);

$result5 = create_review("AliceBrown", 6, "Excellent!", $reviews); //Test invalid rating
echo $result5 . "
";
echo "Current Reviews:
";
print_r($reviews);
?>
