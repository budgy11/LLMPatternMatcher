

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
     * Constructor for the UserReview class.
     *
     * @param int $reviewId (Optional) Unique ID for the review. Defaults to null.
     * @param int $userId The ID of the user leaving the review.
     * @param int $productId The ID of the product being reviewed.
     * @param int $rating The rating (e.g., 1-5).
     * @param string $comment The user's comment.
     * @param string $date The date of the review (e.g., 'YYYY-MM-DD').
     */
    public function __construct(
        $reviewId = null,
        $userId,
        $productId,
        $rating,
        $comment = '',
        $date = date('Y-m-d') // Default to current date
    ) {
        $this->reviewId = $reviewId;
        $this->userId = $userId;
        $this->productId = $productId;
        $this->rating = $rating;
        $this->comment = $comment;
        $this->date = $date;
    }

    /**
     * Getters for the review attributes.
     *
     * @return mixed
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
     *  Setsters (for demonstration purposes - consider validation)
     */
    public function setRating($rating) {
        $this->rating = $rating;
    }

    public function setComment($comment) {
        $this->comment = $comment;
    }

    /**
     *  Returns a string representation of the review
     *  Useful for debugging or output.
     *
     * @return string
     */
    public function __toString() {
        return "Review ID: " . $this->getReviewId() .
               "
User ID: " . $this->getUserId() .
               "
Product ID: " . $this->getProductId() .
               "
Rating: " . $this->getRating() .
               "
Comment: " . $this->getComment() .
               "
Date: " . $this->getDate();
    }
}


/**
 * User Review Review Function (Illustrative)
 */
function reviewProduct($userId, $productId, $rating, $comment) {
    // Basic validation - improve this in a real application.
    if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
        return "Invalid rating. Rating must be a number between 1 and 5.";
    }

    // Create a new review object
    $review = new UserReview($userId, $productId, $rating, $comment);

    // Process the review (e.g., store in a database)
    // For this example, we just return the review as a string.

    return (string)$review; // Convert the review object to a string.

    //  In a real application, you would:
    //  1.  Validate the input data more thoroughly (e.g., using a form).
    //  2.  Connect to your database.
    //  3.  Insert the review data into the database.
    //  4.  Handle any errors that may occur.

}



// Example usage:
$reviewText = reviewProduct(123, 456, 4, "Great product! Highly recommend.");
echo $reviewText . "
";


// Another example
$reviewText = reviewProduct(789, 101, 5, "Excellent value for money.");
echo $reviewText . "
";

//Example with invalid rating
$reviewText = reviewProduct(1011, 1111, 6, "Awesome!");
echo $reviewText . "
"; // This will print the error message.
?>
