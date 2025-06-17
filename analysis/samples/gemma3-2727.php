

<?php

/**
 * User Review Function - Processes and stores user reviews.
 */
class UserReview
{
    private $db; // Database connection object

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Adds a new user review to the database.
     *
     * @param string $userId The ID of the user submitting the review.
     * @param string $productName The name of the product being reviewed.
     * @param string $rating The rating given by the user (e.g., 1-5).
     * @param string $comment The user's comment about the product.
     * @return bool True on success, false on failure.
     */
    public function addReview($userId, $productName, $rating, $comment)
    {
        // Input validation (very important!)
        if (!$this->isValidInput($userId, $productName, $rating, $comment)) {
            return false;
        }

        // Prepare the SQL statement
        $sql = "INSERT INTO reviews (user_id, product_name, rating, comment) 
                VALUES (:user_id, :product_name, :rating, :comment)";

        // Prepare the statement
        $stmt = $this->db->prepare($sql);

        // Bind the parameters
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':product_name', $productName);
        $stmt->bindParam(':rating', $rating);
        $stmt->bindParam(':comment', $comment);

        // Execute the statement
        if ($stmt->execute()) {
            return true;
        } else {
            // Handle errors (very important!)
            error_log("Error adding review: " . $stmt->error); // Log the error for debugging
            return false;
        }
    }

    /**
     * Retrieves reviews for a specific product.
     *
     * @param string $productName The product to retrieve reviews for.
     * @return array An array of review objects, or an empty array if no reviews are found.
     */
    public function getReviewsByProduct($productName)
    {
        $sql = "SELECT * FROM reviews WHERE product_name = :product_name";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':product_name', $productName);
        $stmt->execute();

        $reviews = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $reviews[] = $row;
        }

        return $reviews;
    }


    /**
     * Input validation function.
     *
     * @param string $userId
     * @param string $productName
     * @param string $rating
     * @param string $comment
     * @return bool
     */
    private function isValidInput($userId, $productName, $rating, $comment)
    {
        // Basic validation - expand as needed
        if (empty($userId) || empty($productName) || empty($rating) || empty($comment)) {
            return false;
        }

        if (!is_numeric($rating)) {
            return false;
        }

        if ($rating < 1 || $rating > 5) {
            return false;
        }

        // You could add more robust validation here, such as:
        // - Checking if the user ID exists in a users table.
        // - Limiting the length of the comment.
        return true;
    }
}


// Example Usage (Assuming you have a database connection $db)
// (This is just a demonstration, replace with your actual database setup)

// Simulate a database connection (replace with your actual connection)
class MockDB {
    public function prepare($sql) {
        // Simulate a prepared statement
        return new MockStatement($sql);
    }

    public function execute() {} // Dummy implementation
}

class MockStatement {
    private $sql;
    public function __construct($sql) {
        $this->sql = $sql;
    }

    public function bindParam($name, $value) {
        // Do nothing for demonstration purposes
    }

    public function execute() {
        // Simulate success
    }
}


$db = new MockDB();
$reviews = new UserReview($db);

// Add a review
$userId = 123;
$productName = 'Awesome Gadget';
$rating = 5;
$comment = 'This gadget is fantastic! Highly recommended.';

if ($reviews->addReview($userId, $productName, $rating, $comment)) {
    echo "Review added successfully!
";
} else {
    echo "Failed to add review.
";
}

// Get reviews for the product
$reviewsForGadget = $reviews->getReviewsByProduct($productName);

echo "Reviews for " . $productName . ":
";
if (empty($reviewsForGadget)) {
    echo "No reviews found.
";
} else {
    foreach ($reviewsForGadget as $review) {
        echo "User ID: " . $review['user_id'] . ", Rating: " . $review['rating'] . ", Comment: " . $review['comment'] . "
";
    }
}
?>
