<?php
namespace Yivic\Wp\Plugin;

use Exception;
use Illuminate\Container\Container;
use Yivic\Wp\Plugin\Interfaces\WPPluginInterface;
use Yivic\Wp\Plugin\Services\ViewService;
use Yivic\Wp\Plugin\Traits\ServiceTrait;
use Yivic\Wp\Plugin\Traits\ConfigTrait;
use Yivic\Wp\Plugin\Traits\WPAttributeTrait;

/**
 * Class YivicPpvp
 * @package YivicPpvp\Wp\Plugin
 */
class YivicPpvp extends Container implements WPPluginInterface {
    use ConfigTrait;
    use WPAttributeTrait;

    const OPTIONS_GROUP_NAME = 'yivic_ppvp_options_group';

    /**
     * @var string Version of this plugin
     */
    public $version;

    /** @noinspection PhpUnusedElementInspection */
    /**
     * @var string Base path to this plugin
     */
    public $basePath;

    /**
     * @var string Base url of the folder of this plugin
     */
    public $baseUrl;

    /**
     * @var string Name of the custom endpoint
     */
    public $customEndpointName;

    /**
     * Ppvp constructor.
     *
     * @param $config
     */
    public function __construct( $config ) {
        $this->bindConfig( $config );

        if ( !empty( $services = $config['services'] ?? null ) ) {
            $this->registerServices( $services );
        }
    }

    /**
     * Register service providers set in config
     *
     * @param $services
     */
    protected function registerServices( $services ) {
        foreach ( $services as $serviceClassname => $serviceConfig ) {
            $this->bind(
                $serviceClassname,
                function ( $container ) use ( $serviceClassname, $serviceConfig ) {
                    $serviceInstance = new $serviceClassname();

                    if ( in_array( ConfigTrait::class, class_uses( $serviceInstance ), true ) ) {
                        /** @noinspection PhpUndefinedMethodInspection */
                        $serviceInstance->bindConfig( $serviceConfig );
                    }

                    if ( in_array( ServiceTrait::class, class_uses( $serviceInstance ), true ) ) {
                        /** @noinspection PhpUndefinedMethodInspection */
                        $serviceInstance->setContainer( $container );
                        /** @noinspection PhpUndefinedMethodInspection */
                        $serviceInstance->init();
                    }

                    return $serviceInstance;
                }
            );
        }
    }

    /** @noinspection PhpFullyQualifiedNameUsageInspection */
    /**
     * @param $alias
     *
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function getService( $alias ): mixed {
        return $this->make( $alias );
    }

    /** @noinspection PhpFullyQualifiedNameUsageInspection */
    /**
     * Get the `view` service
     *
     * @return ViewService
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public static function getServiceView(): ViewService {
        return static::getInstance()->getService( ViewService::class );
    }

    /**
     * @param $config
     *
     * @throws Exception
     */
    public static function initInstanceWithConfig( $config ) {
        if ( is_null( static::$instance ) ) {
            static::setInstance( new static( $config ) );
        }

        if ( !static::getInstance() instanceof static ) {
            throw new Exception( 'No plugin initialized.' );
        }
    }

    /**
     * Get application locale
     *
     * @return string
     */
    public function getLocale(): string {
        return determine_locale();
    }

    /**
     * Load locale file to textDomain
     */
    protected function loadTextDomain() {
        $locale     = $this->getLocale();
        $mo_file    = $locale.'.mo';
        load_textdomain( $this->textDomain, $this->basePath.'/languages/'.$mo_file );
    }

    /**
     * Initialize all needed things for this plugin: hooks, assignments ...
     */
    public function initPlugin(): void {
        // Load Text Domain
        $this->loadTextDomain();

        add_action( 'init', [ $this, 'addCustomRewriteRules' ] );

        // For Admin
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueueAdminSettingScripts' ] );

        // Hooks for admin
        add_action( 'admin_init', [ $this, 'registerSettingsGroup' ] );
        add_action( 'admin_menu', [ $this, 'registerSettingsPage' ] );

        add_action( 'wp_enqueue_scripts', [ $this, 'enqueueScripts' ] );
    }

    /**
     * Do some needed things when activate plugin
     */
    public function activatePlugin() {
        // Add rewrite rules
        $this->addCustomRewriteRules();

        // Flush rewrite rules when activate plugins
        flush_rewrite_rules( false );
    }

    /**
     * @noinspection PhpUnusedDeclarationInspection
     */
    public function deactivatePlugin() {
        // The problem with calling flush_rewrite_rules() is that the rules instantly get regenerated, while your plugin's hooks are still active.
        delete_option( 'rewrite_rules' );
    }

    /**
     * Check if current screen is Yivic Admin Settings page
     *
     * @return bool
     */
    public function isYivicAdminSettingsScreen() {
        return ( is_admin() );
    }

    /**
     * Enqueue admin scripts for settings
     */
    public function enqueueAdminSettingScripts() {
        // Only enqueue the setting scripts on the Yivic Ppvp settings screen.
        if ( $this->isYivicAdminSettingsScreen() ) {
            wp_enqueue_script( 'yivic-ppvp-settings-js', $this->baseUrl.'/assets/dist/js/admin.js', ['jquery'],
                $this->version, true );
            wp_enqueue_style('yivic-admin-css', $this->baseUrl.'/assets/dist/css/admin.css', [],
                $this->version );
        } // Load the admin stylesheet on shop order screen
        elseif ( isset( $_GET['post_type'] ) ) {
            wp_enqueue_style( 'yivic-admin-css', $this->baseUrl.'/assets/dist/css/admin.css', [],
                $this->version );
        }
    }

    /**
     * Add rewrite rule for Yivic IPN response page
     */
    public function addCustomRewriteRules() {
        add_rewrite_rule( 'custom-ppvp/?$', 'index.php?pagename='.$this->customEndpointName, 'top' );
    }

    /**
     * Register a settings group for this plugin
     */
    public function registerSettingsGroup() {
        add_option( 'custom_endpoint_name', 'custom-ppvp' );
        register_setting( static::OPTIONS_GROUP_NAME, 'custom_endpoint_name', 'sanitize_title' );
    }

    /**
     * Register the Admin page for plugin settings
     */
    public function registerSettingsPage() {
        add_options_page(
            __( 'Ppvp Settings', $this->textDomain ),
            __( 'Ppvp Settings', $this->textDomain ),
            'manage_options',
            'yivic-ppvp-settings',
            [ $this, 'displaySettingsPage' ]
        );
    }

    /**
     * Enqueue FE stylesheet and scripts
     */
    public function enqueueScripts() {
        wp_enqueue_style( 'yivic-ppvp', $this->baseUrl.'/assets/dist/css/main.css', [], $this->version );
        wp_enqueue_script( 'yivic-ppvp', $this->baseUrl.'/assets/dist/js/main.js', ['jquery'], $this->version, true );
    }

    /** @noinspection PhpFullyQualifiedNameUsageInspection */
    /**
     * Showing content for options page
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function displaySettingsPage() {
        /** @var ViewService $viewService */
        $viewService = $this->getService(ViewService::class);

        // We ignore WordPress.XSS.EscapeOutput.OutputNotEscaped because we're gonna do this on the view side
        // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped
        echo $viewService->render(
            'views/admin/yivic-ppvp-settings',
            // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped
            [ 'textDomain' => $this->textDomain ]
        );
        exit;
    }
}