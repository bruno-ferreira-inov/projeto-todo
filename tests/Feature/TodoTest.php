<?php

use App\Models\Todo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\deleteJson;

uses(RefreshDatabase::class);

it('can create a todo', function () {
    $testData = [
        'title' => 'Test Task',
        'priority' => 5,
        'completed' => false,
    ];

    $response = $this->postJson('/todos', $testData);

    $response->assertStatus(201);
    $this->assertDatabaseHas('todos', [
        'title' => 'Test Task',
        'priority' => 5,
        'completed' => false,
    ]);
});

it('can update a todo', function () {
    $todo = Todo::factory()->create([
        'title' => 'Old Title',
        'priority' => 2,
        'completed' => false,
    ]);

    $testData = [
        'title' => 'Updated Title',
        'priority' => 1,
        'completed' => true,
    ];

    $response = $this->patchJson("/todos/{$todo->id}/update", $testData);

    $response->assertStatus(200);
    $this->assertDatabaseHas('todos', [
        'id' => $todo->id,
        'title' => 'Updated Title',
        'priority' => 1,
        'completed' => true,
    ]);
});

it('can delete a todo', function () {
    // Create a todo
    $todo = Todo::factory()->create();

    // Call the delete endpoint
    $response = $this->deleteJson(route('todos.destroy', $todo));

    // Assert the response has no content (204)
    $response->assertNoContent();

    // Assert the todo is deleted from the database
    $this->assertFalse(Todo::where('id', $todo->id)->exists());
});

test('example', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});
