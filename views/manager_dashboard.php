<?php
session_start();

// Vérification si l'utilisateur est bien un manager
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'manager') {
    header("Location: login.php");
    exit();
}
$email = $_SESSION['email'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <title>Manager Dashboard</title>
    <script src="../script/script.js" defer></script>
</head>
<body class="bg-gray-900 text-gray-100 overflow-x-clip">
    <!-- Header Section -->
    <header class="p-4 flex justify-between items-center">
        <div class="flex items-center gap-2">
            <button id="menuButton" class="text-gray-100 text-3xl lg:hidden hover:text-gray-400" aria-label="Open Menu">
                <i class="bx bx-menu"></i>
            </button>
            <div class="flex items-center gap-2 text-teal-400 cursor-pointer">
                <i class="bx bx-infinite text-3xl"></i>
                <span class="text-xl font-semibold">Task Manager</span>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <span class="text-gray-300 font-medium">Bienvenue, <?php echo htmlspecialchars($email); ?></span>
            <img src="../src/user.png" alt="User Avatar" class="w-10 h-10 rounded-full border border-gray-600" />
        </div>
    </header>

    <div class="flex p-3 gap-4">
        <!-- Sidebar Section -->
        <aside id="sidebar" class="w-42 hidden lg:block rounded-lg bg-gray-800 p-2 py-5 fixed lg:relative lg:translate-x-0 transform -translate-x-full transition-transform duration-200 ease-in-out">
            <nav class="space-y-4">
                <a href="#" class="menu-link flex items-center space-x-3 text-gray-300 hover:bg-gray-700 p-3 rounded-md" data-page="statistique">
                    <i class="bx bx-wallet text-teal-400"></i>
                    <span>Statistique</span>
                </a>
                <a href="#" class="menu-link flex items-center space-x-3 text-gray-300 hover:bg-gray-700 p-3 rounded-md" data-page="add_project">
                    <i class="bx bx-line-chart text-teal-400"></i>
                    <span>Add Projects</span>
                </a>
                <a href="#" class="menu-link flex items-center space-x-3 text-gray-300 hover:bg-gray-700 p-3 rounded-md" data-page="add_task">
                    <i class="bx bx-wallet text-teal-400"></i>
                    <span>Add Tasks</span>
                </a>
                <a href="#" class="menu-link flex items-center space-x-3 text-gray-300 hover:bg-gray-700 p-3 rounded-md" data-page="assign_task">
                    <i class="bx bx-user text-teal-400"></i>
                    <span>Assign Task</span>
                </a>
                <a href="logout.php" class="flex items-center space-x-3 text-gray-300 hover:bg-gray-700 p-3 rounded-md">
                    <i class="bx bx-cog text-teal-400"></i>
                    <span>Logout</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main id="main-content" class="flex-1 bg-gray-900 flex gap-4 flex-col lg:flex-row ml-0 lg:ml-42">
            <section id="content" class="w-full lg:flex-1 p-4 space-y-6 bg-gray-800 flex flex-col rounded-lg">
                <!-- Le contenu dynamique des pages sera inséré ici -->
            </section>
        </main>
    </div>
</body>
</html>
