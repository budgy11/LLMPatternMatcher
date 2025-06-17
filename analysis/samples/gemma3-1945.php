

<?php

/**
 * Class Review
 *
 * This class provides functionality to create, store, and retrieve user reviews.
 */
class Review
{
    private $db; // Database connection

    /**
     * Constructor
     *
     * @param PDO $db  Database connection object.  It's best practice to pass this in.
     */
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Create a new review.
     *
     * @param int $product_id The ID of the product the review is for.
     * @param string $user_name The name of the user submitting the review.
     * @param string $rating The rating given (e.g., 1-5).
     * @param string $comment The user's review comment.
     *
     * @return bool True on success, false on failure.
     */
    public function createReview(int $product_id, string $user_name, string $rating, string $comment)
    {
        try {
            $sql = "INSERT INTO reviews (product_id, user_name, rating, comment)
                    VALUES (:product_id, :user_name, :rating, :comment)";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':product_id', $product_id);
            $stmt->bindParam(':user_name', $user_name);
            $stmt->bindParam(':rating', $rating);
            $stmt->bindParam(':comment', $comment);

            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            error_log("Error creating review: " . $e->getMessage()); // Log for debugging
            return false;
        }
    }

    /**
     * Get all reviews for a product.
     *
     * @param int $product_id The ID of the product.
     *
     * @return array An array of Review objects, or an empty array if none found.
     */
    public function getReviewsByProduct(int $product_id)
    {
        $sql = "SELECT * FROM reviews WHERE product_id = :product_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->execute();

        $reviews = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $reviews[] = new Review($row); // Create a new Review object for each row
        }

        return $reviews;
    }

    /**
     * Review class constructor.  Used internally by the Review class
     * to create individual review objects.
     *
     * @param array $data An associative array containing the review data
     */
    private function __construct(array $data) {
        $this->id = $data['id'];
        $this->product_id = $data['product_id'];
        $this->user_name = $data['user_name'];
        $this->rating = $data['rating'];
        $this->comment = $data['comment'];
    }


    /**
     *  Get a single review by its ID.
     *
     * @param int $id The ID of the review to retrieve.
     * @return Review|null A Review object if found, null otherwise.
     */
    public static function getReviewById(int $id) {
        $sql = "SELECT * FROM reviews WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return new Review($row);
        }

        return null;
    }
}


// Example Usage (assuming you have a PDO database connection $db)

// Create a database connection (replace with your database credentials)
try {
    $db = new PDO("mysql:host=localhost;dbname=your_database_name", "your_username", "your_password");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable exception handling
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Create a Review object and use it
$review = new Review($db);

// Create a review
$review->createReview(123, "John Doe", 5, "Great product, highly recommend!");

// Get reviews for product 123
$reviews = $review->getReviewsByProduct(123);

foreach ($reviews as $review) {
    echo "Product ID: " . $review->product_id . "<br>";
    echo "User: " . $review->user_name . "<br>";
    echo "Rating: " . $review->rating . "<br>";
    echo "Comment: " . $review->comment . "<br><br>";
}

// Get a specific review
$specific_review = Review::getReviewById(1); // Assuming ID 1 exists
if ($specific_review) {
    echo "Specific Review: <br>";
    echo "Product ID: " . $specific_review->product_id . "<br>";
    echo "User: " . $specific_review->user_name . "<br>";
    echo "Rating: " . $specific_review->rating . "<br>";
    echo "Comment: " . $specific_review->comment . "<br>";
} else {
    echo "Review not found.";
}

?>
