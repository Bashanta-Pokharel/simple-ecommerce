<?php
include 'db.php';

// Remove all products from cart.
mysqli_query($conn, 'DELETE FROM cart');
header('Location: cart.php');
