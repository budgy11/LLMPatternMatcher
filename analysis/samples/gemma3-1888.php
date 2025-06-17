

<?php

/**
 * User Review Function
 *
 * This function allows you to store user reviews for a product or item.
 * It includes input validation and handles potential errors.
 */
class UserReview {

  private $productId;
  private $userId;
  private $rating;
  private $comment;

  /**
   * Constructor
   *
   * @param int $productId The ID of the product or item being reviewed.
   * @param int $userId The ID of the user submitting the review.
   * @param int $rating The rating given by the user (e.g., 1-5).
   * @param string $comment The user's comment about the product.
   */
  public function __construct(int $productId, int $userId, int $rating, string $comment) {
    if ($productId <= 0) {
      throw new InvalidArgumentException("Product ID must be a positive integer.");
    }
    if ($userId <= 0) {
      throw new InvalidArgumentException("User ID must be a positive integer.");
    }
    if ($rating < 1 || $rating > 5) {
      throw new InvalidArgumentException("Rating must be between 1 and 5.");
    }
    if (empty($comment)) {
      throw new InvalidArgumentException("Comment cannot be empty.");
    }

    $this->productId = $productId;
    $this->userId = $userId;
    $this->rating = $rating;
    $this->comment = $comment;
  }

  /**
   * Save the review to the database (example implementation - replace with your DB logic)
   *
   * @return bool True if the review was saved successfully, false otherwise.
   */
  public function saveReview() {
    // Placeholder - Replace with your database saving logic.
    // This is just an example to show how to use the data.
    $db = new DatabaseConnection(); // Assuming you have a database connection class
    $sql = "INSERT INTO reviews (product_id, user_id, rating, comment) 
            VALUES ('" . $this->productId . "', '" . $this->userId . "', '" . $this->rating . "', '" . $this->comment . "')";
    
    return $db->execute($sql);
  }

  /**
   * Get Review Details
   *
   * @return array An array containing the review details.
   */
  public function getReviewDetails() {
    return [
      'product_id' => $this->productId,
      'user_id' => $this->userId,
      'rating' => $this->rating,
      'comment' => $this->comment
    ];
  }
}

// Example Usage:
try {
  $review = new UserReview(123, 45, 4, "Great product! I love it.");
  if ($review->saveReview()) {
    echo "Review saved successfully!
";
    $details = $review->getReviewDetails();
    print_r($details);
  } else {
    echo "Failed to save review.
";
  }
} catch (InvalidArgumentException $e) {
  echo "Error: " . $e->getMessage() . "
";
}

//  Example of Validation Error
try {
    $review = new UserReview(123, -45, 3, "Bad review"); // Invalid user ID
} catch (InvalidArgumentException $e) {
    echo "Error: " . $e->getMessage() . "
"; // Output: Error: User ID must be a positive integer.
}

?>
