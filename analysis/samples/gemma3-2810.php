

<?php

/**
 * Class UserReview
 *
 * Provides functionality to add, retrieve, and display user reviews.
 */
class UserReview
{
    private $reviews = [];

    /**
     * Adds a new review.
     *
     * @param string $username The username of the reviewer.
     * @param string $comment The review comment.
     * @return bool True if the review was added successfully, false otherwise (e.g., if username is empty).
     */
    public function addReview(string $username, string $comment): bool
    {
        if (empty($username) || empty($comment)) {
            return false;
        }

        $this->reviews[] = [
            'username' => $username,
            'comment' => $comment,
            'date' => date('Y-m-d H:i:s') // Add a timestamp for tracking
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
     * Retrieves reviews for a specific product (simulated - assumes a product ID).
     * This is a placeholder, and you'd need to adapt this to your specific data structure.
     *
     * @param int $productID The ID of the product.
     * @return array An array of review objects for the specified product.
     */
    public function getReviewsByProduct(int $productID): array
    {
        // Placeholder logic - replace with your actual data retrieval.
        // This example just returns all reviews.
        return $this->getAllReviews(); // Replace with actual filtering logic.
    }

    /**
     * Displays all reviews in a formatted way.
     *
     * @return void  Prints the reviews to the console/browser.
     */
    public function displayReviews(): void
    {
        echo "<pre>"; // Use <pre> tags for better formatting in HTML output.
        foreach ($this->getAllReviews() as $review) {
            echo "Username: " . htmlspecialchars($review['username']) . "
";
            echo "Comment: " . htmlspecialchars($review['comment']) . "
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

// Add some reviews
$reviewSystem->addReview('JohnDoe', 'Great product, highly recommended!');
$reviewSystem->addReview('JaneSmith', 'Could be better, some issues with the UI.');
$reviewSystem->addReview('PeterJones', 'Excellent value for the money.');

// Display all reviews
echo "<h2>All Reviews:</h2>";
$reviewSystem->displayReviews();

// Get reviews for a specific product (simulated)
echo "<br><h2>Reviews for Product 123:</h2>";
$productReviews = $reviewSystem->getReviewsByProduct(123);
if (!empty($productReviews)) {
    foreach ($productReviews as $review) {
        echo "Username: " . htmlspecialchars($review['username']) . "
";
        echo "Comment: " . htmlspecialchars($review['comment']) . "
";
        echo "---
";
    }
} else {
    echo "No reviews found for this product.";
}
?>
