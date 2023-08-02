<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Tenant\Customer\CustomerController;
use App\Http\Controllers\Tenant\{PermissionController, UniversalController};
use App\Http\Controllers\Tenant\Accounting\AccountingDependencyController;
use App\Http\Controllers\Tenant\Accounting\ExpenseController;
use App\Http\Controllers\Tenant\Accounting\ExpenseRevenueController;
use App\Http\Controllers\Tenant\Accounting\PricingController;
use App\Http\Controllers\Tenant\Accounting\ProductionExtraController;
use App\Http\Controllers\Tenant\Accounting\RevenueController;
use App\Http\Controllers\Tenant\Accounting\RevenueItemController;
use App\Http\Controllers\Tenant\User\{UserCrudController, RoleManagementController};
use App\Http\Controllers\Tenant\SmartSchedule\{NoteController, OrderController, UpdateOrderController, ReceivingOrderController, ProductionOrderController, BlendOrderController, ShippingOrderController};
use App\Http\Controllers\Tenant\Facility\{FacilityCrudController, FacilityUserController, FacilityDisplayPictureController};
use App\Http\Controllers\Tenant\Inventory\{ProductController, KitController,LocationController, ShipperController, ShipToController,VendorController};
use App\Http\Controllers\Tenant\Profile\{ProfilePictureController, UserInfoController, ChangeEmailController, ChangePasswordController, UpdatePersonalInfoController, GoogleAuthController};

/* UserProfile Update and change Password/Email  Api's*/
Route::get('user-info', [UserInfoController::class, 'userInfo']);
Route::post('update-user', [UpdatePersonalInfoController::class, 'updateUser']);
Route::post('change-email', [ChangeEmailController::class, 'changeEmail']);
Route::post('verify-change-email', [ChangeEmailController::class, 'verifyChangedEmail']);
Route::post('change-password', [ChangePasswordController::class, 'changePassword']);

/* Google Authentication Api's*/
Route::group(['controller' => GoogleAuthController::class], function () {
	Route::get('google-qr-code', 'googleQrCode');
	Route::post('google-verify-code', 'googleVerifyCode');
	// Route::post('google-auth-mail', 'googleAuthMail');
	Route::post('google-auth-activator', 'googleAuthActivator');
	Route::post('google-auth-reset', 'googleAuthReset');
});

/* Profile Picture Api's*/
Route::group(['controller' => ProfilePictureController::class], function () {
	Route::get('get-profile-picture', 'getProfilePicture');
	Route::get('remove-profile-picture', 'removeProfilePicture');
	Route::post('update-profile-picture', 'uploadProfilePicture');
});

// Roles and Permissions
Route::get('all-roles-permissions', [PermissionController::class,'index']);

//Facility Crud APIs
Route::group(['prefix' => 'facility'], function () {
	Route::group(['controller' => FacilityCrudController::class], function () {
		Route::post('get', 'get');
		Route::any('list', 'list');
		Route::post('save', 'save');
		Route::post('update', 'update');
		Route::post('delete', 'deleteAndReassign');
		Route::get('admins', 'getAllFacilityAdmins');
	});
//Facility Users APIs
	Route::group(['controller' => FacilityUserController::class], function () {
		Route::post('get-user-facilities', 'getUserFacilities');
		Route::post('get-facility-users', 'getFacilityUsers');

		Route::post('add-facility-in-user', 'addFacilitiesInUsers');
		Route::post('add-users-in-facilities', 'addUsersInFacilities');
		
		Route::post('make-active-facility', 'makeActiveFacility');	

		Route::post('remove-facilities-from-profile', 'removeUserFacilities');
	});

	Route::post('update-dp', [FacilityDisplayPictureController::class, 'uploadDisplayPic']);
});

//User Crud APIs
Route::group(['prefix' => 'user', 'controller' => UserCrudController::class], function () {
	Route::any('list', 'list');
	Route::post('save', 'save');
	Route::post('update-profile-pic', 'updateProfilePic');
	Route::post('show', 'show');
	Route::post('update', 'update');
	Route::post('delete', 'delete');
	Route::post('multi-delete', 'multiDelete');
});

//Roles APIs
Route::group(['prefix' => 'role'], function () {
	Route::get('list', [RoleManagementController::class, 'list']);
});

/* Customer Api's*/
Route::group(['prefix' => 'customer', 'controller' => CustomerController::class], function () {
	Route::any('list', 'list');
	Route::post('save', 'save');
	Route::post('show', 'show');
	Route::post('update', 'update');
	Route::post('delete', 'delete');
	Route::post('customer-code', 'searchCode');
});


//Product APIs
Route::group(['prefix' => 'product', 'controller' => ProductController::class], function () {
	Route::get('dependencies', 'dependencies');
	Route::any('list', 'list');
	Route::post('get', 'get');
	Route::post('save', 'save');
	Route::post('update', 'update');
	Route::post('delete', 'delete');
});

//Kit APIs
Route::group(['prefix' => 'kit', 'controller' => KitController::class], function () {
	Route::get('dependencies', 'dependencies');
	Route::any('list', 'list');
	Route::post('get', 'get');
	Route::post('save', 'save');
	Route::post('update', 'update');
	Route::post('delete', 'delete');
	Route::post('add-alternative-product', 'addAlternativeProducts');
	Route::post('products-reorder', 'productsReorder');
	Route::post('product-remove', 'productRemove');
});


//Location APIs
Route::group(['prefix' => 'location', 'controller' => LocationController::class], function () {
	Route::any('list', 'list');
	Route::post('save', 'save');
	Route::post('get', 'get');
	Route::post('update', 'update');
	Route::post('delete', 'delete');
});

//Shipper APIs
Route::group(['prefix' => 'shipper', 'controller' => ShipperController::class], function () {
	Route::any('list', 'list');
	Route::post('save', 'save');
	Route::post('get', 'get');
	Route::post('update', 'update');
	Route::post('delete', 'delete');
});


//Vendor APIs
Route::group(['prefix' => 'vendor', 'controller' => VendorController::class], function () {
	Route::any('list', 'list');
	Route::post('save', 'save');
	Route::post('get', 'get');
	Route::post('update', 'update');
	Route::post('delete', 'delete');
});


//Ship To APIs
Route::group(['prefix' => 'ship-to', 'controller' => ShipToController::class], function () {
	Route::any('list', 'list');
	Route::post('save', 'save');
	Route::post('get', 'get');
	Route::post('update', 'update');
	Route::post('delete', 'delete');
	Route::post('multi-status-update', 'multiOption');
});


//Blend Order APIs
Route::group(['prefix' => 'blend-order', 'controller' => BlendOrderController::class], function () {
	Route::post('add-blend-order', 'addNewBlendOrder');
});

// Route::group(['prefix' => 'dependency', 'controller' => DependencyController::class], function () {
// 	Route::get('types', 'dependencyTypeList');
// 	Route::post('add', 'addDependency');
// 	Route::get('get/{slug}', 'specificDependency');
// 	Route::get('get/{slug}/{module}', 'specificDependency');
// 	Route::get('delete/{uuid}', 'deleteDependency');
// });

// Universal API to Fetch Record 
Route::group(['prefix' => 'universal','controller' => UniversalController::class],function()
{
		Route::get('customers','customers');
		Route::post('model-data','moduleData');
		Route::post('customer-products','customerProducts');
		Route::post('customer-kits','customerKits');
});

//Production Order APIs
Route::group(['prefix' => 'production-order', 'controller' => ProductionOrderController::class], function () {
	Route::post('add-production-order', 'addNewProductionOrder');
});

//Notes APIs
Route::group(['prefix' => 'notes', 'controller' => NoteController::class], function () {
	Route::post('add-new-note', 'addNewNote');
	Route::get('get-notes', 'getNotes');
	Route::post('delete-notes', 'deleteNotes');
});

//shipping Order APIs
Route::group(['prefix' => 'shipping-order', 'controller' => ShippingOrderController::class], function () {
	Route::post('add-shipping-order', 'addNewShippingOrder');
});

//Receiving Order APIs
Route::group(['prefix' => 'receiving-order', 'controller' => ReceivingOrderController::class], function () {
	Route::post('add-receiving-order', 'addNewReceivingOrder');
});

// Order APIs
Route::group(['prefix' => 'order', 'controller' => OrderController::class], function () {
	Route::any('list', 'list');
	Route::post('cancel-order', 'cancelOrder');
	Route::get('dependencies', 'dependencies');
	Route::post('order-detail', 'orderDetail');
	Route::post('connected-orders', 'connectedOrders');
	Route::post('possible-connected-orders', 'possibleConnectedOrders');
	Route::post('import-csv','csvFileUpload');
});

Route::group(['prefix' => 'order', 'controller' => UpdateOrderController::class], function () {
	Route::post('update', 'update');
});

//Production Extra APIs
Route::group(['prefix' => 'accounting', 'controller' => ProductionExtraController::class], function () {
	Route::post('add-new-production-extra', 'addNewProductionExtra');
	Route::post('get-single-production-extra', 'getSingleProductionExtra');
	Route::post('delete-production-extra', 'deleteProductionExtra');
	Route::post('list-production-extra', 'listingProductionExtra');
	Route::post('update-production-extra', 'updateProductionExtra');
});

//Pricing Extra APIs
Route::group(['prefix' => 'accounting', 'controller' => PricingController::class], function () {
	Route::post('add-new-pricing', 'addNewPricing');
	Route::post('get-single-pricing', 'getSinglePricing');
	Route::post('delete-pricing', 'deletePricing');
	Route::post('list-pricing', 'pricingList');
	Route::post('update-pricing', 'updatePricing');
});

//Revenue APIs
Route::group(['prefix' => 'accounting', 'controller' => RevenueController::class], function () {
	Route::post('add-new-revenue', 'addNewRevenue');
	Route::post('get-single-revenue', 'getSingleRevenue');
	Route::post('delete-revenue', 'deleteRevenue');
	Route::post('list-revenue', 'revenueList');
	Route::post('update-revenue', 'updateRevenue');
});

//Revenue Item APIs
Route::group(['prefix' => 'accounting', 'controller' => RevenueItemController::class], function () {
	Route::post('add-new-revenue-item', 'addNewRevenueItem');
	Route::post('delete-revenue-item', 'deleteRevenueItem');
	Route::get('list-revenue-item', 'listRevenueItem');
	Route::post('update-revenue-item', 'updateRevenueItem');
});

//Expense Revenue Item APIs
Route::group(['prefix' => 'accounting', 'controller' => ExpenseRevenueController::class], function () {
	Route::post('add-new-expense-revenue', 'addNewExpenseRevenue');
	Route::post('delete-expense-revenue', 'deleteExpenseRevenue');
	Route::post('list-expense-revenue', 'listExpenseRevenue');
	Route::post('update-expense-revenue', 'updateExpenseRevenue');
});


//Expense APIs
Route::group(['prefix' => 'accounting', 'controller' => ExpenseController::class], function () {
	Route::post('add-new-expense', 'addNewExpense');
	Route::post('get-single-expense', 'getSingleExpense');
	Route::post('delete-expense', 'deleteExpense');
	Route::post('list-expense', 'expenseList');
	Route::post('update-expense', 'updateExpense');
});

Route::group(['prefix' => 'accounting', 'controller' => AccountingDependencyController::class], function () {
	Route::post('dependencies', 'dependency');
});