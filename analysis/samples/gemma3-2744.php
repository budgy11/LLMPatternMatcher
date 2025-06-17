

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
   * @param int $reviewId (Optional) - Unique ID for the review.  If not provided, a new ID will be generated.
   * @param int $userId  The ID of the user who wrote the review.
   * @param int $productId The ID of the product being reviewed.
   * @param int $rating   The rating (e.g., 1-5 stars).
   * @param string $comment The user's review comment.
   */
  public function __construct($reviewId = null, $userId, $productId, $rating, $comment) {
    $this->reviewId = $reviewId;
    $this->userId = $userId;
    $this->productId = $productId;
    $this->rating = $rating;
    $this->comment = $comment;
    $this->date = date("Y-m-d H:i:s"); // Set the review date/time
  }

  /**
   * Getters for each property
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
   * Sets a property (for update operations)
   *
   * @param string $property The property to update (e.g., 'comment', 'rating')
   * @param mixed $value The new value for the property.
   */
  public function setProperty($property, $value) {
    switch ($property) {
      case 'comment':
        $this->comment = $value;
        break;
      case 'rating':
        $this->rating = $value;
        break;
      // Add more cases for other properties as needed
      default:
        // Handle invalid property names - throw an exception or log an error
        throw new InvalidArgumentException("Invalid property name: " . $property);
    }
  }

  /**
   *  Display the review in a formatted string.
   *
   * @return string  A formatted string representing the review.
   */
  public function __toString() {
    return "Review ID: " . $this->getReviewId() .
           "
User ID: " . $this->getUserId() .
           "
Product ID: " . $this->getProductId() .
           "
Rating: " . $this->getRating() .
           "
Comment: " . $this->getComment() .
           "
Date: " . $this->getDate();
  }
}

/**
 * User Review Review Function
 */
function displayReview(UserReview $review) {
  echo $review; // Use the __toString() method of the UserReview class
}


// Example Usage:
$review1 = new UserReview(null, 123, 456, 5, "Great product! Highly recommended.");
displayReview($review1);

echo "
-------------------
";

$review2 = new UserReview(null, 456, 789, 3, "It was okay, but a bit pricey.");
displayReview($review2);

echo "
-------------------
";

$review3 = new UserReview(10, 789, 123, 4, "Excellent value for the money!");
$review3->setProperty("comment", "Updated my comment.");
displayReview($review3); // Show updated comment

?>
