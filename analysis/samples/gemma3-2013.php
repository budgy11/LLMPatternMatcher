

<?php

/**
 * User Review Function
 *
 * This function allows users to submit and view reviews.
 *
 * @param string $productName The name of the product being reviewed.
 * @param string $rating A rating from 1 to 5.
 * @param string $comment The user's review comment.
 * @param string $username The user's name.
 * @return array An array containing all reviews, or an empty array if no reviews exist.
 */
function createAndDisplayReviews(string $productName, string $rating, string $comment, string $username): array
{
  // Input validation - Basic example, expand as needed
  if (empty($productName)) {
    return [];
  }
  if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
    return []; // Invalid rating
  }
  if (empty($comment)) {
    return [];
  }

  // Store reviews (In a real application, you'd use a database)
  $reviews = [];

  // Add the review to the array
  $reviews[] = [
    'product' => $productName,
    'rating' => (int)$rating, // Ensure rating is an integer
    'comment' => $comment,
    'username' => $username,
    'timestamp' => date('Y-m-d H:i:s')
  ];

  // Return all reviews
  return $reviews;
}


/**
 * Displays existing reviews
 *
 *  This function takes an array of reviews and formats them for display.
 *
 *  @param array $reviews An array of reviews.
 *  @return string  HTML formatted output for displaying reviews.
 */
function displayReviews(array $reviews): string
{
    if (empty($reviews)) {
        return "<p>No reviews yet!</p>";
    }

    $html = "<ul>";
    foreach ($reviews as $review) {
        $html .= "<li>";
        $html .= "<strong>" . htmlspecialchars($review['username']) . ":</strong> "; //Use htmlspecialchars for safety
        $html .= "Rating: " . $review['rating'] . " - ";
        $html .= htmlspecialchars($review['comment']) . "<br>";
        $html .= "<em>" . date('Y-m-d H:i:s', strtotime($review['timestamp'])) . "</em>";
        $html .= "</li>";
    }
    $html .= "</ul>";

    return $html;
}


// Example Usage:

// Get the product name and review details
$product = "Awesome Widget";
$rate = 4;
$commentText = "This widget is amazing!  Highly recommended.";
$user = "John Doe";

// Create a review
$allReviews = createAndDisplayReviews($product, $rate, $commentText, $user);

// Display all reviews
echo displayReviews($allReviews);



?>
