

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
   * @param int $reviewId (optional, defaults to 0)
   * @param int $userId
   * @param int $productId
   * @param int $rating (1-5)
   * @param string $comment
   */
  public function __construct($reviewId = 0, $userId, $productId, $rating, $comment) {
    $this->reviewId = $reviewId;
    $this->userId = $userId;
    $this->productId = $productId;
    $this->rating = $rating;
    $this->comment = $comment;
    $this->date = date('Y-m-d H:i:s'); // Set the review date/time
  }

  /**
   * Getters and Setters
   */
  public function getReviewId() {
    return $this->reviewId;
  }

  public function setReviewId($reviewId) {
    $this->reviewId = $reviewId;
  }

  public function getUserId() {
    return $this->userId;
  }

  public function setUserId($userId) {
    $this->userId = $userId;
  }

  public function getProductId() {
    return $this->productId;
  }

  public function setProductId($productId) {
    $this->productId = $productId;
  }

  public function getRating() {
    return $this->rating;
  }

  public function setRating($rating) {
    if ($rating >= 1 && $rating <= 5) {
      $this->rating = $rating;
    } else {
      // Handle invalid rating (e.g., log an error, throw an exception)
      error_log("Invalid rating provided: " . $rating); // Log the error
      // Or:  throw new Exception("Invalid rating. Rating must be between 1 and 5.");
    }
  }

  public function getComment() {
    return $this->comment;
  }

  public function setComment($comment) {
    $this->comment = $comment;
  }

  public function getDate() {
    return $this->date;
  }

  /**
   *  Method to display the review data
   *  Useful for printing or returning from a function
   *
   * @return array An associative array containing the review data
   */
  public function toArray() {
    return [
      'review_id' => $this->getReviewId(),
      'user_id' => $this->getUserId(),
      'product_id' => $this->getProductId(),
      'rating' => $this->getRating(),
      'comment' => $this->getComment(),
      'date' => $this->getDate()
    ];
  }
}


/**
 * User Review Review Function
 */
function reviewProduct($userId, $productId, $rating, $comment) {
  // Validation (basic - more robust validation is recommended for production)
  if (!is_numeric($userId) || !is_numeric($productId) || !is_numeric($rating)) {
    return "Error: Invalid input.  User ID, Product ID, and Rating must be numeric.";
  }

  if ($rating < 1 || $rating > 5) {
    return "Error: Rating must be between 1 and 5.";
  }

  // Create a new review object
  $review = new UserReview($userId, $productId, $rating, $comment);

  // You would typically save this review to a database here.
  // For example:
  // $review->saveToDatabase();

  // Return the review data in a user-friendly format
  return $review->toArray();
}


// --- Example Usage ---
// Review example 1
$reviewData1 = reviewProduct(123, 456, 4, "Great product!  I love it.");
print_r($reviewData1);
echo "
";

// Review example 2 - demonstrating error handling
$reviewData2 = reviewProduct("abc", 789, 3, "Okay, but could be better.");
echo $reviewData2;
echo "
";

// Review example 3 - Demonstrating invalid rating handling
$reviewData3 = reviewProduct(456, 789, 6, "Fantastic!");
print_r($reviewData3);
echo "
";


?>
