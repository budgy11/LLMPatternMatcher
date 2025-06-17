

<?php

/**
 * User Review Function
 *
 * This function allows you to store and display user reviews for a product or item.
 *
 * @param string $product_id The ID of the product being reviewed.
 * @param string $user_name The name of the user submitting the review.
 * @param string $review_text The text of the review.
 * @param int $rating (Optional) The rating given by the user (1-5). Defaults to 0.
 * @return array|false Returns an array containing the review ID, or false if the review could not be saved.
 */
function saveUserReview(string $product_id, string $user_name, string $review_text, int $rating = 0) {
  // Validate inputs (you can expand this validation)
  if (empty($product_id) || empty($user_name) || empty($review_text)) {
    return false; // Return false for invalid input
  }

  if ($rating < 1 || $rating > 5) {
    $rating = 0; //  Or handle this differently, e.g., throw an exception.
  }

  //  Database connection (replace with your database setup)
  $db = new PDO('mysql:host=localhost;dbname=your_database_name', 'your_username', 'your_password');

  try {
    // Prepare the SQL statement
    $stmt = $db->prepare(
      "INSERT INTO reviews (product_id, user_name, review_text, rating) 
       VALUES (:product_id, :user_name, :review_text, :rating)"
    );

    // Bind the parameters
    $stmt->bindParam(':product_id', $product_id);
    $stmt->bindParam(':user_name', $user_name);
    $stmt->bindParam(':review_text', $review_text);
    $stmt->bindParam(':rating', $rating);

    // Execute the statement
    $stmt->execute();

    // Get the last inserted ID
    $review_id = $db->lastInsertId();

    return ['id' => $review_id]; // Return the review ID
  } catch (PDOException $e) {
    // Handle database errors
    error_log("Database error: " . $e->getMessage());  // Log for debugging
    return false; // Return false on error
  }
}


/**
 * Display User Reviews
 *
 * This function retrieves and displays user reviews for a product.
 *
 * @param string $product_id The ID of the product.
 * @return array|false Returns an array of reviews or false if no reviews are found.
 */
function displayUserReviews(string $product_id) {
  // Database connection
  $db = new PDO('mysql:host=localhost;dbname=your_database_name', 'your_username', 'your_password');

  try {
    // Prepare the SQL statement
    $stmt = $db->prepare("SELECT id, user_name, review_text, rating FROM reviews WHERE product_id = :product_id");
    $stmt->bindParam(':product_id', $product_id);
    $stmt->execute();

    $reviews = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $reviews[] = [
        'id' => $row['id'],
        'user_name' => $row['user_name'],
        'review_text' => $row['review_text'],
        'rating' => $row['rating']
      ];
    }

    return $reviews;

  } catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    return false;
  }
}

// Example Usage:
// 1. Save a Review
$review_data = saveUserReview('product123', 'John Doe', 'This is a great product!', 5);

if ($review_data) {
  echo "Review saved successfully!  Review ID: " . $review_data['id'] . "
";
} else {
  echo "Failed to save review.
";
}

// 2. Display Reviews for a Product
$reviews = displayUserReviews('product123');

if ($reviews) {
  echo "Reviews for product123:
";
  foreach ($reviews as $review) {
    echo "  User: " . $review['user_name'] . ", Rating: " . $review['rating'] . ", Text: " . $review['review_text'] . "
";
  }
} else {
  echo "No reviews found for product123.
";
}


?>
