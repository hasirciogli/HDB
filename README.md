**README.md for Hasirciogli\Hdb\Database Class**

## Introduction

This README file provides an overview of the `Hasirciogli\Hdb\Database` class, a PHP class designed to simplify database interactions, offering a structure similar to Laravel's database layer.

## Features

* **Connection Management:** Establishes a PDO connection to a MySQL database based on configuration provided through an interface (`DatabaseConfigInterface`).
* **Query Building:** Facilitates the construction of SQL queries using a chainable method approach.
* **Prepared Statements:** Employs prepared statements to prevent SQL injection vulnerabilities.
* **Data Binding:** Allows for binding of parameters to queries for dynamic data handling.
* **Execution and Retrieval:** Executes the built query and retrieves results as associative arrays or a single row.

## Installation

**1. Composer (Recommended):**

If you're using Composer in your project, add the package to your `composer.json` file:

```json
"require": {
    "hasirciogli/hdb": "^1.0" // Replace with the specific version you want
}
```

Then, run `composer install` to download the package.

**2. Manual Download:**

Download the `Hasirciogli\Hdb` directory and include it in your project's file structure.

## Usage

**1. Configuration:**

Create a class that implements the `DatabaseConfigInterface`. This interface defines properties for database credentials and connection details:

```php
interface DatabaseConfigInterface
{
    const DB_HOST = 'localhost';
    const DB_NAME = 'your_database_name';
    const DB_USER = 'your_username';
    const DB_PASS = 'your_password';
}
```

**2. Database Connection:**

Instantiate the `Database` class, passing an instance of your configuration class:

```php
use Hasirciogli\Hdb\Database;
use MyProject\DatabaseConfig; // Replace with your config class path

$db = Database::cfun(new DatabaseConfig());
```

**3. Building Queries:**

Use chainable methods to construct your SQL queries:

```php
$users = $db->Select('users')
            ->Where('isActive', true)
            ->OrderBy('username', 'ASC')
            ->Get('all'); // Get all results as an array
```

**4. Prepared Statements and Data Binding:**

The class utilizes prepared statements and data binding automatically. You don't need to manually escape values:

```php
$userId = 123;
$user = $db->Select('users')
            ->Where('id', $userId)
            ->Get(); // Get a single user row
```

**5. Additional Methods:**

* `Use(string $DbName)`: Selects a specific database within the connection.
* `LastInsertId()`: Retrieves the last inserted ID after an `INSERT` operation.
* `Insert(string $TableName, $Dataset)`: Builds an `INSERT` query with the specified table name and data.
* `CustomSql(string $Sql)`: Allows for execution of raw SQL queries.

## Error Handling

The `CheckDB()` method throws an exception if a database connection cannot be established. Handle this exception in your code to provide appropriate error messages.

## Security Considerations

While the `Database` class utilizes prepared statements, it's still recommended to validate user input before using it in queries to prevent potential security issues.

## Contribution

We welcome contributions to improve this library! Fork the repository on GitHub and submit pull requests with your enhancements.

## License

This library is licensed under the [MIT License](https://opensource.org/licenses/MIT).
