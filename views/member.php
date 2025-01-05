<?php
session_start();

// Vérifiez si l'utilisateur est connecté et a le rôle de 'member'
function checkUserLogin($role = 'member')
{
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== $role) {
        header("Location: login.php");
        exit();
    }
}

// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gestion_projet";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Récupérer les tâches assignées à l'utilisateur
function getUserTasks($conn, $user_id)
{
    $query = "SELECT task_id, task_title, task_description, due_date, status FROM tasks WHERE assigned_to = ? ORDER BY due_date ASC";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

// Vérifier si l'utilisateur est connecté
checkUserLogin();

// Récupérer les tâches de l'utilisateur
$user_id = $_SESSION['user_id'];
$tasks = getUserTasks($conn, $user_id);

// Fermer la connexion à la base de données
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.3/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <div class="container mx-auto p-6">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-4">Welcome,
                <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>

            <div class="mt-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Your Assigned Tasks</h2>

                <?php if (empty($tasks)): ?>
                    <p class="text-gray-600">You have no tasks assigned to you.</p>
                <?php else: ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php foreach ($tasks as $task): ?>
                            <div class="bg-white p-6 rounded-lg shadow-md">
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                    <?php echo htmlspecialchars($task['task_title']); ?>
                                </h3>
                                <p class="text-gray-600 mb-4">
                                    <?php echo htmlspecialchars($task['task_description']); ?>
                                </p>
                                <p class="text-gray-500 text-sm mb-2">
                                    Due Date: <?php echo date('M d, Y', strtotime($task['due_date'])); ?>
                                </p>
                                <p class="text-sm font-semibold text-gray-700">
                                    Status: <span class="text-green-500"><?php echo ucfirst($task['status']); ?></span>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>

</html>