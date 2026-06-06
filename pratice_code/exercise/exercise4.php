<?php

class User
{
  public function __construct(
    protected string $username,
    protected string $email
  ) {}

  public function getUsername(): string
  {
    return $this->username;
  }

  public function getPermissions(): array
  {
    return ['view_pages'];
  }
}

class Editor extends User
{
  // Inherits constructor from User
  public function getPermissions(): array
  {
    // Merge parent permissions with editor specific ones
    return array_merge(parent::getPermissions(), ['edit_pages', 'publish_pages']);
  }
}

class Admin extends Editor
{
  public function getPermissions(): array
  {
    // Merge editor permissions with admin specific ones
    return array_merge(parent::getPermissions(), ['delete_pages', 'manage_users']);
  }
}

// --- Usage Example ---
$users = [
  new User("john_doe", "john@example.com"),
  new Editor("alice_w", "alice@example.com"),
  new Admin("super_dev", "admin@example.com")
];

foreach ($users as $user) {
  echo "User: " . $user->getUsername() . " | Total Permissions: " . count($user->getPermissions()) . "\n";
  echo "Permissions: " . implode(", ", $user->getPermissions()) . "\n\n";
}
