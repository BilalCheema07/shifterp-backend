<?php

use App\Models\Tenant\{Category, ChargeType, Customer, Dependency, File, Kit, Unit, Shipper, ShipTo, Driver, Facility, PricingType, Product, RevenueItem, RevenueType, Shift, StackType};
use Illuminate\Support\Facades\File as LaravelFile;


if(!function_exists('getRandom')){
	function getRandom($model){
		$model_name = "App\Models\Tenant\\".ucfirst($model);
		if (class_exists($model_name)) {
			do {
				$rand_number = mt_rand(100000,999999);
				if($model == 'order'){
					$data = $model_name::where('po_number', $rand_number)->first();
				}
			} while ($data);
			return $rand_number;
		} else {
			return json_response(403, __("Tenant.universal_model_error"));
		}
	}

}
if (!function_exists('delete_file')) {
	function delete_file($id)
	{
		$file = File::find($id);
		LaravelFile::delete(public_path('file_upload/' . $file->path));
		$file->delete();
	}
}

//Kit Id
if (!function_exists('kitId')) {
	function kitId($uuid)
	{
		return Kit::findByUUIDOrFail($uuid)->id;
	}
}

//Shipper Id
if (!function_exists('ShipperId')) {
	function ShipperId($uuid)
	{
		return Shipper::findByUUIDOrFail($uuid)->id;
	}
}

//ShipTo Id
if (!function_exists('ShipTo')) {
	function ShipTo($uuid)
	{
		return ShipTo::findByUUIDOrFail($uuid)->id;
	}
}

//Unit ID
if (!function_exists('unitId')) {
	function unitId($uuid)
	{
		return Unit::findByUUIDOrFail($uuid)->id;
	}
}

//Customer Id
if (!function_exists('customerId')) {
	function customerId($uuid)
	{
		return Customer::findByUUIDOrFail($uuid)->id;
	}
}

//Facility Id
if (!function_exists('facilityId')) {
	function facilityId($uuid)
	{
		return Facility::findByUUIDOrFail($uuid)->id;
	}
}

//Category Id
if (!function_exists('categoryId')) {
	function categoryId($uuid)
	{
		return Category::findByUUIDOrFail($uuid)->id;
	}
}

//Product Id
if (!function_exists('productId')) {
	function productId($uuid)
	{
		return Product::findByUUIDOrFail($uuid)->id;
	}
}

//Pricing Type Id
if (!function_exists('pricingTypeId')) {
	function pricingTypeId($uuid)
	{
		return PricingType::findByUUIDOrFail($uuid)->id;
	}
}

//Driver Id
if (!function_exists('driverId')) {
	function driverId($uuid)
	{
		return Driver::findByUUIDOrFail($uuid)->id;
	}
}
//StackType Id
if (!function_exists('stackTypeId')) {
	function stackTypeId($uuid)
	{
		return StackType::findByUUIDOrFail($uuid)->id;
	}
}

//ChargeType Id
if (!function_exists('ChargeTypeId')) {
	function ChargeTypeId($uuid)
	{
		return ChargeType::findByUUIDOrFail($uuid)->id;
	}
}

//Revenue Type Id
if (!function_exists('revenueTypeId')) {
	function revenueTypeId($uuid)
	{
		return RevenueType::findByUUIDOrFail($uuid)->id;
	}
}

//Revenue Item Id
if (!function_exists('revenueItemId')) {
	function revenueItemId($uuid)
	{
		return RevenueItem::findByUUIDOrFail($uuid)->id;
	}
}

//Revenue Item Id
if (!function_exists('shiftId')) {
	function shiftId($uuid)
	{
		return Shift::findByUUIDOrFail($uuid)->id;
	}
}

//Date Format
if (!function_exists('dateFormat')) {
	function dateFormat($date)
	{
		return date('Y-m-d', strtotime($date));
	}
}

//Time Format
if (!function_exists('timeFormat')) {
	function timeFormat($time)
	{
		return date('H:i:s', strtotime($time));
	}
}

// //Driver Name
// if (!function_exists('driverName')) {
// 	function driverName($driver)
// 	{
// 		if ($driver == "") {
// 			return "";
// 		}
// 		$dependency = Dependency::where('name', 'LIKE', "%".$driver."%")->firstOrFail();
// 		return $dependency->name;
// 	}
// }

if (!function_exists('json_response')) {
	function json_response($resp_code = 200, $msg = '', $data = array())
	{
		$success = false;
		if ($resp_code == 200) {
			$success = true;
		}
		return response([
			'success'	=> $success,
			'message'	=> $msg,
			'data'		=> $data
		], $resp_code);
	}
}