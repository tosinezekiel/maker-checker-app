<?php

namespace Database\Factories;

use App\Models\Document;
use App\Constants\Status;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class DocumentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Document::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'data' => json_encode(User::factory()->make()->toArray()),
            'status' => Status::PENDING,
            'uuid' => Str::uuid(),
            'author_id' => User::factory()->create()->id
        ];
    }
    
}
