(function () {

    /*External picture handling */
    var preview = document.querySelector('#preview'),
        saveUpl = document.querySelector('#savebutton_UP'),
        inputFile = document.querySelector('#input-file'),
        fileToUpload = document.querySelector('#file-to-upload'),
        uploadArea = document.querySelector('#formUpl');

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
    };

    for (var i = 1; i <= document.getElementById('nbrFilters').value; i++) {
        if (i == 1)
            document.getElementById('filter' + i).style.borderColor = '#ff6800';

        document.getElementById('filter' + i).addEventListener('click', function () {
            // console.log('filter' + this.alt);

            // document.getElementById('calque').src = '../img/filters/' + this.alt + '.png';
            document.getElementById('filter-id').value = this.alt;
            for (var i = 1; i <= document.getElementById('nbrFilters').value; i++) {
                var target = document.getElementById('filter' + i);

                if (target.alt == this.alt) {
                    target.style.borderColor = '#ff6800';
                    startbutton.style.display = 'block';
                }
                else
                    target.style.borderColor = 'transparent';
            }
        });
    }
})();