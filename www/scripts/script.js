document.querySelectorAll('.edit-image-btn').forEach(item => {
    item.addEventListener('click', event => {
        editImage(item.getAttribute("image-id"))
    })
})
document.querySelectorAll('.album-image').forEach(item => {
    item.addEventListener('click', event => {
        let answer = window.confirm("Set this image as cover of album?");
        if (answer) {
            setCoverPhoto(item.getAttribute("album-id"), item.getAttribute("image-id"))
        }
    })
})
document.querySelectorAll('.delete-image-btn').forEach(item => {
    item.addEventListener('click', event => {
        let answer = window.confirm("Do you want to delete this image?");
        if (answer) {
            deleteImage(item.getAttribute("album-id"), item.getAttribute("image-id"))
        }
    })
})
document.querySelectorAll('.delete-album-btn').forEach(item => {
    item.addEventListener('click', event => {
        let answer = window.confirm("Do you want to delete this album?");
        if (answer) {
            deleteAlbum(item.getAttribute("album-id"))
        }
    })
})
document.querySelectorAll('.reorder-up').forEach(item => {
    item.addEventListener('click', event => {
        let imageId = item.getAttribute("image-id");
        let images = Array.prototype.slice.call(document.querySelectorAll(".image-container"))
        let targetImage = document.querySelector(".image-container[image-id='" + imageId + "']")
        reorderUp(imageId, images, targetImage)
        databaseReorder(false)
    })
})

document.querySelectorAll('.reorder-down').forEach(item => {
    item.addEventListener('click', event => {
        let imageId = item.getAttribute("image-id");
        let images = Array.prototype.slice.call(document.querySelectorAll(".image-container"))
        let targetImage = document.querySelector(".image-container[image-id='" + imageId + "']")
        reorderDown(imageId, images, targetImage)
        databaseReorder(false)
    })
})

document.querySelectorAll('.album-reorder-up').forEach(item => {
    item.addEventListener('click', event => {
        let albumId = item.getAttribute("album-id");
        let albums = Array.prototype.slice.call(document.querySelectorAll(".album-row"))
        let targetAlbum = document.querySelector(".album-row[album-id='" + albumId + "']")
        reorderAlbumUp(albumId, albums, targetAlbum)
        databaseAlbumReorder(false)
    })
})

document.querySelectorAll('.album-reorder-down').forEach(item => {
    item.addEventListener('click', event => {
        let albumId = item.getAttribute("album-id");
        let albums = Array.prototype.slice.call(document.querySelectorAll(".album-row"))
        let targetAlbum = document.querySelector(".album-row[album-id='" + albumId + "']")
        reorderAlbumDown(albumId, albums, targetAlbum)
        databaseAlbumReorder(false)
    })
})

function editImage(imageId) {
    axios.get('/handle/editImage/' + imageId, {
        params: {
            "title": document.querySelectorAll("input[image-id='" + imageId + "']")[0].value,
            "description": document.querySelectorAll("input[image-id='" + imageId + "']")[1].value
        }
    })
        .then(function (response) {
            let data = response["data"];
            imageDataUpdate(data);
        })
        .catch(function (error) {
            console.log(error);
        })
        .then(function () {
            // always executed
        });
}

function setCoverPhoto(albumId, imageId) {
    axios.get('/handle/setCoverPhoto', {
        params: {
            "imageId": imageId,
            "albumId": albumId
        }
    })
        .then(function (response) {
            changeCoverPhoto(imageId)
        })
        .catch(function (error) {
            console.log(error);
        })
        .then(function () {
            // always executed
        });
}

function deleteImage(albumId, imageId) {
    axios.get('/handle/deleteImage', {
        params: {
            "imageId": imageId,
            "albumId": albumId
        }
    })
        .then(function (response) {
            deleteImageDiv(imageId)
            if (albumId != null) {
                let id = response.data.response;
                if (Number.isInteger(id)) {
                    changeCoverPhoto(id)
                }
                databaseReorder(true)
            } else {
                databaseReorder(false)
            }
        })
        .catch(function (error) {
            console.log(error);
        })
        .then(function () {
            // always executed
        });
}

function deleteAlbum(albumId) {
    axios.get('/handle/deleteAlbum', {
        params: {
            "albumId": albumId
        }
    })
        .then(function (response) {
            deleteAlbumDiv(albumId)
        })
        .catch(function (error) {
            console.log(error);
        })
        .then(function () {
            // always executed
        });
}

function databaseReorder(reload) {
    let imagesOrder = []
    let i = 1;
    document.querySelectorAll(".image").forEach(item => {
        imagesOrder.push(i++, item.getAttribute("image-id"))
    })
    axios.get('/handle/reorderAlbum', {
        params: {
            "imagesOrder": imagesOrder
        }
    })
        .then(function (response) {
            if (!response["response"]) {
                if (reload) {
                    location.reload()
                }
            }
        })
        .catch(function (error) {
            console.log(error);
        })
        .then(function () {
            // always executed
        });
}
function databaseAlbumReorder(reload) {
    let albumsOrder = []
    let i = 1;
    document.querySelectorAll(".album-row").forEach(item => {
        albumsOrder.push(i++, item.getAttribute("album-id"))
    })
    axios.get('/handle/reorderAlbums', {
        params: {
            "albumsOrder": albumsOrder
        }
    })
        .then(function (response) {
            if (!response["response"]) {
                if (reload) {
                    location.reload()
                }
            }
        })
        .catch(function (error) {
            console.log(error);
        })
        .then(function () {
            // always executed
        });
}
function sessionLayoutToggle(value){
    axios.get('/handle/sessionLayoutToggle', {
        params: {
            "value": value
        }
    })
        .then(function (response) {

        })
        .catch(function (error) {
            console.log(error);
        })
        .then(function () {
            // always executed
        });
}

function reorderAlbumUp(albumId, albums, targetAlbum) {
    let container = "#albums-table-container"
    let i
    if (targetAlbum !== albums[0]) {
        for (i = 0; i < albums.length; i++) {
            if (targetAlbum === albums[i]) {
                albums[i] = albums[(i - 1)];
                albums[(i - 1)] = targetAlbum;
                break;
            }
        }
    }
    renderElements(albums, container)
}

function reorderAlbumDown(albumId, albums, targetAlbum) {
    let container = "#albums-table-container"
    let j = albums.length - 1
    let i;
    if (targetAlbum !== albums[j]) {
        for (i = 0; i < albums.length; i++) {
            if (targetAlbum === albums[i]) {
                albums[i] = albums[(i + 1)];
                albums[(i + 1)] = targetAlbum;
                break;
            }
        }
    }
    renderElements(albums, container)
}

function imageDataUpdate(data) {
    if (!data["response"]) {
        location.reload()
    }
}


function changeCoverPhoto(imageId) {
    document.querySelector('.cover-photo').classList.remove("cover-photo")
    document.querySelector(".image-container[image-id='" + imageId + "']").classList.add("cover-photo")
}


function deleteImageDiv(imageId) {
    document.querySelector(".image-container[image-id='" + imageId + "']").remove()
}


function deleteAlbumDiv(albumId) {
    document.querySelector("tr[album-id='" + albumId + "']").remove()
}


function reorderUp(imageId, images, targetImage) {
    let container = "#images-container"
    let i
    if (targetImage !== images[0]) {
        for (i = 0; i < images.length; i++) {
            if (targetImage === images[i]) {
                images[i] = images[(i - 1)];
                images[(i - 1)] = targetImage;
                break;
            }
        }
    }
    let containerIg = "#instagram-container"
    let targetImageIg = document.querySelector(".grid-image[image-id='" + imageId + "']")
    let imagesIg = Array.prototype.slice.call(document.querySelectorAll(".grid-image"))
    if (targetImageIg !== imagesIg[0]) {
        for (i = 0; i < imagesIg.length; i++) {
            if (targetImageIg === imagesIg[i]) {
                imagesIg[i] = imagesIg[(i - 1)];
                imagesIg[(i - 1)] = targetImageIg;
                break;
            }
        }
    }
    renderElements(images, container)
    renderElements(imagesIg, containerIg)
}

function reorderDown(imageId, images, targetImage) {
    let container = "#images-container"
    let j = images.length - 1
    let i;
    if (targetImage !== images[j]) {
        for (i = 0; i < images.length; i++) {
            if (targetImage === images[i]) {
                images[i] = images[(i + 1)];
                images[(i + 1)] = targetImage;
                break;
            }
        }
    }
    let containerIg = "#instagram-container"
    let targetImageIg = document.querySelector(".grid-image[image-id='" + imageId + "']")
    let imagesIg = Array.prototype.slice.call(document.querySelectorAll(".grid-image"))
    j = imagesIg.length - 1
    if (targetImageIg !== imagesIg[j]) {
        for (i = 0; i < imagesIg.length; i++) {
            if (targetImageIg === imagesIg[i]) {
                imagesIg[i] = imagesIg[(i + 1)];
                imagesIg[(i + 1)] = targetImageIg;
                break;
            }
        }
    }
    renderElements(images, container)
    renderElements(imagesIg, containerIg)
}

function renderElements(images, container) {
    const list = document.querySelector(container)
    images.forEach(image => {
        list.appendChild(image)
    })
}


window.onload = () => {
    let layoutToggle = document.getElementById("darkmodeToggleButton");
    if (layoutToggle) {
        layoutToggle.addEventListener("click", setLayout);
        let isIg = (layoutToggle.getAttribute("toggle")==="true");

        function switchLayout() {
            let instagramContainer = document.querySelector(".instagram-container")
            instagramContainer.classList.toggle("hidden")
            let defaultContainer = document.querySelector(".default-container")
            defaultContainer.classList.toggle("hidden")
            let newImageFormContainer = document.querySelector(".new-image-form-container")
            if (newImageFormContainer) {
                newImageFormContainer.classList.toggle("hidden")
            }
            let albumFormsContainer = document.querySelector(".album-forms-container")
            if (albumFormsContainer) {
                albumFormsContainer.classList.toggle("hidden")
            }
        }

        function setLayout() {
            if (!isIg) {
                layoutToggle.classList.replace("fa-toggle-off", "fa-toggle-on");
                sessionLayoutToggle(true);
                isIg = true;
                switchLayout()
            } else {
                layoutToggle.classList.replace("fa-toggle-on", "fa-toggle-off");
                sessionLayoutToggle(false);
                isIg = false;
                switchLayout()
            }
        }
    }
}