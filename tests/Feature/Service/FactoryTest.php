<?php
use App\Models\User;
use App\Models\Service;
use Database\Seeders\TestSeeder;

beforeEach(function(){
    $this->seed(TestSeeder::class);
    $this->regularUser = User::where('email', env('EMAIL'))->first();
    $this->adminUser = User::where('email', env('ADMIN_EMAIL'))->first();
});
describe('Service Factory', function(){
    it('creates service', function(){
        $service = Service::factory()->create(['type' => 'translating']);
        expect($service->id)->toBe(1);
        expect(Service::find($service->id))->not->toBeNull();
    });
    
    it('creates specific services', function(){
        $service = Service::factory()->proofreading()->create();
        expect($service->type)->toBe('proofreading');
        expect($service->languages)->not->toHaveKeys(['source', 'target', 'bidirectional']);
        
        $service = Service::factory()->translating()->create();
        expect($service->type)->toBe('translating');
        expect($service->languages[0])->toHaveKeys(['source', 'target', 'bidirectional']);
    });
});