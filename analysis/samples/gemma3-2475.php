

<?php

/**
 * User Review Function
 *
 * This function allows you to create, display, and manage user reviews for a given product or item.
 *
 * @param string $productId The ID of the product/item being reviewed.
 * @param string $productName The name of the product/item.
 * @param string $reviewText The text of the review submitted by the user.
 * @param string $username The username of the reviewer.
 * @param int $rating (optional) The rating given by the user (1-5). Defaults to 0.
 * @return array An array containing the review data or an error message.
 */
function createReview(string $productId, string $productName, string $reviewText, string $username, int $rating = 0) {
  // Input validation - crucial for security and data integrity
  if (empty($productId) || empty($productName) || empty($reviewText) || empty($username)) {
    return ['error' => 'All fields are required.'];
  }

  if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
    return ['error' => 'Rating must be a number between 1 and 5.'];
  }

  // **Important:  Sanitization and Data Sanitization**
  //  You MUST sanitize user input to prevent XSS and SQL injection vulnerabilities.
  //  This is a simplified example; you'll need to adapt this to your database setup.
  $reviewText = htmlspecialchars($reviewText); // Escape HTML entities
  //  Consider using a database query with prepared statements to protect against SQL injection.

  // **Simulated Database Storage (Replace with your actual database interaction)**
  // In a real application, you'd use a database.
  $review = [
    'productId' => $productId,
    'productName' => $productName,
    'reviewText' => $reviewText,
    'username' => $username,
    'rating' => $rating,
    'date' => date('Y-m-d H:i:s') // Add a timestamp for sorting/display
  ];

  // Add the review to an array (simulating a database insertion)
  $reviews = getReviewsForProduct($productId); //  Retrieve existing reviews
  $reviews[] = $review; 

  return $reviews;
}



/**
 * Retrieves all reviews for a specific product.
 * 
 * @param string $productId The ID of the product.
 * @return array An array of review objects.  Empty array if no reviews found.
 */
function getReviewsForProduct(string $productId) {
  //Simulated retrieval from database
  // In a real scenario, this would query your database.
  $reviews = [
    ['productId' => '123', 'productName' => 'Awesome Widget', 'reviewText' => 'Great product!', 'username' => 'john_doe', 'rating' => 5, 'date' => '2023-10-27 10:00:00'],
    ['productId' => '123', 'productName' => 'Awesome Widget', 'reviewText' => 'Works as expected.', 'username' => 'jane_smith', 'rating' => 4, 'date' => '2023-10-26 14:30:00'],
    ['productId' => '456', 'productName' => 'Basic Gadget', 'reviewText' => 'Okay, but overpriced.', 'username' => 'peter_jones', 'rating' => 2, 'date' => '2023-10-25 09:15:00']
  ];
  
  // Filter reviews for the given product ID
  $filteredReviews = [];
  foreach($reviews as $review){
    if ($review['productId'] == $productId) {
      $filteredReviews[] = $review;
    }
  }
  
  return $filteredReviews;
}


/**
 * Displays a list of reviews for a product.
 * 
 * @param array $reviews An array of review objects.
 */
function displayReviews(array $reviews) {
  if (empty($reviews)) {
    echo "<p>No reviews yet.</p>";
    return;
  }

  echo "<h2>Reviews for " . $reviews[0]['productName'] . "</h2>"; // Assume first product is the relevant one
  echo "<ul>";
  foreach ($reviews as $review) {
    echo "<li>";
    echo "<p><strong>" . $review['username'] . ":</strong> " . $review['reviewText'] . "</p>";
    echo "<p>Rating: " . $review['rating'] . " stars</p>";
    echo "<p>Date: " . $review['date'] . "</p>";
    echo "</li>";
  }
  echo "</ul>";
}



// Example Usage:
$productId = '123';
$productName = 'Awesome Widget';

// Create a review
$reviewResult = createReview($productId, $productName, "This is an amazing product!", "john_doe", 5);

if (isset($reviewResult['error'])) {
    echo "<p style='color: red;'>Error: " . $reviewResult['error'] . "</p>";
} else {
  // Display the reviews
  displayReviews($reviewResult);
}

?>
