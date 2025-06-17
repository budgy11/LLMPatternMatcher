

<?php

/**
 * User Review Class
 *
 * This class allows you to store and manage user reviews for a product or item.
 */
class UserReview {

    private $reviewId;
    private $productId;
    private $userId;
    private $rating;
    private $comment;
    private $date;

    /**
     * Constructor
     *
     * Initializes a new UserReview object.
     *
     * @param int $productId The ID of the product being reviewed.
     * @param int $userId The ID of the user submitting the review.
     * @param int $rating  The rating given by the user (e.g., 1-5).
     * @param string $comment The user's review comment.
     */
    public function __construct($productId, $userId, $rating, $comment) {
        $this->productId = $productId;
        $this->userId = $userId;
        $this->rating = $rating;
        $this->comment = $comment;
        $this->date = date('Y-m-d H:i:s'); // Set the current date and time
    }

    /**
     * Getters and Setters (Encapsulation)
     */

    public function getReviewId() {
        return $this->reviewId;
    }

    public function setReviewId($reviewId) {
        $this->reviewId = $reviewId;
    }

    public function getProductId() {
        return $this->productId;
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

    public function getCommentLength() {
        return strlen($this->comment);
    }


    public function getDate() {
        return $this->date;
    }


    /**
     *  A simple method for representing the review as a string. Useful for debugging or display.
     *
     * @return string
     */
    public function __toString() {
        return "Review ID: " . $this->reviewId . ", Product ID: " . $this->productId . ", User ID: " . $this->userId . ", Rating: " . $this->rating . ", Comment: " . $this->comment . ", Date: " . $this->date;
    }
}

/**
 * User Review Function (Example Usage)
 */
function displayUserReviews($reviews) {
    echo "<h2>User Reviews</h2>";
    if (empty($reviews)) {
        echo "<p>No reviews yet.</p>";
        return;
    }

    echo "<ul>";
    foreach ($reviews as $review) {
        echo "<li>" . $review . "</li>"; // Uses the __toString() method
    }
    echo "</ul>";
}

// Example Usage
$review1 = new UserReview(123, 456, 5, "Great product!  I highly recommend it.");
$review2 = new UserReview(456, 789, 4, "It's okay, but could be better.");
$review3 = new UserReview(789, 123, 3, "Not bad, but pricey.");

$allReviews = [$review1, $review2, $review3];

displayUserReviews($allReviews);

// Example of using the length method
echo "<p>Review 1 Length: " . $review1->getCommentLength() . "</p>";

?>
