<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "flowstack";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['email']) || $_SESSION['role'] != 'Chef') {
    header("Location: login.php");
    exit();
}

$query = "SELECT user_id, nom, prenom FROM users WHERE role = 'user'";
$result = $conn->query($query);

$taches_query = "SELECT tache_id, title FROM taches WHERE statut = 'To Do'";
$taches_result = $conn->query($taches_query);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.3/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <div class="container mx-auto p-6">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-4">Tableau de Bord Administrateur</h1>
            <a href="addtask.php" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-700 mb-4">Ajouter une
                tâche</a>
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Tâches à assigner</h2>
            <?php if ($taches_result->num_rows > 0): ?>
                <ul class="space-y-4">
                    <?php while ($task = $taches_result->fetch_assoc()): ?>
                        <li class="flex justify-between items-center">
                            <span class="text-gray-800"><?php echo $task['title']; ?></span>
                            <form action="assign_task.php" method="POST" class="flex items-center">
                                <input type="hidden" name="tache_id" value="<?php echo $task['tache_id']; ?>">
                                <select name="user_id" class="p-2 border border-gray-300 rounded-md">
                                    <?php while ($user = $result->fetch_assoc()): ?>
                                        <option value="<?php echo $user['user_id']; ?>">
                                            <?php echo $user['nom'] . ' ' . $user['prenom']; ?></option>
                                    <?php endwhile; ?>
                                </select>
                                <button type="submit"
                                    class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-700 ml-2">Assigner</button>
                            </form>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p class="text-gray-600">Aucune tâche à assigner.</p>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>