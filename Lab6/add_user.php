<?php
// Include user functions if not already included
if (!function_exists('getAvailableRoles')) {
    require_once 'user_functions.php';
}

// Get available roles
$roles = getAvailableRoles();
?>

<h2>Add New User</h2>

<form id="addUserForm">
    <div class="form-group">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>
    </div>
    
    <div class="form-group">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
    </div>
    
    <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
    </div>
    
    <div class="form-group">
        <label for="age">Age:</label>
        <input type="number" id="age" name="age" min="18" max="120" required>
    </div>
    
    <div class="form-group">
        <label for="role">Role:</label>
        <select id="role" name="role" required>
            <option value="">Select Role</option>
            <option value="admin">Admin</option>
            <option value="user">User</option>
        </select>
    </div>
    
    <div class="form-group">
        <label for="profile">Profile Description:</label>
        <textarea id="profile" name="profile" rows="3"></textarea>
    </div>
    
    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
    </div>
    
    <div class="form-group">
        <label for="webpage">Webpage URL:</label>
        <input type="url" id="webpage" name="webpage" placeholder="https://example.com">
    </div>
    
    <button type="submit">Add User</button>
    <p id="addMessage"></p>
</form>