<?php

namespace Tests;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

use App\Models\User;
use App\Models\Report;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
        //Artisan::call('migrate');
        //Artisan::call('db:seed');
    }

    public function createUser(): User
    {
        return User::create([
          'username' => 'teste',
          'email' => 'teste@teste.com',
          //'password' => $request->password,
          'password' => Hash::make('123456789'),
          'isAdmin' => true,
          'entryDate' => Carbon::now()
        ]);
    }

    public function readUser($id): User
    {
        return User::find($id);
    }

    public function readUserByEmail($email): User
    {
        return User::where('email', $email)->first();
    }

    public function createReport($creatorId): Report
    {
        return Report::create([
            'title' => 'report-test',
            'summary' => 'summary test',
            'creatorID' => $creatorId,
            'fileInServer' => 'archive.pdf' // TODO: make this later
        ]);
    }
}
