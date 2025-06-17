

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
     * @param int $reviewId (Optional, auto-generated)
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
        $this->date = date("Y-m-d H:i:s"); // Set the current date and time
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
     * Setters (Optional - you can add these for updating reviews)
     *
     *  Example:
     *  $review->setComment("Updated comment");
     */

    //  Add setters if you need to update review information later
}

/**
 * User Review Review Function (Simplified)
 */
function generateUserReviewReview(UserReview $review) {
    $reviewData = array(
        'review_id' => $review->getReviewId(),
        'user_id' => $review->getUserId(),
        'product_id' => $review->getProductId(),
        'rating' => $review->getRating(),
        'comment' => $review->getComment(),
        'date' => $review->getDate()
    );

    return $reviewData;
}



// --- Example Usage ---

// Create a review object
$review1 = new UserReview(null, 123, 456, 5, "Great product!");
$reviewData1 = generateUserReviewReview($review1);
print_r($reviewData1);

$review2 = new UserReview(null, 456, 789, 4, "Good but could be better.");
$reviewData2 = generateUserReviewReview($review2);
print_r($reviewData2);

?>
