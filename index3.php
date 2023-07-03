<?php
// Fichier de données pour stocker les tâches
$dataFile = 'tasks.txt';

// Fonction pour récupérer les tâches à partir du fichier de données
function getTasks() {
    global $dataFile;
    $tasks = [];

    if (file_exists($dataFile)) {
        $data = file_get_contents($dataFile);
        $tasks = unserialize($data);
    }

    return $tasks;
}

// Fonction pour sauvegarder les tâches dans le fichier de données
function saveTasks($tasks) {
    global $dataFile;
    $data = serialize($tasks);
    file_put_contents($dataFile, $data);
}

// Récupérer les tâches existantes
$tasks = getTasks();

// Traitement des actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ajouter une nouvelle tâche
    if (isset($_POST['add'])) {
        $newTask = [
            'name' => $_POST['name'],
            'description' => $_POST['description'],
            'deadline' => $_POST['deadline'],
            'status' => 'pending'
        ];
        $tasks[] = $newTask;
        saveTasks($tasks);
    }

    // Mettre à jour une tâche existante
    if (isset($_POST['update'])) {
        $taskId = $_POST['task_id'];
        $tasks[$taskId]['name'] = $_POST['name'];
        $tasks[$taskId]['description'] = $_POST['description'];
        $tasks[$taskId]['deadline'] = $_POST['deadline'];
        $tasks[$taskId]['status'] = $_POST['status'];
        saveTasks($tasks);
    }

    // Supprimer une tâche
    if (isset($_POST['delete'])) {
        $taskId = $_POST['task_id'];
        unset($tasks[$taskId]);
        $tasks = array_values($tasks);
        saveTasks($tasks);
    }

    // Rediriger vers la page d'accueil pour éviter la réémission du formulaire
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Système de gestion de tâches</title>
</head>
<body>
    <h1>Système de gestion de tâches</h1>

    <h2>Liste des tâches</h2>
    <?php if (count($tasks) > 0): ?>
        <ul>
            <?php foreach ($tasks as $taskId => $task): ?>
                <li>
                    <?php echo $task['name']; ?>
                    [<?php echo $task['status']; ?>]
                    <a href="edit.php?id=<?php echo $taskId; ?>">Modifier</a>
                    <form method="post" action="">
                        <input type="hidden" name="task_id" value="<?php echo $taskId; ?>">
                        <button type="submit" name="delete">Supprimer</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Aucune tâche trouvée.</p>
    <?php endif; ?>

    <h2>Ajouter une nouvelle tâche</h2>
    <form method="post" action="">
        <label>Nom :</label>
        <input type="text" name="name" required><br>

        <label>Description :</label>
        <textarea name="description" required></textarea><br>

        <label>Date limite :</label>
        <input type="date" name="deadline" required><br>

        <input type="submit" name="add" value="Ajouter">
    </form>
</body>
</html>
