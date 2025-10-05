<?php

namespace tests\Unit\Actions;

use SkPro\Component\User\CommandsHandlers\StoreUserCommandHandler;
use SkPro\Component\User\Dtos\UserDto;
use SkPro\Component\User\Mail\SendWelcomeMessage;
use SkPro\Component\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class StoreUserActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_user_and_emails_and_sends_mail()
    {
        Mail::fake();

        $dto = new UserDto(
            name: 'Janusz',
            lastName: 'Kowalski',
            phone: '123456789',
            emails: ['janusz@example.com', 'kowalski@example.com']
        );

        $action = new StoreUserCommandHandler;
        $action->handle($dto);

        $this->assertDatabaseHas('users', [
            'name' => 'Janusz',
            'last_name' => 'Kowalski',
            'phone' => '123456789',
        ]);

        $user = User::where('name', 'Janusz')->first();

        $this->assertDatabaseHas('user_emails', [
            'user_id' => $user->id,
            'email' => 'janusz@example.com',
        ]);
        $this->assertDatabaseHas('user_emails', [
            'user_id' => $user->id,
            'email' => 'kowalski@example.com',
        ]);

        Mail::assertQueued(SendWelcomeMessage::class, 2);
    }
}
