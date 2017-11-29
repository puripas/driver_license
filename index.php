<?php
/**
 * Plugin Name: driver license
 * Plugin URI: http://www.puripas.com
 * Description: driver license.
 * Version: 1.0.0
 * Author: Amnat  Rompruek
 * Author URI: http://www.puripas.com
 * License: GPL2
*/
function wcps_load_plugin_textdomain() {
	load_plugin_textdomain( 'driver_license', false, dirname( plugin_basename( __FILE__ ) ) . '/language' );
}
// Add jquery to admin page
add_action( 'admin_enqueue_scripts', 'my_admin_enqueue_scripts' );
function my_admin_enqueue_scripts($hook) {
/*  */
}
add_action('init', 'driver_license');     
function driver_license() {  
    $args = array(  
        'label' => __('Car License Quiz', 'driver_license'),  
        'singular_label' => __('Car License Quiz', 'driver_license'),  
        'public' => true,  
        'show_ui' => true,  
        'capability_type' => 'post',  
        'hierarchical' => false,  
        'rewrite' => true,  
        'supports' => array('title', 'editor', 'thumbnail')  
       );     
    register_post_type( 'car_license_quiz' , $args );  
}
register_taxonomy("car_license_quiz_category", array("car_license_quiz"), array("hierarchical" => true, "label" => __('Car License Quiz Category', 'fahmai'), "singular_label" => __('Car License Quiz Category', 'driver_license'), "rewrite" => false));
?>
<?php
// Add the Meta Box
add_action('add_meta_boxes', 'add_car_license_quiz_meta_box');
function add_car_license_quiz_meta_box() {
    add_meta_box(
        'car_license_quiz_meta_box', // $id
        'Car License Quiz', // $title 
        'car_license_quiz_meta_box', // $callback
        'car_license_quiz', // $page
        'normal', // $context
        'high'); // $priority
}
// Field Array
$prefix = 'car_license_quiz_';
$car_license_quiz_meta_fields = array(
    array(
        'label'=> __('ก.', 'driver_license'),
        'desc'  => __('A.', 'driver_license'),
        'id'    => $prefix.'quiz_a',
        'type'  => 'text'
    ),
    array(
        'label'=> __('ข.', 'driver_license'),
        'desc'  => __('B.', 'driver_license'),
        'id'    => $prefix.'quiz_b',
        'type'  => 'text'
    ),
    array(
        'label'=> __('ค.', 'driver_license'),
        'desc'  => __('C.', 'driver_license'),
        'id'    => $prefix.'quiz_c',
        'type'  => 'text'
    ),
    array(
        'label'=> __('ง.', 'driver_license'),
        'desc'  => __('D.', 'driver_license'),
        'id'    => $prefix.'quiz_d',
        'type'  => 'text'
    ),					
    array(
		'label'=> __('ข้อที่ถูกต้อง', 'driver_license'),
        'desc'  => __('Answers for the exam.', 'driver_license'),
        'id'    => $prefix.'quiz_answers',
        'type'  => 'select',
        'options' => array (
            'one' => array (
                'label' => __('ข้อ ก', 'driver_license'),
                'value' => '1'
            ),
            'two' => array (
                'label' => __('ข้อ ข', 'driver_license'),
                'value' => '2'
            ),
            'three' => array (
                'label' => __('ข้อ ค', 'driver_license'),
                'value' => '3'
            ),
            'four' => array (
                'label' => __('ข้อ ง', 'driver_license'),
                'value' => '4'
            )			
        )
    )

);
?>
<?php
// The Callback
function car_license_quiz_meta_box() {
global $car_license_quiz_meta_fields, $post;       
// Use nonce for verification
echo '<input type="hidden" name="custom_meta_box_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';
     
    // Begin the field table and loop
    echo '<table class="form-table">';
    foreach ($car_license_quiz_meta_fields as $field) {
        // get value of this field if it exists for this post
        $meta = get_post_meta($post->ID, $field['id'], true);
        // begin a table row with
        echo '<tr>
                <th><label for="'.$field['id'].'">'.$field['label'].'</label></th>
                <td>';
                switch($field['id']) {
                    // case items will go here
					// text
					case 'car_license_quiz_quiz_a':
						echo '<input type="text" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" size="30" />
							<br /><span class="description">'.$field['desc'].'</span>';
					break;
					case 'car_license_quiz_quiz_b':
						echo '<input type="text" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" size="30" />
							<br /><span class="description">'.$field['desc'].'</span>';
					break;
					case 'car_license_quiz_quiz_c':
						echo '<input type="text" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" size="30" />
							<br /><span class="description">'.$field['desc'].'</span>';
					break;
					case 'car_license_quiz_quiz_d':
						echo '<input type="text" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" size="30" />
							<br /><span class="description">'.$field['desc'].'</span>';
					break;																																																																												
					case 'car_license_quiz_quiz_answers':// select
						echo '<select name="'.$field['id'].'" id="'.$field['id'].'">';
						foreach ($field['options'] as $option) {
							echo '<option', $meta == $option['value'] ? ' selected="selected"' : '', ' value="'.$option['value'].'">'.$option['label'].'</option>';
						}
						echo '</select><br /><span class="description">'.$field['desc'].'</span>';
					break;
																							
                } //end switch
        echo '</td></tr>';
    } // end foreach
    echo '</table>'; // end table
}
?>
<?php   
// Save the Data
add_action('save_post', 'save_car_license_quiz_meta_box');
function save_car_license_quiz_meta_box($post_id) {
    global $custom_meta_fields;
	if(!empty($_POST)){     
		// verify nonce
		if (!wp_verify_nonce($_POST['custom_meta_box_nonce'], basename(__FILE__))) 
			return $post_id;
		// check autosave
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
			return $post_id;
		// check permissions
		if ('page' == $_POST['post_type']) {
			if (!current_user_can('edit_page', $post_id))
				return $post_id;
			} elseif (!current_user_can('edit_post', $post_id)) {
				return $post_id;
		}
		 
		// loop through fields and save the data
		foreach ($custom_meta_fields as $field) {
			$old = get_post_meta($post_id, $field['id'], true);
			$new = $_POST[$field['id']];
			if ($new && $new != $old) {
				update_post_meta($post_id, $field['id'], $new);
			} elseif ('' == $new && $old) {
				delete_post_meta($post_id, $field['id'], $old);
			}
		} // end foreach
	}
}
?>
