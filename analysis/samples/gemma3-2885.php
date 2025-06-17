

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
   * @param int $review_id (optional) - Unique ID for the review.  If not provided, generates one.
   * @param int $user_id  - The ID of the user who wrote the review.
   * @param int $product_id - The ID of the product being reviewed.
   * @param int $rating  -  The rating (e.g., 1-5).
   * @param string $comment - The user's comment about the product.
   */
  public function __construct(
    int $user_id,
    int $product_id,
    int $rating,
    string $comment = ""  // Default comment to empty string
  ) {
    $this->generateReviewId();
    $this->user_id = $user_id;
    $this->product_id = $product_id;
    $this->rating = $rating;
    $this->comment = $comment;
    $this->date = date("Y-m-d H:i:s"); // Get current timestamp
  }

  /**
   * Generates a unique review ID.
   */
  private function generateReviewId(): void {
    // You could use database sequence or a unique string generation method.
    // For simplicity, using a timestamp and a counter.
    static $counter = 0;
    $this->review_id = $counter++;
  }

  /**
   * Getters
   */
  public function getReviewId(): int {
    return $this->review_id;
  }

  public function getUser(): int {
    return $this->user_id;
  }

  public function getProduct(): int {
    return $this->product_id;
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
   * Returns the review as an array. Useful for database insertion.
   * @return array
   */
  public function toArray(): array {
    return [
      'review_id' => $this->review_id,
      'user_id' => $this->user_id,
      'product_id' => $this->product_id,
      'rating' => $this->rating,
      'comment' => $this->comment,
      'date' => $this->date
    ];
  }
}


/**
 * User Review Function (Example)
 */
function displayReviewDetails(UserReview $review): void {
  echo "Review ID: " . $review->getReviewId() . "<br>";
  echo "User ID: " . $review->getUser() . "<br>";
  echo "Product ID: " . $review->getProduct() . "<br>";
  echo "Rating: " . $review->getRating() . "<br>";
  echo "Comment: " . $review->getComment() . "<br>";
  echo "Date: " . $review->getDate() . "<br>";
}


// Example Usage:

// Create a new review object
$review1 = new UserReview(123, 456, 5, "Great product!  I highly recommend it.");
$review2 = new UserReview(789, 101, 3, "Okay, but could be better.");
$review3 = new UserReview(101, 102); //uses default empty comment.


// Display the review details
displayReviewDetails($review1);
displayReviewDetails($review2);
displayReviewDetails($review3);

// Demonstrate the toArray method:
$review_data = $review1->toArray();
print_r($review_data);  // Output will be an array.



//  Considerations for a Real Application:

// 1. Database Integration:  The code above is a basic example. In a real application,
//    you would store the reviews in a database (e.g., MySQL, PostgreSQL).

// 2. Validation: Add input validation to ensure that the rating is within a valid range (e.g., 1-5) and that required fields are not empty.

// 3. Error Handling: Implement error handling to gracefully handle potential errors, such as database connection errors.

// 4. Security:  Sanitize user input to prevent security vulnerabilities (e.g., SQL injection).

// 5.  More Complex Interactions: Consider features like pagination for displaying reviews, sorting reviews, and searching for reviews.
?>
