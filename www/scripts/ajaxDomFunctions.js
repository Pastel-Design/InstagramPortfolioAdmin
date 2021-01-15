export function reorderAlbumUp(albumId, albums, targetAlbum) {
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

export function reorderAlbumDown(albumId, albums, targetAlbum) {
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

export function imageDataUpdate(data) {
    if (!data["response"]) {
        location.reload()
    }
}


export function changeCoverPhoto(imageId) {
    document.querySelector('.cover-photo').classList.remove("cover-photo")
    document.querySelector(".image-container[image-id='" + imageId + "']").classList.add("cover-photo")
}


export function deleteImageDiv(imageId) {
    document.querySelector(".image-container[image-id='" + imageId + "']").remove()
}


export function deleteAlbumDiv(albumId) {
    document.querySelector("tr[album-id='" + albumId + "']").remove()
}


export function reorderUp(imageId, images, targetImage) {
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

export function reorderDown(imageId, images, targetImage) {
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

