<?php
namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes(true);

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/**
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */
// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');

//Authentication public
$routes->add('login', 'AuthenticationController::login', ['as' => 'login']);
$routes->get('logout', 'AuthenticationController::logout', ['as' => 'logout']);
$routes->add('forgot_password', 'AuthenticationController::forgot_password', ['as' => 'forgot_password']);
$routes->add('change_password', 'AuthenticationController::change_password', ['as' => 'change_password']);
$routes->add('reset_password', 'AuthenticationController::reset_password', ['as' => 'reset_password']);

$routes->add('dashboard', 'Dashboard::index', ['as' => 'dashboard', 'filter' => 'appAuth']);

$routes->group('authentication', ['filter' => 'appAuth'], function($routes) {
    $routes->get('user-companies-header/(:any)', 'AuthenticationController::getUserMainCompaniesHeader/$1', ['as' => 'user_main_companies_header']);
    $routes->add('change-user-company', 'AuthenticationController::changeUserMainCompany', ['as' => 'user_main_company_change']);
});

$routes->group('user', ['filter' => 'appAuth'], function($routes) {
    $routes->add('create', 'User::create', ['as' => 'user_create']);
    $routes->add('detail/(:num)', 'User::detail/$1', ['as' => 'user_detail']);
    $routes->add('edit/(:num)', 'User::edit/$1', ['as' => 'user_edit']);
    $routes->add('list', 'User::list', ['as' => 'user_list']);
});

$routes->group('order', ['filter' => 'appAuth'], function($routes) {
	$routes->add('list', 'Order::list', ['as' => 'order_list']);
	$routes->add('detail/(:any)', 'Order::detail/$1', ['as' => 'order_detail']);
    $routes->add('validate/(:any)', 'Order::validateOrder/$1', ['as' => 'order_validate']);
    $routes->add('cancel/(:any)', 'Order::cancel/$1', ['as' => 'order_cancel']);
    $routes->add('delete/(:any)', 'Order::delete/$1', ['as' => 'order_delete']);
    $routes->add('create', 'Order::create', ['as' => 'order_create']);
    $routes->add('edit/(:any)', 'Order::edit/$1', ['as' => 'order_edit']);
    $routes->add('update/(:any)', 'Order::update/$1', ['as' => 'order_update']);
    $routes->add('pdf/(:any)', 'Order::pdf/$1', ['as' => 'order_pdf']);
});

$routes->group('invoice', ['filter' => 'appAuth'], function($routes) {
    $routes->add('create', 'Invoice::create', ['as' => 'invoice_create']);
    $routes->add('show/(:any)', 'Invoice::show/$1', ['as' => 'invoice_show']);
    $routes->add('pdf/(:any)', 'Invoice::pdf/$1', ['as' => 'invoice_pdf']);
    $routes->add('', 'Invoice::index', ['as' => 'invoice']);
});

$routes->group('payment_method', ['filter' => 'appAuth'], function($routes) {
    $routes->add('/', 'PaymentMethod::index', ['as' => 'payment_method']);
    $routes->add('test', 'PaymentMethod::test', []);
    $routes->add('add', 'PaymentMethod::add', []);
    $routes->add('create', 'PaymentMethod::create', []);
    $routes->add('setupIntent', 'PaymentMethod::setupIntent', ['as' => 'setup_intent']);
    $routes->add('setActive', 'PaymentMethod::setActive', ['as' => 'set_active']);
    $routes->add('delete', 'PaymentMethod::delete', ['as' => 'delete']);
});


$routes->group('groups-permissions', ['filter' => 'appAuth'], function($routes) {
    $routes->add('list', 'PermissionController::listGroupsPermissions', ['as' => 'groups_permissions_list']);
    $routes->add('update', 'PermissionController::updateGroupsPermissions', ['as' => 'groups_permissions_update']);
});

$routes->group('company', ['filter' => 'appAuth'], function($routes) {
    $routes->add('create', 'CompanyController::create', ['as' => 'company_create']);
    $routes->add('detail/(:num)', 'CompanyController::detail/$1', ['as' => 'company_detail']);
    $routes->add('edit/(:num)', 'CompanyController::edit/$1', ['as' => 'company_edit']);
    $routes->add('list', 'CompanyController::list', ['as' => 'company_list']);
    $routes->add('children-companies', 'CompanyController::getChildrenCompanies', ['as' => 'children_companies']);
});

$routes->group('visualslib', ['filter' => 'appAuth'], function($routes) {
    $routes->add('manage', 'Visualslib::manage', ['as' => 'visual_manage']);
    $routes->add('categories', 'Visualslib::categories', ['as' => 'visual_categories']);
    $routes->add('features', 'Visualslib::features', ['as' => 'visual_features']);
});

$routes->group('administration', ['filter' => 'appAuth'], function($routes) {
    $routes->add('traductions', 'Administration::traductions', ['as' => 'traductions_list']);
    $routes->add('traductions_gen', 'Administration::traductions_gen', ['as' => 'traductions_gen']);
    $routes->add('pricing', 'Price::list',['as' => 'price_list']);    
});

/*
$routes->group('campaign', ['filter' => 'appAuth'], function($routes) {
    $routes->add('list', 'CampaignController::list', ['as' => 'campaign_list']);
   // $routes->add('create', 'CampaignController::createCampaign', ['as' => 'campaign_create']);
    $routes->add('edit/(:num)', 'CampaignController::editCampaign/$1', ['as' => 'campaign_edit']);
    $routes->add('save', 'CampaignController::saveCampaign', ['as' => 'campaign_save']);
    $routes->add('getChannelType', 'CampaignController::getChannelType', ['as' => 'campaign_get_channel_type']);
    $routes->add('getCampaignData', 'CampaignController::getCampaignData', ['as' => 'campaign_get_campaign_data']);
});
*/

$routes->group('price', ['filter' => 'appAuth'], function($routes) {
    $routes->add('list', 'Price::list', ['as' => 'pricing']);
    $routes->add('create_price', 'Price::create', ['as' => 'create_price']);
    //$routes->add('edit/(:num)', 'User::edit/$1', ['as' => 'user_edit']);
});

$routes->group('blacklistfiles', ['filter' => 'appAuth'], function($routes) {
    $routes->add('list', 'BlacklistFile::list', ['as' => 'blacklistfile_list']);
    $routes->add('detail/(:any)', 'BlacklistFile::detail/$1', ['as' => 'blacklistfile_detail']);
    $routes->add('send/(:any)', 'BlacklistFile::send/$1', ['as' => 'blacklistfile_send']);
    $routes->add('upload', 'BlacklistFile::upload', ['as' => 'blacklistfile_upload']);
    $routes->add('delete', 'BlacklistFile::delete', ['as' => 'blacklistfile_delete']);
});

$routes->group('communicationplan', ['filter' => 'appAuth'], function($routes) {
    $routes->add('list', 'CommunicationPlan::list', ['as' => 'communicationplan_list']);
    $routes->add('detail/(:any)', 'CommunicationPlan::detail/$1', ['as' => 'communicationplan_detail']);
});

// route for compaign detail
$routes->group('campaign', ['filter' => 'appAuth'], function($routes) {
    $routes->add('', 'CampaignController::createCampaign', ['as' => 'create_campaign']);
    $routes->add('edit/(:num)', 'CampaignController::editCampaign/$1', ['as' => 'edit_campaign']);
    $routes->add('channel/(:num)', 'CampaignController::channel', ['as' => 'create_channel']);
    $routes->add('channel/edit/(:num)', 'CampaignController::editChannel/$1', ['as' => 'edit_channel']);
    $routes->add('segmentation/(:num)', 'CampaignController::createSegmentation/$1',  ['as' => 'create_segmentation']);
    $routes->add('segmentation/edit/(:num)', 'CampaignController::editSegmentation/$1',  ['as' => 'edit_segmentation']);
    $routes->add('segmentation/delete/(:num)', 'CampaignController::deleteSegmentationItem/$1',  ['as' => 'delete_segmentation']);
    $routes->add('segmentation/count/(:num)', 'CampaignController::startCounting/$1',  ['as' => 'count_segmentation']);
    $routes->add('content/(:num)', 'CampaignController::content/$1',  ['as' => 'create_content']);
    $routes->add('content/edit/(:num)', 'CampaignController::editContent/$1',  ['as' => 'edit_content']);
    $routes->add('content/delete/(:num)', 'CampaignController::deleteContent/$1',  ['as' => 'delete_content']);
    $routes->add('content/load', 'CampaignController::loadContentForm',  ['as' => 'load_content_form']);
    $routes->add('content/preview/(:num)', 'CampaignController::previewContent/$1',  ['as' => 'preview_content']);
    $routes->add('planning/(:num)', 'CampaignController::planning/$1',  ['as' => 'create_planning']);
    $routes->add('planning/edit/(:num)', 'CampaignController::editPlanning/$1',  ['as' => 'edit_planning']);
    $routes->add('validation', 'CampaignController::createValidation',  ['as' => 'create_validation']);
    $routes->add('list', 'CampaignController::list',  ['as' => 'campaign_list']);
    $routes->add('delete/(:any)', 'CampaignController::delete/$1', ['as' => 'campaign_delete']);
    $routes->add('detect/(:any)', 'CampaignController::detect/$1', ['as' => 'campaign_detect']);
    $routes->add('validation/reload/(:num)', 'CampaignController::validationReload/$1', ['as' => 'validation_reload']);
    $routes->add('validation/(:num)', 'CampaignController::validateCampaign/$1', ['as' => 'validate_campaign']);
    $routes->add('programmation/(:num)', 'CampaignController::programmation/$1', ['as' => 'create_programmation']);
    $routes->add('segmentationItem/(:any)/(:any)', 'CampaignController::createSegmentationItem/$1/$2', ['as' => 'campaign_segmentation']);
    $routes->add('segmentation/submit/(:any)', 'CampaignController::submitSegmentation/$1', ['as' => 'submit_segmentation']);
});

// route for contact us
$routes->group('contact-us', ['filter' => 'appAuth'], function($routes) {
    $routes->add('(:num)', 'ContactUsController::contactUs/$1', ['as' => 'contact_us']);
    $routes->add('summarize/(:num)', 'ContactUsController::summarize/$1', ['as' => 'campaign_summarize']);
});

/**
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need to it be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
