        <div id="error-container">
            <?php
            foreach ($errors as $key => $value) {
                echo "<p style='color: red;'>Error: " . $value . "</p>";
            }
            ?>
