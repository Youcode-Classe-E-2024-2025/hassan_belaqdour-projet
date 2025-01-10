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

    // Méthode pour assigner une tâche à un utilisateur
    public function assignTask($task_id, $user_id)
    {
        // Vérifier si la tâche est déjà assignée à l'utilisateur
        $checkStmt = $this->conn->prepare("
            SELECT COUNT(*) FROM task_assignment WHERE task_id = :task_id AND users_id = :user_id
        ");
        $checkStmt->bindParam(':task_id', $task_id);
        $checkStmt->bindParam(':user_id', $user_id);
        $checkStmt->execute();
        $count = $checkStmt->fetchColumn();

        // Si la tâche est déjà assignée, ne pas insérer et retourner un message d'erreur
        if ($count > 0) {
            return false; // Tâche déjà assignée à cet utilisateur
        }

        // Sinon, procéder à l'insertion
        $stmt = $this->conn->prepare("
            INSERT INTO task_assignment (task_id, users_id) 
            VALUES (:task_id, :user_id)
        ");
        $stmt->bindParam(':task_id', $task_id);
        $stmt->bindParam(':user_id', $user_id);
        return $stmt->execute();
    }

    // Récupérer la liste des utilisateurs
    public function getUsers()
    {
        $stmt = $this->conn->query("SELECT id, name FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer la liste des tâches
    public function getTasks()
    {
        $stmt = $this->conn->query("SELECT id, title FROM task");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

$assign = new AssignTask();
$users = $assign->getUsers();
$tasks = $assign->getTasks();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $task_id = $_POST['task_id'];
    $user_id = $_POST['user_id'];

    // Vérifier si l'assignation a réussi
    if ($assign->assignTask($task_id, $user_id)) {
        echo "<p class='text-green-500'>Tâche assignée avec succès!</p>";
        header("Location: ../views/statistique.php");
        exit();
    } else {
        echo "<p class='text-red-500'>Cette tâche est déjà assignée à cet utilisateur.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assigner une Tâche</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 p-10">
    <div class="max-w-lg mx-auto bg-white p-8 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-5">Assigner une Tâche</h1>

        <form method="POST" class="space-y-4">
            <!-- Sélection de la tâche -->
            <label class="block font-semibold">Tâche :</label>
            <select name="task_id" required class="p-2 border rounded w-full">
                <?php foreach ($tasks as $task): ?>
                    <option value="<?= $task['id'] ?>"><?= htmlspecialchars($task['title']) ?></option>
                <?php endforeach; ?>
            </select>

            <!-- Sélection de l'utilisateur -->
            <label class="block font-semibold">Utilisateur :</label>
            <select name="user_id" required class="p-2 border rounded w-full">
                <?php foreach ($users as $user): ?>
                    <option value="<?= $user['id'] ?>">
                        <?= htmlspecialchars($user['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <!-- Bouton de soumission -->
            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-700">
                Assigner la Tâche
            </button>
            <a href="../views/index.php" class="block text-center mt-4 text-blue-600">Retour</a>
        </form>
    </div>
</body>

</html>