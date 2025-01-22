<?php
use App\Models\User;
use App\Models\Service;
use Database\Seeders\TestSeeder;

beforeEach(function(){
    $this->seed(TestSeeder::class);
    $this->regularUser = User::where('email', env('EMAIL'))->first();
    $this->adminUser = User::where('email', env('ADMIN_EMAIL'))->first();

});

describe('Service Management', function(){

    describe('authentication', function(){
        dataset('service_endpoints', function(){
            $endpoint = 'api/services';
            return [
                ['getJson', $endpoint],
                ['getJson', $endpoint . '/1'],
                ['postJson', $endpoint],
                ['putJson', $endpoint . '/1'],
                ['deleteJson', $endpoint . '/1']
            ];
        });
        test('endpoints require authentication', function($method, $endpoint) {
            $this->$method($endpoint)->assertStatus(401);
        })->with('service_endpoints');
    });

    test('Services Factory works', function(){
        $service = Service::factory()->create();
        expect($service->id)->toBe(1);
        expect(Service::find($service->id))->not->toBeNull();
    });


});