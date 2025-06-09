<?php
/**
 * 
 * 
 * frontend links backend without reloding page
 */

// Start session if not started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include user functions
require_once 'user_functions.php';

// ajax expects json response
header('Content-Type: application/json');

// Get the action from the request
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

// Response array
$response = ['success' => false, 'message' => 'Invalid action'];

switch ($action) {
    case 'getUsers':
        // Get role filter if provided
        $role = isset($_REQUEST['role']) ? trim($_REQUEST['role']) : '';
        
        // Store current filter in session
        $_SESSION['current_role_filter'] = $role;
        
        // Get filtered users
        $users = getUsers($role);
        
        $response = [
            'success' => true,
            'users' => $users,
            'currentFilter' => $role
        ];
        break;
        
    case 'getCurrentFilter':
        // Return current filter from session
        $currentFilter = isset($_SESSION['current_role_filter']) ? $_SESSION['current_role_filter'] : '';
        
        $response = [
            'success' => true,
            'currentFilter' => $currentFilter
        ];
        break;
        
    case 'searchUsers':
        // Get search term
        $name = isset($_REQUEST['name']) ? trim($_REQUEST['name']) : '';
        
        if (empty($name)) {
            $response = [
                'success' => false,
                'message' => 'Please enter a name to search'
            ];
        } else {
            // Search users by name
            $users = searchUsersByName($name);
            
            $response = [
                'success' => true,
                'users' => $users
            ];
        }
        break;
        
    case 'getUserById':
        // Get user ID
        $id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
        
        if ($id <= 0) {
            $response = [
                'success' => false,
                'message' => 'Invalid user ID'
            ];
        } else {
            // Get user by ID
            $user = getUserById($id);
            
            if ($user) {
                $response = [
                    'success' => true,
                    'user' => $user
                ];
            } else {
                $response = [
                    'success' => false,
                    'message' => 'User not found'
                ];
            }
        }
        break;
        
    case 'addUser':
        // Check if form data is provided
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $response = [
                'success' => false,
                'message' => 'Invalid request method'
            ];
            break;
        }
        
        // Get form data
        $userData = [
            'name' => isset($_POST['name']) ? trim($_POST['name']) : '',
            'username' => isset($_POST['username']) ? trim($_POST['username']) : '',
            'password' => isset($_POST['password']) ? $_POST['password'] : '',
            'age' => isset($_POST['age']) ? intval($_POST['age']) : 0,
            'role' => isset($_POST['role']) ? trim($_POST['role']) : '',
            'profile' => isset($_POST['profile']) ? trim($_POST['profile']) : '',
            'email' => isset($_POST['email']) ? trim($_POST['email']) : '',
            'webpage' => isset($_POST['webpage']) ? trim($_POST['webpage']) : ''
        ];
        
        // Basic validation
        if (empty($userData['name']) || empty($userData['username']) || 
            empty($userData['password']) || empty($userData['role']) || 
            empty($userData['email']) || $userData['age'] < 18) {
            
            $response = [
                'success' => false,
                'message' => 'Please fill all required fields correctly'
            ];
            break;
        }
        
        // Add user
        $result = addUser($userData);
        
        if ($result === true) {
            $response = [
                'success' => true,
                'message' => 'User added successfully'
            ];
        } else {
            $response = [
                'success' => false,
                'message' => $result
            ];
        }
        break;
        
    case 'updateUser':
        // Check if form data is provided
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $response = [
                'success' => false,
                'message' => 'Invalid request method'
            ];
            break;
        }
        
        // Get user ID
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        
        if ($id <= 0) {
            $response = [
                'success' => false,
                'message' => 'Invalid user ID'
            ];
            break;
        }
        
        // Get form data
        $userData = [
            'name' => isset($_POST['name']) ? trim($_POST['name']) : '',
            'username' => isset($_POST['username']) ? trim($_POST['username']) : '',
            'password' => isset($_POST['password']) ? $_POST['password'] : '',
            'age' => isset($_POST['age']) ? intval($_POST['age']) : 0,
            'role' => isset($_POST['role']) ? trim($_POST['role']) : '',
            'profile' => isset($_POST['profile']) ? trim($_POST['profile']) : '',
            'email' => isset($_POST['email']) ? trim($_POST['email']) : '',
            'webpage' => isset($_POST['webpage']) ? trim($_POST['webpage']) : ''
        ];
        
        // Basic validation
        if (empty($userData['name']) || empty($userData['username']) || 
            empty($userData['role']) || empty($userData['email']) || $userData['age'] < 18) {
            
            $response = [
                'success' => false,
                'message' => 'Please fill all required fields correctly'
            ];
            break;
        }
        
        // Update user
        $result = updateUser($id, $userData);
        
        if ($result === true) {
            $response = [
                'success' => true,
                'message' => 'User updated successfully'
            ];
        } else {
            $response = [
                'success' => false,
                'message' => $result
            ];
        }
        break;
        
    case 'deleteUser':
        // Get user ID
        $id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
        
        if ($id <= 0) {
            $response = [
                'success' => false,
                'message' => 'Invalid user ID'
            ];
            break;
        }
        
        // Delete user
        if (deleteUser($id)) {
            $response = [
                'success' => true,
                'message' => 'User deleted successfully'
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'Error deleting user'
            ];
        }
        break;
        
    default:
        $response = [
            'success' => false,
            'message' => 'Unknown action: ' . $action
        ];
}

// Return JSON response
echo json_encode($response);