// Current filter state
let currentFilter = "";

// Initialize the application when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing...');
    initializeTabs();
    loadUsers(); // Load users immediately on page load
    getCurrentFilter();
    setupEventListeners();
});

// Tab functionality
function initializeTabs() {
    document.querySelectorAll('.tab').forEach(tab => {
        tab.addEventListener('click', function() {
            document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
            
            this.classList.add('active');
            const tabId = this.dataset.tab;
            document.getElementById(tabId).classList.add('active');
            
            if (tabId === 'browse') {
                loadUsers();
            } else if (tabId === 'update') {
                refreshUpdateDropdown();
            }
        });
    });
}

// Setup event listeners
function setupEventListeners() {
    // Browse tab event listeners
    const applyFilterBtn = document.getElementById('applyFilter');
    if (applyFilterBtn) {
        applyFilterBtn.addEventListener('click', applyRoleFilter);
    }
    
    // Search tab event listeners
    const searchButton = document.getElementById('searchButton');
    if (searchButton) {
        searchButton.addEventListener('click', searchUsers);
    }
    
    // Add user form
    const addUserForm = document.getElementById('addUserForm');
    if (addUserForm) {
        addUserForm.addEventListener('submit', addUser);
    }
    
    // Update user event listeners
    const loadUserDataBtn = document.getElementById('loadUserData');
    if (loadUserDataBtn) {
        loadUserDataBtn.addEventListener('click', loadUserDataForUpdate);
    }
    
    const updateUserForm = document.getElementById('updateUserForm');
    if (updateUserForm) {
        updateUserForm.addEventListener('submit', updateUser);
    }
}

// Load users with role filter
async function loadUsers() {
    console.log('Loading users...');
    try {
        const response = await fetch('ajax_handlers.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'action=getUsers&role=' + encodeURIComponent(currentFilter)
        });
        
        console.log('Response status:', response.status);
        const data = await response.json();
        console.log('Response data:', data);
        
        if (data.success) {
            displayUsers(data.users, 'usersTableBody');
            updateCurrentFilterDisplay();
        } else {
            console.error('Error loading users:', data.message);
            // Show error in table
            const tableBody = document.getElementById('usersTableBody');
            if (tableBody) {
                tableBody.innerHTML = '<tr><td colspan="8">Error loading users: ' + data.message + '</td></tr>';
            }
        }
    } catch (error) {
        console.error('Error loading users:', error);
        const tableBody = document.getElementById('usersTableBody');
        if (tableBody) {
            tableBody.innerHTML = '<tr><td colspan="8">Error loading users. Check console for details.</td></tr>';
        }
    }
}

// Get current filter from server
async function getCurrentFilter() {
    try {
        const response = await fetch('ajax_handlers.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'action=getCurrentFilter'
        });
        
        const data = await response.json();
        
        if (data.success) {
            currentFilter = data.currentFilter;
            updateCurrentFilterDisplay();
            
            // Set the select box value
            const roleFilter = document.getElementById('roleFilter');
            if (roleFilter) {
                roleFilter.value = currentFilter;
            }
        }
    } catch (error) {
        console.error('Error getting current filter:', error);
    }
}

// Apply role filter
async function applyRoleFilter() {
    const roleFilter = document.getElementById('roleFilter');
    if (roleFilter) {
        currentFilter = roleFilter.value;
        await loadUsers();
    }
}

// Update current filter display
function updateCurrentFilterDisplay() {
    const currentFilterDisplay = document.getElementById('currentFilter');
    if (currentFilterDisplay) {
        if (currentFilter === "") {
            currentFilterDisplay.textContent = "Current filter: None";
        } else {
            currentFilterDisplay.textContent = `Current filter: ${currentFilter}`;
        }
    }
}

// Search users by name
async function searchUsers() {
    const nameSearch = document.getElementById('nameSearch');
    const searchTerm = nameSearch.value.trim();
    const searchMessage = document.getElementById('searchMessage');
    const searchResultsTable = document.getElementById('searchResultsTable');
    
    // Clear previous messages
    if (searchMessage) {
        searchMessage.style.display = 'none';
    }
    
    if (searchTerm === "") {
        if (searchMessage) {
            searchMessage.textContent = "Please enter a name to search";
            searchMessage.style.display = 'block';
            searchMessage.className = 'error';
        }
        if (searchResultsTable) {
            searchResultsTable.style.display = 'none';
        }
        return;
    }
    
    try {
        const response = await fetch('ajax_handlers.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'action=searchUsers&name=' + encodeURIComponent(searchTerm)
        });
        
        const data = await response.json();
        
        if (data.success) {
            if (data.users.length > 0) {
                displayUsers(data.users, 'searchResultsBody');
                if (searchResultsTable) {
                    searchResultsTable.style.display = 'table';
                }
                
                if (searchMessage) {
                    searchMessage.textContent = `Found ${data.users.length} user(s)`;
                    searchMessage.style.display = 'block';
                    searchMessage.className = 'success';
                }
            } else {
                if (searchResultsTable) {
                    searchResultsTable.style.display = 'none';
                }
                if (searchMessage) {
                    searchMessage.textContent = "No users found matching your search";
                    searchMessage.style.display = 'block';
                    searchMessage.className = 'error';
                }
            }
        } else {
            if (searchResultsTable) {
                searchResultsTable.style.display = 'none';
            }
            if (searchMessage) {
                searchMessage.textContent = "Error: " + data.message;
                searchMessage.style.display = 'block';
                searchMessage.className = 'error';
            }
        }
    } catch (error) {
        console.error('Error searching users:', error);
        if (searchResultsTable) {
            searchResultsTable.style.display = 'none';
        }
        if (searchMessage) {
            searchMessage.textContent = "Error searching users. Please try again.";
            searchMessage.style.display = 'block';
            searchMessage.className = 'error';
        }
    }
}

// Display users in table
function displayUsers(users, tableBodyId) {
    console.log('displayUsers called with', users.length, 'users');
    
    const tableBody = document.getElementById(tableBodyId);
    if (!tableBody) {
        console.error('Table body not found:', tableBodyId);
        return;
    }
    
    tableBody.innerHTML = "";
    
    if (users.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="8">No users found</td></tr>';
        return;
    }
    
    users.forEach(user => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${user.id}</td>
            <td>${user.name}</td>
            <td>${user.username}</td>
            <td>${user.age}</td>
            <td>${user.role}</td>
            <td>${user.email}</td>
            <td>${user.webpage || 'N/A'}</td>
            <td>
                <button onclick="editUser(${user.id})">Edit</button>
                <button class="delete" onclick="confirmAndDeleteUser(${user.id})">Delete</button>
            </td>
        `;
        tableBody.appendChild(row);
    });
}

// Add new user
async function addUser(event) {
    event.preventDefault();
    
    const form = document.getElementById('addUserForm');
    const formData = new FormData(form);
    formData.append('action', 'addUser');
    
    try {
        const response = await fetch('ajax_handlers.php', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        const messageElement = document.getElementById('addMessage');
        
        if (data.success) {
            messageElement.className = 'success';
            messageElement.textContent = data.message;
            form.reset();
            await loadUsers();
            await refreshUpdateDropdown();
        } else {
            messageElement.className = 'error';
            messageElement.textContent = data.message;
        }
    } catch (error) {
        console.error('Error adding user:', error);
        const messageElement = document.getElementById('addMessage');
        messageElement.className = 'error';
        messageElement.textContent = 'Error adding user';
    }
}

// Edit user (navigate to update tab and load user data)
function editUser(userId) {
    // Switch to update tab
    document.querySelector('.tab[data-tab="update"]').click();
    
    // Set the user in dropdown
    const userIdToUpdate = document.getElementById('userIdToUpdate');
    if (userIdToUpdate) {
        userIdToUpdate.value = userId;
        loadUserDataForUpdate();
    }
}

// Load user data for updating
async function loadUserDataForUpdate() {
    const userIdToUpdate = document.getElementById('userIdToUpdate');
    const userId = userIdToUpdate.value;
    
    if (!userId) {
        alert("Please select a user to update");
        return;
    }
    
    try {
        const response = await fetch('ajax_handlers.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'action=getUserById&id=' + encodeURIComponent(userId)
        });
        
        const data = await response.json();
        
        if (data.success) {
            const user = data.user;
            
            // Populate form fields
            document.getElementById('updateId').value = user.id;
            document.getElementById('updateName').value = user.name;
            document.getElementById('updateUsername').value = user.username;
            document.getElementById('updatePassword').value = '';  // Don't show password
            document.getElementById('updateAge').value = user.age;
            document.getElementById('updateRole').value = user.role;
            document.getElementById('updateProfile').value = user.profile;
            document.getElementById('updateEmail').value = user.email;
            document.getElementById('updateWebpage').value = user.webpage;
        } else {
            alert(data.message);
        }
    } catch (error) {
        console.error('Error loading user data:', error);
        alert('Error loading user data');
    }
}

// Update user
async function updateUser(event) {
    event.preventDefault();
    
    const form = document.getElementById('updateUserForm');
    const formData = new FormData(form);
    formData.append('action', 'updateUser');
    
    try {
        const response = await fetch('ajax_handlers.php', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        const messageElement = document.getElementById('updateMessage');
        
        if (data.success) {
            messageElement.className = 'success';
            messageElement.textContent = data.message;
            await loadUsers();
            await refreshUpdateDropdown();
        } else {
            messageElement.className = 'error';
            messageElement.textContent = data.message;
        }
    } catch (error) {
        console.error('Error updating user:', error);
        const messageElement = document.getElementById('updateMessage');
        messageElement.className = 'error';
        messageElement.textContent = 'Error updating user';
    }
}

// Confirm and delete user (separate function for clearer organization)
function confirmAndDeleteUser(userId) {
    if (!confirm(`Are you sure you want to delete user with ID ${userId}?`)) {
        return;
    }
    deleteUser(userId);
}

// Delete user
async function deleteUser(userId) {
    try {
        const response = await fetch('ajax_handlers.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'action=deleteUser&id=' + encodeURIComponent(userId)
        });
        
        const data = await response.json();
        
        if (data.success) {
            await loadUsers();
            await refreshUpdateDropdown();
            alert(data.message);
        } else {
            alert(data.message);
        }
    } catch (error) {
        console.error('Error deleting user:', error);
        alert('Error deleting user');
    }
}

// Refresh the update dropdown with current users
async function refreshUpdateDropdown() {
    try {
        const response = await fetch('ajax_handlers.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'action=getUsers'
        });
        
        const data = await response.json();
        
        if (data.success) {
            const userIdToUpdate = document.getElementById('userIdToUpdate');
            if (userIdToUpdate) {
                userIdToUpdate.innerHTML = '<option value="">Select a User</option>';
                
                data.users.forEach(user => {
                    const option = document.createElement('option');
                    option.value = user.id;
                    option.textContent = `${user.id} - ${user.name} (${user.username})`;
                    userIdToUpdate.appendChild(option);
                });
            }
        }
    } catch (error) {
        console.error('Error refreshing user dropdown:', error);
    }
}