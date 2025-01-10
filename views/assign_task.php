<?php
session_start();
require_once '../config/database.php';

class AssignTask
{
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function getUsers()
    {
        $stmt = $this->conn->query("SELECT id, name FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
    public function getTasks()
    {
        $stmt = $this->conn->query("SELECT id, title FROM task");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function assignTask($task_id, $user_id)
    {
        $checkStmt = $this->conn->prepare("SELECT COUNT(*) FROM task_assignment WHERE task_id = :task_id AND users_id = :user_id");
        $checkStmt->bindParam(':task_id', $task_id);
        $checkStmt->bindParam(':user_id', $user_id);
        $checkStmt->execute();
        $count = $checkStmt->fetchColumn();

        if ($count > 0) {
            return false;
        }

        $stmt = $this->conn->prepare("INSERT INTO task_assignment (task_id, users_id) VALUES (:task_id, :user_id)");
        $stmt->bindParam(':task_id', $task_id);
        $stmt->bindParam(':user_id', $user_id);
        return $stmt->execute();
    }
}

$assign = new AssignTask();
$users = $assign->getUsers();
$tasks = $assign->getTasks();
$assignmentMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $task_id = $_POST['task_id'];
    $user_id = $_POST['user_id'];

    if ($assign->assignTask($task_id, $user_id)) {
        $assignmentMessage = "<p class='text-green-500'>Tâche assignée avec succès!</p>";
    } else {
        $assignmentMessage = "<p class='text-red-500'>Cette tâche est déjà assignée à cet utilisateur.</p>";
    }
}

if (empty($users)) {
    echo "<p class='text-red-500'>Aucun utilisateur trouvé dans la base de données.</p>";
}
if (empty($tasks)) {
    echo "<p class='text-red-500'>Aucune tâche trouvée dans la base de données.</p>";
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assigner une tasks</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 p-10">
    <div class="max-w-lg mx-auto bg-white p-8 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-5">Assigner des tasks</h1>

        <?= $assignmentMessage ?>

        <form method="POST" class="space-y-4">
    <div>
        <label for="task_id" class="block text-gray-200">Tasks :</label>
        <select name="task_id" id="task_id" required class="w-full px-4 py-2 bg-gray-700 text-gray-200 border border-gray-600 rounded-md">
            <?php foreach ($tasks as $task): ?>
                        <option value="<?= $task['id'] ?>"><?= htmlspecialchars($task['title']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        
            <div>
                <label for="user_id" class="block text-gray-200">Members :</label>
                <select name="user_id" id="user_id" required
                    class="w-full px-4 py-2 bg-gray-700 text-gray-200 border border-gray-600 rounded-md">
                    <?php foreach ($users as $user): ?>
                        <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        
            
            <button type="submit" class="w-full bg-teal-400 text-white py-2 rounded-md hover:bg-teal-500">
                Assign Task to this member
            </button>
    
            <a href="../views\manager_dashboard.php" class="block text-center mt-4 text-teal-400 hover:text-teal-500">Retour</a>
        </form>

    </div>
</body>

</html>
