<?php
namespace WTS_EAE;

use Elementor;
use WTS_EAE\Classes\Helper;
use const EAE_PATH;

class Plugin {

	public static $instance;

	public $module_manager;

	public static $helper = null;
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public function __construct() {
		//die('---');
		$this->register_autoloader();
		self::$helper = new Helper();

		add_action( 'elementor/init', [ $this, 'eae_elementor_init' ], - 10 );
		add_action( 'elementor/elements/categories_registered', [ $this, 'register_category' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'eae_scripts' ] );
		add_action( 'elementor/editor/wp_head', [ $this, 'eae_editor_enqueue_scripts' ] );
		add_action( 'elementor/controls/controls_registered', [ $this, 'register_controls' ] );
		add_action( 'plugins_loaded', [ $this, '_plugins_loaded' ] );
		//add_action('elementor/widgets/widgets_registered','widgets_registered');
		add_action('admin_enqueue_scripts', [$this,'eae_admin_scripts']);

		$this->_includes();

		$this->module_manager = new Managers\Module_Manager();
	}

	function eae_elementor_init() {

	}

	public function _plugins_loaded() {


		if ( ! did_action( 'elementor/loaded' ) ) {
			/* TO DO */
			add_action( 'admin_notices', array( $this, 'wts_eae_pro_fail_load' ) );

			return;
		}
		$elementor_version_required = '3.0';


		// Check for required Elementor version
		if ( ! version_compare( ELEMENTOR_VERSION, $elementor_version_required, '>=' ) ) {
			add_action( 'admin_notices', array($this,'elementor_requried_version_fail' ));
			return;
		}

		// WPML Compatibility
        if (is_plugin_active('sitepress-multilingual-cms/sitepress.php') && is_plugin_active('wpml-string-translation/plugin.php')) {
            require_once EAE_PATH . 'wpml/modules/class-wpml-eae-animated-text.php';
            require_once EAE_PATH . 'wpml/modules/class-wpml-eae-gmap.php';
            require_once EAE_PATH . 'wpml/modules/class-wpml-eae-filterable-gallery.php';
            require_once EAE_PATH . 'wpml/modules/class-wpml-eae-price-table.php';
            require_once EAE_PATH . 'wpml/modules/class-wpml-eae-timeline.php';
            require_once EAE_PATH . 'wpml/modules/class-wpml-eae-info-circle.php';
            require_once EAE_PATH . 'wpml/modules/class-wpml-eae-comparison-table.php';
            require_once EAE_PATH . 'wpml/wpml-compatibility.php';
        }
	}

	public function register_category( $elements ) {

		\Elementor\Plugin::instance()->elements_manager->add_category(
			'wts-eae',
			[
				'title' => 'Elementor Addon Elements',
				'icon'  => 'font'
			],
			1
		);

		//require_once EAE_PATH.'modules/animated-gradient.php';
		//require_once EAE_PATH.'modules/particles.php';
		//require_once EAE_PATH.'modules/bg-slider.php';
	}

	public function _includes() {
		if(is_admin()){
			require_once EAE_PATH. 'inc/admin/settings-page.php';
			require_once EAE_PATH. 'inc/admin/controls.php';
			require_once EAE_PATH. 'inc/admin/Settings.php';
		}
	}
	public function register_controls( Elementor\Controls_Manager $controls_manager ) {

		require_once EAE_PATH . 'controls/group/icon.php';
		require_once EAE_PATH . 'controls/group/icon_timeline.php';
		require_once EAE_PATH . 'controls/group/grid-control.php';

		//$controls_manager->register_control( self::BPEL_HOVER_TRANSITION, new \BPEL\Controls\Hover_Transition() );

		$controls_manager->add_group_control( 'eae-icon', new \WTS_EAE\Controls\Group\Group_Control_Icon() );

		$controls_manager->add_group_control( 'eae-icon-timeline', new \WTS_EAE\Controls\Group\Group_Control_Icon_Timeline() );

		$controls_manager->add_group_control( 'eae-grid', new \WTS_EAE\Controls\Group\Group_Control_Grid() );

	}
	function eae_admin_scripts(){
		$screen = get_current_screen();
		if($screen->id == 'toplevel_page_eae-settings') {
			add_action( 'admin_print_scripts', [ $this, 'eae_disable_admin_notices' ] );

			wp_enqueue_script( 'eae-admin', EAE_URL . 'assets/js/admin.js', [ 'wp-components' ], '1.0', true );
			wp_enqueue_style( 'eae-admin-css', EAE_URL . 'assets/css/eae-admin.css' );



			$modules = self::$helper->get_eae_modules();

			wp_localize_script( 'eae-admin', 'eaeGlobalVar', array(
				'site_url'     => site_url(),
				'eae_dir'      => EAE_URL,
				'ajax_url'     => admin_url( 'admin-ajax.php' ),
				'map_key'      => get_option( 'wts_eae_gmap_key' ),
				'eae_elements' => $modules,
				'eae_version' => EAE_VERSION,
				'nonce'        => wp_create_nonce( 'eae_ajax_nonce' )
			) );
		}
	}

	function eae_disable_admin_notices() {
		global $wp_filter;
		if ( is_user_admin() ) {
			if ( isset( $wp_filter['user_admin_notices'] ) ) {
				unset( $wp_filter['user_admin_notices'] );
			}
		} elseif ( isset( $wp_filter['admin_notices'] ) ) {
			unset( $wp_filter['admin_notices'] );
		}
		if ( isset( $wp_filter['all_admin_notices'] ) ) {
			unset( $wp_filter['all_admin_notices'] );
		}
	}

	function eae_scripts() {
		wp_enqueue_style( 'eae-css', EAE_URL . 'assets/css/eae'.EAE_SCRIPT_SUFFIX.'.css' );

		/* chart js file */
        wp_register_script( 'eae-chart', EAE_URL . 'assets/js/Chart.bundle'.EAE_SCRIPT_SUFFIX.'.js');


		/* animated text css and js file*/


		wp_register_script( 'animated-main', EAE_URL . 'assets/js/animated-main'.EAE_SCRIPT_SUFFIX.'.js', array( 'jquery' ), '1.0', true );

		wp_enqueue_script( 'eae-main', EAE_URL . 'assets/js/eae'.EAE_SCRIPT_SUFFIX.'.js', array(
			'jquery',
		), '1.0', true );
		wp_localize_script('eae-main','eae',array(
			'ajaxurl' => admin_url('admin-ajax.php'),
			'current_url' => base64_encode( self::$helper->get_current_url_non_paged()),
			'breakpoints' => Elementor\Core\Responsive\Responsive::get_breakpoints()
		));
		wp_register_script( 'eae-particles', EAE_URL . 'assets/js/particles'.EAE_SCRIPT_SUFFIX.'.js', array( 'jquery' ), '1.0', true );

		wp_register_style( 'vegas-css', EAE_URL . 'assets/lib/vegas/vegas'.EAE_SCRIPT_SUFFIX.'.css' );
		wp_register_script( 'vegas', EAE_URL . 'assets/lib/vegas/vegas'.EAE_SCRIPT_SUFFIX.'.js', array( 'jquery' ), '2.4.0', true );
		wp_register_script( 'wts-swiper-script', EAE_URL . 'assets/lib/swiper/js/swiper'.EAE_SCRIPT_SUFFIX.'.js', array( 'jquery' ), '4.4.6', true );
		wp_register_script( 'wts-swiper-style', EAE_URL . 'assets/lib/swiper/css/swiper'.EAE_SCRIPT_SUFFIX.'.css' );

		wp_register_script( 'wts-magnific', EAE_URL . 'assets/lib/magnific'.EAE_SCRIPT_SUFFIX.'.js', array( 'jquery' ), '1.9', true );
		wp_register_script( 'wts-isotope', EAE_URL . 'assets/lib/isotope/isotope.pkgd'.EAE_SCRIPT_SUFFIX.'.js', array( 'jquery' ), '2.6.2', true );
		wp_register_script( 'wts-tilt', EAE_URL . 'assets/lib/tilt/tilt.jquery'.EAE_SCRIPT_SUFFIX.'.js', array( 'jquery' ), '', true );
		if(is_plugin_active('elementor/elementor.php')) {
			wp_register_style(
				'font-awesome-5-all',
				ELEMENTOR_ASSETS_URL . 'lib/font-awesome/css/all.min.css'
			);
			wp_register_style(
				'font-awesome-4-shim',
				ELEMENTOR_ASSETS_URL . 'lib/font-awesome/css/v4-shims.min.css' );
			wp_register_script(
				'font-awesome-4-shim',
				ELEMENTOR_ASSETS_URL . 'lib/font-awesome/js/v4-shims.min.js'
			);
		}
		$map_key = get_option( 'wts_eae_gmap_key' );
		if ( isset( $map_key ) && $map_key != '' ) {
			wp_register_script( 'eae-gmap', 'https://maps.googleapis.com/maps/api/js?key=' . $map_key );
		}

		wp_register_script( 'pinit', '//assets.pinterest.com/js/pinit.js', '', '', false );


		wp_register_script( 'eae-stickyanything', EAE_URL . 'assets/js/stickyanything.js', array( 'jquery' ), '1.1.2', true );

		$localize_data = array(
			'plugin_url' => EAE_URL
		);
		wp_localize_script( 'eae-main', 'eae_editor', $localize_data );



	}

	function eae_editor_enqueue_scripts() {

		wp_enqueue_style( 'eae-icons', EAE_URL . 'assets/lib/eae-icons/style.css' );
	}

	private function register_autoloader() {
		spl_autoload_register( [ __CLASS__, 'autoload' ] );
	}

	function autoload( $class ) {

		/*require_once EAE_PATH.'inc/helper.php';*/
		if ( 0 !== strpos( $class, __NAMESPACE__ ) ) {
			return;
		}


		if ( ! class_exists( $class ) ) {

			$filename = strtolower(
				preg_replace(
					[ '/^' . __NAMESPACE__ . '\\\/', '/([a-z])([A-Z])/', '/_/', '/\\\/' ],
					[ '', '$1-$2', '-', DIRECTORY_SEPARATOR ],
					$class
				)
			);

			$filename = EAE_PATH . $filename . '.php';

			if ( is_readable( $filename ) ) {
				include( $filename );
			}
		}
	}
	function elementor_requried_version_fail() {
		if ( ! current_user_can( 'update_plugins' ) ) {
			return;
		}
		$elementor_version_required = '3.0.0';
		$file_path = 'elementor/elementor.php';

		$upgrade_link = wp_nonce_url( self_admin_url( 'update.php?action=upgrade-plugin&plugin=' ) . $file_path, 'upgrade-plugin_' . $file_path );
		$message = '<p>' . __( 'Elementor Addon Elements requires Elementor ' . $elementor_version_required . '. Please update Elementor to continue.', 'eae-wts' ) . '</p>';
		$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $upgrade_link, __( 'Update Elementor Now', 'elementor-pro' ) ) . '</p>';

		echo '<div class="error">' . $message . '</div>';
	}

	public function wts_eae_pro_fail_load() {

		$plugin = 'elementor/elementor.php';

		if ( _is_elementor_installed() ) {
			if ( ! current_user_can( 'activate_plugins' ) ) {
				return;
			}

			$message = sprintf( __( '<b>Elementor Addon Elements</b> is not working because you need to activate the <b>Elementor</b> plugin.', 'wts-eae' ), '<strong>', '</strong>' );
			$action_url   = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin );
			$button_label = __( 'Activate Elementor', 'wts-eae' );

		} else {
			if ( ! current_user_can( 'install_plugins' ) ) {
				return;
			}
			$message = sprintf( __( '<b>Elementor Addon Elements</b> is not working because you need to install the <b>Elementor</b> plugin.', 'wts-eae' ), '<strong>', '</strong>' );
			$action_url   = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ), 'install-plugin_elementor' );
			$button_label = __( 'Install Elementor', 'wts-eae' );
		}

		$button = '<p><a href="' . $action_url . '" class="button-primary">' . $button_label . '</a></p><p></p>';

		printf( '<div class="%1$s"><p>%2$s</p>%3$s</div>', 'notice notice-error', $message, $button );
	}
}

Plugin::get_instance();