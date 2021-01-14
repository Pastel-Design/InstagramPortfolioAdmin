document.querySelectorAll('.edit-image-btn').forEach(item => {
    item.addEventListener('click', event => {
        editImage(item.getAttribute("image-id"))
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

function imageDataUpdate(data) {
    if (!data["response"]) {
        location.reload()
    }
}

document.querySelectorAll('.album-image').forEach(item => {
    item.addEventListener('click', event => {
        let answer = window.confirm("Set this image as cover of album?");
        if (answer) {
            setCoverPhoto(item.getAttribute("album-id"), item.getAttribute("image-id"))
        }
    })
})

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

function changeCoverPhoto(imageId) {
    document.querySelector('.cover-photo').classList.remove("cover-photo")
    document.querySelector(".image-container[image-id='" + imageId + "']").classList.add("cover-photo")
}

document.querySelectorAll('.delete-image-btn').forEach(item => {
    item.addEventListener('click', event => {
        let answer = window.confirm("Do you want to delete this image?");
        if (answer) {
            deleteImage(item.getAttribute("album-id"), item.getAttribute("image-id"))
        }
    })
})

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

function deleteImageDiv(imageId) {
    document.querySelector(".image-container[image-id='" + imageId + "']").remove()
}

document.querySelectorAll('.delete-album-btn').forEach(item => {
    item.addEventListener('click', event => {
        let answer = window.confirm("Do you want to delete this album?");
        if (answer) {
            deleteAlbum(item.getAttribute("album-id"))
        }
    })
})

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

function deleteAlbumDiv(albumId) {
    document.querySelector("tr[album-id='" + albumId + "']").remove()
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

function reorderUp(imageId, images, targetImage) {
    let container = "#images-container"
    if (targetImage !== images[0]) {
        for (let i = 0; i < images.length; i++) {
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
    renderImages(images, container)
    renderImages(imagesIg, containerIg)
}

function reorderDown(imageId, images, targetImage) {
    let container = "#images-container"
    let j = images.length - 1
    if (targetImage !== images[j]) {
        for (let i = 0; i < images.length; i++) {
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
    renderImages(images, container)
    renderImages(imagesIg, containerIg)
}

function renderImages(images, container) {
    const list = document.querySelector(container)
    images.forEach(image => {
        list.appendChild(image)
    })
}