import * as domFunctions from "./ajaxDomFunctions";

export function editImage(imageId) {
    axios.get('/handle/editImage/' + imageId, {
        params: {
            "title": document.querySelectorAll("input[image-id='" + imageId + "']")[0].value,
            "description": document.querySelectorAll("input[image-id='" + imageId + "']")[1].value
        }
    })
        .then(function (response) {
            let data = response["data"];
            domFunctions.imageDataUpdate(data);
        })
        .catch(function (error) {
            console.log(error);
        })
        .then(function () {
            // always executed
        });
}

export function setCoverPhoto(albumId, imageId) {
    axios.get('/handle/setCoverPhoto', {
        params: {
            "imageId": imageId,
            "albumId": albumId
        }
    })
        .then(function (response) {
            domFunctions.changeCoverPhoto(imageId)
        })
        .catch(function (error) {
            console.log(error);
        })
        .then(function () {
            // always executed
        });
}

export function deleteImage(albumId, imageId) {
    axios.get('/handle/deleteImage', {
        params: {
            "imageId": imageId,
            "albumId": albumId
        }
    })
        .then(function (response) {
            domFunctions.deleteImageDiv(imageId)
            if (albumId != null) {
                let id = response.data.response;
                if (Number.isInteger(id)) {
                    domFunctions.changeCoverPhoto(id)
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

export function deleteAlbum(albumId) {
    axios.get('/handle/deleteAlbum', {
        params: {
            "albumId": albumId
        }
    })
        .then(function (response) {
            domFunctions.deleteAlbumDiv(albumId)
        })
        .catch(function (error) {
            console.log(error);
        })
        .then(function () {
            // always executed
        });
}

export function databaseReorder(reload) {
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
export function databaseAlbumReorder(reload) {
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
export function sessionLayoutToggle(value){
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