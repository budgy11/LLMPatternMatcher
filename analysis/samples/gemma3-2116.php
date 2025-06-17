

<?php

/**
 * User Review Function
 *
 * This function allows users to submit and display reviews for a product or service.
 *
 * @param string $productName The name of the product or service being reviewed.
 * @param string $reviewText The text of the review submitted by the user.
 * @param int $userId (Optional) The ID of the user submitting the review.  Useful for tracking and moderation.
 * @param string $reviewerName (Optional)  The name of the reviewer, if not provided, uses $userId.
 *
 * @return array An array containing the review details and a success/failure message.
 */
function submitReview(string $productName, string $reviewText, int $userId = 0, string $reviewerName = '') {

  // Input Validation - VERY IMPORTANT
  if (empty($productName)) {
    return ['success' => false, 'message' => 'Product name cannot be empty.'];
  }
  if (empty($reviewText)) {
    return ['success' => false, 'message' => 'Review text cannot be empty.'];
  }

  // Sanitize input - Protect against XSS and other vulnerabilities
  $productName = htmlspecialchars($productName);
  $reviewText = htmlspecialchars($reviewText);

  // Determine Reviewer Name
  if (empty($reviewerName)) {
    $reviewerName = $userId > 0 ?  "User ID: " . $userId : 'Anonymous';
  }

  // Store the Review - In a real application, this would likely involve database interaction
  //  This is just a placeholder for demonstration
  $review = [
    'product_name' => $productName,
    'review_text' => $reviewText,
    'user_id' => $userId,
    'reviewer_name' => $reviewerName,
    'submission_timestamp' => time() // Add a timestamp for tracking
  ];

  // Simulate saving to a file or database
  file_put_contents('reviews.txt', $review . PHP_EOL, FILE_APPEND);  // Append to a file

  return ['success' => true, 'review' => $review, 'message' => 'Review submitted successfully!'];
}

/**
 * Displays a list of reviews.
 *
 * @param array $reviews An array of review data.
 */
function displayReviews(array $reviews) {
    echo "<h2>Reviews for " .  $reviews[0]['product_name'] . "</h2>";

    if (empty($reviews)) {
        echo "<p>No reviews yet.</p>";
        return;
    }

    echo "<ul>";
    foreach ($reviews as $review) {
        echo "<li>";
        echo "<strong>" . $review['reviewer_name'] . ":</strong> " . $review['review_text'] . "<br>";
        echo "<strong>Date:</strong> " . date('Y-m-d H:i:s', $review['submission_timestamp']);
        echo "</li>";
    }
    echo "</ul>";
}


// Example Usage:

// Simulate a form submission (replace this with your actual form handling)
$product = "Awesome Gadget X";
$reviewText = "This gadget is amazing!  It does everything I need, and it's super easy to use.";
$userId = 123; // Example user ID

$result = submitReview($product, $reviewText, $userId);

if ($result['success']) {
    echo "<p>Review submitted successfully!</p>";
    displayReviews($result); //  Display the submitted review
} else {
    echo "<p>Error submitting review: " . $result['message'] . "</p>";
}


// Example for anonymous user:
$resultAnon = submitReview("Another Product", "Great product!", 0, "");
if ($resultAnon['success']) {
  displayReviews($resultAnon);
}
?>
