// Declaration des variables
let deleteAllTaskBtn = document.getElementById("deleteAllTaskBtn");
let allTasksBtn = document.getElementById("allTasksButton");
let doneBtn = document.getElementById("doneButton");
let notDoneBtn = document.getElementById("notDoneButton");


//Fonctions et description

//Confimartion pour vider la liste de toutes les tàches
function confirmDeleteAllTasks() {
  if (confirm("Vous êtes sur le point de supprimer toutes vos tâches. Voulez-vous continuer ?")) {
    window.location.href = "index.php?route=todolistDeleteAllTasksForOneUser";
  }
  else {
    window.location.href = "index.php?route=todolist";
  }
}

// Affiche toutes les tàches
function showAllTasks() {
  window.location.href = "index.php?route=todolist";
}

// Affiche toutes les tàches déja faites
function showDoneTasks() {
  window.location.href = "index.php?route=todolistDone";
}

// Affiche toutes les tàches pas encore faites
function showNotDoneTasks() {
  window.location.href = "index.php?route=todolistNotDone";
}


//Ecouteurs d'evenement
allTasksBtn.addEventListener("click", showAllTasks);
doneBtn.addEventListener("click", showDoneTasks);
notDoneBtn.addEventListener("click", showNotDoneTasks);
deleteAllTaskBtn.addEventListener("click", confirmDeleteAllTasks);
