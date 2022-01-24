// Declaration des variables
let headerDashboard = document.getElementById("headerDashboard");
let arrowDashboardMenu = document.getElementById("arrowDashboardMenu");
let overview = document.getElementById("overview");
let users = document.getElementById("users");
let userDetails = document.getElementById("userDetails");
let userModify = document.getElementById("userModify");
let dashboardSystem = document.getElementById("dashboardSystem");
let firstMenu = document.getElementById("firstMenu");
let secondMenu = document.getElementById("secondMenu");
let thirdMenu = document.getElementById("thirdMenu");
let fourthMenu = document.getElementById("fourthMenu");
let firstLink = document.getElementById("firstLink");
let secondLink = document.getElementById("secondLink");
let thirdLink = document.getElementById("thirdLink");
let fourthLink = document.getElementById("fourthLink");
let logo = document.querySelector("svg");
let title = document.getElementById("headerDashboardTitle");
let dashboardMenu = true


//Fonctions et description

//Confimation suppression utilisateur via dashboard
$("#dashboardDeleteOneUser").on("click", function() {
  let id = $("#userId").val();
  if (confirm("Etes vous sur de vouloir supprimer cet utilisateur ? Cette action est irréversible !") == true) {
    window.location.href = "index.php?route=deleteOneUserFromDashboard&id=" + id;
  }
  else {
    window.location.href = "index.php?route=adminPanelUserDetails&id=" + id;
  }
});

//menu dashboard animation quand on le cache
function hideHeaderDashboard() {
  headerDashboard.animate([{ transform: "translateX(0rem)" }, { transform: "translateX(-9rem)" }], { duration: 500 });
  switch (window.location.href) {
    case "https://home-cloud.dew-hub.ovh/index.php?route=adminPanel":
      overview.animate([{ maxWidth: "80%" }, { maxWidth: "90%" }], { duration: 500 });
      break;
    case "https://home-cloud.dew-hub.ovh/index.php?route=adminPanelUsers":
      users.animate([{ maxWidth: "80%" }, { maxWidth: "90%" }], { duration: 500 });
      break;
    case "https://home-cloud.dew-hub.ovh/index.php?route=adminPanelSystem":
      dashboardSystem.animate([{ maxWidth: "80%" }, { maxWidth: "90%" }], { duration: 500 });
      break;
  }
  arrowDashboardMenu.animate([{ transform: "rotateY(180deg)" }], { duration: 500 });
  firstMenu.animate([{ transform: "scale(1)" }, { transform: "scale(0)" }], { duration: 500 });
  secondMenu.animate([{ transform: "scale(1)" }, { transform: "scale(0)" }], { duration: 500 });
  thirdMenu.animate([{ transform: "scale(1)" }, { transform: "scale(0)" }], { duration: 500 });
  fourthMenu.animate([{ transform: "scale(1)" }, { transform: "scale(0)" }], { duration: 500 });
  firstLink.animate([{ width: "80%" }, { width: "45%" }, { margin: "0.5rem" }, { margin: "1.5rem auto" }], {
    duration: 500,
  });
  secondLink.animate([{ width: "80%" }, { width: "45%" }, { margin: "0.5rem" }, { margin: "1.5rem auto" }], {
    duration: 500,
  });
  thirdLink.animate([{ width: "80%" }, { width: "45%" }, { margin: "0.5rem" }, { margin: "1.5rem auto" }], {
    duration: 500,
  });
  fourthLink.animate([{ width: "80%" }, { width: "45%" }, { margin: "0.5rem" }, { margin: "1.5rem auto" }], {
    duration: 500,
  });
  title.animate([{ transform: "scale(1)" }, { transform: "scale(0)" }], { duration: 500 });
  logo.animate([{ transform: "scale(1)" }, { transform: "scale(0.5)" }], { duration: 500 });
  setTimeout(function() {
    document.querySelector(".arrowDashboardMenu").style.left = "3rem";
    headerDashboard.style.width = "3rem";
    arrowDashboardMenu.style.transform = "rotateY(180deg)";
    switch (window.location.href) {
      case "https://home-cloud.dew-hub.ovh/index.php?route=adminPanel":
        overview.style.maxWidth = "90%";
        break;
      case "https://home-cloud.dew-hub.ovh/index.php?route=adminPanelUsers":
        users.style.maxWidth = "90%";
        break;
      case "https://home-cloud.dew-hub.ovh/index.php?route=adminPanelSystem":
        dashboardSystem.style.maxWidth = "90%";
        break;
    }
    firstMenu.style.display = "none";
    secondMenu.style.display = "none";
    thirdMenu.style.display = "none";
    fourthMenu.style.display = "none";
    title.style.display = "none";
    logo.style.width = "90%";
    firstLink.style.width = "45%";
    firstLink.style.margin = "1.5rem auto";
    secondLink.style.width = "45%";
    secondLink.style.margin = "1.5rem auto";
    thirdLink.style.width = "45%";
    thirdLink.style.margin = "1.5rem auto";
    fourthLink.style.width = "45%";
    fourthLink.style.margin = "1.5rem auto";
  }, 475);

  return (dashboardMenu = false);
}

//menu dashboard animation quand on l'affiche
function displayHeaderDashboard() {
  headerDashboard.style.width = "12rem";
  headerDashboard.animate([{ transform: "translateX(-9rem)" }, { transform: "translateX(0rem)" }], { duration: 500 });
  document.querySelector(".arrowDashboardMenu").style.left = "12rem";
  arrowDashboardMenu.animate([{ transform: "rotateY(0deg)" }], { duration: 500 });
  switch (window.location.href) {
    case "https://home-cloud.dew-hub.ovh/index.php?route=adminPanel":
      overview.animate([{ maxWidth: "90%" }, { maxWidth: "80%" }], { duration: 500 });
      break;
    case "https://home-cloud.dew-hub.ovh/index.php?route=adminPanelUsers":
      users.animate([{ maxWidth: "90%" }, { maxWidth: "80%" }], { duration: 500 });
      break;
    case "https://home-cloud.dew-hub.ovh/index.php?route=adminPanelSystem":
      dashboardSystem.animate([{ maxWidth: "90%" }, { maxWidth: "80%" }], { duration: 500 });
      break;
  }
  firstMenu.style.display = "";
  secondMenu.style.display = "";
  thirdMenu.style.display = "";
  fourthMenu.style.display = "";
  title.style.display = "";
  logo.style.width = "";
  firstLink.style.width = "";
  firstLink.style.margin = "";
  secondLink.style.width = "";
  secondLink.style.margin = "";
  thirdLink.style.width = "";
  thirdLink.style.margin = "";
  fourthLink.style.width = "";
  fourthLink.style.margin = "";
  firstMenu.animate([{ transform: "scale(0)" }, { transform: "scale(1)" }], { duration: 500 });
  secondMenu.animate([{ transform: "scale(0)" }, { transform: "scale(1)" }], { duration: 500 });
  thirdMenu.animate([{ transform: "scale(0)" }, { transform: "scale(1)" }], { duration: 500 });
  fourthMenu.animate([{ transform: "scale(0)" }, { transform: "scale(1)" }], { duration: 500 });
  firstLink.animate([{ width: "45%" }, { width: "80%" }, { margin: "1.5rem auto" }, { margin: "0.5rem" }], {
    duration: 500,
  });
  secondLink.animate([{ width: "45%" }, { width: "80%" }, { margin: "1.5rem auto" }, { margin: "0.5rem" }], {
    duration: 500,
  });
  thirdLink.animate([{ width: "45%" }, { width: "80%" }, { margin: "1.5rem auto" }, { margin: "0.5rem" }], {
    duration: 500,
  });
  fourthLink.animate([{ width: "45%" }, { width: "80%" }, { margin: "1.5rem auto" }, { margin: "0.5rem" }], {
    duration: 500,
  });
  title.animate([{ transform: "scale(0)" }, { transform: "scale(1)" }], { duration: 500 });
  logo.animate([{ transform: "scale(0.5)" }, { transform: "scale(1)" }], { duration: 500 });
  setTimeout(function() {
    arrowDashboardMenu.style.transform = "rotateY(0deg)";
    switch (window.location.href) {
      case "https://home-cloud.dew-hub.ovh/index.php?route=adminPanel":
        overview.style.maxWidth = "80%";
        break;
      case "https://home-cloud.dew-hub.ovh/index.php?route=adminPanelUsers":
        users.style.maxWidth = "80%";
        break;
      case "https://home-cloud.dew-hub.ovh/index.php?route=adminPanelSystem":
        dashboardSystem.style.maxWidth = "80%";
        break;
    }
  }, 475);

  return (dashboardMenu = true);
}


//Ecouteurs d'evenement
arrowDashboardMenu.addEventListener("click", function() {
  if (dashboardMenu === true) {
    requestAnimationFrame(hideHeaderDashboard);
  }
  else {
    requestAnimationFrame(displayHeaderDashboard);
  }
});
