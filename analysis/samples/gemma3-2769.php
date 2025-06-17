

<?php

/**
 * User Review Class
 * 
 * This class allows you to store and manage user reviews for a product or item.
 */
class UserReview {
    private $product_id;
    private $user_id;
    private $rating;
    private $comment;
    private $date_submitted;

    /**
     * Constructor
     * 
     * Initializes a new UserReview object.
     * 
     * @param int $product_id The ID of the product being reviewed.
     * @param int $user_id The ID of the user submitting the review.
     * @param int $rating The rating given (e.g., 1-5).
     * @param string $comment The user's comment.
     */
    public function __construct($product_id, $user_id, $rating, $comment) {
        $this->product_id = $product_id;
        $this->user_id = $user_id;
        $this->rating = $rating;
        $this->comment = $comment;
        $this->date_submitted = date('Y-m-d H:i:s'); // Get current timestamp
    }

    /**
     * Getters
     */
    public function getProductId() {
        return $this->product_id;
    }

    public function getUserId() {
        return $this->user_id;
    }

    public function getRating() {
        return $this->rating;
    }

    public function getComment() {
        return $this->comment;
    }

    public function getDateSubmitted() {
        return $this->date_submitted;
    }

    /**
     * Display Review (for debugging or display purposes)
     * 
     * Returns a formatted string representing the review.
     * @return string
     */
    public function displayReview() {
        return "Product ID: " . $this->getProductId() . "
"
               . "User ID: " . $this->getUserId() . "
"
               . "Rating: " . $this->getRating() . "
"
               . "Comment: " . $this->getComment() . "
"
               . "Date Submitted: " . $this->getDateSubmitted();
    }
}


/**
 * User Review Function
 *
 * This function takes a user-submitted review and stores it.
 * 
 * @param array $reviewData An associative array containing the review data:
 *                            - product_id => int (Product ID)
 *                            - user_id => int (User ID)
 *                            - rating => int (Rating)
 *                            - comment => string (Comment)
 * @return UserReview|null A UserReview object if the review was successfully created, or null if there was an error.
 */
function storeUserReview(array $reviewData) {
    // Validation (Add more robust validation as needed)
    if (!isset($reviewData['product_id'], $reviewData['user_id'], $reviewData['rating'], $reviewData['comment'])) {
        error_log("Missing review data: " . print_r($reviewData, true));
        return null;
    }

    if (!is_int($reviewData['product_id']) || !is_int($reviewData['user_id']) || !is_int($reviewData['rating'])) {
        error_log("Invalid review data types: " . print_r($reviewData, true));
        return null;
    }

    if ($reviewData['rating'] < 1 || $reviewData['rating'] > 5) {
        error_log("Invalid rating value: " . $reviewData['rating']);
        return null;
    }

    // In a real application, you'd save this to a database
    // For this example, we'll just create a UserReview object.
    $review = new UserReview($reviewData['product_id'], $reviewData['user_id'], $reviewData['rating'], $reviewData['comment']);
    return $review;
}


// Example Usage:
$reviewData = [
    'product_id' => 123,
    'user_id' => 456,
    'rating' => 4,
    'comment' => 'Great product! Highly recommended.'
];

$review = storeUserReview($reviewData);

if ($review) {
    echo "Review created successfully:
";
    echo $review->displayReview() . "
";
} else {
    echo "Error creating review.
";
}


?>
