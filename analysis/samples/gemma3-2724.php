

<?php

/**
 *  User Review Function
 *
 *  This function allows users to submit and view reviews for a given item.
 *
 *  @param string $item_name - The name of the item being reviewed.
 *  @param string $user_name - The name of the user submitting the review.
 *  @param string $review_text - The text of the review.
 *  @param array $reviews - An array to store the reviews.  Defaults to an empty array.
 *
 *  @return array - The updated array of reviews, with the new review added.
 */
function addReview(string $item_name, string $user_name, string $review_text, array &$reviews = []) {
  // Validate input (basic checks - enhance for production)
  if (empty($item_name) || empty($user_name) || empty($review_text)) {
    return $reviews; // Return original array if any input is empty
  }

  // Create a review object (could be a simple associative array, or a dedicated class)
  $review = [
    'user' => $user_name,
    'text' => $review_text,
    'date' => date('Y-m-d H:i:s') // Add timestamp for review date
  ];

  // Add the review to the array.  Passing &$reviews means we're modifying the original array.
  $reviews[] = $review;

  return $reviews;
}

/**
 * Displays Reviews
 *
 *  This function displays the reviews in a formatted way.
 *
 *  @param array $reviews - The array of reviews to display.
 */
function displayReviews(array $reviews) {
  if (empty($reviews)) {
    echo "<p>No reviews yet!</p>";
    return;
  }

  echo "<h2>Reviews for " . htmlspecialchars($review_item) . "</h2>";
  echo "<ul>";
  foreach ($reviews as $review) {
    echo "<li>";
    echo "<strong>" . htmlspecialchars($review['user']) . ":</strong> " . htmlspecialchars($review['text']) . "<br>";
    echo "Date: " . htmlspecialchars($review['date']);
    echo "</li>";
  }
  echo "</ul>";
}


// Example Usage:

$myReviews = []; // Initialize an empty array to store reviews

// Add some reviews
$myReviews = addReview("Awesome Product", "John Doe", "This product is fantastic! I highly recommend it.");
$myReviews = addReview("Another Great Item", "Jane Smith", "It's really good value for the money.");
$myReviews = addReview("Something Cool", "Peter Jones", "I love this!");

// Display the reviews
displayReviews($myReviews);

// Example to show how input validation works
$newReviews = addReview("", "Test User", "Empty Review");
print_r($newReviews); // Prints the original $newReviews array (unchanged)

?>
