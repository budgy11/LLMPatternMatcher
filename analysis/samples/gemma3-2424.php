

<?php

/**
 * User Review Function
 *
 * This function allows users to submit and display user reviews.
 *
 * @param string $productId The ID of the product being reviewed.
 * @param string $reviewText The text of the review submitted by the user.
 * @param string $reviewerName The name of the user submitting the review.
 * @param int $rating (Optional)  A rating from 1-5. Defaults to 0.
 *
 * @return array  An array containing the review data, or an error message if the review fails.
 */
function submitReview(string $productId, string $reviewText, string $reviewerName, int $rating = 0) {

  // Input validation - basic checks
  if (empty($reviewText)) {
    return ['status' => 'error', 'message' => 'Review text cannot be empty.'];
  }
  if (empty($reviewerName)) {
    return ['status' => 'error', 'message' => 'Reviewer name cannot be empty.'];
  }

  // Validation for rating if provided
  if ($rating < 1 || $rating > 5) {
    return ['status' => 'error', 'message' => 'Rating must be between 1 and 5.'];
  }


  // Store the review (Simulating a database insert - replace with your database logic)
  $review = [
    'product_id' => $productId,
    'reviewer_name' => $reviewerName,
    'review_text' => $reviewText,
    'rating' => $rating,
    'timestamp' => time() // Add a timestamp
  ];

  //Simulate database insertion. In a real application, use a database query.
  //This example just returns the review.
  return $review;

}

/**
 * Display Reviews Function
 *
 * This function takes an array of reviews and displays them in a user-friendly format.
 *
 * @param array $reviews An array of review data (as returned by submitReview).
 */
function displayReviews(array $reviews) {
    echo "<h2>Reviews for Product ID: " . $reviews[0]['product_id'] . "</h2>"; // Assuming product ID is in the first element of the array.

    if (empty($reviews)) {
        echo "<p>No reviews yet.</p>";
        return;
    }

    echo "<ul>";
    foreach ($reviews as $review) {
        echo "<li>";
        echo "<strong>Reviewer:</strong> " . htmlspecialchars($review['reviewer_name']) . "<br>";
        echo "<strong>Rating:</strong> " . $review['rating'] . " / 5<br>";
        echo "<strong>Review:</strong> " . htmlspecialchars($review['review_text']) . "<br>";
        echo "<strong>Timestamp:</strong> " . date('Y-m-d H:i:s', $review['timestamp']) . "</li>";
    }
    echo "</ul>";
}

// Example Usage:
$product_id = "123";

// Submit a review
$review_data = submitReview($product_id, "This is a fantastic product!", "John Doe", 5);

if ($review_data['status'] === 'success') {
    echo "<h2>Review Submitted Successfully!</h2>";
    echo "<p>Review ID: " . $review_data['id'] . "</p>";  // Assuming you've added a unique ID
} else {
    echo "<p style='color: red;'>Error submitting review: " . $review_data['message'] . "</p>";
}

// Display the review
displayReviews($review_data);

// Example of submitting an invalid review
$invalid_review = submitReview($product_id, "", "Jane Smith");
if ($invalid_review['status'] === 'error') {
    echo "<p style='color: red;'>Error submitting review: " . $invalid_review['message'] . "</p>";
}
?>
