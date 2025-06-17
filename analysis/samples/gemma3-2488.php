

<?php

/**
 * User Review Class
 * 
 * This class allows you to manage and display user reviews.
 */
class UserReview {

    private $reviewId;
    private $userId;
    private $productID;
    private $rating;
    private $comment;
    private $date;

    /**
     * Constructor
     * 
     * Initializes a new UserReview object.
     *
     * @param int $reviewId (Optional) The unique ID for the review. Defaults to null.
     * @param int $userId The ID of the user submitting the review.
     * @param int $productID The ID of the product the review is for.
     * @param int $rating The rating given by the user (e.g., 1-5).
     * @param string $comment The user's comment about the product.
     * @param string $date The date of the review submission (e.g., 'YYYY-MM-DD').
     */
    public function __construct($reviewId = null, $userId, $productID, $rating, $comment, $date) {
        $this->reviewId = $reviewId;
        $this->userId = $userId;
        $this->productID = $productID;
        $this->rating = $rating;
        $this->comment = $comment;
        $this->date = $date;
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

    public function getProductID() {
        return $this->productID;
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
     * Display Review (Simple Formatting)
     *
     * Formats and displays the review details.
     *
     * @return string  The formatted review string.
     */
    public function displayReview() {
        return "Review ID: " . ($this->reviewId ? $this->reviewId : "N/A") .
               "
User ID: " . $this->userId .
               "
Product ID: " . $this->productID .
               "
Rating: " . $this->rating .
               "
Comment: " . $this->comment .
               "
Date: " . $this->date;
    }
}



/**
 *  Example Usage
 */

// Create a review object
$review = new UserReview(null, 123, 456, 5, "Great product! Highly recommended.", '2023-10-27');

// Display the review
echo $review->displayReview() . "
";

//Another example
$review2 = new UserReview(1, 456, 789, 4, "It's okay.", '2023-10-26');
echo $review2->displayReview() . "
";

//  Example of managing reviews in an array (Illustrative - you'd likely use a database)
$reviews = [];
$reviews[] = new UserReview(1, 101, 102, 3, "Good value", '2023-10-25');
$reviews[] = new UserReview(2, 102, 101, 5, "Excellent!", '2023-10-27');


// Display all reviews
echo "--- All Reviews ---
";
foreach ($reviews as $review) {
    echo $review->displayReview() . "

";
}
?>
