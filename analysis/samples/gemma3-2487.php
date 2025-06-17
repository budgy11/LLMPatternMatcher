

<?php

/**
 * User Review Class - Allows users to submit and view reviews for a product.
 */
class UserReview {

    private $db;  // Database connection (implementation details hidden)

    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Submits a new user review.
     *
     * @param int $product_id The ID of the product the review is for.
     * @param string $user_name The name of the user submitting the review.
     * @param string $review_text The text of the review.
     * @return bool True on success, false on failure.
     */
    public function submitReview(int $product_id, string $user_name, string $review_text) {
        // Basic input validation (improve this in a real application)
        if (!$product_id || !$user_name || !$review_text) {
            return false;
        }

        // Construct the SQL query
        $sql = "INSERT INTO reviews (product_id, user_name, review_text, review_date)
                VALUES (:product_id, :user_name, :review_text, NOW())";

        // Prepare the statement
        $stmt = $this->db->prepare($sql); // Assume $this->db has a prepare method

        // Bind the parameters
        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':user_name', $user_name);
        $stmt->bindParam(':review_text', $review_text);

        // Execute the query
        return $stmt->execute();
    }


    /**
     * Retrieves all reviews for a specific product.
     *
     * @param int $product_id The ID of the product.
     * @return array An array of review objects, or an empty array if no reviews are found.
     */
    public function getReviewsByProduct(int $product_id) {
        $sql = "SELECT * FROM reviews WHERE product_id = :product_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':product_id', $product_id);

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC); // Adapt this based on your database library

        $reviews = [];
        foreach ($results as $row) {
            $reviews[] = (object) [
                'review_id' => $row['review_id'],
                'product_id' => $row['product_id'],
                'user_name' => $row['user_name'],
                'review_text' => $row['review_text'],
                'review_date' => $row['review_date']
            ];
        }
        return $reviews;
    }


    /**
     * Retrieves a single review by ID.
     *
     * @param int $review_id The ID of the review.
     * @return object|null  A review object if found, null otherwise.
     */
    public function getReviewById(int $review_id) {
        $sql = "SELECT * FROM reviews WHERE review_id = :review_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':review_id', $review_id);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return (object) [
                'review_id' => $result['review_id'],
                'product_id' => $result['product_id'],
                'user_name' => $result['user_name'],
                'review_text' => $result['review_text'],
                'review_date' => $result['review_date']
            ];
        }
        return null;
    }
}


// Example usage (assuming you have a database connection established)

// Sample database connection - replace with your actual database connection
$db = new PDO("mysql:host=localhost;dbname=your_database", "your_username", "your_password");



// Create an instance of the UserReview class
$review = new UserReview($db);

// Submit a review
if ($review->submitReview(1, "John Doe", "Great product!  Highly recommended.")) {
    echo "Review submitted successfully!";
} else {
    echo "Failed to submit review.";
}

// Get reviews for product ID 1
$reviews = $review->getReviewsByProduct(1);

if ($reviews) {
    echo "<br><h2>Reviews for Product 1:</h2>";
    foreach ($reviews as $review) {
        echo "<p><strong>User:</strong> " . $review->user_name . "<br>";
        echo "<strong>Review:</strong> " . $review->review_text . "<br>";
        echo "<strong>Date:</strong> " . $review->review_date . "</p>";
    }
} else {
    echo "<p>No reviews found for product 1.</p>";
}

// Get review by ID
$singleReview = $review->getReviewById(1);

if ($singleReview) {
    echo "<br><h2>Single Review:</h2>";
    echo "<p><strong>User:</strong> " . $singleReview->user_name . "<br>";
    echo "<strong>Review:</strong> " . $singleReview->review_text . "<br>";
    echo "<strong>Date:</strong> " . $singleReview->review_date . "</p>";
} else {
    echo "<p>Review not found.</p>";
}
?>
