

<?php

/**
 * Reviews Class - Handles user reviews.
 */
class Reviews {

  private $reviews = []; // Stores all reviews
  private $dbConnection = null; // Connection to the database (for persistence)

  /**
   * Constructor
   *
   * Establishes the database connection (if available).
   */
  public function __construct($dbConfig = null) {
    // Attempt to connect to the database (e.g., MySQL, PostgreSQL)
    // You'll need to adapt this part to your specific database setup.
    if ($dbConfig) {
      $this->dbConnection = new PDO(
        "mysql:host=" . $dbConfig['host'] . ";dbname=" . $dbConfig['dbname'] . ";charset=utf8",
        $dbConfig['user'],
        $dbConfig['password']
      );
    }
  }


  /**
   * Add a new review
   *
   * @param int $productId The ID of the product the review is for.
   * @param string $userName The name of the user who wrote the review.
   * @param string $reviewText The text of the review.
   * @param int $rating The rating (e.g., 1-5).
   *
   * @return bool True on success, false on failure.
   */
  public function addReview(int $productId, string $userName, string $reviewText, int $rating) {
    // Basic validation
    if (!$productId || !$userName || !$reviewText || $rating < 1 || $rating > 5) {
      return false;
    }

    $review = [
      'productId' => $productId,
      'userName' => $userName,
      'reviewText' => $reviewText,
      'rating' => $rating,
      'createdAt' => date('Y-m-d H:i:s') // Timestamp for creation
    ];

    $this->reviews[] = $review;

    // Optionally save to database here
    if ($this->dbConnection) {
      $this->saveReviewToDatabase($review);
    }
    return true;
  }

  /**
   * Get all reviews for a product
   *
   * @param int $productId The ID of the product.
   *
   * @return array An array of reviews, or an empty array if none found.
   */
  public function getReviewsByProduct(int $productId) {
    $productReviews = [];
    foreach ($this->reviews as $review) {
      if ($review['productId'] == $productId) {
        $productReviews[] = $review;
      }
    }
    return $productReviews;
  }

  /**
   * Save review to database (using PDO)
   */
  private function saveReviewToDatabase(array $review) {
    $sql = "INSERT INTO reviews (productId, userName, reviewText, rating, createdAt)
            VALUES (:productId, :userName, :reviewText, :rating, :createdAt)";

    $stmt = $this->dbConnection->prepare($sql);
    $stmt->bindParam(':productId', $review['productId']);
    $stmt->bindParam(':userName', $review['userName']);
    $stmt->bindParam(':reviewText', $review['reviewText']);
    $stmt->bindParam(':rating', $review['rating']);
    $stmt->bindParam(':createdAt', $review['createdAt']);

    try {
      $stmt->execute();
    } catch (PDOException $e) {
      // Handle database errors appropriately (logging, display error message)
      error_log("Database error saving review: " . $e->getMessage()); //Log for debugging
      // Optionally display an error message to the user (if appropriate)
    }
  }
}


// Example Usage:
// Assuming you have a database setup and a table named 'reviews'
// with columns: productId (int), userName (string), reviewText (text), rating (int), createdAt (datetime)

// Initialize Reviews object (replace with your database config)
$reviews = new Reviews([
  'host' => 'localhost',
  'dbname' => 'your_database_name',
  'user' => 'your_database_user',
  'password' => 'your_database_password'
]);

// Add a review
if ($reviews->addReview(123, "John Doe", "Great product!", 5)) {
  echo "Review added successfully!";
} else {
  echo "Failed to add review.";
}

// Get reviews for product 123
$reviewsForProduct123 = $reviews->getReviewsByProduct(123);

// Display the reviews
echo "<pre>";
print_r($reviewsForProduct123);
echo "</pre>";


?>
