

<?php

/**
 * User Review Function
 *
 * This function allows you to store, display, and manage user reviews.
 *
 * @param string $productId The ID of the product being reviewed.
 * @param string $userEmail The email address of the user submitting the review.
 * @param string $reviewText The text of the review.
 * @param int    $rating    The rating given by the user (e.g., 1-5).
 * @param array $dbConnection Database connection object.  (Important: Use PDO for security!)
 *
 * @return bool True on success, false on failure.
 */
function addReview(string $productId, string $userEmail, string $reviewText, int $rating, PDO $dbConnection) {
  // Input validation - CRITICAL for security and data integrity
  if (empty($productId)) {
    error_log("Invalid product ID provided.");
    return false;
  }
  if (empty($userEmail)) {
    error_log("Invalid user email provided.");
    return false;
  }
  if (empty($reviewText)) {
    error_log("Review text cannot be empty.");
    return false;
  }
  if ($rating < 1 || $rating > 5) {
    error_log("Invalid rating provided. Rating must be between 1 and 5.");
    return false;
  }

  // SQL Injection Prevention - IMPORTANT!  Use prepared statements.
  $sql = "INSERT INTO reviews (product_id, user_email, review_text, rating)
          VALUES (:product_id, :user_email, :review_text, :rating)";

  try {
    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':product_id', $productId);
    $stmt->bindParam(':user_email', $userEmail);
    $stmt->bindParam(':review_text', $reviewText);
    $stmt->bindParam(':rating', $rating);

    $result = $stmt->execute();

    if ($result) {
      return true;
    } else {
      error_log("Error executing review insert: " . print_r($stmt->errorInfo(), true)); //Log the error
      return false;
    }

  } catch (PDOException $e) {
    error_log("PDOException: " . $e->getMessage()); // Log the PDO exception
    return false;
  }
}

/**
 *  Example Retrieval Function (for demonstration - would likely be much more complex in a real application)
 *
 * @param int $productId The product ID to retrieve reviews for.
 * @param PDO $dbConnection Database connection object.
 *
 * @return array An array of review objects (or an empty array if none are found).
 */
function getReviewsForProduct(int $productId, PDO $dbConnection) {
  $sql = "SELECT * FROM reviews WHERE product_id = :product_id";

  try {
    $stmt = $dbConnection->prepare($sql);
    $stmt->bindParam(':product_id', $productId);
    $stmt->execute();

    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all rows as associative arrays

    return $reviews;

  } catch (PDOException $e) {
    error_log("PDOException: " . $e->getMessage());
    return [];
  }
}

// --- Example Usage (Illustrative - replace with your actual database connection) ---
// **IMPORTANT:  Replace the following with your actual database configuration!**
$dbHost = 'localhost';
$dbName = 'your_database_name';
$dbUser = 'your_database_user';
$dbPass = 'your_database_password';

try {
    $dbConnection = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8", $dbUser, $dbPass);
    $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Important for error handling
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}



// Add a review
$product_id = 123;
$user_email = 'test@example.com';
$review_text = 'Great product! I highly recommend it.';
$rating = 5;

if (addReview($product_id, $user_email, $review_text, $rating, $dbConnection)) {
    echo "Review added successfully!
";
} else {
    echo "Failed to add review.
";
}

// Retrieve reviews for product 123
$reviews = getReviewsForProduct($product_id, $dbConnection);

echo "Reviews for product " . $product_id . ":
";
if (empty($reviews)) {
    echo "No reviews found.
";
} else {
    foreach ($reviews as $review) {
        echo "  Product ID: " . $review['product_id'] . "
";
        echo "  User Email: " . $review['user_email'] . "
";
        echo "  Review Text: " . $review['review_text'] . "
";
        echo "  Rating: " . $review['rating'] . "
";
        echo "  --- Separator ---
";
    }
}

// Close the database connection (Good practice)
$dbConnection = null;
?>
