

<?php

/**
 * Reviews class to handle user reviews for a product or service.
 */
class Review {

  private $productId;
  private $userId;
  private $rating;
  private $comment;
  private $timestamp;


  /**
   * Constructor for the Review class.
   *
   * @param int $productId The ID of the product or service being reviewed.
   * @param int $userId The ID of the user submitting the review.
   * @param int $rating The rating given by the user (e.g., 1-5).
   * @param string $comment The user's review comment.
   */
  public function __construct($productId, $userId, $rating, $comment) {
    $this->productId = $productId;
    $this->userId = $userId;
    $this->rating = $rating;
    $this->comment = $comment;
    $this->timestamp = time(); // Record the current timestamp
  }

  /**
   * Getters for the review properties.
   *
   * @return mixed
   */
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

  public function getTimestamp() {
    return $this->timestamp;
  }

  /**
   *  Returns a string representation of the review object.  Useful for debugging.
   *
   * @return string
   */
  public function __toString() {
    return "Review ID: " . $this->getId() . ", User ID: " . $this->getUserId() . ", Rating: " . $this->getRating() . ", Comment: " . $this->getComment() . ", Timestamp: " . $this->getTimestamp();
  }

  /**
   * Gets the review ID. This is a simple internal ID.
   *
   * @return int
   */
  private function getId() {
    return mt_rand(1000, 9999); //Generate a random ID for each review.  In a real application, you'd use an auto-incrementing database column.
  }


}



/**
 * Example Usage and Basic Review Function
 */

/**
 *  This function takes a review object and returns a user-friendly review string.
 *  It could be expanded to handle validation, sanitization, etc.
 *
 * @param Review $review The Review object to format.
 * @return string  A formatted string representing the review.
 */
function formatReview(Review $review) {
  return "User ID: " . $review->getUserId() . ", Rating: " . $review->getRating() . ", Comment: \"" . htmlspecialchars($review->getComment()) . "\".";
}


// Example Usage:
$review1 = new Review(123, 456, 5, "Excellent product!  Highly recommended.");
$review2 = new Review(456, 789, 4, "Good product, but could be better.");

echo "Review 1: " . formatReview($review1) . "
";
echo "Review 2: " . formatReview($review2) . "
";



//Example of storing reviews (This is just a basic example; a database is preferable in a real application)
$reviews = [];
$reviews[] = $review1;
$reviews[] = $review2;

//Printing out the reviews stored in the array:
echo "Reviews stored in the array:
";
foreach ($reviews as $review) {
    echo formatReview($review) . "
";
}



?>
