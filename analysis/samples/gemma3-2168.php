

<?php

/**
 * Class Review
 *
 * Handles user reviews, allowing for adding, retrieving, and displaying them.
 */
class Review {

    private $db; // Database connection

    /**
     * Constructor
     *
     * @param PDO $db  PDO database connection object
     */
    public function __construct(PDO $db) {
        $this->db = $db;
    }

    /**
     * Add a new review
     *
     * @param int $productId  The ID of the product being reviewed.
     * @param string $reviewerName  The name of the reviewer.
     * @param string $reviewText  The text of the review.
     * @param int $rating  The rating (e.g., 1-5).
     *
     * @return bool True on success, false on failure.
     */
    public function addReview(int $productId, string $reviewerName, string $reviewText, int $rating) {
        try {
            $stmt = $this->db->prepare("INSERT INTO reviews (product_id, reviewer_name, review_text, rating) VALUES (:product_id, :reviewer_name, :review_text, :rating)");
            $stmt->bindParam(':product_id', $productId);
            $stmt->bindParam(':reviewer_name', $reviewerName);
            $stmt->bindParam(':review_text', $reviewText);
            $stmt->bindParam(':rating', $rating);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error adding review: " . $e->getMessage()); // Log the error
            return false;
        }
    }


    /**
     * Get all reviews for a product
     *
     * @param int $productId The ID of the product to retrieve reviews for.
     * @return array An array of review objects.  Returns an empty array if no reviews are found.
     */
    public function getReviewsByProduct(int $productId): array {
        $reviews = [];
        try {
            $stmt = $this->db->prepare("SELECT * FROM reviews WHERE product_id = :product_id");
            $stmt->bindParam(':product_id', $productId);
            $stmt->execute();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $review = (object) $row; // Convert the row to an object
                $reviews[] = $review;
            }
        } catch (PDOException $e) {
            error_log("Error getting reviews: " . $e->getMessage()); // Log the error
        }
        return $reviews;
    }

    /**
     * Get a single review by its ID.
     *
     * @param int $reviewId The ID of the review to retrieve.
     * @return object|null Review object if found, null otherwise.
     */
    public function getReviewById(int $reviewId) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM reviews WHERE id = :review_id");
            $stmt->bindParam(':review_id', $reviewId);
            $stmt->execute();

            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                return (object) $row;
            } else {
                return null;
            }
        } catch (PDOException $e) {
            error_log("Error getting review by ID: " . $e->getMessage());
            return null;
        }
    }

     /**
     * Delete a review.
     *
     * @param int $reviewId The ID of the review to delete.
     * @return bool True on success, false on failure.
     */
    public function deleteReview(int $reviewId) {
        try {
            $stmt = $this->db->prepare("DELETE FROM reviews WHERE id = :review_id");
            $stmt->bindParam(':review_id', $reviewId);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error deleting review: " . $e->getMessage());
            return false;
        }
    }
}

// Example usage (assuming you have a database connection named $db):

// Create a database connection (replace with your actual credentials)
try {
    $db = new PDO("mysql:host=localhost;dbname=your_database_name", "your_username", "your_password");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  // Enable error mode
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}


// Instantiate the Review class
$review = new Review($db);

// Add a review
$review->addReview(123, "John Doe", "Great product!", 5);

// Get reviews for product 123
$reviews = $review->getReviewsByProduct(123);
foreach ($reviews as $review) {
    echo "Reviewer: " . $review->reviewer_name . "<br>";
    echo "Rating: " . $review->rating . "<br>";
    echo "Text: " . $review->review_text . "<br>";
    echo "<hr>";
}

// Get a single review
$singleReview = $review->getReviewById(1);

if ($singleReview) {
    echo "Reviewer: " . $singleReview->reviewer_name . "<br>";
    echo "Rating: " . $singleReview->rating . "<br>";
    echo "Text: " . $singleReview->review_text . "<br>";
}

// Delete a review
$review->deleteReview(1);


?>
