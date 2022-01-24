//Declaration des variables
let headerAccountBtn = document.getElementById("headerAccount");
let headerAccountMenu = document.getElementById("headerAccountMenu");
let headerAccountArrow = document.getElementById("headerAccountArrow");
let DeleteMyAccount = document.getElementById("DeleteMyAccount");
let countClickHeader = 1;


function showHeaderAccountMenu() {
    headerAccountArrow.animate([{ transform: "rotate(90deg)" }, { transform: "rotate(0deg)" }], { duration: 200 });
    setTimeout(function() {
        headerAccountArrow.style.transform = "rotate(0deg)"
    }, 185);
    headerAccountMenu.style.display = "flex";
    headerAccountMenu.animate([{ transform: "rotateX(-100deg)", opacity: "0" }, { transform: "rotateX(0deg)", opacity: "1" }], { duration: 200 });
}

function hideHeaderAccountMenu() {
    headerAccountArrow.animate([{ transform: "rotate(0deg)" }, { transform: "rotate(90deg)" }], { duration: 200 });
    headerAccountMenu.animate([{ transform: "rotateX(0deg)", opacity: "1" }, { transform: "rotateX(-80deg)", opacity: "0" }], { duration: 215 });
    setTimeout(function() {
        headerAccountArrow.style.transform = "rotate(90deg)";
        headerAccountMenu.style.display = "none";
    }, 185)
}

// Affichage de la modale : menu utilisateur
function displayOrHideHeaderAccountMenu() {
    countClickHeader++;
    if (countClickHeader % 2 == 0) {
        requestAnimationFrame(showHeaderAccountMenu);
    }
    else {
        requestAnimationFrame(hideHeaderAccountMenu);
        countClickHeader = 1;
    }
}

// File delete on files view
$("#submitMoveToTrash").click(function() {
    $("#submitMoveToTrashHidden").click();
});

// File restore on trash view
$("#submitRestoreFromTrash").click(function() {
    $("#submitRestoreFromTrashHidden").click();
});

//Select All chexkboxes
$("#selectAllFiles").click(function() {
    $("input[type=checkbox]").prop("checked", $(this).prop("checked"));
});
$("input[type=checkbox]").click(function() {
    if (!$(this).prop("checked")) {
        $("#selectAllFiles").prop("checked", false);
    }
});


$("#DeleteMyAccount").on("click", function() {
    if (confirm("Etes vous sur de vouloir supprimer votre compte ? Cette action est irréversible !")) {
        window.location.href = "index.php?route=deleteMyAccount&id=" + $("#DeleteMyAccount")[0].dataset.user;
    }
});

//Ecouteur d'evenements
headerAccountBtn.addEventListener("click", displayOrHideHeaderAccountMenu);
