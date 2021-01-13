import axios from "axios";

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
            let id = response.data.response;
            if(Number.isInteger(id)){
                changeCoverPhoto(id)
                deleteImageDiv(imageId)
            }else{
                deleteImageDiv(imageId)
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

function deleteImage(albumId, imageId) {
    axios.get('/handle/deleteAlbum', {
        params: {
            "albumId": albumId
        }
    })
        .then(function (response) {
            let id = response.data.response;
            if(Number.isInteger(id)){
                changeCoverPhoto(id)
                deleteImageDiv(imageId)
            }else{
                deleteImageDiv(imageId)
            }

        })
        .catch(function (error) {
            console.log(error);
        })
        .then(function () {
            // always executed
        });
}