

<?php

/**
 * User Review Function
 *
 * This function allows users to submit and display reviews.
 *
 * @param string $productName The name of the product being reviewed.
 * @param string $reviewText The text of the review.
 * @param string $userId (Optional) The ID of the user submitting the review.  Used for tracking and potentially preventing duplicates.
 * @return array An array containing the review details or an error message.
 */
function createReview(string $productName, string $reviewText, string $userId = "") {
  // Input validation - Basic example.  Expand this for more robust validation.
  if (empty($productName)) {
    return ["error" => "Product name cannot be empty."];
  }
  if (empty($reviewText)) {
    return ["error" => "Review text cannot be empty."];
  }

  // In a real application, you'd store this in a database.
  // This is a placeholder for demonstration purposes.
  $review = [
    "product_name" => $productName,
    "review_text" => $reviewText,
    "user_id" => $userId,
    "date_created" => date("Y-m-d H:i:s")
  ];

  return $review;
}


/**
 * Display User Reviews
 *
 * This function retrieves and displays user reviews for a given product.
 *
 * @param string $productName The name of the product.
 * @return string HTML markup for displaying the reviews or a message if no reviews exist.
 */
function displayReviews(string $productName) {
  // Placeholder for retrieving reviews from a database.
  // In a real application, you'd query your database.

  $reviews = [
    ["product_name" => "Awesome Gadget", "review_text" => "This gadget is amazing! Highly recommended.", "user_id" => "user123", "date_created" => "2023-10-26 10:00:00"],
    ["product_name" => "Awesome Gadget", "review_text" => "Great value for the price.", "user_id" => "user456", "date_created" => "2023-10-27 14:30:00"],
    ["product_name" => "Basic Widget", "review_text" => "It does the job, but nothing special.", "user_id" => "user789", "date_created" => "2023-10-28 09:15:00"]
  ];


  if (empty($reviews)) {
    return "<p>No reviews yet for this product.</p>";
  }

  $html = "<h2>Reviews for " . $productName . "</h2>";
  $html .= "<ul>";
  foreach ($reviews as $review) {
    $html .= "<li><strong>" . $review['product_name'] . "</strong> - " . $review['review_text'] . " (by " . $review['user_id'] . ") - " . $review['date_created'] . "</li>";
  }
  $html .= "</ul>";
  return $html;
}



// Example Usage:

// Create a review
$newReview = createReview("Awesome Gadget", "This is the best gadget I've ever bought!");

if (isset($newReview["error"])) {
  echo "<p style='color: red;'>Error creating review: " . $newReview["error"] . "</p>";
} else {
  // Display the review
  echo displayReviews("Awesome Gadget");
}


?>
