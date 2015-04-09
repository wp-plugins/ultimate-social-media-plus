<?php
/* add fb like add this and google share to end of every post */

function sfsi_plus_social_buttons_below($content) {
	global $post;
         $sfsi_section6=  unserialize(get_option('sfsi_plus_section6_options',false));
		 
		 //new options that are added on the third questions
		 //so in this function we are replacing all the past options 
		 //that were saved under option6 by new settings saved under option8 
		 $sfsi_section8=  unserialize(get_option('sfsi_plus_section8_options',false));
		 $sfsi_plus_show_item_onposts = $sfsi_section8['sfsi_plus_show_item_onposts'];
		 //new options that are added on the third questions
        
  /* check if option activated in admin or not */ 
  //if($sfsi_section6['sfsi_plus_show_Onposts']=="yes")
  //removing following condition for now
  /*if($sfsi_section8['sfsi_plus_show_Onposts']=="yes")
  {*/
	$permalink = get_permalink($post->ID);
        $title = get_the_title();
	$sfsiLikeWith="45px;";
        /* check for counter display */
        //if($sfsi_section6['sfsi_plus_icons_DisplayCounts']=="yes")
		if($sfsi_section8['sfsi_plus_icons_DisplayCounts']=="yes")
		{
            $show_count=1;
	    	$sfsiLikeWith="75px;";
        }   
        else
        {
            $show_count=0;
        } 
        //$txt=(isset($sfsi_section6['sfsi_plus_textBefor_icons']))? $sfsi_section6['sfsi_plus_textBefor_icons'] : "Share this Post with :" ;
        $txt=(isset($sfsi_section8['sfsi_plus_textBefor_icons']))? $sfsi_section8['sfsi_plus_textBefor_icons'] : "Please follow and like us:" ;
		//$float= $sfsi_section6['sfsi_plus_icons_alignment'];
        $float= $sfsi_section8['sfsi_plus_icons_alignment'];
		$icons="<div class='sfsi_Sicons' style='float:".$float."'><div style='float:left;margin:0 px; line-height: 32px'><span>".$txt."</span></div>";
        
        $icons.="<div class='sf_fb' style='float:left;margin:5px;width:".$sfsiLikeWith."'>".sfsi_plus_FBlike($permalink,$show_count)."</div>";
		$icons.="<div class='sf_google'  style='float:left;margin:5px;max-width:62px;min-width:35px;'>".sfsi_plus_googlePlus($permalink,$show_count)."</div>";
        $icons.="<div class='sf_addthis'  style='float:left;margin:8px 5px 5px 5px;'>".sfsi_plus_Addthis($show_count)."</div>";
      
	$icons.="</div>";
    if(!is_feed() && !is_home() && !is_page()) {
		$content =   $content .$icons;
	}
  //}   
	return $content;
}

/* create google+ button */
function sfsi_plus_googlePlus($permalink,$show_count) {
        $google_html = '<div class="g-plusone" data-href="' . $permalink . '" ';
        if($show_count) {
                $google_html .= 'data-size="large" ';
        } else {
                $google_html .= 'data-size="large" data-annotation="none" ';
        }
        $google_html .= '></div>';
        return $google_html;
}

/* create fb like button */
function sfsi_plus_FBlike($permalink,$show_count) {
      
                $send = 'false';
                $width = 180;

        $fb_like_html = '<fb:like href="'.$permalink.'" width="'.$width.'" send="'.$send.'" showfaces="false" ';
        if($show_count==1) { 
                $fb_like_html .= 'layout="button_count"';
        } else {
                $fb_like_html .= 'layout="button"';
        }
        $fb_like_html .= ' action="like"></fb:like>';
        return $fb_like_html;
}
/* create add this  button */
function sfsi_plus_Addthis($show_count)
{
   
   $atiocn=' <script type="text/javascript">
var addthis_config = {
     pubid: "YOUR-PROFILE-ID"
}
</script>';

if($show_count==1)
   {
       $atiocn.=' <div class="addthis_toolbox">
              <a class="addthis_counter addthis_pill_style share_showhide"></a>
	   </div>';
	    return $atiocn;
	
   }
   else
   {
	$atiocn.='<div class="addthis_toolbox addthis_default_style addthis_20x20_style"><a class="sfsiplus_addthis_button_compact " href="#">  <img src="'.SFSI_PLUS_PLUGURL.'images/sharebtn.png"  border="0" alt="Share" /></a></div>';
      return $atiocn; 
    }
}

function sfsi_plus_Addthis_blogpost($show_count, $permalink, $post_title)
{ 
   $atiocn=' <script type="text/javascript">
var addthis_config = {
     url: "'.$permalink.'",
   	 title: "'.$post_title.'"
}
</script>';
if($show_count==1)
   {
	   $atiocn.=' <div class="addthis_toolbox" addthis:url="'.$permalink.'" addthis:title="'.$post_title.'">
              <a class="addthis_counter addthis_pill_style share_showhide"></a>
	   </div>';
	    return $atiocn;
   }
   else
   {
	$atiocn.='<div class="addthis_toolbox addthis_default_style addthis_20x20_style" addthis:url="'.$permalink.'" addthis:title="'.$post_title.'"><a class="sfsiplus_addthis_button_compact " href="#">  <img src="'.SFSI_PLUS_PLUGURL.'images/sharebtn.png"  border="0" alt="Share" /></a></div>';
      return $atiocn; 
    }
}
	
/* add all external javascript to wp_footer */        
 function sfsi_plus_footer_script() {
	  $sfsi_section1=  unserialize(get_option('sfsi_plus_section1_options',false));
	  $sfsi_section6=  unserialize(get_option('sfsi_plus_section6_options',false));
	if($sfsi_section1['sfsi_plus_facebook_display']=="yes")
	{
	?>
	<!--facebook like and share js -->                   
	<div id="fb-root"></div>
	<script>(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
	  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&appId=1425108201100352&version=v2.0";
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));</script>
 <?php } if($sfsi_section1['sfsi_plus_google_display']=="yes" || $sfsi_section1['sfsi_plus_youtube_display']=="yes") { ?>
 <!--google share and  like and e js -->
	<script type="text/javascript">
		window.___gcfg = {
		  lang: 'en-US'
		};
		(function() {
			var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
			po.src = 'https://apis.google.com/js/plusone.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
		})();
	</script>
	
	<script type='text/javascript' src='https://apis.google.com/js/plusone.js'></script>
	<script type='text/javascript' src='https://apis.google.com/js/platform.js'></script>
	<!-- google share -->
	<script type="text/javascript">
	  (function() {
	    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
	    po.src = 'https://apis.google.com/js/platform.js';
	    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
	  })();
	</script>
<?php } if($sfsi_section1['sfsi_plus_linkedin_display']=="yes") { ?>	
       <!-- linkedIn share and  follow js -->
        <script src="//platform.linkedin.com/in.js" type="text/javascript">lang: en_US</script>
	
<?php } if($sfsi_section1['sfsi_plus_share_display']=="yes" || $sfsi_section6['sfsi_plus_show_Onposts']=="yes") { ?>		
	 <!-- Addthis js -->
        <script type="text/javascript" src="https://s7.addthis.com/js/300/addthis_widget.js"></script>
        <script type="text/javascript">
       var addthis_config = {  ui_click: true  };
       </script>
<?php } if($sfsi_section1['sfsi_plus_pinterest_display']=="yes") {?>
	
	<!--pinit js -->
	<script type="text/javascript" src="//assets.pinterest.com/js/pinit.js"></script>
	
	<?php } if($sfsi_section1['sfsi_plus_twitter_display']=="yes") {?>
<!-- twitter JS End -->
	<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>	
	<?php }
	/* activate footer credit link */
        if(get_option('sfsi_plus_footer_sec')=="yes")
        {
	    if(!is_admin())
	    {
            //$footer_link='<div class="sfsiplus_footerLnk" style="margin: 0 auto;z-index:1000; absolute; text-align: center;">Social media & sharing icons powered by  <a href="https://wordpress.org/plugins/ultimate-social-media-icons/" target="_new">UltimatelySocial</a> ';
			$footer_link='<div class="sfsiplus_footerLnk" style="margin: 0 auto;z-index:1000; absolute; text-align: center;">Social media & sharing icons powered by  <a href="http://ultimatelysocial.com/" target="_new">UltimatelySocial</a> ';
	    	$footer_link.="</div>";
	    echo $footer_link;
	    }
	}    
        
}
/* filter the content of post */
//commenting following code as we are going to extend this functionality 
//add_filter('the_content', 'sfsi_plus_social_buttons_below');

/* update footer for frontend and admin both */ 
if(!is_admin())
{   global $post;
   add_action( 'wp_footer', 'sfsi_plus_footer_script' );	
   add_action('wp_footer','sfsi_plus_check_PopUp');
   add_action('wp_footer','sfsi_plus_frontFloter');	 	     
}
			 				    
if(is_admin())
{
	add_action('in_admin_footer', 'sfsi_plus_footer_script');	
}

/* ping to vendor site on updation of new post */

?>