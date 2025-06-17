

<?php

/**
 * User Review Function
 *
 * This function allows you to create, display, and manage user reviews.
 * It's a basic implementation and can be expanded with more features
 * (e.g., moderation, rating scales, image uploads).
 *
 * @param string $productName The name of the product being reviewed.
 * @param string $userID The ID of the user submitting the review.
 * @param string $reviewText The text of the review.
 * @param int $rating (Optional) The rating given for the product (1-5).
 *
 * @return array An array containing the review ID, or an error message if the review could not be saved.
 */
function createReview(string $productName, string $userID, string $reviewText, int $rating = 1) {
    // --- Input Validation ---
    if (empty($productName)) {
        return ['error' => 'Product name cannot be empty.'];
    }
    if (empty($userID)) {
        return ['error' => 'User ID cannot be empty.'];
    }
    if (empty($reviewText)) {
        return ['error' => 'Review text cannot be empty.'];
    }

    if ($rating < 1 || $rating > 5) {
        return ['error' => 'Rating must be between 1 and 5.'];
    }

    // --- Data Sanitization & Escaping (IMPORTANT!) ---
    $productName = htmlspecialchars($productName);  // Escape HTML tags
    $reviewText = htmlspecialchars($reviewText);   // Escape HTML tags
    $userID = htmlspecialchars($userID);

    // --- Data Storage (Simplified - Replace with Database Logic) ---
    // In a real application, you would save this data to a database.
    // This example uses an in-memory array for demonstration purposes.
    $reviews = isset($_SESSION['reviews']) ? $_SESSION['reviews'] : []; // Load from session or initialize

    $reviewID = count($reviews) + 1; // Simple ID generation
    $review = [
        'reviewID' => $reviewID,
        'productName' => $productName,
        'userID' => $userID,
        'reviewText' => $reviewText,
        'rating' => $rating,
        'timestamp' => time()
    ];

    $reviews[$reviewID] = $review;
    $_SESSION['reviews'] = $reviews; // Save back to session

    return [
        'success' => true,
        'reviewID' => $reviewID
    ];
}

/**
 * Display a Single Review
 *
 * Displays a single review's details.
 *
 * @param array $review The review data.
 */
function displayReview(array $review) {
    echo "<h3>Review for: " . $review['productName'] . "</h3>";
    echo "<p><strong>User:</strong> " . $review['userID'] . "</p>";
    echo "<p><strong>Rating:</strong> " . $review['rating'] . "</p>";
    echo "<p><strong>Date:</strong> " . date("Y-m-d H:i:s", $review['timestamp']) . "</p>";
    echo "<p>" . $review['reviewText'] . "</p>";
}

/**
 * Display All Reviews for a Product
 *
 * Displays all reviews for a specific product.
 *
 * @param string $productName The product name.
 */
function displayProductReviews(string $productName) {
    // --- Retrieve Reviews (Replace with Database Query) ---
    $reviews = isset($_SESSION['reviews']) ? $_SESSION['reviews'] : [];

    $productReviews = [];
    foreach ($reviews as $review) {
        if ($review['productName'] == $productName) {
            $productReviews[] = $review;
        }
    }

    // --- Display Reviews ---
    if (empty($productReviews)) {
        echo "<p>No reviews found for this product.</p>";
    } else {
        echo "<h3>Reviews for " . $productName . "</h3>";
        foreach ($productReviews as $review) {
            displayReview($review);
        }
    }
}

// --- Example Usage (Demonstration) ---
// 1. Create a Review
session_start(); // Start the session

$reviewResult = createReview('Awesome Gadget', 'user123', 'This is a fantastic gadget!');
if (isset($reviewResult['error'])) {
    echo "<p style='color:red;'>Error creating review: " . $reviewResult['error'] . "</p>";
} else {
    echo "<p style='color:green;'>Review created successfully. Review ID: " . $reviewResult['reviewID'] . "</p>";
}

// 2. Display Reviews for the Product
displayProductReviews('Awesome Gadget');
?>
