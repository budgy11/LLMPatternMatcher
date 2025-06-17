

<?php

/**
 * User Review Function
 *
 * This function allows users to submit and display reviews.
 * It includes basic input validation and database interaction (replace with your actual database setup).
 */

class UserReview
{
    private $dbConnection;

    public function __construct($dbConfig) {
        // Replace with your database connection details
        $this->dbConnection = new PDO(
            $dbConfig['driver'] . ":" . $dbConfig['host'] . ":" . $dbConfig['dbname'],
            $dbConfig['user'],
            $dbConfig['password']
        );
        $this->dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Submit a new review.
     *
     * @param string $username     The username of the reviewer.
     * @param string $reviewText   The review text.
     * @return bool True if successful, false otherwise.
     */
    public function submitReview($username, $reviewText)
    {
        // Input validation (add more as needed)
        if (empty($username) || empty($reviewText)) {
            return false;
        }

        try {
            $stmt = $this->dbConnection->prepare("INSERT INTO reviews (username, review_text, rating) VALUES (:username, :review_text, :rating)");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':review_text', $reviewText);
            $stmt->bindParam(':rating', 5); // Default rating of 5.  Modify as needed.

            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            error_log("Error submitting review: " . $e->getMessage()); // Log errors for debugging
            return false;
        }
    }

    /**
     * Get all reviews.
     *
     * @return array An array of review objects, or an empty array if no reviews exist.
     */
    public function getAllReviews()
    {
        try {
            $stmt = $this->dbConnection->prepare("SELECT id, username, review_text, rating, created_at FROM reviews");
            $stmt->execute();
            $reviews = [];
            while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
                $reviews[] = (object) [
                    'id' => $row->id,
                    'username' => $row->username,
                    'review_text' => $row->review_text,
                    'rating' => $row->rating,
                    'created_at' => $row->created_at
                ];
            }
            return $reviews;
        } catch (PDOException $e) {
            error_log("Error retrieving reviews: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get a single review by ID.
     *
     * @param int $reviewId The ID of the review to retrieve.
     * @return object|null A review object if found, null otherwise.
     */
    public function getReviewById($reviewId)
    {
        try {
            $stmt = $this->dbConnection->prepare("SELECT id, username, review_text, rating, created_at FROM reviews WHERE id = :reviewId");
            $stmt->bindParam(':reviewId', $reviewId);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_OBJ);

            if ($row) {
                return (object) [
                    'id' => $row->id,
                    'username' => $row->username,
                    'review_text' => $row->review_text,
                    'rating' => $row->rating,
                    'created_at' => $row->created_at
                ];
            } else {
                return null;
            }
        } catch (PDOException $e) {
            error_log("Error retrieving review by ID: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Delete a review.
     *
     * @param int $reviewId The ID of the review to delete.
     * @return bool True if successful, false otherwise.
     */
    public function deleteReview($reviewId)
    {
        try {
            $stmt = $this->dbConnection->prepare("DELETE FROM reviews WHERE id = :reviewId");
            $stmt->bindParam(':reviewId', $reviewId);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            error_log("Error deleting review: " . $e->getMessage());
            return false;
        }
    }

}

// Example Usage (Replace with your actual database configuration)
$dbConfig = [
    'driver' => 'mysql',
    'host' => 'localhost',
    'dbname' => 'your_database_name',
    'user' => 'your_username',
    'password' => 'your_password'
];


// Create an instance of the Review class
$review = new UserReview($dbConfig);

// Submit a review
$reviewResult = $review->submitReview("JohnDoe", "This is a fantastic product!");

if ($reviewResult) {
    echo "Review submitted successfully!";
} else {
    echo "Error submitting review.";
}

// Get all reviews
$allReviews = $review->getAllReviews();
echo "<br>All Reviews:<br>";
foreach ($allReviews as $review) {
    echo "ID: " . $review->id . "<br>";
    echo "Username: " . $review->username . "<br>";
    echo "Review: " . $review->review_text . "<br>";
    echo "Rating: " . $review->rating . "<br>";
    echo "<br>";
}

// Get a specific review
$specificReview = $review->getReviewById(1);

if ($specificReview) {
    echo "<br>Specific Review:<br>";
    echo "ID: " . $specificReview->id . "<br>";
    echo "Username: " . $specificReview->username . "<br>";
    echo "Review: " . $specificReview->review_text . "<br>";
    echo "Rating: " . $specificReview->rating . "<br>";
}
else{
    echo "<br>Review not found.";
}

// Delete a review (uncomment to use)
// $deleteResult = $review->deleteReview(1);
// if ($deleteResult) {
//     echo "<br>Review deleted successfully!";
// } else {
//     echo "<br>Error deleting review.";
// }
?>
