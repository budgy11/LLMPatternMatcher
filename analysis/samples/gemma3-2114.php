

<?php

/**
 * User Review Class
 *
 * This class provides a basic structure for managing user reviews.
 */
class UserReview {

  /**
   * @var int User ID
   */
  private $userId;

  /**
   * @var string Review Text
   */
  private $reviewText;

  /**
   * @var int Rating (e.g., 1-5 stars)
   */
  private $rating;

  /**
   * @var DateTime Timestamp of review creation
   */
  private $timestamp;

  /**
   * Constructor
   *
   * @param int $userId
   * @param string $reviewText
   * @param int $rating
   */
  public function __construct(int $userId, string $reviewText, int $rating) {
    $this->userId = $userId;
    $this->reviewText = $reviewText;
    $this->rating = $rating;
    $this->timestamp = new DateTime(); // Automatically timestamp creation
  }

  /**
   * Get User ID
   *
   * @return int
   */
  public function getUserId(): int {
    return $this->userId;
  }

  /**
   * Get Review Text
   *
   * @return string
   */
  public function getReviewText(): string {
    return $this->reviewText;
  }

  /**
   * Get Rating
   *
   * @return int
   */
  public function getRating(): int {
    return $this->rating;
  }

  /**
   * Get Timestamp
   *
   * @return DateTime
   */
  public function getTimestamp(): DateTime {
    return $this->timestamp;
  }

  /**
   * Display Review (Simple Formatting)
   *
   * @return string
   */
  public function displayReview(): string {
    return "User ID: " . $this->userId . "
" .
           "Review: " . $this->reviewText . "
" .
           "Rating: " . $this->rating . "
" .
           "Timestamp: " . $this->timestamp->format('Y-m-d H:i:s');
  }
}


/**
 * User Review Review Function
 *
 * This function allows you to create and display user reviews.
 *
 * @param array $reviews An array of UserReview objects.
 */
function displayReviews(array $reviews) {
  echo "<h2>User Reviews</h2>
";

  if (empty($reviews)) {
    echo "<p>No reviews yet.</p>
";
    return;
  }

  foreach ($reviews as $review) {
    echo "<h3>Review:</h3>
";
    echo $review->displayReview() . "

";
  }
}


// Example Usage:
// Create some reviews
$review1 = new UserReview(123, "Great product! Highly recommended.", 5);
$review2 = new UserReview(456, "Could be better, but decent.", 3);
$review3 = new UserReview(789, "Amazing!  I love it!", 5);

// Store the reviews in an array
$reviews = [$review1, $review2, $review3];

// Display the reviews
displayReviews($reviews);


//---  Another Example:  Creating Reviews from Input (Illustrative)---
// This part demonstrates how you'd integrate this with a form.
//  For simplicity, we'll use a simplified form with hardcoded values.
//  In a real application, you'd use $_POST or $_GET to collect the data.

//  Create a form (for example, in an HTML file)
/*
<form method="post">
  <label for="userId">User ID:</label>
  <input type="number" id="userId" name="userId" value="123"><br><br>

  <label for="reviewText">Review Text:</label><br>
  <textarea id="reviewText" name="reviewText" rows="4" cols="50"></textarea><br><br>

  <label for="rating">Rating (1-5):</label>
  <select id="rating" name="rating">
    <option value="1">1</option>
    <option value="2">2</option>
    <option value="3">3</option>
    <option value="4">4</option>
    <option value="5">5</option>
  </select><br><br>

  <input type="submit" value="Submit Review">
</form>
*/

// Simulate form submission (replace with actual $_POST data in a real app)
/*
if (isset($_POST['userId']) && isset($_POST['reviewText']) && isset($_POST['rating'])) {
  $userId = (int)$_POST['userId'];
  $reviewText = $_POST['reviewText'];
  $rating = (int)$_POST['rating'];

  $newReview = new UserReview($userId, $reviewText, $rating);
  // Add the new review to the $reviews array (or store it in a database).
  $reviews[] = $newReview;
}
*/

// Then call displayReviews($reviews); to display the new review(s).
?>
