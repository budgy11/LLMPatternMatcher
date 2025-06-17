

<?php

/**
 * User Review Class
 *
 * This class allows you to store and display user reviews for a product or item.
 */
class UserReview {

    private $reviewId;
    private $userId;
    private $rating;
    private $comment;
    private $date;

    /**
     * Constructor
     *
     * Initializes a new UserReview object.
     *
     * @param int $reviewId  (Optional)  The unique ID for the review.  If not provided, auto-generated.
     * @param int $userId   The ID of the user who wrote the review.
     * @param int $rating   The rating given by the user (e.g., 1-5).
     * @param string $comment The user's comment about the product/item.
     */
    public function __construct($reviewId = null, $userId, $rating, $comment) {
        $this->reviewId = $reviewId;
        $this->userId = $userId;
        $this->rating = $rating;
        $this->comment = $comment;
        $this->date = date('Y-m-d H:i:s'); // Set current timestamp for date
    }

    /**
     * Getters for each property.
     *
     * @return mixed  The value of the property.
     */
    public function getReviewId() {
        return $this->reviewId;
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

    public function getDate() {
        return $this->date;
    }

    /**
     * Display the review in a formatted string.
     *
     * @return string  A string containing the review details.
     */
    public function displayReview() {
        return "Review ID: " . $this->getReviewId() . "
" .
               "User ID: " . $this->getUserId() . "
" .
               "Rating: " . $this->getRating() . "
" .
               "Comment: " . $this->getComment() . "
" .
               "Date: " . $this->getDate();
    }
}

/**
 * User Review Function (Example Usage)
 */
function processUserReview($userId, $rating, $comment) {
    // Create a new UserReview object
    $review = new UserReview($userId, $userId, $rating, $comment);

    // Display the review
    echo $review->displayReview() . "
";

    // Optionally, you could save this review to a database here...
    // This is just a demonstration.
}


// Example Usage:
processUserReview(123, 5, "Excellent product! Highly recommended.");
processUserReview(456, 3, "It's okay, but could be better.");
?>
