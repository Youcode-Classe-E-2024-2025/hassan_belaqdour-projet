<?php
session_start();
require_once '../config/database.php';

class MemberDashboard
{
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    // Récupérer les tâches assignées à un membre connecté
    public function getMemberTasks($user_id)
    {
        $stmt = $this->conn->prepare("
            SELECT t.id, t.title, t.status
            FROM task t
            JOIN task_assignment ta ON t.id = ta.task_id
            WHERE ta.users_id = :user_id
        ");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Mettre à jour le statut d'une tâche
    public function updateTaskStatus($task_id, $new_status)
    {
        $stmt = $this->conn->prepare("
            UPDATE task 
            SET status = :status 
            WHERE id = :task_id
        ");
        $stmt->bindParam(':status', $new_status);
        $stmt->bindParam(':task_id', $task_id);
        return $stmt->execute();
    }
}

// Vérification de la connexion utilisateur
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$dashboard = new MemberDashboard();
$user_id = $_SESSION['user_id'];

// Mise à jour du statut d'une tâche si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['task_id'], $_POST['status'])) {
    $task_id = $_POST['task_id'];
    $new_status = $_POST['status'];
    if ($dashboard->updateTaskStatus($task_id, $new_status)) {
        echo "<p class='text-green-500'>Statut mis à jour avec succès!</p>";
    } else {
        echo "<p class='text-red-500'>Erreur lors de la mise à jour.</p>";
    }
}

// Récupération des tâches du membre
$tasks = $dashboard->getMemberTasks($user_id);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord Membre</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 p-6">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-lg">
        <h1 class="text-3xl font-bold mb-4">Tableau de Bord - Membre</h1>

        <!-- Tableau des tâches -->
        <table class="table-auto w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border p-2">Titre</th>
                    <th class="border p-2">Statut</th>
                    <th class="border p-2">Modifier le Statut</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tasks as $task): ?>
                    <tr>
                        <td class="border p-2"><?= htmlspecialchars($task['title']) ?></td>
                        <td class="border p-2"><?= htmlspecialchars($task['status']) ?></td>
                        <td class="border p-2">
                            <form method="POST">
                                <input type="hidden" name="task_id" value="<?= $task['id'] ?>">
                                <select name="status" class="p-2 border">
                                    <option value="TODO" <?= $task['status'] == 'TODO' ? 'selected' : '' ?>>TODO</option>
                                    <option value="DOING" <?= $task['status'] == 'DOING' ? 'selected' : '' ?>>DOING</option>
                                    <option value="DONE" <?= $task['status'] == 'DONE' ? 'selected' : '' ?>>DONE</option>
                                </select>
                                <button type="submit" class="bg-blue-500 text-white px-4 py-2 mt-2 rounded">Mettre à
                                    jour</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    </div>
</body>

</html>