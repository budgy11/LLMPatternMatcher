

<?php

/**
 * User Review Function
 *
 * This function allows users to submit and display reviews for a given product or item.
 *
 * @param string $productId The ID of the product/item being reviewed.
 * @param string $productName The name of the product/item (for display).
 * @param string $reviewerName The name of the reviewer.
 * @param string $reviewText The text of the review.
 * @param array $reviews Array to store reviews (passed by reference).
 * @return void
 */
function addReview(string $productId, string $productName, string $reviewerName, string $reviewText, &$reviews) {

  // Sanitize input (very important to prevent XSS and SQL Injection)
  $reviewerName = htmlspecialchars($reviewerName);
  $reviewText = htmlspecialchars($reviewText);

  // Create a review object (or you can just use an array)
  $review = [
    'productId' => $productId,
    'reviewerName' => $reviewerName,
    'reviewText' => $reviewText,
    'timestamp' => date('Y-m-d H:i:s'), // Add timestamp for ordering
  ];

  // Add the review to the array.  Since reviews is passed by reference,
  // the array is modified directly.
  $reviews[] = $review;
}

/**
 * Display Reviews Function
 *
 * This function displays the reviews for a given product/item.
 *
 * @param array $reviews Array of reviews (passed by reference).
 * @param string $productName The name of the product/item (for display).
 */
function displayReviews(array &$reviews, string $productName) {
  echo "<h2>Reviews for " . $productName . "</h2>";

  if (empty($reviews)) {
    echo "<p>No reviews yet.</p>";
  } else {
    echo "<ul>";
    foreach ($reviews as $review) {
      echo "<li>";
      echo "<strong>" . $review['reviewerName'] . ":</strong> " . $review['reviewText'] . "<br>";
      echo "Date: " . $review['timestamp'];
      echo "</li>";
    }
    echo "</ul>";
  }
}


// Example Usage:
// Initialize an empty array to store reviews
$reviews = [];

// Add some reviews
addReview('product123', 'Awesome Gadget', 'John Doe', 'This gadget is fantastic!  I highly recommend it.', $reviews);
addReview('product123', 'Awesome Gadget', 'Jane Smith', 'Great value for money.', $reviews);
addReview('product456', 'Cool Device', 'Peter Jones', 'A bit buggy, but generally good.', $reviews);

// Display the reviews
displayReviews($reviews, 'Awesome Gadget');


// Example of how to add a review via form submission (simplified)
//  This requires a form that submits the data to this PHP script.
//  You'll need to implement the HTML form and handle the form submission appropriately.
//  This is just an illustration.

/*
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productId = $_POST["product_id"];
    $reviewerName = $_POST["reviewer_name"];
    $reviewText = $_POST["review_text"];

    addReview($productId, $productName, $reviewerName, $reviewText, $reviews);
    // Redirect to display the reviews
    header("Location: display_reviews.php"); // Redirect to a page displaying the reviews
    exit();
}
*/
?>
