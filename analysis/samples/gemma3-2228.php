

<?php

/**
 * User Review Function
 *
 * This function allows you to store and display user reviews for a given product or item.
 *
 * @param string $productName The name of the product or item being reviewed.
 * @param string $userId The ID of the user submitting the review.
 * @param string $rating A rating from 1 to 5.
 * @param string $comment The user's written review.
 *
 * @return bool True if the review was successfully saved, false otherwise.
 */
function saveUserReview(string $productName, string $userId, string $rating, string $comment) {
  // Basic validation - add more robust validation as needed
  if (empty($productName) || empty($userId) || empty($rating) || empty($comment)) {
    error_log("Error: Missing required fields for review."); // Log the error for debugging
    return false;
  }

  if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
    error_log("Error: Invalid rating. Rating must be a number between 1 and 5.");
    return false;
  }

  // Simulate saving to a database (replace with your actual database interaction)
  $review = [
    'product_name' => $productName,
    'user_id' => $userId,
    'rating' => $rating,
    'comment' => $comment,
    'date_created' => date("Y-m-d H:i:s") // Add a timestamp for organization
  ];

  // Save to database (example - adapt to your database setup)
  $db = new PDO('mysql:host=localhost;dbname=your_database_name', 'your_db_user', 'your_db_password'); // Replace with your database details

  try {
    $stmt = $db->prepare("INSERT INTO reviews (product_name, user_id, rating, comment, date_created) VALUES (:product_name, :user_id, :rating, :comment, :date_created)");
    $stmt->bindParam(':product_name', $review['product_name']);
    $stmt->bindParam(':user_id', $review['user_id']);
    $stmt->bindParam(':rating', $review['rating']);
    $stmt->bindParam(':comment', $review['comment']);
    $stmt->bindParam(':date_created', $review['date_created']);
    $stmt->execute();

    return true; // Successfully saved
  } catch (PDOException $e) {
    error_log("Error saving review: " . $e->getMessage()); // Log the error for debugging
    return false; // Error saving
  }
}

/**
 * Display User Reviews for a Product
 *
 * Fetches and displays reviews for a given product.
 *
 * @param string $productName The name of the product.
 * @return array An array of review objects, or an empty array if no reviews are found.
 */
function displayUserReviews(string $productName) {
  // Example using a simulated database. Replace with your database query.
  // This is just for demonstration.

  $db = new PDO('mysql:host=localhost;dbname=your_database_name', 'your_db_user', 'your_db_password'); // Replace with your database details
  $stmt = $db->prepare("SELECT * FROM reviews WHERE product_name = :product_name");
  $stmt->bindParam(':product_name', $productName);
  $stmt->execute();

  $reviews = $stmt->fetchAll(PDO::FETCH_OBJ);
  return $reviews;
}

// Example Usage (Illustrative - adapt to your application)
// You'll need to implement your own form and display logic

// Save a review
if (saveUserReview('Awesome Widget', 'user123', 4, 'Great product!  Highly recommended.') === true) {
  echo "<p>Review saved successfully!</p>";
} else {
  echo "<p>Error saving review.</p>";
}

// Display reviews for 'Awesome Widget'
$reviews = displayUserReviews('Awesome Widget');

if (count($reviews) > 0) {
  echo "<h3>Reviews for Awesome Widget:</h3>";
  foreach ($reviews as $review) {
    echo "<p><strong>Rating:</strong> " . $review->rating . "<br>";
    echo "<strong>Comment:</strong> " . $review->comment . "<br>";
    echo "<strong>User:</strong> " . $review->user_id . "</p>";
  }
} else {
  echo "<p>No reviews found for this product.</p>";
}

?>
