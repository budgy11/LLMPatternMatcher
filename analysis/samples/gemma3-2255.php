

<?php

/**
 *  User Review Function
 *
 *  This function allows you to create, display, and manage user reviews for a product or item.
 *
 *  Parameters:
 *  - $product_id (int): The ID of the product the review is for.
 *  - $user_id (int): The ID of the user submitting the review.
 *  - $rating (int): The rating given by the user (e.g., 1-5).
 *  - $comment (string): The review text.
 *
 *  Returns:
 *  - int: The ID of the newly created review, or -1 if the review could not be created.
 */

/**
 * Creates a new review.
 *
 * @param int $product_id The ID of the product.
 * @param int $user_id The ID of the user.
 * @param int $rating The rating (1-5).
 * @param string $comment The review text.
 * @return int|false The review ID on success, or false on failure.
 */
function createReview(int $product_id, int $user_id, int $rating, string $comment): int|false
{
    // **IMPORTANT: Database connection logic is simplified here.**
    // Replace this with your actual database connection code.
    // This example assumes you have a database connection named `$db`.

    // 1. Validate Input (Important for security and data integrity)
    if (!$db) {
        error_log("Database connection not established in createReview function.");
        return -1;
    }

    if ($rating < 1 || $rating > 5) {
        error_log("Invalid rating provided: " . $rating);
        return -1;
    }

    if (empty($comment)) {
        error_log("Review comment cannot be empty.");
        return -1;
    }


    // 2. Prepare the SQL Query
    $sql = "INSERT INTO reviews (product_id, user_id, rating, comment) 
            VALUES (:product_id, :user_id, :rating, :comment)";

    // 3. Prepare the Statement
    $stmt = $db->prepare($sql);

    if (!$stmt) {
        error_log("Failed to prepare SQL statement in createReview function.");
        return -1;
    }


    // 4. Bind Parameters
    $stmt->bindParam(':product_id', $product_id);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':rating', $rating);
    $stmt->bindParam(':comment', $comment);

    // 5. Execute the Query
    if (!$stmt->execute()) {
        error_log("Error executing SQL query in createReview function: " . $stmt->error);
        return -1;
    }

    // 6. Get the Last Inserted ID
    $review_id = $db->lastInsertId();

    // 7.  Return the Review ID
    return $review_id;
}

/**
 * Displays all reviews for a product.
 *
 * @param int $product_id The ID of the product.
 * @return array An array of review objects, or an empty array if no reviews exist.
 */
function getReviewsForProduct(int $product_id): array {
  // Database connection logic here (simplified for example)
  if (!$db) {
    error_log("Database connection not established in getReviewsForProduct.");
    return [];
  }

  $sql = "SELECT * FROM reviews WHERE product_id = :product_id";
  $stmt = $db->prepare($sql);
  $stmt->bindParam(':product_id', $product_id);
  $stmt->execute();

  $reviews = [];
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $reviews[] = $row;
  }
  return $reviews;
}


// Example Usage (Illustrative - Replace with your database setup)
/*
// Setup Example (for demonstration purposes)
$db = new PDO("mysql:host=localhost;dbname=your_database_name", "username", "password");

// Create a review
$review_id = createReview(123, 45, 4, "Great product! I love it.");

if ($review_id) {
    echo "Review created successfully with ID: " . $review_id . "
";
} else {
    echo "Failed to create review.
";
}

// Get reviews for product ID 123
$reviews = getReviewsForProduct(123);

if (!empty($reviews)) {
    echo "Reviews for product 123:
";
    foreach ($reviews as $review) {
        echo "  Product ID: " . $review['product_id'] . ", User ID: " . $review['user_id'] . ", Rating: " . $review['rating'] . ", Comment: " . $review['comment'] . "
";
    }
} else {
    echo "No reviews found for product 123.
";
}
*/


?>
