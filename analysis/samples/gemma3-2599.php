

<?php

/**
 * User Review Function
 *
 * This function allows users to submit and view reviews for a product or service.
 * It includes basic input validation and sanitization.  For a production environment,
 * you'd want to use a more robust validation and sanitization system and a database connection.
 *
 * @param string $productId The ID of the product or service being reviewed.
 * @param string $rating The rating provided by the user (1-5).
 * @param string $comment The user's written review.
 * @param string $username The username of the user submitting the review.
 * @return array An array containing the review data or an error message.
 */
function submit_review(string $productId, string $rating, string $comment, string $username): array
{
  // Input Validation
  if (empty($productId)) {
    return ['error' => 'Product ID cannot be empty.'];
  }
  if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
    return ['error' => 'Invalid rating. Rating must be a number between 1 and 5.'];
  }
  if (empty($comment)) {
    return ['error' => 'Comment cannot be empty.'];
  }
  if (empty($username)) {
    return ['error' => 'Username cannot be empty.'];
  }

  // Sanitize input -  Important for security!
  $productId = filter_var($productId, FILTER_SANITIZE_STRING);
  $comment = filter_var($comment, FILTER_SANITIZE_STRING);
  $username = filter_var($username, FILTER_SANITIZE_STRING);

  // Create review data
  $reviewData = [
    'productId' => $productId,
    'rating' => $rating,
    'comment' => $comment,
    'username' => $username,
    'timestamp' => time() // Add timestamp for ordering
  ];

  // For demonstration purposes, we'll just return the data.
  // In a real application, you would save this data to a database.
  return $reviewData;
}

/**
 * Display a Single Review
 *
 * Displays a single review from a list of reviews.
 *
 * @param array $review A single review object.
 * @return string HTML representation of the review.
 */
function display_review(array $review): string
{
    $timestamp = date('Y-m-d H:i:s', strtotime($review['timestamp']));

    return "<div class='review'>
                <strong>Username:</strong> " . htmlspecialchars($review['username']) . "<br>
                <strong>Rating:</strong> " . $review['rating'] . " / 5<br>
                <strong>Timestamp:</strong> " . $timestamp . "<br>
                <strong>Comment:</strong> " . htmlspecialchars($review['comment']) . "
             </div>";
}

/**
 * Display All Reviews for a Product
 *
 *  Displays all reviews for a given product.
 *  Uses the 'display_review' function to render each review.
 *  Sorts the reviews by timestamp (newest first).
 *
 * @param array $reviews An array of review objects.
 * @return string HTML representation of all the reviews.
 */
function display_all_reviews(array $reviews): string
{
    // Sort reviews by timestamp (newest first)
    usort($reviews, function ($a, $b) {
        return $b['timestamp'] - $a['timestamp'];
    });

    $html = '';
    foreach ($reviews as $review) {
        $html .= display_review($review);
    }

    return $html;
}


// Example Usage:
$productId = '123';
$rating = 4;
$comment = 'This product is great!';
$username = 'john.doe';

$reviewData = submit_review($productId, $rating, $comment, $username);

if (isset($reviewData['error'])) {
    echo "<p>Error: " . $reviewData['error'] . "</p>";
} else {
    echo "Review submitted successfully!";
    echo "<br><br>";

    // Example of displaying reviews for a product (Simulated data for demonstration)
    $reviews = [
        ['productId' => '123', 'rating' => 5, 'comment' => 'Excellent product!', 'username' => 'jane.smith', 'timestamp' => time() - 3600),  // 1 hour ago
        ['productId' => '123', 'rating' => 3, 'comment' => 'Good, but could be better.', 'username' => 'peter.jones', 'timestamp' => time() - 7200), // 2 hours ago
        ['productId' => '123', 'rating' => 4, 'comment' => 'Very happy with this purchase.', 'username' => 'mary.brown', 'timestamp' => time() - 10800),  // 3 hours ago
    ];

    echo "<h2>Reviews for Product " . $productId . "</h2>";
    echo display_all_reviews($reviews);

}

?>
