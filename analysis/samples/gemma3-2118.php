

<?php

/**
 * User Review Function
 *
 * This function allows you to submit and display user reviews for a product or item.
 *
 * @param string $productName The name of the product being reviewed.
 * @param string $reviewText The text of the user's review.
 * @param string $username The name of the user submitting the review.
 * @param array $reviews (Optional) An existing array of reviews to add to. Defaults to an empty array.
 *
 * @return array An array of reviews, updated with the new review.
 */
function addReview(string $productName, string $reviewText, string $username, array $reviews = []) {
  // Sanitize input (basic, expand as needed for production)
  $productName = trim($productName);
  $reviewText = trim($reviewText);
  $username = trim($username);

  // Validate input (you can enhance this with more robust validation)
  if (empty($productName) || empty($reviewText) || empty($username)) {
    return $reviews; // Return existing reviews if input is invalid
  }

  // Create a new review object.  Could be an object or an associative array.
  $review = [
    'username' => $username,
    'review' => $reviewText,
    'date' => date('Y-m-d H:i:s') // Add a timestamp for better organization
  ];

  // Add the new review to the array
  $reviews[] = $review;

  return $reviews;
}


/**
 * Function to display the reviews
 *
 * @param array $reviews An array of reviews to display.
 */
function displayReviews(array $reviews) {
    echo "<h2>Reviews for " .  implode(",",array_column($reviews, 'username')) . "</h2>";

    if (empty($reviews)) {
        echo "<p>No reviews yet!</p>";
        return;
    }

    echo "<ul>";
    foreach ($reviews as $review) {
        echo "<li>";
        echo "<strong>" . $review['username'] . ":</strong> " . $review['review'] . " - " . $review['date'];
        echo "</li>";
    }
    echo "</ul>";
}



// Example Usage:
$product = "Awesome Gadget X";

// Add some initial reviews
$reviews = addReview($product, "This gadget is fantastic!  It's easy to use and does everything I need.", "John Doe");
$reviews = addReview($product, "Great product, but a little pricey.", "Jane Smith", $reviews);
$reviews = addReview($product, "Works as described.", "Peter Jones", $reviews);


// Display the reviews
displayReviews($reviews);

?>
