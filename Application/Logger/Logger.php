<?php declare(strict_types=1);


namespace Ramsterhad\DeepDanbooruTagAssist\Application\Logger;


use Ramsterhad\DeepDanbooruTagAssist\Application\Kernel;

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
            $this->destination = $this->getDefaultDestinationDirectory() . $this->getDefaultDestinationFile();
        }

        $message = \sprintf('%s %s', $this->getDate(), $message);

        $this->write($this->destination, $message);
    }

    public function getDefaultDestinationDirectory(): string
    {
        return Kernel::getBasePath() . 'log' . \DIRECTORY_SEPARATOR;
    }

    public function getDefaultDestinationFile(): string
    {
        return 'default.log';
    }

    /**
     * Removes the username and the token from the stacktrace.
     * @param string $stacktrace
     * @return string
     */
    protected function sanitiseCredentials(string $stacktrace): string
    {
        $stacktrace = preg_replace('/authenticate\((.*)\)/', 'authenticate($username, $token)', $stacktrace);
        $stacktrace = preg_replace('/Endpoint->requestPost\((.*)\)/', 'Endpoint->requestPost($endpoint, $username, $token)', $stacktrace);
        return $stacktrace;
    }

    private function getDate(): string
    {
        return $date = \date('Y.m.d H:i:s', \time());
    }
    
    protected function write(string $destination, string $message): void
    {
        $message = $this->sanitiseCredentials($message);

        $message .= \PHP_EOL;
        \file_put_contents($destination, $message, \FILE_APPEND);
    }
}
