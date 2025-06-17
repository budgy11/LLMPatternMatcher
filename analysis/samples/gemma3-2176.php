

<?php

/**
 *  User Review Function - A simple example.
 *
 *  Allows users to submit and view reviews for a product or service.
 *
 *  This is a basic example and should be expanded upon for a real-world application.
 */

class Review {

  private $reviewId;
  private $productId; // Associate with a product or service
  private $userId;
  private $rating; // Numeric rating (e.g., 1-5)
  private $comment;
  private $date;

  /**
   * Constructor
   *
   * @param int $productId The ID of the product or service being reviewed.
   * @param int $userId The ID of the user submitting the review.
   * @param int $rating The rating given (1-5).
   * @param string $comment The user's review comment.
   */
  public function __construct(int $productId, int $userId, int $rating, string $comment) {
    $this->productId = $productId;
    $this->userId = $userId;
    $this->rating = $rating;
    $this->comment = $comment;
    $this->date = date('Y-m-d H:i:s'); // Set the current timestamp
  }

  /**
   * Getters
   */
  public function getReviewId(): int {
    return $this->reviewId;
  }

  public function getProductId(): int {
    return $this->productId;
  }

  public function getUserId(): int {
    return $this->userId;
  }

  public function getRating(): int {
    return $this->rating;
  }

  public function getComment(): string {
    return $this->comment;
  }

  public function getDate(): string {
    return $this->date;
  }


  /**
   *  Save the Review (Placeholder - replace with database integration)
   *  This is a placeholder and should be replaced with your database logic.
   *
   * @return bool True if the review was saved, false otherwise.
   */
  public function save(): bool {
    // Placeholder - Replace with database logic (e.g., INSERT query)
    // Example:
    // $db = new DatabaseConnection();  // Assuming you have a DatabaseConnection class
    // $result = $db->insertReview($this->productId, $this->userId, $this->rating, $this->comment);
    // return $result;

    // For this example, just return true:
    return true;
  }

  /**
   *  Display the Review
   *
   * @return string HTML representation of the review.
   */
  public function display(): string {
    return "<div class='review'>" .
           "<b>Product ID:</b> " . $this->productId . "<br>" .
           "<b>User ID:</b> " . $this->userId . "<br>" .
           "<b>Rating:</b> " . $this->rating . "<br>" .
           "<b>Comment:</b> " . $this->comment . "<br>" .
           "<b>Date:</b> " . $this->date . "</div>";
  }
}


// Example Usage:
$review1 = new Review(123, 45, 5, "Great product!  Highly recommended.");
$review1->save(); // Save the review

echo $review1->display(); // Display the review


// Example of creating another review
$review2 = new Review(456, 78, 3, "It was okay, but could be better.");
$review2->save();
echo "<br>";
echo $review2->display();

?>
