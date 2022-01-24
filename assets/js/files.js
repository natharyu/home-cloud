// Déclaration des variables
let addFileBtn = document.getElementById("addFileBtn");
let plusIcon = document.getElementById("plusIcon");
let addFileBtnMenu = document.getElementById("addFileBtnMenu");
let addFiles = document.getElementById("addFiles");
let createFolder = document.getElementById("createFolder");
let addFilesModal = document.getElementById("addFilesModal");
let createFolderModal = document.getElementById("createFolderModal");
let closeAddFilesModal = document.getElementById("closeAddFilesModal");
let closeCreateFolderModal = document.getElementById("closeCreateFolderModal");
let countClick = 1;

//Fonctions et description

// Affichage de la modale : ajouter ficher/Creer un dossier
function displayOrHideAddFileBtnMenu() {
  countClick++;
  if (countClick % 2 == 0) {
    requestAnimationFrame(showAddFileMenu);

    function showAddFileMenu() {
      plusIcon.animate(
        [
          { transform: "rotate(0deg)", color: "" },
          { transform: "rotate(45deg)", color: "var(--error)" },
        ],
        { duration: 215 }
      );
      setTimeout(function () {
        plusIcon.style.color = "var(--error)";
        plusIcon.style.transform = "rotate(45deg)";
      }, 200);
      addFileBtnMenu.style.display = "flex";
    }
  } else {
    requestAnimationFrame(hideAddFileMenu);

    function hideAddFileMenu() {
      plusIcon.animate(
        [
          { transform: "rotate(45deg)", color: "var(--error)" },
          { transform: "rotate(0deg)", color: "black" },
        ],
        { duration: 215 }
      );
      setTimeout(function () {
        plusIcon.style.color = "";
        plusIcon.style.transform = "rotate(0deg)";
      }, 200);
      addFileBtnMenu.style.display = "none";
    }
    countClick = 1;
  }
}

//Affiche la modale : importation de fichiers/dossier
function displayAddFilesModal() {
  addFilesModal.style.display = "block";
  document.body.style.position = "fixed";
}

//Masque la modale : importation de fichiers/dossier
function hideAddFilesModal() {
  addFilesModal.style.display = "none";
  document.body.style.position = "";
  document.querySelector(".drop-area p").innerText =
    "Glisser déposer un ou plusieurs fichiers ici ou, cliquer pour sélectionner le ou les fichiers à importer";
  document.querySelector("#errorUploadMsg").innerHTML = "";
  document.querySelector("#errorUploadMsgFolder").innerHTML = "";
}

//Affiche la modale : création de dossier
function displayCreateFolderModal() {
  createFolderModal.style.display = "block";
  document.body.style.position = "fixed";
}

//Masque la modale : création de dossier
function hideCreateFolderModal() {
  createFolderModal.style.display = "none";
  document.body.style.position = "";
}

/*================================*/
/*=======JQUERY===================*/
/*================================*/

//Confimartion pour vider la corbeille
$("#deleteFromTrash").on("click", function () {
  alert("Etes vous sur de vouloir vider la corbeille ? Cette action est irréversible !");
});

//Ajax Drop area pour upload de fichier
$(function () {
  // Drag enter
  $(".drop-area").on("dragenter", function (e) {
    e.stopPropagation();
    e.preventDefault();
    $("p").text("Déposer le(s) fichier(s)");
  });

  // Drag over
  $(".drop-area").on("dragover", function (e) {
    e.stopPropagation();
    e.preventDefault();
    $("p").text("Déposer le(s) fichier(s)");
  });

  // Drop
  $(".drop-area").on("drop", function (e) {
    e.stopPropagation();
    e.preventDefault();

    $("p").text("En cours d'importation");

    let formData = new FormData();

    let path = $("#path").val();
    let files = e.originalEvent.dataTransfer.files;
    let fileName = "file[]";

    for (let i = 0; i < files.length; i++) {
      const file = files[i];
      if (file.name !== "") {
        formData.append(fileName, file);
        formData.append("path", path);
      }
    }
    uploadData(formData);
  });

  //file
  $("#uploadfile").click(function () {
    $("#file").click();
  });
  //folder
  $("#uploadfiles").click(function () {
    $("#files").click();
  });

  //file
  $("#file").on("change", ({ target }) => {
    let formData = new FormData();

    let path = $("#path").val();
    let files = target.files;
    let fileName = "file[]";

    for (let i = 0; i < files.length; i++) {
      const file = files[i];
      if (file.name !== "") {
        formData.append(fileName, file);
        formData.append("path", path);
      }
    }

    uploadData(formData);
  });

  //file
  function uploadData(formdata) {
    $.ajax({
      xhr: function () {
        var xhr = new window.XMLHttpRequest();
        xhr.upload.addEventListener(
          "progress",
          function (evt) {
            if (evt.lengthComputable) {
              var percentComplete = parseInt((evt.loaded / evt.total) * 100);
              $(".progress-bar").width(percentComplete + "%");
              $(".progress-bar").html(percentComplete + "%");
            }
          },
          false
        );
        return xhr;
      },
      url: "index.php?route=addFile",
      type: "post",
      data: formdata,
      contentType: false,
      processData: false,
      dataType: "json",
      beforeSend: function () {
        $(".progress").show();
        $(".progress-bar").width("0%");
      },
      success: function (result) {
        if (result === "Limite atteinte") {
          $(".progress").hide();
          $("#errorUploadMsg").empty();
          $("#errorUploadMsg").append(`<span>Le nombre de fichiers est supérieur à la limite d'import autorisé</span>`);
        }
        else{
          window.location.href = "index.php?route=files&folder=" + result.id;
        }
      },
      error: function (error) {
        switch (error.responseJSON) {
          case "Le fichier est supérieur à 2 Mo":
            $(".progress").hide();
            $("#errorUploadMsg").empty();
            $("#errorUploadMsg").append(`<span>Le fichier est supérieur à la taille maximum autorisée</span>`);
            break;
          case "Une erreur est survenue":
            $(".progress").hide();
            $("#errorUploadMsg").empty();
            $("#errorUploadMsg").append(`<span>Une erreur est survenue</span>`);
            break;
          case "Les champs sont obligatoire":
            $(".progress").hide();
            $("#errorUploadMsg").empty();
            $("#errorUploadMsg").append(`<span>Les champs sont obligatoire</span>`);
            break;
        }
      },
    });
  }

  //folder
  $("#files").on("change", ({ target }) => {
    let formData = new FormData();

    let path = $("#path").val();
    let files = target.files;
    let fileName = "file[]";
    let folderName = "folder[]";

    for (let i = 0; i < files.length; i++) {
      const file = files[i];
      if (file.name !== "") {
        formData.append(fileName, file);
        formData.append(folderName, file.webkitRelativePath);
        formData.append("path", path);
      }
    }
    uploadFolder(formData);
  });
});

//folder
function uploadFolder(formdata) {
  $.ajax({
    xhr: function () {
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener(
        "progress",
        function (evt) {
          if (evt.lengthComputable) {
            var percentComplete = parseInt((evt.loaded / evt.total) * 100);
            $(".progress-bar").width(percentComplete + "%");
            $(".progress-bar").html(percentComplete + "%");
          }
        },
        false
      );
      return xhr;
    },
    url: "index.php?route=addFolder",
    type: "post",
    data: formdata,
    contentType: false,
    processData: false,
    dataType: "json",
    beforeSend: function () {
      $(".progress").show();
      $(".progress-bar").width("0%");
    },
    success: function (result) {
      if (result === "Limite atteinte") {
        $(".progress").hide();
        $("#errorUploadMsg").empty();
        $("#errorUploadMsg").append(`<span>Le nombre de fichiers est supérieur à la limite d'import autorisé</span>`);
      }
      else{
        window.location.href = "index.php?route=files&folder=" + result.id;
      }
    },
    error: function (error) {
      let position = error.responseText.search("Limite atteinte");
      if (position) {
        $(".progress").hide();
        $("#errorUploadMsgFolder").empty();
        $("#errorUploadMsgFolder").append(
          `<span>Le nombre de fichiers est supérieur à la limite d'import autorisé</span>`
        );
      }
      switch (error.responseJSON) {
        case "Le fichier est supérieur à 2 Mo":
          $(".progress").hide();
          $("#errorUploadMsgFolder").empty();
          $("#errorUploadMsgFolder").append(`<span>Un des fichiers est supérieur à la taille maximum autorisée</span>`);
          break;
        case "Une erreur est survenue":
          $(".progress").hide();
          $("#errorUploadMsgFolder").empty();
          $("#errorUploadMsgFolder").append(`<span>Une erreur est survenue</span>`);
          break;
        case "Les champs sont obligatoire":
          $(".progress").hide();
          $("#errorUploadMsgFolder").empty();
          $("#errorUploadMsgFolder").append(`<span>Les champs sont obligatoire</span>`);
          break;
      }
    },
  });
}

//AJAX Create new folder
$("#submitCreateFolder").click(function () {
  let formData = new FormData();
  let path = $("#path").val();
  let files = $("#newFolderName").val();

  formData.append("file", files);
  formData.append("path", path);

  createNewFolder(formData);
});

function createNewFolder(formdata) {
  $.ajax({
    url: "index.php?route=createFolder",
    type: "post",
    data: formdata,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (result) {
      // console.log(result);
      window.location.href = "index.php?route=files&folder=" + result.id;
    },
    error: function (error) {
      // console.log(error);
      switch (error.responseJSON) {
        case "Un dossier existe déjà sous ce nom":
          window.location.href = "index.php?route=files&error=" + error.responseJSON;
          break;
        case "Une erreur est survenue":
          window.location.href = "index.php?route=files&error=" + error.responseJSON;
          break;
        case "Les champs sont obligatoire":
          window.location.href = "index.php?route=files&error=" + error.responseJSON;
          break;
      }
    },
  });
}

//AJAX Search in files
$("#SearchInFiles").keyup(function () {
  $.ajax({
    url: "index.php?route=searchInFiles",
    type: "post",
    data: { keyword: $(this).val() },
    success: function (data) {
      let results = Object.values(JSON.parse(data));

      $("#filesSearchList").empty();

      let searching = this.data;

      if (searching == "keyword=") {
        $("#filesSearchList").hide();
      } else {
        $("#filesSearchList").show();
      }

      let sorts = results.sort((a, b) => {
        if (
          a.name.toLowerCase().indexOf(searching.toLowerCase()) > b.name.toLowerCase().indexOf(searching.toLowerCase())
        ) {
          return 1;
        } else if (
          a.name.toLowerCase().indexOf(searching.toLowerCase()) < b.name.toLowerCase().indexOf(searching.toLowerCase())
        ) {
          return -1;
        } else {
          if (a.name > b.name) {
            return 1;
          } else {
            return -1;
          }
        }
      });

      sorts.forEach((sort) => {
        if (sort.type === "dir") {
          $("#filesSearchList").append(`
            <li>
                <a href="index.php?route=files&folder=${sort.id}"><i class="fas fa-folder"></i>  ${sort.name}</a>
            </li>`);
        } else if (sort.type.substr(0, 5) == "audio") {
          $("#filesSearchList").append(`
            <li>
              <a href="${sort.path}/${sort.name}"><i class="fas fa-music"></i> ${sort.name}</a>
            </li>`);
        } else if (sort.type.substr(0, 5) == "image") {
          $("#filesSearchList").append(`
            <li>
              <a href="${sort.path}/${sort.name}"><i class="fas fa-image"></i> ${sort.name}</a>
            </li>`);
        } else if (sort.type.substr(0, 5) == "video") {
          $("#filesSearchList").append(`
            <li>
              <a href="${sort.path}/${sort.name}"><i class="fas fa-film"></i> ${sort.name}</a>
            </li>`);
        } else {
          $("#filesSearchList").append(`
            <li>
            <a href="${sort.path}/${sort.name}"><i class="fas fa-file"></i> ${sort.name}</a>
            </li>`);
        }
      });
    },
  });
});

createFolder.addEventListener("click", displayCreateFolderModal);
closeCreateFolderModal.addEventListener("click", hideCreateFolderModal);
addFiles.addEventListener("click", displayAddFilesModal);
closeAddFilesModal.addEventListener("click", hideAddFilesModal);
addFileBtn.addEventListener("click", displayOrHideAddFileBtnMenu);
