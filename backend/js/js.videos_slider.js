(function($tns) {
  let sliders = document.querySelectorAll('.videos-slider');
  sliders.forEach(function(slider){
    if(slider.dataset.numtoshow > 0) {
      $tns({
        "container": slider,
        "items": slider.dataset.numtoshow,
        "arrowKeys": true,
        "swipeAngle": false,
        "speed": 400,
        "controlsText": ["", ""],
        "nav": false,
        "gutter": 11,
        "responsive": {
          0: {
            items: 1
          },
          540: {
            items: slider.dataset.numtoshow
          }
        }
      });
    }
  });
})(tns);