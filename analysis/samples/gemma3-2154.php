

<?php

/**
 * User Review Class
 *
 * This class handles the creation, storage, and retrieval of user reviews.
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
     * Initializes a new UserReview object.
     *
     * @param int $userId    The ID of the user submitting the review.
     * @param int $productId The ID of the product being reviewed.
     * @param int $rating    The rating given by the user (e.g., 1-5).
     * @param string $comment The user's review comment.
     */
    public function __construct($userId, $productId, $rating, $comment) {
        $this->userId = $userId;
        $this->productId = $productId;
        $this->rating = $rating;
        $this->comment = $comment;
        $this->date = date('Y-m-d H:i:s'); // Current timestamp
    }

    /**
     * Getters
     *
     * Returns the values of the review properties.
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
     *  Setter for reviewId (optional, typically only set during storage)
     *  @param int $reviewId
     */
    public function setReviewId($reviewId) {
        $this->reviewId = $reviewId;
    }

}


/**
 * User Review Review Function -  A basic function to simulate a user review functionality.
 * This is more of a demonstration and can be expanded significantly.
 */
function displayUserReview($review) {
    if ($review instanceof UserReview) {
        echo "<h2>Review for Product ID: " . $review->getProductId() . "</h2>";
        echo "<p><strong>User ID:</strong> " . $review->getUserId() . "</p>";
        echo "<p><strong>Rating:</strong> " . $review->getRating() . " / 5</p>";
        echo "<p><strong>Comment:</strong> " . $review->getComment() . "</p>";
        echo "<p><strong>Date:</strong> " . $review->getDate() . "</p>";
    } else {
        echo "Invalid Review Object.  Expected UserReview object.";
    }
}



// --- Example Usage ---

// Create some reviews
$review1 = new UserReview(123, 456, 5, "Great product!  Highly recommend.");
$review2 = new UserReview(456, 789, 4, "Good, but could be better.");
$review3 = new UserReview(789, 101, 3, "Average.  Nothing special.");

// Display the reviews
displayUserReview($review1);
echo "<br>";
displayUserReview($review2);
echo "<br>";
displayUserReview($review3);

?>
