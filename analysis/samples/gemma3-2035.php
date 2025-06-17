

<?php
// ... (ReviewManager class definition remains the same) ...

//Example usage with PDO:

try {
    $pdo = new PDO("mysql:host=localhost;dbname=your_database_name", "your_username", "your_password");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //Throw exceptions on errors

    $reviewManager = new ReviewManager();
    $reviewManager->setPDO($pdo); //Set the PDO object

    //Add reviews to the database
    $reviewId1 = $reviewManager->addReview("John Doe", "This product is amazing!");
    $reviewId2 = $reviewManager->addReview("Jane Smith", "I'm a bit disappointed with the shipping time.");

    //Display reviews from the database
    echo "<h2>Reviews from Database</h2>";
    $reviewManager->displayAllReviews();
} catch(PDOException $e) {
    echo "Database error: " . $e->getMessage();
}
?>
