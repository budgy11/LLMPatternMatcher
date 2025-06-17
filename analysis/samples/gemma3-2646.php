

<?php

/**
 * User Review Class
 *
 * This class allows you to manage and store user reviews.
 */
class UserReview {

    private $reviewId;
    private $userId;
    private $productId; // Assuming you have a product ID
    private $rating;
    private $comment;
    private $date;

    /**
     * Constructor
     *
     * Initializes a new UserReview object.
     *
     * @param int $userId The ID of the user leaving the review.
     * @param int $productId The ID of the product being reviewed.
     * @param int $rating The rating given by the user (e.g., 1-5).
     * @param string $comment The user's comment about the product.
     */
    public function __construct($userId, $productId, $rating, $comment) {
        $this->userId = $userId;
        $this->productId = $productId;
        $this->rating = $rating;
        $this->comment = $comment;
        $this->date = date('Y-m-d H:i:s'); // Get current timestamp
    }

    /**
     * Getters
     *
     * Return the values of the review properties.
     *
     * @return mixed  Return the value of the property.
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
     *  Setters (optional - for updating reviews in the future)
     *  Example:
     *  public function setComment($newComment) {
     *      $this->comment = $newComment;
     *  }
     */
}


/**
 * User Review Review Function (Demonstration)
 */
function reviewProduct($userId, $productId, $rating, $comment) {
    // Create a new UserReview object
    $review = new UserReview($userId, $productId, $rating, $comment);

    // **In a real application, you would now store this review in a database or file.**
    // For this example, we'll just print the review information.

    echo "Review Details:
";
    echo "  User ID: " . $review->getUserId() . "
";
    echo "  Product ID: " . $review->getProductId() . "
";
    echo "  Rating: " . $review->getRating() . "
";
    echo "  Comment: " . $review->getComment() . "
";
    echo "  Date: " . $review->getDate() . "
";
}


// Example Usage:
reviewProduct(123, 456, 5, "Great product! I highly recommend it.");
reviewProduct(456, 789, 3, "It's okay, but could be better.");


// Example of using a database (Conceptual - requires database connection)
/*
$db = new PDO('mysql:host=localhost;dbname=your_database_name', 'username', 'password');

// Example insert statement:
// $stmt = $db->prepare("INSERT INTO reviews (userId, productId, rating, comment) VALUES (?, ?, ?, ?)");
// $stmt->execute([$review->getUserId(), $review->getProductId(), $review->getRating(), $review->getComment()]);
*/

?>
