

<!-- Bootstrap tooltips -->
<script type="text/javascript" src="{{asset('assets/frontend/js/popper.min.js')}}"></script>
<!-- Bootstrap core JavaScript -->
<script type="text/javascript" src="{{asset('assets/frontend/js/bootstrap.min.js')}}"></script>
<!-- MDB core JavaScript -->
<script type="text/javascript" src="{{asset('assets/frontend/js/mdb.min.js')}}"></script>
 <!-- myscripte class -->
<script type="text/javascript" src="{{asset('assets/frontend/js/script.js')}}" ></script>
<!-- Your custom scripts (optional) -->
 <script type="text/javascript" src="{{asset('assets/frontend/js/custom.js')}}"></script>
 <script type="text/javascript" src="{{asset('assets/frontend/js/js-plugins.min.js')}}"></script>
 <script src="{{asset('assets/frontend/js/multislider.js')}}"></script>
<script>
$('#basicSlider').multislider({
			continuous: true,
			duration: 2000
		});
		$('#mixedSlider').multislider({
			duration: 750,
			interval: 3000
		});
</script>

</body>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-36251023-1']);
  _gaq.push(['_setDomainName', 'jqueryscript.net']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
 <script>
  $(".sidepanel > span").on('click', function () {
    $(".sidepanel").toggleClass("show");
    $(this).toggleClass('spin');
    return false;
  });
  $('.color-picker a').on("click", function () {
    $('.color-picker a').removeClass("applied");
    $(this).addClass("applied");
    return false;
  });
$('.multi-item-carousel').on('slide.bs.carousel', function (e) {
    let $e = $(e.relatedTarget),
      itemsPerSlide = 3,
      totalItems = $('.carousel-item', this).length,
      $itemsContainer = $('.carousel-inner', this),
      it = itemsPerSlide - (totalItems - $e.index());
    if (it > 0) {
      for (var i = 0; i < it; i++) {
        $('.carousel-item', this).eq(e.direction == "left" ? i : 0).
          // append slides to the end/beginning
          appendTo($itemsContainer);
      }
    }
  });

</script>
<script>

function openNav() {
document.getElementById("mySidebar").style.width = "300px";
document.getElementById("main").style.marginLeft = "300px";
}

function closeNav() {
document.getElementById("mySidebar").style.width = "0";
document.getElementById("main").style.marginLeft= "0";
}

</script>

<script>
 $('#change_lamguage').on('click', function(){
  var lang = $('#change_lamguage').text();
  var language = $.trim(lang);
  if (language !="") {
      if(language=="Arabic"){
        set_lang= 'ar';
      }else{
        set_lang= 'en';
      }
    $.ajax( {
          type: 'POST',
          headers: {
            'X-CSRF-TOKEN': $( 'meta[name="csrf-token"]' ).attr( 'content' )
          },
          url: '{{url("language")}}/'+set_lang,
          success: function ( msg ) {
           location.reload();
          }
        } );
  }
});
</script>
