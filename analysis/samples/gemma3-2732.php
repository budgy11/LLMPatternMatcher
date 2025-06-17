

<?php

/**
 * User Review Function
 * 
 * This function allows you to process user-submitted reviews.
 * It includes input validation, sanitization, and storage logic.
 * 
 * @param string $name The user's name.
 * @param string $comment The user's review comment.
 * @param string $rating The user's rating (e.g., 1-5).
 * @param string $item_id (Optional) The ID of the item the review is for.
 * @return bool True if the review was successfully saved, false otherwise.
 */
function saveUserReview(string $name, string $comment, string $rating, string $item_id = "") {
    // 1. Input Validation and Sanitization
    $name = sanitizeString($name); // Use a function to sanitize
    $comment = sanitizeString($comment);
    $rating = intval($rating); // Convert rating to an integer

    if (empty($name) || empty($comment) || $rating <= 0 || $rating > 5) {
        error_log("Invalid review data: " . print_r($_POST, true)); // Log the entire POST data for debugging
        return false;
    }

    // 2. Data Preparation
    $review_data = [
        'name' => $name,
        'comment' => $comment,
        'rating' => $rating,
        'item_id' => $item_id,
        'timestamp' => date("Y-m-d H:i:s") // Add a timestamp for record keeping
    ];

    // 3. Storage (Example: Using a simple file)
    $filename = "reviews.txt";
    $review_string = json_encode($review_data) . "
";

    if (file_put_contents($filename, $review_string, FILE_APPEND) !== false) {
        return true;
    } else {
        error_log("Failed to save review to file: " . $filename);
        return false;
    }

    // **Alternative:  Storing in a database (Recommended for production)**
    //  You would typically connect to your database (MySQL, PostgreSQL, etc.)
    //  and use prepared statements to prevent SQL injection vulnerabilities.
    //  Example (Conceptual - Requires database connection setup):
    //
    //  $db = new DatabaseConnection(); // Assuming you have a DatabaseConnection class
    //  $stmt = $db->prepare("INSERT INTO reviews (name, comment, rating, item_id, timestamp) VALUES (?, ?, ?, ?, ?)");
    //  $stmt->bind_param("sss", $name, $comment, $rating, $item_id);
    //  $stmt->execute();
    //  $stmt->close();
    //  return true;
}



/**
 * Sanitize String Function
 * 
 * Simple function to sanitize a string.
 *  (More robust sanitization is often needed in real-world scenarios)
 *
 * @param string $string The string to sanitize.
 * @return string The sanitized string.
 */
function sanitizeString(string $string): string {
    //Remove HTML tags
    $string = strip_tags($string);
    // Remove unwanted characters (example - customize this as needed)
    $string = str_replace([';', ':', '-', '_'], '', $string);
    return trim($string); //Trim leading/trailing whitespace
}

// Example Usage:
// Simulate receiving the review data from a form submission.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $comment = $_POST["comment"];
    $rating = $_POST["rating"];
    $item_id = $_POST["item_id"]; //Optional
    
    if (saveUserReview($name, $comment, $rating, $item_id)) {
        echo "Review submitted successfully!";
    } else {
        echo "Error submitting review.";
    }
}
?>
