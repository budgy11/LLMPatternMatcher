

<?php

/**
 * Class Review
 *
 * This class provides functionality to create, store, and display user reviews.
 */
class Review
{
    private $db; // Database connection

    /**
     * Constructor
     *
     * Initializes the Review class with a database connection.
     *
     * @param PDO $db  A PDO database connection object.
     */
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Create a new review.
     *
     * @param int $productId The ID of the product the review is for.
     * @param string $reviewerName The name of the reviewer.
     * @param string $comment The review text.
     * @return int|false The ID of the newly created review, or false on failure.
     */
    public function createReview(int $productId, string $reviewerName, string $comment)
    {
        try {
            $sql = "INSERT INTO reviews (product_id, reviewer_name, comment) 
                     VALUES (:product_id, :reviewer_name, :comment)";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':product_id', $productId);
            $stmt->bindParam(':reviewer_name', $reviewerName);
            $stmt->bindParam(':comment', $comment);

            $stmt->execute();

            return $this->db->lastInsertId(); // Returns the ID of the newly inserted review
        } catch (PDOException $e) {
            error_log("Error creating review: " . $e->getMessage()); // Log the error for debugging
            return false;
        }
    }

    /**
     * Get all reviews for a product.
     *
     * @param int $productId The ID of the product.
     * @return array An array of review objects, or an empty array if no reviews are found.
     */
    public function getReviewsByProduct(int $productId)
    {
        try {
            $sql = "SELECT id, reviewer_name, comment, rating FROM reviews WHERE product_id = :product_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':product_id', $productId);
            $stmt->execute();

            $reviews = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $reviews[] = new ReviewObject($row); // Create ReviewObject from the fetched row
            }

            return $reviews;
        } catch (PDOException $e) {
            error_log("Error getting reviews: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get a single review by ID.
     *
     * @param int $reviewId The ID of the review.
     * @return ReviewObject|null A ReviewObject if found, or null if not found.
     */
    public function getReviewById(int $reviewId)
    {
        try {
            $sql = "SELECT id, reviewer_name, comment, rating FROM reviews WHERE id = :review_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':review_id', $reviewId);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                return new ReviewObject($row);
            }

            return null;
        } catch (PDOException $e) {
            error_log("Error getting review by ID: " . $e->getMessage());
            return null;
        }
    }


     /**
     * Update an existing review.
     *
     * @param int $reviewId The ID of the review to update.
     * @param string $reviewerName The new name of the reviewer.
     * @param string $comment The new review text.
     * @param int $rating The new rating for the review.
     * @return bool True on success, false on failure.
     */
    public function updateReview(int $reviewId, string $reviewerName, string $comment, int $rating)
    {
        try {
            $sql = "UPDATE reviews SET reviewer_name = :reviewer_name, comment = :comment, rating = :rating WHERE id = :review_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':review_id', $reviewId);
            $stmt->bindParam(':reviewer_name', $reviewerName);
            $stmt->bindParam(':comment', $comment);
            $stmt->bindParam(':rating', $rating);

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
        try {
            $sql = "DELETE FROM reviews WHERE id = :review_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':review_id', $reviewId);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            error_log("Error deleting review: " . $e->getMessage());
            return false;
        }
    }
}


/**
 * ReviewObject Class
 * Represents a single review.
 */
class ReviewObject
{
    public int $id;
    public string $reviewer_name;
    public string $comment;
    public int $rating;

    /**
     * ReviewObject Constructor
     *
     * Initializes a ReviewObject from a PDO row.
     *
     * @param array $row  The fetched row from the database.
     */
    public function __construct(array $row)
    {
        $this->id = $row['id'];
        $this->reviewer_name = $row['reviewer_name'];
        $this->comment = $row['comment'];
        $this->rating = $row['rating'];
    }
}
?>
