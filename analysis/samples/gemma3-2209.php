

<?php

/**
 * Reviews Class
 *
 * This class provides functionality for managing user reviews for a product or item.
 */
class Reviews {

    private $db; // Database connection

    /**
     * Constructor
     *
     * Initializes the Reviews class with a database connection.
     *
     * @param PDO $db A PDO database connection object.
     */
    public function __construct(PDO $db) {
        $this->db = $db;
    }

    /**
     * Add a new review
     *
     * Adds a new review to the database.
     *
     * @param int $productId The ID of the product the review is for.
     * @param string $username The username of the reviewer.
     * @param string $rating The rating given (e.g., 1-5).
     * @param string $comment The user's review comment.
     *
     * @return bool True on success, false on failure.
     */
    public function addReview(int $productId, string $username, string $rating, string $comment) {
        try {
            $sql = "INSERT INTO reviews (product_id, user_name, rating, comment)
                    VALUES (:product_id, :user_name, :rating, :comment)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':product_id', $productId);
            $stmt->bindParam(':user_name', $username);
            $stmt->bindParam(':rating', $rating);
            $stmt->bindParam(':comment', $comment);

            $result = $stmt->execute();

            return ($result === 1); // Returns true if at least one row was affected.

        } catch (PDOException $e) {
            error_log("Error adding review: " . $e->getMessage()); // Log the error
            return false;
        }
    }

    /**
     * Get all reviews for a product
     *
     * Retrieves all reviews for a specific product from the database.
     *
     * @param int $productId The ID of the product.
     * @return array An array of review objects, or an empty array if no reviews are found.
     */
    public function getReviewsByProduct(int $productId) {
        try {
            $sql = "SELECT * FROM reviews WHERE product_id = :product_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':product_id', $productId);
            $stmt->execute();

            $reviews = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $reviews[] = $row;
            }

            return $reviews;
        } catch (PDOException $e) {
            error_log("Error getting reviews: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get average rating for a product
     *
     * Calculates the average rating for a product.
     *
     * @param int $productId The ID of the product.
     * @return float|null The average rating, or null if no reviews exist.
     */
    public function getAverageRating(int $productId) {
        try {
            $sql = "SELECT AVG(rating) AS average_rating
                    FROM reviews
                    WHERE product_id = :product_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':product_id', $productId);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row && isset($row['average_rating'])) {
                return (float) $row['average_rating'];
            } else {
                return null;
            }
        } catch (PDOException $e) {
            error_log("Error getting average rating: " . $e->getMessage());
            return null;
        }
    }
}


// Example Usage (Assuming you have a database connection $db):
// Create a database connection (replace with your actual credentials)
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable exception throwing
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}


// Create the Reviews class instance
$reviews = new Reviews($db);

// Add a review
$reviewResult = $reviews->addReview(1, 'JohnDoe', 5, 'Great product! Highly recommended.');

if ($reviewResult) {
    echo "Review added successfully!";
} else {
    echo "Failed to add review.";
}

// Get reviews for product ID 1
$reviewsForProduct = $reviews->getReviewsByProduct(1);
echo "<br>Reviews for Product 1:<br>";
foreach ($reviewsForProduct as $review) {
    echo "User: " . $review['user_name'] . ", Rating: " . $review['rating'] . ", Comment: " . $review['comment'] . "<br>";
}

// Get average rating for product ID 1
$averageRating = $reviews->getAverageRating(1);
echo "<br>Average Rating for Product 1: " . ($averageRating !== null ? $averageRating : "No reviews found.");
?>
