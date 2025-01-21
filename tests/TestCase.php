<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;


abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    protected $tokenStructure = [
            'access_token',
            'refresh_token',
            'expires_in',
            'token_type'
        ];
    protected $userStructure = [
        'uuid',
        'email',
        'name',
        'created_at',
        'updated_at'
    ];

    protected $regularUserResource = [
        'uuid',
        'email'
    ];

    protected $projectResource = [
        'uuid',
        'name',
        'description',
        'num_chars',
        'num_pages',
        'status',
        'total_price',
        'start_date',
        'projected_end_date'
    ];

    protected $projectResourceExpanded = [
        'owner_id',
        'created_at',
        'updated_at'
    ];
}
