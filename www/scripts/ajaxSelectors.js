import * as functions from "./ajaxFunctions";
import * as domFunctions from "./ajaxDomFunctions";
document.querySelectorAll('.edit-image-btn').forEach(item => {
    item.addEventListener('click', event => {
        functions.editImage(item.getAttribute("image-id"))
    })
})
document.querySelectorAll('.album-image').forEach(item => {
    item.addEventListener('click', event => {
        let answer = window.confirm("Set this image as cover of album?");
        if (answer) {
            functions.setCoverPhoto(item.getAttribute("album-id"), item.getAttribute("image-id"))
        }
    })
})
document.querySelectorAll('.delete-image-btn').forEach(item => {
    item.addEventListener('click', event => {
        let answer = window.confirm("Do you want to delete this image?");
        if (answer) {
            functions.deleteImage(item.getAttribute("album-id"), item.getAttribute("image-id"))
        }
    })
})
document.querySelectorAll('.delete-album-btn').forEach(item => {
    item.addEventListener('click', event => {
        let answer = window.confirm("Do you want to delete this album?");
        if (answer) {
            functions.deleteAlbum(item.getAttribute("album-id"))
        }
    })
})
document.querySelectorAll('.reorder-up').forEach(item => {
    item.addEventListener('click', event => {
        let imageId = item.getAttribute("image-id");
        let images = Array.prototype.slice.call(document.querySelectorAll(".image-container"))
        let targetImage = document.querySelector(".image-container[image-id='" + imageId + "']")
        domFunctions.reorderUp(imageId, images, targetImage)
        functions.databaseReorder(false)
    })
})

document.querySelectorAll('.reorder-down').forEach(item => {
    item.addEventListener('click', event => {
        let imageId = item.getAttribute("image-id");
        let images = Array.prototype.slice.call(document.querySelectorAll(".image-container"))
        let targetImage = document.querySelector(".image-container[image-id='" + imageId + "']")
        domFunctions.reorderDown(imageId, images, targetImage)
        functions.databaseReorder(false)
    })
})

document.querySelectorAll('.album-reorder-up').forEach(item => {
    item.addEventListener('click', event => {
        let albumId = item.getAttribute("album-id");
        let albums = Array.prototype.slice.call(document.querySelectorAll(".album-row"))
        let targetAlbum = document.querySelector(".album-row[album-id='" + albumId + "']")
        domFunctions.reorderAlbumUp(albumId, albums, targetAlbum)
        functions.databaseAlbumReorder(false)
    })
})

document.querySelectorAll('.album-reorder-down').forEach(item => {
    item.addEventListener('click', event => {
        let albumId = item.getAttribute("album-id");
        let albums = Array.prototype.slice.call(document.querySelectorAll(".album-row"))
        let targetAlbum = document.querySelector(".album-row[album-id='" + albumId + "']")
        domFunctions.reorderAlbumDown(albumId, albums, targetAlbum)
        functions.databaseAlbumReorder(false)
    })
})