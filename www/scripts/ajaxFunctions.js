export function editImage(imageId) {
    console.log(imageId)
    axios.get('/handle/addToCart/' + productCode, {
        params: {}
    })
        .then(function (response) {
            let data = response["data"];
            updateCartInfo(data["cartInfo"]);
        })
        .catch(function (error) {
            console.log(error);
        })
        .then(function () {
            // always executed
        });
}

function imageDataUpdate(imageId) {
    console.log(imageId)
}