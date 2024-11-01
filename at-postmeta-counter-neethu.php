<?php
/*
 Plugin Name:  AT Postmeta Counter -Neethu
Plugin URI: http://www.postmeta.com
Description: Plugin for count the number of words and the characters
Author:Neethu 
Version: 1.0
Author URI: http://www.cek.com
*/


/**
 * Adds a box to the main column on the Post and Page edit screens.
*/
function apcn_install_table()
{
	global $wpdb;	
	$sql = "CREATE TABLE autotable(id int(20) UNIQUE,wordcount int(50),charcount int(50))";
	$var=$wpdb->query($sql);
	
}
register_activation_hook( __FILE__, 'apcn_install_table' );


function apcn_myplugin_add_meta_box() {

	$screens = array( 'post',);

	foreach ( $screens as $screen ) {

		add_meta_box(
		'myplugin_sectionid',
		__( 'My Post Section Title', 'myplugin_textdomain' ),
		'myplugin_meta_box_callback',
		$screen
		);
	}
}
add_action( 'add_meta_boxes', 'apcn_myplugin_add_meta_box' );
function myplugin_meta_box_callback( $post ) 
{
	//echo "<input type='text' name='countw' placeholder='number of words'><br>";
	// echo "<br><input type='text' name='countc' placeholder='number of characters'>";
	
}

	add_action('save_post', 'setup_post_page');
	function setup_post_page($ID)
	
{
	if($_POST){
	    global $wpdb;
		/*$table_name = "wp_posts";
		$str = "SELECT post_content FROM ".$table_name." WHERE ID = $ID";
		var_dump($ID);
		exit();
		$result= $wpdb->get_results ($str);
		foreach ($result as $rows)
		{
			$var=$rows->post_content;
		} */
	    	    
	    $con=$_POST['post_content'];
		echo $con;
		$wc=str_word_count($con);
		echo $wc;
		$chc=strlen($con);
		echo $chc;
		$ID=$GLOBALS['post']->ID;
		//$uni="SELECT ID from autotable";
		$results= $wpdb->get_results("SELECT id from autotable where id=$ID");
		echo "SELECT id from autotable where id=$ID";
		//exit();
		
		  if(count($results)>0)
		  {
		  	$sqli="update autotable set wordcount=$wc,charcount=$chc where id=$ID";
		  	echo $sqli;
		  	//exit();
		  	$wpdb->query($sqli);
		  }
		  	 else{
		  	 	//exit();
		  	
		$connect="INSERT INTO `autotable`(`id`, `wordcount`, `charcount`) VALUES ($ID,$wc,$chc)";
		
		$in=$wpdb->query($connect);
		echo  $in;
// 		/exit();
	 
		}
	}
}
class apcn_wp_my_plugin extends WP_Widget 
{

	// constructor

	function apcn_wp_my_plugin() {
	
	parent::WP_Widget(false, $name = __('My Widget', 'apcn_wp_widget_plugin') );

		}
		
		




	// widget form creation
	
	function apcn_form($instance) 
	{
		
			
			// Check values
			
			if( $instance) 
			{
				
				$title = esc_attr($instance['title']);
				/* $text = esc_attr($instance['text']);
				$textarea = esc_textarea($instance['textarea']);
			 */
			} else 
			{
				
				$title = '';
			
				/* $text = '';
			
				$textarea = ''; */
			
			}
		
			?>
		
		 
		
		<p>
	
		<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Widget Title', 'wp_widget_plugin'); ?></label>
		
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
	
		</p>
		
		 
		
              <!--<p> 
	
		<label for="<?php echo $this->get_field_id('text'); ?>"><?php _e('Text:', 'wp_widget_plugin'); ?></label>
	
		<input class="widefat" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>" type="text" value="<?php echo $text; ?>" />
	
		</p>
	
		<p> 
	
		<label for="<?php echo $this->get_field_id('textarea'); ?>"><?php _e('Textarea:', 'wp_widget_plugin'); ?></label>
		
		<textarea class="widefat" id="<?php echo $this->get_field_id('textarea'); ?>" name="<?php echo $this->get_field_name('textarea'); ?>"><?php echo $textarea; ?></textarea>
 		</p>  -->

		<?php
		
	}
				
	
	
	
	// widget update
	
	function apcn_update($new_instance, $old_instance) {
	
	$instance = $old_instance;
	
	// Fields
	
	$instance['title'] = strip_tags($new_instance['title']);
	
	/* $instance['text'] = strip_tags($new_instance['text']);
	
	$instance['textarea'] = strip_tags($new_instance['textarea']);
	 */
	return $instance;
	
}

		



	// widget display

	function widget($args, $instance) 
    {
		extract( $args );

// these are the widget options

$title = apply_filters('widget_title', $instance['title']);

/* $text = $instance['text'];

$textarea = $instance['textarea'];
 */
echo $before_widget;

// Display the widget

echo '<div class="widget-text wp_widget_plugin_box">';


// Check if title is set

if ( $title ) 
{
	
	echo $before_title . $title . $after_title;
	
}



// Check if text is set

/* if( $text ) 
{
	
	echo '<p class="wp_widget_plugin_text">'.$text.'</p>';
	
}

// Check if textarea is set

if( $textarea ) 
 {
	
	echo '<p class="wp_widget_plugin_textarea">'.$textarea.'</p>';
	
 } */

echo '</div>';

echo $after_widget;
global $wpdb;
$id=$GLOBALS['post']->ID;


$qry1= $wpdb->get_results("select wordcount,charcount from autotable where id=$id");
if(count($qry1) > 0) {
echo "<br>no of words:".$qry1[0]->wordcount;
echo "<br>no of characters:".$qry1[0]->charcount;
}


?>

<div>

</div>
<?php 
}



		/* ... */
}



// register widget

add_action('widgets_init', create_function('', 'return register_widget("apcn_wp_my_plugin");'));

	?>