

<?php

/**
 * User Review Class
 *
 * This class provides a basic structure for managing user reviews.
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
   * Initializes a new UserReview object.
   * 
   * @param int $review_id (optional) Unique ID for the review. If not provided, one will be generated.
   * @param int $user_id  The ID of the user leaving the review.
   * @param int $product_id The ID of the product being reviewed.
   * @param int $rating   The rating given by the user (e.g., 1-5).
   * @param string $comment The user's comment about the product.
   */
  public function __construct(
    int $user_id,
    int $product_id,
    int $rating,
    string $comment
  ) {
    $this->generateUniqueId();
    $this->user_id = $user_id;
    $this->product_id = $product_id;
    $this->rating = $rating;
    $this->comment = $comment;
    $this->date = date('Y-m-d H:i:s'); // Get current timestamp
  }


  /**
   * Generates a unique ID for the review.
   */
  private function generateUniqueId() {
    $this->review_id = uniqid(); // Use uniqid() for a unique string.
  }

  /**
   * Getters
   */
  public function getReviewId() {
    return $this->review_id;
  }

  public function getUserID() {
    return $this->user_id;
  }

  public function getProductID() {
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
   *  String representation of the review object. Useful for debugging or output.
   */
  public function __toString() {
    return "Review ID: " . $this->review_id . "
" .
           "User ID: " . $this->user_id . "
" .
           "Product ID: " . $this->product_id . "
" .
           "Rating: " . $this->rating . "
" .
           "Comment: " . $this->comment . "
" .
           "Date: " . $this->date;
  }
}


/**
 *  User Review Review Function Example
 */
function displayUserReview(UserReview $review) {
  echo $review; // Uses the __toString() method of the UserReview class.
}

// Example Usage:
$review1 = new UserReview(123, 456, 5, "Great product!  Highly recommend.");
displayUserReview($review1);

$review2 = new UserReview(789, 101, 3, "It was okay, but could be better.");
displayUserReview($review2);


//  Simulated Database Interaction (for demonstration - replace with real DB code)
class ReviewDatabase {
    private $reviews = [];

    public function addReview(UserReview $review) {
        $this->reviews[] = $review;
    }

    public function getReviews() {
        return $this->reviews;
    }
}

$db = new ReviewDatabase();
$db->addReview($review1);
$db->addReview($review2);
echo "
All Reviews:
";
print_r($db->getReviews());

?>
