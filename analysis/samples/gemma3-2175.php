

<?php

/**
 * User Review Class
 *
 * This class allows users to submit and view reviews for a product or service.
 */
class UserReview {

  private $reviewId;
  private $productId;
  private $userId;
  private $rating;
  private $comment;
  private $date;

  /**
   * Constructor
   * 
   * Initializes a new UserReview object.
   *
   * @param int $productId The ID of the product or service being reviewed.
   * @param int $userId The ID of the user submitting the review.
   * @param int $rating  The rating given (e.g., 1-5).
   * @param string $comment The user's review comment.
   */
  public function __construct($productId, $userId, $rating, $comment) {
    $this->productId = $productId;
    $this->userId = $userId;
    $this->rating = $rating;
    $this->comment = $comment;
    $this->date = date("Y-m-d H:i:s"); // Set current date and time
  }

  /**
   * Getters for each review attribute
   *
   *  Provides access to the review's data.
   *
   * @return int|string|int|string|string
   */
  public function getReviewId() {
    return $this->reviewId;
  }

  public function getProductId() {
    return $this->productId;
  }

  public function getUserId() {
    return $this->userId;
  }

  public function getRating() {
    return $this->rating;
  }

  public function getComment() {
    return $this->comment;
  }

  public function getDate() {
    return $this->date;
  }


  /**
   *  String representation of the review
   *  Useful for debugging or displaying review data
   *
   * @return string
   */
  public function __toString() {
    return "Review ID: " . $this->getReviewId() . "
" .
           "Product ID: " . $this->getProductId() . "
" .
           "User ID: " . $this->getUserId() . "
" .
           "Rating: " . $this->getRating() . "
" .
           "Comment: " . $this->getComment() . "
" .
           "Date: " . $this->getDate();
  }
}

// Example Usage
$review1 = new UserReview(123, 456, 5, "Great product!  I highly recommend it.");
$review2 = new UserReview(789, 101, 4, "Good value for the price.");

echo $review1 . "

";
echo $review2 . "
";

//  ------------------------
//  Database Interaction Example (Conceptual)
//  ------------------------

// In a real application, you would:
// 1. Connect to your database.
// 2. Prepare an SQL query to insert the review data.
// 3. Execute the query.
// 4. Handle any database errors.

// Example:
/*
$db = new PDO('mysql:host=localhost;dbname=your_database', 'username', 'password');

try {
  $stmt = $db->prepare("INSERT INTO reviews (productId, userId, rating, comment) 
                        VALUES (?, ?, ?, ?)");
  $stmt->execute([$review1->getProductId(), $review1->getUserId(), $review1->getRating(), $review1->getComment()]);

  echo "Review saved to database!
";
} catch (PDOException $e) {
  echo "Error saving review: " . $e->getMessage() . "
";
}
*/
?>
