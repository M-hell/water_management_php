<?php
// Database connection
// $conn = new mysqli('sql106.infinityfree.com', 'if0_38530000', 'CghLSOtRVY', 'if0_38530000_testphp');
$conn = new mysqli('localhost', 'root', '', 'testphp');

// Hardcoded login credentials
$valid_email = "adminuser@gmail.com";
$valid_password = "admin123";

// Initialize variables
$error = '';
$success = '';
$logged_in = false;
$registrations = [];

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login'])) {
        // Login handling
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        
        if ($email === $valid_email && $password === $valid_password) {
            $logged_in = true;
            // Fetch data from database
            refreshData();
        } else {
            $error = 'Invalid email or password';
        }
    } elseif (isset($_POST['delete_id'])) {
        // Delete handling
        $id = (int)$_POST['delete_id'];
        $stmt = $conn->prepare("DELETE FROM registration WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $success = 'Record deleted successfully';
            refreshData();
        } else {
            $error = 'Error deleting record';
        }
        $stmt->close();
    } elseif (isset($_POST['update_response'])) {
        // Update response handling
        $id = (int)$_POST['id'];
        $response = $_POST['response'] ?? '';
        
        $stmt = $conn->prepare("UPDATE registration SET response = ? WHERE id = ?");
        $stmt->bind_param("si", $response, $id);
        if ($stmt->execute()) {
            $success = 'Response updated successfully';
            refreshData();
        } else {
            $error = 'Error updating response';
        }
        $stmt->close();
    }
}

// Logout functionality
if (isset($_GET['logout'])) {
    $logged_in = false;
    session_destroy();
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

function refreshData() {
    global $conn, $registrations;
    $result = $conn->query("SELECT * FROM registration");
    if ($result) {
        $registrations = $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <style>
        .fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .slide-in {
            animation: slideIn 0.3s ease-out;
        }
        @keyframes slideIn {
            from { transform: translateX(-20px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        .transition-all {
            transition: all 0.3s ease;
        }
        .btn-delete {
            transition: all 0.2s ease;
        }
        .btn-delete:hover {
            transform: scale(1.1);
        }
        .response-textarea {
            min-height: 100px;
            resize: vertical;
        }
        .modal {
            transition: opacity 0.3s ease, transform 0.3s ease;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <?php if (!$logged_in): ?>
    <!-- Login Form -->
    <div class="bg-white rounded-xl shadow-2xl p-8 w-full max-w-md animate__animated animate__fadeIn">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Admin Panel</h1>
            <p class="text-gray-600 mt-2">Please sign in to continue</p>
        </div>
        
        <?php if ($error): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 animate__animated animate__shakeX">
            <?php echo $error; ?>
        </div>
        <?php endif; ?>
        
        <form method="POST" class="space-y-6">
            <div class="space-y-2">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="email" name="email" required 
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                    placeholder="Enter your email">
            </div>
            
            <div class="space-y-2">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" id="password" name="password" required 
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                    placeholder="Enter your password">
            </div>
            
            <button type="submit" name="login" 
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-all transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                Sign In
            </button>
        </form>
    </div>
    <?php else: ?>
    <!-- Data Table -->
    <div class="w-full max-w-6xl bg-white rounded-xl shadow-xl overflow-hidden animate__animated animate__fadeIn">
        <div class="flex justify-between items-center bg-gray-800 text-white p-4">
            <h1 class="text-2xl font-bold">Registration Data</h1>
            <a href="?logout=1" class="bg-red-500 hover:bg-red-600 px-4 py-2 rounded-lg transition-all">Logout</a>
        </div>
        
        <div class="p-4 overflow-x-auto">
            <?php if ($success): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 animate__animated animate__fadeIn">
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>
            <?php if ($error && !isset($_POST['login'])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 animate__animated animate__shakeX">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <?php if (empty($registrations)): ?>
                <div class="text-center py-8 text-gray-500">No registration data found.</div>
            <?php else: ?>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider slide-in">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider slide-in">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider slide-in">Phone</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider slide-in">Company</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider slide-in">Message</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider slide-in">Response</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider slide-in">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($registrations as $index => $row): ?>
                        <tr class="<?php echo $index % 2 === 0 ? 'bg-white' : 'bg-gray-50'; ?> fade-in" style="animation-delay: <?php echo $index * 0.05; ?>s">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($row['name'] ?? ''); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($row['email'] ?? ''); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($row['number'] ?? ''); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($row['company'] ?? ''); ?></td>
                            <td class="px-6 py-4 text-sm text-gray-500"><?php echo htmlspecialchars($row['message'] ?? ''); ?></td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                <?php if (!empty($row['response'])): ?>
                                    <?php echo nl2br(htmlspecialchars($row['response'])); ?>
                                <?php else: ?>
                                    <span class="text-gray-400">No response yet</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 space-x-2">
                                <button onclick="openResponseModal(<?php echo $row['id']; ?>, `<?php echo htmlspecialchars($row['response'] ?? '', ENT_QUOTES); ?>`)" 
                                    class="text-blue-600 hover:text-blue-900 btn-delete" title="Add/Edit Response">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                <form method="POST" onsubmit="return confirm('Are you sure you want to delete this record?');" class="inline">
                                    <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" class="text-red-600 hover:text-red-900 btn-delete" title="Delete">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

    <!-- Response Modal -->
    <div id="responseModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg p-6 w-full max-w-md modal transform scale-95 opacity-0">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium">Add/Edit Response</h3>
                <button onclick="closeResponseModal()" class="text-gray-500 hover:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form method="POST" id="responseForm">
                <input type="hidden" name="id" id="responseId">
                <input type="hidden" name="update_response" value="1">
                <textarea name="response" id="responseText" class="w-full px-3 py-2 border border-gray-300 rounded-md response-textarea focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                <div class="mt-4 flex justify-end space-x-3">
                    <button type="button" onclick="closeResponseModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-all">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-all">Save Response</button>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <script>
        // Add some interactive animations
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('input');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('ring-2', 'ring-blue-200');
                });
                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('ring-2', 'ring-blue-200');
                });
            });
        });

        // Response modal functions
        function openResponseModal(id, currentResponse) {
            const modal = document.getElementById('responseModal');
            const modalContent = modal.querySelector('.modal');
            document.getElementById('responseId').value = id;
            document.getElementById('responseText').value = currentResponse;
            
            modal.classList.remove('hidden');
            setTimeout(() => {
                modalContent.classList.remove('scale-95', 'opacity-0');
                modalContent.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeResponseModal() {
            const modal = document.getElementById('responseModal');
            const modalContent = modal.querySelector('.modal');
            
            modalContent.classList.remove('scale-100', 'opacity-100');
            modalContent.classList.add('scale-95', 'opacity-0');
            
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }
    </script>
</body>
</html>