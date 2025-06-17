

<?php

/**
 * User Review Class
 *
 * This class provides a basic system for storing and displaying user reviews.
 */
class UserReview {

    private $review_id;
    private $user_id;
    private $rating;
    private $comment;
    private $date_created;

    /**
     * Constructor
     *
     * Initializes a new UserReview object.
     *
     * @param int $user_id The ID of the user submitting the review.
     * @param int $rating The rating given by the user (e.g., 1-5).
     * @param string $comment The user's review comment.
     */
    public function __construct($user_id, $rating, $comment) {
        $this->review_id = null; // Will be assigned by the database
        $this->user_id = $user_id;
        $this->rating = $rating;
        $this->comment = $comment;
        $this->date_created = date("Y-m-d H:i:s"); // Current timestamp
    }

    /**
     * Getters and Setters (for encapsulation)
     */

    public function getReviewId() {
        return $this->review_id;
    }

    public function setReviewId($review_id) {
        $this->review_id = $review_id;
    }

    public function getUserID() {
        return $this->user_id;
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

    public function setRating($rating) {
        $this->rating = $rating;
    }

    public function setComment($comment) {
        $this->comment = $comment;
    }

    /**
     * Display the review as a formatted string.
     *
     * @return string The formatted review string.
     */
    public function displayReview() {
        return "Review ID: " . $this->getReviewId() .
               "
User ID: " . $this->getUserID() .
               "
Rating: " . $this->getRating() .
               "
Comment: " . $this->getComment() .
               "
Date Created: " . $this->date_created;
    }
}



/**
 * User Review Review Function - Example Usage
 */

function displayUserReviews($reviews) {
    echo "<h2>User Reviews</h2>";
    echo "<ul>";
    foreach ($reviews as $review) {
        echo "<li>" . $review->displayReview() . "</li>";
    }
    echo "</ul>";
}


// Example Usage:

// Create some review objects
$review1 = new UserReview(123, 5, "Great product!  Highly recommended.");
$review1->setReviewId(1);

$review2 = new UserReview(456, 3, "It was okay, but could be better.");
$review2->setReviewId(2);


$review3 = new UserReview(789, 4, "Excellent value for the money!");
$review3->setReviewId(3);



// Store the reviews (this would typically involve database interaction)
$reviews = array($review1, $review2, $review3);

// Display the reviews
displayUserReviews($reviews);


?>
