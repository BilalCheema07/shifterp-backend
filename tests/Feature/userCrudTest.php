<?php

namespace Tests\Feature;

use Tests\TestCase;

class userCrudTest extends TestCase
{
    public function testUserList()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $GLOBALS['token'])->json('POST', 'tenant/api/user/list');

        $response->assertStatus(200);
    }

    public function testUpdateUser()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $GLOBALS['token'])->postjson('/tenant/api/update-user', 
        [
            'fname'             => 'awais',
            'lname'             => 'sheikh',
            'hire_date'         => '20/4/12',
            'release_date'      => '20/4/12',
            'job_title'         => 'Admin',
            'address'           => 'Al-najaf colony',
            'city'              => 'faisalabad',
            'state'             => 'punjab',
            'zip'               => '38000',
            'department'        => 'Administrator',
            'supervisor_name'   => 'admin',
            'birth_date'        => '20/4/12',
            'shift'             => 'full',
        ]);
        
        $response->assertStatus(200);
    }
}
