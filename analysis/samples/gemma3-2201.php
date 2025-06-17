

// Example Usage:

// Create a review
$result = createReview("123", "john_doe", "5", "Great product!");

if ($result['success']) {
    echo "Review created: " . $result['message'] . "
";
} else {
    echo "Error creating review: " . $result['message'] . "
";
}

// Get reviews for product ID 123
$reviews = getReviews("123");

if (count($reviews) > 0) {
    echo "Reviews for product 123:
";
    foreach ($reviews as $review) {
        echo "- " . $review['username'] . ": " . $review['rating'] . " - " . $review['comment'] . "
";
    }
} else {
    echo "No reviews found for product 123
";
}

// Update a review (example)
$updateResult = updateReview(1, 4, "Improved rating");

if ($updateResult['success']) {
  echo "Review updated: " . $updateResult['message'] . "
";
}
else
{
    echo "Error updating review: " . $updateResult['message'] . "
";
}

// Delete a review (example)
$deleteResult = deleteReview(1);

if ($deleteResult['success']) {
  echo "Review deleted: " . $deleteResult['message'] . "
";
}
else
{
    echo "Error deleting review: " . $deleteResult['message'] . "
";
}


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
     * @param int $reviewId (optional, auto-incrementing ID)
     * @param int $userId
     * @param int $productId
     * @param int $rating (1-5)
     * @param string $comment
     */
    public function __construct($reviewId = null, $userId, $productId, $rating, $comment) {
        $this->reviewId = $reviewId;
        $this->userId = $userId;
        $this->productId = $productId;
        $this->rating = $rating;
        $this->comment = $comment;
        $this->date = date('Y-m-d H:i:s'); // Set current timestamp
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
     * Setters (Use with caution - validate data)
     */
    public function setRating($rating) {
        if ($rating >= 1 && $rating <= 5) {
            $this->rating = $rating;
        } else {
            throw new Exception("Rating must be between 1 and 5.");
        }
    }

    // Add setters for other properties as needed.  Validate data!
}


/**
 * User Review Review Function
 */
function reviewUserReview($review) {
  // This function is designed to provide a simple review of a UserReview object.
  // You can extend this to include more sophisticated validation and output formatting.

  // Validate the review object
  if (!is_object($review) || !($review instanceof UserReview)) {
    throw new Exception("Invalid review object. Must be an instance of UserReview.");
  }

  $userId = $review->getUserId();
  $rating = $review->getRating();
  $comment = $review->getComment();
  $productId = $review->getProductId();

  // Basic validation - improve this!
  if (empty($comment)) {
    throw new Exception("Comment cannot be empty.");
  }

  // Construct a review string
  $reviewString = "Review ID: " . ($review->getReviewId() ? $review->getReviewId() : "N/A") . "
" .
                  "User ID: " . $userId . "
" .
                  "Product ID: " . $productId . "
" .
                  "Rating: " . $rating . "
" .
                  "Comment: " . $comment . "
" .
                  "Date: " . $review->getDate();


  return $reviewString;
}



// --- Example Usage ---

// Create a review object
$review = new UserReview(null, 123, 456, 5, "This product is amazing!");

try {
    // Review the review
    $reviewString = reviewUserReview($review);
    echo $reviewString . PHP_EOL; // Output the review
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
}


// Example of invalid review (empty comment)
$review2 = new UserReview(null, 456, 789, 3, "");

try {
    $reviewString = reviewUserReview($review2);
    echo $reviewString . PHP_EOL;
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL; // Output: Error: Comment cannot be empty.
}


?>
