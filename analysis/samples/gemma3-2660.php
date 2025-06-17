

<?php

/**
 * User Review Class
 *
 * This class allows you to store and manage user reviews.
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
   * Initializes a new UserReview object.
   *
   * @param int $reviewId (Optional) The unique identifier for the review. If not provided, it's automatically generated.
   * @param int $userId The ID of the user who wrote the review.
   * @param int $productId The ID of the product being reviewed.
   * @param int $rating The rating (e.g., 1-5 stars).
   * @param string $comment The user's comment about the product.
   */
  public function __construct($reviewId = null, $userId, $productId, $rating, $comment) {
    $this->reviewId = $reviewId;
    $this->userId = $userId;
    $this->productId = $productId;
    $this->rating = $rating;
    $this->comment = $comment;
    $this->date = date('Y-m-d H:i:s'); // Timestamp for when the review was created
  }

  /**
   * Getters
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
   * Setters (optional - for updating reviews)
   */
  public function setComment($comment) {
    $this->comment = $comment;
  }

  // Add more setters as needed (e.g., for updating the rating)
}


/**
 * User Review Review Function (example)
 */
function displayReview($review) {
  echo "<p><strong>User:</strong> " . $review->getUserId() . "</p>";
  echo "<p><strong>Product:</strong> " . $review->getProductId() . "</p>";
  echo "<p><strong>Rating:</strong> " . $review->getRating() . " stars</p>";
  echo "<p><strong>Comment:</strong> " . $review->getComment() . "</p>";
  echo "<p><strong>Date:</strong> " . $review->getDate() . "</p>";
}


// Example Usage:
$review1 = new UserReview(null, 123, 456, 5, "Great product! I love it.");
$review2 = new UserReview(null, 789, 456, 4, "Good value for the money.");

displayReview($review1);
displayReview($review2);


//  Example of how to add reviews to an array for database storage.
$reviews = [];

$review3 = new UserReview(null, 101, 102, 3, "It was okay.");
$reviews[] = $review3;

// Display all reviews
echo "<br><br><h2>All Reviews:</h2>";
foreach($reviews as $review) {
  displayReview($review);
  echo "<br>";
}

?>
