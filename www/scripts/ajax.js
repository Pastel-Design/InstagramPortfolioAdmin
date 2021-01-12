document.querySelectorAll('.edit-image-btn').forEach(item => {
    item.addEventListener('click', event => {
        editImage(item.getAttribute("image-id"))
    })
})

function editImage(imageId) {
    axios.get('/handle/editImage/' + imageId, {
        params: {
            "title": document.querySelectorAll("input[image-id='" + imageId + "']")[0].value,
            "description":document.querySelectorAll("input[image-id='" + imageId + "']")[1].value
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
    console.log(data.response)
    if(!data["response"]){
        location.reload()
        console.log("dd")
    }
}

document.querySelectorAll('.album-image').forEach(item => {
    item.addEventListener('click', event => {
        let answer = window.confirm("Set this image as cover of album?");
        if (answer) {
            setCoverPhoto(item.getAttribute("album-id"),item.getAttribute("image-id"))
        }
    })
})

function setCoverPhoto(albumId,imageId) {
    axios.get('/handle/setCoverPhoto', {
        params: {
            "imageId": imageId,
            "albumId":albumId
        }
    })
        .then(function (response) {
            document.querySelector('.cover-photo').classList.remove("cover-photo")
            document.querySelector(".image-container[image-id='" + imageId + "']").classList.add("cover-photo")
            //location.reload()
        })
        .catch(function (error) {
            console.log(error);
        })
        .then(function () {
            // always executed
        });
}