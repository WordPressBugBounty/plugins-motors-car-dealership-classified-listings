<?php
class ButterBean_Control_Section_Title extends ButterBean_Control {
	/**
	* The type of control.
	*
	* @since  1.0.0
	* @access public
	* @var    string
	*/
	public $type = 'section_title';
	public $heading;
	public $link;

	/**
	* Constructor for the control.
	*
	* @since  1.0.0
	* @access public
	* @param  ButterBean $manager Control manager instance.
	* @param  string     $id      Control ID.
	* @param  array      $args    Control arguments.
	*/
	public function __construct( $manager, $id, $args = array() ) {
		parent::__construct( $manager, $id, $args );

		if ( isset( $args['heading'] ) ) {
			$this->heading = $args['heading'];
		}

		if ( isset( $args['link'] ) ) {
			$this->link = $args['link'];
		}
	}

	/**
	* Adds custom data to the json array. This data is passed to the Underscore template.
	*
	* @since  1.0.0
	* @access public
	* @return void
	*/
	public function to_json() {
		parent::to_json();
		$this->json['heading'] = $this->heading;

		$this->json['link'] = $this->link;
	}
}
