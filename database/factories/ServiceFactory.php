<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Service;
use App\Helpers\ServiceValidationHelper;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    protected $model = Service::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => fake()->randomElement(['proofreading', 'translating']),
            'languages' => $this->generateLanguages('proofreading'),
            'user_id' => User::inRandomOrder()->value('id')
        ];
    }

    public function configure(){
        return $this->afterMaking(function (Service $service){
            if($service->type === 'translating' && ServiceValidationHelper::isOneLevelArray($service->languages)){
                $service->languages = $this->generateLanguages('translating');
            }
        });
    }

    private function generateLanguages($type){
        if($type === 'proofreading'){
            return fake()->randomElements(['es-ES', 'ca-ES', 'en-UK', 'en-US', 'de-DE'], fake()->numberBetween(1,5));
        }

        if($type === "translating"){
            $pairs = [];
            for ($i = 0; $i < fake()->numberBetween(1,3); $i++){
                $pairs[] = [
                    'source' => fake()->randomElement(['es-ES', 'ca-ES', 'en-UK', 'en-US', 'de-DE']),
                    'target' => fake()->randomElement(['es-ES', 'ca-ES', 'en-UK', 'en-US', 'de-DE']),
                    'bidirectional' => fake()->boolean()
                ];
            }
            return $pairs;
        }
        return [];
    }

    public function translating(): Factory {
        return $this->state(function (array $attributes){
            return [
                'type' => 'translating',
                'languages' => $this->generateLanguages('translating')
            ];
        });
    }

    public function proofreading(): Factory {
        return $this->state(function (array $attributes){
            return [
                'type' => 'proofreading',
                'languages' => $this->generateLanguages('proofreading')
            ];
        });
    }
}
