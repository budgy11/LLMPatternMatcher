

<?php

/**
 * User Review Function
 *
 * This function allows you to store user reviews with basic validation and formatting.
 *
 * @param string $username The name of the user submitting the review.
 * @param string $review_text The text of the review.
 * @param int $product_id The ID of the product the review is for.
 *
 * @return bool True if the review was successfully saved, false otherwise.
 */
function saveReview(string $username, string $review_text, int $product_id)
{
    // Validation
    if (empty($username)) {
        error_log("Error: Username cannot be empty."); // Log error for admin review
        return false;
    }

    if (empty($review_text)) {
        error_log("Error: Review text cannot be empty."); // Log error for admin review
        return false;
    }

    if (!is_numeric($product_id)) {
        error_log("Error: Product ID must be a number."); // Log error for admin review
        return false;
    }

    // Sanitize the review text (optional, but recommended)
    $review_text = htmlspecialchars($review_text, ENT_QUOTES, 'UTF-8');

    // Store the review (replace with your database interaction code)
    // This is a placeholder - you'll need to connect to your database
    // and insert the review data.

    $db_connection = new PDO("mysql:host=localhost;dbname=your_database_name", "your_username", "your_password");

    try {
        $stmt = $db_connection->prepare("INSERT INTO reviews (username, review_text, product_id) VALUES (:username, :review_text, :product_id)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':review_text', $review_text);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->execute();
        return true;

    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());  // Log for debugging
        return false;
    }

}


// Example Usage (to demonstrate the function)
// Save a review:
$username = "John Doe";
$review_text = "This product is amazing!  I love it!";
$product_id = 123;

if (saveReview($username, $review_text, $product_id)) {
    echo "Review saved successfully!";
} else {
    echo "Failed to save review.";
}


// ---  Example of displaying a review (assuming a simple HTML display) ---
//  This is just for demonstration purposes.  You'll need to adapt this
//  to match your application's styling and database structure.

function displayReview(int $review_id) {
    // Retrieve review details from the database
    $db_connection = new PDO("mysql:host=localhost;dbname=your_database_name", "your_username", "your_password");
    $stmt = $db_connection->prepare("SELECT username, review_text, product_id FROM reviews WHERE id = :id");
    $stmt->bindParam(':id', $review_id);
    $stmt->execute();
    $review = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($review) {
        echo "<div class='review'>";
        echo "<strong>Username:</strong> " . htmlspecialchars($review['username']) . "<br>"; //Sanitize output too!
        echo "<strong>Review:</strong> " . htmlspecialchars($review['review_text']) . "<br>";
        echo "Product ID: " . $review['product_id'];
        echo "</div>";
    } else {
        echo "<p>No review found for this ID.</p>";
    }
}

// Example of displaying a review with ID 1:
displayReview(1);
?>
