

<?php

/**
 * User Review Function
 *
 * This function allows users to submit and display reviews for a product or item.
 * It includes input validation and basic sanitization.
 *
 * @param string $productName The name of the product being reviewed.
 * @param string $reviewText  The review text submitted by the user.
 * @param array $allowedHTML  An array of allowed HTML tags (optional, defaults to empty array).
 *
 * @return array An array containing:
 *               - 'success': True if the review was successfully submitted.
 *               - 'message': A message indicating the status of the submission.
 *               - 'review': The submitted review text.
 */
function submitReview(string $productName, string $reviewText, array $allowedHTML = []) {

  // Input Validation and Sanitization
  if (empty($productName)) {
    return [
      'success' => false,
      'message' => 'Product name cannot be empty.',
      'review' => ''
    ];
  }

  if (empty($reviewText)) {
    return [
      'success' => false,
      'message' => 'Review text cannot be empty.',
      'review' => ''
    ];
  }

  // Basic HTML Sanitization (example - customize as needed)
  $reviewText = strip_tags($reviewText, $allowedHTML); // Remove tags from the allowed list

  // You can add more sophisticated sanitization here, like escaping HTML entities
  // or using a more robust HTML sanitization library.

  // Data Storage (Simple example - consider database for real applications)
  $reviews = ['product' => $productName, 'review' => $reviewText];

  // Store the review (replace with database logic in a real application)
  // For example:
  // $result = insertReviewIntoDatabase($reviews);

  // If the insertion was successful (replace with your database query result)
  // $success = true;
  // $message = 'Review submitted successfully.';
  // $review = $reviewText;
  // else {
  //  $success = false;
  //  $message = 'Error submitting review.';
  //  $review = '';
  // }


  // Simulate a successful submission
  $success = true;
  $message = 'Review submitted successfully.';
  $review = $reviewText;


  return [
    'success' => $success,
    'message' => $message,
    'review' => $review
  ];
}

/**
 * Displays a review form and a list of reviews.
 */
function displayReviews() {
  echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "'>";
  echo "Product Name: <input type='text' name='productName'><br>";
  echo "<textarea name='reviewText' rows='5' cols='50'></textarea><br>";
  echo "<input type='submit' value='Submit Review'>";
  echo "</form>";

  echo "<br>";

  echo "<h2>Reviews:</h2>";

  // Simulated review data (replace with data retrieval from database)
  $reviews = [
    ['product' => 'Laptop X1', 'review' => 'Excellent laptop! Fast and reliable.'],
    ['product' => 'Mouse M2', 'review' => 'Great mouse, comfortable to use.'],
    ['product' => 'Keyboard K3', 'review' => 'Good keyboard, but the keys feel a little stiff.'],
  ];


  echo "<ul>";
  foreach ($reviews as $review) {
    echo "<li>Product: " . $review['product'] . ", Review: " . $review['review'] . "</li>";
  }
  echo "</ul>";
}


// Example Usage:
displayReviews();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $productName = $_POST["productName"];
  $reviewText = $_POST["reviewText"];

  $result = submitReview($productName, $reviewText);

  if ($result['success']) {
    echo "<p style='color:green;'> " . $result['message'] . "</p>";
    // You could redirect the user or display a confirmation message.
  } else {
    echo "<p style='color:red;'> " . $result['message'] . "</p>";
  }
}
?>
