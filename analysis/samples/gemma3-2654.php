

<?php

/**
 * User Review Class
 */
class UserReview {

  private $review_id;
  private $user_id;
  private $product_id;
  private $rating;
  private $comment;
  private $date;

  /**
   * Constructor
   *
   * @param int $review_id (optional, auto-generated)
   * @param int $user_id
   * @param int $product_id
   * @param int $rating (1-5)
   * @param string $comment
   */
  public function __construct($review_id = null, $user_id, $product_id, $rating, $comment) {
    $this->review_id = $review_id;
    $this->user_id = $user_id;
    $this->product_id = $product_id;
    $this->rating = $rating;
    $this->comment = $comment;
    $this->date = date('Y-m-d H:i:s'); // Use current timestamp
  }

  /**
   * Getters
   */
  public function getReviewId() {
    return $this->review_id;
  }

  public function getUserId() {
    return $this->user_id;
  }

  public function getProductId() {
    return $this->product_id;
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
   * Setters (optional - add if you need to modify these properties)
   */
  public function setRating($rating) {
    if ($rating >= 1 && $rating <= 5) {
      $this->rating = $rating;
    } else {
      echo "Invalid rating. Rating must be between 1 and 5.";
    }
  }
}


/**
 * User Review Review Function
 */
function reviewUser(UserReview $review) {
  // You can perform validation here (e.g., check if user exists, product exists, rating is valid)
  echo "Review Submitted by User " . $review->getUserId() . " for Product " . $review->getProductId() . ": " . $review->getComment() . " (Rating: " . $review->getRating() . ")" . PHP_EOL;

  // Example: Save the review to a database (replace with your actual database code)
  // $db = new DatabaseConnection();
  // $db->insertReview($review->getReviewId(), $review->getUserId(), $review->getProductId(), $review->getRating(), $review->getComment());
}


// Example Usage:
$review1 = new UserReview(null, 123, 456, 5, "This product is amazing!");
reviewUser($review1);

$review2 = new UserReview(null, 456, 789, 4, "Good value for the money.");
reviewUser($review2);

$review3 = new UserReview(null, 789, 456, 3, "It could be better.");
reviewUser($review3);

// Example of setting a rating after creation
$review4 = new UserReview(null, 101, 112, 2, "Not impressed.");
$review4->setRating(1); // Set rating to 1
reviewUser($review4);


?>
