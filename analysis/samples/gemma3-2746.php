

<?php

/**
 * User Review Class
 */
class UserReview {
  private $reviewId;
  private $userId;
  private $productId;
  private $rating;
  private $comment;
  private $date;

  /**
   * Constructor for the UserReview class.
   *
   * @param int $reviewId (optional) - Unique identifier for the review.  Defaults to 0.
   * @param int $userId The ID of the user who wrote the review.
   * @param int $productId The ID of the product being reviewed.
   * @param int $rating The rating given by the user (1-5).
   * @param string $comment The user's comment about the product.
   * @param string $date The date the review was submitted (YYYY-MM-DD format).
   */
  public function __construct($reviewId = 0, $userId, $productId, $rating, $comment, $date) {
    $this->reviewId = $reviewId;
    $this->userId = $userId;
    $this->productId = $productId;
    $this->rating = $rating;
    $this->comment = $comment;
    $this->date = $date;
  }

  /**
   * Getters for each attribute.
   *
   * @return mixed
   */
  public function getReviewId() {
    return $this->reviewId;
  }

  public function getUserId() {
    return $this->userId;
  }

  public function getProductId() {
    return $this->productId;
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
   *  Validation function -  Basic validation.  Extend as needed.
   *
   * @return bool True if valid, false otherwise.
   */
  public function isValid() {
    // Basic validation: Rating should be between 1 and 5
    if ($this->rating < 1 || $this->rating > 5) {
      return false;
    }
    // Add more validation as needed (e.g., comment length, date format)
    return true;
  }
}


/**
 * User Review Review Function
 */
function reviewReview(UserReview $review) {
  // This is a simple review function.  You'll likely want to
  // integrate this with a database, display it on a page,
  // or perform other actions.

  if ($review->isValid()) {
    echo "Review ID: " . $review->getReviewId() . "<br>";
    echo "User ID: " . $review->getUserId() . "<br>";
    echo "Product ID: " . $review->getProductId() . "<br>";
    echo "Rating: " . $review->getRating() . "<br>";
    echo "Comment: " . $review->getComment() . "<br>";
    echo "Date: " . $review->getDate() . "<br>";
    echo "<br>";
  } else {
    echo "Invalid Review Data.  Please check the rating and comment.<br>";
  }
}



// Example Usage:
$review1 = new UserReview(1, 123, 456, 5, "Great product!", "2023-10-27");
reviewReview($review1);

$review2 = new UserReview(2, 456, 789, 2, "It's okay, but...", "2023-10-26");
reviewReview($review2);

$review3 = new UserReview(3, 789, 101, 6, "Excellent!", "2023-10-27"); // Invalid rating
reviewReview($review3);
?>
