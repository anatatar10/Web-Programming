<?php

if (!isset($conn)) {
    require_once 'db_config.php';
}


function getUsers($role = "") {
    global $conn;
    
    $sql = "SELECT * FROM users";
    
    if (!empty($role)) {
        $sql .= " WHERE role = '" . mysqli_real_escape_string($conn, $role) . "'";
    }
    
    $result = mysqli_query($conn, $sql);
    $users = [];
    
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            // Remove password from results for security
            unset($row['password']);
            $users[] = $row;
        }
    }
    
    return $users;
}


function getUserById($id) {
    global $conn;
    
    $sql = "SELECT * FROM users WHERE id = " . intval($id);
    $result = mysqli_query($conn, $sql);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        // Remove password from results for security
        unset($user['password']);
        return $user;
    }
    
    return null;
}


function searchUsersByName($name) {
    global $conn;
    
    $search_term = mysqli_real_escape_string($conn, $name);
    $sql = "SELECT * FROM users WHERE name LIKE '%$search_term%'";
    
    $result = mysqli_query($conn, $sql);
    $users = [];
    
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            // Remove password from results for security
            unset($row['password']);
            $users[] = $row;
        }
    }
    
    return $users;
}


function addUser($userData) {
    global $conn;
    
    // Check if username already exists
    $check_sql = "SELECT * FROM users WHERE username = '" . 
    mysqli_real_escape_string($conn, $userData['username']) . "'";
    $check_result = mysqli_query($conn, $check_sql);
    
    if (mysqli_num_rows($check_result) > 0) {
        return "Username already exists";
    }
    
    // Hash the password
    $hashed_password = password_hash($userData['password'], PASSWORD_DEFAULT);
    
    $sql = "INSERT INTO users (name, username, password, age, role, profile, email, webpage) VALUES (
        '" . mysqli_real_escape_string($conn, $userData['name']) . "',
        '" . mysqli_real_escape_string($conn, $userData['username']) . "',
        '" . mysqli_real_escape_string($conn, $hashed_password) . "',
        " . intval($userData['age']) . ",
        '" . mysqli_real_escape_string($conn, $userData['role']) . "',
        '" . mysqli_real_escape_string($conn, $userData['profile']) . "',
        '" . mysqli_real_escape_string($conn, $userData['email']) . "',
        '" . mysqli_real_escape_string($conn, $userData['webpage']) . "'
    )";
    
    if (mysqli_query($conn, $sql)) {
        return true;
    } else {
        return "Error adding user: " . mysqli_error($conn);
    }
}


function updateUser($id, $userData) {
    global $conn;
    
    // Check if username already exists for another user
    $check_sql = "SELECT * FROM users WHERE username = '" . 
    mysqli_real_escape_string($conn, $userData['username']) . "' AND id != " . intval($id);
    $check_result = mysqli_query($conn, $check_sql);
    
    if (mysqli_num_rows($check_result) > 0) {
        return "Username already exists";
    }
    
    // Start building SQL
    $sql = "UPDATE users SET 
            name = '" . mysqli_real_escape_string($conn, $userData['name']) . "',
            username = '" . mysqli_real_escape_string($conn, $userData['username']) . "',
            age = " . intval($userData['age']) . ",
            role = '" . mysqli_real_escape_string($conn, $userData['role']) . "',
            profile = '" . mysqli_real_escape_string($conn, $userData['profile']) . "',
            email = '" . mysqli_real_escape_string($conn, $userData['email']) . "',
            webpage = '" . mysqli_real_escape_string($conn, $userData['webpage']) . "'";
    
    // Update password only if provided
    if (!empty($userData['password'])) {
        $hashed_password = password_hash($userData['password'], PASSWORD_DEFAULT);
        $sql .= ", password = '" . mysqli_real_escape_string($conn, $hashed_password) . "'";
    }
    
    $sql .= " WHERE id = " . intval($id);
    
    if (mysqli_query($conn, $sql)) {
        return true;
    } else {
        return "Error updating user: " . mysqli_error($conn);
    }
}


function deleteUser($id) {
    global $conn;
    
    $sql = "DELETE FROM users WHERE id = " . intval($id);
    
    return mysqli_query($conn, $sql);
}


function getAvailableRoles() {
    return [
        "admin" => "Admin",
        "user" => "User"
    ];
}