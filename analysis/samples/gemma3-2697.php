

<?php

/**
 * User Review Function - Allows users to submit and display reviews.
 *
 * This function provides a basic interface for submitting and viewing user reviews.
 * It includes input validation and simple display formatting.
 *
 * @param array $reviews An array to store user reviews.  Defaults to an empty array.
 * @param string $username The username of the user submitting the review.
 * @param string $reviewText The text of the review.
 * @return array The updated array of reviews.
 */
function submitUserReview(array &$reviews, string $username, string $reviewText) {

  // Input validation - very basic for demonstration.  Enhance this for production.
  if (empty($reviewText)) {
    echo "Error: Review text cannot be empty.
";
    return $reviews;
  }

  // Format the review
  $formattedReview = "User: $username - Review: $reviewText";

  // Add the review to the array
  $reviews[] = $formattedReview;

  return $reviews;
}


/**
 * Displays all user reviews.
 *
 * This function iterates through the review array and prints each review.
 *
 * @param array $reviews An array of user reviews.
 */
function displayReviews(array $reviews) {
  echo "<h2>User Reviews:</h2>
";
  if (empty($reviews)) {
    echo "<p>No reviews submitted yet.</p>
";
    return;
  }
  echo "<ul>
";
  foreach ($reviews as $review) {
    echo "  <li>$review</li>
";
  }
  echo "</ul>
";
}


// Example Usage:
$review_data = []; // Initialize an empty array to store reviews

// Submit some reviews
submitUserReview($review_data, "Alice", "This product is amazing!");
submitUserReview($review_data, "Bob", "I'm very happy with my purchase.");
submitUserReview($review_data, "Charlie", "It's okay, could be better.");
submitUserReview($review_data, "David", "Excellent value for money!");


// Display the reviews
displayReviews($review_data);


//Demonstration of input validation
submitUserReview($review_data, "Eve", ""); // Attempt to submit an empty review
displayReviews($review_data); // Display the reviews after the invalid attempt
?>
