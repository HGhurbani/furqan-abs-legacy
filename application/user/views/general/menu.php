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
        <section id="navMain">
    		<div class="navCont">

    			<ul>
    				<li <?php if($PG_TITLE == 'work/index.php' || $PG_TITLE == '/'){echo 'class="current"';}?>><a href="<?php echo base_url();?>index.php/welcome"><?php echo DASHBOARD;?></a></li>
                    <?php foreach($list_menu_value as $key => $item):?>
                    <?php
							if($item->$sTitle1 != 'Settings'):
							if($item->$sTitle1 == 'Finance')
									$pathName = 'cashbook_receipt/showlist';
                    		elseif($item->$sTitle1 == 'Setup')
									$pathName = 'main_group/showlist';
                            elseif($item->$sTitle1 == 'Sales')
									$pathName = 'ticket_booking/showlist';
							else
								$pathName = $item->vFileName;
						?>
                    	
                        <?php $check = $this->uri->segment(3);?>
                        <li <?php if($item->nCId == $check){echo 'class="current"';}?>>
                      <a href="<?php echo base_url();?>index.php/<?php echo $pathName;?>/<?php echo $item->nCId;?>"><?php echo $item->$sTitle1;?></a>
 							<!-- start 2nd level submenu -->
                                <?php 
									//check sub menu exists
									$list_sub_menu_array = $this->common_model->get_admin_submenu($adminId,$item->nCId);
									if(!empty($list_sub_menu_array))
									{
								?>
                               			 <ul>
											 <?php foreach($list_sub_menu_array as $skey=>$sitem):?>
                                             <?php $check2 = $this->uri->segment(3);?>
                                                    <li <?php if($sitem->vFileName == $PG_TITLE||$sitem->nId==$check2&&$check2!=''){ echo 'class="active"';}?>><a href="<?php echo base_url();?>index.php/<?php echo $sitem->vFileName;?>/<?php echo $sitem->nCategory;?>"><?php echo $sitem->$sTitle2;?></a>
                                                    
                  			<!-- start 3rd level submenu -->
                                <?php 
									//check 3rd level sub menu exists
									$list_sub_menu2_array = $this->common_model->get_admin_submenu2($adminId,$sitem->nCategory,$sitem->nMId);
									if(!empty($list_sub_menu2_array))
									{
								?>
                               			 <ul>
											 <?php foreach($list_sub_menu2_array as $skey3=>$sitem3):?>
                                             <?php $check2 = $this->uri->segment(3);?>
                                                    <li <?php if($sitem3->vFileName == $PG_TITLE||$sitem3->nId==$check2){ echo 'class=""';}?>><a href="<?php echo base_url();?>index.php/<?php echo $sitem3->vFileName;?>/<?php echo $sitem3->nCategory;?>" ><?php echo $sitem3->$sTitle2;?></a></li>
                                            <?php endforeach;?>
                         				 </ul>
                        			 <?php }//end if?>
                                     <!-- end 3rd level submenu -->                          
                                                    
                                                    </li>
                                            <?php endforeach;?>
                         				 </ul>
                        			 <?php }//end if?>
                                     <!-- end 2nd level submenu -->       
                      </li>
					<?php endif;endforeach;?>
                    <li class="skip"><a href="<?php echo base_url();?>index.php/logout" class="logout"><img src="<?php echo base_url();?>static/images/logout.gif" alt="" width="15" height="16"></a></li>
    				<li class="skip"><div id="google_translate_element"></div><script type="text/javascript">
function googleTranslateElementInit() {
  new google.translate.TranslateElement({pageLanguage: 'en', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');
}
</script><script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
        </li>

    				<li class="skip"><a href="<?php echo base_url();?>index.php/change_password/changepass/9"><?php echo CHANGEPASSWORD;?></a></li>
    				<li class="skip"><a href="#"><?php echo HELP;?></a></li>


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
