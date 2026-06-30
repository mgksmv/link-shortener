<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\Links\LinkResource;
use App\Filament\Resources\Links\Pages\EditLink;
use App\Filament\Resources\Links\Pages\ListLinks;
use App\Filament\Resources\Links\RelationManagers\VisitsRelationManager;
use App\Models\Link;
use App\Models\User;
use Filament\Actions\Testing\TestAction;
use function Pest\Livewire\livewire;

test('can list links', function () {
    $user = User::factory()->create();
    $links = Link::factory()->count(3)->create(['user_id' => $user->id]);

    $this->actingAs($user);

    livewire(ListLinks::class)
        ->assertCanSeeTableRecords($links);
});

test('cannot see other users links', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $otherLink = Link::factory()->create(['user_id' => $otherUser->id]);

    $this->actingAs($user);

    livewire(ListLinks::class)
        ->assertCanNotSeeTableRecords([$otherLink]);
});

test('can create link', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    livewire(ListLinks::class)
        ->callAction('create', [
            'original_url' => 'https://google.com',
        ])
        ->assertHasNoActionErrors();

    $this->assertDatabaseHas('links', [
        'user_id' => $user->id,
        'original_url' => 'https://google.com',
    ]);
});

test('can delete link', function () {
    $user = User::factory()->create();
    $link = Link::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user);

    livewire(ListLinks::class)
        ->callAction(TestAction::make('delete')->table($link));

    $this->assertModelMissing($link);
});

test('can render edit page', function () {
    $user = User::factory()->create();
    $link = Link::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user);

    $this->get(LinkResource::getUrl('edit', ['record' => $link]))
        ->assertSuccessful();
});

test('can see visits on edit page', function () {
    $user = User::factory()->create();
    $link = Link::factory()->create(['user_id' => $user->id]);
    $visit1 = $link->visits()->create(['ip_address' => '192.168.0.1', 'visited_at' => now()]);
    $visit2 = $link->visits()->create(['ip_address' => '192.168.0.2', 'visited_at' => now()]);
    $visit3 = $link->visits()->create(['ip_address' => '192.168.0.3', 'visited_at' => now()]);

    $this->actingAs($user);

    livewire(VisitsRelationManager::class, [
        'ownerRecord' => $link,
        'pageClass' => EditLink::class,
    ])
        ->assertCanSeeTableRecords([$visit1, $visit2, $visit3])
        ->assertSee('192.168.0.1');
});

test('list page has expected actions visible', function () {
    $user = User::factory()->create();
    $link = Link::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user);

    livewire(ListLinks::class)
        ->assertActionVisible('create')
        ->assertActionVisible(TestAction::make('edit')->table($link))
        ->assertActionVisible(TestAction::make('delete')->table($link));
});

test('edit page has expected actions visible', function () {
    $user = User::factory()->create();
    $link = Link::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user);

    livewire(EditLink::class, ['record' => $link->id])
        ->assertActionVisible('delete');
});
