<?php
//  logic is handled via AJAX
?>

<h2>Search Users</h2>

<div id="searchSection">
    <div class="form-group">
        <label for="nameSearch">Search by Name:</label>
        <input type="text" id="nameSearch" placeholder="Enter name to search">
    </div>
    <button id="searchButton">Search</button>
    <button id="clearSearch">Clear Results</button>
</div>

<div id="searchResultsContainer" style="margin-top: 20px;">
    <p id="searchMessage" style="display: none;"></p>
    <table id="searchResultsTable" style="display: none;">
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
        <tbody id="searchResultsBody">
            <!-- results -->
        </tbody>
    </table>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('searchButton').addEventListener('click', function() {
        searchUsers();
    });
    
    document.getElementById('clearSearch').addEventListener('click', function() {
        document.getElementById('nameSearch').value = '';
        document.getElementById('searchResultsTable').style.display = 'none';
        document.getElementById('searchMessage').style.display = 'none';
    });
    
    document.getElementById('nameSearch').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            searchUsers();
        }
    });
});
</script>