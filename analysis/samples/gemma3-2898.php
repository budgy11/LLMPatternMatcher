

<?php

/**
 * Class UserReview
 *
 * Handles the creation, retrieval, and display of user reviews.
 */
class UserReview
{
    private $reviews = []; // Store reviews in an array
    private $db; // Database connection object (optional - for persistent storage)

    /**
     * Constructor
     *
     * Initializes the UserReview object.  You can optionally initialize a database connection here.
     *
     * @param PDO|null $db  An optional PDO database connection object.
     */
    public function __construct(PDO|null $db = null)
    {
        $this->db = $db;
    }

    /**
     * Create a new user review.
     *
     * @param int $productId The ID of the product being reviewed.
     * @param string $userName The name of the user submitting the review.
     * @param string $reviewText The text of the review.
     * @return int|false The ID of the newly created review, or false on failure.
     */
    public function createReview(int $productId, string $userName, string $reviewText)
    {
        $reviewId = $this->db ? $this->db->query("INSERT INTO reviews (product_id, user_name, review_text) VALUES (:product_id, :user_name, :review_text) 
                                      SELECT LAST_INSERT_ID()")->fetchColumn() : (int)count($this->reviews) + 1;

        $review = [
            'productId' => $productId,
            'userName' => $userName,
            'reviewText' => $reviewText,
            'reviewId' => $review, // Added reviewId for easy retrieval
            'dateCreated' => date('Y-m-d H:i:s')
        ];

        $this->reviews[] = $review;
        return $review['reviewId'];
    }

    /**
     * Get a review by its ID.
     *
     * @param int $reviewId The ID of the review to retrieve.
     * @return array|null The review object, or null if not found.
     */
    public function getReview(int $reviewId)
    {
        foreach ($this->reviews as $review) {
            if ($review['reviewId'] == $reviewId) {
                return $review;
            }
        }
        return null;
    }

    /**
     * Get all reviews for a product.
     *
     * @param int $productId The ID of the product.
     * @return array An array of review objects.
     */
    public function getReviewsByProduct(int $productId)
    {
        $reviews = [];
        foreach ($this->reviews as $review) {
            if ($review['productId'] == $productId) {
                $reviews[] = $review;
            }
        }
        return $reviews;
    }


    /**
     * Update a review.  (Implement logic for updating reviews - e.g., allow moderation)
     *
     * @param int $reviewId The ID of the review to update.
     * @param string $newReviewText The new text of the review.
     * @return bool True on successful update, false on failure.
     */
    public function updateReview(int $reviewId, string $newReviewText)
    {
        $review = $this->getReview($reviewId);
        if ($review) {
            $review['reviewText'] = $newReviewText;
            return true;
        }
        return false;
    }


    /**
     * Delete a review. (Implement moderation logic)
     *
     * @param int $reviewId The ID of the review to delete.
     * @return bool True on successful deletion, false on failure.
     */
    public function deleteReview(int $reviewId)
    {
        $review = $this->getReview($reviewId);
        if ($review) {
            foreach ($this->reviews as $key => $r) {
                if ($r['reviewId'] == $reviewId) {
                    unset($this->reviews[$key]);
                    return true;
                }
            }
            return false;
        }
        return false;
    }


    /**
     * Display all reviews. (For demonstration purposes - typically you'd render this in a template)
     *
     * @return void
     */
    public function displayReviews()
    {
        echo "<h2>Reviews:</h2>";
        foreach ($this->reviews as $review) {
            echo "<h3>Review ID: " . $review['reviewId'] . "</h3>";
            echo "<p>Product ID: " . $review['productId'] . "</p>";
            echo "<p>User: " . $review['userName'] . "</p>";
            echo "<p>Review: " . $review['reviewText'] . "</p>";
            echo "<p>Date Created: " . $review['dateCreated'] . "</p>";
            echo "<hr>";
        }
    }
}

// Example Usage (Illustrative - you'd likely integrate this into a web application):
// Assume you have a PDO connection established.
// $db = new PDO('mysql:host=localhost;dbname=your_database', 'username', 'password');

// $reviewSystem = new UserReview($db); // Pass the database connection

// // Create a review
// $reviewId = $reviewSystem->createReview(1, "John Doe", "Great product!");

// // Get a review
// $review = $reviewSystem->getReview($reviewId);
// if ($review) {
//     echo "<h2>Review Details:</h2>";
//     echo "<p>Review Text: " . $review['reviewText'] . "</p>";
// }

// // Display all reviews for product 1
// echo "<h2>Reviews for Product 1:</h2>";
// $reviewSystem->displayReviews();

// // Update a review
// $reviewSystem->updateReview($reviewId, "Excellent product - highly recommended!");

// // Delete a review
// $reviewSystem->deleteReview($reviewId);
?>
