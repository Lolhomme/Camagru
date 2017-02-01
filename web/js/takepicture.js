(function() {

    /*Webcam handling*/
    var streaming = false,
        video        = document.querySelector('#video'),
        cover        = document.querySelector('#cover'),
        canvas       = document.querySelector('#canvas'),
        photo        = document.querySelector('#photo'),
        startbutton  = document.querySelector('#startbutton'),
        save         = document.querySelector('#savebutton'),
        previewCam   = document.querySelector('#previewCam'),
        formCam      = document.querySelector('#formcam'),
        width = 640,
        height = 480;

    navigator.getMedia = ( navigator.getUserMedia ||
    navigator.webkitGetUserMedia ||
    navigator.mozGetUserMedia ||
    navigator.msGetUserMedia);

    navigator.getMedia(
        {
            video: true,
            audio: false
        },
        function(stream) {
            if (navigator.mozGetUserMedia) {
                video.mozSrcObject = stream;
            } else {
                var vendorURL = window.URL || window.webkitURL;
                video.src = vendorURL.createObjectURL(stream);
            }
            video.play();
        },
        function(err) {
            console.log("An error occured! " + err);
        }
    );

    video.addEventListener('canplay', function(ev) {
        if (!streaming) {
            height = video.videoHeight / (video.videoWidth/width);
            video.setAttribute('width', width);
            video.setAttribute('height', height);
            canvas.setAttribute('width', width);
            canvas.setAttribute('height', height);
            streaming = true;
        }
    }, false);

    function takepicture() {
        canvas.width = width;
        canvas.height = height;
        canvas.getContext('2d').drawImage(video, 0, 0, width, height);
        var data = canvas.toDataURL('image/png');
        photo.setAttribute('src', data);
        document.getElementById('base-img').setAttribute('value', data);
    }

    startbutton.addEventListener('click', function(ev){
        takepicture();
        previewCam.style.display = 'block';
        save.style.display = 'block';
        preview.style.display = 'none';
        formUpl.style.display = 'none';
        ev.preventDefault();
    }, false);

    /*External picture handling */
    var preview = document.querySelector('#preview'),
        saveUpl = document.querySelector('#savebutton_UP'),
        inputFile = document.querySelector('#input-file'),
        formUpl = document.querySelector('#formUpl');

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
        formCam.style.display = 'none';
    };

    for (var i = 1; i <= document.getElementById('nbrFilters').value; i++) {
        if (i == 1)
            document.getElementById('filter' + i).style.borderColor = '#ff6800';

        document.getElementById('filter' + i).addEventListener('click', function () {

            // document.getElementById('calque').src = '../img/filters/' + this.alt + '.png';
            document.getElementById('filter-id').value = this.alt;
            document.getElementById('filter-id2').value = this.alt;
            for (var i = 1; i <= document.getElementById('nbrFilters').value; i++) {
                var target = document.getElementById('filter' + i);

                if (target.alt == this.alt) {
                    target.style.borderColor = '#ff6800';
                    formCam.style.display = 'block';
                    formUpl.style.display = 'block';
                }
                else
                    target.style.borderColor = 'transparent';
            }
        });
    }
})();
