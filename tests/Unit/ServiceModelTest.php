<?php
use App\Models\User;
use App\Models\Service;

beforeEach(function(){
    $this->user = User::factory()->create();
});
describe('Service Model', function(){

    describe("Proofreading", function(){
        test("duplicate languages", function(){
            $service = Service::factory()->create([
                'type' => 'proofreading',
                'languages' => ['es-ES', 'es-ES', 'es-ES', 'ca-ES']
            ]);
            expect($service->id)->not->toBeNull();
            expect($service->languages)->toBe(['es-ES', 'ca-ES']);
    
        });

        it('throws exception for wrong language structure', function(){
            $service = Service::factory()->create([
                'type' => "proofreading",
                'languages' => ["This is Wrong", 0, ["nested array" => "this is wrong"]]
            ]);
        })->throws("Languages must be a one-level language pair array. For example: ['es-ES', 'en-UK', 'de-DE']");

        it('avoids updating with duplicates', function(){
            $languages = ['es-ES', 'ca-ES', 'en-UK'];
            $service = Service::factory()->translating()->create([
                'type' => "proofreading",
                'languages' => ['es-ES', 'ca-ES', 'en-UK']
            ]);
            expect($service->languages)->toBe($languages);

            $service->update([
                'languages' => ['es-ES', 'es-ES', 'ca-ES', 'ca-ES', 'ca-ES', 'en-UK']
            ]);
            $service = Service::find($service->id);
            expect($service->languages)->toBe($languages);
        });
    });
    
    describe("Translating", function(){
        test('Duplicate languages', function(){
            $service = Service::factory()->create([
                'type' => 'translating',
                'languages' => [
                    [
                        'source' => 'es-ES', 
                        'target' => 'ca-ES',
                        'bidirectional' => true
                    ],
                    [
                        'source' => 'es-ES', 
                        'target' => 'ca-ES',
                        'bidirectional' => true
                    ],
                    [
                        'source' => 'en-UK', 
                        'target' => 'ca-ES',
                        'bidirectional' => false
                    ]]
            ]);
            expect($service->id)->not->toBeNull();
            expect($service->languages)->toBe([
                [
                    'source' => 'es-ES', 
                    'target' => 'ca-ES',
                    'bidirectional' => true
                ],
                [
                    'source' => 'en-UK', 
                    'target' => 'ca-ES',
                    'bidirectional' => false
                ]
            ]);
        });

        it('throws exception for wrong language structure', function(){
            $service = Service::factory()->create([
                'type' => "translating",
                'languages' => ["This is Wrong", 0, ["nested array" => "this is wrong"]]
            ]);
        })->throws("Language JSON must have structure: ['source': 'es-ES', 'target': 'ca-ES', 'bidirectional': true]");
    });

    

});