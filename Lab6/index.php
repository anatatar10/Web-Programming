<?php

session_start();

require_once 'db_config.php';

$title = "User Management System";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1><?php echo $title; ?></h1>
        
        <div class="tabs">
            <div class="tab active" data-tab="browse">Browse Users</div>
            <div class="tab" data-tab="search">Search Users</div>
            <div class="tab" data-tab="add">Add User</div>
            <div class="tab" data-tab="update">Update User</div>
        </div>
        
        <div id="browse" class="tab-content active">
            <?php include 'browse.php'; ?>
        </div>
        
        <div id="search" class="tab-content">
            <?php include 'search.php'; ?>
        </div>
        
        <div id="add" class="tab-content">
            <?php include 'add_user.php'; ?>
        </div>
        
        <div id="update" class="tab-content">
            <?php include 'update_user.php'; ?>
        </div>
    </div>

    <script src="common.js"></script>
</body>
</html>