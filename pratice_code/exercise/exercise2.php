<?php

class BankAccount {
    private float $balance;
    private array $transactionHistory = [];

    public function __construct(float $initialBalance) {
        if ($initialBalance < 0) {
            throw new InvalidArgumentException("Initial balance cannot be negative.");
        }
        $this->balance = $initialBalance;
        $this->transactionHistory[] = "Account opened with initial balance: $" . number_format($initialBalance, 2);
    }

    public function deposit(float $amount): void {
        if ($amount <= 0) {
            throw new InvalidArgumentException("Deposit amount must be positive.");
        }
        $this->balance += $amount;
        $this->transactionHistory[] = "Deposited $" . number_format($amount, 2);
    }

    public function withdraw(float $amount): void {
        if ($amount <= 0) {
            throw new InvalidArgumentException("Withdrawal amount must be positive.");
        }
        if ($amount > $this->balance) {
            throw new Exception("Insufficient funds for withdrawal of $" . number_format($amount, 2));
        }
        $this->balance -= $amount;
        $this->transactionHistory[] = "Withdrew $" . number_format($amount, 2);
    }

    public function getBalance(): float {
        return $this->balance;
    }

    public function getHistory(): array {
        return $this->transactionHistory;
    }
}

// --- Usage Example ---
try {
    $account = new BankAccount(100.00);
    $account->deposit(50.50);
    $account->withdraw(30.00);
    
    foreach ($account->getHistory() as $log) {
        echo $log . "\n";
    }
    echo "Current Balance: $" . $account->getBalance() . "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}