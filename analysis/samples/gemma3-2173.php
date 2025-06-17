

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
     * @param int $reviewId (Optional) - Unique ID for the review. If not provided, one will be generated.
     * @param int $userId The ID of the user who wrote the review.
     * @param int $productId The ID of the product being reviewed.
     * @param int $rating The rating (1-5).
     * @param string $comment The user's comment.
     */
    public function __construct(int $userId, int $productId, int $rating, string $comment = "") {
        $this->generateId();
        $this->userId = $userId;
        $this->productId = $productId;
        $this->rating = $rating;
        $this->comment = $comment;
        $this->date = date("Y-m-d H:i:s"); // Timestamp for date
    }

    /**
     * Generate a unique ID for the review.
     */
    private function generateId(): void {
        $this->reviewId = uniqid();
    }

    /**
     * Get the Review ID.
     *
     * @return int
     */
    public function getReviewId(): int {
        return $this->reviewId;
    }

    /**
     * Get the User ID.
     *
     * @return int
     */
    public function getUserId(): int {
        return $this->userId;
    }

    /**
     * Get the Product ID.
     *
     * @return int
     */
    public function getProductId(): int {
        return $this->productId;
    }

    /**
     * Get the Rating.
     *
     * @return int
     */
    public function getRating(): int {
        return $this->rating;
    }

    /**
     * Get the Comment.
     *
     * @return string
     */
    public function getComment(): string {
        return $this->comment;
    }

    /**
     * Get the Date of the review.
     *
     * @return string
     */
    public function getDate(): string {
        return $this->date;
    }

    /**
     *  Return the review as an array for easy output.
     *
     * @return array
     */
    public function toArray(): array {
        return [
            'reviewId' => $this->reviewId,
            'userId' => $this->userId,
            'productId' => $this->productId,
            'rating' => $this->rating,
            'comment' => $this->comment,
            'date' => $this->date
        ];
    }
}



/**
 * User Review Review Function (Example Usage and Validation)
 */
function createReview(array $data): ?UserReview {
    // Input validation
    if (!isset($data['userId']) || !is_int($data['userId']) || $data['userId'] <= 0) {
        error_log("Invalid userId: " . $data['userId']); // Log the error (good practice)
        return null;
    }

    if (!isset($data['productId']) || !is_int($data['productId']) || $data['productId'] <= 0) {
        error_log("Invalid productId: " . $data['productId']);
        return null;
    }

    if (!isset($data['rating']) || !is_int($data['rating']) || $data['rating'] < 1 || $data['rating'] > 5) {
        error_log("Invalid rating: " . $data['rating']);
        return null;
    }

    if (empty($data['comment'])) {
        $comment = "";
    } else {
        $comment = trim($data['comment']); // Trim whitespace
    }

    // Create the review object
    return new UserReview($data['userId'], $data['productId'], $data['rating'], $comment);
}

// Example Usage:
$reviewData = [
    'userId' => 123,
    'productId' => 456,
    'rating' => 4,
    'comment' => 'Great product! Highly recommend.'
];

$review = createReview($reviewData);

if ($review) {
    echo "Review created successfully!
";
    print_r($review->toArray());  // Display the review in array format
} else {
    echo "Error creating review.
";
}

//Example with invalid data to demonstrate error handling
$invalidReviewData = [
    'userId' => "abc",  //Invalid userId (string instead of int)
    'productId' => 789,
    'rating' => 6, //Invalid rating (greater than 5)
    'comment' => ""
];
$review = createReview($invalidReviewData);

if ($review) {
    echo "Review created successfully!
";
    print_r($review->toArray());  // Display the review in array format
} else {
    echo "Error creating review (as expected).
";
}
?>
