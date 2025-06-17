

<?php

/**
 * Class UserReview
 *
 * This class provides functionality for creating, retrieving, updating, and deleting user reviews.
 */
class UserReview
{
    private $db; // Database connection (implementation depends on your database)

    /**
     * Constructor
     *
     * Initializes the UserReview class with a database connection.
     *
     * @param PDO $db A PDO database connection object.
     */
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Create a new review.
     *
     * @param int $productId The ID of the product the review is for.
     * @param string $user  The name or identifier of the user.
     * @param string $rating The rating given (e.g., 1-5).
     * @param string $comment The user's review comment.
     * @return int|null The ID of the newly created review, or null on failure.
     */
    public function createReview(int $productId, string $user, string $rating, string $comment)
    {
        // Validate inputs (add more robust validation here)
        if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
            return null;
        }

        $sql = "INSERT INTO reviews (product_id, user, rating, comment)
                VALUES (:product_id, :user, :rating, :comment)";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':product_id', $productId);
        $stmt->bindParam(':user', $user);
        $stmt->bindParam(':rating', $rating);
        $stmt->bindParam(':comment', $comment);

        try {
            $stmt->execute();
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            // Handle database errors appropriately (logging, error messages)
            error_log("Error creating review: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Get a review by ID.
     *
     * @param int $reviewId The ID of the review to retrieve.
     * @return array|null An associative array containing the review data, or null if not found.
     */
    public function getReview(int $reviewId)
    {
        $sql = "SELECT * FROM reviews WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $reviewId);
        $stmt->execute();

        $review = $stmt->fetch(PDO::FETCH_ASSOC);
        return $review;
    }

    /**
     * Update an existing review.
     *
     * @param int $reviewId The ID of the review to update.
     * @param string $user  The new user name or identifier.
     * @param string $rating The new rating.
     * @param string $comment The new review comment.
     * @return bool True on success, false on failure.
     */
    public function updateReview(int $reviewId, string $user, string $rating, string $comment)
    {
        // Validate inputs (add more robust validation here)
        if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
            return false;
        }

        $sql = "UPDATE reviews
                SET user = :user,
                    rating = :rating,
                    comment = :comment
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $reviewId);
        $stmt->bindParam(':user', $user);
        $stmt->bindParam(':rating', $rating);
        $stmt->bindParam(':comment', $comment);

        try {
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            error_log("Error updating review: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete a review.
     *
     * @param int $reviewId The ID of the review to delete.
     * @return bool True on success, false on failure.
     */
    public function deleteReview(int $reviewId)
    {
        $sql = "DELETE FROM reviews WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $reviewId);

        try {
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            error_log("Error deleting review: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get all reviews for a product.
     *
     * @param int $productId The ID of the product.
     * @return array An array of associative arrays, each representing a review.
     */
    public function getReviewsByProduct(int $productId)
    {
        $sql = "SELECT * FROM reviews WHERE product_id = :product_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':product_id', $productId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}


// Example Usage (Illustrative - replace with your database connection)
// Assuming you have a PDO database connection object named $db

// Create a database connection (replace with your actual connection details)
$db = new PDO("mysql:host=localhost;dbname=your_database", "your_user", "your_password");


// Create a UserReview object
$reviewManager = new UserReview($db);

// Create a new review
$newReviewId = $reviewManager->createReview(1, "John Doe", 5, "Great product!");

if ($newReviewId) {
    echo "New review created with ID: " . $newReviewId . "
";
} else {
    echo "Failed to create review.
";
}

// Get the review by ID
$review = $reviewManager->getReview($newReviewId);
if ($review) {
    echo "Review:
";
    print_r($review);
} else {
    echo "Review not found.
";
}

// Get all reviews for product ID 1
$reviews = $reviewManager->getReviewsByProduct(1);
echo "
Reviews for product 1:
";
print_r($reviews);

//Update review - example
$updateResult = $reviewManager->updateReview($newReviewId, "Jane Smith", 4, "Improved!");
if ($updateResult) {
    echo "
Review updated successfully.
";
} else {
    echo "
Failed to update review.
";
}

//Delete review - example
$deleteResult = $reviewManager->deleteReview($newReviewId);
if ($deleteResult) {
    echo "
Review deleted successfully.
";
} else {
    echo "
Failed to delete review.
";
}
?>
