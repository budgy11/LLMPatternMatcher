

<?php

/**
 * User Review Function
 *
 * This function allows you to submit and display user reviews for a product or service.
 *
 * @param string $productName The name of the product or service being reviewed.
 * @param string $reviewText The text of the review submitted by the user.
 * @param string $reviewerName The name of the user submitting the review.
 * @param array  $reviews An array to store reviews. If not provided, an empty array will be created.
 *
 * @return array An array containing all collected reviews.  Returns an empty array on error.
 */
function create_review(string $productName, string $reviewText, string $reviewerName, array &$reviews = []) {

  // Input validation - basic checks
  if (empty($productName) || empty($reviewText) || empty($reviewerName)) {
    error_log("Error: Product name, review text, and reviewer name cannot be empty."); // Log the error for debugging.
    return []; // Return an empty array to indicate an error
  }

  // Create the review object
  $review = [
    'product' => $productName,
    'reviewer' => $reviewerName,
    'date' => date('Y-m-d H:i:s'), // Add a timestamp for reference
    'text' => $reviewText
  ];

  // Add the review to the array
  $reviews[] = $review;

  return $reviews;
}


/**
 * Function to display a single review
 *
 * @param array $review A single review object.
 */
function display_review(array $review) {
  echo "<p><strong>Product:</strong> " . htmlspecialchars($review['product']) . "</p>";
  echo "<p><strong>Reviewer:</strong> " . htmlspecialchars($review['reviewer']) . "</p>";
  echo "<p><strong>Date:</strong> " . htmlspecialchars($review['date']) . "</p>";
  echo "<p><strong>Review Text:</strong> " . htmlspecialchars($review['text']) . "</p>";
  echo "<hr>";
}


/**
 * Function to display all reviews
 *
 * @param array $reviews An array of review objects.
 */
function display_all_reviews(array $reviews) {
  echo "<h2>User Reviews for " . htmlspecialchars($reviews[0]['product']) . "</h2>"; // Display the product name.
  foreach ($reviews as $review) {
    display_review($review);
  }
}

// --- Example Usage ---

// Initialize the reviews array
$reviews = [];

// Create some reviews
$reviews = create_review("Awesome Widget", "This widget is fantastic! It does everything I need and more.", "John Doe");
$reviews = create_review("Super Gadget", "The gadget is amazing and so easy to use.", "Jane Smith");
$reviews = create_review("Mega Device", "Great value for money. Highly recommended!", "Peter Jones");

// Display all reviews
display_all_reviews($reviews);

?>
