<?php

namespace tests\Unit\Actions;

use SkPro\Component\User\CommandsHandlers\UpdateUserCommandHandler;
use SkPro\Component\User\Dtos\UserDto;
use SkPro\Component\User\Mail\SendWelcomeMessage;
use SkPro\Component\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Mockery;
use SkPro\Models\UserEmail;
use Tests\TestCase;

class UpdateUserActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_updates_user_and_emails()
    {
        Mail::fake();
        $user = User::factory()->create();
        UserEmail::factory()->create(['user_id' => $user->id, 'email' => 'old@example.com']);

        $dto = new UserDto(
            name: 'Janusz',
            lastName: 'Kowalski',
            phone: '222222222',
            emails: ['janusz1@example.com', 'janusz2@example.com']
        );

        $action = new UpdateUserCommandHandler;
        $action->handle($user, $dto);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Janusz',
            'last_name' => 'Kowalski',
            'phone' => '222222222',
        ]);

        $this->assertDatabaseMissing('user_emails', [
            'email' => 'kowalski@example.com',
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseHas('user_emails', [
            'email' => 'janusz1@example.com',
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseHas('user_emails', [
            'email' => 'janusz2@example.com',
            'user_id' => $user->id,
        ]);

        Mail::assertQueued(SendWelcomeMessage::class, 2);
    }

    public function test_it_handles_exception_during_update()
    {
        $user = User::factory()->create();
        $dto = new UserDto(
            name: 'Janusz',
            lastName: 'Kowalski',
            phone: '222222222',
            emails: ['new@example.com']
        );

        $userMock = Mockery::mock(User::class)->makePartial();
        $userMock->shouldReceive('update')->andThrow(new \Exception('Test error'));
        $this->app->instance(User::class, $userMock);

        $action = new UpdateUserCommandHandler;
        $action->handle($userMock, $dto);

        $this->assertDatabaseMissing('users', [
            'name' => 'Janusz',
        ]);
    }
}
