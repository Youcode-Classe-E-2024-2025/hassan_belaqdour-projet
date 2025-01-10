<?php
session_start();
require_once '../config/database.php';

class Project
{
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function addProject($name, $description, $manager_id)
    {
        $stmt = $this->conn->prepare("
            INSERT INTO project (name, description, manager_id) 
            VALUES (:name, :description, :manager_id)
        ");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':manager_id', $manager_id);
        return $stmt->execute();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        header('Location: ../views/login.php');
        exit();
    }

    $project = new Project();
    $name = htmlspecialchars($_POST['name']);
    $description = htmlspecialchars($_POST['description']);
    $manager_id = $_SESSION['user_id'];

    if ($project->addProject($name, $description, $manager_id)) {
        echo "<p class='text-green-500'>Projet ajouté avec succès!</p>";
        header("Location: ../views/statistique.php");
        exit();
    } else {
        echo "<p class='text-red-500'>Erreur lors de l'ajout du projet.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Projet</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 p-10">
    <div class="max-w-lg mx-auto bg-white p-8 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-5">Ajouter un Projet</h1>

        <form method="POST">
            <label class="block mb-2">Nom du Projet :</label>
            <input type="text" name="name" required class="p-2 border rounded w-full mb-4">

            <label class="block mb-2">Description :</label>
            <textarea name="description" class="p-2 border rounded w-full mb-4"></textarea>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Ajouter</button>
            <a href="../views/index.php" class="ml-4 text-blue-600">Retour</a>
        </form>
    </div>
</body>

</html>