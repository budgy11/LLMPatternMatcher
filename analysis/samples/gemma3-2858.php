

   $reviewHTML = displayUserReviews($yourReviewsArray, $maximumNumberOfReviews);
   echo $reviewHTML;
   
   * Replace `$yourReviewsArray` with the array of review objects you have.
   * Adjust the `$maximumNumberOfReviews` if you want to show more or fewer reviews.
3. **Style with CSS:** Add CSS rules to style the `user-reviews`, `review`, `user`, `rating`, and `comment` classes to customize the appearance of the reviews.

Example CSS:



<?php

/**
 * Reviews Class
 *
 * This class allows you to manage user reviews for a specific item (e.g., a product, movie, etc.).
 * It provides functionality to add, display, and potentially moderate reviews.
 */
class Reviews {

    private $reviews = []; // Array to store reviews
    private $db = null; // Database connection (optional)

    /**
     * Constructor
     *
     * Initializes the Reviews class.  Allows for database connection setup.
     *
     * @param PDO $db (Optional) PDO database connection object.
     */
    public function __construct(PDO $db = null) {
        $this->db = $db;
    }

    /**
     * Add a Review
     *
     * Adds a new review to the $reviews array.
     *
     * @param int $itemId The ID of the item the review is for.
     * @param string $user The name or ID of the user submitting the review.
     * @param string $comment The review text.
     * @return bool True on success, false on failure (e.g., invalid data).
     */
    public function addReview(int $itemId, string $user, string $comment) {
        // Basic validation - improve this for production
        if (!is_numeric($itemId)) {
            return false;
        }
        if (empty($user)) {
            return false;
        }
        if (empty($comment)) {
            return false;
        }

        $this->reviews[] = [
            'itemId' => $itemId,
            'user' => $user,
            'comment' => $comment,
            'date' => date('Y-m-d H:i:s') // Add a timestamp
        ];
        return true;
    }

    /**
     * Get All Reviews for an Item
     *
     * Retrieves all reviews associated with a specific item ID.
     *
     * @param int $itemId The ID of the item.
     * @return array An array of review objects, or an empty array if no reviews exist.
     */
    public function getReviewsForItem(int $itemId) {
        //Filtering based on item ID
        $reviews = [];
        foreach($this->reviews as $review) {
            if ($review['itemId'] == $itemId) {
                $reviews[] = $review;
            }
        }
        return $reviews;
    }

    /**
     * Display Reviews
     *
     * Formats and displays the reviews.
     *
     * @return string  A formatted string containing the reviews.
     */
    public function displayReviews() {
        $output = "<h2>Reviews for Item ID: " . implode(",", $this->getReviewsForItem($this->getLatestItemId())) . "</h2>";
        $reviews = $this->getReviewsForItem($this->getLatestItemId());

        if (empty($reviews)) {
            $output .= "<p>No reviews yet.</p>";
        } else {
            $output .= "<ul>";
            foreach ($reviews as $review) {
                $output .= "<li><strong>User:</strong> " . htmlspecialchars($review['user']) . "<br>";
                $output .= "<em>Rating:</em> " . htmlspecialchars($review['comment']) . "<br>";
                $output .= "<em>Date:</em> " . htmlspecialchars($review['date']) . "</li>";
            }
            $output .= "</ul>";
        }
        return $output;
    }

    /**
     *  Helper to get the latest itemId to retrieve reviews from
     */
    private function getLatestItemId(){
        if (empty($this->reviews)) return 0;
        return array_key_last($this->reviews, 'itemId');
    }
}

// --- Example Usage ---
// Create a database connection (replace with your actual credentials)
$db = new PDO('mysql:host=localhost;dbname=my_reviews_db', 'user', 'password');


// Create a Reviews object
$reviews = new Reviews($db);

// Add some reviews
$reviews->addReview(1, "John Doe", "Great product! I love it.");
$reviews->addReview(1, "Jane Smith", "Could be better, but good overall.");
$reviews->addReview(2, "Peter Jones", "Excellent value for money.");

// Get reviews for item 1
$reviewsForItem1 = $reviews->getReviewsForItem(1);

// Display the reviews
echo $reviews->displayReviews();

?>
