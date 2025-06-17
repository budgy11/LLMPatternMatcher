   
3. **Pass Data:**  In your view, create a variable containing the necessary data (product ID, product name, and the reviews array) that you'll pass to the `displayUserReviews` function.
4. **Call the Function:** Call the function, and then output the returned HTML.

**Example (with a simple HTML template):**



<?php

/**
 * User Review Function
 *
 * This function allows you to collect and display user reviews for a given item or product.
 * It includes basic input validation and handles display of reviews.
 */
class UserReviewFunction
{
    private $reviews = [];

    /**
     * Adds a new review to the collection.
     *
     * @param string $name The reviewer's name.
     * @param string $comment The review comment.
     * @return bool True if the review was added successfully, false otherwise (e.g., if input is invalid).
     */
    public function addReview(string $name, string $comment): bool
    {
        // Input Validation (Important for security and data integrity)
        if (empty($name)) {
            error_log("Invalid review: Missing reviewer name.");
            return false;
        }
        if (empty($comment)) {
            error_log("Invalid review: Missing comment.");
            return false;
        }

        $this->reviews[] = [
            'name' => $name,
            'comment' => $comment,
            'date' => date("Y-m-d H:i:s") // Add a timestamp for record keeping
        ];

        return true;
    }

    /**
     * Retrieves all reviews.
     *
     * @return array An array of review objects.
     */
    public function getAllReviews(): array
    {
        return $this->reviews;
    }

    /**
     * Displays all reviews in a formatted way.
     *
     * @return void  Displays the reviews to the browser.
     */
    public function displayReviews(): void
    {
        echo "<h2>User Reviews</h2>";
        if (empty($this->getAllReviews())) {
            echo "<p>No reviews yet.</p>";
        } else {
            echo "<ul>";
            foreach ($this->getAllReviews() as $review) {
                echo "<li><strong>" . htmlspecialchars($review['name']) . ":</strong> " . htmlspecialchars($review['comment']) . "</li>";
            }
            echo "</ul>";
        }
    }
}

// Example Usage:
// Create an instance of the UserReviewFunction class
$reviewFunction = new UserReviewFunction();

// Add some reviews
$reviewFunction->addReview("Alice", "Great product!  I highly recommend it.");
$reviewFunction->addReview("Bob", "Could be better, but overall decent.");
$reviewFunction->addReview("Charlie", "Excellent value for money!");


// Display the reviews
$reviewFunction->displayReviews();

// Example of handling an invalid review (missing comment)
$reviewFunction->addReview("David", ""); // This will log an error
$reviewFunction->displayReviews();

?>
