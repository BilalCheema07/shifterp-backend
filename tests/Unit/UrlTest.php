<?php

namespace Tests\Unit;

// use PHPUnit\Framework\TestCase;
use Tests\TestCase;

class UrlTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_api_login()
    {
        $response = $this->get('/api/login');
        $response->assertStatus($response->status());
    }

    public function test_verify_sms()
    {
        $response = $this->get('/api/verify_sms');
        $response->assertStatus($response->status());
    }

    public function test_resend_sms()
    {
        $response = $this->get('/api/resend_sms');
        $response->assertStatus($response->status());
    }

    public function test_logout()
    {
        $response = $this->get('/api/logout');
        $response->assertStatus($response->status());
    }

    public function test_refresh()
    {
        $response = $this->get('/api/refresh');
        $response->assertStatus($response->status());
    }
}
