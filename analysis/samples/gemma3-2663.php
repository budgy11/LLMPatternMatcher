

<?php

/**
 * Class Review
 * 
 * Handles the creation, retrieval, and display of user reviews.
 */
class Review {

    private $db; // Database connection object

    /**
     * Constructor
     *
     * @param PDO $db  A PDO database connection object.
     */
    public function __construct(PDO $db) {
        $this->db = $db;
    }

    /**
     * Creates a new review in the database.
     *
     * @param int $product_id The ID of the product the review is for.
     * @param string $username The username of the reviewer.
     * @param string $rating The rating given (e.g., 1-5).
     * @param string $comment The review text.
     *
     * @return bool True on success, false on failure.
     */
    public function createReview(int $product_id, string $username, string $rating, string $comment): bool {
        try {
            $stmt = $this->db->prepare("INSERT INTO reviews (product_id, username, rating, comment) VALUES (:product_id, :username, :rating, :comment)");
            $stmt->bindParam(':product_id', $product_id);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':rating', $rating);
            $stmt->bindParam(':comment', $comment);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            error_log("Error creating review: " . $e->getMessage()); // Log the error
            return false;
        }
    }

    /**
     * Retrieves all reviews for a given product ID.
     *
     * @param int $product_id The ID of the product.
     * @return array An array of review objects or an empty array if no reviews are found.
     */
    public function getReviewsByProduct(int $product_id): array {
        $reviews = [];
        try {
            $stmt = $this->db->prepare("SELECT id, username, rating, comment, created_at FROM reviews WHERE product_id = :product_id");
            $stmt->bindParam(':product_id', $product_id);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_OBJ);

            if ($result) {
                foreach ($result as $row) {
                    $reviews[] = new ReviewObject($row); // Use the ReviewObject
                }
            }
            return $reviews;
        } catch (PDOException $e) {
            error_log("Error getting reviews: " . $e->getMessage()); // Log the error
            return [];
        }
    }


    /**
     *  Review Object - A simple object to hold review data.
     */
    private function __construct(array $data) {
        $this->id = $data['id'] ?? null;
        $this->product_id = $data['product_id'] ?? null;
        $this->username = $data['username'] ?? null;
        $this->rating = $data['rating'] ?? null;
        $this->comment = $data['comment'] ?? null;
        $this->created_at = $data['created_at'] ?? null;
    }

}

/**
 * Review Object - A simple object to hold review data.
 */
class ReviewObject {
    public int $id;
    public int $product_id;
    public string $username;
    public string $rating;
    public string $comment;
    public string $created_at;
}


// Example Usage (Illustrative - Replace with your actual database setup)
//  ----------------------------------------------------------------------

// Sample Database Setup (Replace with your connection details)
$db_host = 'localhost';
$db_user = 'your_db_user';
$db_password = 'your_db_password';
$db_name = 'your_db_name';

try {
    $db = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $reviews = new Review($db);

    // Create a review
    if ($reviews->createReview(1, "john_doe", 5, "Great product, highly recommended!")) {
        echo "Review created successfully!
";
    } else {
        echo "Error creating review.
";
    }

    // Get reviews for product ID 1
    $reviews = $reviews->getReviewsByProduct(1);

    if ($reviews) {
        echo "Reviews for product 1:
";
        foreach ($reviews as $review) {
            echo "  ID: " . $review->id . "
";
            echo "  Username: " . $review->username . "
";
            echo "  Rating: " . $review->rating . "
";
            echo "  Comment: " . $review->comment . "
";
            echo "  Created At: " . $review->created_at . "
";
            echo "---
";
        }
    } else {
        echo "No reviews found for product 1.
";
    }

} catch (PDOException $e) {
    echo "Database connection error: " . $e->getMessage() . "
";
}
?>
