<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: addtask.php");
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

$task_title = $task_description = $due_date = $assigned_to = "";
$error_message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $task_title = $_POST['task_title'];
    $task_description = $_POST['task_description'];
    $due_date = $_POST['due_date'];
    $assigned_to = $_POST['assigned_to'];

    if (empty($task_title) || empty($task_description) || empty($due_date) || empty($assigned_to)) {
        $error_message = "Tous les champs sont requis.";
    } else {
        $query = "INSERT INTO tasks (task_title, task_description, due_date, assigned_to) 
                  VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssi", $task_title, $task_description, $due_date, $assigned_to);

        if ($stmt->execute()) {
            header("Location: dashboard.php");
            exit();
        } else {
            $error_message = "Erreur lors de l'ajout de la tâche : " . $stmt->error;
        }

        $stmt->close();
    }
}

$query = "SELECT user_id, username FROM users WHERE role = 'member'";
$members = $conn->query($query);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une tâche</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.3/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">

    <div class="container mx-auto p-6">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-4">Ajouter une nouvelle tâche</h1>

            <?php if (!empty($error_message)): ?>
                <div class="bg-red-500 text-white p-4 rounded-lg mb-4">
                    <p><?php echo $error_message; ?></p>
                </div>
            <?php endif; ?>

            <form method="POST" action="addtask.php">
                <div class="mb-4">
                    <label for="task_title" class="block text-gray-700">Titre de la tâche</label>
                    <input type="text" id="task_title" name="task_title" class="w-full px-4 py-2 border rounded-md"
                        required value="<?php echo htmlspecialchars($task_title); ?>">
                </div>

                <div class="mb-4">
                    <label for="task_description" class="block text-gray-700">Description de la tâche</label>
                    <textarea id="task_description" name="task_description" class="w-full px-4 py-2 border rounded-md"
                        required><?php echo htmlspecialchars($task_description); ?></textarea>
                </div>

                <div class="mb-4">
                    <label for="due_date" class="block text-gray-700">Date d'échéance</label>
                    <input type="date" id="due_date" name="due_date" class="w-full px-4 py-2 border rounded-md" required
                        value="<?php echo htmlspecialchars($due_date); ?>">
                </div>

                <div class="mb-4">
                    <label for="assigned_to" class="block text-gray-700">Assigner à un membre</label>
                    <select id="assigned_to" name="assigned_to" class="w-full px-4 py-2 border rounded-md" required>
                        <option value="">Sélectionner un membre</option>
                        <?php while ($member = $members->fetch_assoc()): ?>
                            <option value="<?php echo $member['user_id']; ?>" <?php if ($assigned_to == $member['user_id'])
                                   echo 'selected'; ?>>
                                <?php echo htmlspecialchars($member['username']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="mb-4">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-700">Ajouter
                        la tâche</button>
                </div>
            </form>
        </div>
    </div>

</body>

</html>