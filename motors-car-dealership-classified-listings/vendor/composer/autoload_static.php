<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit5db859e1dab632b715b93d33219eb1cf
{
    public static $prefixLengthsPsr4 = array (
        'M' => 
        array (
            'Motors_Elementor_Widgets_Free\\' => 30,
            'MotorsVehiclesListing\\StarterTheme\\' => 35,
            'MotorsVehiclesListing\\' => 22,
            'MotorsNuxy\\' => 11,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Motors_Elementor_Widgets_Free\\' => 
        array (
            0 => __DIR__ . '/../..' . '/elementor/inc',
        ),
        'MotorsVehiclesListing\\StarterTheme\\' => 
        array (
            0 => __DIR__ . '/../..' . '/includes/starter-theme',
        ),
        'MotorsVehiclesListing\\' => 
        array (
            0 => __DIR__ . '/../..' . '/includes/class',
        ),
        'MotorsNuxy\\' => 
        array (
            0 => __DIR__ . '/../..' . '/includes/nuxy',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'MotorsNuxy\\MotorsNuxyHelpers' => __DIR__ . '/../..' . '/includes/nuxy/MotorsNuxyHelpers.php',
        'MotorsVehiclesListing\\Api\\ApiPosts' => __DIR__ . '/../..' . '/includes/class/Api/ApiPosts.php',
        'MotorsVehiclesListing\\Core\\CoreApi' => __DIR__ . '/../..' . '/includes/class/Core/CoreApi.php',
        'MotorsVehiclesListing\\Core\\CoreController' => __DIR__ . '/../..' . '/includes/class/Core/CoreController.php',
        'MotorsVehiclesListing\\Core\\CoreModel' => __DIR__ . '/../..' . '/includes/class/Core/CoreModel.php',
        'MotorsVehiclesListing\\Elementor\\Nuxy\\AddListingManager' => __DIR__ . '/../..' . '/includes/class/Elementor/Nuxy/AddListingManager.php',
        'MotorsVehiclesListing\\Elementor\\Nuxy\\FeaturesSettings' => __DIR__ . '/../..' . '/includes/class/Elementor/Nuxy/FeaturesSettings.php',
        'MotorsVehiclesListing\\Features\\Elementor\\Nuxy\\TemplateManager' => __DIR__ . '/../..' . '/includes/class/Features/Elementor/Nuxy/TemplateManager.php',
        'MotorsVehiclesListing\\Features\\FriendlyUrl' => __DIR__ . '/../..' . '/includes/class/Features/FriendlyUrl.php',
        'MotorsVehiclesListing\\Features\\MultiplePlan' => __DIR__ . '/../..' . '/includes/class/Features/MultiplePlan.php',
        'MotorsVehiclesListing\\Helper\\AuthorizationHelper' => __DIR__ . '/../..' . '/includes/class/Helper/AuthorizationHelper.php',
        'MotorsVehiclesListing\\Helper\\CleanHelper' => __DIR__ . '/../..' . '/includes/class/Helper/CleanHelper.php',
        'MotorsVehiclesListing\\Helper\\FilterHelper' => __DIR__ . '/../..' . '/includes/class/Helper/FilterHelper.php',
        'MotorsVehiclesListing\\Helper\\ListingStats' => __DIR__ . '/../..' . '/includes/class/Helper/ListingStats.php',
        'MotorsVehiclesListing\\Helper\\OptionsHelper' => __DIR__ . '/../..' . '/includes/class/Helper/OptionsHelper.php',
        'MotorsVehiclesListing\\Loader' => __DIR__ . '/../..' . '/includes/class/Loader.php',
        'MotorsVehiclesListing\\MenuPages\\AddCarFormSettings' => __DIR__ . '/../..' . '/includes/class/MenuPages/AddCarFormSettings.php',
        'MotorsVehiclesListing\\MenuPages\\FilterSettings' => __DIR__ . '/../..' . '/includes/class/MenuPages/FilterSettings.php',
        'MotorsVehiclesListing\\MenuPages\\ListingDetailsSettings' => __DIR__ . '/../..' . '/includes/class/MenuPages/ListingDetailsSettings.php',
        'MotorsVehiclesListing\\MenuPages\\MenuBase' => __DIR__ . '/../..' . '/includes/class/MenuPages/MenuBase.php',
        'MotorsVehiclesListing\\MenuPages\\MenuBuilder' => __DIR__ . '/../..' . '/includes/class/MenuPages/MenuBuilder.php',
        'MotorsVehiclesListing\\MenuPages\\SearchResultsSettings' => __DIR__ . '/../..' . '/includes/class/MenuPages/SearchResultsSettings.php',
        'MotorsVehiclesListing\\MenuPages\\SingleListingTemplateSettings' => __DIR__ . '/../..' . '/includes/class/MenuPages/SingleListingTemplateSettings.php',
        'MotorsVehiclesListing\\Plugin\\MVL_Const' => __DIR__ . '/../..' . '/includes/class/Plugin/MVL_Const.php',
        'MotorsVehiclesListing\\Plugin\\PluginOptions' => __DIR__ . '/../..' . '/includes/class/Plugin/PluginOptions.php',
        'MotorsVehiclesListing\\Plugin\\Settings' => __DIR__ . '/../..' . '/includes/class/Plugin/Settings.php',
        'MotorsVehiclesListing\\Post\\Model\\PostMetaModel' => __DIR__ . '/../..' . '/includes/class/Post/Model/PostMetaModel.php',
        'MotorsVehiclesListing\\Post\\Model\\PostModel' => __DIR__ . '/../..' . '/includes/class/Post/Model/PostModel.php',
        'MotorsVehiclesListing\\Post\\PostController' => __DIR__ . '/../..' . '/includes/class/Post/PostController.php',
        'MotorsVehiclesListing\\SellerNoteMetaBoxes' => __DIR__ . '/../..' . '/includes/class/SellerNoteMetaBoxes.php',
        'MotorsVehiclesListing\\StarterTheme\\Helpers\\Themes' => __DIR__ . '/../..' . '/includes/starter-theme/Helpers/Themes.php',
        'MotorsVehiclesListing\\Terms\\Model\\TermsModel' => __DIR__ . '/../..' . '/includes/class/Terms/Model/TermsModel.php',
        'MotorsVehiclesListing\\Terms\\TermsController' => __DIR__ . '/../..' . '/includes/class/Terms/TermsController.php',
        'MotorsVehiclesListing\\User\\Model\\UserListingsModel' => __DIR__ . '/../..' . '/includes/class/User/Model/UserListingsModel.php',
        'MotorsVehiclesListing\\User\\Model\\UserMetaModel' => __DIR__ . '/../..' . '/includes/class/User/Model/UserMetaModel.php',
        'MotorsVehiclesListing\\User\\Model\\UserModel' => __DIR__ . '/../..' . '/includes/class/User/Model/UserModel.php',
        'MotorsVehiclesListing\\User\\Model\\UserReviewsModel' => __DIR__ . '/../..' . '/includes/class/User/Model/UserReviewsModel.php',
        'MotorsVehiclesListing\\User\\UserController' => __DIR__ . '/../..' . '/includes/class/User/UserController.php',
        'MotorsVehiclesListing\\User\\UserListingsController' => __DIR__ . '/../..' . '/includes/class/User/UserListingsController.php',
        'MotorsVehiclesListing\\User\\UserMetaController' => __DIR__ . '/../..' . '/includes/class/User/UserMetaController.php',
        'MotorsVehiclesListing\\User\\UserReviewsController' => __DIR__ . '/../..' . '/includes/class/User/UserReviewsController.php',
        'Motors_Elementor_Widgets_Free\\Helpers\\Helper' => __DIR__ . '/../..' . '/elementor/inc/Helpers/Helper.php',
        'Motors_Elementor_Widgets_Free\\Helpers\\RegisterActions' => __DIR__ . '/../..' . '/elementor/inc/Helpers/RegisterActions.php',
        'Motors_Elementor_Widgets_Free\\Widgets\\AddListing' => __DIR__ . '/../..' . '/elementor/inc/Widgets/AddListing.php',
        'Motors_Elementor_Widgets_Free\\Widgets\\ContactFormSeven' => __DIR__ . '/../..' . '/elementor/inc/Widgets/ContactFormSeven.php',
        'Motors_Elementor_Widgets_Free\\Widgets\\DealersList' => __DIR__ . '/../..' . '/elementor/inc/Widgets/DealersList.php',
        'Motors_Elementor_Widgets_Free\\Widgets\\HeaderFooter\\AddCarButton' => __DIR__ . '/../..' . '/elementor/inc/Widgets/HeaderFooter/AddCarButton.php',
        'Motors_Elementor_Widgets_Free\\Widgets\\HeaderFooter\\CompareButton' => __DIR__ . '/../..' . '/elementor/inc/Widgets/HeaderFooter/CompareButton.php',
        'Motors_Elementor_Widgets_Free\\Widgets\\HeaderFooter\\ProfileButton' => __DIR__ . '/../..' . '/elementor/inc/Widgets/HeaderFooter/ProfileButton.php',
        'Motors_Elementor_Widgets_Free\\Widgets\\ImageCategories' => __DIR__ . '/../..' . '/elementor/inc/Widgets/ImageCategories.php',
        'Motors_Elementor_Widgets_Free\\Widgets\\InventorySearchFilter' => __DIR__ . '/../..' . '/elementor/inc/Widgets/InventorySearchFilter.php',
        'Motors_Elementor_Widgets_Free\\Widgets\\InventorySearchResults' => __DIR__ . '/../..' . '/elementor/inc/Widgets/InventorySearchResults.php',
        'Motors_Elementor_Widgets_Free\\Widgets\\InventorySortBy' => __DIR__ . '/../..' . '/elementor/inc/Widgets/InventorySortBy.php',
        'Motors_Elementor_Widgets_Free\\Widgets\\InventoryViewType' => __DIR__ . '/../..' . '/elementor/inc/Widgets/InventoryViewType.php',
        'Motors_Elementor_Widgets_Free\\Widgets\\ListingSearchTabs' => __DIR__ . '/../..' . '/elementor/inc/Widgets/ListingSearchTabs.php',
        'Motors_Elementor_Widgets_Free\\Widgets\\ListingsCompare' => __DIR__ . '/../..' . '/elementor/inc/Widgets/ListingsCompare.php',
        'Motors_Elementor_Widgets_Free\\Widgets\\ListingsGridTabs' => __DIR__ . '/../..' . '/elementor/inc/Widgets/ListingsGridTabs.php',
        'Motors_Elementor_Widgets_Free\\Widgets\\LoginRegister' => __DIR__ . '/../..' . '/elementor/inc/Widgets/LoginRegister.php',
        'Motors_Elementor_Widgets_Free\\Widgets\\PricingPlan' => __DIR__ . '/../..' . '/elementor/inc/Widgets/PricingPlan.php',
        'Motors_Elementor_Widgets_Free\\Widgets\\SingleListing\\Classified\\ListingData' => __DIR__ . '/../..' . '/elementor/inc/Widgets/SingleListing/Classified/ListingData.php',
        'Motors_Elementor_Widgets_Free\\Widgets\\SingleListing\\Classified\\Price' => __DIR__ . '/../..' . '/elementor/inc/Widgets/SingleListing/Classified/Price.php',
        'Motors_Elementor_Widgets_Free\\Widgets\\SingleListing\\Classified\\Title' => __DIR__ . '/../..' . '/elementor/inc/Widgets/SingleListing/Classified/Title.php',
        'Motors_Elementor_Widgets_Free\\Widgets\\SingleListing\\Classified\\UserDataSimple' => __DIR__ . '/../..' . '/elementor/inc/Widgets/SingleListing/Classified/UserDataSimple.php',
        'Motors_Elementor_Widgets_Free\\Widgets\\SingleListing\\DealerEmail' => __DIR__ . '/../..' . '/elementor/inc/Widgets/SingleListing/DealerEmail.php',
        'Motors_Elementor_Widgets_Free\\Widgets\\SingleListing\\DealerPhoneNumber' => __DIR__ . '/../..' . '/elementor/inc/Widgets/SingleListing/DealerPhoneNumber.php',
        'Motors_Elementor_Widgets_Free\\Widgets\\SingleListing\\Features' => __DIR__ . '/../..' . '/elementor/inc/Widgets/SingleListing/Features.php',
        'Motors_Elementor_Widgets_Free\\Widgets\\SingleListing\\Gallery' => __DIR__ . '/../..' . '/elementor/inc/Widgets/SingleListing/Gallery.php',
        'Motors_Elementor_Widgets_Free\\Widgets\\SingleListing\\ListingDescription' => __DIR__ . '/../..' . '/elementor/inc/Widgets/SingleListing/ListingDescription.php',
        'Motors_Elementor_Widgets_Free\\Widgets\\SingleListing\\OfferPriceButton' => __DIR__ . '/../..' . '/elementor/inc/Widgets/SingleListing/OfferPriceButton.php',
        'Motors_Elementor_Widgets_Free\\Widgets\\SingleListing\\Similar' => __DIR__ . '/../..' . '/elementor/inc/Widgets/SingleListing/Similar.php',
        'Motors_Elementor_Widgets_Free\\Widgets\\SingleListing\\Title' => __DIR__ . '/../..' . '/elementor/inc/Widgets/SingleListing/Title.php',
        'Motors_Elementor_Widgets_Free\\Widgets\\WidgetBase' => __DIR__ . '/../..' . '/elementor/inc/Widgets/WidgetBase.php',
        'Motors_Elementor_Widgets_Free\\Widgets\\WidgetManager\\MotorsWidgetsManagerFree' => __DIR__ . '/../..' . '/elementor/inc/Widgets/WidgetManager/MotorsWidgetsManagerFree.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit5db859e1dab632b715b93d33219eb1cf::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit5db859e1dab632b715b93d33219eb1cf::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit5db859e1dab632b715b93d33219eb1cf::$classMap;

        }, null, ClassLoader::class);
    }
}