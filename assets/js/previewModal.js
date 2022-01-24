let oneFileModal = document.getElementById("oneFileModal");
let closeoneFileViewModal = document.getElementById("closeoneFileViewModal");
let fileLinks = document.querySelectorAll("#fileLink a")
let preview = document.getElementById("preview");

//Affiche la modale : audio preview
function displayAudioPreviewModal(title, src, fileID) {
    preview.innerHTML =
        `<figure>
      <figcaption>${title}</figcaption>
      <audio
        controls autoplay
        src="${src}">
            Your browser does not support the
            <code>audio</code> element.
      </audio>
    </figure>
    <a class="btn column" href="index.php?route=downloadOneFile&id=${fileID}"><i class="fas fa-download"></i> Telecharger le fichier</a>`;
    oneFileModal.style.display = "block";
    document.body.style.position = "fixed";
}
//Affiche la modale : audio preview
function displayImagePreviewModal(title, src, fileID) {
    preview.innerHTML =
        `<figure>
      <figcaption>${title}</figcaption>
      <img src="${src}" alt="${title}"/>
    </figure>
    <a class="btn column" href="index.php?route=downloadOneFile&id=${fileID}"><i class="fas fa-download"></i> Telecharger le fichier</a>`;
    oneFileModal.style.display = "block";
    document.body.style.position = "fixed";
}
//Affiche la modale : audio preview
function displayVideoPreviewModal(title, src, fileID) {
    preview.innerHTML = `<figure>
      <figcaption>${title}</figcaption>
      <video
        controls autoplay
        src="${src}">
            Your browser does not support the
            <code>audio</code> element.
      </video>
    </figure>
    <a class="btn column" href="index.php?route=downloadOneFile&id=${fileID}"><i class="fas fa-download"></i> Telecharger le fichier</a>`;
    oneFileModal.style.display = "block";
    document.body.style.position = "fixed";
}

//Masque la modale : file preview
function hideFilePreviewModal() {
    preview.innerHTML = "";
    oneFileModal.style.display = "none";
    document.body.style.position = "";
    addEventToAllFiles();
}

function addEventToAllFiles() {
    for (let i = 0; i < fileLinks.length; i++) {
        let title = "";
        let src = "";
        let fileID;
        let data = [];
        if (fileLinks[i].attributes[0].value === 'audio') {
            if (fileLinks[i].innerText !== "") {
                title = fileLinks[i].innerText;
            }
            else {
                title = fileLinks[i].title;
            }
            fileID = fileLinks[i].dataset.fileid;
            src = fileLinks[i].dataset.href;
            fileLinks[i].addEventListener("click", function() {
                displayAudioPreviewModal(title, src, fileID);
            });
        }
        else if (fileLinks[i].attributes[0].value === 'image') {

            if (fileLinks[i].innerText !== "") {
                title = fileLinks[i].innerText;
            }
            else {
                title = fileLinks[i].title;
            }
            fileID = fileLinks[i].dataset.fileid;
            src = fileLinks[i].dataset.href;
            fileLinks[i].addEventListener("click", function() {
                displayImagePreviewModal(title, src, fileID);
            });
        }
        else if (fileLinks[i].attributes[0].value === 'video') {
            if (fileLinks[i].innerText !== "") {
                title = fileLinks[i].innerText;
            }
            else {
                title = fileLinks[i].title;
            }
            fileID = fileLinks[i].dataset.fileid;
            src = fileLinks[i].dataset.href;
            fileLinks[i].addEventListener("click", function() {
                displayVideoPreviewModal(title, src, fileID);
            });
        }
    }
}

addEventToAllFiles();

//Ecouteurs d'evenements
closeoneFileViewModal.addEventListener("click", hideFilePreviewModal);
