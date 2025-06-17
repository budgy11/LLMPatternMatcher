

<?php

/**
 * Class Review
 *
 * This class provides a simple function to store and retrieve user reviews.
 * It uses a file to persist the reviews, allowing for basic persistence.
 *
 * Note: This is a simplified example for demonstration purposes.  For production
 *       applications, consider using a database.
 */
class Review {

    private $filename = 'reviews.txt'; // File to store reviews

    /**
     * Adds a new review to the database.
     *
     * @param string $username The username of the reviewer.
     * @param string $review The review text.
     * @return bool True if the review was added successfully, false otherwise.
     */
    public function addReview(string $username, string $review): bool {
        $data = file_get_contents($this->filename);
        if ($data === false) {
            // Handle file not found or other read errors
            return false;
        }

        $review_entry = "User: $username - $review";
        if (strpos($data, $review_entry) === false) { // Check if review already exists
            $data .= "
$review_entry";
            if (file_put_contents($this->filename, $data) === false) {
                // Handle write errors
                return false;
            }
            return true;
        } else {
            // Review already exists - return false to prevent duplicates
            return false;
        }
    }


    /**
     * Retrieves all reviews.
     *
     * @return array An array of review strings.
     */
    public function getAllReviews(): array {
        $data = file_get_contents($this->filename);
        if ($data === false) {
            return []; // Return empty array on error
        }

        $reviews = explode("
", $data);
        return array_slice($reviews, 0); //Return only the actual reviews, removing empty elements.
    }

    /**
     * Clears all reviews.  Use with caution!
     */
    public function clearReviews(): void {
        if (file_put_contents($this->filename, '') === false) {
            // Handle write errors
            // Log error or throw exception instead of just returning false
            error_log("Failed to clear reviews file.");
        }
    }
}



// Example usage:
$reviewSystem = new Review();

// Add some reviews
$reviewSystem->addReview("JohnDoe", "This product is amazing!");
$reviewSystem->addReview("JaneSmith", "Great value for money.");
$reviewSystem->addReview("JohnDoe", "I had a problem with the delivery."); // Duplicate - should not be added


// Get all reviews
$allReviews = $reviewSystem->getAllReviews();
echo "All Reviews:
";
foreach ($allReviews as $review) {
    echo $review . "
";
}

// Clear reviews (demonstration)
// $reviewSystem->clearReviews();
// echo "
Reviews after clearing:
";
// foreach ($reviewSystem->getAllReviews() as $review) {
//     echo $review . "
";
// }


?>
