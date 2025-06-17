

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
     * Constructor for the UserReview class.
     *
     * @param int $reviewId (optional) - Unique identifier for the review. If not provided, generated.
     * @param int $userId   - ID of the user submitting the review.
     * @param int $productId - ID of the product being reviewed.
     * @param int $rating    - Rating (e.g., 1-5).
     * @param string $comment - User's comment/feedback.
     */
    public function __construct($reviewId = null, $userId, $productId, $rating, $comment) {
        $this->reviewId = $reviewId;
        $this->userId = $userId;
        $this->productId = $productId;
        $this->rating = $rating;
        $this->comment = $comment;
        $this->date = date('Y-m-d H:i:s'); // Set the date/time of the review
    }

    /**
     * Getters
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
     *  Setters (Optional - use for data modification)
     *  @param mixed $value
     */

     public function setRating($rating) {
        $this->rating = $rating;
     }

     public function setComment($comment) {
        $this->comment = $comment;
     }


    /**
     *  Display Review (for demonstration)
     *  @return string
     */
    public function displayReview() {
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
 * Example Usage
 */
$review1 = new UserReview(null, 123, 456, 5, "Great product! Highly recommend.");
$review2 = new UserReview(10, 789, 112, 4, "Good value for money.");

echo $review1->displayReview() . PHP_EOL;
echo $review2->displayReview() . PHP_EOL;


/**
 *  Function to Process Reviews (example)
 */
function processReviews($reviews) {
    echo "--- Review Summary ---" . PHP_EOL;
    $totalRating = 0;
    $reviewCount = count($reviews);

    foreach ($reviews as $review) {
        $totalRating += $review->getRating();
        echo $review->displayReview() . PHP_EOL;
    }

    if ($reviewCount > 0) {
        $averageRating = $totalRating / $reviewCount;
        echo "Average Rating: " . $averageRating . PHP_EOL;
    } else {
        echo "No reviews yet." . PHP_EOL;
    }
}

// Example usage of the processReviews function
$reviews = [$review1, $review2];
processReviews($reviews);


?>
