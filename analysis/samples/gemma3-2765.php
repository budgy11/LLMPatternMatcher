

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
     * @param int $reviewId (optional) - Unique identifier for the review.  Defaults to null.
     * @param int $userId The ID of the user submitting the review.
     * @param int $productId The ID of the product being reviewed.
     * @param int $rating The rating (e.g., 1-5).
     * @param string $comment The user's comment.
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
     * Setters (can be used for modification)
     *
     * Note:  Consider validation in a real-world application.
     */
    public function setRating($rating) {
        $this->rating = $rating;
    }

    public function setComment($comment) {
        $this->comment = $comment;
    }


    /**
     *  A simple method to display the review information.
     *  This is for demonstration purposes.
     */
    public function displayReview() {
        echo "Review ID: " . ($this->reviewId ?: "N/A") . "<br>";
        echo "User ID: " . $this->userId . "<br>";
        echo "Product ID: " . $this->productId . "<br>";
        echo "Rating: " . $this->rating . "<br>";
        echo "Comment: " . $this->comment . "<br>";
        echo "Date: " . $this->date . "<br>";
    }
}


/**
 * Example Usage
 */

// Create some review objects
$review1 = new UserReview(null, 123, 456, 5, "Great product! Highly recommended.");
$review2 = new UserReview(1, 789, 456, 4, "Good, but could be better.");

// Display the reviews
echo "Review 1:<br>";
$review1->displayReview();
echo "<br>";

echo "Review 2:<br>";
$review2->displayReview();


/**
 *  Extending this for a review system would involve:
 *
 *  - Storing reviews in a database (using PDO, MySQLi, etc.)
 *  - Validation of input data (e.g., rating must be between 1 and 5)
 *  - Adding error handling
 *  - Implementing methods to retrieve, update, and delete reviews.
 */
?>
