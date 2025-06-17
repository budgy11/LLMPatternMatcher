

<?php

/**
 * User Review Class
 *
 * This class provides a simple way to store and manage user reviews.
 */
class UserReview {

    private $reviewId;
    private $userId;
    private $productId;
    private $rating;
    private $comment;
    private $reviewDate;

    /**
     * Constructor
     *
     * Initializes a new UserReview object.
     *
     * @param int $reviewId (Optional) - The unique ID of the review.  If not provided, auto-generated.
     * @param int $userId   The ID of the user who wrote the review.
     * @param int $productId The ID of the product being reviewed.
     * @param int $rating    The rating given by the user (e.g., 1-5).
     * @param string $comment The user's comment about the product.
     */
    public function __construct(
        $reviewId = null,
        $userId,
        $productId,
        $rating,
        $comment
    ) {
        $this->reviewId = $reviewId;
        $this->userId = $userId;
        $this->productId = $productId;
        $this->rating = $rating;
        $this->comment = $comment;
        $this->reviewDate = date('Y-m-d H:i:s'); // Use current timestamp
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

    public function getReviewDate() {
        return $this->reviewDate;
    }

    /**
     *  Setters (optional - you could make these private and only allow modification
     *  in specific cases, for example, through a moderation system)
     */
    public function setRating($rating) {
        $this->rating = $rating;
    }

    public function setComment($comment) {
        $this->comment = $comment;
    }


    /**
     *  Method to display review details
     */
    public function displayReview() {
        echo "Review ID: " . $this->getReviewId() . "<br>";
        echo "User ID: " . $this->getUserId() . "<br>";
        echo "Product ID: " . $this->getProductId() . "<br>";
        echo "Rating: " . $this->getRating() . "<br>";
        echo "Comment: " . $this->getComment() . "<br>";
        echo "Date: " . $this->getReviewDate() . "<br>";
    }
}


/**
 * User Review Review Function (Demonstration)
 */
function displayReviews($reviews) {
    echo "<h2>User Reviews:</h2>";

    foreach ($reviews as $review) {
        echo "<div class='review'>";
        $review->displayReview();
        echo "</div><hr>";
    }
}



// Example Usage:

// Create some reviews
$review1 = new UserReview(null, 123, 456, 5, "Great product!  Highly recommend.");
$review2 = new UserReview(null, 456, 456, 4, "Good product, but could be better.");
$review3 = new UserReview(null, 789, 456, 3, "Average product.");


// Store the reviews (in a real application, this would likely be a database)
$allReviews = array($review1, $review2, $review3);


// Display the reviews
displayReviews($allReviews);


// **Demonstration of Adding Reviews to a Database (Conceptual)**

/*
//  Assume you have a database connection established
//  and a table named "reviews" with columns:
//  - review_id (INT, PRIMARY KEY, AUTO_INCREMENT)
//  - user_id (INT)
//  - product_id (INT)
//  - rating (INT)
//  - comment (TEXT)
//  - review_date (TIMESTAMP)

//  Here's a conceptual example (adapt to your database library):
/*
$db = new DatabaseConnection(); // Replace with your actual database connection

foreach ($allReviews as $review) {
    $sql = "INSERT INTO reviews (user_id, product_id, rating, comment)
            VALUES (" . $db->escapeString($review->getUserId()) . ", " . $db->escapeString($review->getProductId()) . ", " . $db->escapeString($review->getRating()) . ", " . $db->escapeString($review->getComment()) . ")";

    $result = $db->execute($sql);
    if ($result) {
        // Successfully inserted the review
        $reviewId = $db->lastInsertId(); // Get the ID of the newly inserted review
        $review->setReviewId($reviewId); // Set the review ID
        echo "Review added to database with ID: " . $review->getReviewId() . "<br>";
    } else {
        echo "Error adding review to database.<br>";
    }
}
*/
?>
