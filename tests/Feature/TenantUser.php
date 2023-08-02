<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class TenantUserTest extends TestCase
{
	public function testUserList()
	{
		$response = $this->withHeader('Authorization', 'Bearer ' . $GLOBALS['token'])
			->json('GET', '/tenant/api/user/list');
		
		$response->assertStatus(200);
	}
	
	public function testSearchUsers()
	{
		$response = $this->withHeader('Authorization', 'Bearer ' . $GLOBALS['token'])
			->json('POST', 'tenant/api/user/search');
		
		$response->assertStatus(200);
	}
	public function testSaveUser()
	{

		$username = strtolower(Str::random(8));
		$new_mail = $username.'@gmail.com';
		Storage::fake('public');
		$permissions =['1c239614876e4b599d76792fde6324c9','c2fdf14d5780403ba2f3b3217c8cafb9','509d3288f5c546a9ae4daa3fa19dcb81','2bfdee8e798141869b30f6585c6349b9'];
		$facilities =['7062e862092544fdbaffaf0466545a3d','24426074104a4673ae2a2d097c6c971c'];
		$data = [
			'fname' => 'Awais', 
			'lname' => 'sheikh', 
			'email' => $new_mail, 
			'phone' => '03209500003', 
			'username' => $username, 
			'address' => 'Al-najaf Colony Street no. 4 , Faisalabad', 
			'city' => 'Faisalabad', 
			'state' => 'Punjab',
			'zip_code' => '38000', 
			'status' => '1',
			'role_id' => '76a2997de5934a49bc7bdc935cc96aa9',
			'facilities' => $facilities,
			'permission_ids' => $permissions,
			'image' => UploadedFile::fake()->image('avatar.png',100,100,true),
		];

		$response = $this->withHeader('Authorization', 'Bearer ' . $GLOBALS['token'])->postjson('/tenant/api/user/save', 
		$data);
		global $add_user_uuid;
		global $add_user_email;
		global $add_user_username;
		$add_user_uuid = $response['data']['user']['uuid'];
		$add_user_username = $response['data']['user']['username'];
		$add_user_email = $response['data']['user']['email'];
		$response->assertStatus(200);
	}

	public function testUpdateUser(){
		
		Storage::fake('public');
		$permissions =['1c239614876e4b599d76792fde6324c9'];
		$facilities =['7062e862092544fdbaffaf0466545a3d'];
		$data = [
			'fname' => 'Awais', 
			'lname' => 'sheikh', 
			'email' => $GLOBALS['add_user_email'], 
			'phone' => '03209500003', 
			'username' => $GLOBALS['add_user_username'], 
			'address' => 'Al-najaf Colony Street no. 4 , Faisalabad', 
			'city' => 'Faisalabad', 
			'state' => 'Punjab',
			'zip_code' => '38000', 
			'status' => '1',
			'role_id' => '76a2997de5934a49bc7bdc935cc96aa9',
			'facilities' => $facilities,
			'permission_ids' => $permissions,
			'image' => UploadedFile::fake()->image('avatar.png',100,100,true),
		];

		$response = $this->withHeader('Authorization', 'Bearer ' . $GLOBALS['token'])->postjson('/tenant/api/user/update/'.$GLOBALS['add_user_uuid'], 
		$data);
		$response->assertStatus(200);
	}


	// public function testShowSingleUser()
	// {
	// 	$response = $this->withHeader('Authorization', 'Bearer ' . $GLOBALS['token'])
	// 		->json('GET', 'tenant/api/user/show/472f7b4efd8645c9972c48d7218cfa53');
		
	// 	$response->assertStatus(200);
	// }
	
	// public function testDeleteSingleUser()
	// {
	// 	$response = $this->withHeader('Authorization', 'Bearer ' . $GLOBALS['token'])
	// 		->json('GET', 'tenant/api/user/delete/472f7b4efd8645c9972c48d7218cfa53');

	// 	$response->assertStatus(200);
	// }
	
	// public function testMultiDeleteUser()
	// {
	// 	$response = $this->withHeader('Authorization', 'Bearer ' . $GLOBALS['token'])
	// 		->json('POST', 'tenant/api/user/multi-delete',[
	// 			'ids' => ['060dd8382e8f43b4b7067f34a0fac866']
	// 	]);
		
	// 	$response->assertStatus(200);
	// }
}