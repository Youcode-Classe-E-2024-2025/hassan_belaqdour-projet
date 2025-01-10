<?php
session_start();
require_once '../config/database.php';

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$user_role = $_SESSION['role'];

$db = new Database();
$conn = $db->getConnection();

// Récupération des projets et des membres pour les assignations
$projects = $conn->query("SELECT id, name FROM project")->fetchAll(PDO::FETCH_ASSOC);
$members = $conn->query("SELECT id, name FROM users WHERE role='member'")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Tableau de Bord Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 p-10">
    <h1 class="text-3xl font-bold mb-5">Bienvenue, <?= htmlspecialchars($user_name) ?>
        (<?= htmlspecialchars($user_role) ?>)</h1>

    <!-- Bouton Ajouter un Projet -->
    <form action="add_project.php" method="POST" class="mb-4">
        <input type="text" name="project_name" placeholder="Nom du projet" required class="p-2 border rounded">
        <textarea name="description" placeholder="Description" class="p-2 border rounded"></textarea>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Ajouter Projet</button>
    </form>

    <!-- Bouton Ajouter une Tâche -->
    <form action="add_task.php" method="POST" class="mb-4">
        <input type="text" name="title" placeholder="Titre de la tâche" required class="p-2 border rounded">
        <textarea name="description" placeholder="Description" class="p-2 border rounded"></textarea>

        <select name="project_id" required class="p-2 border rounded">
            <option value="" disabled selected>Choisir un projet</option>
            <?php foreach ($projects as $project): ?>
                <option value="<?= $project['id'] ?>"><?= htmlspecialchars($project['name']) ?></option>
            <?php endforeach; ?>
        </select>

        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Ajouter Tâche</button>
    </form>

    <!-- Bouton Assigner une Tâche à un Membre -->
    <form action="assign_task.php" method="POST">
        <select name="task_id" required class="p-2 border rounded">
            <option value="" disabled selected>Choisir une tâche</option>
            <?php
            $tasks = $conn->query("SELECT id, title FROM task")->fetchAll(PDO::FETCH_ASSOC);
            foreach ($tasks as $task): ?>
                <option value="<?= $task['id'] ?>"><?= htmlspecialchars($task['title']) ?></option>
            <?php endforeach; ?>
        </select>

        <select name="user_id" required class="p-2 border rounded">
            <option value="" disabled selected>Choisir un membre</option>
            <?php foreach ($members as $member): ?>
                <option value="<?= $member['id'] ?>"><?= htmlspecialchars($member['name']) ?></option>
            <?php endforeach; ?>
        </select>

        <button type="submit" class="bg-purple-500 text-white px-4 py-2 rounded">Assigner Tâche</button>
    </form>
</body>

</html>