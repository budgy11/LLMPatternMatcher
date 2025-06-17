

<?php

/**
 * User Review Class - Allows users to submit and view reviews.
 */
class UserReview {

  private $reviews = [];

  /**
   * Adds a new review.
   *
   * @param string $username The username of the reviewer.
   * @param string $comment The review comment.
   * @return bool True if the review was successfully added, false otherwise.
   */
  public function addReview(string $username, string $comment) {
    if (empty($username) || empty($comment)) {
      return false; // Or throw an exception for better error handling
    }

    $this->reviews[] = [
      'username' => $username,
      'comment' => $comment,
      'date' => date('Y-m-d H:i:s') // Add timestamp for sorting
    ];
    return true;
  }

  /**
   * Retrieves all reviews.
   *
   * @return array An array of review objects.
   */
  public function getAllReviews() {
    return $this->reviews;
  }

  /**
   * Retrieves reviews based on a specified criteria (e.g., most recent).
   *
   * @param string $sortCriteria (optional) 'recent' or 'oldest'. Defaults to 'recent'.
   * @return array An array of review objects, sorted based on the criteria.
   */
  public function getReviews($sortCriteria = 'recent') {
    if ($sortCriteria !== 'recent' && $sortCriteria !== 'oldest') {
      //  Optionally throw an exception for invalid criteria
      //throw new InvalidArgumentException("Invalid sort criteria.  Must be 'recent' or 'oldest'.");
      $sortCriteria = 'recent';
    }

    // Sort the reviews based on the criteria.  'recent' sorts by date descending
    usort($this->reviews, function ($review1, $review2) {
      return $review2['date'] <=> $review1['date'];
    });

    return $this->reviews;
  }


  /**
   * Removes a review by username.
   *
   * @param string $username The username of the review to remove.
   * @return bool True if the review was successfully removed, false otherwise.
   */
  public function removeReview(string $username) {
    global $reviews; // Access the global reviews array
    foreach ($reviews as $key => $review) {
      if ($review['username'] === $username) {
        unset($reviews[$key]);
        return true;
      }
    }
    return false;
  }
}



// --- Example Usage ---

// Create a Review object
$reviewSystem = new UserReview();

// Add some reviews
$reviewSystem->addReview('JohnDoe', 'Great product, highly recommend!');
$reviewSystem->addReview('JaneSmith', 'Could be better, needs more features.');
$reviewSystem->addReview('PeterJones', 'Excellent value for money.');

// Get all reviews
$allReviews = $reviewSystem->getAllReviews();
echo "<h2>All Reviews:</h2>";
echo "<pre>";
print_r($allReviews);
echo "</pre>";

// Get reviews sorted by recent date
$recentReviews = $reviewSystem->getReviews();
echo "<h2>Recent Reviews:</h2>";
echo "<pre>";
print_r($recentReviews);
echo "</pre>";

// Get reviews sorted by oldest date
$oldestReviews = $reviewSystem->getReviews('oldest');
echo "<h2>Oldest Reviews:</h2>";
echo "<pre>";
print_r($oldestReviews);
echo "</pre>";

// Remove a review
$reviewSystem->removeReview('JaneSmith');

// Verify removal
echo "<h2>Reviews After Removing JaneSmith:</h2>";
echo "<pre>";
print_r($reviewSystem->getAllReviews());
echo "</pre>";

?>
