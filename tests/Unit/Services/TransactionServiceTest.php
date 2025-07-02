<?php

use Src\Api\Models\Transaction;
use Src\Api\Models\Account;
use Src\Api\Services\TransactionService;
use Src\Api\Services\AuthorizationService;
use Src\Api\Repositories\TransactionRepositoryPdo;
use Src\Api\Repositories\AccountRepositoryPdo;

beforeEach(function () {
    $this->authService = mock(AuthorizationService::class);
    $this->transactionRepo = mock(TransactionRepositoryPdo::class);
    $this->accountRepo = mock(AccountRepositoryPdo::class);
    $this->pdo = mock(PDO::class);

    $this->service = new class($this->authService, $this->transactionRepo, $this->accountRepo, $this->pdo) extends TransactionService {
        public function __construct($auth, $transRepo, $accountRepo, $pdo)
        {
            $this->authorizationService = $auth;
            $this->transactionRepositoryPdo = $transRepo;
            $this->accountRepositoryPdo = $accountRepo;
            $this->pdoConnection = $pdo;
        }
    };
});

test('should throw exception when service is unavailable', function () {
    $this->authService->shouldReceive('isServiceAvailable')->once()->andReturn(false);

    $this->expectException(Exception::class);
    $this->expectExceptionMessage('Service is not available. Try again later.');

    $this->service->transfer([
        'payer' => 1,
        'payee' => 2,
        'amount' => 100,
    ]);
});

test('should throw exception when user has insufficient balance', function () {
    $this->authService->shouldReceive('isServiceAvailable')->once()->andReturn(true);

    $wallet = new Account;
    $wallet->balance = 50;

    $this->accountRepo->shouldReceive('findByUserId')->once()->andReturn($wallet);

    $this->expectException(Exception::class);
    $this->expectExceptionMessage('The requested amount is not available.');

    $this->service->transfer([
        'payer' => 1,
        'payee' => 2,
        'amount' => 100,
    ]);
});


test('should rollback if an exception occurs during makeTransaction', function () {
    $transaction = new Transaction;
    $transaction->payerId = 1;
    $transaction->payeeId = 2;
    $transaction->amount = 100;

    $this->transactionRepo->shouldReceive('create')->andReturn($transaction);
    $this->pdo->shouldReceive('beginTransaction')->once()->andReturnTrue();
    $this->pdo->shouldReceive('rollback')->once()->andReturnTrue();

    $this->accountRepo->shouldReceive('findByUserId')->once()->andThrow(new Exception('Fake failure'));

    $this->expectException(Exception::class);
    $this->expectExceptionMessage('Error while making transaction!');

    $this->service->makeTransaction([
        'payer' => 1,
        'payee' => 2,
        'amount' => 100,
    ]);
});

test('userTransactions returns transactions', function () {
    $this->transactionRepo
        ->shouldReceive('findUserTransactions')
        ->once()
        ->with(1)
        ->andReturn(['tx1', 'tx2']);

    $result = $this->service->userTransactions(1);

    expect($result)->toBe(['tx1', 'tx2']);
});
