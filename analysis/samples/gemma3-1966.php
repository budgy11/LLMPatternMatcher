

// Example of sanitization (replace with your chosen method):
$safeComment = htmlspecialchars($review['comment'], ENT_QUOTES, 'UTF-8');


<?php

/**
 * Class Review
 *
 * This class provides functionality for creating, saving, and retrieving user reviews.
 */
class Review {

    private $db; // Database connection object

    /**
     * Constructor
     *
     * Initializes the Review class with a database connection.
     *
     * @param PDO $db A PDO database connection object.
     */
    public function __construct(PDO $db) {
        $this->db = $db;
    }

    /**
     * Create a new review
     *
     * @param int $product_id The ID of the product being reviewed.
     * @param string $user_name The name of the user submitting the review.
     * @param string $rating The rating given by the user (e.g., 1-5).
     * @param string $comment The user's comment about the product.
     * @return int|false The ID of the newly created review, or false on failure.
     */
    public function createReview(int $product_id, string $user_name, string $rating, string $comment) {
        // Validate inputs (important for security and data integrity)
        if (!$this->validateReviewData($product_id, $user_name, $rating, $comment)) {
            return false;
        }

        // Sanitize inputs (crucial for security - prevents SQL injection)
        $product_id = $this->db->quote($product_id);
        $user_name = $this->db->quote($user_name);
        $rating = $this->db->quote($rating);
        $comment = $this->db->quote($comment);

        // SQL query
        $sql = "INSERT INTO reviews (product_id, user_name, rating, comment)
                VALUES (:product_id, :user_name, :rating, :comment)";

        // Prepare and execute the query
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':user_name', $user_name);
        $stmt->bindParam(':rating', $rating);
        $stmt->bindParam(':comment', $comment);

        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        } else {
            // Handle errors (logging recommended)
            error_log("Error creating review: " . $stmt->errorInfo()[2]);
            return false;
        }
    }


    /**
     * Get a review by ID
     *
     * @param int $review_id The ID of the review to retrieve.
     * @return array|false An associative array containing review details, or false on failure.
     */
    public function getReview(int $review_id) {
        $sql = "SELECT * FROM reviews WHERE id = :review_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':review_id', $review_id);
        $stmt->execute();

        $review = $stmt->fetch(PDO::FETCH_ASSOC);

        return $review ?: false; // Return false if no review found
    }

    /**
     * Get reviews for a product
     *
     * @param int $product_id The ID of the product to retrieve reviews for.
     * @return array An array of associative arrays, each representing a review for the product.
     */
    public function getReviewsByProduct(int $product_id) {
        $sql = "SELECT * FROM reviews WHERE product_id = :product_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

     /**
      * Validate review data
      *
      * @param int $product_id The ID of the product being reviewed.
      * @param string $user_name The name of the user submitting the review.
      * @param string $rating The rating given by the user (e.g., 1-5).
      * @param string $comment The user's comment about the product.
      * @return bool True if the data is valid, false otherwise.
      */
    private function validateReviewData(int $product_id, string $user_name, string $rating, string $comment) {
        // Add more validation rules here, such as:
        // - Check if rating is a valid number (e.g., 1-5)
        // - Check if user_name is not empty
        // - Check if comment length is within acceptable bounds.

        // Example:
        if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
            return false;
        }
        if (empty($user_name)) {
            return false;
        }
        return true;
    }
}

// Example Usage (assuming you have a PDO database connection)
// $db = new PDO("mysql:host=localhost;dbname=your_database_name", "your_username", "your_password");
// $review = new Review($db);

// $review_id = $review->createReview(123, "John Doe", 4, "Great product!");
// if ($review_id) {
//     echo "New review created with ID: " . $review_id . "<br>";
// } else {
//     echo "Failed to create review.";
// }

// $review = $review->getReview($review_id);
// if ($review) {
//     echo "Review details: " . print_r($review, true) . "<br>";
// } else {
//     echo "Review not found.";
// }
?>
