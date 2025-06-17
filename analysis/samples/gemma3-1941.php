

<?php

/**
 * User Review Class
 *
 * This class allows you to store and manage user reviews.
 * It handles data validation, saving reviews to a database,
 * and retrieving reviews.
 */
class UserReview
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db; // Database connection object
    }

    /**
     * Creates a new review.
     *
     * @param int $productId The ID of the product the review is for.
     * @param string $user  The user's name.
     * @param string $rating The rating (e.g., 1-5).
     * @param string $comment The review text.
     *
     * @return bool True on success, false on failure.
     */
    public function createReview($productId, $user, $rating, $comment)
    {
        // Input validation (important!)
        if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
            return false; // Invalid rating
        }
        if (empty($user) || empty($comment)) {
            return false; // Empty fields are not allowed.
        }

        // Prepare the SQL statement (using prepared statements for security)
        $sql = "INSERT INTO reviews (product_id, user, rating, comment)
                VALUES (:product_id, :user, :rating, :comment)";

        // Prepare the statement
        $stmt = $this->db->prepare($sql);

        // Bind the parameters
        $stmt->bindParam(':product_id', $productId);
        $stmt->bindParam(':user', $user);
        $stmt->bindParam(':rating', $rating);
        $stmt->bindParam(':comment', $comment);

        // Execute the statement
        return $stmt->execute();
    }

    /**
     * Retrieves all reviews for a specific product.
     *
     * @param int $productId The ID of the product.
     *
     * @return array An array of review objects, or an empty array if no reviews are found.
     */
    public function getReviewsByProduct($productId)
    {
        $sql = "SELECT * FROM reviews WHERE product_id = :product_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':product_id', $productId);
        $stmt->execute();

        $reviews = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $reviews[] = new Review($row['product_id'], $row['user'], $row['rating'], $row['comment']); //  Create Review Objects
        }

        return $reviews;
    }

    /**
     * Retrieves a single review by ID.
     *
     * @param int $reviewId The ID of the review.
     *
     * @return Review|null A Review object, or null if not found.
     */
    public function getReviewById($reviewId)
    {
        $sql = "SELECT * FROM reviews WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $reviewId);
        $stmt->execute();

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return new Review($row['product_id'], $row['user'], $row['rating'], $row['comment']);
        }

        return null;
    }

    /**
     * Deletes a review by ID.
     *
     * @param int $reviewId The ID of the review to delete.
     *
     * @return bool True on success, false on failure (e.g., review not found).
     */
    public function deleteReview($reviewId)
    {
        $sql = "DELETE FROM reviews WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $reviewId);
        return $stmt->execute();
    }

}


/**
 * Review Object (Data Object)
 */
class Review
{
    public $productId;
    public $user;
    public $rating;
    public $comment;

    public function __construct($productId, $user, $rating, $comment)
    {
        $this->productId = $productId;
        $this->user = $user;
        $this->rating = $rating;
        $this->comment = $comment;
    }
}



// Example Usage (Illustrative - Requires a Database Connection)
// Replace with your actual database setup

// Dummy Database Connection (Replace with your real connection)
$db = new PDO('mysql:host=localhost;dbname=your_database', 'your_user', 'your_password');

// Create an instance of the Review class
$reviewManager = new UserReview($db);

// Create a review
$reviewManager->createReview(123, 'John Doe', 4, 'Great product! Highly recommended.');

// Get reviews for product 123
$reviews = $reviewManager->getReviewsByProduct(123);

foreach ($reviews as $review) {
    echo "Product ID: " . $review->productId . "<br>";
    echo "User: " . $review->user . "<br>";
    echo "Rating: " . $review->rating . "<br>";
    echo "Comment: " . $review->comment . "<br>";
    echo "<hr>";
}

// Get a specific review
$review = $reviewManager->getReviewById(1); // Replace 1 with the actual review ID

if ($review) {
    echo "Review details: <br>";
    echo "Product ID: " . $review->productId . "<br>";
    echo "User: " . $review->user . "<br>";
    echo "Rating: " . $review->rating . "<br>";
    echo "Comment: " . $review->comment . "<br>";
} else {
    echo "Review not found.";
}
?>
