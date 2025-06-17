

   $loader = new \Twig\Loader\FilesystemLoader('templates/'); // Adjust path
   $twig = new \Twig\Environment($loader);
   

3. **Use Twig to render the template:**

   
   $template = $twig->render('review_template.twig', $reviewData);
   echo $template;
   

   Where `review_template.twig` is your Twig template file.

**Example `review_template.twig` (using Twig):**



<?php

/**
 * Class Review
 *
 * Handles the creation, retrieval, and display of user reviews.
 */
class Review {

    private $reviews = [];
    private $db = null; // Database connection object

    /**
     * Constructor
     *
     * Initializes the Review object and establishes database connection (optional).
     *
     * @param PDO|null $db (Optional) PDO database connection object.  If null, uses in-memory storage.
     */
    public function __construct(PDO $db = null) {
        $this->db = $db;
        if (!$this->db) {
            // In-memory storage - this is a simple example and not suitable for production
            $this->reviews = [];
        }
    }

    /**
     * Add a new review.
     *
     * @param string $user   The user who wrote the review.
     * @param string $comment The review comment.
     * @return bool True on success, false on failure (e.g., empty comment).
     */
    public function addReview(string $user, string $comment): bool {
        if (empty($comment)) {
            return false;
        }

        $id = count($this->reviews) + 1; // Simple ID generation
        $this->reviews[$id] = [
            'user' => $user,
            'comment' => $comment,
            'date' => date('Y-m-d H:i:s') // Add timestamp for date
        ];
        return true;
    }

    /**
     * Get all reviews.
     *
     * @return array An array of review objects.
     */
    public function getAllReviews(): array {
        return $this->reviews;
    }

    /**
     * Get a review by its ID.
     *
     * @param int $id The ID of the review to retrieve.
     * @return array|null Review object if found, null otherwise.
     */
    public function getReviewById(int $id): ?array {
        if (isset($this->reviews[$id])) {
            return $this->reviews[$id];
        }
        return null;
    }

    /**
     * Delete a review by its ID.
     *
     * @param int $id The ID of the review to delete.
     * @return bool True on success, false on failure.
     */
    public function deleteReview(int $id): bool {
        if (isset($this->reviews[$id])) {
            unset($this->reviews[$id]);
            return true;
        }
        return false;
    }


    // --- Database integration (Optional - for persistence) ---
    /**
     *  Save Reviews to Database (Example)
     *  This is a simplified example.  A production system would likely
     *  use a more robust ORM or query builder.
     */
    public function saveToDatabase() {
        if ($this->db) {
            try {
                $sql = "INSERT INTO reviews (user, comment, date) VALUES (:user, :comment, :date)";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':user', $this->reviews);
                $stmt->bindParam(':comment', $this->reviews);
                $stmt->bindParam(':date', date('Y-m-d H:i:s'));

                $stmt->execute();

            } catch (PDOException $e) {
                // Handle database errors - log them, display a generic message to the user, etc.
                error_log("Database error: " . $e->getMessage());
                echo "Error saving review to database.";
            }
        }
    }
}


// --- Example Usage ---

// Create a Review object - using in-memory storage
$reviewSystem = new Review();

// Add some reviews
$reviewSystem->addReview("Alice", "Great product!");
$reviewSystem->addReview("Bob", "Could be better.");
$reviewSystem->addReview("Charlie", "Excellent value for money.");

// Display all reviews
echo "<h2>All Reviews:</h2>";
$allReviews = $reviewSystem->getAllReviews();
foreach ($allReviews as $review) {
    echo "<strong>User:</strong> " . $review['user'] . "<br>";
    echo "<strong>Comment:</strong> " . $review['comment'] . "<br>";
    echo "<strong>Date:</strong> " . $review['date'] . "<br>";
    echo "<hr>";
}

// Get a specific review
$specificReview = $reviewSystem->getReviewById(2);
if ($specificReview) {
    echo "<h2>Review ID 2:</h2>";
    echo "<strong>User:</strong> " . $specificReview['user'] . "<br>";
    echo "<strong>Comment:</strong> " . $specificReview['comment'] . "<br>";
}

// Delete a review
$reviewSystem->deleteReview(1);

echo "<h2>Reviews after deleting review ID 1:</h2>";
$allReviews = $reviewSystem->getAllReviews();
foreach ($allReviews as $review) {
    echo "<strong>User:</strong> " . $review['user'] . "<br>";
    echo "<strong>Comment:</strong> " . $review['comment'] . "<br>";
    echo "<hr>";
}
?>
