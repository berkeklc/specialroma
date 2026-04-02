<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Core\App\Enums\PageStatus;
use Modules\Core\App\Models\Page;

uses(RefreshDatabase::class);

test('the application returns a successful response', function () {
    Page::query()->create([
        'title' => ['tr' => 'Ana Sayfa', 'en' => 'Home'],
        'slug' => 'home',
        'status' => PageStatus::Published->value,
        'is_home' => true,
        'blocks' => [],
        'sort_order' => 0,
    ]);

    $response = $this->get('/');

    $response->assertOk();
});
