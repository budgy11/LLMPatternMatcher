

<?php

/**
 * Class UserReview
 *
 * Handles creating, retrieving, updating, and deleting user reviews.
 */
class UserReview
{
    private $db; // Database connection

    /**
     * Constructor
     *
     * Initializes the database connection.
     *
     * @param PDO $db  Database connection object.
     */
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Creates a new review.
     *
     * @param int $productId  The ID of the product being reviewed.
     * @param string $userRating The user's rating (e.g., "5-star", "1-star").
     * @param string $comment  The user's review comment.
     * @return int|false  The ID of the new review if successful, or false on failure.
     */
    public function createReview(int $productId, string $userRating, string $comment)
    {
        // Validate inputs (important for security)
        if (!isset($productId, $userRating, $comment)) {
            return false;
        }

        if (empty($userRating) || empty($comment)) {
            return false;
        }

        try {
            $sql = "INSERT INTO reviews (product_id, user_rating, comment)
                    VALUES (:product_id, :user_rating, :comment)";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':product_id', $productId);
            $stmt->bindParam(':user_rating', $userRating);
            $stmt->bindParam(':comment', $comment);
            $stmt->execute();

            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error creating review: " . $e->getMessage());  // Log the error
            return false;
        }
    }

    /**
     * Retrieves a review by its ID.
     *
     * @param int $reviewId The ID of the review to retrieve.
     * @return array|null  An associative array representing the review, or null if not found.
     */
    public function getReview(int $reviewId)
    {
        try {
            $sql = "SELECT * FROM reviews WHERE id = :review_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':review_id', $reviewId);
            $stmt->execute();

            $review = $stmt->fetch(PDO::FETCH_ASSOC);

            return $review;
        } catch (PDOException $e) {
            error_log("Error retrieving review: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Updates a review by its ID.
     *
     * @param int $reviewId The ID of the review to update.
     * @param string $userRating The new user rating.
     * @param string $comment The new review comment.
     * @return bool  True if successful, false otherwise.
     */
    public function updateReview(int $reviewId, string $userRating, string $comment)
    {
        try {
            $sql = "UPDATE reviews SET user_rating = :user_rating, comment = :comment WHERE id = :review_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':review_id', $reviewId);
            $stmt->bindParam(':user_rating', $userRating);
            $stmt->bindParam(':comment', $comment);
            $stmt->execute();

            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error updating review: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Deletes a review by its ID.
     *
     * @param int $reviewId The ID of the review to delete.
     * @return bool  True if successful, false otherwise.
     */
    public function deleteReview(int $reviewId)
    {
        try {
            $sql = "DELETE FROM reviews WHERE id = :review_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':review_id', $reviewId);
            $stmt->execute();

            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error deleting review: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Retrieves all reviews for a product.
     *
     * @param int $productId The ID of the product.
     * @return array  An array of associative arrays, each representing a review.
     */
    public function getReviewsForProduct(int $productId)
    {
        try {
            $sql = "SELECT * FROM reviews WHERE product_id = :product_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':product_id', $productId);
            $stmt->execute();

            $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $reviews;
        } catch (PDOException $e) {
            error_log("Error retrieving reviews for product: " . $e->getMessage());
            return [];
        }
    }
}



// Example Usage (Assuming you have a database connection object $db)

// Create a database connection (replace with your actual credentials)
$dsn = 'mysql:host=localhost;dbname=your_database_name';
$username = 'your_username';
$password = 'your_password';

try {
    $db = new PDO($dsn, $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable exception handling

    $review = new UserReview($db);

    // Create a review
    $newReviewId = $review->createReview(1, "5-star", "Great product!");
    if ($newReviewId) {
        echo "New review created with ID: " . $newReviewId . "<br>";
    } else {
        echo "Failed to create review.<br>";
    }

    // Get a review by ID
    $review = $review->getReview(1);
    if ($review) {
        echo "Review: " . $review['user_rating'] . " - " . $review['comment'] . "<br>";
    } else {
        echo "Review not found.<br>";
    }

    // Get reviews for product ID 1
    $reviews = $review->getReviewsForProduct(1);
    echo "Reviews for product 1:<br>";
    foreach ($reviews as $review) {
        echo "  " . $review['user_rating'] . " - " . $review['comment'] . "<br>";
    }

} catch (PDOException $e) {
    echo "Database connection error: " . $e->getMessage();
}

?>
