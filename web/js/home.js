(function() {

    var streaming = false,
        video        = document.querySelector('#video'),
        cover        = document.querySelector('#cover'),
        canvas       = document.querySelector('#canvas'),
        photo        = document.querySelector('#photo'),
        startbutton  = document.querySelector('#startbutton'),
        width = 320,
        height = 0;

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

    video.addEventListener('canplay', function(ev){
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
    }

    startbutton.addEventListener('click', function(ev){
        takepicture();
        ev.preventDefault();
    }, false);

    /*function handleFiles(input) {
        var canvas_file = document.getElementById('canvasfile');
        var ctx = canvas_file.getContext('2d');
        var img = new Image();
        var file = input.files[0];
        var reader  = new FileReader();

        img.onload = function() {
            canvas_file.width = img.width;
            canvas_file.height = img.height;
            ctx.drawImage(img, 0, 0);
        }
        reader.onloadend = function () {
            img.src = reader.result;
        }
        reader.readAsDataURL(file);
    }*/
})();

function hiddenbutton(){
    var button = document.getElementById('savebutton');
    if (button.style.display == 'none')
        button.style.display = 'block';
    else
        button.style.display = 'none';
}