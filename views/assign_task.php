<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'Chef') {
    header("Location: login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gestion_projet";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = "SELECT task_id, task_title, task_description, due_date FROM tasks WHERE assigned_to IS NULL";
$tasks_result = $conn->query($query);

$members_query = "SELECT user_id, username FROM users WHERE role = 'member'";
$members_result = $conn->query($members_query);

$error_message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $task_id = $_POST['task_id'];
    $assigned_to = $_POST['assigned_to'];

    if (empty($task_id) || empty($assigned_to)) {
        $error_message = "Veuillez sélectionner une tâche et un membre.";
    } else {
        $query = "UPDATE tasks SET assigned_to = ? WHERE task_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $assigned_to, $task_id);

        if ($stmt->execute()) {
            header("Location: dashboard.php");
            exit();
        } else {
            $error_message = "Erreur lors de l'assignation de la tâche.";
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assigner une tâche</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.3/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">

    <div class="container mx-auto p-6">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-4">Assigner une tâche</h1>

            <?php if (!empty($error_message)): ?>
                <div class="bg-red-500 text-white p-4 rounded-lg mb-4">
                    <p><?php echo $error_message; ?></p>
                </div>
            <?php endif; ?>

            <form method="POST" action="assign_task.php">
                <div class="mb-4">
                    <label for="task_id" class="block text-gray-700">Sélectionner une tâche</label>
                    <select id="task_id" name="task_id" class="w-full px-4 py-2 border rounded-md" required>
                        <option value="">Sélectionner une tâche</option>
                        <?php while ($task = $tasks_result->fetch_assoc()): ?>
                            <option value="<?php echo $task['task_id']; ?>">
                                <?php echo htmlspecialchars($task['task_title']); ?> - Due:
                                <?php echo date('M d, Y', strtotime($task['due_date'])); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="assigned_to" class="block text-gray-700">Assigner à un membre</label>
                    <select id="assigned_to" name="assigned_to" class="w-full px-4 py-2 border rounded-md" required>
                        <option value="">Sélectionner un membre</option>
                        <?php while ($member = $members_result->fetch_assoc()): ?>
                            <option value="<?php echo $member['user_id']; ?>">
                                <?php echo htmlspecialchars($member['username']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="mb-4">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-700">Assigner
                        la tâche</button>
                </div>
            </form>

        </div>
    </div>

</body>

</html>