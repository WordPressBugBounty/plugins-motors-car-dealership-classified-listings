<?php

namespace MotorsElementorWidgetsFree\Widgets\WidgetManager;

use MotorsElementorWidgetsFree\Widgets\ListingSearchTabs;
use MotorsElementorWidgetsFree\Widgets\ImageCategories;
use MotorsElementorWidgetsFree\Widgets\InventorySearchFilter;
use MotorsElementorWidgetsFree\Widgets\InventorySortBy;
use MotorsElementorWidgetsFree\Widgets\InventoryViewType;
use MotorsElementorWidgetsFree\Widgets\InventorySearchResults;
use MotorsElementorWidgetsFree\Widgets\SingleListing\Title;
use MotorsElementorWidgetsFree\Widgets\SingleListing\Gallery;
use MotorsElementorWidgetsFree\Widgets\SingleListing\Features;
use MotorsElementorWidgetsFree\Widgets\SingleListing\DealerEmail;
use MotorsElementorWidgetsFree\Widgets\SingleListing\DealerPhoneNumber;
use MotorsElementorWidgetsFree\Widgets\SingleListing\OfferPriceButton;
use MotorsElementorWidgetsFree\Widgets\SingleListing\Similar;
use MotorsElementorWidgetsFree\Widgets\SingleListing\ListingDescription;
use MotorsElementorWidgetsFree\Widgets\ContactFormSeven;
use MotorsElementorWidgetsFree\Widgets\SingleListing\Classified\UserDataSimple;
use MotorsElementorWidgetsFree\Widgets\HeaderFooter\AddCarButton;
use MotorsElementorWidgetsFree\Widgets\HeaderFooter\CompareButton;
use MotorsElementorWidgetsFree\Widgets\HeaderFooter\ProfileButton;
use MotorsElementorWidgetsFree\Widgets\ListingsCompare;
use MotorsElementorWidgetsFree\Widgets\AddListing;
use MotorsElementorWidgetsFree\Widgets\DealersList;
use MotorsElementorWidgetsFree\Widgets\PricingPlan;
use MotorsElementorWidgetsFree\Widgets\ListingsGridTabs;
use MotorsElementorWidgetsFree\Widgets\LoginRegister;
use MotorsElementorWidgetsFree\Widgets\SingleListing\Classified\Title as TitleClassified;
use MotorsElementorWidgetsFree\Widgets\SingleListing\Classified\Price as PriceClassified;
use MotorsElementorWidgetsFree\Widgets\SingleListing\Classified\ListingData as ListingDataClassified;

class MotorsWidgetsManagerFree {

	private static $instance = array();

	protected function __construct() {
	}

	protected function __clone() {
	}

	public static function getInstance() {
		$cls = static::class;
		if ( ! isset( self::$instance[ $cls ] ) ) {
			self::$instance[ $cls ] = new static();
		}

		return self::$instance[ $cls ];
	}

	public function stm_ew_get_all_registered_widgets() {
		$widgets = array(
			ListingSearchTabs::class,
			ImageCategories::class,
			InventorySearchFilter::class,
			InventorySortBy::class,
			InventoryViewType::class,
			InventorySearchResults::class,
			Title::class,
			TitleClassified::class,
			PriceClassified::class,
			Gallery::class,
			ListingDataClassified::class,
			Features::class,
			ListingDescription::class,
			UserDataSimple::class,
			DealerEmail::class,
			DealerPhoneNumber::class,
			OfferPriceButton::class,
			Similar::class,
			ContactFormSeven::class,
			PricingPlan::class,
			ListingsCompare::class,
			AddCarButton::class,
			CompareButton::class,
			ProfileButton::class,
			ListingsGridTabs::class,
			LoginRegister::class,
		);

		if ( stm_is_motors_theme() || is_mvl_pro() ) {
			$widgets = array_merge(
				$widgets,
				array(
					DealersList::class,
				)
			);
		}

		$widgets = array_merge(
			array(
				AddListing::class,
			),
			$widgets
		);

		return $widgets;
	}
}
