<?php
include 'db.php';

// Copy cart products to orders table.
$cart = mysqli_query($conn, 'SELECT * FROM cart');
while ($row = mysqli_fetch_assoc($cart)) {
    mysqli_query($conn, "INSERT INTO orders(product_id, name, price, image) VALUES('$row[product_id]', '$row[name]', '$row[price]', '$row[image]')");
}

mysqli_query($conn, 'DELETE FROM cart');
header('Location: orders.php');
