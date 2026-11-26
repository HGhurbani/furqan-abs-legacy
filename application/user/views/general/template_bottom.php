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
                 </div>
            </div>
        </div>
    </div>

 	<script type="text/javascript">
        $(function() {
            if ($.browser.msie && $.browser.version.substr(0,1)<7)
            {
            $('li').has('ul').mouseover(function(){
                $(this).children('ul').css('visibility','visible');
                }).mouseout(function(){
                $(this).children('ul').css('visibility','hidden');
                })
            }
    
            /* Mobile */
            $('#menu-wrap').prepend('<div id="menu-trigger">Menu</div>');		
            $("#menu-trigger").bind("click", function(){
                $("#menu").slideToggle();
            });
    
            // iPad
            var isiPad = navigator.userAgent.match(/iPad/i) != null;
            if (isiPad) $('#menu ul').addClass('no-transition');

            $('.showDetails').each(function(){
                $(this).click(function(e){
                    e.preventDefault();
                    if($('#'+$(this).attr('data-target')).hasClass('hidden'))
                    {
                        $('#'+$(this).attr('data-target')).removeClass('hidden')
                    }
                    else
                    {
                        $('#'+$(this).attr('data-target')).addClass('hidden')
                    }

                })

            })
        });          
    </script>
</body>
</html>
