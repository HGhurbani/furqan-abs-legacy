<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4:             */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2010 Qbicblack  Technologies                      |
// +----------------------------------------------------------------------+
// | This source file is a part of ABS Account Balancing Solution         |
// +----------------------------------------------------------------------+
// | Authors: programmers                                                 |
// |                                                                      |
// +----------------------------------------------------------------------+                                                     
if($this->common_model->get_set_sessions()!='')
{
	$adminId 		 = $this->common_model->get_set_sessions(); 
	$list_menu_value = $this->common_model->get_admin_menu($adminId);
	$PG_TITLE 		 = $this->common_model->ClientFilePathName($_SERVER['PHP_SELF']);
	$PG_TITLE1 		 = $this->common_model->ClientFilePathName1($_SERVER['PHP_SELF']);

	//checking lang
	switch($this->session->userdata('lang'))
	{
		case "eng":
			$sTitle1 = 'vTitle';
			$sTitle2 = 'vModuleTitle';
		break;
		
		case "ar":
			$sTitle1 = 'vTitle2';
			$sTitle2 = 'vModuleTitle2';
		break;
	}//end switch
?>
        <!--Nav-->
		<style>
		div.navCont {
    padding: 0px 0px 0 !important;
}
		</style>
        <section id="navMain">
    		<div class="navCont">

    			<ul>
    			
                   
                   </ul>
                </div>
        </section>

         <script>

            function setMainContentPos () {
                if ($('.navCont > ul > li.current').children('ul').length) {
                    var activeSubnavHeight = $('.navCont > ul > li.current > ul').height();
                    $('#maincontent').stop().animate({
                        top: 103+activeSubnavHeight
                    },200);
                } else {
                    $('#maincontent').stop().animate({
                        top: 98
                    },200);
                };
            }

            $(function() {
                setMainContentPos ();
                $(".navCont > ul > li").hover(function(){
                    if ($(this).children('ul').length) {
                        var activeSubnavHeight = $(this).children('ul').height();
                        $('#maincontent').stop().animate({
                            top: 103+activeSubnavHeight
                        },200);
                    } else {
                        $('#maincontent').stop().animate({
                            top: 98
                        },200);
                    };
                });
            });

            $(window).resize(function() {
                setMainContentPos ();
            });

			$(document).ready(function(){
				hideAllNav();
				showCurrentNav();
				$(".navCont > ul > li").each(function(){
					initialiseNav(this);
				});
				
				$(".navCont > ul > li").bind("mouseenter",function(){
					hideAllNav();
					showChildNav(this);
				});
				
				$(".navCont").bind("mouseleave",function(){
					hideAllNav();
					showCurrentNav();
                    setMainContentPos ();
				});
		
			});
			
		
			function initialiseNav(navitem){
				//centre of this button
				var widthone = 0;
				widthone = $(navitem).outerWidth();
				widthone = widthone/2;
				
				$(navitem).prevUntil('ul').each(function() {
					widthone = widthone + ($(this).outerWidth());
				});
				
				//width of subnav
				var widthtwo = 0;
				$(navitem).find("li").each(function() {
					widthtwo = widthtwo + ($(this).outerWidth());
				});
				widthtwo = widthtwo/2;
				
				//calculate margin
				var marginvalue = 0;
				marginvalue = widthone - widthtwo;
				
				if(marginvalue>0){
					//set left margin of first subnav item only if it isn't negative
					$(navitem).children("ul").find("li").first().css("margin-left", marginvalue);
				}
			}
		
			function hideAllNav(menu){
				$(".navCont > ul > li > ul").removeClass("on");
				$(".navCont > ul > li > ul").addClass("off");
			}
			
			function showChildNav(actOnMe){
				$(".navCont > ul > li").removeClass("MenuVisible");
				$(actOnMe).find("ul").removeClass("off");
				$(actOnMe).find("ul").addClass("on");
				$(actOnMe).not(".current").find("ul").bind("mouseenter",function(){
					$(this).addClass("MenuVisible");
				}).bind("mouseleave",function(){
					$(this).removeClass("MenuVisible");
				});
			}
			
			function showCurrentNav(){
				//only do this if it is currently hidden
				if($(".navCont > ul > li.current > ul").hasClass("off")){
					$(".navCont > ul > li.current > ul").removeClass("off");
					$(".navCont > ul > li.current > ul").addClass("on");
				}
			}


        </script>
    </div>
<?php
}//end if
?> 
