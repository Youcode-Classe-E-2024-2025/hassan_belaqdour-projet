<?php
require_once '../config/database.php';

class Dashboard
{
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function getPendingUsers()
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE role = 'user'");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function approveUser($userId)
    {
        $stmt = $this->conn->prepare("UPDATE users SET role = 'validated_user' WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $userId);
        return $stmt->execute();
    }

    public function rejectUser($userId)
    {
        $stmt = $this->conn->prepare("DELETE FROM users WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $userId);
        return $stmt->execute();
    }
}

session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'manager') {
    header("Location: login.php");
    exit();
}

$dashboard = new Dashboard();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['approve'])) {
        $dashboard->approveUser($_POST['user_id']);
    } elseif (isset($_POST['reject'])) {
        $dashboard->rejectUser($_POST['user_id']);
    }
}

$pendingUsers = $dashboard->getPendingUsers();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.3/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-6">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-4">Admin Dashboard</h1>
            <h2 class="text-xl font-semibold mb-4">Pending Users</h2>
            <table class="w-full border-collapse border border-gray-300">
                <thead>
                    <tr>
                        <th class="border p-2">Nom</th>
                        <th class="border p-2">Email</th>
                        <th class="border p-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pendingUsers as $user) : ?>
                        <tr>
                            <td class="border p-2"><?php echo htmlspecialchars($user['nom']); ?></td>
                            <td class="border p-2"><?php echo htmlspecialchars($user['email']); ?></td>
                            <td class="border p-2">
                                <form method="POST">
                                    <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                                    <button type="submit" name="approve" class="bg-green-500 text-white px-4 py-2 rounded">Approve</button>
                                    <button type="submit" name="reject" class="bg-red-500 text-white px-4 py-2 rounded">Reject</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
