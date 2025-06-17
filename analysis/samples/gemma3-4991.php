
<!DOCTYPE html>
<html>
<head>
    <title>Search Example</title>
    <style>
        .search-results {
            margin-top: 20px;
            border: 1px solid #ccc;
            padding: 10px;
        }
    </style>
</head>
<body>

    <h1>Search</h1>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
