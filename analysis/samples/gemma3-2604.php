

<?php

/**
 * User Review Class
 *
 * This class allows you to store and manage user reviews for a product or service.
 */
class UserReview {

    /**
     * @var int Product ID
     */
    private $productId;

    /**
     * @var string User's Name
     */
    private $userName;

    /**
     * @var string User's Review Text
     */
    private $reviewText;

    /**
     * @var int Rating (1-5)
     */
    private $rating;

    /**
     * @var DateTime Timestamp of review submission
     */
    private $timestamp;

    /**
     * Constructor
     *
     * @param int $productId
     * @param string $userName
     * @param string $reviewText
     * @param int $rating
     */
    public function __construct(int $productId, string $userName, string $reviewText, int $rating) {
        $this->productId = $productId;
        $this->userName = $userName;
        $this->reviewText = $reviewText;
        $this->rating = $rating;
        $this->timestamp = new DateTime(); // Automatically set the timestamp
    }

    /**
     * Get Product ID
     *
     * @return int
     */
    public function getProductId(): int {
        return $this->productId;
    }

    /**
     * Get User Name
     *
     * @return string
     */
    public function getUserName(): string {
        return $this->userName;
    }

    /**
     * Get Review Text
     *
     * @return string
     */
    public function getReviewText(): string {
        return $this->reviewText;
    }

    /**
     * Get Rating
     *
     * @return int
     */
    public function getRating(): int {
        return $this->rating;
    }

    /**
     * Get Timestamp
     *
     * @return DateTime
     */
    public function getTimestamp(): DateTime {
        return $this->timestamp;
    }

    /**
     * Display Review
     *  This method formats the review data into a user-friendly string.
     *
     * @return string
     */
    public function displayReview(): string {
        return sprintf(
            "Review by: %s
" .
            "Rating: %d
" .
            "Review: %s
" .
            "Submitted on: %s",
            $this->userName,
            $this->rating,
            $this->reviewText,
            $this->timestamp->format('Y-m-d H:i:s') // Formatted timestamp
        );
    }
}


/**
 * User Review Function (Demonstration)
 */
function processUserReview(
    int $productId,
    string $userName,
    string $reviewText,
    int $rating
): UserReview|null {
    // Validation - Add your own validation logic here.
    if (empty($userName)) {
        error_log("Invalid review: User name cannot be empty.");  // Log the error
        return null;
    }
    if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
        error_log("Invalid review: Rating must be between 1 and 5.");
        return null;
    }

    // Create the review object
    $review = new UserReview($productId, $userName, $reviewText, $rating);

    // Return the review object
    return $review;
}


// Example Usage:
$productId = 123;
$userName = "John Doe";
$reviewText = "Great product!  I highly recommend it.";
$rating = 5;

$review = processUserReview($productId, $userName, $reviewText, $rating);

if ($review) {
    echo "--- Review ---";
    echo $review->displayReview();
    echo "
";
} else {
    echo "Review processing failed.  Check the logs for details.";
}

// Example with invalid input:
$invalidReview = processUserReview(456, "", "Some text", 3);  // Empty name
if (!$invalidReview) {
    echo "Invalid review example failed as expected.";
}

?>
