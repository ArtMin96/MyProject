<script>
    $(document).ready(function() {
      let locale = '{{app()->getLocale()}}';
      let about_us_path =  '';
      if(locale == 'am') {
        about_us_path = '/assets/json_animation/about_us.json';
      }
      if(locale == 'ru') {
        about_us_path =  '';
      }
      if(locale == 'en') {
        about_us_path =  '';
      }
      about_us_path = '/assets/json_animation/about_us.json';
  
  let about_us = lottie.loadAnimation({
      container: document.getElementById('about_us'),
      renderer: "svg",
      loop: false,
      autoplay: false,
      path: about_us_path
    });
  
    $('#about_us').hover(function() {
        about_us.setDirection(1);
        about_us.play();
    },
    function(){
        //about_us.setDirection(-1);
        about_us.stop();
  
    })
  })
  </script>