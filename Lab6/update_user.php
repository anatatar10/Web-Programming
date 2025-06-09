<?php
if (!function_exists('getUsers') || !function_exists('getAvailableRoles')) {
    require_once 'user_functions.php';
}

// Get all users for dropdown
$users = getUsers();

// Get available roles
$roles = getAvailableRoles();
?>

<h2>Update User</h2>

<div class="form-group">
    <label for="userIdToUpdate">Select User ID to Update:</label>
    <select id="userIdToUpdate">
        <option value="">Select a User</option>
        <?php foreach ($users as $user): ?>
            <option value="<?php echo $user['id']; ?>">
                <?php echo $user['id'] . " - " . $user['name'] . " (" . $user['username'] . ")"; ?>
            </option>
        <?php endforeach; ?>
    </select>
    <button id="loadUserData">Load User Data</button>
</div>

<form id="updateUserForm">
    <input type="hidden" id="updateId" name="id">
    
    <div class="form-group">
        <label for="updateName">Name:</label>
        <input type="text" id="updateName" name="name" required>
    </div>
    
    <div class="form-group">
        <label for="updateUsername">Username:</label>
        <input type="text" id="updateUsername" name="username" required>
    </div>
    
    <div class="form-group">
        <label for="updatePassword">Password:</label>
        <input type="password" id="updatePassword" name="password" placeholder="Leave blank to keep current password">
    </div>
    
    <div class="form-group">
        <label for="updateAge">Age:</label>
        <input type="number" id="updateAge" name="age" min="18" max="120" required>
    </div>
    
    <div class="form-group">
        <label for="updateRole">Role:</label>
        <select id="updateRole" name="role" required>
            <option value="">Select Role</option>
            <option value="admin">Admin</option>
            <option value="user">User</option>
        </select>
    </div>
    
    <div class="form-group">
        <label for="updateProfile">Profile Description:</label>
        <textarea id="updateProfile" name="profile" rows="3"></textarea>
    </div>
    
    <div class="form-group">
        <label for="updateEmail">Email:</label>
        <input type="email" id="updateEmail" name="email" required>
    </div>
    
    <div class="form-group">
        <label for="updateWebpage">Webpage URL:</label>
        <input type="url" id="updateWebpage" name="webpage" placeholder="https://example.com">
    </div>
    
    <button type="submit">Update User</button>
    <p id="updateMessage"></p>
</form>