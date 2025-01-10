document.addEventListener("DOMContentLoaded", () => {
  // Attacher un événement à chaque lien du menu pour charger les pages
  document.querySelectorAll(".menu-link").forEach((link) => {
    link.addEventListener("click", (event) => {
      event.preventDefault();
      const page = link.getAttribute("data-page"); // Récupérer la page à charger
      loadPage(page); // Appeler la fonction pour charger la page
    });
  });
});

// Fonction pour charger la page
function loadPage(page) {
  const contentDiv = document.getElementById("content"); // La div qui contient le formulaire

  // Vider le contenu actuel
  contentDiv.innerHTML = "";

  // En fonction de la page à afficher
  if (page === "add_project") {
    contentDiv.innerHTML = `
            <h2 class="text-2xl text-gray-100 mb-4">Add New Project</h2>
            <form action="add_project.php" method="POST" class="space-y-4">
                <div>
                    <label for="project_name" class="block text-gray-200">Project Name</label>
                    <input type="text" id="project_name" name="project_name" class="w-full px-4 py-2 bg-gray-700 text-gray-200 border border-gray-600 rounded-md" required />
                </div>
                <div>
                    <label for="description" class="block text-gray-200">Description</label>
                    <textarea id="description" name="description" rows="4" class="w-full px-4 py-2 bg-gray-700 text-gray-200 border border-gray-600 rounded-md" required></textarea>
                </div>
                <div>
                    <label for="start_date" class="block text-gray-200">Start Date</label>
                    <input type="date" id="start_date" name="start_date" class="w-full px-4 py-2 bg-gray-700 text-gray-200 border border-gray-600 rounded-md" required />
                </div>
                <div>
                    <label for="end_date" class="block text-gray-200">End Date</label>
                    <input type="date" id="end_date" name="end_date" class="w-full px-4 py-2 bg-gray-700 text-gray-200 border border-gray-600 rounded-md" required />
                </div>
                <button type="submit" class="w-full bg-teal-400 text-white py-2 rounded-md">Add Project</button>
            </form>
        `;
  }

  if (page === "add_task") {
    contentDiv.innerHTML = `
            <h2 class="text-2xl text-gray-100 mb-4">Add New Task</h2>
            <form action="add_task.php" method="POST" class="space-y-4">
                <div>
                    <label for="task_title" class="block text-gray-200">Task Title</label>
                    <input type="text" id="task_title" name="task_title" class="w-full px-4 py-2 bg-gray-700 text-gray-200 border border-gray-600 rounded-md" required />
                </div>
                <div>
                    <label for="task_description" class="block text-gray-200">Task Description</label>
                    <textarea id="task_description" name="task_description" rows="4" class="w-full px-4 py-2 bg-gray-700 text-gray-200 border border-gray-600 rounded-md" required></textarea>
                </div>
                <div>
                    <label for="project_id" class="block text-gray-200">Select Project</label>
                    <select id="project_id" name="project_id" class="w-full px-4 py-2 bg-gray-700 text-gray-200 border border-gray-600 rounded-md">
                        <!-- Dynamically populated list of projects -->
                        <option value="1">Project 1</option>
                        <option value="2">Project 2</option>
                    </select>
                </div>
                <button type="submit" class="w-full bg-teal-400 text-white py-2 rounded-md">Add Task</button>
            </form>
        `;
  }

  if (page === "assign_task") {
    contentDiv.innerHTML = `
            <h2 class="text-2xl text-gray-100 mb-4">Assign Task to User</h2>
            <form action="assign_task.php" method="POST" class="space-y-4">
                <div>
                    <label for="task_id" class="block text-gray-200">Task ID</label>
                    <input type="number" id="task_id" name="task_id" class="w-full px-4 py-2 bg-gray-700 text-gray-200 border border-gray-600 rounded-md" required />
                </div>
                <div>
                    <label for="user_id" class="block text-gray-200">Select User</label>
                    <select id="user_id" name="user_id" class="w-full px-4 py-2 bg-gray-700 text-gray-200 border border-gray-600 rounded-md">
                        <!-- Dynamically populated list of users -->
                        <option value="1">User 1</option>
                        <option value="2">User 2</option>
                    </select>
                </div>
                <button type="submit" class="w-full bg-teal-400 text-white py-2 rounded-md">Assign Task</button>
            </form>
        `;
  }
}
