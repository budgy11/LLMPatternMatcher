
<!DOCTYPE html>
<html>
<head>
    <title>Purchase Functionality</title>
    <style>
        .order-confirmation {
            border: 1px solid #ccc;
            padding: 10px;
            margin: 20px;
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>

    <h1>Purchase Product</h1>

    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
