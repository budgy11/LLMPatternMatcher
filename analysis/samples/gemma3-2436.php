

<?php

/**
 * User Review Function
 *
 * This function allows you to store and display user reviews for a product or item.
 *
 * @param string $productId The unique identifier for the product/item.
 * @param string $username The username of the reviewer.
 * @param string $rating  The rating given by the user (e.g., 1-5 stars).
 * @param string $comment The user's review comment.
 * @param array $dbConnection  An established database connection object.
 *
 * @return array  An array containing success status and potentially the ID of the new review.
 */
function createReview(string $productId, string $username, string $rating, string $comment, array $dbConnection) {
  // Input validation (basic) - enhance this for production
  if (empty($productId)) {
    return ['success' => false, 'message' => 'Product ID cannot be empty.'];
  }
  if (empty($username)) {
    return ['success' => false, 'message' => 'Username cannot be empty.'];
  }
  if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
    return ['success' => false, 'message' => 'Rating must be a number between 1 and 5.'];
  }
  if (empty($comment)) {
    return ['success' => false, 'message' => 'Comment cannot be empty.'];
  }


  // Prepare the SQL query - IMPORTANT: Use prepared statements to prevent SQL injection!
  $sql = "INSERT INTO reviews (product_id, username, rating, comment) 
           VALUES (?, ?, ?, ?)";

  // Prepare the statement
  $stmt = $dbConnection->prepare($sql);

  if ($stmt === false) {
    return ['success' => false, 'message' => 'Database error preparing statement.'];
  }

  // Bind the parameters
  $stmt->bind_param("ssis", $productId, $username, $rating, $comment);  // s = string, i = integer, s = string

  // Execute the query
  if ($stmt->execute() === false) {
    return ['success' => false, 'message' => 'Database error executing query: ' . $stmt->error];
  }

  // Get the last inserted ID (if supported by your database)
  $reviewId = $dbConnection->insert_id; //  This might not work on all databases
  
  // Close the statement
  $stmt->close();

  return ['success' => true, 'review_id' => $reviewId];
}



/**
 * Function to retrieve all reviews for a product.
 *
 * @param string $productId The product ID.
 * @param array $dbConnection An established database connection.
 *
 * @return array An array of review objects, or an empty array if no reviews are found.
 */
function getReviewsForProduct(string $productId, array $dbConnection) {
    $sql = "SELECT id, product_id, username, rating, comment, created_at
            FROM reviews
            WHERE product_id = ?
            ORDER BY created_at DESC"; //  Order by date for the most recent reviews

    $stmt = $dbConnection->prepare($sql);

    if ($stmt === false) {
        return ['reviews' => []]; // Return empty array in case of error.
    }

    $stmt->bind_param("s", $productId);

    $stmt->execute();

    $result = $stmt->get_result();

    $reviews = [];
    while ($row = $result->fetch_assoc()) {
        $reviews[] = $row;
    }

    $stmt->close();

    return $reviews;
}

// Example Usage (Illustrative - Adapt to your setup)
// Assuming you have a database connection object named $conn

// Create a review
//$review_data = createReview("product_123", "JohnDoe", 4, "Great product!", $conn);
//print_r($review_data);

// Retrieve reviews for a product
//$reviews = getReviewsForProduct("product_123", $conn);
//print_r($reviews);
?>
