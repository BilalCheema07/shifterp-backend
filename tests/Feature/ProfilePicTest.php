<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProfilePicTest extends TestCase
{
	private $base_url = '/tenant/api/';

	public function testGetProfilePic()
	{
		$response = $this->withHeader('Authorization', 'Bearer ' . $GLOBALS['token'])
			->json('GET', $this->base_url . 'get-profile-picture');
			
		$response->assertStatus(200);
	}

	public function testRemoveProfilePic()
	{
		$response = $this->withHeader('Authorization', 'Bearer ' . $GLOBALS['token'])
			->json('GET', $this->base_url . 'remove-profile-picture');
			
		$response->assertStatus(200);
	}

	public function testUpdateProfilePic()
	{
		Storage::fake('public');

		$response = $this->withHeader('Authorization', 'Bearer ' . $GLOBALS['token'])
			->json('POST', $this->base_url . 'update-profile-picture', [
				'image' => UploadedFile::fake()->image('avatar.png', 100, 100, true)
			]);
			

		$response->assertStatus(200);
	}
}