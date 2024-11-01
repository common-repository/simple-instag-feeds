<?php
/*
Plugin Name: Simple Instag Feeds
Plugin URI: https://wordpress.org/support/profile/amybeagh
Description: Simple Instagram Feeds - Display your InstaGram Feeds on website sidebar using Simple Instagram Feeds.
Version: 1.0
Author :Amy Beagh
Author URI: https://wordpress.org/support/profile/amybeagh
*/
class sif_simple_instag_widget_section{
    public $options;
    public function __construct() {
        //you can run delete_option method to reset all data
        //delete_option('insGram_widget_options');
        $this->options = get_option('sif_wid_option_form');
        $this->sif_register_fields_and_setting();
    }
    
    public static function add_sif_tools_options_page(){
        add_options_page('Simple Instagram Feeds', 'Simple Instagram Feeds', 'administrator', __FILE__, array('sif_simple_instag_widget_section','sif_widget_tools_options'));
    }
    
    public static function sif_widget_tools_options(){
?>
<div class="wrap">
<div class="sif_outer">
  <h2 class="top-style">Simple Instagram Feeds Setting</h2>
  <form method="post" action="options.php" enctype="multipart/form-data" class="sif_frm">
    <?php settings_fields('sif_wid_option_form'); ?>
    <?php do_settings_sections(__FILE__); ?>
    <p class="submit">
      <input name="submit" type="submit" class="button-success-012" value="Save Changes"/>
    </p>
  </form>
</div>
</div>
<?php
    }
    public function sif_register_fields_and_setting(){
        register_setting('sif_wid_option_form', 'sif_wid_option_form',array($this,'sif_widget_validate_form_set'));
        add_settings_section('sif_widget_main_section', '', array($this,'sif_widget_main_cb_page_section'), __FILE__);
        //Start Creating Fields and Options
		 //sif_pageURL
        add_settings_field('sif_pageURL', 'Instagram Widget ID', array($this,'page_url_section'), __FILE__,'sif_widget_main_section');
		
        //sif_marginTop
        add_settings_field('sif_marginTop', 'Margin Top', array($this,'marTop_section'), __FILE__,'sif_widget_main_section');
       
        //sif_width
        add_settings_field('sif_width', 'Width', array($this,'page_sif_width_setion'), __FILE__,'sif_widget_main_section');
		
		//sif_height
        add_settings_field('sif_height', 'Height', array($this,'page_sif_height_setion'), __FILE__,'sif_widget_main_section');
            
        //sif_alignment option
         add_settings_field('sif_alignment', 'Position', array($this,'page_position_section'),__FILE__,'sif_widget_main_section');
		 
		 //show hide option
	
	     add_settings_field('sif_status', 'Show/Hide', array($this,'sif_status_section'),__FILE__,'sif_widget_main_section');
    }
    public function sif_widget_validate_form_set($plugin_options){
        return($plugin_options);
    }
    public function sif_widget_main_cb_page_section(){
        //optional
    }
    //sif_pageURL_settings
    public function page_url_section() {
        if(empty($this->options['sif_pageURL'])) $this->options['sif_pageURL'] = "";
        echo "<input name='sif_wid_option_form[sif_pageURL]' type='text' value='{$this->options['sif_pageURL']}' />";
    }
	
	//sif_marginTop_settings
    public function marTop_section() {
        if(empty($this->options['sif_marginTop'])) $this->options['sif_marginTop'] = "110";
        echo "<input name='sif_wid_option_form[sif_marginTop]' type='text' value='{$this->options['sif_marginTop']}' />";
    }

    //sif_width_settings
    public function page_sif_width_setion() {
        if(empty($this->options['sif_width'])) $this->options['sif_width'] = "400";
        echo "<input name='sif_wid_option_form[sif_width]' type='text' value='{$this->options['sif_width']}' />";
    }
    //page_sif_height_setion
    public function page_sif_height_setion() {
        if(empty($this->options['sif_height'])) $this->options['sif_height'] = "400";
        echo "<input name='sif_wid_option_form[sif_height]' type='text' value='{$this->options['sif_height']}' />";
    }
    //sif_alignment_settings
    public function page_position_section(){
        if(empty($this->options['sif_alignment'])) $this->options['sif_alignment'] = "left";
        $sif_items = array('left','right');
        foreach($sif_items as $item){
          $sif_selected = ($this->options['sif_alignment'] === $item) ? 'checked = "checked"' : '';
		  echo "<input type='radio' name='sif_wid_option_form[sif_alignment]' value='$item' $sif_selected> ".ucfirst($item)."&nbsp;";
        }
    }
	
	 //show hide section
    public function sif_status_section(){
        if(empty($this->options['sif_status'])) $this->options['sif_status'] = "on";
        $items = array('on','off');
        
	   foreach($items as $item){
            $selected = ($this->options['sif_status'] === $item) ? 'checked = "checked"' : '';
			echo "<input type='radio' name='sif_wid_option_form[sif_status]' value='$item' $selected> ".ucfirst($item)."&nbsp;";
        }
		
    }
    
}
add_action('admin_menu', 'sif_widget_form_options');

function sif_widget_form_options(){
    sif_simple_instag_widget_section::add_sif_tools_options_page();
}

add_action('admin_init','sif_widget_form_object');
function sif_widget_form_object(){
    new sif_simple_instag_widget_section();
}
add_action('wp_footer','sif_widget_add_content_in_page_footer');
function sif_widget_add_content_in_page_footer(){
    
    $sif_option= get_option('sif_wid_option_form');
    extract($sif_option);
	

$sif_intagram_output = '';
$sif_intagram_output .= '<iframe src="http://widgets-code.websta.me/w/'.$sif_pageURL.'?ck=MjAxNi0wNi0yMFQwODo0MjoxNy4wMDBa" allowtransparency="true" frameborder="0"
 scrolling="yes" style="border:none;overflow:hidden; width:'.$sif_width.'px; height:'.$sif_height.'px" ></iframe>';

$sif_imgURL = plugins_url('assets/sifimg-icon.png', __FILE__ );


?>
<?php
if($sif_status == 'on'):
if($sif_alignment=='left'){?>
<div id="sif_insGram_widget_display">

  <div id="sif_insGram1" class="sif_ilnk_area_left">
  <a class="open" id="sif_ilink" href="javascript:;"><img style="top: 0px;right:-45px;" src="<?php echo $sif_imgURL;?>" alt=""></a>
    <div id="sif_insGram2" class="sif_ilink_inner_area_left" >
    <?php echo $sif_intagram_output; ?>
    </div>
  </div>
 </div>

<style type="text/css">
        
        div.sif_ilnk_area_left{        
            left: -<?php echo trim($sif_width+10);?>px;         
            top: <?php echo $sif_marginTop;?>px;         
            z-index: 10000; height:<?php echo trim($sif_height+10);?>px;        
            -webkit-transition: all .5s ease-in-out;        
            -moz-transition: all .5s ease-in-out;        
            -o-transition: all .5s ease-in-out;        
            transition: all .5s ease-in-out;        
            }
        
        div.sif_ilnk_area_left.sif_showdiv{        
            left:0;
        
            }	
        
        div.sif_ilink_inner_area_left{        
            text-align: left;        
            width:<?php echo trim($sif_width);?>px;        
            height:<?php echo trim($sif_height);?>px;        
            }
        
        
        </style>

<?php } else { ?>



<div id="sif_insGram_widget_display">

  <div id="sif_insGram1" class="sif_ilnk_area_right">
  <a class="open" id="sif_ilink" href="javascript:;"><img style="top: 0px;left:-45px;" src="<?php echo $sif_imgURL;?>" alt=""></a>
    <div id="sif_insGram2" class="sif_link_inner_area_right">
      <?php echo $sif_intagram_output; ?>
      
    </div>
     
  </div>
</div>
<style type="text/css">
        
        div.sif_ilnk_area_right{ right: -<?php echo trim($sif_width+10);?>px;top: <?php echo $sif_marginTop;?>px; z-index: 10000;height:<?php echo trim($sif_height+10);?>px; -webkit-transition: all .5s ease-in-out;  -moz-transition: all .5s ease-in-out; -o-transition: all .5s ease-in-out; transition: all .5s ease-in-out; }
        
        div.sif_ilnk_area_right.sif_showdiv{ right:0; }	
        
        div.sif_link_inner_area_right{ text-align: left;        
            width:<?php echo trim($sif_width);?>px;
            height:<?php echo trim($sif_height);?>px;
        
            }
        
        div.sif_ilnk_area_right .contacticonlink {	        
            left: -32px;        
            text-align: left;        
        }		
        
        </style>
<?php } endif; ?>
 <script type="text/javascript">
        
        jQuery(document).ready(function() {
            jQuery('#sif_ilink').click(function(){
                jQuery(this).parent().toggleClass('sif_showdiv');
        
        });});
</script>
<?php
}
add_action( 'wp_enqueue_scripts', 'register_sif_widget_form_styles' );
add_action( 'admin_enqueue_scripts', 'register_sif_widget_form_styles' );
 function register_sif_widget_form_styles() {
    wp_register_style( 'sif_widget_styles', plugins_url( 'assets/sifcss_main.css' , __FILE__ ) );
    wp_enqueue_style( 'sif_widget_styles' );
   }
/*@Default setting for Simple instagram widget*/ 
$sif_defaults = array(
     'sif_pageURL' => '',
	 'sif_marginTop' => 100,
     'sif_width' => '300',
	 'sif_height' => '300',
     'sif_alignment' => 'left',
	 'sif_status' => 'on'
 );
add_option('sif_wid_option_form', $sif_defaults);

/* shortcode for this plugin */
function simple_intag_widget_shortcode(){
	$sif_option1= get_option('sif_wid_option_form');
    extract($sif_option1);
$sif_intagram_output_shortcode = '';
$sif_intagram_output_shortcode .= '<iframe src="http://widgets-code.websta.me/w/'.$sif_pageURL.'?ck=MjAxNi0wNi0yMFQwODo0MjoxNy4wMDBa" allowtransparency="true" frameborder="0"
 scrolling="yes" style="border:none;overflow:hidden; width:'.$sif_width.'px; height:'.$sif_height.'px" ></iframe>';	
return $sif_intagram_output_shortcode;	
}
add_shortcode('simple_intag_widget_display', 'simple_intag_widget_shortcode');
/* End shortcode */


 
 