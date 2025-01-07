<?php

namespace MotorsVehiclesListing\MenuPages;

class MenuBuilder {

	public $menu_positions;

	public function __construct() {
		add_filter( 'custom_menu_order', array( $this, 'submenu_order' ) );
	}

	public function submenu_order( $menu_order ) {
		global $submenu;

		$this->menu_positions = apply_filters( 'mvl_submenu_positions', array() );
		asort( $this->menu_positions );
		$sort_by_position = $this->menu_positions;
		$for_sort         = $submenu['mvl_plugin_settings'];

		$starter      = array();
		$matchedItems = array();

		foreach ( $for_sort as $k => $item ) {
			if ( 'motors_starter_demo_installer' === $item[2] || 'mst-starter-options' === $item[2] ) {
				$starter = $item;
				unset( $for_sort[ $k ] );
				continue;
			}

			if ( array_key_exists( $item[2], $sort_by_position ) ) {
				$matchedItems[ $item[2] ] = $item;
			}
		}

		$sortedMatchedItems = array();
		foreach ( $sort_by_position as $key => $value ) {
			if ( isset( $matchedItems[ $key ] ) ) {
				$sortedMatchedItems[] = $matchedItems[ $key ];
			}
		}

		$sortedIterator = 0;
		$finalArray     = array();

		foreach ( $for_sort as $k => $item ) {
			if ( array_key_exists( $item[2], $sort_by_position ) && ! empty( $sortedMatchedItems[ $sortedIterator ] ) ) {
				$finalArray[] = $sortedMatchedItems[ $sortedIterator ];
				$sortedIterator ++;
			} else {
				$finalArray[] = $item;

				if ( 0 !== $k && 'mvl_plugin_settings' === $item[2] && ! empty( $starter ) ) {
					$finalArray[] = $starter;
				}
			}
		}

		$submenu['mvl_plugin_settings'] = $finalArray;

		return $menu_order;
	}
}

