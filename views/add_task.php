<?php
session_start();
require_once '../config/database.php';

class Task
{
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function addTask($title, $description, $status, $category_id, $tag_id, $project_id)
    {
        $stmt = $this->conn->prepare("
            INSERT INTO task (title, description, status, category_id, tag_id, project_id)
            VALUES (:title, :description, :status, :category_id, :tag_id, :project_id)
        ");
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->bindParam(':tag_id', $tag_id);
        $stmt->bindParam(':project_id', $project_id);
        return $stmt->execute();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        header('Location: ../views/login.php');
        exit();
    }

    $task = new Task();
    $title = htmlspecialchars($_POST['title']);
    $description = htmlspecialchars($_POST['description']);
    $status = $_POST['status'];
    $category_id = $_POST['category_id'];
    $tag_id = $_POST['tag_id'];
    $project_id = $_POST['project_id'];

    if ($task->addTask($title, $description, $status, $category_id, $tag_id, $project_id)) {
        echo "<p class='text-green-500'>Tâche ajoutée avec succès!</p>";
        header("Location: ../views/statistique.php");
        exit();
    } else {
        echo "<p class='text-red-500'>Erreur lors de l'ajout de la tâche.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une Tâche</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

</html>