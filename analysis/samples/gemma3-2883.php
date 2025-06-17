

<?php

/**
 * User Review Function
 *
 * This function allows you to store and display user reviews for a product.
 * It handles input validation, sanitization, and basic storage.
 *
 * @param string $productId The unique identifier for the product.
 * @param string $userName The name of the user submitting the review.
 * @param string $reviewText The user's review text.
 * @param string $dbConnection PDO connection object to the database.
 *
 * @return array An array containing the result of the review submission:
 *              - 'success':  True if the review was successfully saved, false otherwise.
 *              - 'message':  A message indicating the status of the review.
 *              - 'reviewId': The ID of the newly created review if successful, or null otherwise.
 */
function submitReview(string $productId, string $userName, string $reviewText, PDO $dbConnection) {

  // Input Validation and Sanitization - IMPORTANT!  Prevent SQL Injection!
  $productId = filter_var($productId, FILTER_SANITIZE_STRING);  // Sanitize product ID
  $userName = filter_var($userName, FILTER_SANITIZE_STRING); // Sanitize user name
  $reviewText = filter_var($reviewText, FILTER_SANITIZE_STRING); // Sanitize review text

  //  Check if the product exists (basic check - improve for real-world use)
  $stmt = $dbConnection->prepare("SELECT id FROM products WHERE id = :product_id");
  $stmt->bindParam(':product_id', $productId);
  $stmt->execute();
  $product = $stmt->fetch(PDO::FETCH_OBJ);

  if (!$product) {
    return [
      'success' => false,
      'message' => "Product with ID '$productId' not found.",
      'reviewId' => null
    ];
  }

  // Sanitize review text to prevent XSS.  Consider using HTML escaping.
  $reviewText = htmlspecialchars($reviewText, ENT_QUOTES, 'UTF-8');


  // Prepare and execute the insert statement
  $stmt = $dbConnection->prepare("INSERT INTO reviews (product_id, user_name, review_text) VALUES (:product_id, :user_name, :review_text)");
  $stmt->bindParam(':product_id', $productId);
  $stmt->bindParam(':user_name', $userName);
  $stmt->bindParam(':review_text', $reviewText);
  $stmt->execute();

  $reviewId = $dbConnection->lastInsertId();

  return [
    'success' => true,
    'message' => "Review submitted successfully!",
    'reviewId' => $reviewId
  ];
}

// Example Usage (assuming you have a database connection object $dbConnection)
//
// $result = submitReview('123', 'John Doe', 'This is a great product!', $dbConnection);
//
// if ($result['success']) {
//   echo "Review submitted successfully! Review ID: " . $result['reviewId'];
// } else {
//   echo "Error submitting review: " . $result['message'];
// }


/**
 * Function to display reviews for a product.
 *
 * @param string $productId The unique identifier for the product.
 * @param PDO $dbConnection PDO connection object to the database.
 */
function displayReviews(string $productId, PDO $dbConnection) {
  $query = "SELECT id, user_name, review_text, created_at FROM reviews WHERE product_id = :product_id ORDER BY created_at DESC";
  $stmt = $dbConnection->prepare($query);
  $stmt->bindParam(':product_id', $productId);
  $stmt->execute();

  $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

  if (empty($reviews)) {
    echo "<p>No reviews yet.</p>";
  } else {
    echo "<h2>Reviews for Product ID: " . $productId . "</h2>";
    echo "<ul>";
    foreach ($reviews as $review) {
      echo "<li>";
      echo "<p><strong>User:</strong> " . htmlspecialchars($review['user_name']) . "</p>";
      echo "<p>" . htmlspecialchars($review['review_text']) . "</p>";
      echo "<p>Date: " . date("Y-m-d H:i:s", strtotime($review['created_at'])) . "</p>";
      echo "</li>";
    }
    echo "</ul>";
  }
}



?>
