

<?php

/**
 * User Review Function
 *
 * This function allows you to create and display user reviews for a product or item.
 *
 * @param string $productName  The name of the product/item being reviewed.
 * @param string $userId       The unique identifier of the user submitting the review.
 * @param string $rating       The rating given by the user (e.g., 1-5).
 * @param string $comment      The user's review comment.
 * @param array $reviews       (Optional) An existing array of reviews to append to.
 *
 * @return array  An updated array of reviews, including the new review.
 */
function createAndDisplayReview(string $productName, string $userId, string $rating, string $comment, array &$reviews = []) {
  // Validate input (example - you should expand this for robust validation)
  if (empty($productName)) {
    return $reviews; // Return empty if product name is empty
  }
  if (empty($userId)) {
    return $reviews; // Return empty if user ID is empty
  }
  if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
    return $reviews; // Return empty if rating is invalid
  }
  if (empty($comment)) {
    return $reviews; // Return empty if comment is empty
  }
  

  // Create a new review object (can be an array, object, or custom class)
  $review = [
    'product' => $productName,
    'user'    => $userId,
    'rating'  => $rating,
    'comment' => $comment,
    'date'    => date('Y-m-d H:i:s')  // Timestamp for the review
  ];

  // Add the review to the array
  $reviews[] = $review;

  return $reviews;
}


// Example Usage:
$product = "Awesome Widget";

// First Review
$reviews1 = createAndDisplayReview($product, "user123", 5, "This widget is fantastic! Highly recommended.");
print_r($reviews1);

// Second Review
$reviews2 = createAndDisplayReview($product, "user456", 4, "Good product, does what it says.", $reviews1);
print_r($reviews2);

// Display the reviews (example - you'd likely use a template engine or similar)
echo "<h2>Reviews for " . $product . "</h2>";
if (empty($reviews2)) {
  echo "<p>No reviews yet.</p>";
} else {
  echo "<ul>";
  foreach ($reviews2 as $review) {
    echo "<li>";
    echo "<strong>User:</strong> " . $review['user'] . "<br>";
    echo "<strong>Rating:</strong> " . $review['rating'] . "<br>";
    echo "<strong>Comment:</strong> " . $review['comment'] . "<br>";
    echo "<strong>Date:</strong> " . $review['date'] . "</li>";
  }
  echo "</ul>";
}

?>
