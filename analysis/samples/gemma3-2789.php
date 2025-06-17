

<?php

/**
 * Reviews class to manage user reviews for a product.
 */
class Review {

  private $productId;
  private $userId;
  private $rating;
  private $comment;

  /**
   * Constructor for the Review class.
   *
   * @param int $productId The ID of the product the review is for.
   * @param int $userId The ID of the user submitting the review.
   * @param int $rating The rating given by the user (e.g., 1-5).
   * @param string $comment The user's written review.
   */
  public function __construct(int $productId, int $userId, int $rating, string $comment) {
    $this->productId = $productId;
    $this->userId = $userId;
    $this->rating = $rating;
    $this->comment = $comment;
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

  /**
   * Validates the review data.
   *
   * @return bool True if the review data is valid, false otherwise.
   */
  public function isValid(): bool {
    // Add validation logic here.  For example:
    // - Rating must be between 1 and 5
    // - Comment cannot be empty
    return $this->rating >= 1 && $this->rating <= 5 && !empty($this->comment);
  }

  /**
   *  Outputs the review in a formatted string
   * @return string A formatted string representation of the review.
   */
  public function __toString(): string {
    if (!$this->isValid()) {
      return "Invalid Review Data";
    }
    return "Product ID: " . $this->productId .
           "
User ID: " . $this->userId .
           "
Rating: " . $this->rating .
           "
Comment: " . $this->comment;
  }

}

/**
 *  User review function
 */
function generateReviewUserInterface() {
    echo "<!DOCTYPE html>";
    echo "<html>";
    echo "<head>";
    echo "<title>User Review Form</title>";
    echo "</head>";
    echo "<body>";
    echo "<h1>User Review Form</h1>";

    echo "<form method='post' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>";
    echo "<label for='productId'>Product ID:</label><br>";
    echo "<input type='number' id='productId' name='productId' required><br><br>";

    echo "<label for='userId'>User ID:</label><br>";
    echo "<input type='number' id='userId' name='userId' required><br><br>";

    echo "<label for='rating'>Rating (1-5):</label><br>";
    echo "<input type='number' id='rating' name='rating' min='1' max='5' required><br><br>";

    echo "<label for='comment'>Comment:</label><br>";
    echo "<textarea id='comment' name='comment' rows='4' cols='50' required></textarea><br><br>";

    echo "<input type='submit' value='Submit Review'>";
    echo "</form>";
    echo "</body>";
    echo "</html>";
}


// Example usage:
// Create a review object
$review = new Review(123, 456, 4, "Great product!");

// Print the review
echo $review; // Output the formatted review string

// Generate HTML for user review form
generateReviewUserInterface();

?>
