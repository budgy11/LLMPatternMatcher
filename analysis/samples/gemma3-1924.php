

<?php

/**
 * User Review Function
 *
 * This function allows you to store and display user reviews for a product or service.
 *
 * @param string $productName The name of the product or service being reviewed.
 * @param string $reviewerName The name of the user who wrote the review.
 * @param string $reviewText The text of the review.
 * @param int $rating  (Optional)  The rating given by the user (e.g., 1-5). Defaults to 0.
 * @param int $userId (Optional) The unique ID of the user.  Used for database integration.
 *
 * @return bool|string Returns true on success, or an error message string on failure.
 */
function createReview(string $productName, string $reviewerName, string $reviewText, int $rating = 0, int $userId = 0)
{
    // Input validation -  Important for security and data integrity
    if (empty($productName) || empty($reviewerName) || empty($reviewText)) {
        return "Error: Product Name, Reviewer Name, and Review Text cannot be empty.";
    }
    if ($rating < 1 || $rating > 5) {
        return "Error: Rating must be between 1 and 5.";
    }
    if ($userId <= 0) {
        return "Error: User ID must be a positive integer.";
    }
    // ---------------------------------------------------------------------
    //  Here, you would typically interact with a database
    //  to store the review data.  This is a simplified example
    //  for demonstration purposes.
    // ---------------------------------------------------------------------

    // Example:  Store the review in a simple array (Replace with database interaction)
    $reviews = [];
    if (isset($_SESSION['reviews'])) {
        $reviews = $_SESSION['reviews']; // Use session storage if available.  Better than global variables.
    }


    $newReview = [
        'product' => $productName,
        'reviewer' => $reviewerName,
        'text' => $reviewText,
        'rating' => $rating,
        'timestamp' => time() // Add a timestamp for tracking.
        'userId' => $userId
    ];
    $reviews[] = $newReview;
    $_SESSION['reviews'] = $reviews; // Store in session
    return true;

    // ---------------------------------------------------------------------
    //  Example Database Interaction (Conceptual - requires database setup)
    //  This is commented out because it needs database credentials and setup.
    //  Uncomment and modify to use a database.
    /*
    try {
        // Connect to the database (replace with your database credentials)
        $db = new PDO('mysql:host=localhost;dbname=your_database_name', 'your_username', 'your_password');

        // Prepare and execute the SQL query
        $stmt = $db->prepare("INSERT INTO reviews (product_name, reviewer_name, review_text, rating, timestamp, userId) 
                               VALUES (:product_name, :reviewer_name, :review_text, :rating, :timestamp, :userId)");
        $stmt->bindParam(':product_name', $productName);
        $stmt->bindParam(':reviewer_name', $reviewerName);
        $stmt->bindParam(':review_text', $reviewText);
        $stmt->bindParam(':rating', $rating);
        $stmt->bindParam(':timestamp', $timestamp);
        $stmt->bindParam(':userId', $userId);

        $stmt->execute();
        return true;
    } catch (PDOException $e) {
        // Handle database errors
        error_log("Database Error: " . $e->getMessage());  //Log the error for debugging
        return "Error: Could not save review to database.  " . $e->getMessage();
    }
    */
}


// Example Usage:
// Create a review
$result = createReview("Awesome Widget", "John Doe", "This widget is fantastic!", 5);

if ($result === true) {
    echo "Review created successfully!";
} else {
    echo $result; // Display the error message
}

// Another example
$result2 = createReview("Another Product", "Jane Smith", "Good but could be better", 3, 123);
if ($result2 === true) {
    echo "<br> Review created successfully!";
} else {
    echo "<br>" . $result2;
}

?>
