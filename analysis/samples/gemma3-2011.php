

<?php

/**
 * User Review Function
 * 
 * This function takes user input for a review and returns a formatted HTML string 
 * displaying the review with user information.
 *
 * @param string $username The username of the reviewer.
 * @param string $reviewText The text of the review.
 * @param int $rating (Optional)  The rating (1-5).  Defaults to 0.
 * @param string $timestamp (Optional)  The timestamp for the review. Defaults to current time.
 * @return string HTML formatted review
 */
function generateReviewHTML(string $username, string $reviewText, int $rating = 0, string $timestamp = null) {
  // Validate input (basic example, you can expand on this)
  if (empty($username) || empty($reviewText)) {
    return "<p>Error: Username and review text are required.</p>";
  }

  if ($rating < 1 || $rating > 5) {
    $rating = 0; // Default to 0 if invalid
  }

  // Format timestamp if provided
  $formattedTimestamp = $timestamp ? date('Y-m-d H:i:s') : date('Y-m-d H:i:s');

  // Build the HTML
  $html = "<div class='review'>";
  $html .= "<p><strong>User:</strong> " . htmlspecialchars($username) . "</p>";
  $html .= "<p><strong>Rating:</strong> " . $rating . " / 5</p>";
  $html .= "<p><strong>Review:</strong> " . htmlspecialchars($reviewText) . "</p>";
  $html .= "<p><strong>Timestamp:</strong> " . $formattedTimestamp . "</p>";
  $html .= "</div>";

  return $html;
}

// Example Usage:
$username = "JohnDoe";
$review = "This product is amazing!  I highly recommend it!";
$rating = 5;
$timestamp = time();  // Use current timestamp

$reviewHTML = generateReviewHTML($username, $review, $rating, $timestamp);
echo $reviewHTML;

// Another Example (without timestamp):
$username = "JaneSmith";
$review = "The product was okay, but could be better.";
$reviewHTML = generateReviewHTML($username, $review);
echo $reviewHTML;

?>
