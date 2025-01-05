<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gestion_projet";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
function checkUserLogin($role = 'member')
{
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== $role) {
        header("Location: dashboard.php");
        exit();
    }
}

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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['task_id']) && isset($_POST['status'])) {
    $task_id = $_POST['task_id'];
    $status = $_POST['status'];

    $update_query = "UPDATE tasks SET status = ? WHERE task_id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("si", $status, $task_id);
    if ($stmt->execute()) {
        header("Location: member_dashboard.php");
        exit();
    } 
}

checkUserLogin();

$user_id = $_SESSION['user_id'];
$tasks = getUserTasks($conn, $user_id);

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord Membre</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.3/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <div class="container mx-auto p-6">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-4">Bienvenue,
                <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>

            <div class="mt-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Vos Tâches Assignées</h2>

                <?php if (empty($tasks)): ?>
                    <p class="text-gray-600">Vous n'avez aucune tâche assignée.</p>
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
                                    Statut: <span class="text-green-500"><?php echo ucfirst($task['status']); ?></span>
                                </p>

                                <!-- Formulaire pour changer le statut -->
                                <form action="member_dashboard.php" method="POST" class="mt-4">
                                    <input type="hidden" name="task_id" value="<?php echo $task['task_id']; ?>">
                                    <select name="status" class="p-2 border border-gray-300 rounded-md">
                                        <option value="To Do" <?php echo $task['status'] == 'To Do' ? 'selected' : ''; ?>>To Do
                                        </option>
                                        <option value="Doing" <?php echo $task['status'] == 'Doing' ? 'selected' : ''; ?>>Doing
                                        </option>
                                        <option value="Done" <?php echo $task['status'] == 'Done' ? 'selected' : ''; ?>>Done
                                        </option>
                                    </select>
                                    <button type="submit"
                                        class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-700 ml-2">Mettre à
                                        jour</button>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>

</html>