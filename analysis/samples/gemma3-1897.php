

<?php

/**
 * User Review Function
 *
 * This function allows you to store and display user reviews for a product or service.
 *
 * @param string $productId The unique identifier of the product or service.
 * @param string $userName The name of the user submitting the review.
 * @param string $reviewText The text of the review.
 * @param int    $rating    The rating given by the user (1-5).
 * @param int    $userId    The unique identifier of the user.  (Optional - for more robust tracking)
 *
 * @return array An array containing the review data or an error message if the review couldn't be saved.
 */
function saveReview(string $productId, string $userName, string $reviewText, int $rating, int $userId = null) {
  // Input Validation - Add more robust validation as needed
  if (empty($productId)) {
    return ["error" => "Product ID cannot be empty."];
  }
  if (empty($userName)) {
    return ["error" => "User name cannot be empty."];
  }
  if (empty($reviewText)) {
    return ["error" => "Review text cannot be empty."];
  }
  if ($rating < 1 || $rating > 5) {
    return ["error" => "Rating must be between 1 and 5."];
  }
  if ($userId === null || empty($userId)) {
    // Optionally, generate a unique ID or use a database-generated ID
    $userId = generateUniqueUserId(); // Implement this function as needed.
  }


  // **Data Storage -  Replace this with your database logic**
  // In a real application, you would save this data to a database.
  // This is a placeholder for demonstration purposes.

  $review = [
    'productId' => $productId,
    'userName' => $userName,
    'reviewText' => $reviewText,
    'rating' => $rating,
    'userId' => $userId,
    'timestamp' => time() // Add timestamp for ordering/filtering
  ];

  // Save the review (replace with your database saving logic)
  // Example:
  // $result = saveToDatabase($review);

  // Mock save to a simple array (for demonstration)
  $reviews =  getReviewsForProduct($productId); // Assume this function exists
  $reviews[] = $review;

  //Return the review
  return $review;
}



/**
 * Placeholder function to retrieve reviews for a product.
 * Replace with your actual database query.
 *
 * @param string $productId
 * @return array
 */
function getReviewsForProduct(string $productId)
{
  // This is just a placeholder - replace with your actual database query
  $reviews = [
    ['productId' => '123', 'userName' => 'John Doe', 'reviewText' => 'Great product!', 'rating' => 5],
    ['productId' => '123', 'userName' => 'Jane Smith', 'reviewText' => 'Could be better.', 'rating' => 3]
  ];
  return $reviews;
}

/**
 * Placeholder function to generate a unique user ID.
 * Replace with your actual user ID generation logic.
 *
 * @return int
 */
function generateUniqueUserId() {
  // This is a placeholder - replace with your actual user ID generation logic
  return uniqid();
}



/**
 * Displays a review.
 *
 * @param array $review  The review data.
 */
function displayReview(array $review) {
  if (empty($review)) {
    echo "No review available.";
    return;
  }

  echo "<h3>Review for Product ID: " . $review['productId'] . "</h3>";
  echo "<p><strong>User:</strong> " . htmlspecialchars($review['userName']) . "</p>";
  echo "<p><strong>Rating:</strong> " . $review['rating'] . " / 5</p>";
  echo "<p>" . htmlspecialchars($review['reviewText']) . "</p>";
  echo "<p><em>Timestamp:</em> " . date("Y-m-d H:i:s", $review['timestamp']) . "</p>";
}


// Example Usage:
$productId = "123";
$userName = "Alice";
$reviewText = "This product is amazing!  I highly recommend it.";
$rating = 5;

$reviewResult = saveReview($productId, $userName, $reviewText, $rating);

if (isset($reviewResult['error'])) {
  echo "<p style='color:red;'>Error: " . $reviewResult['error'] . "</p>";
} else {
  displayReview($reviewResult);
}


?>
