

// Example Usage
$product_id = "123";
$username = "JohnDoe";
$rating = 4;
$comment = "This is a great product!";

$result = submit_review($product_id, $username, $rating, $comment);

if ($result['success']) {
  echo "<p>Review submitted successfully!</p>";
} else {
  echo "<p>Error: " . $result['message'] . "</p>";
}


<?php

/**
 * User Review Function - Allows users to submit and display reviews.
 */

class UserReview
{
    private $reviews = [];

    /**
     * Submits a new user review.
     *
     * @param string $username The username of the reviewer.
     * @param string $reviewText The text of the review.
     * @return bool True on successful submission, false otherwise (e.g., if username is empty).
     */
    public function submitReview(string $username, string $reviewText): bool
    {
        if (empty($username) || empty($reviewText)) {
            return false;
        }

        $this->reviews[] = [
            'username' => $username,
            'review'   => $reviewText,
            'date'     => date('Y-m-d H:i:s') // Timestamp of the review
        ];

        return true;
    }

    /**
     * Retrieves all submitted reviews.
     *
     * @return array An array of review objects.
     */
    public function getAllReviews(): array
    {
        return $this->reviews;
    }

    /**
     * Retrieves a single review by its index in the array.  Useful if you're managing reviews sequentially.
     *
     * @param int $index The index of the review to retrieve.
     * @return array|null The review object at the given index, or null if the index is invalid.
     */
    public function getReviewByIndex(int $index): ?array
    {
        if ($index >= 0 && $index < count($this->reviews)) {
            return $this->reviews[$index];
        } else {
            return null;
        }
    }

    /**
     * Displays all reviews in a formatted way.
     *
     * @return void Prints the reviews to the console (for demonstration).
     */
    public function displayAllReviews(): void
    {
        echo "<pre>"; // Use <pre> tag for better formatting in the console
        foreach ($this->getAllReviews() as $review) {
            echo "Username: " . htmlspecialchars($review['username']) . "
";
            echo "Review: " . htmlspecialchars($review['review']) . "
";
            echo "Date: " . $review['date'] . "
";
            echo "---
";
        }
        echo "</pre>";
    }
}


// Example Usage:
$reviewSystem = new UserReview();

// Submit some reviews
$reviewSystem->submitReview('JohnDoe', 'This product is amazing!');
$reviewSystem->submitReview('JaneSmith', 'Could be better, needs some improvements.');
$reviewSystem->submitReview('PeterJones', 'Fantastic value for money!');

// Display all reviews
echo "<h2>All Reviews:</h2>";
$reviewSystem->displayAllReviews();

// Retrieve a specific review
$review = $reviewSystem->getReviewByIndex(1);
if ($review) {
    echo "<h2>Review at Index 1:</h2>";
    echo "Username: " . htmlspecialchars($review['username']) . "
";
    echo "Review: " . htmlspecialchars($review['review']) . "
";
} else {
    echo "<p>Review not found.</p>";
}


?>
