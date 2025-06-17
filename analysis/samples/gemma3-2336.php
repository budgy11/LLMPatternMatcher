

<?php

/**
 * User Review Function - Allows users to submit and view reviews.
 */
class UserReview
{
    private $reviews = []; // Array to store reviews
    private $db_connection = null; // Database connection object (optional - for persistence)


    /**
     * Constructor:  Optionally connects to the database.
     *
     * @param PDO $db  (Optional) A PDO database connection object.  If null, reviews will be stored in memory.
     */
    public function __construct(PDO $db = null)
    {
        $this->db_connection = $db;
    }

    /**
     * Adds a new review.
     *
     * @param string $username  The username of the reviewer.
     * @param string $reviewText The review text.
     * @return bool True on success, false on failure (e.g., validation issues).
     */
    public function addReview(string $username, string $reviewText)
    {
        // Basic validation - can be expanded
        if (empty($username) || empty($reviewText)) {
            return false;
        }

        // Optional:  More robust validation (e.g., sanitize inputs, check length, etc.)
        // Example:
        // $username = trim($username);
        // $reviewText = trim($reviewText);

        $review = [
            'username' => $username,
            'review_text' => $reviewText,
            'timestamp' => time() // Add a timestamp for ordering and tracking
        ];

        $this->reviews[] = $review; // Add to the array

        return true;
    }

    /**
     * Retrieves all reviews.
     *
     * @return array An array of review objects.
     */
    public function getAllReviews()
    {
        return $this->reviews;
    }

    /**
     * Retrieves a review by its ID (not applicable for in-memory storage, but good practice).
     *
     * @param int $reviewId  The ID of the review.  (For database implementations, this would be the primary key.)
     * @return array|null  The review object if found, null if not found.
     */
    public function getReviewById(int $reviewId)
    {
        //  This is a placeholder for database implementation.
        //  In a real database, you'd query the database here.
        return null; // Simulate finding a review
    }


    /**
     *  Stores the reviews in a database (if a database connection is established).
     *  This method is kept for completeness and demonstration.
     */
    public function saveToDatabase()
    {
        if ($this->db_connection === null) {
            echo "No database connection established.  Cannot save reviews.
";
            return;
        }

        try {
            //  Example:  This is a basic example. Adapt to your database schema.
            $sql = "INSERT INTO reviews (username, review_text, timestamp) VALUES (:username, :review_text, :timestamp)";
            $stmt = $this->db_connection->prepare($sql);
            $stmt->bindParam(':username', $this->reviews[0]['username']); // Assuming we're saving the first review
            $stmt->bindParam(':review_text', $this->reviews[0]['review_text']);
            $stmt->bindParam(':timestamp', $this->reviews[0]['timestamp']);

            $stmt->execute();
            echo "Review saved to database.
";

        } catch (PDOException $e) {
            echo "Error saving review to database: " . $e->getMessage() . "
";
        }
    }
}



// Example Usage:
// ------------------------------------------------------------------

// 1. In-Memory Storage (no database)
$reviewSystem = new UserReview();

$reviewSystem->addReview('JohnDoe', 'Great product!  Highly recommended.');
$reviewSystem->addReview('JaneSmith', 'Could be better. Needs more features.');

$allReviews = $reviewSystem->getAllReviews();
echo "All Reviews:
";
print_r($allReviews);

// 2.  Using a Database (Requires a database connection)
// Example using a dummy PDO connection (replace with your actual database details)
// This will only work if you have a database set up with a 'reviews' table.
try {
    $db = new PDO('mysql:host=localhost;dbname=your_database', 'your_user', 'your_password');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // For proper error handling

    $reviewSystemDB = new UserReview($db);

    $reviewSystemDB->addReview('User1', 'Excellent!');
    $reviewSystemDB->addReview('User2', 'Okay.');

    $reviewSystemDB->saveToDatabase(); // Save to the database
} catch (PDOException $e) {
    echo "Database connection error: " . $e->getMessage() . "
";
}


?>
