

<?php

/**
 * User Review Class
 */
class UserReview {

    /**
     * @var int  The ID of the reviewed item (e.g., product, movie, etc.)
     */
    private $itemId;

    /**
     * @var string The user's review text
     */
    private $reviewText;

    /**
     * @var int  The rating given (e.g., 1-5 stars)
     */
    private $rating;

    /**
     * @var string The user's name
     */
    private $userName;

    /**
     * @var DateTime  Timestamp of when the review was created.
     */
    private $createdAt;

    /**
     * Constructor
     *
     * @param int $itemId The ID of the reviewed item.
     * @param string $reviewText The user's review text.
     * @param int $rating The rating given (1-5).
     * @param string $userName The user's name.
     */
    public function __construct(int $itemId, string $reviewText, int $rating, string $userName) {
        $this->itemId = $itemId;
        $this->reviewText = $reviewText;
        $this->rating = $rating;
        $this->userName = $userName;
        $this->createdAt = new DateTime(); // Use DateTime class for accurate timestamps
    }

    /**
     * Getters
     *
     * @return int
     */
    public function getItemId(): int {
        return $this->itemId;
    }

    /**
     * Get the review text.
     *
     * @return string
     */
    public function getReviewText(): string {
        return $this->reviewText;
    }

    /**
     * Get the rating.
     *
     * @return int
     */
    public function getRating(): int {
        return $this->rating;
    }

    /**
     * Get the user's name.
     *
     * @return string
     */
    public function getUserName(): string {
        return $this->userName;
    }

    /**
     * Get the creation timestamp.
     *
     * @return DateTime
     */
    public function getCreatedAt(): DateTime {
        return $this->createdAt;
    }

    /**
     * Display the review as a formatted string.
     *
     * @return string
     */
    public function __toString(): string {
        return sprintf(
            "Review by: %s
" .
            "Item ID: %d
" .
            "Rating: %d
" .
            "Review: %s
" .
            "Created at: %s",
            $this->userName,
            $this->itemId,
            $this->rating,
            $this->reviewText,
            $this->createdAt->format('Y-m-d H:i:s') // Format the timestamp
        );
    }
}


/**
 * User Review Review Function (Example)
 */
function reviewUser(UserReview $review) {
    echo $review . "
";
}


// Example Usage:
$productReview = new UserReview(123, "Great product!  I love it!", 5, "John Doe");
reviewUser($productReview);


$movieReview = new UserReview(456, "A fantastic movie, highly recommended!", 4, "Jane Smith");
reviewUser($movieReview);


// Demonstrating Getters
echo "
Item ID: " . $productReview->getItemId() . "
";
echo "Rating: " . $productReview->getRating() . "
";


?>
