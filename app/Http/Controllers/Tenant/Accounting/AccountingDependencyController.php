<?php

namespace App\Http\Controllers\Tenant\Accounting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Accounting\AccountingDependencyRequest;
use App\Http\Resources\Tenant\Unit\UtypeCollection;
use App\Models\Tenant\{Category, Customer, Utype, ChargeType, ExpenseType, Facility, PricingType, Product, RevenueType, Shift};

class AccountingDependencyController extends Controller
{
    public function dependency(AccountingDependencyRequest $request)
    {
        $data = [];
        switch ($request->name) {
			case "production_extra":
                $data["units"] = new UtypeCollection(Utype::where('name', 'production_extra')->with('units')->get());
                return json_response(200, __("Tenant.dependency_fetch"), $data);        
			case "pricing":
                $data["customer"] = Customer::where('status', 1)->select("uuid","code","name")->get();
                $data["units"] = new UtypeCollection(Utype::where('name', 'pricing')->with('units')->get());
                $data["category"] = Category::select('uuid', 'name')->get();
                $data["products"] = Product::whereHas('category', function($query) use ($request){
                    $query->where('uuid', @$request->category_id);
                })->WhereHas('customer',  function($query) use ($request){
                    $query->where('uuid', @$request->customer_id);
                })->select("uuid", "name")->get();

                $data["charge_types"] = ChargeType::where('type', 'pricing')->select("uuid","name")->get();
                $data["pricing_types"] = PricingType::select("uuid","name")->get();
                
                return json_response(200, __("Tenant.dependency_fetch"), $data);

			case "revenue":
                $data['shift'] = Shift::select('uuid', 'name')->get();
                $data['revenue_type'] = RevenueType::select('uuid', 'name')->get();
                
                return json_response(200, __("Tenant.dependency_fetch"), $data);

			case "expense":
                $data['expense_type'] = ExpenseType::where('parent_id', 0)->with('children')->get();
                return json_response(200, __("Tenant.dependency_fetch"), $data);
            case "expense_revenue":
                $data['shift'] = Shift::select('uuid', 'name')->get();
                $data['revenue_type'] = RevenueType::select('uuid', 'name')->get();
                $data['facilities'] = Facility::select('uuid', 'name')->get();

                $data['customer'] = Customer::whereHas('facilities', function ($query) use ($request) {
                    $query->where('uuid', @$request->facility_id);
                })->select("uuid", "name")->get();

                return json_response(200, __("Tenant.dependency_fetch"), $data);
		}

		return json_response(200, __("Tenant.dependency_fetch"), $data);
    }
}
