## Firebase-Client
Firebase Client for PHP.
Create your PHP project with firebase's Realtime Database.

### Example
```php
<?php

require "vendor/autoload.php";
use Firebase\Firebase;

$url = "your firebase url";

$client = new Firebase($url);

// Read data
$client->retrieve("users");

// Post data
$data = ['name' => 'John doe', 'age' => 28];
$client->insert("users", $data);

// Update data
$data = ['name' => 'Natasha', 'age' => 21];
$client->insert("users", $data, $id);

// Delete data
$client->update("users", $id);
```
