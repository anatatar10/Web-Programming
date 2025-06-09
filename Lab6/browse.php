<?php
if (!function_exists('getUsers')) {
    require_once 'user_functions.php';
}

$roles = getAvailableRoles();
?>

<h2>Browse Users</h2>

<div id="filterSection">
    <h3>Filter Users</h3>
    <div class="form-group">
        <label for="roleFilter">Filter by Role:</label>
        <select id="roleFilter">
            <option value="">All Roles</option>
            <option value="admin">Admin</option>
            <option value="user">User</option>
        </select>
    </div>
    <button id="applyFilter">Apply Filter</button>
    <p id="currentFilter">Current filter: None</p>
</div>

<div id="usersTableContainer">
    <table id="usersTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Username</th>
                <th>Age</th>
                <th>Role</th>
                <th>Email</th>
                <th>Webpage</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="usersTableBody">
            <tr>
                <td colspan="8">Loading users...</td>
            </tr>
        </tbody>
    </table>
</div>

<script>
    // users are loaded when this tab is first shown
    document.addEventListener('DOMContentLoaded', function() {
        // Load users immediately
        loadUsers();
    });
</script>