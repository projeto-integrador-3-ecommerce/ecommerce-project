<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('users:promote-admin {email}')]
#[Description('Grant administrator access to an existing user')]
class PromoteUserToAdmin extends Command
{
    public function handle(): int
    {
        $user = User::query()->where('email', (string) $this->argument('email'))->first();

        if ($user === null) {
            $this->error('No user found with that email address.');

            return self::FAILURE;
        }

        $user->is_admin = true;
        $user->save();

        $this->info('Administrator access granted.');

        return self::SUCCESS;
    }
}
