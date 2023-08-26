<?php

namespace StellarWP\Templates;

class Part_Cache {

	/**
	 * @var string
	 */
	protected string $template;

	/**
	 * @var int
	 */
	protected int $expiration;

	/**
	 * @var string
	 */
	protected string $expiration_trigger;

	/**
	 * @var string|bool|null
	 */
	protected $html;

	/**
	 * @var string
	 */
	protected string $key;

	/**
	 ** Short description
	 *
	 * @param $template           - which template in the views directory is being cached (relative path).
	 * @param $id                 - a unique identifier for this fragment.
	 * @param $expiration         - expiration time for the cached fragment.
	 * @param $expiration_trigger - wordpress hook to expire on.
	 */
	public function __construct( $template, $id, $expiration, $expiration_trigger ) {
		$this->template           = $template;
		$this->key                = $template . '_' . $id;
		$this->expiration         = $expiration;
		$this->expiration_trigger = $expiration_trigger;

		$this->add_hooks();
	}

	/**
	 * Hook in to show cached content and bypass queries where needed
	 */
	public function add_hooks() {
		$hook_prefix = Config::get_hook_prefix();

		// set the cached html in transients after the template part is included
		add_filter( "stellarwp/templates/{$hook_prefix}/get_template_part_content", [ $this, 'set' ], 10, 2 );

		// get the cached html right before the setup_view runs so it's available for bypassing any view logic
		add_action( "stellarwp/templates/{$hook_prefix}/before_view", [ $this, 'get' ], 9, 1 );

		// when the specified template part is included, show the cached html instead
		add_filter( "stellarwp/templates/get_template_part_path_{$this->template}", [ $this, 'display' ] );
	}

	/**
	 * Checks if there is a cached html fragment in the transients, if it's there,
	 * don't include the requested file path. If not, just return the file path like normal
	 *
	 * @param $path file path to the month view template part
	 *
	 * @return string
	 */
	public function display( $path ): string {

		if ( $this->html !== false ) {
			echo $this->html;

			return '';
		}

		return (string) $path;
	}

	/**
	 * Set cached html in transients
	 *
	 * @param $html
	 * @param $template
	 *
	 * @return string
	 */
	public function set( $html, $template ): string {
		if ( $template == $this->template ) {
			set_transient( "{$this->key}|{$this->expiration_trigger}", $html, $this->expiration );
		}

		return $html;
	}

	/**
	 * Retrieve the cached html from transients, set class property
	 */
	public function get() {

		if ( isset( $this->html ) ) {

			return $this->html;
		}

		$this->html = get_transient( "{$this->key}|{$this->expiration_trigger}" );

		return $this->html;

	}
}
