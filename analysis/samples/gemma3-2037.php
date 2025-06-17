

<?php

/**
 * User Review Function
 * 
 * This function allows you to collect and display user reviews for a given item (e.g., product, movie, etc.).
 * It uses a simple form to collect the review and then displays it.
 *
 * @param string $itemName The name of the item being reviewed.
 * @return array An array containing the review text, rating, and timestamp.
 *               Returns an empty array if there's an error.
 */
function collectUserReview(string $itemName) {
  // Sanitize input - crucial for security!  This prevents XSS attacks.
  $reviewText = htmlspecialchars(trim($_POST['reviewText']));
  $rating = (int) $_POST['rating']; // Cast to integer to ensure numeric rating

  // Validate input - Add more validation as needed (e.g., min/max rating, review length)
  if (empty($reviewText)) {
    return []; // Return empty array for empty review
  }
  if ($rating < 1 || $rating > 5) {
    return []; // Return empty array for invalid rating
  }

  // Store the review (in a real application, you'd likely use a database)
  // For demonstration, we'll just store it in an array.
  $review = [
    'itemName' => $itemName,
    'reviewText' => $reviewText,
    'rating' => $rating,
    'timestamp' => time()
  ];

  return $review;
}

// Example Usage (Demonstration -  Replace with your database interaction)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $review = collectUserReview($_POST['itemName']);

  if (!empty($review)) {
    echo "<h2>Review submitted for " . htmlspecialchars($review['itemName']) . "</h2>";
    echo "<p><strong>Rating:</strong> " . $review['rating'] . "</p>";
    echo "<p><strong>Review:</strong> " . htmlspecialchars($review['reviewText']) . "</p>";
    echo "<p><strong>Timestamp:</strong> " . date("Y-m-d H:i:s", $review['timestamp']) . "</p>";
  } else {
    echo "<p style='color:red;'>Please fill out the form correctly and ensure valid input.</p>";
  }
}
?>
