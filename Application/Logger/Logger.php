<?php declare(strict_types=1);


namespace Ramsterhad\DeepDanbooruTagAssist\Application\Logger;


use Ramsterhad\DeepDanbooruTagAssist\Application\Application;

class Logger
{
    protected ?string $destination;

    /**
     * @param string|null $destination Optional path to an alternative log file.
     */
    public function __construct(?string $destination = null)
    {
        $this->destination = $destination;
    }

    public function log(string $message): void
    {
        if ($this->destination === null) {
            $this->destination = self::getDefaultDestinationDirectory() .self::getDefaultDestinationFile();
        }

        $message = \sprintf('%s: %s', $this->getDate(), $message);

        $this->write($this->destination, $message);
    }

    public static function getDefaultDestinationDirectory(): string
    {
        return Application::getBasePath() . 'log' . \DIRECTORY_SEPARATOR;
    }

    public static function getDefaultDestinationFile(): string
    {
        return 'default.log';
    }

    private function getDate(): string
    {
        return $date = \date('Y.m.d H:i:s', \time());
    }
    
    private function write(string $destination, string $message): void
    {
        $message .= \PHP_EOL;
        \file_put_contents($destination, $message, \FILE_APPEND);
    }
}
