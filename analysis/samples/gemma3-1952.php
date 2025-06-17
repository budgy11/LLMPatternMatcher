

<?php

/**
 * Function to process user reviews and display them.
 *
 * @param array $reviews An array of review objects (see example below).
 * @return void
 */
function displayUserReviews(array $reviews) {
  if (empty($reviews)) {
    echo "<p>No reviews yet. Be the first to leave one!</p>";
    return;
  }

  echo "<h2>User Reviews</h2>";

  foreach ($reviews as $review) {
    echo "<div class='review'>";
    echo "<p><strong>Author:</strong> " . htmlspecialchars($review['author']) . "</p>";
    echo "<p><strong>Rating:</strong> " . $review['rating'] . " / 5</p>";
    echo "<p>" . nl2br(htmlspecialchars($review['comment'])) . "</p>"; // Use nl2br for line breaks
    echo "<div class='timestamp'>Posted: " . date("F d, Y g:i:s", strtotime($review['timestamp'])) . "</div>";
    echo "</div>";
  }
}


/**
 * Example Review Object
 *
 * @param string $author
 * @param int $rating
 * @param string $comment
 * @param string $timestamp
 *
 * @return array
 */
function createReviewObject(string $author, int $rating, string $comment, string $timestamp = time()) {
  return [
    'author' => $author,
    'rating' => $rating,
    'comment' => $comment,
    'timestamp' => $timestamp,
  ];
}


// Example Usage (for testing)
$review1 = createReviewObject("Alice Smith", 4, "Great product!  I love it!", time() - 3600); // Review posted 1 hour ago
$review2 = createReviewObject("Bob Johnson", 5, "Excellent value for the money.", time() - 7200); // Review posted 2 hours ago
$review3 = createReviewObject("Charlie Brown", 3, "It's okay, but could be better.", time() - 14400); // Review posted 4 hours ago

$allReviews = [$review1, $review2, $review3];

displayUserReviews($allReviews);

?>
