

<?php

/**
 * Class UserReview
 *
 * Handles user review creation, retrieval, and basic validation.
 */
class UserReview {

  private $db_connection;

  public function __construct($db_connection) {
    $this->db_connection = $db_connection;
  }

  /**
   * Creates a new user review.
   *
   * @param int $userId The ID of the user submitting the review.
   * @param int $productId The ID of the product being reviewed.
   * @param string $title The title of the review.
   * @param string $comment The review comment.
   * @return int|false The ID of the newly created review, or false on failure.
   */
  public function createReview(int $userId, int $productId, string $title, string $comment) {
    // Validation (Simple - can be expanded)
    if (!$this->isValidReview($userId, $productId, $title, $comment)) {
      return false;
    }

    // Prepare the SQL statement
    $sql = "INSERT INTO reviews (user_id, product_id, title, comment)
            VALUES (:user_id, :product_id, :title, :comment)";

    // Prepare the statement
    $stmt = $this->db_connection->prepare($sql);

    // Bind the parameters
    $stmt->bindParam(':user_id', $userId);
    $stmt->bindParam(':product_id', $productId);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':comment', $comment);

    // Execute the statement
    return $stmt->execute();
  }

  /**
   * Retrieves a single review by its ID.
   *
   * @param int $reviewId The ID of the review to retrieve.
   * @return array|false An array containing the review data, or false if not found.
   */
  public function getReviewById(int $reviewId) {
    // Prepare the SQL statement
    $sql = "SELECT * FROM reviews WHERE id = :review_id";

    // Prepare the statement
    $stmt = $this->db_connection->prepare($sql);

    // Bind the parameter
    $stmt->bindParam(':review_id', $reviewId);

    // Execute the statement
    $stmt->execute();

    // Get the result
    $review = $stmt->fetch(PDO::FETCH_ASSOC);

    // Return the review data
    return $review;
  }

  /**
   * Retrieves all reviews for a given product.
   *
   * @param int $productId The ID of the product.
   * @return array An array of review objects.
   */
  public function getReviewsByProduct(int $productId) {
    $sql = "SELECT * FROM reviews WHERE product_id = :product_id";
    $stmt = $this->db_connection->prepare($sql);
    $stmt->bindParam(':product_id', $productId);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
   * Validates the review data.  Add more complex validation as needed.
   *
   * @param int $userId
   * @param int $productId
   * @param string $title
   * @param string $comment
   * @return bool True if the data is valid, false otherwise.
   */
  private function isValidReview(int $userId, int $productId, string $title, string $comment) {
    // Basic validation - enhance as needed
    if ($userId <= 0) {
      return false;
    }
    if ($productId <= 0) {
      return false;
    }
    if (empty($title)) {
      return false;
    }
    if (empty($comment)) {
      return false;
    }
    return true;
  }
}


// Example Usage (Assuming you have a database connection established)
// Replace with your actual database connection details
$db_connection = new PDO("mysql:host=localhost;dbname=your_database_name", "your_username", "your_password");

$reviewManager = new UserReview($db_connection);

// Create a review
$userId = 1;
$productId = 10;
$reviewTitle = "Great Product!";
$reviewComment = "I really enjoyed this product. Highly recommended!";

$reviewId = $reviewManager->createReview($userId, $productId, $reviewTitle, $reviewComment);

if ($reviewId) {
  echo "Review created successfully with ID: " . $reviewId . "<br>";
} else {
  echo "Failed to create review.<br>";
}

// Get review by ID
$review = $reviewManager->getReviewById($reviewId);

if ($review) {
  echo "Review details: ";
  foreach ($review as $key => $value) {
    echo $key . ": " . $value . ", ";
  }
  echo "<br>";
} else {
  echo "Review not found.<br>";
}

// Get reviews for a product
$productReviews = $reviewManager->getReviewsByProduct($productId);

echo "Reviews for Product " . $productId . ":<br>";
foreach ($productReviews as $review) {
  echo "- " . $review['title'] . " - " . $review['comment'] . "<br>";
}
?>
