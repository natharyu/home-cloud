
<section id="todolist" class="todolist">
    <article class="mainContent">
        <h3 class="column">Todolist</h3>
        <div class="flexTodolist">
            <div class="columnTodolist todolistLeftSide">
                <h4>Ajouter une tache</h4>

                <?php if( isset( $_GET['error'] ) ) : ?>
                    <p class="error"><?= htmlspecialchars($_GET['error']) ?></p>
                <?php endif; ?>

                <form action="index.php?route=todolistAddTask" method="POST">
                    <input type="text" name="task" id="inputTask" placeholder="Nouvelle tache" />
                    <button class="btn"><i class="fas fa-plus"></i> Ajouter cette tache</button>
                </form>

            </div>
            <div class="columnTodolist todolistRightSide">
                <h4>Liste des tâches</h4>
                <div>
                    <button class="btn" id="allTasksButton">Toutes les tâches</button>
                    <button class="btn-warning" id="notDoneButton">A faire</button>
                    <button class="btn-success" id="doneButton">Déjà fait</button>
                </div>
                
                <button class="btn-danger" id="deleteAllTaskBtn">
                    <i class="fas fa-trash-alt"></i> Supprimer toutes les taches
                </button>
                <ul>
                    <?php foreach($tasks as $task) : ?>
                    <li>
                        <form class="taskName" method="POST" action="index.php?route=todolistModifyTaskDoneStatus">
                            <button type="submit">
                                <input type="hidden" name="route" value="<?= htmlspecialchars($_GET['route']) ?>">
                                <input type="hidden" name="id" value="<?= htmlspecialchars($task['id']) ?>">
                                <span <?php if (htmlspecialchars($task['done']) == true) { echo 'class="doneTask"'; } ?>><?= htmlspecialchars($task['task']) ?></span>
                            </button>
                        </form>

                        <form method="POST" action="index.php?route=todolistDeleteOneTask">
                            <input type="hidden" name="route" value="<?= htmlspecialchars($_GET['route']) ?>">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($task['id']) ?>">    
                            <button type="submit" class="btn-danger"><i class="fas fa-trash-alt"></i></button>
                        </form>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </article>
</section>