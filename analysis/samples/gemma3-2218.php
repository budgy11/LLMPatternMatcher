

<?php

/**
 * User Review Function
 *
 * This function allows users to submit and view reviews for a product or service.
 *
 * @param string $productName  The name of the product or service being reviewed.
 * @param string $reviewText   The text of the review submitted by the user.
 * @param string $userEmail    The email address of the user submitting the review.
 * @param array  $reviews      (Optional) An array of existing reviews (for demonstration/expansion).
 *
 * @return array  An array containing the new review and the updated list of reviews.
 */
function submitReview(string $productName, string $reviewText, string $userEmail, array &$reviews = []) {
  // Input validation -  This is crucial!
  if (empty($productName)) {
    return ["error" => "Product name cannot be empty."];
  }
  if (empty($reviewText)) {
    return ["error" => "Review text cannot be empty."];
  }
  if (empty($userEmail)) {
    return ["error" => "User email cannot be empty."];
  }

  // Create the new review object
  $newReview = [
    "product_name" => $productName,
    "review_text" => $reviewText,
    "user_email" => $userEmail,
    "timestamp" => date("Y-m-d H:i:s") // Add a timestamp
  ];

  // Add the new review to the array
  $reviews[] = $newReview;

  return ["success" => "Review submitted successfully!", "review" => $newReview, "reviews" => $reviews];
}


/**
 * Function to display reviews
 *
 * Displays a list of reviews, sorted by most recent.
 *
 * @param array $reviews  The array of reviews.
 */
function displayReviews(array $reviews) {
    echo "<h2>Reviews for " . $reviews[0]['product_name'] . "</h2>"; //Dynamically uses product name from first review.
    if (empty($reviews)) {
        echo "<p>No reviews yet.</p>";
        return;
    }

    echo "<ul>";
    usort($reviews, function($a, $b) {
        return $b["timestamp"] <=> $a["timestamp"]; // Sort by timestamp (most recent first)
    });

    foreach ($reviews as $review) {
        echo "<li>";
        echo "<p><strong>" . $review['user_email'] . ":</strong> " . htmlspecialchars($review['review_text']) . "</p>";
        echo "<p><i>Timestamp: " . $review['timestamp'] . "</i></p>";
        echo "</li>";
    }
    echo "</ul>";
}



// Example Usage:

// Simulate a database (replace with your actual database interaction)
$currentReviews = [];

// Submit a review
$result = submitReview("Awesome Widget", "This widget is fantastic!  Highly recommended.", "user@example.com");

if (isset($result["error"])) {
    echo "<p style='color: red;'>Error: " . $result["error"] . "</p>";
} else {
    echo "<p style='color: green;'>Review submitted successfully!</p>";
}


// Submit another review
$result = submitReview("Super Gadget", "Great product, easy to use.", "anotheruser@example.com");

if (isset($result["error"])) {
    echo "<p style='color: red;'>Error: " . $result["error"] . "</p>";
} else {
    echo "<p style='color: green;'>Review submitted successfully!</p>";
}


// Display the reviews
displayReviews($currentReviews);


?>
