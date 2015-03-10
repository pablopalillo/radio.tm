<?php
/**
 * Plugin Name: RiotSchedule
 * Plugin URI: http://www.mattyribbo.co.uk/riotschedule
 * Description: Plugin to display weekly schedule for TV/Radio
 * Version: 1.1b2
 * Author: Matt Ribbins
 * Author URI: http://www.mattyribbo.co.uk
 * Copyright 2013 Matt Ribbins (matt@mattyribbo.co.uk)
 * 
 * Based from the 'Weekly Schedule' plugin from http://yannickcorner.nayanna.biz/wordpress-plugins/
 * Weekly Schedule (C) Yannick Lefebvre  (email : ylefebvre@gmail.com)   
 * 
 * 
 * This program is free software; you can redistribute it and/or modify   
 * it under the terms of the GNU General Public License as published by    
 * the Free Software Foundation; either version 2 of the License, or    
 * (at your option) any later version.    
 * 
 * This program is distributed in the hope that it will be useful,    
 * but WITHOUT ANY WARRANTY; without even the implied warranty of    
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the    
 * GNU General Public License for more details.    
 * 
 * You should have received a copy of the GNU General Public License    
 * along with this program; if not, write to the Free Software    
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
 
$version = "1.1b2";


if (is_file(trailingslashit(ABSPATH.PLUGINDIR).'riotschedule.php')) {
	define('WS_FILE', trailingslashit(ABSPATH.PLUGINDIR).'riotschedule.php');
}
else if (is_file(trailingslashit(ABSPATH.PLUGINDIR).'riotschedule/riotschedule.php')) {
	define('WS_FILE', trailingslashit(ABSPATH.PLUGINDIR).'riotschedule/riotschedule.php');
}

// Action hooks
add_action( 'init', 'rs_create_schedule_post' );

// Action hooks - admin page
add_action( 'admin_init', 'rs_create_schedule_post_admin' );
add_action( 'save_post', 'rs_add_schedule_item', 10, 2 );
add_action( 'delete_post', 'rs_delete_schedule_item');
add_action( 'wp_trash_post', 'rs_trash_schedule_item');

// Action hooks - widget
add_action( 'widgets_init', 'rs_register_widget' );

// Action hooks - admin menu
add_action( 'admin_menu', array('WS_Admin','add_config_page') );
add_action( 'manage_shows_posts_custom_column', 'rs_manage_schedule_columns', 10, 2);

// Action hooks - AJAX queries
add_action('wp_ajax_rs_show_now', 'rs_ajax_text_show_now');
add_action('wp_ajax_nopriv_rs_show_now', 'rs_ajax_text_show_now');
add_action('wp_ajax_rs_show_next', 'rs_ajax_text_show_next');
add_action('wp_ajax_nopriv_rs_show_next', 'rs_ajax_text_show_next');

// Action hooks - RSS and XML feed
add_action('wp_ajax_rs_ajax_text_show_now', 'rs_ajax_text_show_now');
add_action('wp_ajax_nopriv_rs_ajax_text_show_now', 'rs_ajax_text_show_now');
add_action('wp_ajax_rs_rss_feed_now_next', 'rs_rss_feed_now_next');
add_action('wp_ajax_nopriv_rs_rss_feed_now_next', 'rs_rss_feed_now_next');
add_action('wp_ajax_rs_xml_feed_now_next', 'rs_xml_feed_now_next');
add_action('wp_ajax_nopriv_rs_xml_feed_now_next', 'rs_xml_feed_now_next');


// Register shortcodes
add_shortcode('riotschedule', 'rs_library_func');
add_shortcode('riotschedule-flat', 'rs_library_flat_func');
add_shortcode('riotschedule-day', 'rs_day_list_func' );
add_shortcode('riotschedule-nownext', 'rs_now_next_func');
add_shortcode('riotschedule-listall', 'rs_list_shows_func');
add_shortcode('riotschedule-pid', 'rs_pid_list_func');


// Post filters
add_filter('the_posts', 'rs_conditionally_add_scripts_and_styles'); // the_posts gets triggered before wp_head
add_filter('manage_edit-shows_columns', 'rs_admin_schedule_columns');
add_filter('manage_edit-shows_sortable_columns', 'rs_admin_schedule_sortable_columns');
add_filter( 'request', 'rs_admin_schedule_columns_orderby' );
add_filter( 'template_include', 'rs_include_template_function', 1 );


// Register Widget
function rs_register_widget() {
    register_widget( "RSTodayScheduleWidget" );
}

// Installation hook
register_activation_hook(WS_FILE, 'rs_install');


// Common
function rs_return_default_options($options) {
	$options['starttime'] = 10;
	$options['endtime'] = 24;
	$options['timedivision'] = 1.0;
	$options['tooltipwidth'] = 300;
	$options['tooltiptarget'] = 'right center';
	$options['tooltippoint'] = 'left center';
	$options['tooltipcolorscheme'] = 'ui-tooltip';
	$options['displaydescription'] = "tooltip";
	$options['daylist'] = "";
	$options['timeformat'] = "24hours";
	$options['layout'] = 'horizontal';
	$options['adjusttooltipposition'] = true;
	$options['schedulename'] = "Default";
	$options['linktarget'] = "newwindow";		
	return $options;
}

function rs_return_default_genoptions($genoptions) {
	$genoptions['stylesheet'] = "stylesheet.css";
	$genoptions['numberschedules'] = 1;
	$genoptions['debugmode'] = false;
	$genoptions['includestylescript'] = "";
	$genoptions['frontpagestylescript'] = false;
	$genoptions['version'] = "1.1";
	return $genoptions;
}

function rs_set_default_days() {
	$schedule = 1;
	$sqlstatement = "INSERT INTO " . $wpdb->prefix . "rs_days (`id`, `name`, `rows`, `scheduleid`) VALUES
					(1, 'Mon', 1, " . $schedule . "),
					(2, 'Tue', 1, " . $schedule . "),
					(3, 'Wed', 1, " . $schedule . "),
					(4, 'Thu', 1, " . $schedule . "),
					(5, 'Fri', 1, " . $schedule . "),
					(6, 'Sat', 1, " . $schedule . "),
					(7, 'Sun', 1, " . $schedule . ")";
	$result = $wpdb->query($sqlstatement);
}

function rs_set_default_category($schedule) {
	$sqlstatement = "INSERT INTO " . $wpdb->prefix . "rs_categories (`name`, `scheduleid`) VALUES ('Default', " . $schedule . ")";
			$result = $wpdb->query($sqlstatement);	
}

function rs_get_day_string( $day ) {
	global $wpdb;
	$days = $wpdb->get_results("SELECT * from " . $wpdb->prefix. "rs_days where scheduleid = 1 ORDER by id");
	foreach ($days as $myday) {
		if ($myday->id == $day)
		return $myday->name;
	}	
}

function rs_display_pid_day_time( $pid ) {
	// Display day and time for a show with $pid
	global $wpdb;

    //Fetch the results
    $schedule_query = 'SELECT * from ' . $wpdb->prefix . 'rs_items WHERE pid = ' . $pid . ' LIMIT 0, 1';

    $schedule_item = $wpdb->get_row( $schedule_query );
    if ( ! empty( $schedule_item ) ) {
		$item_day = rs_get_day_string($schedule_item->day);
		$item_time = rs_get_time_string($schedule_item->starttime);
		
		echo $item_day . ' ' . $item_time;
    }
}

function rs_get_time_string( $in ) {
	// Generate readable time
	
	$options = get_option($settingsname);
	if ($options == "") {
		$options = rs_return_default_options($options);
	}
	
	if (fmod($in, 1) == 0.25)
		$minutes = "15";
	elseif (fmod($in, 1) == 0.50)
		$minutes = "30";
	elseif (fmod($in, 1) == 0.75)
		$minutes = "45";
	else
		$minutes = "00";

	if ($timeformat == "24hours" || $timeformat == "") {
		$output .= floor( $in ) . ":" . $minutes;
	} else if ($timeformat == "12hours") {
		if ($in < 12) {
			$timeperiod = "am";
			if ($in == 0)
				$hour = 12;
			else
				$hour = floor($in);
		} else {
			$timeperiod = "pm";
			if ($i >= 12 && $i < 13)
				$hour = floor($in);
			else
				$hour = floor($in) - 12;
		}
		
		$output .= $hour;
		if ($minutes != "") 
			$output .= ":" . $minutes;
		$output .=  $timeperiod;
	}
	return $output;
}

// Custom Post
function rs_create_schedule_post() {
	// Register the custom post type
	register_post_type( 'shows',
        array(
            'labels' => array(
                'name' => 'Scheduled Items',
                'singular_name' => 'Scheduled Item',
                'add_new' => 'Add New',
                'add_new_item' => 'Add New Scheduled Item',
                'edit' => 'Edit',
                'edit_item' => 'Edit Scheduled Item',
                'new_item' => 'New Scheduled Item',
                'view' => 'View',
                'view_item' => 'View Scheduled Item',
                'search_items' => 'Search Schedule',
                'not_found' => 'No Scheduled Items Found',
                'not_found_in_trash' => 'No Scheduled Items found in Trash',
                'parent' => 'Parent Scheduled Item'
            ),
			'description' => 'Scheduled Item',
			'exclude_from_search' => false,
            'public' => true,
			'show_ui' => true,
            'menu_position' => 15,
            'supports' => array( 'title', 'editor', 'author', 'comments', 'thumbnail', 'revisions' ),
            'taxonomies' => array( '' ),
            'menu_icon' => plugins_url( 'icons/calendar.png', __FILE__ ),
            'has_archive' => true,
			'capability_type' => 'post',
			//'capabilities' => array(
			//	'edit_post' => 'edit_schedule',
			//	'edit_posts' => 'edit_schedules',
			//	'edit_others_posts' => 'edit_others_schedules',
			//	'publish_posts' => 'publish_schedules',
			//	'read_post' => 'read_schedule',
			//	'read_private_posts' => 'read_private_schedules',
			//	'delete_post' => 'delete_schedule'
			//),
        )
    );
}

function rs_create_schedule_post_admin() {
	add_meta_box('scheduled_item_meta_box',
        'Scheduled Item Details',
        'rs_display_scheduled_item_meta_box',
        'shows', 'side', 'high'
    );	
}

function rs_display_scheduled_item_meta_box( $scheduled_item ) {
	// Display custom post meta box for scheduled items
	global $wpdb;
	
    $schedule_id = $scheduled_item->ID;
	$schedule = 1;

	$settingsname = 'RS_PP' . $schedule;
	$options = get_option($settingsname);
	
	if ($options == "") {
		$options = rs_return_default_options($options);
	
		$schedulename = 'RS_PP' . $schedule;
		update_option($schedulename, $options);
				
		$catsresult = $wpdb->query("SELECT * from " . $wpdb->prefix . "rs_categories where scheduleid = " . $schedule);

		if (!$catsresult) {
			rs_set_default_category($schedule);
		}

		$wpdb->rs_days = $wpdb->prefix.'rs_days';
										
		$daysresult = $wpdb->query("SELECT * from " . $wpdb->prefix . "rs_days where scheduleid = " . $schedule);
						
		if (!$daysresult) {
			rs_set_default_days();
		}
	}
	
	$genoptions = get_option("RiotScheduleGeneral");
	if ($genoptions == false) {
		$genoptions = rs_return_default_genoptions($genoptions);
	}
	
	$itemexist = $wpdb->get_row("SELECT * from " . $wpdb->prefix . "rs_items WHERE pid = " . $schedule_id);
			
    ?>
    <table>
    				<tr>
                    	<td>Show Type</td>
                        <td>
                        <select style='width: 150px' name="type">
							<option value='1' <?php selected( 1, $itemexist->type );?>>Scheduled Item</option>
                            <option value='2' <?php selected( 2, $itemexist->type );?>>Unscheduled Item</option>
                    	</select>
                        </td>
                    </tr>
                    <?php if ( $itemexist->type == 2 ) { ?>
					<tr>
                    	<td>Enable Unscheduled Item</td>
                        <td><input type="checkbox" <?php checked ( $itemexist->enabled ); ?> name="enabled" value="enabled" /></td>
                    </tr>
					<?php } ?>
    				<tr>
					<td>Short Description (One line)</td>
					<td><textarea name="description" style='width: 150px;' rows="5"><?php if ($itemexist) { echo htmlspecialchars(stripslashes($itemexist->description)); }?></textarea>
                    </td>
                    </tr>
    				<tr>
					<td>Day</td>
                    <td><select style='width: 150px' name="day">
					<?php $days = $wpdb->get_results("SELECT * from " . $wpdb->prefix. "rs_days where scheduleid = " . $schedule . " ORDER by id");
					
						foreach ($days as $day) {
							if ($day->id == $itemexist->day)
									$selectedstring = "selected='selected'";
								else 
									$selectedstring = ""; 
									
							echo "<option value='" . $day->id . "' " . $selectedstring . ">" .  $day->name . "</option>\n";
						}
					?></select></td>
					</tr>
                    
					<td>Start Time</td>
					<td><select style='width: 150px' name="starttime">
					<?php for ($i = $options['starttime']; $i < $options['endtime']; $i += $options['timedivision']) {
						  		if ($options['timeformat'] == '24hours')
									$hour = floor($i);
								elseif ($options['timeformat'] == '12hours') {
									if ($i < 12) {
										$timeperiod = "am";
										if ($i == 0)
											$hour = 12;
										else
											$hour = floor($i);
									} else {
										$timeperiod = "pm";
										if ($i >= 12 && $i < 13)
											$hour = floor($i);
										else
											$hour = floor($i) - 12;
									}
								}
									
								
								if (fmod($i, 1) == 0.25)
                                    $minutes = "15";
								elseif (fmod($i, 1) == 0.50)
									$minutes = "30";
								elseif (fmod($i, 1) == 0.75)
									$minutes = "45";
                                else
                                    $minutes = "00";
									
 								if ($i == $itemexist->starttime)
									$selectedstring = "selected='selected'";
								else 
									$selectedstring = ""; 

								if ($options['timeformat'] == '24 hours')
									echo "<option value='" . $i . "'" . $selectedstring . ">" .  $hour . "h" . $minutes . "\n";
								else
									echo "<option value='" . $i . "'" . $selectedstring . ">" .  $hour . ":" . $minutes . $timeperiod . "\n";
						  }
					?></select></td>
					</tr>
					<tr>
					<td>Duration</td>
					<td><select style='width: 150px' name="duration">
					<?php for ($i = $options['timedivision']; $i <= ($options['endtime'] - $options['starttime']); $i += $options['timedivision'])
						  {
								if (fmod($i, 1) == 0.25)
                                    $minutes = "15";
								elseif (fmod($i, 1) == 0.50)
									$minutes = "30";
								elseif (fmod($i, 1) == 0.75)
									$minutes = "45";
                                else
                                    $minutes = "00";
									
 								if ($i == $itemexist->duration) 
									$selectedstring = "selected='selected'";
								else 
									$selectedstring = "";

								echo "<option value='" . $i . "' " . $selectedstring . ">" .  floor($i) . "h" . $minutes . "\n";
						  }
					?></select></td>
                    </tr>
                    <tr>
                    <td>Category</td>
                    <td><select style='width: 150px' name="category">
                    <?php 
						$categories = $wpdb->get_results("SELECT * from " . $wpdb->prefix. "rs_categories ORDER by name");
					
						foreach ($categories as $category) {
						
							if ($day->category == $itemexist->category)
									$selectedstring = "selected='selected'";
								else 
									$selectedstring = ""; 
									
							echo "<option value='" . $category->id . "' " . $selectedstring . ">" .  $category->name . "</option>\n";
						}
					?>
                    </select></td>
                    </tr>
                    <tr>
                    <td>Schedule ID</td>
                    <td><select style='width: 100px' name="schedule">
                    <?php
						for( $i = 1; $i <= $genoptions['numberschedules']; $i++ ) {
							
							echo "<option value='" . $i . "' ". selected( $i, $itemexist->schedule ) . ">" . $i . "</option>\n";
						}
					?>
                    </select></td>
                    </tr>
                    <input type="hidden" name="riotschedule_submit" value="riotschedule_submit" />
    </table>
    <?php
}

function rs_add_schedule_item( $schedule_id, $schedule_item ) {
	// Add or modify scheduled ite
	global $wpdb;
	if ( ( !wp_is_post_revision( $schedule_id ) ) && ( $schedule_item->post_type == 'shows' ) && ( $schedule_item->post_status == 'publish' ) ) {
		if( isset ( $_POST['riotschedule_submit'] ) ) {
			// Compile our items
			$newitem = array( 'name' => $_POST['post_title'],
							  'description' => $_POST['description'],
							  'starttime' => $_POST['starttime'],
							  'category' => $_POST['category'],
							  'duration' => $_POST['duration'],
							  'day' => $_POST['day'],
							  'scheduleid' => $_POST['schedule'],
							  'pid' => $schedule_id,
							  'enabled' => 0,
							  'type' => $_POST['type']
							 );
			// Force enable. Scheduled items are always enabled. Unscheduled items are disabled by default
			if($newitem['type'] == '1') {
				$newitem['enabled'] = '1';
			}
			if( $newitem['type'] == '2' ) {

				if ( isset ( $_POST['enabled'] ) ) {
					$newitem['enabled'] = '1' ;
				} else {
					$newitem['enabled'] = '0';
				}
			}
			
			// Search for matching PID in our database
			$endtime = $newitem['starttime'] + $newitem['duration'];
			$conflictquery  = "SELECT * from " . $wpdb->prefix . "rs_items where pid = " . $schedule_id;

			$conflictingitems = $wpdb->get_results($conflictquery);
			if ($conflictingitems) {
				$update = true;
			} else {
				$update = false;
			}
			if ( $update ) {
				// We're updating a current record.
				$wpdb->update( $wpdb->prefix . 'rs_items', $newitem, array( 'pid' => $schedule_id ));
				//echo '<div id="message" class="updated fade"><p><strong>Updated Scheduled Item</strong></div>';
			} else {
				$wpdb->insert( $wpdb->prefix . 'rs_items', $newitem);
				//echo '<div id="message" class="updated fade"><p><strong>Inserted New Scheduled Item</strong></div>';
			}
		} 
		if ( ( $schedule_item->post_status == 'draft' ) || ( $schedule_item->post_status == 'pending' ) ) {
			// Don't enable draft/pending
			$wpdb->update( $wpdb->prefix . 'rs_items', array( 'enabled' => false ), array( 'pid' => $schedule_id ));			
		} else {
			// Seems we're just re-enabling this scheduled item or updating the item
			if ( $newitem['type'] == '1' ) {
				$wpdb->update( $wpdb->prefix . 'rs_items', array( 'enabled' => true ), array( 'pid' => $schedule_id ));
			}
		}
	}
}

function rs_delete_schedule_item( $schedule_id ) {
	// Delete a scheduled item, called when schedule post is deleted!
	global $wpdb;
	echo "DELETE";
	$itemexist = $wpdb->get_row("SELECT * from " . $wpdb->prefix . "rs_items WHERE pid = " . $schedule_id);
	if ($itemexist) {
		$wpdb->query("DELETE from " . $wpdb->prefix . "rs_items WHERE pid = " . $schedule_id);
		//echo '<div id="message" class="updated fade"><p><strong>Scheduled Item Deleted</strong></div>';
	}
}

function rs_trash_schedule_item( $schedule_id, $schedule_item ) {
	// Disables a scheduled item when trashed.
	global $wpdb;
	echo "TRASH";
	$itemexist = $wpdb->get_row("SELECT * from " . $wpdb->prefix . "rs_items WHERE pid = " . $schedule_id);
	if ($itemexist) {
		//$wpdb->query("UPDATE " . $wpdb->prefix . "rs_items SET enabled = '0' WHERE pid = " . $schedule_id);
		$wpdb->update( $wpdb->prefix . 'rs_items', array( 'enabled' => false ), array( 'pid' => $schedule_id ));
		//echo '<div id="message" class="updated fade"><p><strong>Scheduled Item Trashed</strong></div>';
	}
}

// Scheduled post widget
class RSTodayScheduleWidget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
	 		'riotschedule_widget', // Base ID
			'RiotSchedule Widget', // Name
			array( 'description' => 'Displays a list of schedule items' ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
        global $wp_locale;
        extract( $args );	  
	  
        $title = apply_filters( 'widget_title', $instance['title'] );
		$max_items = ( !empty( $instance['max_items'] ) ? $instance['max_items'] : 5 );
        $schedule_id = ( !empty( $instance['schedule_id'] ) ? $instance['schedule_id'] : 1 );
        $empty_msg = ( !empty( $instance['empty_msg'] ) ? $instance['empty_msg'] : 'No Items Found' );
		$today = ( ( !empty( $instance['day'] ) && ( $instance['day'] != 0 ) ) ? $instance['day'] : ( date( 'N', current_time( 'timestamp', 0 ) ) ) );
		echo $before_widget;
		if ( ! empty( $title ) )
			echo $before_title . $title . $after_title;

		// Fetch results
		global $wpdb;
		
		$schedule_query = 'SELECT * from ' . $wpdb->prefix . 'rs_items WHERE day = ' . $today . ' AND scheduleid = ' . $schedule_id . ' AND type = 1 AND enabled = 1 ORDER by starttime ASC LIMIT 0, ' . $max_items;
        
		$schedule_items = $wpdb->get_results( $schedule_query );
		
		if ( ! empty( $schedule_items ) ) {
            echo '<ul>';
		  
            foreach ( $schedule_items as $schedule_item ) {
                $item_name = stripslashes( $schedule_item->name );
                $start_hour = $schedule_item->starttime;
				$address = get_permalink($schedule_item->pid);
				$pid = $schedule_item->pid;
                
                if( strpos( $start_hour, '.' ) > 0 ) {
                    $start_hour = substr( $start_hour, 0, strlen( $start_hour ) - strpos( $start_hour, '.' ) );
                    $start_hour .= ':30';
                } else {
                    $start_hour .= ":00";
                }
                
                echo '<li>';
                
                echo  $start_hour . ' - ';
				if ( !empty( $address ) ) {
                    echo '<a href="' . $address . '">';
                }
				echo  $item_name;
                
                if ( !empty( $address ) ) {
                    echo '</a>';
                }
                echo '</li>';
            }
		  
		  echo '</ul>';
		} else {
		  echo $empty_msg;  
		}
		
		echo $after_widget;
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['max_items'] = strip_tags( $new_instance['max_items'] );
		
        if ( is_numeric ( $new_instance['schedule_id'] ) )
            $instance['schedule_id'] = intval( $new_instance['schedule_id'] );
        else
            $instance['schedule_id'] = $old_instance['schedule_id'];
			
		if ( is_numeric ( $new_instance['day'] ) )
            $instance['day'] = intval( $new_instance['day'] );
        else
            $instance['day'] = $old_instance['day'];
        
        $instance['empty_msg'] = strip_tags( $new_instance['empty_msg'] );

		return $instance;
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		global $wpdb;
		
		/* Set initial values/defaults */
        $title = ( !empty( $instance['title'] ) ? $instance['title'] : "Today's Scheduled Items" );
		$max_items = ( !empty( $instance['max_items'] ) ? $instance['max_items'] : 10 );
        $schedule_id = ( !empty( $instance['schedule_id'] ) ? $instance['schedule_id'] : 1 );
        $empty_msg = ( !empty( $instance['empty_msg'] ) ? $instance['empty_msg'] : 'No Items Found' );
		$day = ( !empty( $instance['day'] ) ? $instance['empty_msg'] : 0);
        
       	$genoptions = get_option( 'RiotScheduleGeneral' );
		?>

		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
        
        <p>
		<label for="<?php echo $this->get_field_id( 'empty_msg' ); ?>">Empty Item List Message:</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'empty_msg' ); ?>" name="<?php echo $this->get_field_name( 'empty_msg' ); ?>" type="text" value="<?php echo esc_attr( $empty_msg ); ?>" />
		</p>
        
        <p>
		<label for="<?php echo $this->get_field_id( 'day' ); ?>">Day:</label>
        <select style='width: 150px' id="<?php echo $this->get_field_id( 'day' ); ?>" name="<?php echo $this->get_field_name( 'day' ); ?>"><?php 
			echo "<option value='0' " . selected( 0, esc_attr( $day ) ) . ">Current Day</option>\n";
			$mydays = $wpdb->get_results("SELECT * from " . $wpdb->prefix. "rs_days where scheduleid = 1 ORDER by id");
			foreach ($mydays as $myday) {
				echo "<option value='" . $myday->id . "' " . selected( $myday->id, esc_attr( $day ) ) . ">" .  $myday->name . "</option>\n";
			}
		?></select>
		</p>

		<p>
		<label for="<?php echo $this->get_field_id( 'max_items' ); ?>">Max Number of Items:</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'max_items' ); ?>" name="<?php echo $this->get_field_name( 'max_items' ); ?>" type="text" value="<?php echo esc_attr( $max_items ); ?>" />
		<span class='description'><?php __( 'Maximum number of items to display' ); ?></span>
		</p>

        <p>
            <label for="<?php echo $this->get_field_id( 'schedule_id' ); ?>">Schedule ID</label>

            <select class="widefat" id="<?php echo $this->get_field_id( 'schedule_id' ); ?>" name="<?php echo $this->get_field_name( 'schedule_id' ); ?>">
            <?php if ( empty( $genoptions['numberschedules'] ) ) $number_of_schedules = 2; else $number_of_schedules = $genoptions['numberschedules'];
                for ($counter = 1; $counter <= $number_of_schedules; $counter++): ?>
                    <?php $tempoptionname = "RS_PP" . $counter;
                       $tempoptions = get_option($tempoptionname); ?>
                       <option value="<?php echo $counter ?>" <?php selected( $schedule_id, $counter ); ?>>Schedule <?php echo $counter ?><?php if ($tempoptions != "") echo " (" . $tempoptions['schedulename'] . ")"; ?></option>
                <?php endfor; ?>
            </select>
        </p>
        
		<?php
	}

}

function rs_library_func($atts) {
	extract(shortcode_atts(array(
		'schedule' => ''
	), $atts));
	
	if ($schedule == '') {
		$options = get_option('RS_PP1');
		$schedule = 1;
	} else {
		$schedulename = 'RS_PP' . $schedule;
		$options = get_option($schedulename);
	}
	
	if ($options == false) {
		return "Requested schedule (Schedule " . $schedule . ") is not available.<br />";
	}
	
	return rs_library($schedule, $options['starttime'], $options['endtime'], $options['timedivision'], $options['layout'], $options['tooltipwidth'], $options['tooltiptarget'], $options['tooltippoint'], $options['tooltipcolorscheme'], $options['displaydescription'], $options['daylist'], $options['timeformat'], $options['adjusttooltipposition'], $options['linktarget']);
}

function rs_now_next_func( $atts ) {
	// Display now and next information
	global $wpdb;

	// Get shortcode attributes
	extract( shortcode_atts( array(
		'mode' => 'now',
		'offset' => '0',
		'display' => 'name',
		'default_message' => 'Non-stop Music 24/7',
		'schedule' => '1'
	), $atts ) );
	
	// Default message
	$on_air = $default_message;	

	$items_show = false;
	
	// Get the *WORDPRESS* time in Unix format
	$u_time = current_time("timestamp");
	// Get the day number (Mon=1,Tue=2...)
	$wsc_day = date("N",$u_time); 
	// Get the hour number, andd any offset
	$wsc_hour = date("G",$u_time + $offset);

	// Current show mode
	if($mode == 'now') {
		// Find the most recent show
		$sqlitems = "SELECT * FROM " . $wpdb->prefix . "rs_items WHERE day = '" . $wsc_day . "' AND starttime  <= '" . $wsc_hour . "' AND enabled = 1 AND scheduleid = " . $schedule . " ORDER BY starttime DESC, type DESC LIMIT 0,1";
		$items = $wpdb->get_row($sqlitems,ARRAY_A);
		// Check that there is a show that has happened or is happening
		if($items) {
			// Check that the show is still running
			$show_end = $items['duration'] + $items['starttime'];
			if($show_end > $wsc_hour) {
				$items_show = true;	
			}
		} else {
			// Look at yesterday, could be an overlapping show
		}

	}
	
	// Next show mode
	if($mode == 'next') {
		// Find the next item after the current hour
		$sqlitems = "SELECT * FROM ".$wpdb->prefix."rs_items WHERE day = '".$wsc_day."' AND starttime > '".$wsc_hour."' AND enabled = 1 AND AND scheduleid = " . $schedule . " ORDER BY starttime ASC, type DESC LIMIT 0,1";
		$items = $wpdb->get_row($sqlitems,ARRAY_A);
		// If items found, that is the next show. If no items found, check next day up until 1pm!
		if(!$items) {
			// Increment day
			if($wsc_day = 7) {
				$wsc_day = 1;
			} else {
				$wsc_day++;
			}
			// Search again
			$sqlitems = "SELECT * FROM ".$wqdb->prefix."rs_items WHERE day = '".$wsc_day."' AND starttime < 13 LIMIT 0,1";
			$items = $wpdb->get_row($sqlitems,ARRAY_A);
			if($items) {
				$items_show = true;
			}
		} else {
			$items_show = true;
		}
	}
	
	// Display results
	if($items_show == true) {
		if($display == "name") { 
			$on_air = "<a target=\"_parent\" href=\"" . get_permalink($items['pid']) . "\">".$items['name']."</a>";
		}
		else if($display == "time") { 
			$on_air = $items['starttime'].":00"; 
		} else if($display == "pid") {
			$on_air = $items['pid'];	
		} else if($display == "desc") {
			$on_air = $items['description'];	
		}
		else { 
			$on_air = $items['name']; 
		}
	} else {
		if($display == "time") {
			// We have no time, just show infinity
			$on_air = "&#8734;";
		}
		if($display == "desc") {
			$on_air = "Playing top charting music, classic hits and more!";
		}
	}
				
	// Remove the slash apostrophes
	$on_air = stripslashes($on_air);
	return $on_air;
}

function rs_ajax_text_show_now() {
	$show_now = rs_now_next_func(array("mode" => "now"));
	echo strip_tags($show_now);
	die();
}

function rs_ajax_text_show_next() {
	$show_now = rs_now_next_func(array("mode" => "next"));
	echo strip_tags($show_now);
	die();
}



function rs_rss_feed_now_next() {
	// Displays RSS feed of now and next
	$show_now['name'] = rs_now_next_func(array("mode" => "now"));
	$show_now['time'] = rs_now_next_func(array("mode" => "now", "display" => "time"));
	$show_next['name'] = rs_now_next_func(array("mode" => "next"));
	$show_next['time'] = rs_now_next_func(array("mode" => "next", "display" => "time"));
	echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<rss version=\"0.92\">\n";
	echo "<channel>\n<title>" . get_bloginfo("name") . " Now and Next</title>\n<description>Shows the Now and Next shows on " . get_bloginfo("name") . "</description>\n";
	echo "<link>http://www.hubradio.co.uk</link>\n";
	echo "<item><guid>http://www.hubradio.co.uk/?now</guid><title>".strip_tags($show_now['name'])." - ".strip_tags($show_now['time'])."</title><description>".strip_tags($show_now['name'])."</description></item\n>";
	echo "<item><guid>http://www.hubradio.co.uk/?now</guid><title>".strip_tags($show_next['name'])." - ".strip_tags($show_next['time'])."</title><description>".strip_tags($show_next['time'])."</description></item>\n";
	echo "</channel>\n</rss>\n";
	die();
}

function rs_xml_feed_now_next() {
	$show_now = rs_now_next_func(array("mode" => "now"));
	$show_next = rs_now_next_func(array("mode" => "next"));
	echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<root><show>".strip_tags($show_now)."</show>\n<show>".strip_tags($show_next)."</show>\n</root>";
	die();
}

function rs_day_list_func( $atts ) {
	// Display schedule for a day in a list
	global $wpdb;
	extract(shortcode_atts(array(
		'schedule' => 1,
        'max_items' => 20,
        'empty_msg' => 'No Shows Found',
		'day' => date('N', current_time('timestamp', 0)),
		'period_start' => 0,
		'period_end' => 24,
		'thumbnail' => 0,
		'thumb_height' => 100,
		'thumb_width' => 100,
		'block_name_desc' => 0,
		'type' => 1
	), $atts));
    
    $today = $day;
    $output = '<div class="rs_widget_output">';

    //Fetch the results
    $schedule_query = 'SELECT * from ' . $wpdb->prefix . 
                      'rs_items WHERE day = ' . $today . 	
					  ' AND enabled = 1' .		
                      ' AND scheduleid = ' . $schedule . 
					  ' AND starttime >= ' . $period_start .
					  ' AND starttime < ' . $period_end .
					  ' AND type = ' . $type .
					  ' ORDER by starttime ASC, type DESC LIMIT 0, ' . $max_items;

    $schedule_items = $wpdb->get_results( $schedule_query );

    if ( ! empty( $schedule_items ) ) {
        $output .= '<ul>';

        foreach ( $schedule_items as $schedule_item ) {
            $item_name = stripslashes($schedule_item->name);
			$item_desc = stripslashes($schedule_item->description);
			$start_hour = $schedule_item->starttime;
			$address = get_permalink($schedule_item->pid);
			$pid = $schedule_item->pid;

            if( strpos($start_hour, '.') > 0 ) {
                $start_hour = substr($start_hour, 0, strlen($start_hour) - strpos($start_hour, '.'));
                $start_hour .= ':30';
            } else {
                $start_hour .= ":00";
            }
			if(! empty( $address ) ) {
                $output .=  '<a href="' . $address . '">';
            }
			
            $output .= '<li class="hub-schedule-time-li">';
            
            $output .= '<div class="hub-schedule-time-time">' . $start_hour . '</div>';
			
			if( $block_name_desc == 1 ) {
				$output .= '<div class="hub-schedule-time-block">';
			}
			$output .= '<div class="hub-schedule-time-name">';
			
			
			$output .= $item_name;


			$output .= '</div>';
			
			$output .= '<div class="hub-schedule-time-desc">' . $item_desc . '</div>';
			
			if( $block_name_desc == 1) {
				$output .= '</div>';
			}
			
			if( $thumbnail ) {
				$output .= '<div class="hub-schedule-time-thumb">';
				set_post_thumbnail_size( $thumb_width, $thumb_height, true );
				$output .= get_the_post_thumbnail( $pid );
				$output .= '</div>';
			}
            $output .= '</li>';
			
            if( ! empty( $address ) ) {
                $output .=  '</a>';
            }
        }

      $output .= '</ul>';
    } else {
      $output .= $empty_msg;  
    }

    $output .= '</div>';
    
    return $output;
}

function rs_pid_list_func( $atts ) {
	// Display schedule for a day in a list
	global $wpdb;
	extract(shortcode_atts(array(
		'pid' => 1,
        'empty_msg' => 'No Item Found',
		'thumbnail' => 0,
		'thumb_height' => 100,
		'thumb_width' => 100,
		'block_name_desc' => 0
	), $atts));
    
    $today = $day;
    $output = '<div class="rs_widget_output">';

    //Fetch the results
    $schedule_query = 'SELECT * from ' . $wpdb->prefix . 
                      'rs_items WHERE pid = ' . $pid . 	
					  ' AND enabled = 1' .
					  ' ORDER by starttime ASC, type DESC LIMIT 0, 1';

    $schedule_items = $wpdb->get_results( $schedule_query );

    if ( ! empty( $schedule_items ) ) {
        $output .= '<ul>';

        foreach ( $schedule_items as $schedule_item ) {
            $item_name = stripslashes($schedule_item->name);
			$item_desc = stripslashes($schedule_item->description);
			$start_hour = $schedule_item->starttime;
			$address = get_permalink($schedule_item->pid);
			$pid = $schedule_item->pid;

            if( strpos($start_hour, '.') > 0 ) {
                $start_hour = substr($start_hour, 0, strlen($start_hour) - strpos($start_hour, '.'));
                $start_hour .= ':30';
            } else {
                $start_hour .= ":00";
            }
			if(! empty( $address ) ) {
                $output .=  '<a href="' . $address . '">';
            }
			
            $output .= '<li class="hub-schedule-time-li">';
            
            $output .= '<div class="hub-schedule-time-time">' . $start_hour . '</div>';
			
			if( $block_name_desc == 1 ) {
				$output .= '<div class="hub-schedule-time-block">';
			}
			$output .= '<div class="hub-schedule-time-name">';
			
			
			$output .= $item_name;


			$output .= '</div>';
			
			$output .= '<div class="hub-schedule-time-desc">' . $item_desc . '</div>';
			
			if( $block_name_desc == 1) {
				$output .= '</div>';
			}
			
			if( $thumbnail ) {
				$output .= '<div class="hub-schedule-time-thumb">';
				set_post_thumbnail_size( $thumb_width, $thumb_height, true );
				$output .= get_the_post_thumbnail( $pid );
				$output .= '</div>';
			}
            $output .= '</li>';
			
            if( ! empty( $address ) ) {
                $output .=  '</a>';
            }
        }

      $output .= '</ul>';
    } else {
      $output .= $empty_msg;  
    }

    $output .= '</div>';
    
    return $output;
}

function rs_list_shows_func( $atts ) {
	// Display list of all shows
	global $wpdb;
	global $post;

	extract(shortcode_atts(array(
		'schedule' => 1,
        'empty_msg' => 'No Items Found',
		'thumbnail' => 0,
		'thumb_height' => 100,
		'thumb_width' => 100,
		'type' => 1
	), $atts));
    
    $today = $day;
	set_post_thumbnail_size( $thumb_width, $thumb_height, true );
	
	$postslist = get_posts( array(
		'posts_per_page' =>  -1,
		'orderby' => 'rand',
		'post_type' => 'shows'
	) );
	
	$output .= '<ul>';
	
	foreach ( $postslist as $post ) :  setup_postdata( $post ); 
		//Fetch the RiotSchedule specific results
    	$schedule_query = 'SELECT * from ' . $wpdb->prefix . 
						  'rs_items WHERE pid = ' . $post->ID . 
						  ' AND type = ' . $type .			
						  ' LIMIT 0, 1';
		$schedule_item = $wpdb->get_row( $schedule_query );
		if($schedule_item) {
			$output .= '<li class="riotschedule-fulllist-item">';
	
			$output .= '<a href=' . get_permalink() . '>';
	
			$output .= '<div class="riotschedule-fulllist-thumb">';
			$output .= get_the_post_thumbnail();			
			$output .= '</div>';
	
			$output .= '<div class="riotschedule-fulllist-title">';
			$output .= get_the_title();
			$output .= '</div>';
			
			$output .= '<div class="riotschedule-fulllist-time">';
			$output .= rs_get_day_string( $schedule_item->day ) . ' ' . rs_get_time_string( $schedule_item->starttime );
			$output .= '</div>';
			
			$output .= '<div class="riotschedule-fulllist-desc">';
			$output .= $schedule_item->description;
			$output .= '</div>';
	
			$output .= '</a>';
	
			$output .= '</li>';
		}
    endforeach; 


	$output .= '</ul>';
    
    return $output;
}

function rs_conditionally_add_scripts_and_styles($posts){
	if (empty($posts)) return $posts;
	
	$load_jquery = false;
	$load_qtip = false;
	$load_style = false;
	
	$genoptions = get_option('RiotScheduleGeneral');

	foreach ($posts as $post) {		
			$continuesearch = true;
			$searchpos = 0;
			$scheduleids = array();
			
			while ($continuesearch) {
				$weeklyschedulepos = stripos($post->post_content, 'riotschedule ', $searchpos);
				if ($weeklyschedulepos == false) {
					$weeklyschedulepos = stripos($post->post_content, 'riotschedule]', $searchpos);
				}
				$continuesearch = $weeklyschedulepos;
				if ($continuesearch) {
					$load_style = true;
					$shortcodeend = stripos($post->post_content, ']', $weeklyschedulepos);
					if ($shortcodeend)
						$searchpos = $shortcodeend;
					else
						$searchpos = $weeklyschedulepos + 1;
						
					if ($shortcodeend) {
						$settingconfigpos = stripos($post->post_content, 'settings=', $weeklyschedulepos);
						if ($settingconfigpos && $settingconfigpos < $shortcodeend) {
							$schedule = substr($post->post_content, $settingconfigpos + 9, $shortcodeend - $settingconfigpos - 9);
							$scheduleids[] = $schedule;
						} else if (count($scheduleids) == 0) {
							$scheduleids[] = 1;
						}
					}
				}	
			}
		}
		
		if ($scheduleids) {
			foreach ($scheduleids as $scheduleid) {
				$schedulename = 'RS_PP' . $scheduleid;
				$options = get_option($schedulename);			
				
				if ($options['displaydescription'] == "tooltip") {
					$load_jquery = true;
					$load_qtip = true;
				}					
			}
		}
			
		if ($genoptions['includescriptcss'] != '') {
			$pagelist = explode (',', $genoptions['includescriptcss']);
			foreach($pagelist as $pageid) {
				if (is_page($pageid)) {
					$load_jquery = true;
					$load_style = true;
					$load_qtip = true;				
				}
			}
		}
	
	if ($load_style) {		
		if ($genoptions == "")
			$genoptions['stylesheet'] = 'stylesheet.css';
			
		wp_enqueue_style('weeklyschedulestyle', get_bloginfo('wpurl') . '/wp-content/plugins/riotschedule/' . $genoptions['stylesheet']);	
	}
 
	if ($load_jquery) {
		wp_enqueue_script('jquery');
	}
	
	if ($load_qtip) {
		wp_enqueue_style('qtipstyle', get_bloginfo('wpurl') . '/wp-content/plugins/riotschedule/jquery-qtip/jquery.qtip-2.0.min.css');
		wp_enqueue_script('qtip', get_bloginfo('wpurl') . '/wp-content/plugins/riotschedule/jquery-qtip/jquery.qtip-2.0.min.js');
	}
	 
	return $posts;
}

function rs_library($scheduleid = 1, $starttime = 10, $endtime = 24, $timedivision = 1, $layout = 'horizontal', $tooltipwidth = 300, $tooltiptarget = 'right center', $tooltippoint = 'leftMiddle', $tooltipcolorscheme = 'ui-tooltip', $displaydescription = 'tooltip', $daylist = '', $timeformat = '24hours', $adjusttooltipposition = true, $linktarget = 'newwindow') {
	// Displays schedule in week overview format
	global $wpdb;	
	
	$numberofcols = ($endtime - $starttime) / $timedivision;
	
	$output = "<!-- Weekly Schedule Output -->\n";
	$output .= "<div class='ws-schedule' id='ws-schedule" . $scheduleid . "'>\n";
	
	if ($layout == 'horizontal' || $layout == '') {
		$output .= "<table>\n";	
	} elseif ($layout == 'vertical') {
		$output .= "<div class='verticalcolumn'>\n";
		$output .= "<table class='verticalheader'>\n";
	}
	
	$output .= "<tr class='topheader'>";
	$output .= "<th class='rowheader'></th>";
	
	if ($layout == 'vertical') {
		$output .= "</tr>\n";
	}

	for ($i = $starttime; $i < $endtime; $i += $timedivision)	{
	
	if (fmod($i, 1) == 0.25)
		$minutes = "15";
	elseif (fmod($i, 1) == 0.50)
		$minutes = "30";
	elseif (fmod($i, 1) == 0.75)
		$minutes = "45";
	else
		$minutes = "";


		if ($timeformat == "24hours" || $timeformat == "") {
			if ($layout == 'vertical')
				$output .= "<tr class='datarow'>";
			
			$output .= "<th>" .  floor($i) . "h" . $minutes . "</th>";
			
			if ($layout == 'vertical')
				$output .= "</tr>\n";
			
		} else if ($timeformat == "12hours") {
			if ($i < 12) {
				$timeperiod = "am";
				if ($i == 0)
					$hour = 12;
				else
					$hour = floor($i);
			} else {
				$timeperiod = "pm";
				if ($i >= 12 && $i < 13)
					$hour = floor($i);
				else
					$hour = floor($i) - 12;
			}
			
			if ($layout == 'vertical')
				$output .= "<tr class='datarow'>";
			
			$output .= "<th>" . $hour;
			if ($minutes != "")
				$output .= ":" . $minutes;
			$output .=  $timeperiod . "</th>";			
			
			if ($layout == 'vertical')
				$output .= "</tr>\n";
		}
	}

	if ($layout == 'horizontal' || $layout == '') {
		$output .= "</tr>\n";
	} elseif ($layout == 'vertical') {
		$output .= "</table>\n";
		$output .= "</div>\n";
	}

 	$sqldays = "SELECT * from " .  $wpdb->prefix . "rs_days WHERE scheduleid = " . $scheduleid . "AND enabled = 1 AND scheduleid = " . $scheduleid;
	
	if ($daylist != "")
		$sqldays .= " AND id in (" . $daylist . ") ORDER BY FIELD(id, " . $daylist. ")";
		
	$daysoftheweek = $wpdb->get_results($sqldays);

	foreach ($daysoftheweek as $day) {
		for ($daysrow = 1; $daysrow <= $day->rows; $daysrow++) {
			$columns = $numberofcols;
			$time = $starttime;
			
			if ($layout == 'vertical') {
				$output .= "<div class='verticalcolumn" . $day->rows. "'>\n";
				$output .= "<table class='vertical" . $day->rows . "'>\n";				
				$output .= "<tr class='vertrow" . $day->rows. "'>";
			} elseif ($layout == 'horizontal' || $layout == '') {
				$output .= "<tr class='row" . $day->rows . "'>\n";
			}

			if ($daysrow == 1 && ($layout == 'horizontal' || $layout == ''))
				$output .= "<th rowspan='" . $day->rows . "' class='rowheader'>" . $day->name . "</th>\n";
			if ($daysrow == 1 && $layout == 'vertical' && $day->rows == 1)
				$output .= "<th class='rowheader'>" . $day->name . "</th>\n";
			if ($daysrow == 1 && $layout == 'vertical' && $day->rows > 1)
				$output .= "<th class='rowheader'>&laquo; " . $day->name . "</th>\n";				
			elseif ($daysrow != 1 && $layout == 'vertical') {
				if ($daysrow == $day->rows)
					$output .= "<th class='rowheader'>" . $day->name . " &raquo;</th>\n";
				else
					$output .= "<th class='rowheader'>&laquo; " . $day->name . " &raquo;</th>\n";
			}
				
			if ($layout == 'vertical')
				$output .= "</tr>\n";

			$sqlitems = "SELECT *, i.name as itemname, c.name as categoryname, c.id as catid FROM " . $wpdb->prefix . "rs_items i, " . $wpdb->prefix . "rs_categories c WHERE day = " . $day->id . " AND i.scheduleid = " . $scheduleid . " AND i.category = c.id AND i.starttime >= " . $starttime . " AND i.starttime < " . $endtime . " AND i.enabled = 1 AND i.scheduleid = '" . $scheduleid . "' ORDER by starttime";

			$items = $wpdb->get_results($sqlitems);

			if ($items) {
				foreach($items as $item) {
					for ($i = $time; $i < $item->starttime; $i += $timedivision) {
						if ($layout == 'vertical')
							$output .= "<tr class='datarow'>\n";
							
						$output .= "<td></td>\n";
						
						if ($layout == 'vertical')
							$output .= "</tr>\n";
						
						$columns -= 1;

					}
					
					$colspan = $item->duration / $timedivision;
					
					if ($colspan > $columns) {
						$colspan = $columns;
						$columns -= $columns;
						
						if ($layout == 'horizontal')
							$continue .= "id='continueright' ";
						elseif ($layout == 'vertical')
							$continue .= "id='continuedown' ";
					} else {					
						$columns -= $colspan;
						$continue = "";
					}	
					
					if ($layout == 'vertical')
							$output .= "<tr class='datarow" . $colspan . "'>";
					
					$output .= '<td class=ws-item-' . $item->id . ' ';
					
					if ( !empty( $item->itemcolor) || !empty( $item->categorycolor) ) {
                        $output .= 'style= "' . 'background-color:' . (!empty( $item->itemcolor) ? $item->itemcolor : $item->categorycolor ) . ';"';
                    }
					
					if ($displaydescription == "tooltip" && $item->description != "")
						$output .= "tooltip='" . htmlspecialchars(stripslashes($item->description),  ENT_QUOTES) . "' ";
					
					$output .= $continue;
					
					if ($layout == 'horizontal' || $layout == '')
						$output .= "colspan='" . $colspan . "' ";
					
					$output .= "class='cat" . $item->catid . "'>";
                    
                    $output .= '<div class="ws-item-title ws-item-title-' . $item->id . '"';
                    
                    if ( !empty( $item->titlecolor ) )
                        $output .= ' style="color:' . $item->titlecolor . '"';
                    
                    $output .= ">";
					
					if ($item->address != "")
						$output .= "<a target='" . $linktarget . "'href='" . $item->address. "'>";
						
					$output .= stripslashes($item->itemname);
										
					if ($item->address != "")
						"</a>";
                    
                    $output .= "</div>";
						
					if ($displaydescription == "cell")
						$output .= "<br />" .  stripslashes($item->description);
						
					$output .= "</td>";
					$time = $item->starttime + $item->duration;
					
					if ($layout == 'vertical')
						$output .= "</tr>\n";
					
				}

				for ($x = $columns; $x > 0; $x--) {
				
					if ($layout == 'vertical')
							$output .= "<tr class='datarow'>";
					
					$output .= "<td></td>";
					$columns -= 1;
					
					if ($layout == 'vertical')
							$output .= "</tr>";
				}
			} else {
				for ($i = $starttime; $i < $endtime; $i += $timedivision) {
					if ($layout == 'vertical')
							$output .= "<tr class='datarow'>";
							
					$output .= "<td></td>";
					
					if ($layout == 'vertical')
							$output .= "</tr>";
				}
			}

			if ($layout == 'horizontal' || $layout == '')
				$output .= "</tr>";
			
			if ($layout == 'vertical') {
				$output .= "</table>\n";
				$output .= "</div>\n";
			}
		}
	}
	
	if ($layout == 'horizontal' || $layout == '')
		$output .= "</table>";

	$output .= "</div>\n";
	
	if ($displaydescription == "tooltip") {
		$output .= "<script type=\"text/javascript\">\n";
		$output .= "// Create the tooltips only on document load\n";	
		$output .= "jQuery(document).ready(function()\n";
		$output .= "\t{\n";
		$output .= "\t// Notice the use of the each() method to acquire access to each elements attributes\n";
		$output .= "\tjQuery('.ws-schedule td[tooltip]').each(function()\n";
		$output .= "\t\t{\n";
		$output .= "\t\tjQuery(this).qtip({\n";
		$output .= "\t\t\tcontent: jQuery(this).attr('tooltip'), // Use the tooltip attribute of the element for the content\n";
		$output .= "\t\t\tstyle: {\n";
		$output .= "\t\t\t\twidth: " . $tooltipwidth . ",\n";
		$output .= "\t\t\t\tclasses: '" . $tooltipcolorscheme . "' // Give it a crea mstyle to make it stand out\n";
		$output .= "\t\t\t},\n";
		$output .= "\t\t\tposition: {\n";
		if ($adjusttooltipposition)
			$output .= "\t\t\t\tadjust: {method: 'flip flip'},\n";
		$output .= "\t\t\t\tviewport: jQuery(window),\n";
		$output .= "\t\t\t\tat: '" . $tooltiptarget . "',\n";
		$output .= "\t\t\t\tmy: '" . $tooltippoint . "'\n";
		$output .= "\t\t\t}\n";
		$output .= "\t\t});\n";
		$output .= "\t});\n";
		$output .= "});\n";
		$output .= "</script>\n";
	}
	
	$output .= "<!-- End RiotSchedule Output -->\n";
 	return $output;
}

function rs_library_flat_func($atts) {
	extract(shortcode_atts(array(
		'schedule' => ''
	), $atts));
	
	if ($schedule == '') {
		$options = get_option('RS_PP1');
		$schedule = 1;
	} else {
		$schedulename = 'RS_PP' . $schedule;
		$options = get_option($schedulename);
	}
	
	if ($options == false) {
		return "Requested schedule (Schedule " . $schedule . ") is not available from Weekly Schedule<br />";
	}
	
	return rs_library_flat($schedule, $options['starttime'], $options['endtime'], $options['timedivision'], $options['layout'], $options['tooltipwidth'], $options['tooltiptarget'], $options['tooltippoint'], $options['tooltipcolorscheme'], $options['displaydescription'], $options['daylist'], $options['timeformat'], $options['adjusttooltipposition']);
}

function rs_library_flat($scheduleid = 1, $starttime = 10, $endtime = 24, $timedivision = 1.0, $layout = 'horizontal', $tooltipwidth = 300, $tooltiptarget = 'right center', $tooltippoint = 'leftMiddle', $tooltipcolorscheme = 'ui-tooltip', $displaydescription = 'tooltip', $daylist = '', $timeformat = '24hours', $adjusttooltipposition = true) {
	// Week schedule flat output
	global $wpdb;	
	
	$linktarget = "newwindow";
	
	$output = "<!-- RiotSchedule Flat Output -->\n";
	$output .= "<div class='ws-schedule' id='ws-schedule<?php echo $scheduleid; ?>'>\n";
		
 	$sqldays = "SELECT * from " .  $wpdb->prefix . "rs_days where scheduleid = " . $scheduleid;
	
	if ($daylist != "")
		$sqldays .= " AND id in (" . $daylist . ") ORDER BY FIELD(id, " . $daylist. ")";
		
	$daysoftheweek = $wpdb->get_results($sqldays);
	
	$output .= "<table>\n";	

	foreach ($daysoftheweek as $day) {
		for ($daysrow = 1; $daysrow <= $day->rows; $daysrow++) {
			$output .= "<tr><td colspan='3'>" . $day->name . "</td></tr>\n";
		
			$sqlitems = "SELECT *, i.name as itemname, c.name as categoryname, c.id as catid from " . $wpdb->prefix . 
						"rs_items i, " . $wpdb->prefix . "rs_categories c WHERE day = " . $day->id . 			
						" AND i.scheduleid = " . $scheduleid . " AND row = " . $daysrow . " AND i.category = c.id AND i.starttime >= " . $starttime . " AND i.starttime < " .
						$endtime . " ORDER by starttime";

			$items = $wpdb->get_results($sqlitems);

			if ($items) {
				foreach($items as $item) {
				
					$output .= "<tr>\n";
					
					if ($timeformat == '24hours')
						$hour = floor($item->starttime);
					elseif ($options['timeformat'] == '12hours') {
						if ($item->starttime < 12) {
							$timeperiod = "am";
							if ($item->starttime == 0)
								$hour = 12;
							else
								$hour = floor($item->starttime);
						} else {
							$timeperiod = "pm";
							if ($item->starttime == 12)
								$hour = $item->starttime;
							else
								$hour = floor($item->starttime) - 12;
						}
					}
					
					if (fmod($item->starttime, 1) == 0.25)
						$minutes = "15";
					elseif (fmod($item->starttime, 1) == 0.50)
						$minutes = "30";
					elseif (fmod($item->starttime, 1) == 0.75)
						$minutes = "45";
					else
						$minutes = "00";
														
					if ($options['timeformat'] == '24 hours')
						$output .= "<td>" . $hour . "h" . $minutes . " - ";
					else
						$output .= "<td>" . $hour . ":" . $minutes . $timeperiod . " - ";
						
					$endtime = $item->starttime + $item->duration;
					
					if ($timeformat == '24hours')
						$hour = floor($endtime);
					elseif ($options['timeformat'] == '12hours') {
						if ($endtime < 12) {
							$timeperiod = "am";
							if ($endtime == 0)
								$hour = 12;
							else
								$hour = floor($endtime);
						} else {
							$timeperiod = "pm";
							if ($endtime == 12)
								$hour = $endtime;
							else
								$hour = floor($endtime) - 12;
						}
					}
					
					if (fmod($endtime, 1) == 0.25)
						$minutes = "15";
					elseif (fmod($endtime, 1) == 0.50)
						$minutes = "30";
					elseif (fmod($endtime, 1) == 0.75)
						$minutes = "45";
					else
						$minutes = "00";
														
					if ($options['timeformat'] == '24 hours')
						$output .= $hour . "h" . $minutes . "</td>";
					else
						$output .= $hour . ":" . $minutes . $timeperiod . "</td>";
						
					$output .= "<td>\n";
						
					if ($item->address != "")
						$output .= "<a target='" . $linktarget . "'href='" . $item->address. "'>";
						
					$output .= $item->itemname;
										
					if ($item->address != "")
						"</a>";
						
					$output .= "</td>";
					$output .= "<td>" . htmlspecialchars(stripslashes($item->description),  ENT_QUOTES) . "</td>";
					$output .= "</tr>";					
				}
			}
		}
	}

	$output .= "</table>";
	$output .= "</div id='ws-schedule'>\n";		
	$output .= "<!-- End of Weekly Schedule Flat Output -->\n";

 	return $output;
}

function rs_include_template_function( $template_path ) {
	// Use 'shows' template, if exists.
    if ( get_post_type() == 'shows' ) {
        if ( is_single() ) {
            // Checks if the file exists in the theme first, otherwise return the default
            if ( $theme_file = locate_template( array ( 'page-show.php' ) ) ) {
                $template_path = $theme_file;
			}
        }
    }
    return $template_path;
}


function rs_admin_schedule_columns($schedule_cols) {
	// Add custom schedule columns. Disregard the defaults
	$new_columns['cb'] = '<input type="checkbox" />';
     
    $new_columns['title'] = _x('Scheduled Item', 'scheduled item');
    $new_columns['author'] = __('Author');
     
    $new_columns['day'] = __('Day');
    $new_columns['time'] = __('Time');
	$new_columns['daytime'] = __('Week Order');
 
    $new_columns['date'] = _x('Date', 'column name');
 
    return $new_columns;
}

function rs_admin_schedule_sortable_columns($schedule_cols) {
	// Add custom schedule columns. Disregard the defaults
    
    $new_columns['title'] = 'title';
	$new_columns['day'] = 'day';
    $new_columns['time'] = 'time';
	$new_columns['daytime'] = 'daytime';
  
    return $new_columns;
}


function rs_admin_schedule_columns_orderby( $vars ) {
    // Custom array sorting definition here.
 
    return $vars;
}

function rs_manage_schedule_columns($column_name, $id) {
	// Render custom schedule columns.
	global $wpdb;
	$data = $wpdb->get_row("SELECT * from " . $wpdb->prefix . "rs_items WHERE pid = " . $id . "");
	switch ($column_name) {
		case 'id':
			echo $id;
			break;
		case 'day':
			// Get day
			$days = $wpdb->get_results("SELECT * from " . $wpdb->prefix. "rs_days where scheduleid = 1 ORDER by id");
			foreach ($days as $day) {
				if ($day->id == $data->day)
					echo $day->name;
			}
			break;
		case 'time':
			// Get time
			echo $data->starttime;
			break;
		case 'daytime':
			echo $data->day . $data->starttime;
			break;
		default:
			break;
	}
}  

// Admin options page
if ( ! class_exists( 'WS_Admin' ) ) {
	class WS_Admin {		
		function add_config_page() {
			global $wpdb;
			if ( function_exists('add_submenu_page') ) {
				add_options_page('RiotSchedule', 'Schedule', 9, basename(__FILE__), array('WS_Admin','config_page'), plugin_dir_url( __FILE__ ).'icons/calendar.png');
				add_filter('plugin_action_links', array( 'WS_Admin', 'filter_plugin_actions'), 10, 2 );
							}
		} // end add_WS_config_page()

		function filter_plugin_actions( $links, $file ) {
			// Static so we don't call plugin_basename on every plugin row.
			static $this_plugin;
			if ( ! $this_plugin ) $this_plugin = plugin_basename(__FILE__);
			if ( $file == $this_plugin ){
				$settings_link = '<a href="options-general.php?page=riotschedule.php">' . __('Settings') . '</a>';
				array_unshift( $links, $settings_link ); // before other links
			}
			return $links;
		}

		function config_page() {
			global $dlextensions;
			global $wpdb;
			
			$adminpage == "";
			
			if ( !defined('WP_ADMIN_URL') )
				define( 'WP_ADMIN_URL', get_option('siteurl') . '/wp-admin');
			
			if ( isset($_GET['schedule']) ) {
				$schedule = $_GET['schedule'];				
			} elseif (isset($_POST['schedule'])) {
				$schedule = $_POST['schedule'];
			} else {
				$schedule = 1;
			}
			
			if ( isset($_GET['copy']))
			{
				$destination = $_GET['copy'];
				$source = $_GET['source'];
				
				$sourcesettingsname = 'RS_PP' . $source;
				$sourceoptions = get_option($sourcesettingsname);
				
				$destinationsettingsname = 'RS_PP' . $destination;
				update_option($destinationsettingsname, $sourceoptions);
				
				$schedule = $destination;
			}

			if ( isset($_GET['reset']) && $_GET['reset'] == "true") {
			
				$options = rs_return_default_options($options);
			
				$schedule = $_GET['reset'];
				$schedulename = 'RS_PP' . $schedule;
				
				update_option($schedulename, $options);
			}
			if ( isset($_GET['settings'])) {
				if ($_GET['settings'] == 'categories') {
					$adminpage = 'categories';
				} elseif ($_GET['settings'] == 'items') {
					$adminpage = 'items';
				} elseif ($_GET['settings'] == 'general') {
					$adminpage = 'general';
				} elseif ($_GET['settings'] == 'days') {
					$adminpage = 'days';
				}
			
			}
			if ( isset($_POST['submit']) ) {
				if (!current_user_can('manage_options')) die(__('You cannot edit RiotSchedule for WordPress options.'));
				check_admin_referer('wspp-config');
				
				
				if ($_POST['timedivision'] != $options['timedivision'] && $_POST['timedivision'] == "3.0") {
					$itemsquarterhour = $wpdb->get_results("SELECT * from " . $wpdb->prefix . "rs_items WHERE MOD(duration, 1) = 0.25 and scheduleid = " . $schedule);
					$itemshalfhour = $wpdb->get_results("SELECT * from " . $wpdb->prefix . "rs_items WHERE MOD(duration, 1) = 0.5 and scheduleid = " . $schedule);
					$itemshour = $wpdb->get_results("SELECT * from " . $wpdb->prefix . "rs_items WHERE MOD(duration, 1) = 1.0 and scheduleid = " . $schedule);
					$itemstwohour = $wpdb->get_results("SELECT * from " . $wpdb->prefix . "rs_items WHERE MOD(duration, 1) = 2.0 and scheduleid = " . $schedule);
					
					if ($itemsquarterhour) {
						echo '<div id="warning" class="updated fade"><p><strong>Cannot change time division to tri-hourly since some items have quarter-hourly durations</strong></div>';
						$options['timedivision'] = "0.25";
					} elseif ($itemshalfhour) {
						echo '<div id="warning" class="updated fade"><p><strong>Cannot change time division to tri-hourly since some items have half-hourly durations</strong></div>';
						$options['timedivision'] = "0.5";
					} elseif ($itemshour) {
						echo '<div id="warning" class="updated fade"><p><strong>Cannot change time division to tri-hourly since some items have hourly durations</strong></div>';
						$options['timedivision'] = "1.0";
					} elseif ($itemstwohour) {
						echo '<div id="warning" class="updated fade"><p><strong>Cannot change time division to tri-hourly since some items have hourly durations</strong></div>';
						$options['timedivision'] = "2.0";
					} else
						$options['timedivision'] = $_POST['timedivision'];					
				} elseif ($_POST['timedivision'] != $options['timedivision'] && $_POST['timedivision'] == "2.0") {
					$itemsquarterhour = $wpdb->get_results("SELECT * from " . $wpdb->prefix . "rs_items WHERE MOD(duration, 1) = 0.25 and scheduleid = " . $schedule);
					$itemshalfhour = $wpdb->get_results("SELECT * from " . $wpdb->prefix . "rs_items WHERE MOD(duration, 1) = 0.5 and scheduleid = " . $schedule);
					$itemshour = $wpdb->get_results("SELECT * from " . $wpdb->prefix . "rs_items WHERE MOD(duration, 1) = 1.0 and scheduleid = " . $schedule);
					
					if ($itemsquarterhour) {
						echo '<div id="warning" class="updated fade"><p><strong>Cannot change time division to bi-hourly since some items have quarter-hourly durations</strong></div>';
						$options['timedivision'] = "0.25";
					} elseif ($itemshalfhour) {
						echo '<div id="warning" class="updated fade"><p><strong>Cannot change time division to bi-hourly since some items have half-hourly durations</strong></div>';
						$options['timedivision'] = "0.5";
					} elseif ($itemshour) {
						echo '<div id="warning" class="updated fade"><p><strong>Cannot change time division to bi-hourly since some items have hourly durations</strong></div>';
						$options['timedivision'] = "1.0";
					} else
						$options['timedivision'] = $_POST['timedivision'];					
				} elseif ($_POST['timedivision'] != $options['timedivision'] && $_POST['timedivision'] == "1.0") {
					$itemsquarterhour = $wpdb->get_results("SELECT * from " . $wpdb->prefix . "rs_items WHERE MOD(duration, 1) = 0.25 and scheduleid = " . $schedule);
					$itemshalfhour = $wpdb->get_results("SELECT * from " . $wpdb->prefix . "rs_items WHERE MOD(duration, 1) = 0.5 and scheduleid = " . $schedule);
					
					if ($itemsquarterhour) {
						echo '<div id="warning" class="updated fade"><p><strong>Cannot change time division to hourly since some items have quarter-hourly durations</strong></div>';
						$options['timedivision'] = "0.25";
					} elseif ($itemshalfhour) {
						echo '<div id="warning" class="updated fade"><p><strong>Cannot change time division to hourly since some items have half-hourly durations</strong></div>';
						$options['timedivision'] = "0.5";
					} else
						$options['timedivision'] = $_POST['timedivision'];					
				} elseif ($_POST['timedivision'] != $options['timedivision'] && $_POST['timedivision'] == "0.5") {
					$itemsquarterhour = $wpdb->get_results("SELECT * from " . $wpdb->prefix . "rs_items WHERE MOD(duration, 1) = 0.25 and scheduleid = " . $schedule);
					
					if ($itemsquarterhour) {
						echo '<div id="warning" class="updated fade"><p><strong>Cannot change time division to hourly since some items have quarter-hourly durations</strong></div>';
						$options['timedivision'] = "0.25";
					}
					else
						$options['timedivision'] = $_POST['timedivision'];				
				}
				else
					$options['timedivision'] = $_POST['timedivision'];

				foreach (array('starttime','endtime','tooltipwidth','tooltiptarget','tooltippoint','tooltipcolorscheme',
						'displaydescription','daylist', 'timeformat', 'layout', 'schedulename', 'linktarget') as $option_name) {
						if (isset($_POST[$option_name])) {
							$options[$option_name] = $_POST[$option_name];
						}
					}
					
				foreach (array('adjusttooltipposition') as $option_name) {
					if (isset($_POST[$option_name])) {
						$options[$option_name] = true;
					} else {
						$options[$option_name] = false;
					}
				}

				
				$schedulename = 'RS_PP' . $schedule;
				update_option($schedulename, $options);
				
				echo '<div id="message" class="updated fade"><p><strong>Weekly Schedule: Schedule ' . $schedule . ' Updated</strong></div>';
			}
			
			if (isset($_POST['submitgen'])) {
				if (!current_user_can('manage_options')) die(__('You cannot edit the Weekly Schedule for WordPress options.'));
				check_admin_referer('wspp-config');
				
				foreach (array('stylesheet', 'numberschedules', 'includestylescript') as $option_name) {
					if (isset($_POST[$option_name])) {
						$genoptions[$option_name] = $_POST[$option_name];
					}
				}
				
				foreach (array('debugmode', 'frontpagestylescript') as $option_name) {
					if (isset($_POST[$option_name])) {
						$genoptions[$option_name] = true;
					} else {
						$genoptions[$option_name] = false;
					}
				}
				
				update_option('RiotScheduleGeneral', $genoptions);				
			}
			if ( isset($_GET['editcat'])) {					
				$adminpage = 'categories';
				$mode = "edit";
				$selectedcat = $wpdb->get_row("select * from " . $wpdb->prefix . "rs_categories where id = " . $_GET['editcat']);
			}			
			if ( isset($_POST['newcat']) || isset($_POST['updatecat'])) {
				if (!current_user_can('manage_options')) die(__('You cannot edit the Weekly Schedule for WordPress options.'));
				check_admin_referer('wspp-config');
				
				if (isset($_POST['name']))
					$newcat = array(
							"name" => $_POST['name'], 
							"scheduleid" => $_POST['schedule'],
							'backgroundcolor' => $_POST['backgroundcolor']
							);
				else
					$newcat = "";
					
				if (isset($_POST['id']))
					$id = array("id" => $_POST['id']);
					
					
				if (isset($_POST['newcat'])) {
					$wpdb->insert( $wpdb->prefix.'rs_categories', $newcat);
					echo '<div id="message" class="updated fade"><p><strong>Inserted New Category</strong></div>';
				} elseif (isset($_POST['updatecat'])) {
					$wpdb->update( $wpdb->prefix.'rs_categories', $newcat, $id);
					echo '<div id="message" class="updated fade"><p><strong>Category Updated</strong></div>';
				}
				
				$mode = "";
				$adminpage = 'categories';	
			}
			if (isset($_GET['deletecat'])) {
				$adminpage = 'categories';
				
				$catexist = $wpdb->get_row("SELECT * from " . $wpdb->prefix . "rs_categories WHERE id = " . $_GET['deletecat']);
				
				if ($catexist) {
					$wpdb->query("DELETE from " . $wpdb->prefix . "rs_categories WHERE id = " . $_GET['deletecat']);
					echo '<div id="message" class="updated fade"><p><strong>Category Deleted</strong></div>';
				}
			}
			
			if (isset($_POST['updatedays'])) {
				$dayids = array(1, 2, 3, 4, 5, 6, 7);
				
				foreach($dayids as $dayid) {
					$daynamearray = array("name" => $_POST[$dayid]);
					$dayidarray = array("id" => $dayid, "scheduleid" => $_POST['schedule']);
					
					$wpdb->update($wpdb->prefix . 'rs_days', $daynamearray, $dayidarray);
				}					
			}
			
			$wspluginpath = WP_CONTENT_URL.'/plugins/'.plugin_basename(dirname(__FILE__)).'/';
	
			if ($schedule == '') {
				$options = get_option('RS_PP1');
				if ($options == false) {
					$oldoptions = get_option('RS_PP');
					if ($options)
						echo "If you are upgrading from versions before 2.0, please deactivate and reactivate the plugin in the Wordpress Plugins admin to upgrade all tables correctly.";
				}
					
				$schedule = 1;
			} else {
				$settingsname = 'RS_PP' . $schedule;
				$options = get_option($settingsname);
			}

			if ($options == "") {
				$options = rs_return_default_options($options);
			
				$schedulename = 'RS_PP' . $schedule;
				
				update_option($schedulename, $options);
				
				$catsresult = $wpdb->query("SELECT * from " . $wpdb->prefix . "rs_categories where scheduleid = " . $schedule);
						
				if (!$catsresult) {
					$sqlstatement = "INSERT INTO " . $wpdb->prefix . "rs_categories (`name`, `scheduleid`) VALUES 
									('Default', " . $schedule . ")";
					$result = $wpdb->query($sqlstatement);
				}

				$wpdb->rs_days = $wpdb->prefix.'rs_days';
										
				$daysresult = $wpdb->query("SELECT * from " . $wpdb->prefix . "rs_days where scheduleid = " . $schedule);
						
				if (!$daysresult) {
					rs_set_default_days();
				}
			}
			
			$genoptions = get_option('RiotScheduleGeneral');
			if ($genoptions == "") {			
				$genoptions = rs_return_default_genoptions($genoptions);
		
				update_option('RiotScheduleGeneral', $genoptions);	
			}
			
			// Start interface
			?>
			<div class="wrap">
				<h2>RiotSchedule Configuration</h2>
                <p>Version <?php echo $genoptions['version']; ?> | <a href="http://www.mattyribbo.co.uk/projects/riotschedule" target="_blank">By Matt Ribbins</a> | Based from the <a href="http://yannickcorner.nayanna.biz/wordpress-plugins/weekly-schedule/" target="_blank">Weekly Schedule plugin</a></p> <br />
				
				<form name='wsadmingenform' action="<?php echo WP_ADMIN_URL ?>/options-general.php?page=riotschedule.php" method="post" id="ws-conf">
				<?php
				if ( function_exists('wp_nonce_field') )
						wp_nonce_field('wspp-config');
					?>
				<fieldset style='border:1px solid #CCC;padding:10px'>
				<legend class="tooltip" title='These apply to all schedules' style='padding: 0 5px 0 5px;'><strong>General Settings <span style="border:0;padding-left: 15px;" class="submit"><input type="submit" name="submitgen" value="Update General Settings &raquo;" /></span></strong></legend>
				<table>
				<tr>
				<td style='padding: 8px; vertical-align: top'>
					<table>
					<tr>
					<td style='width:200px'>Stylesheet File Name</td>
					<td><input type="text" id="stylesheet" name="stylesheet" size="40" value="<?php echo $genoptions['stylesheet']; ?>"/></td>
					</tr>
					<tr>
					<td>Number of Schedules</td>
					<td><input type="text" id="numberschedules" name="numberschedules" size="5" value="<?php if ($genoptions['numberschedules'] == '') echo '2'; echo $genoptions['numberschedules']; ?>"/></td>
					</tr>
					<tr>
					<td>Debug Mode</td>
					<td><input type="checkbox" id="debugmode" name="debugmode" <?php if ($genoptions['debugmode']) echo ' checked="checked" '; ?>/></td>
					</tr>
					</table>
				</td>
				</tr>
				</table>
				</fieldset>
				</form>

				<div style='padding-top: 15px;clear:both'>
					<fieldset style='border:1px solid #CCC;padding:10px'>
					<legend style='padding: 0 5px 0 5px;'><strong>Schedule Selection and Usage Instructions</strong></legend>				
						<FORM name="scheduleselection">
							Select Current Schedule: 
							<SELECT name="schedulelist" style='width: 300px'>
							<?php if ($genoptions['numberschedules'] == '') $numberofschedules = 2; else $numberofschedules = $genoptions['numberschedules'];
								for ($counter = 1; $counter <= $numberofschedules; $counter++): ?>
									<?php $tempoptionname = "RS_PP" . $counter;
									   $tempoptions = get_option($tempoptionname); ?>
									   <option value="<?php echo $counter ?>" <?php if ($schedule == $counter) echo 'SELECTED';?>>Schedule <?php echo $counter ?><?php if ($tempoptions != "") echo " (" . $tempoptions['schedulename'] . ")"; ?></option>
								<?php endfor; ?>
							</SELECT>
							<INPUT type="button" name="go" value="Go!" onClick="window.location= '?page=riotschedule.php&amp;settings=<?php echo $adminpage; ?>&amp;schedule=' + document.scheduleselection.schedulelist.options[document.scheduleselection.schedulelist.selectedIndex].value">						
							Copy from: 
							<SELECT name="copysource" style='width: 300px'>
							<?php if ($genoptions['numberschedules'] == '') $numberofschedules = 2; else $numberofschedules = $genoptions['numberschedules'];
								for ($counter = 1; $counter <= $numberofschedules; $counter++): ?>
									<?php $tempoptionname = "RS_PP" . $counter;
									   $tempoptions = get_option($tempoptionname); 
									   if ($counter != $schedule):?>
									   <option value="<?php echo $counter ?>" <?php if ($schedule == $counter) echo 'SELECTED';?>>Schedule <?php echo $counter ?><?php if ($tempoptions != "") echo " (" . $tempoptions['schedulename'] . ")"; ?></option>
									   <?php endif; 
								    endfor; ?>
							</SELECT>
							<INPUT type="button" name="copy" value="Copy!" onClick="window.location= '?page=riotschedule.php&amp;copy=<?php echo $schedule; ?>&source=' + document.scheduleselection.copysource.options[document.scheduleselection.copysource.selectedIndex].value">							
					<br />
					<br />
					<table class='widefat' style='clear:none;width:100%;background: #DFDFDF url(/wp-admin/images/gray-grad.png) repeat-x scroll left top;'>
						<thead>
						<tr>
							<th style='width:80px' class="tooltip">
								Schedule #
							</th>
							<th style='width:130px' class="tooltip">
								Schedule Name
							</th>
							<th class="tooltip">
								Code to insert on a Wordpress page to see Weekly Schedule
							</th>
						</tr>
						</thead>
						<tr>
						<td style='background: #FFF'><?php echo $schedule; ?></td><td style='background: #FFF'><?php echo $options['schedulename']; ?></a></td><td style='background: #FFF'><?php echo "[riotschedule schedule=" . $schedule . "]"; ?></td><td style='background: #FFF;text-align:center'></td>
						</tr>
					</table> 
					<br />
					</FORM>
					</fieldset>
				</div>
				<br />

	
				<fieldset style='border:1px solid #CCC;padding:10px'>
				<legend style='padding: 0 5px 0 5px;'><strong>Settings for Schedule <?php echo $schedule; ?> - <?php echo $options['schedulename']; ?></strong></legend>	
				<?php if (($adminpage == "") || ($adminpage == "general")): ?>
				<a href="?page=riotschedule.php&amp;settings=general&amp;schedule=<?php echo $schedule; ?>"><strong>General Settings</strong></a> | <a href="?page=riotschedule.php&amp;settings=categories&amp;schedule=<?php echo $schedule; ?>">Manage Schedule Categories</a> | <a href="?page=riotschedule.php&amp;settings=items&amp;schedule=<?php echo $schedule; ?>">Manage Schedule Items</a> | <a href="?page=riotschedule.php&amp;settings=days&amp;schedule=<?php echo $schedule; ?>">Manage Days Labels</a><br /><br />
				<form name="wsadminform" action="<?php echo WP_ADMIN_URL ?>/options-general.php?page=riotschedule.php" method="post" id="ws-config">
				<?php
					if ( function_exists('wp_nonce_field') )
						wp_nonce_field('wspp-config');
					?>
					Schedule Name: <input type="text" id="schedulename" name="schedulename" size="80" value="<?php echo $options['schedulename']; ?>"/><br /><br />
					<strong>Time-related Settings</strong><br />
					<input type="hidden" name="schedule" value="<?php echo $schedule; ?>" />
					<table>
					<tr>
					<td>Schedule Layout</td>
					<td><select style="width: 200px" name='layout'>
					<?php $layouts = array("horizontal" => "Horizontal", "vertical" => "Vertical");
						foreach($layouts as $key => $layout)
						{
							if ($key == $options['layout'])
								$samedesc = "selected='selected'";
							else
								$samedesc = "";
								
							echo "<option value='" . $key . "' " . $samedesc . ">" . $layout . "\n";
						}
					?>
					</select></td>
					<td>Time Display Format</td>
					<td><select style="width: 200px" name='timeformat'>
					<?php $descriptions = array("24hours" => "24 Hours (e.g. 17h30)", "12hours" => "12 Hours (e.g. 1:30pm)");
						foreach($descriptions as $key => $description)
						{
							if ($key == $options['timeformat'])
								$samedesc = "selected='selected'";
							else
								$samedesc = "";
								
							echo "<option value='" . $key . "' " . $samedesc . ">" . $description . "\n";
						}
					?>
					</select></td>
					</tr>
					<tr>
					<td>Start Time</td>
					<td><select style='width: 200px' name="starttime">
					<?php $timedivider = (in_array($options['timedivision'], array('1.0', '2.0', '3.0')) ? '1.0': $options['timedivision']); 
						  $maxtime = 24 + $timedivider; for ($i = 0; $i < $maxtime; $i+= $timedivider)
						  {
								if ($options['timeformat'] == '24hours')
									$hour = floor($i);
								elseif ($options['timeformat'] == '12hours') {
									if ($i < 12) {
										$timeperiod = "am";
										if ($i == 0)
											$hour = 12;
										else
											$hour = floor($i);
									} else {
										$timeperiod = "pm";
										if ($i >= 12 && $i < 13)
											$hour = floor($i);
										else
											$hour = floor($i) - 12;
									}
								}
							
								if (fmod($i, 1) == 0.25)
                                    $minutes = "15";
								elseif (fmod($i, 1) == 0.50)
									$minutes = "30";
								elseif (fmod($i, 1) == 0.75)
									$minutes = "45";
                                else
                                    $minutes = "00";

									
								if ($i == $options['starttime']) 
									$selectedstring = "selected='selected'";
								else
									$selectedstring = "";
									
								if ($options['timeformat'] == '24 hours')
									echo "<option value='" . $i . "'" . $selectedstring . ">" .  $hour . "h" . $minutes . "\n";
								else
									echo "<option value='" . $i . "'" . $selectedstring . ">" .  $hour . ":" . $minutes . $timeperiod . "\n";
						  }
					?>
					</select></td>
					<td>End Time</td>
					<td><select style='width: 200px' name="endtime">
					<?php for ($i = 0; $i < $maxtime; $i+= $timedivider) {
						  		if ($options['timeformat'] == '24hours')
									$hour = floor($i);
								elseif ($options['timeformat'] == '12hours') {
									if ($i < 12) {
										$timeperiod = "am";
										if ($i == 0)
											$hour = 12;
										else
											$hour = floor($i);
									} else {
										$timeperiod = "pm";
										if ($i >= 12 && $i < 13)
											$hour = floor($i);
										else
											$hour = floor($i) - 12;
									}
								}
								
								if (fmod($i, 1) == 0.25)
                                    $minutes = "15";
								elseif (fmod($i, 1) == 0.50)
									$minutes = "30";
								elseif (fmod($i, 1) == 0.75)
									$minutes = "45";
                                else
                                    $minutes = "00";

								if ($i == $options['endtime']) 
									$selectedstring = "selected='selected'";
								else
									$selectedstring = "";

								if ($options['timeformat'] == '24 hours')
									echo "<option value='" . $i . "'" . $selectedstring . ">" .  $hour . "h" . $minutes . "\n";
								else
									echo "<option value='" . $i . "'" . $selectedstring . ">" .  $hour . ":" . $minutes . $timeperiod . "\n";
						  }
					?>
					</select></td>
					</tr>
					<tr>
					<td>Cell Time Division</td>
					<td><select style='width: 250px' name='timedivision'>
					<?php $timedivisions = array("0.25" => "Quarter-Hourly (15 min intervals)",
												 ".50" => "Half-Hourly (30 min intervals)",
												 "1.0" => "Hourly (60 min intervals)",
												 "2.0" => "Bi-Hourly (120 min intervals)",
												 "3.0" => "Tri-Hourly (180 min intervals)");
						foreach($timedivisions as $key => $timedivision) {
							if ($key == $options['timedivision'])
								$sametime = "selected='selected'";
							else
								$sametime = "";
								
							echo "<option value='" . $key . "' " . $sametime . ">" . $timedivision . "\n";
						}
					?>	
					</select></td>
					<td>Show Description</td>
					<td><select style="width: 200px" name='displaydescription'>
					<?php $descriptions = array("tooltip" => "Show as tooltip", "cell" => "Show in cell after item name", "none" => "Do not display");
						foreach($descriptions as $key => $description) {
							if ($key == $options['displaydescription'])
								$samedesc = "selected='selected'";
							else
								$samedesc = "";
								
							echo "<option value='" . $key . "' " . $samedesc . ">" . $description . "\n";
						}
					?>
					</select></td></tr>
					<tr>
						<td colspan='2'>Day List (comma-separated Day IDs to specify days to be displayed and their order)
						</td>
						<td colspan='2'><input type='text' name='daylist' style='width: 200px' value='<?php echo $options['daylist']; ?>' />
						</td>						
					</tr>
					<tr>
						<td>Target Window Name
						</td>
						<td><input type='text' name='linktarget' style='width: 250px' value='<?php echo $options['linktarget']; ?>' />
						</td>
					</tr>
					</table>
					<br /><br />
					<strong>Tooltip Configuration</strong>
					<table>
					<tr>
					<td>Tooltip Color Scheme</td>
					<td><select name='tooltipcolorscheme' style='width: 100px'>
						<?php $colors = array('ui-tooltip' => 'cream', 'ui-tooltip-dark' => 'dark', 'ui-tooltip-green' => 'green', 'ui-tooltip-light' => 'light', 'ui-tooltip-red' => 'red', 'ui-tooltip-blue' => 'blue');					
							  foreach ($colors as $key => $color) {
									if ($key == $options['tooltipcolorscheme'])
										$samecolor = "selected='selected'";
									else
										$samecolor = "";
										
									echo "<option value='" . $key . "' " . $samecolor . ">" . $color . "\n";
								}
						?>						
					</select></td>
					<td>Tooltip Width</td><td><input type='text' name='tooltipwidth' style='width: 100px' value='<?php echo $options['tooltipwidth']; ?>' /></td>
					</tr>
					<tr>
					<td>Tooltip Anchor Point on Data Cell</td>
					<td><select name='tooltiptarget' style='width: 200px'>
						<?php $positions = array('top left' => 'Top-Left Corner', 'top center' => 'Middle of Top Side', 
												'top right' => 'Top-Right Corner', 'right top' => 'Right Side of Top-Right Corner',
												'right center' => 'Middle of Right Side', 'right bottom' => 'Right Side of Bottom-Right Corner',
												'bottom left' => 'Under Bottom-Left Side', 'bottom center' => 'Under Middle of Bottom Side',
												'bottom right' => 'Under Bottom-Right Side', 'left top' => 'Left Side of Top-Left Corner',
												'left center' => 'Middle of Left Side', 'left bottom' => 'Left Side of Bottom-Left Corner');
								
						foreach($positions as $index => $position) {
									if ($index == $options['tooltiptarget'])
										$sameposition = "selected='selected'";
									else
										$sameposition = "";
										
									echo "<option value='" . $index . "' " . $sameposition . ">" . $position . "\n";
								}
												
						?>
					</select></td>
					<td>Tooltip Attachment Point</td>
					<td><select name='tooltippoint' style='width: 200px'>
						<?php $positions = array('top left' => 'Top-Left Corner', 'top center' => 'Middle of Top Side', 
												'top right' => 'Top-Right Corner', 'right top' => 'Right Side of Top-Right Corner',
												'right center' => 'Middle of Right Side', 'right bottom' => 'Right Side of Bottom-Right Corner',
												'bottom left' => 'Under Bottom-Left Side', 'bottom center' => 'Under Middle of Bottom Side',
												'bottom right' => 'Under Bottom-Right Side', 'left top' => 'Left Side of Top-Left Corner',
												'left center' => 'Middle of Left Side', 'left bottom' => 'Left Side of Bottom-Left Corner');
						
								foreach($positions as $index => $position) {
									if ($index == $options['tooltippoint'])
										$sameposition = "selected='selected'";
									else
										$sameposition = "";
										
									echo "<option value='" . $index . "' " . $sameposition . ">" . $position . "\n";
								}
												
						?>
					</select></td>
					</tr>
					<tr>
					<td>Auto-Adjust Position to be visible</td>
					<td><input type="checkbox" id="adjusttooltipposition" name="adjusttooltipposition" <?php if ($options['adjusttooltipposition'] == true) echo ' checked="checked" '; ?>/></td>
					<td></td><td></td>
					</tr>
					</table>
					<p style="border:0;" class="submit"><input type="submit" name="submit" value="Update Settings &raquo;" /></p>
					</form>
					</fieldset>
				<?php /* --------------------------------------- Categories --------------------------------- */ ?>
				<?php elseif ($adminpage == "categories"): ?>
				<a href="?page=riotschedule.php&amp;settings=general&amp;schedule=<?php echo $schedule; ?>">General Settings</a> | <a href="?page=riotschedule.php&amp;settings=categories&amp;schedule=<?php echo $schedule; ?>"><strong>Manage Schedule Categories</strong></a> | <a href="?page=riotschedule.php&amp;settings=items&amp;schedule=<?php echo $schedule; ?>">Manage Schedule Items</a> | <a href="?page=riotschedule.php&amp;settings=days&amp;schedule=<?php echo $schedule; ?>">Manage Days Labels</a><br /><br />
				<div style='float:left;margin-right: 15px'>
					<form name="wscatform" action="" method="post" id="ws-config">
					<?php
					if ( function_exists('wp_nonce_field') )
						wp_nonce_field('wspp-config');
					?>
					<?php if ($mode == "edit"): ?>
					<strong>Editing Category #<?php echo $selectedcat->id; ?></strong><br />
					<?php endif; ?>
					Category Name: <input style="width:300px" type="text" name="name" <?php if ($mode == "edit") echo "value='" . $selectedcat->name . "'";?>/>
					<br>Background Cell Color (optional)
					<input style="width:100px" type="text" name="backgroundcolor" <?php if ($mode == "edit") echo "value='" . $selectedcat->backgroundcolor . "'";?>/>
					<input type="hidden" name="id" value="<?php if ($mode == "edit") echo $selectedcat->id; ?>" />
					<input type="hidden" name="schedule" value="<?php echo $schedule; ?>" />
					<?php if ($mode == "edit"): ?>
						<p style="border:0;" class="submit"><input type="submit" name="updatecat" value="Update &raquo;" /></p>
					<?php else: ?>
						<p style="border:0;" class="submit"><input type="submit" name="newcat" value="Insert New Category &raquo;" /></p>
					<?php endif; ?>
					</form>
				</div>
				<div>
					<?php $cats = $wpdb->get_results("SELECT count( i.id ) AS nbitems, c.name, c.id, c.backgroundcolor, c.scheduleid FROM " . $wpdb->prefix . "rs_categories c LEFT JOIN " . $wpdb->prefix . "rs_items i ON i.category = c.id WHERE c.scheduleid = " . $schedule . " GROUP BY c.id");
					
							if ($cats): ?>
							  <table class='widefat' style='clear:none;width:400px;background: #DFDFDF url(/wp-admin/images/gray-grad.png) repeat-x scroll left top;'>
							  <thead>
							  <tr>
  							  <th scope='col' style='width: 50px' id='id' class='manage-column column-id' >ID</th>
							  <th scope='col' id='name' class='manage-column column-name' style=''>Name</th>
							  <th scope='col' style='width: 50px;text-align: right' id='color' class='manage-column column-color' style=''>Color</th>
							  <th scope='col' style='width: 50px;text-align: right' id='items' class='manage-column column-items' style=''>Items</th>
							  <th style='width: 30px'></th>
							  </tr>
							  </thead>
							  
							  <tbody id='the-list' class='list:link-cat'>

							  <?php foreach($cats as $cat): ?>
								<tr>
								<td class='name column-name' style='background: #FFF'><?php echo $cat->id; ?></td>
								<td style='background: #FFF'><a href='?page=riotschedule.php&amp;editcat=<?php echo $cat->id; ?>&schedule=<?php echo $schedule; ?>'><strong><?php echo $cat->name; ?></strong></a></td>
								<td style='background: <?php echo $cat->backgroundcolor != NULL ? $cat->backgroundcolor : '#FFF'; ?>;text-align:right'></td>
								<td style='background: #FFF;text-align:right'><?php echo $cat->nbitems; ?></td>
								<?php if ($cat->nbitems == 0): ?>
								<td style='background:#FFF'><a href='?page=riotschedule.php&amp;deletecat=<?php echo $cat->id; ?>&schedule=<?php echo $schedule; ?>' 
								<?php echo "onclick=\"if ( confirm('" . esc_js(sprintf( __("You are about to delete this category '%s'\n  'Cancel' to stop, 'OK' to delete."), $cat->name )) . "') ) { return true;}return false;\"" ?>><img src='<?php echo $wspluginpath; ?>/icons/delete.png' /></a></td>
								<?php else: ?>
								<td style='background: #FFF'></td>
								<?php endif; ?>
								</tr>
							  <?php endforeach; ?>				
							  
							  </tbody>
							  </table>
							 
							<?php endif; ?>
							
							<p>Categories can only be deleted when they don't have any associated items.</p>
				</div>
				<?php /* --------------------------------------- Items --------------------------------- */ ?>
				<?php elseif ($adminpage == "items"): ?>
				<a href="?page=riotschedule.php&amp;settings=general&amp;schedule=<?php echo $schedule; ?>">General Settings</a> | <a href="?page=riotschedule.php&amp;settings=categories&amp;schedule=<?php echo $schedule; ?>">Manage Schedule Categories</a> | <a href="?page=riotschedule.php&amp;settings=items&amp;schedule=<?php echo $schedule; ?>"><strong>Manage Schedule Items</strong></a> | <a href="?page=riotschedule.php&amp;settings=days&amp;schedule=<?php echo $schedule; ?>">Manage Days Labels</a><br /><br />
				<div style='float:left;margin-right: 15px;width: 500px;'>
					<p>Items tab has been deprecated. Creating a new scheduled item takes place in the 'Scheduled Item' section of WordPress (below Posts).</p>
				</div>
				<div>
				<?php $items = $wpdb->get_results("SELECT d.name as dayname, i.id, i.name, i.backgroundcolor, i.day, i.starttime FROM " . $wpdb->prefix . "rs_items as i, " . $wpdb->prefix . "rs_days as d WHERE i.day = d.id 
								and i.scheduleid = " . $schedule . " and d.scheduleid = " . $_GET['schedule'] . " ORDER by day, starttime, name");
					
							if ($items): ?>
							  <table class='widefat' style='clear:none;width:500px;background: #DFDFDF url(/wp-admin/images/gray-grad.png) repeat-x scroll left top;'>
							  <thead>
							  <tr>
  							  <th scope='col' style='width: 50px' id='id' class='manage-column column-id' >ID</th>
							  <th scope='col' id='name' class='manage-column column-name' style=''>Name</th>
							  <th scope='col' id='color' class='manage-column column-color' style=''>Color</th>
							  <th scope='col' id='day' class='manage-column column-day' style='text-align: right'>Day</th>
							  <th scope='col' style='width: 50px;text-align: right' id='starttime' class='manage-column column-items' style=''>Start Time</th>
							  <th style='width: 30px'></th>
							  </tr>
							  </thead>
							  
							  <tbody id='the-list' class='list:link-cat'>

							  <?php foreach($items as $item): ?>
								<tr>
								<td class='name column-name' style='background: #FFF'><a href='?page=riotschedule.php&amp;edititem=<?php echo $item->id; ?>&amp;schedule=<?php echo $schedule; ?>'><strong><?php echo $item->id; ?></strong></a></td>
								<td style='background: #FFF'><a href='?page=riotschedule.php&amp;edititem=<?php echo $item->id; ?>&amp;schedule=<?php echo $schedule; ?>'><strong><?php echo stripslashes($item->name); ?></strong></a></td>

								<td style='background: <?php echo $item->backgroundcolor ? $item->backgroundcolor : '#FFF'; ?>'></td>
								<td style='background: #FFF;text-align:right'><?php echo $item->dayname; ?></td>
								<td style='background: #FFF;text-align:right'>
								<?php 
								
								if ($options['timeformat'] == '24hours')
									$hour = floor($item->starttime);
								elseif ($options['timeformat'] == '12hours')
								{
									if ($item->starttime < 12)
									{
										$timeperiod = "am";
										if ($item->starttime == 0)
											$hour = 12;
										else
											$hour = floor($item->starttime);
									}
									else
									{
										$timeperiod = "pm";
										if ($item->starttime == 12)
											$hour = $item->starttime;
										else
											$hour = floor($item->starttime) - 12;
									}
								}
								
								if (fmod($item->starttime, 1) == 0.25)
                                    $minutes = "15";
								elseif (fmod($item->starttime, 1) == 0.50)
									$minutes = "30";
								elseif (fmod($item->starttime, 1) == 0.75)
									$minutes = "45";
                                else
                                    $minutes = "00";
																	
								if ($options['timeformat'] == '24 hours')
									echo $hour . "h" . $minutes . "\n";
								else
									echo $hour . ":" . $minutes . $timeperiod . "\n";
								?></td>
								<td style='background:#FFF'><a href='?page=riotschedule.php&amp;deleteitem=<?php echo $item->id; ?>&amp;schedule=<?php echo $schedule; ?>' 
								<?php echo "onclick=\"if ( confirm('" . esc_js(sprintf( __("You are about to delete the item '%s'\n  'Cancel' to stop, 'OK' to delete."), $item->name )) . "') ) { return true;}return false;\""; ?>><img src='<?php echo $wspluginpath; ?>/icons/delete.png' /></a></td>
								</tr>
							  <?php endforeach; ?>				
							  
							  </tbody>
							  </table>
							<?php endif; ?>
				</div>
				<?php elseif ($adminpage == "days"): ?>
				<div>
					<a href="?page=riotschedule.php&amp;settings=general&amp;schedule=<?php echo $schedule; ?>">General Settings</a> | <a href="?page=riotschedule.php&amp;settings=categories&amp;schedule=<?php echo $schedule; ?>">Manage Schedule Categories</a> | <a href="?page=riotschedule.php&amp;settings=items&amp;schedule=<?php echo $schedule; ?>">Manage Schedule Items</a> | <a href="?page=riotschedule.php&amp;settings=days&amp;schedule=<?php echo $schedule; ?>"><strong>Manage Days Labels</strong></a><br /><br />
					<div>
						<form name="rs_daysform" action="" method="post" id="ws-config">
						<?php
						if ( function_exists('wp_nonce_field') )
							wp_nonce_field('wspp-config');
							
						$days = $wpdb->get_results("SELECT * from " . $wpdb->prefix . "rs_days WHERE scheduleid = " . $schedule . " ORDER by id");
						
						if ($days):
						?>
						<input type="hidden" name="schedule" value="<?php echo $schedule; ?>" />
						<table>
						<tr>
						<th style='text-align:left'><strong>ID</strong></th><th style='text-align:left'><strong>Name</strong></th>
						</tr>
						<?php foreach($days as $day): ?>
							<tr>
								<td style='width:30px;'><?php echo $day->id; ?></td><td><input style="width:300px" type="text" name="<?php echo $day->id; ?>" value='<?php echo $day->name; ?>'/></td>
							</tr>
						<?php endforeach; ?>
						</table>					
						
						<p style="border:0;" class="submit"><input type="submit" name="updatedays" value="Update &raquo;" /></p>
						
						<?php endif; ?>
						
						</form>
					</div>
				</div>
				<?php endif; ?>			
			</div>
            <div>
                <p>This plugin is free, and licenced under GPLv2. If you've found this plugin useful please do share and/or donate. <form action="https://www.paypal.com/cgi-bin/webscr" method="post"><input type="hidden" name="cmd" value="_s-xclick"><input type="hidden" name="hosted_button_id" value="4JHJ3RZJAWRP4"><input type="image" src="https://www.paypalobjects.com/en_GB/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="Donate via PayPal."><img alt="" border="0" src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" width="1" height="1"></form></p>
            </div>	
			<?php
		} // end config_page()

	} // end class WS_Admin
} //endif


function rs_install() {
	// Install
	global $wpdb;
	
	$charset_collate = '';
	if ( version_compare(mysql_get_server_info(), '4.1.0', '>=') ) {
		if (!empty($wpdb->charset)) {
			$charset_collate .= " DEFAULT CHARACTER SET $wpdb->charset";
		}
		if (!empty($wpdb->collate)) {
			$charset_collate .= " COLLATE $wpdb->collate";
		}
	}
	
	$wpdb->rs_categories = $wpdb->prefix.'rs_categories';

	$result = $wpdb->query("
			CREATE TABLE IF NOT EXISTS `$wpdb->rs_categories` (
				`id` int(10) unsigned NOT NULL auto_increment,
				`name` varchar(255) NOT NULL,
				`scheduleid` int(10) default NULL,
				`backgroundcolor` varchar(7) NULL,
				PRIMARY KEY  (`id`)
				) $charset_collate"); 
				
	$catsresult = $wpdb->query("
			SELECT * from `$wpdb->rs_categories`");
			
	if (!$catsresult)
		$result = $wpdb->query("
			INSERT INTO `$wpdb->rs_categories` (`name`, `scheduleid`, `backgroundcolor`) VALUES
			('Default', 1, NULL)");				
				
	$wpdb->rs_days = $wpdb->prefix.'rs_days';
	
	$result = $wpdb->query("
			CREATE TABLE IF NOT EXISTS `$wpdb->rs_days` (
				`id` int(10) unsigned NOT NULL,
				`name` varchar(12) NOT NULL,
				`rows` int(10) unsigned NOT NULL,
				`scheduleid` int(10) NOT NULL default '0',
				PRIMARY KEY  (`id`, `scheduleid`)
				)  $charset_collate"); 
				
	$daysresult = $wpdb->query("
			SELECT * from `$wpdb->rs_days`");
			
	if (!$daysresult)
		$result = $wpdb->query("
			INSERT INTO `$wpdb->rs_days` (`id`, `name`, `rows`, `scheduleid`) VALUES
			(1, 'Mon', 1, 1),
			(2, 'Tue', 1, 1),
			(3, 'Wed', 1, 1),
			(4, 'Thu', 1, 1),
			(5, 'Fri', 1, 1),
			(6, 'Sat', 1, 1),
			(7, 'Sun', 1, 1)");
			
	$wpdb->rs_items = $wpdb->prefix.'rs_items';
    
	$item_table_creation_query = "
			CREATE TABLE `$wpdb->rs_items` (
				`id` int(10) unsigned NOT NULL auto_increment,
				`pid` int(10) unsigned NOT NULL,
				`name` text(500) NOT NULL,
				`description` text NOT NULL,
				`starttime` float unsigned NOT NULL,
				`duration` float NOT NULL,
				`day` int(10) unsigned NOT NULL,
				`category` int(10) unsigned NOT NULL,
				`scheduleid` int(10) NOT NULL default '0',
				`enabled` tinyint(1) NOT NULL default '1',
				`type` int(1) unsigned NOT NULL default '1',
				PRIMARY KEY  (`id`,`scheduleid`)
			) $charset_collate";
    
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $item_table_creation_query );
	
	// Any upgrade scripts do it here. Check version, upgrade as necessary
	
	$genoptions = get_option("RiotScheduleGeneral");
	
	if ( $genoptions == false ) {
		$genoptions = rs_return_default_genoptions($genoptions);
		update_option( 'RiotScheduleGeneral', $genoptions );
	} 
	if ( $genoptions['version'] === "2.7" ) {
		// Port from Weekly Schedule may have put this false version number in. Revert back to 1.0
		$genoptions['version'] = "1.0";
		update_option( 'RiotScheduleGeneral', $genoptions );
	}
	
	if ( $genoptions['version'] === "1.0" ) {
		// Add type to scheduled item (introduced 1.1)
		$result = $wpdb->query("ALTER TABLE `$wpdb->rs_items` ADD COLUMN `type` INT(1) UNSIGNED NOT NULL DEFAULT '1' AFTER `enabled`;");
		
		$genoptions['version'] = "1.1";
		update_option( 'RiotScheduleGeneral', $genoptions );
	}
	if ( $genoptions['version'] === "1.1" ) {
		// Bug in new 1.0 installations may have prevented this being added in. 
		$result = $wpdb->query("ALTER TABLE `$wpdb->rs_items` ADD COLUMN `type` INT(1) UNSIGNED NOT NULL DEFAULT '1' AFTER `enabled`;");
		$genoptions['version'] = "1.1b2";
		update_option( 'RiotScheduleGeneral', $genoptions );
	}
}

function rs_import_from_weekly_schedule() {
	// Import from the Weekly Schedule plugin
		
}
?>