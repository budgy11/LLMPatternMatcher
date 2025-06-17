

<?php
// ... (Code from above - Review class and ReviewObject) ...

// Example Usage:
$db = new PDO('mysql:host=localhost;dbname=your_database_name', 'your_username', 'your_password');

$review = new Review($db);

// Create a review
$productId = 1;
$reviewer = 'John Doe';
$comment = 'Great product!';
$newReviewId = $review->createReview($productId, $reviewer, $comment);

if ($newReviewId) {
    echo "New review created with ID: " . $newReviewId . "
";
} else {
    echo "Failed to create review.
";
}

// Get reviews for product 1
$reviews = $review->getReviewsByProduct(1);

foreach ($reviews as $review) {
    echo "Reviewer: " . $review->reviewer_name . ", Comment: " . $review->comment . ", Rating: " . $review->rating . "
";
}
?>
