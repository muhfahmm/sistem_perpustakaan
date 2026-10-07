<?php

namespace Tests\Feature;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;
use Mockery;
use Tests\TestCase;

class AdminAuthenticationTest extends TestCase
{
    public function test_login_page_is_not_cached(): void
    {
        $response = $this->get(route('admin.login'));

        $response->assertOk()
            ->assertHeader('Pragma', 'no-cache')
            ->assertHeader('Expires', '0');

        $this->assertStringContainsString('no-store', $response->headers->get('Cache-Control'));
        $this->assertStringContainsString('no-cache', $response->headers->get('Cache-Control'));
    }

    public function test_authenticated_admin_is_redirected_away_from_login(): void
    {
        $guard = Mockery::mock(Guard::class);
        $guard->shouldReceive('check')->once()->andReturnTrue();
        Auth::shouldReceive('guard')->with('admin')->once()->andReturn($guard);

        $this->get(route('admin.login'))
            ->assertRedirect('/');
    }
}
