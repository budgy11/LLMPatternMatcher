

<?php

/**
 * User Review Function
 *
 * This function allows you to add, retrieve, update, and delete user reviews.
 * It utilizes a simple database interaction for demonstration purposes.
 *  Replace the database connection details with your actual credentials.
 */

class ReviewManager {

    private $db;

    public function __construct($dbConfig) {
        // Assuming $dbConfig is an array containing:
        // ['host'] => 'localhost'
        // ['user'] => 'your_db_user'
        // ['password'] => 'your_db_password'
        // ['dbname'] => 'your_database_name'
        try {
            $this->db = new PDO($dbConfig['driver'] . ':host=' . $dbConfig['host'] . ';dbname=' . $dbConfig['dbname'], $dbConfig['user'], $dbConfig['password']);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    /**
     * Adds a new review.
     *
     * @param string $user_id The ID of the user who wrote the review.
     * @param string $rating The rating (e.g., 1-5).
     * @param string $comment The review text.
     * @return int|false The ID of the newly created review, or false on failure.
     */
    public function addReview(string $user_id, string $rating, string $comment) {
        try {
            $stmt = $this->db->prepare("INSERT INTO reviews (user_id, rating, comment) VALUES (?, ?, ?)");
            $stmt->execute([$user_id, $rating, $comment]);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Retrieves a review by ID.
     *
     * @param int $review_id The ID of the review to retrieve.
     * @return array|false An array containing the review data, or false if not found.
     */
    public function getReview(int $review_id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM reviews WHERE id = ?");
            $stmt->execute([$review_id]);
            $review = $stmt->fetch(PDO::FETCH_ASSOC);
            return $review ? $review : false;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Updates an existing review.
     *
     * @param int $review_id The ID of the review to update.
     * @param string $rating The new rating.
     * @param string $comment The new review text.
     * @return bool True on success, false on failure.
     */
    public function updateReview(int $review_id, string $rating, string $comment) {
        try {
            $stmt = $this->db->prepare("UPDATE reviews SET rating = ?, comment = ? WHERE id = ?");
            $stmt->execute([$rating, $comment, $review_id]);
            return $stmt->rowCount() > 0; // Return true if at least one row was affected
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Deletes a review.
     *
     * @param int $review_id The ID of the review to delete.
     * @return bool True on success, false on failure.
     */
    public function deleteReview(int $review_id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM reviews WHERE id = ?");
            $stmt->execute([$review_id]);
            return $stmt->rowCount() > 0; // Return true if at least one row was affected
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Retrieves all reviews.
     *
     * @return array An array of review objects.
     */
    public function getAllReviews() {
        $reviews = [];
        $stmt = $this->db->query("SELECT * FROM reviews");
        $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $reviews;
    }
}

// Example usage (replace with your actual database configuration)
$dbConfig = [
    'driver' => 'mysql', // or pdo_mysql, etc.
    'host' => 'localhost',
    'user' => 'your_db_user',
    'password' => 'your_db_password',
    'dbname' => 'your_database_name'
];

$reviewManager = new ReviewManager($dbConfig);

// Add a review
$newReviewId = $reviewManager->addReview(1, 5, "This is a fantastic product!");

if ($newReviewId) {
    echo "New review created with ID: " . $newReviewId . "
";
} else {
    echo "Failed to add review.
";
}

// Get a review
$review = $reviewManager->getReview($newReviewId);
if ($review) {
    echo "Review:
";
    print_r($review);
} else {
    echo "Review not found.
";
}

// Update a review
$updateSuccess = $reviewManager->updateReview($newReviewId, 4, "Updated review text");
if ($updateSuccess) {
    echo "Review updated successfully.
";
} else {
    echo "Failed to update review.
";
}

// Delete a review
$deleteSuccess = $reviewManager->deleteReview($newReviewId);
if ($deleteSuccess) {
    echo "Review deleted successfully.
";
} else {
    echo "Failed to delete review.
";
}

// Get all reviews
$allReviews = $reviewManager->getAllReviews();
echo "All Reviews:
";
print_r($allReviews);

?>
