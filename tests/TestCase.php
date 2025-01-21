<?php

namespace Tests;

use Illuminate\Support\Facades\DB;
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

    protected $showUserResource = [
        'uuid',
        'name',
        'email',
        'projects'
    ];

    protected $regularUserResource = [
        'uuid',
        'email',
        'project_count'
    ];

    protected $adminUserResource = [
        'uuid',
        'email',
        'project_count',
        'name',
        'created_at',
        'updated_at'
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

    protected function setUp(): void
    {
        parent::setUp();

        // Enable foreign key constraints for SQLite
        if (DB::connection()->getDriverName() === 'sqlite_testing') {
            DB::statement('PRAGMA foreign_keys = ON');
        }
    }
}
