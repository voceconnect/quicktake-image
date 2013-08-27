<?php
/*
  Plugin Name: Quicktake Image Widget
  Plugin URI: http://vocecommunications.com
  Description: Add WordPress Widget that allows users to add an image, link and caption
  Version: 0.1.0
  Author: matstars, voceplatforms
  Author URI: http://vocecommunications.com
  License: GPL2
 */


class quicktake_image_widget extends WP_Widget {

	
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueues' ) );
		parent::__construct(
			'quicktake-image-widget',
			__( 'Quicktake Image Widget', 'quicktake-image-widget' ),
			array(
				'classname'		=>	'quicktake-image-widget',
				'description'	=>	__( 'Adds a simple widget that allows the display of an image with an optional link and a caption.', 'quicktake-image-widget' )
			)
		);
	} 

	
	/**
	 * Outputs the content of the widget.
	 *
	 * @method 	widget
	 * @param	array	args		The array of form elements
	 * @param	array	instance	The current instance of the widget
	 */
	public function widget( $args, $instance ) {
		extract( $args, EXTR_SKIP );
		echo $before_widget;
		$image_array	= wp_get_attachment_image_src( $instance['attachment_id'], 'large' );
		$image_src 		= $image_array[0];
		$caption 		= $instance['caption'];
		$url 			= $instance['url'];
		$alt 			= '';
		if ( ! $url ) {
			$full_size_image_array	= wp_get_attachment_image_src( $instance['attachment_id'], 'full' );
			$url 		= $full_size_image_array[0];
			
		}
		if ( $caption ) {
			$alt = 'alt="' . $caption . '"';
			$caption = '<p>' . $caption . '</p>';
		}
		?>
		 <div class="thumbnail">
			<a href="<?php echo $url; ?>"><img src="<?php echo $image_src; ?>" <?php echo $alt; ?> /></a>
			<?php echo $caption; ?>
		</div>
		<?php
		echo $after_widget;

	} 

	/**
	 * Processes the widget's options to be saved.
	 *
	 * @method 	update
	 * @param	array	new_instance	The previous instance of values before the update.
	 * @param	array	old_instance	The new instance of values to be generated via the update.
	 * @return 	array 	instance
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['attachment_url'] 	= esc_html( $new_instance['attachment_url'] );
		$instance['attachment_id'] 		= esc_html( $new_instance['attachment_id'] );
		$instance['url'] 				= esc_html( $new_instance['url'] );
		$instance['caption']			= strip_tags( $new_instance['caption'] );
		$instance['before_widget'] = "FDFDSFS";
		return $instance;

	} 

	/**
	 * Generates the administration form for the widget.
	 *
	 * @param	array	instance	The array of keys and values for the widget.
	 * @return 	void
	 */
	public function form( $instance ) {
		$defaults = array(
			'attachment_id'		=> 	'',
			'attachment_url'	=> 	'',
			'url'				=> 	'',
			'caption'			=>	''
			);

		$instance = wp_parse_args(
			(array) $instance, (array) $defaults
		);
		$attachment_id 		= $instance['attachment_id'];
		$attachment_url 	= $instance['attachment_url'];
		$url 				= $instance['url'];
		$caption			= $instance['caption'];
		$image 				= '<img />';
		if ( is_numeric( $attachment_id ) ) {
			$image = wp_get_attachment_image( $attachment_id, 'thumbnail' );
		}
		?>
		<div class="quicktake-image-widget">
			<input type="hidden" class="widefat attachment-id" id="<?php echo $this->get_field_id('attachment_id'); ?>" value="<?php echo $attachment_id ?>" name="<?php echo $this->get_field_name('attachment_id'); ?>" />
			<input type="hidden" class="widefat attachment-url" id="<?php echo $this->get_field_id('attachment_url'); ?>" value="<?php echo $attachment_url ?>" name="<?php echo $this->get_field_name('attachment_url'); ?>" />
			<div class="image"><?php echo $image; ?></div>
			<p><label for="<?php echo $this->get_field_id('caption'); ?>">Caption:</label><input type="text" class="widefat" id="<?php echo $this->get_field_id('caption'); ?>" value="<?php echo $caption ?>" name="<?php echo $this->get_field_name('caption'); ?>" /></p>			
			<p><label for="<?php echo $this->get_field_id('url'); ?>">Link:</label><input type="text" class="widefat" id="<?php echo $this->get_field_id('url'); ?>" value="<?php echo $url ?>" name="<?php echo $this->get_field_name('url'); ?>" /></p>
			<a href="#" class="attach-image button clear">Attach Image</a>
		</div>
		<?php 
	} 



	/**
	 *
	 * Enqueue Scripts & Styles
	 * 
	 * @method enqueues
	 * @return void
	 */

	function enqueues( $hook ){
		$pages = apply_filters( 'quicktake_image_widget_scripts', array( 'post-new.php', 'post.php', 'widgets.php' ) );

		if( !in_array( $hook, $pages ) ) {
			return;
		}
		wp_enqueue_script( 'thickbox' );
		wp_enqueue_style( 'thickbox' );
		wp_enqueue_script( 'wp-media-modal', plugins_url( '/js/wp-media-modal.js', __FILE__ ), array( 'thickbox' ) );
		wp_enqueue_script( 'quicktake-image-widget', plugins_url( '/js/quicktake-image-widget.js', __FILE__ ), array( 'wp-media-modal' ) );
	
	}
	
} 


add_action( 'widgets_init', create_function( '', 'register_widget("quicktake_image_widget");' ) );
