document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(".menu-link").forEach((link) => {
    link.addEventListener("click", (event) => {
      event.preventDefault();
      const page = link.getAttribute("data-page");
      loadPage(page);
    });
  });
});

function loadPage(page) {
  const contentDiv = document.getElementById("content");

  contentDiv.innerHTML = "";

  if (page === "add_project") {
    contentDiv.innerHTML = `
        <h2 class="text-2xl text-gray-100 mb-4">Add New Project</h2>
        <form action="add_project.php" method="POST" class="space-y-4">
            <div>
                <label for="name" class="block text-gray-200">Project Name</label>
                <input type="text" id="name" name="name" class="w-full px-4 py-2 bg-gray-700 text-gray-200 border border-gray-600 rounded-md" required />
            </div>
            <div>
                <label for="description" class="block text-gray-200">Description</label>
                <textarea id="description" name="description" rows="4" class="w-full px-4 py-2 bg-gray-700 text-gray-200 border border-gray-600 rounded-md" required></textarea>
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
                <input type="text" id="task_title" name="title" class="w-full px-4 py-2 bg-gray-700 text-gray-200 border border-gray-600 rounded-md" required />
            </div>
            <div>
                <label for="task_description" class="block text-gray-200">Task Description</label>
                <textarea id="task_description" name="description" rows="4" class="w-full px-4 py-2 bg-gray-700 text-gray-200 border border-gray-600 rounded-md" required></textarea>
            </div>
            <div>
                <label for="status" class="block text-gray-200">Status</label>
                <select id="status" name="status" class="w-full px-4 py-2 bg-gray-700 text-gray-200 border border-gray-600 rounded-md">
                    <option value="TODO">TODO</option>
                    <option value="DOING">DOING</option>
                    <option value="DONE">DONE</option>
                </select>
            </div>
            <div>
                <label for="category_id" class="block text-gray-200">Category ID</label>
                <input type="number" id="category_id" name="category_id" class="w-full px-4 py-2 bg-gray-700 text-gray-200 border border-gray-600 rounded-md" required />
            </div>
            <div>
                <label for="tag_id" class="block text-gray-200">Tag ID</label>
                <input type="number" id="tag_id" name="tag_id" class="w-full px-4 py-2 bg-gray-700 text-gray-200 border border-gray-600 rounded-md" required />
            </div>
            <div>
                <label for="project_id" class="block text-gray-200">Project ID</label>
                <input type="number" id="project_id" name="project_id" class="w-full px-4 py-2 bg-gray-700 text-gray-200 border border-gray-600 rounded-md" required />
            </div>
            <button type="submit" class="w-full bg-teal-400 text-white py-2 rounded-md">Add Task</button>

            <a href="assign_task.php">assign task</a>
        </form>
    `;
  }


}
