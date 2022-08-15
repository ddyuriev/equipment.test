<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Console\Command;

class register extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'register {name} {email} {password}';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $fields = [
            'name' => $this->argument('name'),
            'email' => $this->argument('email'),
            'password' => $this->argument('password'),
        ];
        $validator = Validator::make($fields, [
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string'
        ]);
        if ($validator->fails()) {
            print_r($validator->errors()->all());
            return 0;
        }

        $fields['password'] = bcrypt($fields['password']);
        $user = User::create($fields);
        $token = $user->createToken($fields['email'])->plainTextToken;

        $response = [
            'user' => $user->toArray(),
            'token' => $token
        ];
        print_r($response);

        return 0;
    }
}
