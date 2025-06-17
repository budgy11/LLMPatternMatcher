

<?php

/**
 * User Review Class
 *
 * This class allows users to submit and manage reviews.
 */
class UserReview
{
    /**
     * @var array Array of reviews
     */
    private $reviews = [];

    /**
     * Adds a new review to the reviews array.
     *
     * @param string $reviewer  The name of the reviewer.
     * @param string $comment   The review comment.
     * @return bool True if the review was added successfully, false otherwise (e.g., invalid input).
     */
    public function addReview(string $reviewer, string $comment)
    {
        if (empty($reviewer) || empty($comment)) {
            return false; // Invalid input
        }

        $this->reviews[] = [
            'reviewer' => $reviewer,
            'comment' => $comment,
            'date' => date('Y-m-d H:i:s') // Add timestamp for review date
        ];
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
     * Retrieves reviews by a specific reviewer.
     *
     * @param string $reviewer The name of the reviewer to filter by.
     * @return array An array of review objects for the specified reviewer.
     */
    public function getReviewsByReviewer(string $reviewer)
    {
        $reviews = [];
        foreach ($this->getAllReviews() as $review) {
            if ($review['reviewer'] === $reviewer) {
                $reviews[] = $review;
            }
        }
        return $reviews;
    }

    /**
     * Displays all reviews.
     */
    public function displayReviews()
    {
        echo "<table border='1'>
";
        echo "<tr><th>Reviewer</th><th>Comment</th><th>Date</th></tr>
";

        foreach ($this->getAllReviews() as $review) {
            echo "<tr>
";
            echo "<td>" . htmlspecialchars($review['reviewer']) . "</td>
";
            echo "<td>" . htmlspecialchars($review['comment']) . "</td>
";
            echo "<td>" . htmlspecialchars($review['date']) . "</td>
";
            echo "</tr>
";
        }
        echo "</table>
";
    }
}

// Example Usage:

// Create a new review object
$reviewSystem = new UserReview();

// Add some reviews
$reviewSystem->addReview("John Doe", "Great product!  Highly recommended.");
$reviewSystem->addReview("Jane Smith", "The product is okay, but could be better.");
$reviewSystem->addReview("Peter Jones", "Excellent value for money.");

// Display all reviews
echo "<h2>All Reviews:</h2>";
$reviewSystem->displayReviews();

// Get reviews for a specific reviewer
echo "<br><h2>Reviews by John Doe:</h2>";
$johnReviews = $reviewSystem->getReviewsByReviewer("John Doe");
if (count($johnReviews) > 0) {
    foreach ($johnReviews as $review) {
        echo htmlspecialchars($review['comment']) . "<br>";
    }
} else {
    echo "No reviews found for John Doe.";
}

?>
