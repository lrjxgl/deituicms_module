let mediaRecorder = new MediaRecorder(dest.stream);
var mediaRecorder2;
var recordUrl;

 mediaRecorder.addEventListener('dataavailable', function(e) {
     data = [e.data];
     blob = new Blob(data, { type: 'audio/mp3' });
     recordUrl = URL.createObjectURL(blob);
	 download(recordUrl)
 })
 function download(url) {
     let link = document.createElement("a");
     link.setAttribute("download", "demo.mp3");
     link.setAttribute("href", url);
     link.click();
 }
 //录制麦克风
if (navigator.mediaDevices === undefined) {
  navigator.mediaDevices = {};
}

if (navigator.mediaDevices.getUserMedia === undefined) {
  navigator.mediaDevices.getUserMedia = function(constraints) {
    var getUserMedia = navigator.webkitGetUserMedia || navigator.mozGetUserMedia;
    if (!getUserMedia) {
      return Promise.reject(new Error('getUserMedia is not implemented in this browser'));
    }

    return new Promise(function(resolve, reject) {
      getUserMedia.call(navigator, constraints, resolve, reject);
    });
  }
}
navigator.mediaDevices.getUserMedia({
       audio: true,
})
.then(function(stream){
	mediaRecorder2 = new MediaRecorder(stream);
	 
	mediaRecorder2.addEventListener('dataavailable', function(e) {
	    data = [e.data];
	    blob = new Blob(data, { type: 'audio/mp3' });
	    recordUrl = URL.createObjectURL(blob);
		 download(recordUrl)
	})
})
 