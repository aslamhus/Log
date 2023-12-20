# Simple Log Class

A lightweight PHP class for easy and flexible logging, designed for simplicity and ease of use. This class allows you to log messages to a default log file or a custom log file, making it convenient to implement logging throughout your application.

## Features

- **Static Methods:** Utilize static methods to log messages from anywhere in your application without the need to instantiate the class.

- **Write to Log:** Log messages with a timestamp to a specified log file.

  ```php
  Log::write("update mysql record: $record_id");
  ```

- **Search in Log:** Find occurrences of a regex pattern in a log file.

  ```php
  $pattern = '/update air_date/';
  $matches = Log::find($logPath, $pattern);
  ```

- **Clear Log:** Clear the contents of a log file.

  ```php
  Log::clear($logPath);
  ```

- **Overwrite Last Line:** Overwrite the last line of a log file, useful for logging progress.

  ```php
  Log::write("update air_date (received from client): $air_date", 'log', 'logs', true);
  ```

## Usage

### Writing to Log

```php
Log::write("Your log message here");
```

### Searching in Log

```php
$pattern = '/your_search_pattern/';
$matches = Log::find($logPath, $pattern);
```

### Clearing Log

```php
Log::clear($logPath);
```

### Overwriting Last Line

```php
Log::write("Your log message here", 'log', 'logs', true);
```

## TODO

1. Add logging levels.
2. Implement a wrapper class to support non-static methods where a log file path is passed to the constructor.

## Contributing

Contributions are welcome! Please fork the repository and create a pull request with your improvements.

## License

This Simple Log Class is licensed under the [MIT License](LICENSE).

---

_Designed and maintained by [@aslamhus](https://github.com/aslamhus)_
