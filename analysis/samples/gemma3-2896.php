

<?php

/**
 * User Review Class
 *
 * This class allows you to store and manage user reviews for a product or item.
 */
class UserReview
{
    private $reviewId;
    private $userId;
    private $productId;
    private $rating;
    private $comment;
    private $date;

    /**
     * Constructor
     *
     * Initializes a new UserReview object.
     *
     * @param int $reviewId (optional) - Unique ID for the review.  Auto-generated if not provided.
     * @param int $userId  The ID of the user submitting the review.
     * @param int $productId The ID of the product being reviewed.
     * @param int $rating  The rating given by the user (e.g., 1-5).
     * @param string $comment The user's comment about the product.
     */
    public function __construct(int $userId, int $productId, int $rating, string $comment = "")
    {
        $this->reviewId = null; // Will be auto-generated
        $this->userId = $userId;
        $this->productId = $productId;
        $this->rating = $rating;
        $this->comment = $comment;
        $this->date = date("Y-m-d H:i:s"); // Timestamp of when the review was created
    }

    /**
     * Getters and Setters
     */

    public function getReviewId(): int
    {
        return $this->reviewId;
    }

    public function setReviewId(int $reviewId)
    {
        $this->reviewId = $reviewId;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId)
    {
        $this->userId = $userId;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function setProductId(int $productId)
    {
        $this->productId = $productId;
    }

    public function getRating(): int
    {
        return $this->rating;
    }

    public function setRating(int $rating)
    {
        $this->rating = $rating;
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public function setComment(string $comment)
    {
        $this->comment = $comment;
    }

    public function getDate(): string
    {
        return $this->date;
    }
}


/**
 * User Review Function -  Simple Example
 */
function displayUserReview(UserReview $review)
{
    echo "Review ID: " . $review->getReviewId() . "<br>";
    echo "User ID: " . $review->getUserId() . "<br>";
    echo "Product ID: " . $review->getProductId() . "<br>";
    echo "Rating: " . $review->getRating() . " stars<br>";
    echo "Comment: " . $review->getComment() . "<br>";
    echo "Date: " . $review->getDate() . "<br>";
}


// Example Usage:
$review1 = new UserReview(123, 456, 5, "Great product!  I highly recommend it.");
displayUserReview($review1);

$review2 = new UserReview(789, 456, 3, "It was okay, but could be better.");
displayUserReview($review2);

?>
