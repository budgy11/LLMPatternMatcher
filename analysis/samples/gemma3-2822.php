

<?php

/**
 * User Review Class
 *
 * This class allows you to store, display, and manage user reviews.
 */
class UserReview {

  private $reviewId;
  private $userId;
  private $rating;
  private $comment;
  private $date;

  /**
   * Constructor
   *
   * Initializes a new UserReview object.
   *
   * @param int $reviewId (Optional) - The unique identifier for the review. If not provided, one will be generated.
   * @param int $userId The ID of the user submitting the review.
   * @param int $rating The rating given by the user (e.g., 1-5).
   * @param string $comment The user's review comment.
   */
  public function __construct($reviewId = null, $userId, $rating, $comment) {
    $this->reviewId = $reviewId;
    $this->userId = $userId;
    $this->rating = $rating;
    $this->comment = $comment;
    $this->date = date('Y-m-d H:i:s'); // Set the date and time of the review
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
   * Display the review in a formatted string.
   *
   * @return string  The review formatted as a string.
   */
  public function displayReview() {
    return "Review ID: " . $this->getReviewId() .
           "
User ID: " . $this->getUserId() .
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
 *
 * This function takes an array of UserReview objects and outputs a formatted display
 * of all the reviews.
 *
 * @param array $reviews An array of UserReview objects.
 */
function displayReviews(array $reviews) {
  echo "<h2>User Reviews</h2>
";
  foreach ($reviews as $review) {
    echo $review->displayReview() . "

";
  }
}


// Example Usage:
// Create some UserReview objects
$review1 = new UserReview(1, 123, 5, "Excellent product!  Highly recommended.");
$review2 = new UserReview(2, 456, 4, "Good value for the price.");
$review3 = new UserReview(3, 789, 3, "It's okay, but could be better.");


// Store the reviews in an array
$reviews = array($review1, $review2, $review3);

// Call the displayReviews function to output the reviews
displayReviews($reviews);

?>
