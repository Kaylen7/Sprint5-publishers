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
        'id',
        'name',
        'email',
        'created_at',
        'updated_at'
    ];

    protected $regularUserResource = [
        'id',
        'email'
    ];
}
