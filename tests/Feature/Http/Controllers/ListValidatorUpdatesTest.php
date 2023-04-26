<?php

use App\Models\ValidatorUpdate;
use Illuminate\Testing\Fluent\AssertableJson;

it('should list all records', function () {
    ValidatorUpdate::factory(100)->create();

    $this
        ->get('/api/validators/updates', [])
        ->assertStatus(200)
        ->assertJson(function (AssertableJson $json) {
            $json
                ->has(3)
                ->has('data', 100)
                ->has('links')
                ->has('meta');
        });
});

it('should filter records', function () {
    $data = ValidatorUpdate::factory(100)->create();

    $this
        ->get('/api/validators/updates?'.http_build_query([
            'filter[block_id]' => $data[0]->block_id,
        ]))
        ->assertStatus(200)
        ->assertJson(function (AssertableJson $json) {
            $json
                ->has(3)
                ->has('data', 1)
                ->has('links')
                ->has('meta');
        });
});

it('should only include requested fields', function () {
    ValidatorUpdate::factory(100)->create();

    $this
        ->get('/api/validators/updates?'.http_build_query([
            'fields' => 'value',
        ]))
        ->assertStatus(200)
        ->assertJson(function (AssertableJson $json) {
            $json
                ->has(3)
                ->has('data', 100)
                ->has('links')
                ->has('meta');
        })
        ->assertJsonStructure([
            'data' => [
                '*' => ['value'],
            ],
        ]);
});
