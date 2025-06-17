

<?php

/**
 * User Review Class
 *
 * This class allows you to store and manage user reviews for a product or item.
 */
class UserReview {

    private $reviewId;
    private $userId;
    private $productId;
    private $rating; // 1-5
    private $comment;
    private $date;

    /**
     * Constructor
     *
     * Initializes a new UserReview object.
     *
     * @param int $userId  The ID of the user submitting the review.
     * @param int $productId The ID of the product/item being reviewed.
     * @param int $rating The rating given by the user (1-5).
     * @param string $comment The user's comment about the product/item.
     */
    public function __construct($userId, $productId, $rating, $comment) {
        $this->reviewId = null; // Will be assigned automatically by the database
        $this->userId = $userId;
        $this->productId = $productId;
        $this->rating = $rating;
        $this->comment = $comment;
        $this->date = date('Y-m-d H:i:s'); // Set the current timestamp
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
     * Setters (if needed - can be added for modifying review data)
     */
    public function setRating($rating) {
        $this->rating = $rating;
    }

    // Add more setters as needed (e.g., for comment)
}


/**
 * User Review Function
 *
 * This function takes a UserReview object and returns a formatted string
 * representing the review.
 *
 * @param UserReview $review The UserReview object to format.
 *
 * @return string A formatted string of the review data.
 */
function formatUserReview(UserReview $review) {
    $userId = $review->getUserId();
    $rating = $review->getRating();
    $comment = $review->getComment();
    $date = $review->getDate();

    return "Review ID: " . $review->getReviewId() . "
" .
           "User ID: " . $userId . "
" .
           "Rating: " . $rating . "/5
" .
           "Comment: " . $comment . "
" .
           "Date: " . $date;
}



// --- Example Usage ---

// Create a new review
$review = new UserReview(123, 456, 5, "Excellent product!  Highly recommended.");

// Format the review
$formattedReview = formatUserReview($review);

// Print the formatted review
echo $formattedReview . "
";

// Example with a different review
$review2 = new UserReview(789, 101, 3, "Good, but could be better.");
echo formatUserReview($review2) . "
";

?>
