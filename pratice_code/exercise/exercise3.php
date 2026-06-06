<?php

interface NotificationSender
{
  public function send(string $message, string $to): void;
}

class EmailNotification implements NotificationSender
{
  public function send(string $message, string $to): void
  {
    echo "Email sent to $to with message: $message\n";
  }
}

class SMSNotification implements NotificationSender
{
  public function send(string $message, string $to): void
  {
    echo "SMS sent to $to with message: $message\n";
  }
}

class PushNotification implements NotificationSender
{
  public function send(string $message, string $to): void
  {
    echo "Push alert sent to $to with message: $message\n";
  }
}

class UserAlert
{
  // Dependency Injection via Constructor
  public function __construct(private NotificationSender $sender) {}

  public function trigger(string $msg, string $userContact): void
  {
    $this->sender->send($msg, $userContact);
  }
}

// --- Usage Example ---
// We can swap the underlying notification strategy without touching UserAlert logic
$emailAlert = new UserAlert(new EmailNotification());
$emailAlert->trigger("Your code works!", "dev@example.com");

$smsAlert = new UserAlert(new SMSNotification());
$smsAlert->trigger("Security Alert!", "+123456789");
