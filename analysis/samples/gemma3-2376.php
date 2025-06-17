

<?php

/**
 * Class UserReview
 *
 * Handles the creation, retrieval, and display of user reviews.
 */
class UserReview
{
    private $reviews = []; // Array to store reviews
    private $db; // Database connection (using PDO for example)

    /**
     * Constructor: Initializes the UserReview class.
     *
     * @param PDO $db PDO database connection.  Defaults to a dummy connection.
     */
    public function __construct(PDO $db = null)
    {
        $this->db = $db ?? new PDO('mysql:host=localhost;dbname=your_database_name', 'your_username', 'your_password'); // Replace with your database credentials.  Use a dummy PDO for testing.
        $this->reviews = [];
    }


    /**
     * Add a new review.
     *
     * @param string $user_id The ID of the user writing the review.
     * @param string $product_id The ID of the product being reviewed.
     * @param string $rating The rating (e.g., 1-5).
     * @param string $comment The review comment.
     * @return bool True if the review was added successfully, false otherwise.
     */
    public function addReview(string $user_id, string $product_id, string $rating, string $comment): bool
    {
        $rating = (int)$rating; // Ensure rating is an integer
        if ($rating < 1 || $rating > 5) {
            return false; // Invalid rating
        }

        try {
            $stmt = $this->db->prepare("INSERT INTO reviews (user_id, product_id, rating, comment) VALUES (:user_id, :product_id, :rating, :comment)");
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':product_id', $product_id);
            $stmt->bindParam(':rating', $rating);
            $stmt->bindParam(':comment', $comment);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            // Handle database errors (log them, etc.)
            error_log("Error adding review: " . $e->getMessage());
            return false;
        }
    }



    /**
     * Retrieve all reviews for a specific product.
     *
     * @param string $product_id The ID of the product.
     * @return array An array of review objects, or an empty array if no reviews are found.
     */
    public function getReviewsByProduct(string $product_id): array
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM reviews WHERE product_id = :product_id");
            $stmt->bindParam(':product_id', $product_id);
            $stmt->execute();
            $reviews = $stmt->fetchAll(PDO::FETCH_OBJ); // Fetch results as objects for easier access.

            return $reviews;
        } catch (PDOException $e) {
            // Handle database errors.
            error_log("Error retrieving reviews: " . $e->getMessage());
            return [];
        }
    }



    /**
     * Retrieve a single review by its ID.
     *
     * @param int $review_id The ID of the review to retrieve.
     * @return object|null A review object if found, null otherwise.
     */
    public function getReviewById(int $review_id): ?object
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM reviews WHERE id = :review_id");
            $stmt->bindParam(':review_id', $review_id);
            $stmt->execute();
            $review = $stmt->fetch(PDO::FETCH_OBJ);
            return $review;
        } catch (PDOException $e) {
            // Handle database errors.
            error_log("Error retrieving review: " . $e->getMessage());
            return null;
        }
    }


    /**
     * Update an existing review.  Requires the review_id.
     *
     * @param int $review_id The ID of the review to update.
     * @param string $new_rating The new rating.
     * @param string $new_comment The new review comment.
     * @return bool True if the review was updated successfully, false otherwise.
     */
    public function updateReview(int $review_id, string $new_rating, string $new_comment): bool
    {
        try {
            $rating = (int)$new_rating;
            if ($rating < 1 || $rating > 5) {
                return false; // Invalid rating
            }

            $sql = "UPDATE reviews SET rating = :rating, comment = :comment WHERE id = :review_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':review_id', $review_id);
            $stmt->bindParam(':rating', $rating);
            $stmt->bindParam(':comment', $new_comment);
            $stmt->execute();
            return true;

        } catch (PDOException $e) {
            // Handle database errors.
            error_log("Error updating review: " . $e->getMessage());
            return false;
        }
    }


    /**
     * Delete a review by its ID.
     *
     * @param int $review_id The ID of the review to delete.
     * @return bool True if the review was deleted successfully, false otherwise.
     */
    public function deleteReview(int $review_id): bool
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM reviews WHERE id = :review_id");
            $stmt->bindParam(':review_id', $review_id);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            // Handle database errors.
            error_log("Error deleting review: " . $e->getMessage());
            return false;
        }
    }



    //  Methods to display the reviews (for demonstration purposes)
    public function displayReviewsByProduct(string $product_id)
    {
        $reviews = $this->getReviewsByProduct($product_id);
        if ($reviews) {
            echo "<h2>Reviews for Product ID: " . $product_id . "</h2>";
            foreach ($reviews as $review) {
                echo "<p><strong>User:</strong> " . $review->user_id . "<br>";
                echo "<strong>Rating:</strong> " . $review->rating . "<br>";
                echo "<strong>Comment:</strong> " . $review->comment . "</p>";
            }
        } else {
            echo "<p>No reviews found for this product.</p>";
        }
    }
}



// Example Usage:

// Create a dummy database connection (replace with your actual database credentials)
$db = new PDO('mysql:host=localhost;dbname=your_database_name', 'your_username', 'your_password');

$reviewSystem = new UserReview($db);


// Add a review
if ($reviewSystem->addReview(123, 456, 4, "Great product!")) {
    echo "<p>Review added successfully.</p>";
} else {
    echo "<p>Failed to add review.</p>";
}


// Retrieve reviews for a product
$reviewSystem->displayReviewsByProduct(456); // Displays reviews for product ID 456


//Update a review
if ($reviewSystem->updateReview(1, 5, "Excellent! I highly recommend this.") ) {
    echo "<p>Review updated successfully.</p>";
} else {
    echo "<p>Failed to update review.</p>";
}


//Delete a review
if($reviewSystem->deleteReview(1)){
    echo "<p>Review deleted successfully</p>";
} else {
    echo "<p>Failed to delete review</p>";
}
?>
