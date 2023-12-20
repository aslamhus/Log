<?php


/**
 *
 * Log v1.0.0
 * A simple log class by @aslamhus
 *
 *
 * ## Features
 * Log to a default log file or a custom log file.
 * We use static methods so that we can log from anywhere in the application
 * without having to instantiate the class.
 *
 * ## Usage
 *
 * ### Write to log file
 *
 * @example
 * Log::write("update mysql record: $record_id");
 * // will result in a log file with the following contents:
 * // [17.12.2020 18:00:00] update mysql record: 123
 *
 * ### Search
 *
 * You can find occurences of a regex pattern in a log file:
 *
 * @example
 * $pattern = '/update air_date/';
 * $matches = Log::find($logPath, $pattern);
 *
 *
 * ### Clear
 *
 * @example
 *
 * Log::clear($logPath);
 *
 *
 * ### Overwrite last line
 *
 * You can overwrite the last line of a log file by passing true as the third argument
 * This is useful for logging progress
 *
 * @example
 * Log::write("update air_date (received from client): $air_date", 'log', 'logs', true);
 *
 *
 *
 * ## TODO:
 *
 * 1. Add logging levels
 * 2. Wrapper class to support non static methods where a log file path is passed to the constructor
 *
 * -
 */
class Log
{
    /**
     * Log to a file
     *
     * @example
     *
     *  Log::write("update air_date (received from client): $air_date");
     *
     * @param string $message - the message to log
     * @param string [$filename] - the filename (excluding extension. Will still have date appended )
     * @param string [$path] - the path to the log file
     * @return void
     */
    public static function write(string $message, $filename = '', $path = '', $overwriteLastLine = false)
    {

        // get the real path to the logs directory
        $path = $path ?: realpath($_SERVER['DOCUMENT_ROOT']) . DIRECTORY_SEPARATOR . 'logs';
        // if the path does not exist, create it
        if(!file_exists($path)) {
            // make path directories recursively
            mkdir($path, 0755, true);
        }
        // use the date as the filename if none is provided
        $filename = $filename ?: date("j.n.Y") . '.log';
        $logPath = $path . DIRECTORY_SEPARATOR . $filename;
        // if overwriteLastLine is true, truncate the last line from the file
        // this is useful for logging progress
        if($overwriteLastLine && file_exists($logPath)) {
            Log::truncateLastLine($logPath);
        }
        // prepend message with date and time, e.g. [17.12.2020 18:00:00]
        $message = "[" . date("j.n.Y H:i:s") . "] " . $message . PHP_EOL;
        // validate log path
        if(!is_writable($path)) {
            throw new \Exception("Log directory is not writable: '$path'");
        }
        // write to log file
        file_put_contents($logPath, $message, FILE_APPEND);

    }



    /**
     * Truncate the last line from a file
     *
     * @param string $logPath
     * @return void
     */
    public static function truncateLastLine($logPath)
    {

        $size = filesize($logPath);
        $block = 4096;
        $truncate = max($size - $block, 0);
        $f =  fopen($logPath, 'c+');
        if(flock($f, LOCK_EX)) {
            fseek($f, $truncate);
            // trim trailing newline chars of the file
            $bin = rtrim(fread($f, $block), "\n");
            //  truncate the file
            if ($r = strrpos($bin, "\n")) {
                ftruncate($f, $truncate + $r + 1);
            }
        }
        fclose($f);
    }

    /**
     * Clear a log file
     *
     * @param  string  $logPath - the path to the log file
     * @return void
     */
    public static function clear($logPath)
    {
        Log::checkLogFileExists($logPath);
        file_put_contents($logPath, '');
    }

    /**
     * Finds occurences of a regex pattern in a log file
     *
     * @param [type] $logPath
     * @param string $needle - the text to search for
     * @return array
     */
    public static function find($logPath, string $needle): array
    {
        Log::checkLogFileExists($logPath);
        // get the log file contents
        $log = file_get_contents($logPath);
        // match all occurences of the pattern until the end of the line
        $pattern = "/" . $needle . ".*/";
        // match all occurences of the pattern
        preg_match($pattern, $log, $matches);
        return $matches;
    }

    /**
     * Check if a log file exists
     *
     * @param string $logPath - the full path to the log file
     * @return true
     */
    private static function checkLogFileExists($logPath)
    {
        if(!file_exists($logPath)) {
            throw new \Exception("Log file does not exist: '$logPath'");
        }
        return true;
    }
}
