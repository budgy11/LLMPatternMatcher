

<?php

/**
 *  User Review Function
 *
 *  This function takes user input for a review and returns a formatted review string.
 *
 *  @param string $reviewerName - The name of the reviewer.
 *  @param string $rating - The rating given by the reviewer (e.g., 1-5).
 *  @param string $comment - The user's written comment for the review.
 *
 *  @return string - A formatted review string.  Returns an error message if input is invalid.
 */
function createReview(string $reviewerName, string $rating, string $comment)
{
  // Input validation - Basic example.  You should expand this.
  if (empty($reviewerName)) {
    return "Error: Reviewer name cannot be empty.";
  }
  if (!is_numeric($rating) || (int)$rating < 1 || (int)$rating > 5) {
    return "Error: Rating must be a number between 1 and 5.";
  }
  if (empty($comment)) {
    return "Error: Review comment cannot be empty.";
  }

  // Create the formatted review string
  $review = "Review by: " . $reviewerName . "
";
  $review .= "Rating: " . $rating . "/5
";
  $review .= "Comment: " . $comment . "
";
  $review .= "--- End of Review ---
";

  return $review;
}


// Example Usage:
$reviewer = "John Doe";
$rate = 4;
$feedback = "This product is amazing!  I highly recommend it.";

$review_text = createReview($reviewer, $rate, $feedback);
echo $review_text . "
";

// Example with invalid input:
$invalidReview = createReview("", 6, "Bad review!");
echo $invalidReview . "
";

$anotherInvalid = createReview("Jane", "2", "");
echo $anotherInvalid . "
";


?>
