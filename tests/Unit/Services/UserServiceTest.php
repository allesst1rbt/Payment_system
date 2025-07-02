<?php

use Src\Api\Models\User;
use Src\Api\Models\Account;
use Src\Api\Services\UserService;
use Src\Api\Repositories\UserRepositoryPdo;
use Src\Api\Repositories\AccountRepositoryPdo;
use Src\Jwt;
use PHPUnit\Framework\MockObject\MockObject;

beforeEach(function () {
     $this->userRepository = mock(UserRepositoryPdo::class);
    $this->accountRepository = mock(AccountRepositoryPdo::class);
    $this->jwt = mock(Jwt::class);

    $this->service = new class($this->userRepository, $this->accountRepository, $this->jwt) extends UserService {
        public function __construct($userRepo, $accountRepo, $jwt)
        {
            $this->userRespositoryPdo = $userRepo;
            $this->accountRepositoryPdo = $accountRepo;
            $this->jwt = $jwt;
        }
    };
});

test('can create a user successfully', function () {
    $request = [
        'name' => 'Test User',
        'email' => 'user@example.com',
        'document' => '12345678900',
        'password' => 'Pass12&woe',
        'type' => 'user',
    ];

    $mockUser = new User;
    $mockUser->id = 1;

    $mockAccount = new Account;
    $mockAccount->userId = 1;
    $mockAccount->balance = 100.00;

    $this->userRepository
        ->shouldReceive('create')
        ->once()
        ->andReturn($mockUser);
    $this->userRepository
        ->shouldReceive('findByUserEmail')
        ->once()
        ->andReturn(null);
    $this->userRepository
        ->shouldReceive('findByUserDocument')
        ->once()
        ->andReturn(null);

    $this->accountRepository
        ->shouldReceive('create')
        ->once()
        ->andReturn($mockAccount);

    $result = $this->service->create($request);

    expect($result)->toBeInstanceOf(User::class);
    expect($result->account)->toBeInstanceOf(Account::class);
    expect($result->account->balance)->toEqual(100.00);
});

test('cannot create a user successfully because password', function () {
    $this->expectException(Exception::class);
    $this->expectExceptionMessage('Invalid data received for user creation.');
    
    $request = [
        'name' => 'Test User',
        'email' => 'user@example.com',
        'document' => '12345678900',
        'password' => '12345678900',
        'type' => 'user',
    ];


    $this->service->create($request);
});

test('cannot create a user successfully because already used email', function () {
    $this->expectException(Exception::class);
    $this->expectExceptionMessage('Document or email already used');
    $request = [
        'name' => 'Test User',
        'email' => 'user@example.com',
        'document' => '12345678900',
        'password' => 'Pass12&woe',
        'type' => 'user',
    ];

    $mockUser = new User;
    $mockUser->id = 1;


    $this->userRepository
        ->shouldReceive('findByUserEmail')
        ->once()
        ->andReturn($mockUser);
    $this->userRepository
        ->shouldReceive('findByUserDocument')
        ->once()
        ->andReturn(null);


    $this->service->create($request);

});
test('cannot create a user successfully because already used document', function () {
    $this->expectException(Exception::class);
    $this->expectExceptionMessage('Document or email already used');
    $request = [
        'name' => 'Test User',
        'email' => 'user@example.com',
        'document' => '12345678900',
        'password' => 'Pass12&woe',
        'type' => 'user',
    ];

    $mockUser = new User;
    $mockUser->id = 1;


    $this->userRepository
        ->shouldReceive('findByUserEmail')
        ->once()
        ->andReturn($mockUser);
    $this->userRepository
        ->shouldReceive('findByUserDocument')
        ->once()
        ->andReturn(null);


    $this->service->create($request);

});
test('find user by id returns user with account', function () {
    $user = new User;
    $user->id = 1;

    $account = new Account;
    $account->userId = 1;
    $account->balance = 100;

    $this->userRepository->shouldReceive('findByUserId')->once()->andReturn($user);
    $this->accountRepository->shouldReceive('findByUserId')->once()->andReturn($account);

    $result = $this->service->findUserById(1);

    expect($result)->toBeInstanceOf(User::class);
    expect($result->account)->toBeInstanceOf(Account::class);
    expect($result->account->balance)->toEqual(100);
});

test('login returns JWT token when credentials are valid', function () {
    $user = new User;
    $user->email = 'user@example.com';
    $user->password = password_hash('secret', PASSWORD_BCRYPT);

    $this->userRepository->shouldReceive('findByUserEmail')->once()->andReturn($user);
    $this->jwt->shouldReceive('encode')->once()->andReturn('fake-jwt');

    $auth = (object) [
        'email' => 'user@example.com',
        'password' => 'secret',
    ];

    $token = $this->service->login($auth);

    expect($token)->toBe('fake-jwt');
});

test('login throws exception on invalid credentials', function () {
    $this->expectException(Exception::class);
    $this->expectExceptionMessage('Invalid Credentials');

    $this->userRepository->shouldReceive('findByUserEmail')->once()->andReturn(null);

    $auth = (object) [
        'email' => 'user@example.com',
        'password' => 'wrongpass',
    ];

    $this->service->login($auth);
});
