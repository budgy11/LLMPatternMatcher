

<?php

/**
 * User Review Function
 *
 * This function allows you to collect and display user reviews for a specific item.
 *
 * @param string $itemId The ID of the item being reviewed.
 * @param int $maxReviews The maximum number of reviews to display (optional, defaults to 5).
 * @return array An array containing:
 *              - 'reviews': An array of review objects.
 *              - 'totalReviews': The total number of reviews for the item.
 */
function getReviews(string $itemId, int $maxReviews = 5) {
  // Simulate a database or data source for reviews.  Replace this with your actual data source.
  $reviewsData = [
    'item123' => [
      ['user' => 'John Doe', 'rating' => 4, 'comment' => 'Great product!  Highly recommended.'],
      ['user' => 'Jane Smith', 'rating' => 5, 'comment' => 'Excellent quality and fast delivery.'],
      ['user' => 'Peter Jones', 'rating' => 3, 'comment' => 'Good value for the price.'],
    ],
    'item456' => [
      ['user' => 'Alice Brown', 'rating' => 2, 'comment' => 'Not as good as I expected.'],
      ['user' => 'Bob Williams', 'rating' => 3, 'comment' => 'Decent, but could be better.'],
    ],
  ];

  // Check if the item has any reviews
  if (!isset($reviewsData[$itemId])) {
    return ['reviews' => [], 'totalReviews' => 0];
  }

  $reviews = $reviewsData[$itemId];

  // Limit the number of reviews
  $reviews = array_slice($reviews, 0, $maxReviews, true);

  // Calculate the total number of reviews
  $totalReviews = count($reviews);

  return ['reviews' => $reviews, 'totalReviews' => $totalReviews];
}


/**
 * Display Reviews Function (Example)
 *
 * This function takes the array of reviews and formats it for display.
 *
 * @param array $reviews The array of review objects returned by getReviews().
 */
function displayReviews(array $reviews) {
  echo "<div class='review-container'>";
  if (empty($reviews['reviews'])) {
    echo "<p>No reviews yet.</p>";
  } else {
    echo "<h2>Reviews for Item " . $reviews['itemId'] . "</h2>"; // Assuming you pass $itemId to this function
    echo "<ul>";
    foreach ($reviews['reviews'] as $review) {
      echo "<li>";
      echo "<p><strong>User:</strong> " . $review['user'] . "</p>";
      echo "<p><strong>Rating:</strong> " . $review['rating'] . " / 5</p>";
      echo "<p>" . $review['comment'] . "</p>";
      echo "</li>";
    }
    echo "</ul>";

    if (count($reviews['reviews']) < $reviews['totalReviews']) {
      echo "<p>Showing " . count($reviews['reviews']) . " of " . $reviews['totalReviews'] . " reviews.</p>";
    }
  }
  echo "</div>";
}



// Example Usage:

// Get reviews for item 'item123'
$reviewsForItem123 = getReviews('item123');
displayReviews($reviewsForItem123);

// Get reviews for item 'item456'
$reviewsForItem456 = getReviews('item456');
displayReviews($reviewsForItem456);

// Get reviews for a non-existent item
$reviewsForNonExistentItem = getReviews('item999');
displayReviews($reviewsForNonExistentItem);

?>
