

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
   * Constructor
   *
   * @param int $reviewId (optional) - Unique identifier for the review.  If not provided, will be generated.
   * @param int $userId  - ID of the user submitting the review.
   * @param int $productId - ID of the product being reviewed.
   * @param int $rating   - Rating from 1 to 5.
   * @param string $comment - User's comment on the product.
   */
  public function __construct(
    int $reviewId = null,
    int $userId,
    int $productId,
    int $rating,
    string $comment
  ) {
    $this->reviewId = $reviewId;
    $this->userId = $userId;
    $this->productId = $productId;
    $this->rating = $rating;
    $this->comment = $comment;
    $this->date = date("Y-m-d H:i:s"); // Timestamp of the review
  }

  /**
   * Getters
   */
  public function getReviewId(): int
  {
    return $this->reviewId;
  }

  public function getUserId(): int
  {
    return $this->userId;
  }

  public function getProductId(): int
  {
    return $this->productId;
  }

  public function getRating(): int
  {
    return $this->rating;
  }

  public function getComment(): string
  {
    return $this->comment;
  }

  public function getDate(): string
  {
    return $this->date;
  }


  /**
   *  Method to represent the review as an array for easy output.
   *  Useful for JSON encoding or displaying on a webpage.
   *
   * @return array
   */
  public function toArray(): array
  {
    return [
      'review_id' => $this->getReviewId(),
      'user_id' => $this->getUserId(),
      'product_id' => $this->getProductId(),
      'rating' => $this->getRating(),
      'comment' => $this->getComment(),
      'date' => $this->getDate(),
    ];
  }
}


/**
 * User Review Review Function (Example Usage)
 */
function reviewProduct($userId, $productId, $rating, $comment) {
  $review = new UserReview($userId, $productId, $rating, $comment);
  // You can now save this review to a database or perform other actions.
  // For this example, we'll just print the review information.

  echo "Review Details:
";
  echo "Review ID: " . $review->getReviewId() . "
";
  echo "User ID: " . $review->getUserId() . "
";
  echo "Product ID: " . $review->getProductId() . "
";
  echo "Rating: " . $review->getRating() . "
";
  echo "Comment: " . $review->getComment() . "
";
  echo "Date: " . $review->getDate() . "
";
  echo "
";
}



// Example Usage:
reviewProduct(123, 456, 5, "Great product!  I love it!"); // Example 1
reviewProduct(789, 101, 4, "Pretty good, but could be better."); // Example 2
?>
