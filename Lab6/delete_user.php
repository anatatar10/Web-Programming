<?php
include 'database.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); 
    mysqli_query($conn, "DELETE FROM users WHERE id = $id");
}

// Redirect back to the list
header("Location: index.php");
exit();
?>
