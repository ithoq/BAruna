//(function($){

  	/* ---------------------------------------------- /*
	 * Preloader
	/* ---------------------------------------------- */

	$(window).load(function() {
		$('#status').fadeOut();
		$('#preloader').delay(300).fadeOut('slow');
	});

	$(document).ready(function() {

	    ratioImg();
	    //hero();  
	    
		$(window).resize($.debounce(1000, function(){

			ratioImg();
			//hero();
		}));

		$('.select').selectric({
	      	disableOnMobile: false
	    });

  	});

	//set ration img
	function ratioImg(){
	  	$('.ratio-img').each(function(){
	    		
	  		var ratio = $(this).attr('ratio').split(':');
	  		r_w = ratio[0];
	  		r_h = ratio[1];
	  		set_h = $(this).width()/(r_w/r_h);
	  		$(this).css({'height': set_h+'px'});
	  	});
	};

	//set heroes
	function hero() {

		var hScreen = $(window).height();

		$('.heroes').css({'height': hScreen+'px'});
	};
//(jQuery);