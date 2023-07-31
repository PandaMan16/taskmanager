<?php 

    $dataFile = 'list.txt';
    
    $edit = -1;
    
    function lecture(){
        global $dataFile;
        $tasks = [];

        if (file_exists($dataFile)) {
            $data = file_get_contents($dataFile);
            $tasks = unserialize($data);
        }

        return $tasks;
    }

    function save($tasks){
        global $dataFile;
        $data = serialize($tasks);
        file_put_contents($dataFile, $data);
    }

    function add(){
        date_default_timezone_set('UTC');
        $dateAujourdhui = new DateTime();
        $tasks = lecture();
        $newTask = [
            'nom' => $_POST['nom'],
            'description' => nl2br(htmlspecialchars($_POST['description'])),
            'date' => $dateAujourdhui->format('Y-m-d'),
            'limit' => $_POST['limit'],
            'status' => 'En Attente',

        ];
        $tasks[] = $newTask;
        save($tasks);
        header('Location: index.php');
        exit();
    }
    function edit($id){
        $tasks = lecture();
        $tasks[$id]['nom'] = $_POST['nom'];
        $tasks[$id]['description'] = nl2br(htmlspecialchars($_POST['description']));
        $tasks[$id]['limit'] = $_POST['limit'];
        $tasks[$id]['status'] = $_POST['status'];
        
        save($tasks);
        header('Location: index.php');
        exit();
    }
    function delete($id){
        $tasks = lecture();

        unset($tasks[$id]);
        $tasks = array_values($tasks);

        save($tasks);
        header('Location: index.php');
        exit();
    }
    $tasks = lecture();
    if (isset($_POST['add'])) {
        add();
    }else if(isset($_POST['edit'])){
        $edit = $_POST['value'];
    }else if(isset($_POST['delete'])){
        delete($_POST['value']);
    }else if(isset($_POST['set'])){
        edit($_POST['value']);
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style.css">
    <title>gestion de tâches</title>
</head>
<body>
<?php if (count($tasks) > 0){
 foreach ($tasks as $taskId => $task){ 
  if($taskId != $edit){ ?>
    <div class="tache" style="--clr:'red'">
        <h3><?php echo $task['nom']; ?></h3>
        <div class="info">                    
            <p class="limit"><?php echo $task['limit']; ?></p>
            <p class="status"><?php echo $task['status']; ?></p>
        </div>
        <div>
            <p class="description"><?php echo $task['description']; ?></p>
        </div>
        <p class="date"><?php echo $task['date']; ?></p>
        <div class="removeedit">
            <form method="post"><input type="submit" name="edit" value="Edit"><input type="hidden" name="value" value="<?php echo $taskId ?>"></form>
            <form method="post"><input type="submit" name="delete" value="Delete"><input type="hidden" name="value" value="<?php echo $taskId ?>"></form>
        </div>
    </div>
<?php }else{ ?>
    <div id="editask">
        <form method="POST">
            <label for="nom">Titre de la tache</label>
            <input type="text" name="nom" value="<?php echo $task['nom']; ?>">
            <label for="status">Status</label>
            <input type="text" name="status" value="<?php echo $task['status']; ?>">
            <label for="limit">Date Limite</label>
            <input type="date" name="limit" value="<?php echo $task['limit']; ?>">
            <label for="description">Description</label>
            <textarea name="description"><?php echo $task['description']; ?></textarea>
            <input type="hidden" name="value" value="<?php echo $taskId ?>">
            <input type="submit" name="set" value="Edité">
        </form>
        <form method="post"><input type="submit" name="delete" value="Delete"><input type="hidden" name="value" value="<?php echo $taskId ?>"></form>
    </div>
<?php } } }?>
    <div id="newtask">
        <form method="POST">
            <label for="nom">Titre de la tache</label>
            <input type="text" name="nom">
            <label for="limit">Date Limite</label>
            <input type="date" name="limit">
            <label for="description">Description</label>
            <textarea name="description"></textarea>
            <input type="submit" name="add" value="Ajouter">
        </form>
    </div>
    
</body>
</html>