    <input type="text" name="search_term" placeholder="Enter search term">
    <button type="submit">Search</button>
  </form>

</body>
</html>


<?php

// Simulate a database or data source (replace with your actual data source)
$data = [
    ['id' => 1, 'name' => 'Apple', 'description' => 'A red fruit'],
    ['id' => 2, 'name' => 'Banana', 'description' => 'A yellow fruit'],
    ['id' => 3, 'name' => 'Orange', 'description' => 'A citrus fruit'],
    ['id' => 4, 'name' => 'Grape', 'description' => 'Small, sweet fruit'],
];

// Handle the search form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $searchTerm = $_POST["search_term"];

    // Perform the search
    $searchResults = [];
    foreach ($data as $item) {
        // Case-insensitive search
        $found = false;
        if (stripos($item['name'], $searchTerm) !== false ||
            stripos($item['description'], $searchTerm) !== false) {
            $found = true;
        }

        if ($found) {
            $searchResults[] = $item;
        }
    }
}
?>
