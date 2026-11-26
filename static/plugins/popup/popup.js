$(document).ready(function() {
	var boxWidth = $('.popup-box').outerWidth();
	var scrolltop;
	function resetBoxWidth() {
		boxWidth = 0;
		}
		/* Preliminary information */
		var winWidth = $(window).width();
		var winHeight = $(window).height();
		var scrollPos = $(window).scrollTop();

	function centerBox() {
		boxWidth = $('.popup-box').width();
		/* auto scroll bug */
		
		/* Calculate positions */
		
		var disWidth = (winWidth - boxWidth) / 2
		var disHeight = scrollPos + 150;
		/* Move stuff about */
		$('.popup-box').attr('style','').css({'width' : boxWidth+'px', 'left' : disWidth+'px', 'top' : disHeight+'px'});
		$('#blackout').css({'width' : winWidth+'px', 'height' : winHeight+'px'});
	
		return false;
	}
	
	
	$(window).resize(centerBox);
	$(window).scroll(centerBox);
	centerBox();

	//$(document).live('click','[class*=popup-link]', function(e){
	$('[class*=popup-link]').click(function(e) {
		 scrolltop = $('#maincontent').scrollTop(); 
		$('#maincontent').animate({scrollTop:0}, 400);
	
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		if(boxWidth < 900){
			boxWidth = $('.popup-box').outerWidth();
		} else {
			$('.popup-box').width(900)
		}
		centerBox();
		/* Get the id (the number appended to the end of the classes) */
		var name = $(this).attr('class');
		//var id = name[name.length - 1];
		var id   = name.split('popup-link-');
		id       = id[1];
		
		var scrollPos = $(window).scrollTop();
		
		/* Show the correct popup box, show the blackout and disable scrolling */
		$('#popup-box-'+id).show();
		$('#blackout').show();
		$('html,body').css('overflow', 'hidden');
		
		/* Fixes a bug in Firefox */
		$('html').scrollTop(scrollPos);
	});
	$('[class*=popup-box]').click(function(e) { 
		/* Stop the link working normally on click if it's linked to a popup */
		e.stopPropagation(); 
	});
	$('html').click(function() {
		var scrollPos = $(window).scrollTop();
		/* Hide the popup and blackout when clicking outside the popup */
		$('[id^=popup-box-]').hide(); 
		$('#blackout').hide(); 
		$("html,body").css("overflow","auto");
		$('#maincontent').scrollTop(scrolltop); 
		$('html').scrollTop(scrollPos);
		resetBoxWidth();
	});
	
	//$(document).live('click','.close', function(){
	$('[class*=close]').click(function(e) {
		resetBoxWidth();
		var scrollPos = $(window).scrollTop();
		/* Similarly, hide the popup and blackout when the user clicks close */
		$('[id^=popup-box-]').hide(); 
		$('#blackout').hide();
		$("html,body").css("overflow","auto");
		$('#maincontent').scrollTop(scrolltop); 
		$('html').scrollTop(scrollPos);
	});
});