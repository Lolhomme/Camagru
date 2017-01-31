(function () {

    /*External picture handling */
    var preview = document.querySelector('#preview'),
        saveUpl = document.querySelector('#savebutton_UP'),
        inputFile = document.querySelector('#input-file'),
        fileToUpload = document.querySelector('#file-to-upload'),
        uploadArea = document.querySelector('#upload-area');

    inputFile.onchange = function (e) {
        e.preventDefault();

        var file = this.files[0];
        var url = URL.createObjectURL(file);
        var img = new Image(640, 480);

        img.src = url;
        img.setAttribute('crossOrigin', 'anonymous');
        img.setAttribute('id', 'image');

        preview.appendChild(img);
        preview.style.display = 'block';
        saveUpl.style.display = 'block';
        inputFile.style.display = 'none';
        fileToUpload.style.border = 'none';
        uploadArea.style.display = 'none';
    }
})();