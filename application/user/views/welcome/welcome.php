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
?>
        <script>window.jQuery || document.write('<script src="js/jquery-1.8.3.min.js"><\/script>')</script>
        
<?php
if($this->common_model->get_set_sessions()!='')
{
  $adminId     = $this->common_model->get_set_sessions(); 
  $list_menu_value = $this->common_model->get_admin_menu($adminId);
  $PG_TITLE      = $this->common_model->ClientFilePathName($_SERVER['PHP_SELF']);
  //print_r($list_menu_value);
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
<?php
        $name   = $this->common_model->get_field_name('Company','vTitle',"  ");
        $phone  = $this->common_model->get_field_name('Company','vPhone',"  ");
        $email  = $this->common_model->get_field_name('Company','vEmail',"  ");
?>
<div class="inner_container">
              <div class="inner_logo">
                <div align="left"><?php echo $name;?>
                    <br><?php echo $phone;?>
                   <br> <?php echo $email;?>
                </div>
            </div>
              <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>

                <div class="menu-row clearfix">
                
        <?php foreach($list_menu_value as $key => $item):?>
                <div class="inner_box">
                  <div class="inner_box_title" align="center">
                  <a href="<?php echo base_url();?>index.php/<?php echo $item->vFileName;?>/<?php echo $item->nCId;?>"><?php echo $item->$sTitle1;?></a></div>
                  
                <!-- start 2nd level submenu -->

                  <div class="dropdown">
                    <ul>
                    <?php 
                    //check sub menu exists
                    $list_sub_menu_array = $this->common_model->get_admin_submenu($adminId,$item->nCId);
                    if(!empty($list_sub_menu_array))
                    {
          ?>
           <?php foreach($list_sub_menu_array as $skey=>$sitem):?>
                     <?php $check2 = $this->uri->segment(3);?>

                      <li <?php if($sitem->vFileName == $PG_TITLE||$sitem->nId==$check2&&$check2!=''){ echo 'class="active"';}?>><a href="<?php echo base_url();?>index.php/<?php echo $sitem->vFileName;?>/<?php echo $sitem->nCategory;?>"><?php echo $sitem->$sTitle2;?></a> 
                      </li>
                       <?php endforeach;?>
                      <?php }//end if?>
                  </ul>
                  </div>
               <!-- end 2nd level submenu -->       
 
                </div> <!-- .inner_box -->

                <?php if(($key+1) % 5 == 0) 
            echo '</div><div class="menu-row clearfix">';?>
        <?php endforeach;?>

                <div class="inner_box">
                  <div class="inner_box_title dash-exp" align="center">
                    <a href="<?php echo base_url();?>index.php/logout">Logout</a>
                  </div>  
                </div> <!-- .inner_box -->

            </div> <!-- .menu-row -->
  <?php }//end if?>

             
                <div style="float:left; width:980px;">
                
    <div class="innerbo_logo">
                <div><img src="<?php echo base_url();?>static/images/clogo_sm.png" width="90" height="67" align="left">
                  <div class="innerbo_text">ACCOUNT BALANCING SOLUTION CO.                                
                    <br>
                  Customer support - <br>
                  +965 96677168 / 97136667                                
                <br>
                  e-mail - <a href="#">support@abskw.com</a><br>
        </div></div>
            </div>
            </div>
          </div>



          <div class="overlay"></div>


          <script>
          $(document).ready(function() {
            $(".menu-row").each(function(){
              $(".inner_box").each(function(){
                initialiseNav(this);
              });
            });


            function initialiseNav(navitem){
              //centre of this button
              var widthone = 0;
              widthone = $(navitem).outerWidth();
              widthone = widthone/2;
              $(navitem).prevUntil().each(function() {
                widthone = widthone + ($(this).outerWidth());
              });
              //width of subnav
              var widthtwo = 0;
              $(navitem).find(".inner_box_title").each(function() {
                widthtwo = widthtwo + ($(this).outerWidth());
              });
              widthtwo = widthtwo/2;
              //calculate margin
              var marginvalue = 0;
              marginvalue = widthone - widthtwo;
              if(marginvalue>0){
                //set left margin of first subnav item only if it isn't negative
                $(navitem).children().find('li').first().css('margin-left', marginvalue);
              }
            }
            $('.inner_box .inner_box_title').click(function() {
              if ($(this).hasClass('dash-exp')) {
                window.self($(this).attr("href"));
              }
              $this = $(this);
              var cSdropDown = $this.parent().find('.dropdown');
              if (cSdropDown.is(":visible")) {
                cSdropDown.fadeOut(200);
                $this.removeClass('active');
                $('.overlay').hide();
              }
              else {
                $('.inner_box .dropdown').fadeOut(200);
                $('.overlay').show();
                $this.parent().css('z-index', 999);
                cSdropDown.fadeIn(200);
                $this.addClass('active');
                $(window).bind('click', function() {
                  cSdropDown.fadeOut(200);
                  $this.removeClass('active');
                  $('.overlay').hide();
                });
                $this.parent().bind('click', function(e) {
                  e.stopPropagation();
                });
              }
              return false;
            });
          });

</script>
