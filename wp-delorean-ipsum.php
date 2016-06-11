<?php
/*
 Plugin Name: Delorean Ipsum Generator
 Plugin URI: https://kungfugrep.com/plugins/delorean-ipsum-generoator
 Description: Adds a button to the post editor that allows clicking to add deloreanipsum.com content
 Author: cklosows
 Version: 0.1
 Author URI: https://chrisk.io
 Text Domain: wp-delorean-ipsum
 Domain Path: languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WP_Delorean_Ipsum' ) ) {

class WP_Delorean_Ipsum {

	private static $instance;

	private function __construct() {
		$this->constants();
		$this->includes();
		$this->hooks();
		$this->filters();
	}

	static public function instance() {

		if ( !self::$instance ) {
			self::$instance = new WP_Delorean_Ipsum();
		}

		return self::$instance;

	}

	private function constants() {

		// Plugin version
		if ( ! defined( 'WP_DI_VERSION' ) ) {
			define( 'WP_DI_VERSION', '0.1' );
		}

		// Plugin Folder Path
		if ( ! defined( 'WP_DI_PLUGIN_DIR' ) ) {
			define( 'WP_DI_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
		}

		// Plugin Folder URL
		if ( ! defined( 'WP_DI_PLUGIN_URL' ) ) {
			define( 'WP_DI_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
		}

		// Plugin Root File
		if ( ! defined( 'WP_DI_PLUGIN_FILE' ) ) {
			define( 'WP_DI_PLUGIN_FILE', __FILE__ );
		}

	}

	private function includes() {}

	private function hooks() {
		add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ) );
		add_action( 'media_buttons', array( $this, 'media_button' ), 11 );
		add_action( 'admin_footer', array( $this, 'admin_footer_for_thickbox' ) );
	}

	private function filters() {}

	public function scripts() {
		global $pagenow;

		if ( in_array( $pagenow, array( 'post.php', 'page.php', 'post-new.php', 'post-edit.php' ) ) ) {
			wp_register_script( 'delorean-ipsum-js', WP_DI_PLUGIN_URL . '/assets/js/jquery.delorean.ipsum.min.js', array('jquery'), WP_DI_VERSION, false );
			wp_enqueue_script( 'delorean-ipsum-js' );

			wp_register_script( 'di-admin-scripts', WP_DI_PLUGIN_URL . '/assets/js/admin-scripts.js', array( 'jquery', 'delorean-ipsum-js' ), WP_DI_VERSION );
			wp_enqueue_script( 'di-admin-scripts' );

			wp_register_style( 'di-admin', WP_DI_PLUGIN_URL . '/assets/css/admin-styles.css', WP_DI_VERSION );
			wp_enqueue_style( 'di-admin' );
		}

	}


	public function media_button() {
		global $pagenow, $typenow;
		$output = '';

		/** Only run in post/page creation and edit screens */
		if ( in_array( $pagenow, array( 'post.php', 'page.php', 'post-new.php', 'post-edit.php' ) ) ) {

			$img = '<span class="wp-media-buttons-icon dashicons dashicons-backup" id="wp-di-media-button"></span>';
			$output = '<a href="#TB_inline?height=500&width=500&inlineId=delorean-ipsum-generator" id="di-thickbox" class="thickbox button" style="padding-left: .4em;">' . $img . ' ' . __( 'Delorean Ipsum', 'delrian-ipsum' ) . '</a>';

		}
		echo $output;
	}

	function admin_footer_for_thickbox() {
		global $pagenow, $typenow;

		add_thickbox();

		// Only run in post/page creation and edit screens
		if ( in_array( $pagenow, array( 'post.php', 'page.php', 'post-new.php', 'post-edit.php' ) ) ) { ?>
			<div id="delorean-ipsum-generator" style="display: none;">
				<div class="di-wrap">
					<p><?php _e( 'Use the form below to configure your content', 'wp-delorean-ipsum' ); ?></p>
					<div class="di-main-config">
						<?php
						$amount_input  = '<input type="number" max="99" min="1" value="3" name="amount" id="di-amount" />';
						$type_input    = '<select id="di-type" name="type"><option selected="selected" value="paragraphs">' . __( 'Paragraphs', 'wp-delorean-ipsum' ) . '</option><option value="sentences">' . __( 'Sentences', 'wp-delorean-ipsum' ) . '</option><option value="words">' . __( 'Words', 'wp-delorean-ipsum' ) . '</option></select>';
						$perpara_input = '<input type="number" max="99" min="1" value="2" name="perpara" id="di-perpara" />';
						?>
						<?php $text = printf( __( 'Create %s %s , with %s sentences per paragraph', 'wp-delorean-ipsum' ), $amount_input, $type_input, $perpara_input ); ?>
					</div>
					<div class="di-additional-config">
						<strong><?php _e( 'Additional configuration', 'wp-delorean-ipsum' ); ?></strong>
						<p>
							<label for="di-tag"><?php _e( 'HTML tag wrapper', 'wp-delorean-ipsum' ); ?></label><br />
							<input type="text" class="small-text" name="tag" value="p" id="di-tag" />
						</p>
						<p>
							<label for="di-character"><?php _e( 'Lines by character', 'wp-delorean-ipsum' ); ?></label><br />
							<input type="text" name="character" value="" placeholder="eg: marty" id="di-character" />
						</p>
					</div>
					<p class="submit">
						<input type="button" id="di-insert-content" class="button-primary" value="<?php echo _e( 'Insert Delorean Ipsum', 'wp-delorean-ipsum' ); ?>" />
						<a id="di-cancel-content-insert" class="button-secondary" onclick="tb_remove();"><?php _e( 'Cancel', 'wp-delorean-ipsum' ); ?></a>
					</p>
				</div>
			</div>
		<?php
		}
	}

}

} // End Class Exists check

function load_wp_delorean_ipsum() {
	return WP_Delorean_Ipsum::instance();
}
add_action( 'plugins_loaded', 'load_wp_delorean_ipsum', PHP_INT_MAX );
