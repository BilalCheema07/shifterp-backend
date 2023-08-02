<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\TenantService;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ProvisionAccountRequest;
use App\Models\{Tenant, ProvisionAccount, Sow, Subscription, SubscriptionDetail};
use App\Http\Resources\{ProvisionAccountResource, SowResource, SubscriptionResource, SubscriptionsResource};

class TenantController extends Controller
{
	protected $service;

	public function __construct(TenantService $service)
	{
		$this->service = $service;	
	}

	public function list(Request $request)
	{
		$login_user = auth()->user();
		if($login_user->role != 'super-admin'){
			return json_response(403, __('auth.auth_error'));
		}
		$provisionAccount = ProvisionAccount::with('user','billingContact','subDetails', 'sowUploads');
		$provisionAccount = $request->search ? $provisionAccount->searchString($request->search) : $provisionAccount;
		$provisionAccount = $request->subscription_ids ? $provisionAccount->searchType($request->subscription_ids) : $provisionAccount;
		$provisionAccount = $request->status ? $provisionAccount->searchStatus( $request->status) : $provisionAccount;
		$provisionAccount = $provisionAccount->orderBy('id', $request->order ?? 'asc');
		$provisionAccount = $provisionAccount->get();

		return json_response(200, __('auth.filter'), ProvisionAccountResource::collection($provisionAccount));
	}
	
	public function store(ProvisionAccountRequest $request)
	{
		$req_name = str_replace(' ', '_', $request->company_name);
		
		try {
			$new_tenant = new Tenant;
			$new_tenant->id = $req_name;
			if($new_tenant->save()) {
				$new_tenant->domains()->create([
					'domain'        		=> $req_name.'.'.$request->getHost(),
					'dba_name'          	=> $request->dba_name,
					'display_name'  		=> $request->company_name,
				]);
			}
		} catch (Exception $e){
			$tenant = Tenant::find($req_name);
			if($tenant) {
				$tenant->domains()->delete();
				$tenant->delete();
			}
			return json_response(500, __('auth.db_error'));
		}
		return $this->service->addProvisionDetails($request,$new_tenant,$req_name);
	}

	public function update(ProvisionAccountRequest $request)
	{
		if(auth()->user()->provision_account_id <= 0) {
			return json_response(401, __('auth.auth_error'));
		}
		$provision_account = ProvisionAccount::where('id', auth()->user()->provision_account_id)->first();

		return $this->service->updateProvisionDetails($request, $provision_account);
	}

	public function changeStatus(ProvisionAccountRequest $request)
	{
		$auth = auth()->user();
		if($auth->role=='super-admin' || $auth->provision_account_id > 0) {
			$provision_id = ProvisionAccount::findByUUIDOrFail($request->provision_account_id);
			$sub_details = SubscriptionDetail::where('provision_account_id', $provision_id->id)->first();
			if ($sub_details->status == $request->status) {
				return json_response(401, __('auth.status_error'));
			}
			if($request->status == 'pause'){
				if(!isset($request->pause_start_date)) {
					return json_response(403, __('auth.pause_missing'));
				}
				$sub_details->pause_start_date = date('Y-m-d', strtotime(@$request->pause_start_date));
				$sub_details->pause_subscription_months = @$request->pause_weeks * 7;
			} else{
				$sub_details->pause_start_date = NULL;
				$sub_details->pause_subscription_months = NULL;
			}
			$sub_details->status = $request->status;
			$sub_details->save();
	
			return json_response(200, __('auth.status_change'), new SubscriptionResource($sub_details));
		}
		return json_response(401,__('auth.auth_error'));
	}

	public function subscriptionHistory(ProvisionAccountRequest $request)
	{
		$provision_account = ProvisionAccount::with('subHistory', 'subDetails', 'sowUploads')->whereUUID($request->provision_account_id)->get();
		return json_response(200, __('auth.subscription_history'), ProvisionAccountResource::collection($provision_account));
	}

	public function subscription()
	{
		$sub = Subscription::all();
		return json_response(200, __('auth.subscription'), SubscriptionsResource::collection($sub));
	}

	public function sowUploads(ProvisionAccountRequest $request)
	{
		$provision_account = ProvisionAccount::findByUUID($request->provision_account_id);
		
		if ($request->hasFile('sow')){
			$ext = strtolower($request->sow->extension());
			$name = explode('.', $request->sow->getClientOriginalName())[0];
			$path = strtolower(Str::random(16)) . '.' . $request->sow->extension();  
			$request->sow->move(public_path('file_upload'), $path);
		}

		$sow = Sow::create([
			'provision_account_id' => $provision_account->id,
			'name' => $name,
			'path' => $path,
			'extension' => $ext,
			'billing_date' => Carbon::now()
		]);

		return json_response(200, __('auth.sow_success'), new SowResource($sow));
	}
}
