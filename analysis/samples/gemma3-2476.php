

<?php

/**
 * User Review Function
 *
 * This function allows you to store and display user reviews for a product or item.
 *
 * @param string $product_id The unique identifier for the product being reviewed.
 * @param string $user_id  The unique identifier for the user submitting the review.
 * @param string $review_text The text of the user's review.
 * @param int    $rating    The rating given by the user (e.g., 1-5 stars).
 * @param int    $timestamp Unix timestamp for the review creation.
 *
 * @return bool  True on successful submission, false on error.
 */
function submit_review(string $product_id, string $user_id, string $review_text, int $rating, int $timestamp = time()) {
  // Validate inputs - VERY IMPORTANT!
  if (empty($product_id) || empty($user_id) || empty($review_text) || $rating < 1 || $rating > 5) {
    error_log("Invalid review data submitted: product_id=$product_id, user_id=$user_id, review_text=$review_text, rating=$rating");
    return false;
  }

  // Sanitize inputs (Crucial for security!)
  $product_id = filter_var($product_id, FILTER_SANITIZE_STRING);
  $review_text = filter_var($review_text, FILTER_SANITIZE_STRING);

  // Add data to a database (replace this with your actual database code)
  // This is a simplified example - use prepared statements for security!
  $db_host = "localhost";
  $db_name = "your_database_name";
  $db_user = "your_database_user";
  $db_password = "your_database_password";

  try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare the SQL statement using prepared statements - HIGHLY RECOMMENDED!
    $stmt = $pdo->prepare("INSERT INTO reviews (product_id, user_id, review_text, rating, timestamp) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$product_id, $user_id, $review_text, $rating, $timestamp]);

    return true;

  } catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    return false;
  }
}



/**
 * Retrieve User Reviews
 *
 * Retrieves reviews for a specific product.
 *
 * @param string $product_id The product ID to retrieve reviews for.
 * @return array An array of review objects, or an empty array if no reviews are found.
 */
function get_reviews(string $product_id) {
  // Implement your database query here to fetch reviews for the product.
  // Example (replace with your actual query):
  $db_host = "localhost";
  $db_name = "your_database_name";
  $db_user = "your_database_user";
  $db_password = "your_database_password";

  try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("SELECT * FROM reviews WHERE product_id = ? ORDER BY timestamp DESC");  //Order by latest reviews
    $stmt->execute([$product_id]);
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC); //Fetch as an associative array

    return $reviews;

  } catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    return []; // Return empty array on error
  }
}


// Example Usage (Demonstration)
//  $product_id = "product123";
//  $user_id = "user456";
//  $review_text = "This is a great product!";
//  $rating = 5;
//  $timestamp = time();
//
// if (submit_review($product_id, $user_id, $review_text, $rating, $timestamp)) {
//   echo "Review submitted successfully!
";
// } else {
//   echo "Error submitting review.
";
// }

// Example to get reviews:
//$reviews = get_reviews($product_id);
//if (!empty($reviews)) {
//  echo "Reviews for product " . $product_id . ":
";
//  foreach ($reviews as $review) {
//    echo "  - User: " . $review['user_id'] . ", Rating: " . $review['rating'] . ", Text: " . $review['review_text'] . "
";
//  }
//} else {
//  echo "No reviews found for product " . $product_id . "
";
//}

?>
