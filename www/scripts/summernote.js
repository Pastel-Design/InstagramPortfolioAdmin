$(window).ready(function () {
    $('#summernote').summernote({
        height: 300,
        maxHeight: 400,
        minHeight: 300,
        maxWidth: 500,
        toolbar: [
            ['para', ['paragraph']],
        ]
    });
});