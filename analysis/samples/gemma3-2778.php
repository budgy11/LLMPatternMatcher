

<?php

/**
 * User Review Function
 *
 * This function allows you to store and display user reviews for a product or service.
 *
 * @param string $productId The unique identifier for the product/service.
 * @param string $userName The user's name or username.
 * @param string $reviewText The user's review text.
 * @param int $rating (Optional) The rating given by the user (1-5). Defaults to 0.
 * @param string $userId (Optional) The user's ID, for database storage. If not provided, a default one will be generated.
 * @return bool True on success, false on failure.
 */
function storeUserReview(string $productId, string $userName, string $reviewText, int $rating = 0, string $userId = null): bool
{
  // Validate inputs (add more validation as needed)
  if (empty($productId) || empty($userName) || empty($reviewText)) {
    error_log("Error: Product ID, User Name, and Review Text cannot be empty."); // Log for debugging
    return false;
  }

  if ($rating < 1 || $rating > 5) {
    error_log("Error: Rating must be between 1 and 5.");
    return false;
  }

  // Database connection (replace with your actual database connection)
  $db = new PDO('mysql:host=localhost;dbname=your_database_name', 'your_username', 'your_password'); // Example, adapt to your setup

  try {
    // Prepare the SQL statement
    $stmt = $db->prepare("INSERT INTO reviews (productId, userName, reviewText, rating, userId) 
                         VALUES (:productId, :userName, :reviewText, :rating, :userId)");

    // Bind parameters
    $stmt->bindParam(':productId', $productId);
    $stmt->bindParam(':userName', $userName);
    $stmt->bindParam(':reviewText', $reviewText);
    $stmt->bindParam(':rating', $rating);
    $stmt->bindParam(':userId', $userId ?? null); // Use null coalescing operator to handle optional userId

    // Execute the statement
    $result = $stmt->execute();

    // Check for errors
    if ($result) {
      //  Ideally, you'd retrieve the newly created ID here to return to the user
      //  For this example, we just return true
      return true;
    } else {
      error_log("Error inserting review: " . print_r($stmt->errorInfo(), true));  // Log more details for debugging
      return false;
    }
  } catch (PDOException $e) {
    error_log("PDO Exception: " . $e->getMessage()); // Log the exception
    return false;
  }
}


/**
 * Function to retrieve all reviews for a product.
 *
 * @param string $productId The product ID.
 * @return array An array of review objects, or an empty array if no reviews exist.
 */
function getReviewsForProduct(string $productId): array {
  // Database connection
  $db = new PDO('mysql:host=localhost;dbname=your_database_name', 'your_username', 'your_password'); // Example, adapt to your setup

  try {
    $stmt = $db->prepare("SELECT id, productId, userName, reviewText, rating, userId, created_at FROM reviews WHERE productId = :productId");
    $stmt->bindParam(':productId', $productId);
    $stmt->execute();

    $reviews = $stmt->fetchAll(PDO::FETCH_OBJ); // Fetch as objects for easier handling

    return $reviews;

  } catch (PDOException $e) {
    error_log("PDO Exception: " . $e->getMessage());
    return []; // Return an empty array in case of error
  }
}



// Example usage (for demonstration purposes - NOT for production)
//  You'll need to create a 'reviews' table in your database first.
//  Example SQL to create the table:
//  CREATE TABLE reviews (
//    id int AUTO_INCREMENT PRIMARY KEY,
//    productId varchar(255) NOT NULL,
//    userName varchar(255) NOT NULL,
//    reviewText TEXT NOT NULL,
//    rating int,
//    userId varchar(255),
//    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
//  );



// Store a review
$reviewSuccess = storeUserReview('product123', 'John Doe', 'Great product!  I highly recommend it.', 5);
if ($reviewSuccess) {
  echo "Review stored successfully!
";
} else {
  echo "Error storing review.
";
}


// Get reviews for 'product123'
$reviews = getReviewsForProduct('product123');

if (!empty($reviews)) {
  echo "Reviews for product123:
";
  foreach ($reviews as $review) {
    echo "  ID: " . $review->id . "
";
    echo "  Product ID: " . $review->productId . "
";
    echo "  User Name: " . $review->userName . "
";
    echo "  Review Text: " . $review->reviewText . "
";
    echo "  Rating: " . $review->rating . "
";
    echo "  User ID: " . $review->userId . "
";
    echo "  Created at: " . $review->created_at . "
";
    echo "--- 
";
  }
} else {
  echo "No reviews found for product123.
";
}


?>
