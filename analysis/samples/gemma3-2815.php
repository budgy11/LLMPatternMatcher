

<?php

/**
 * User Review Class
 *
 * Represents a user review and provides methods for creating, displaying,
 * and saving reviews.
 */
class UserReview
{
    private $reviewId;
    private $userId;
    private $rating;
    private $comment;
    private $dateCreated;

    /**
     * Constructor
     *
     * Initializes a new UserReview object.
     *
     * @param int $userId The ID of the user submitting the review.
     * @param int $rating The rating given by the user (e.g., 1-5).
     * @param string $comment The user's comment about the item.
     */
    public function __construct(int $userId, int $rating, string $comment)
    {
        $this->reviewId = null;  // ID assigned when saved
        $this->userId = $userId;
        $this->rating = $rating;
        $this->comment = $comment;
        $this->dateCreated = date('Y-m-d H:i:s'); // Timestamp of creation
    }

    /**
     * Getters and Setters
     */

    public function getReviewId(): ?int
    {
        return $this->reviewId;
    }

    public function setReviewId(?int $reviewId)
    {
        $this->reviewId = $reviewId;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getRating(): int
    {
        return $this->rating;
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public function getDateCreated(): string
    {
        return $this->dateCreated;
    }


    /**
     * Display the review
     *
     * Returns a formatted string representing the review.
     *
     * @return string The review string.
     */
    public function displayReview(): string
    {
        return "Review ID: " . ($this->reviewId ?? 'N/A') . "
"
               . "User ID: " . $this->userId . "
"
               . "Rating: " . $this->rating . "
"
               . "Comment: " . $this->comment . "
"
               . "Date Created: " . $this->dateCreated;
    }

    /**
     *  Simulated Saving to Database (Replace with actual database interaction)
     *
     * This method simulates saving the review to a database.  You'll need to
     * replace this with your database query logic.
     *
     * @return bool True if saving was successful, false otherwise.
     */
    public function saveReview(): bool
    {
        //  Replace this with your database saving logic
        //  Example using a dummy array:
        //  $reviewData = [
        //      'userId' => $this->userId,
        //      'rating' => $this->rating,
        //      'comment' => $this->comment
        //  ];
        //  return saveReviewToDatabase($reviewData);  //  Assume this function exists

        // Placeholder - just return true for demonstration
        return true;
    }

}


/**
 * User Review Function (Example Usage)
 */

// Create a new review
$review = new UserReview(123, 5, "Excellent product!  Highly recommended.");

// Display the review
echo $review->displayReview() . "
";

// Simulate saving the review
if ($review->saveReview()) {
    echo "Review saved successfully.
";
} else {
    echo "Failed to save review.
";
}


// Example of creating multiple reviews:
$review2 = new UserReview(456, 4, "Good value for the money.");
echo $review2->displayReview() . "
";

?>
