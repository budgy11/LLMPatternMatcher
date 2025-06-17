

<?php

/**
 * User Review Function
 *
 * This function allows you to create and display user reviews for a product or item.
 * It includes basic functionality for adding, displaying, and potentially
 * sorting reviews.
 *
 * @param string $productName The name of the product being reviewed.
 * @param array $reviews An array of user reviews.  Each review should be an associative array
 *                      with keys like 'user', 'rating', and 'comment'.
 * @return void
 */
function displayUserReviews(string $productName, array $reviews) {

  // Security: Sanitize input (essential for preventing XSS)
  $productName = htmlspecialchars($productName);
  foreach ($reviews as $review) {
    $review['user'] = htmlspecialchars($review['user']);
    $review['comment'] = htmlspecialchars($review['comment']);
  }

  echo "<h2>Reviews for " . $productName . "</h2>";

  if (empty($reviews)) {
    echo "<p>No reviews yet. Be the first to review!</p>";
  } else {
    echo "<ul>";
    foreach ($reviews as $review) {
      echo "<li>";
      echo "<strong>" . $review['user'] . ":</strong> " . $review['comment'] . " (Rating: " . $review['rating'] . ")";
      echo "</li>";
    }
    echo "</ul>";
  }
}


/**
 *  Add a new review (example function - can be expanded)
 *
 *  This function adds a new review to the reviews array.  
 *  Important: Add proper security checks and validation in a production environment!
 *
 * @param string $user
 * @param int $rating
 * @param string $comment
 */
function addReview(string $user, int $rating, string $comment) {
  // Basic Validation (In a real application, you'd use more robust validation)
  if (empty($user) || $rating < 1 || $rating > 5 || empty($comment)) {
    echo "<p>Invalid review data. Please provide a valid user, rating (1-5), and comment.</p>";
    return;
  }

  $review = [
    'user' => $user,
    'rating' => $rating,
    'comment' => $comment
  ];

  // In a real application, you'd store this in a database or session.
  // This is just a demo.
  global $reviews; // Access the global $reviews array
  $reviews[] = $review;
}



// Example Usage:
// Initialize the reviews array
$reviews = [];

// Add some reviews
addReview("John Doe", 4, "Great product, highly recommended!");
addReview("Jane Smith", 5, "Excellent quality and fast shipping.");
addReview("Peter Jones", 3, "It's okay, but could be better.");

// Display the reviews
displayUserReviews("Awesome Gadget", $reviews);

// Add another review (demonstration)
addReview("Alice Brown", 2, "The battery life is terrible.");

// Display the reviews again
displayUserReviews("Awesome Gadget", $reviews);


?>
