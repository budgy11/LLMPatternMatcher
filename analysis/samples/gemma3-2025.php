

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
     * @param int $review_id (Optional) - Unique identifier for the review.
     * @param int $user_id   The ID of the user submitting the review.
     * @param int $product_id The ID of the product being reviewed.
     * @param int $rating     The rating (e.g., 1-5).
     * @param string $comment The user's comment about the product.
     * @param string $date     The date of the review (YYYY-MM-DD format recommended).
     */
    public function __construct($review_id = null, $user_id, $product_id, $rating, $comment, $date) {
        $this->review_id = $review_id;
        $this->user_id = $user_id;
        $this->product_id = $product_id;
        $this->rating = $rating;
        $this->comment = $comment;
        $this->date = $date;
    }

    /**
     * Getters
     */
    public function getReviewId() {
        return $this->review_id;
    }

    public function getUserId() {
        return $this->user_id;
    }

    public function getProductId() {
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
     * Setters (For updating reviews - use with caution!)
     */
    public function setRating($rating) {
        $this->rating = $rating;
    }

    public function setComment($comment) {
        $this->comment = $comment;
    }
}

/**
 * User Review Review Function
 */
/**
 *  This function takes a UserReview object and provides a basic review string.
 *  You can customize this to generate more sophisticated output.
 *
 * @param UserReview $review The UserReview object to review.
 * @return string A formatted review string.
 */
function reviewUserReview(UserReview $review) {
    $userId = $review->getUserId();
    $productId = $review->getProductId();
    $rating = $review->getRating();
    $comment = $review->getComment();
    $date = $review->getDate();

    return "Review ID: " . ($review->getReviewId() ? $review->getReviewId() : "N/A") .
           "
User ID: " . $userId .
           "
Product ID: " . $productId .
           "
Rating: " . $rating .
           "
Comment: " . $comment .
           "
Date: " . $date;
}

// Example Usage:
$review1 = new UserReview(123, 101, 202, 5, "Great product!", "2023-10-27");
$review2 = new UserReview(456, 102, 201, 3, "It's okay.", "2023-10-26");

echo reviewUserReview($review1) . "

";
echo reviewUserReview($review2) . "

";

?>
