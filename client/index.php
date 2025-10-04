<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "client_management_system";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_client'])) {
        $clientName = $_POST['clientName'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $country = $_POST['country'];
        $state = $_POST['state'];
        $province = $_POST['province'];
        $amount = $_POST['amount'];
        $paymentMethod = $_POST['paymentMethod'];
        $paymentType = $_POST['paymentType'];
        $status = $_POST['status'];

        $transactionId = 'TRX' . substr(md5(uniqid()), 0, 6);
        $date = date('Y-m-d H:i:s');

        $sql = "INSERT INTO transactions (date, transaction_id, client_name, email, phone, country, state, province, amount, payment_method, payment_type, status)
                VALUES ('$date', '$transactionId', '$clientName', '$email', '$phone', '$country', '$state', '$province', '$amount', '$paymentMethod', '$paymentType', '$status')";

        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('New record created successfully');</script>";
        } else {
            echo "<script>alert('Error: " . $sql . "<br>" . $conn->error . "');</script>";
        }
    } elseif (isset($_POST['update_client'])) {
        $id = $_POST['id'];
        $clientName = $_POST['clientName'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $country = $_POST['country'];
        $state = $_POST['state'];
        $province = $_POST['province'];
        $amount = $_POST['amount'];
        $paymentMethod = $_POST['paymentMethod'];
        $paymentType = $_POST['paymentType'];
        $status = $_POST['status'];

        $sql = "UPDATE transactions SET client_name='$clientName', email='$email', phone='$phone', country='$country', state='$state', province='$province', amount='$amount', payment_method='$paymentMethod', payment_type='$paymentType', status='$status' WHERE id=$id";

        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Record updated successfully');</script>";
        } else {
            echo "<script>alert('Error updating record: " . $conn->error . "');</script>";
        }
    } elseif (isset($_POST['delete_client'])) {
        $id = $_POST['id'];

        $sql = "DELETE FROM transactions WHERE id=$id";

        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Record deleted successfully');</script>";
        } else {
            echo "<script>alert('Error deleting record: " . $conn->error . "');</script>";
        }
    }
}

// Fetch transactions
$searchQuery = $_GET['search'] ?? '';
$statusFilter = $_GET['status'] ?? '';

$sql = "SELECT * FROM transactions WHERE 1=1";
if (!empty($searchQuery)) {
    $sql .= " AND (client_name LIKE '%$searchQuery%' OR transaction_id LIKE '%$searchQuery%')";
}
if (!empty($statusFilter)) {
    $sql .= " AND status = '$statusFilter'";
}

$result = $conn->query($sql);
$transactions = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $transactions[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .bg-darkblue {
            background-color: #1e3a8a;
        }
        .text-darkblue {
            color: #1e3a8a;
        }
        .border-darkblue {
            border-color: #1e3a8a;
        }
        .hover\:bg-darkblue:hover {
            background-color: #1e3a8a;
        }
    </style>
</head>
<body class="bg-gray-50">
    <nav class="bg-darkblue text-white p-4">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-bold font-roboto">Client Management System</h1>
            <div class="flex items-center space-x-4">
                <button class="px-4 py-2 bg-blue-500 hover:bg-blue-700 rounded">
                    <i class="fas fa-user-circle mr-2"></i>Admin
                </button>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-700">Total Clients</h3>
                    <i class="fas fa-users text-2xl text-darkblue"></i>
                </div>
                <p class="text-3xl font-bold text-gray-800"><?php echo count($transactions); ?></p>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-700">Total Revenue</h3>
                    <i class="fas fa-dollar-sign text-2xl text-green-500"></i>
                </div>
                <p class="text-3xl font-bold text-gray-800">
                    $<?php echo array_reduce($transactions, function ($sum, $t) {
                        return $sum + $t['amount'];
                    }, 0); ?>
                </p>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-700">Pending Payments</h3>
                    <i class="fas fa-clock text-2xl text-yellow-500"></i>
                </div>
                <p class="text-3xl font-bold text-gray-800">
                    <?php echo count(array_filter($transactions, function ($t) {
                        return $t['status'] === 'pending';
                    })); ?>
                </p>
            </div>
        </div>

        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center space-x-4">
                <button onclick="document.getElementById('addModal').classList.remove('hidden')" class="bg-darkblue hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
                    <i class="fas fa-plus mr-2"></i> Add New Client
                </button>
            </div>
            <div class="flex items-center space-x-4">
                <form method="GET" class="flex space-x-4">
                    <input type="text" name="search" placeholder="Search by name or ID..." value="<?php echo $searchQuery; ?>" class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-darkblue outline-none w-64">
                    <select name="status" class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-darkblue outline-none">
                        <option value="">All Status</option>
                        <option value="pending" <?php echo $statusFilter === 'pending' ? 'selected' : ''; ?>>Pending</option>
                        <option value="completed" <?php echo $statusFilter === 'completed' ? 'selected' : ''; ?>>Completed</option>
                        <option value="failed" <?php echo $statusFilter === 'failed' ? 'selected' : ''; ?>>Failed</option>
                    </select>
                    <button type="submit" class="px-4 py-2 bg-darkblue text-white rounded-lg hover:bg-blue-700">Search</button>
                </form>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($transactions as $transaction): ?>
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <p class="text-sm text-gray-500"><?php echo $transaction['date']; ?></p>
                                <p class="font-semibold text-gray-800 mt-1"><?php echo $transaction['transaction_id']; ?></p>
                            </div>
                            <div class="flex space-x-2">
                                <button onclick="openEditModal(<?php echo $transaction['id']; ?>)" class="px-3 py-1 bg-darkblue text-white rounded hover:bg-blue-700">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="confirmDelete(<?php echo $transaction['id']; ?>)" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <button onclick="openViewModal('<?php echo $transaction['transaction_id']; ?>')" class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button onclick="exportTransaction('<?php echo $transaction['transaction_id']; ?>')" class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                                    <i class="fas fa-download"></i>
                                </button>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <i class="fas fa-user text-darkblue w-5"></i>
                                <p class="text-gray-800 ml-2"><?php echo $transaction['client_name']; ?></p>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-envelope text-darkblue w-5"></i>
                                <p class="text-gray-600 ml-2"><?php echo $transaction['email']; ?></p>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-dollar-sign text-green-500 w-5"></i>
                                <p class="text-gray-800 font-semibold ml-2">$<?php echo $transaction['amount']; ?></p>
                            </div>
                            <div class="flex items-center mt-2">
                                <span class="px-2 py-1 text-xs rounded-full <?php echo $transaction['status'] === 'completed' ? 'bg-green-100 text-green-800' : ($transaction['status'] === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800'); ?>">
                                    <?php echo ucfirst($transaction['status']); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

       <!-- Add Modal -->
<div id="addModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-8 w-[1000px] h-[600px] overflow-y-auto">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Add New Client</h2>
            <button onclick="document.getElementById('addModal').classList.add('hidden')" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form method="POST">
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Client Name</label>
                    <input type="text" name="clientName" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-darkblue outline-none" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-darkblue outline-none" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                    <input type="tel" name="phone" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-darkblue outline-none" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Country</label>
                    <input type="text" name="country" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-darkblue outline-none" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">State</label>
                    <input type="text" name="state" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-darkblue outline-none" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Province</label>
                    <input type="text" name="province" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-darkblue outline-none" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Amount</label>
                    <input type="text" name="amount" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-darkblue outline-none" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                    <select name="paymentMethod" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-darkblue outline-none" required>
                        <option value="easypaisa">EasyPaisa</option>
                        <option value="jazzcash">JazzCash</option>
                        <option value="bank">Bank Transfer</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Type</label>
                    <select name="paymentType" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-darkblue outline-none" required>
                        <option value="full">Full Payment</option>
                        <option value="partial">Partial Payment</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-darkblue outline-none" required>
                        <option value="pending">Pending</option>
                        <option value="completed">Completed</option>
                        <option value="failed">Failed</option>
                    </select>
                </div>
            </div>
            <div class="col-span-2 flex justify-end mt-6">
                <button type="submit" name="add_client" class="px-6 py-3 bg-darkblue text-white rounded-lg hover:bg-blue-700 transition-colors">
                    Add Client
                </button>
            </div>
        </form>
    </div>
</div>
      <!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-8 w-[1000px] h-[600px] overflow-y-auto">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Edit Client</h2>
            <button onclick="document.getElementById('editModal').classList.add('hidden')" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form method="POST">
            <input type="hidden" name="id" id="editId">
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Client Name</label>
                    <input type="text" name="clientName" id="editClientName" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-darkblue outline-none" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" id="editEmail" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-darkblue outline-none" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                    <input type="tel" name="phone" id="editPhone" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-darkblue outline-none" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Country</label>
                    <input type="text" name="country" id="editCountry" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-darkblue outline-none" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">State</label>
                    <input type="text" name="state" id="editState" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-darkblue outline-none" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Province</label>
                    <input type="text" name="province" id="editProvince" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-darkblue outline-none" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Amount</label>
                    <input type="text" name="amount" id="editAmount" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-darkblue outline-none" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                    <select name="paymentMethod" id="editPaymentMethod" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-darkblue outline-none" required>
                        <option value="easypaisa">EasyPaisa</option>
                        <option value="jazzcash">JazzCash</option>
                        <option value="bank">Bank Transfer</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Type</label>
                    <select name="paymentType" id="editPaymentType" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-darkblue outline-none" required>
                        <option value="full">Full Payment</option>
                        <option value="partial">Partial Payment</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" id="editStatus" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-darkblue outline-none" required>
                        <option value="pending">Pending</option>
                        <option value="completed">Completed</option>
                        <option value="failed">Failed</option>
                    </select>
                </div>
            </div>
            <div class="col-span-2 flex justify-end mt-6">
                <button type="submit" name="update_client" class="px-6 py-3 bg-darkblue text-white rounded-lg hover:bg-blue-700 transition-colors">
                    Update Client
                </button>
            </div>
        </form>
    </div>
</div>

        <!-- View Modal -->
        <div id="viewModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 w-full max-w-md">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold">View Transaction</h2>
                    <button onclick="document.getElementById('viewModal').classList.add('hidden')" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="space-y-4">
                    <p><strong>Transaction ID:</strong> <span id="viewTransactionId"></span></p>
                    <p><strong>Date:</strong> <span id="viewDate"></span></p>
                    <p><strong>Client Name:</strong> <span id="viewClientName"></span></p>
                    <p><strong>Email:</strong> <span id="viewEmail"></span></p>
                    <p><strong>Phone:</strong> <span id="viewPhone"></span></p>
                    <p><strong>Amount:</strong> $<span id="viewAmount"></span></p>
                    <p><strong>Status:</strong> <span id="viewStatus"></span></p>
                    <p><strong>Payment Method:</strong> <span id="viewPaymentMethod"></span></p>
                    <p><strong>Payment Type:</strong> <span id="viewPaymentType"></span></p>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 w-full max-w-md">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold">Delete Transaction</h2>
                    <button onclick="document.getElementById('deleteModal').classList.add('hidden')" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <p class="mb-4">Are you sure you want to delete this transaction?</p>
                <form method="POST" id="deleteForm">
                    <input type="hidden" name="id" id="deleteId">
                    <div class="flex justify-end space-x-4">
                        <button type="button" onclick="document.getElementById('deleteModal').classList.add('hidden')" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                            Cancel
                        </button>
                        <button type="submit" name="delete_client" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                            Delete
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <script>
          function openEditModal(id) {
    fetch(`get_transaction.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('editId').value = data.id;
            document.getElementById('editClientName').value = data.client_name;
            document.getElementById('editEmail').value = data.email;
            document.getElementById('editPhone').value = data.phone;
            document.getElementById('editCountry').value = data.country;
            document.getElementById('editState').value = data.state;
            document.getElementById('editProvince').value = data.province;
            document.getElementById('editAmount').value = data.amount;
            document.getElementById('editPaymentMethod').value = data.payment_method;
            document.getElementById('editPaymentType').value = data.payment_type;
            document.getElementById('editStatus').value = data.status;
            document.getElementById('editModal').classList.remove('hidden');
        });
}

            function openViewModal(transactionId) {
                fetch(`get_transaction.php?transaction_id=${transactionId}`)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('viewTransactionId').innerText = data.transaction_id;
                        document.getElementById('viewDate').innerText = data.date;
                        document.getElementById('viewClientName').innerText = data.client_name;
                        document.getElementById('viewEmail').innerText = data.email;
                        document.getElementById('viewPhone').innerText = data.phone;
                        document.getElementById('viewAmount').innerText = data.amount;
                        document.getElementById('viewStatus').innerText = data.status;
                        document.getElementById('viewPaymentMethod').innerText = data.payment_method;
                        document.getElementById('viewPaymentType').innerText = data.payment_type;
                        document.getElementById('viewModal').classList.remove('hidden');
                    });
            }

            function confirmDelete(id) {
                document.getElementById('deleteId').value = id;
                document.getElementById('deleteModal').classList.remove('hidden');
            }

            function exportTransaction(transactionId) {
                fetch(`get_transaction.php?transaction_id=${transactionId}`)
                    .then(response => response.json())
                    .then(data => {
                        const htmlContent = `
                            <html>
                                <head>
                                    <style>
                                        body { font-family: Arial, sans-serif; padding: 20px; }
                                        .header { text-align: center; margin-bottom: 30px; }
                                        .title { font-size: 24px; color: #2563eb; margin-bottom: 10px; }
                                        .date { color: #666; }  
                                        .section { margin-bottom: 20px; }
                                        .label { font-weight: bold; color: #374151; }
                                        .value { color: #4b5563; }
                                        .status { 
                                            padding: 5px 10px;
                                            border-radius: 15px;
                                            display: inline-block;
                                            color: white;
                                            background: ${
                                                data.status === "completed" 
                                                    ? "#10B981"
                                                    : data.status === "pending"
                                                    ? "#F59E0B" 
                                                    : "#EF4444"
                                            };
                                        }
                                    </style>
                                </head>
                                <body>
                                    <div class="header">
                                        <div class="title">Transaction Details</div>
                                        <div class="date">${data.date}</div>
                                    </div>
                                    
                                    <div class="section">
                                        <div><span class="label">Transaction ID:</span> <span class="value">${data.transaction_id}</span></div>
                                        <div><span class="label">Client Name:</span> <span class="value">${data.client_name}</span></div>
                                        <div><span class="label">Email:</span> <span class="value">${data.email}</span></div>
                                        <div><span class="label">Phone:</span> <span class="value">${data.phone}</span></div>
                                    </div>
                                    
                                    <div class="section">
                                        <div><span class="label">Amount:</span> <span class="value">${data.amount}</span></div>
                                        <div><span class="label">Payment Method:</span> <span class="value">${data.payment_method}</span></div>
                                        <div><span class="label">Payment Type:</span> <span class="value">${data.payment_type}</span></div>
                                        <div><span class="label">Status:</span> <span class="status">${data.status}</span></div>
                                    </div>
                                    
                                    <div class="section">
                                        <div><span class="label">Country:</span> <span class="value">${data.country}</span></div>
                                        <div><span class="label">State:</span> <span class="value">${data.state}</span></div>
                                        <div><span class="label">Province:</span> <span class="value">${data.province}</span></div>
                                    </div>
                                </body>
                            </html>
                        `;

                        const blob = new Blob([htmlContent], { type: "text/html" });
                        const url = URL.createObjectURL(blob);
                        
                        const link = document.createElement("a");
                        link.href = url;
                        link.download = `transaction_${data.transaction_id}.html`;
                        document.body.appendChild(link);
                        link.click();

                        document.body.removeChild(link);
                        URL.revokeObjectURL(url);
                    });
            }
        </script>
    </div>
</body>
</html>