

<?php

/**
 * User Review Function
 *
 * This function handles the creation and display of user reviews.
 * It includes basic validation and error handling.
 */

class UserReview
{
    private $db_connection = null;

    /**
     * Constructor
     *
     * Establishes a database connection (you'll need to replace this 
     * with your actual database connection logic).
     */
    public function __construct($db_config) {
        // Example using PDO - Adapt this to your database system
        try {
            $this->db_connection = new PDO("mysql:host=" . $db_config['host'] . ";dbname=" . $db_config['dbname'] . ";charset=utf8", $db_config['user'], $db_config['password']);
            $this->db_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            // Handle the connection error appropriately.  Logging is a good idea.
            die("Database connection failed: " . $e->getMessage());
        }
    }


    /**
     * Create a New Review
     *
     * Accepts review data via POST.
     *
     * @param array $reviewData Array containing review data (title, rating, comment).
     * @return int|string The ID of the new review if successful, or an error message string if not.
     */
    public function createReview(array $reviewData) {
        // Validation - Very important to prevent bad data
        if (empty($reviewData['title'])) {
            return "Title cannot be empty.";
        }
        if (empty($reviewData['rating'])) {
            return "Rating cannot be empty.";
        }
        if (!is_numeric($reviewData['rating'])) {
            return "Rating must be a number.";
        }
        if (empty($reviewData['comment'])) {
            return "Comment cannot be empty.";
        }

        // Sanitize data (important for security) - adapt to your needs
        $title = htmlspecialchars($reviewData['title']);
        $rating = (int)$reviewData['rating']; // Convert rating to an integer
        $comment = htmlspecialchars($reviewData['comment']);


        // Prepare SQL statement
        $sql = "INSERT INTO reviews (title, rating, comment) VALUES (:title, :rating, :comment)";

        // Prepare the statement
        $stmt = $this->db_connection->prepare($sql);

        // Set the parameters
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':rating', $rating);
        $stmt->bindParam(':comment', $comment);

        // Execute the statement
        try {
            $stmt->execute();
            return $this->db_connection->lastInsertId(); // Return the ID of the newly created review
        } catch (PDOException $e) {
            return "Error creating review: " . $e->getMessage();
        }
    }

    /**
     * Get Reviews
     *
     * Retrieves all reviews, or reviews based on criteria.
     *
     * @param array $criteria  (Optional) Array of criteria for filtering reviews.
     * @return array An array of review objects, or an empty array if no reviews are found.
     */
    public function getReviews(array $criteria = []) {
        $sql = "SELECT * FROM reviews";

        if (!empty($criteria)) {
            $whereClauses = [];
            foreach ($criteria as $column => $value) {
                $whereClauses[] = "$column = :{$column}";
            }
            $whereClause = implode(" AND ", $whereClauses);
            $sql .= " WHERE " . $whereClause;
        }


        $stmt = $this->db_connection->prepare($sql);
        if (!empty($criteria)) {
            $stmt->execute($criteria);
        } else {
            $stmt->execute();
        }

        $reviews = [];
        $stmt->fetch(PDO::FETCH_CLASS, 'Review'); // Fetch as Review objects (you'll need to define Review class)
        while ($review = $stmt->fetch()) {
            $reviews[] = $review;
        }

        return $reviews;
    }



    /**
     * Delete a Review
     *
     * Deletes a review based on its ID.
     *
     * @param int $reviewId The ID of the review to delete.
     * @return bool True if the review was deleted successfully, false otherwise.
     */
    public function deleteReview(int $reviewId) {
        try {
            $sql = "DELETE FROM reviews WHERE id = :id";
            $stmt = $this->db_connection->prepare($sql);
            $stmt->bindParam(':id', $reviewId);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}


// Example Usage (replace with your database details)
$db_config = [
    'host' => 'localhost',
    'dbname' => 'your_database_name',
    'user' => 'your_username',
    'password' => 'your_password'
];

$review_function = new UserReview($db_config);


// Create a review
$new_review_id = $review_function->createReview(['title' => 'Great Product!', 'rating' => 5, 'comment' => 'Highly recommended.']);
if ($new_review_id) {
    echo "New review created with ID: " . $new_review_id . "<br>";
} else {
    echo "Failed to create review.<br>";
    echo $review_function->createReview(['title' => 'Great Product!', 'rating' => 5, 'comment' => 'Highly recommended.']) . "<br>";
}

// Get all reviews
$all_reviews = $review_function->getReviews();

echo "<h3>All Reviews:</h3>";
foreach ($all_reviews as $review) {
    echo "<b>Title:</b> " . $review->title . "<br>";
    echo "<b>Rating:</b> " . $review->rating . "<br>";
    echo "<b>Comment:</b> " . $review->comment . "<br><br>";
}

// Example: Get reviews with rating greater than 4
$high_rated_reviews = $review_function->getReviews(['rating' => '>=' , 'rating' => 4]);

echo "<h3>Reviews with rating >= 4:</h3>";
foreach ($high_rated_reviews as $review) {
    echo "<b>Title:</b> " . $review->title . "<br>";
    echo "<b>Rating:</b> " . $review->rating . "<br>";
    echo "<b>Comment:</b> " . $review->comment . "<br><br>";
}
?>
