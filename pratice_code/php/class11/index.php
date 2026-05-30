<?php

abstract class PaymentProcessor
{
  protected float $amount;
  public function __construct(float $amount)
  {
    $this->amount = $amount;
  }

  // 1 abstract method to be implemented by subclasses
  abstract public function processPayment(): void;

  // 2 Regular method to validate payment amount
  public function formatAmount(): string
  {
    return number_format($this->amount, 2);
  }
}

class PayPalProcessor extends PaymentProcessor
{
  private string $email;
  public function __construct(float $amount, string $email)
  {
    parent::__construct($amount);
    $this->email = $email;
  }

  public function processPayment(): void
  {
    echo "Processing PayPal payment of $" . $this->formatAmount() . " for email: " . $this->email . "<br>";
  }
}

class CreditCardProcessor extends PaymentProcessor
{
  private string $cardNumber;
  public function __construct(float $amount, string $cardNumber)
  {
    parent::__construct($amount);
    $this->cardNumber = $cardNumber;
  }

  public function processPayment(): void
  {
    echo "Processing Credit Card payment of $" . $this->formatAmount() . " for card number: " . $this->cardNumber . "<br>";
  }
}

$paypalPayment = new PayPalProcessor(100.00, "chantha@gmail.com");
$paypalPayment->processPayment(); // Output: Processing PayPal payment of $100.00 for email: chantha@gmail.com

$creditCardPayment = new CreditCardProcessor(250.50, "1234-5678-9012-3456");
$creditCardPayment->processPayment(); // Output: Processing Credit Card payment of $250.
