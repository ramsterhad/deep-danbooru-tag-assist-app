<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist\Application\Api\Shared\Adapter;

use CurlHandle;

use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Shared\Exception\AdapterApplicationException;
use function curl_error;
use function curl_init;
use function curl_setopt;
use function curl_close;

use const
    CURLOPT_AUTOREFERER,
    CURLOPT_HEADER,
    CURLOPT_HTTP_VERSION,
    CURLOPT_URL,
    CURLOPT_CUSTOMREQUEST,
    CURLOPT_USERPWD,
    CURLOPT_POSTFIELDS,
    CURLOPT_RETURNTRANSFER,
    CURLOPT_CONNECTTIMEOUT,
    CURLOPT_TIMEOUT;

final class CurlAdapter implements AdapterInterface
{
    private CurlHandle $connection;

    private string $response;

    public function init(): AdapterInterface
    {
        $this->connection = curl_init();
        return $this;
    }

    public function sendTo(string $url): AdapterInterface
    {
        $this->setOption(CURLOPT_URL, $url);
        return $this;
    }

    public function byMethod(string $method): AdapterInterface
    {
        $this->setOption(CURLOPT_CUSTOMREQUEST, 'PUT');
        return $this;
    }

    public function authenticateWith(string $username, string $password): AdapterInterface
    {
        $this->setOption(CURLOPT_USERPWD, $username . ':' . $password);
        return $this;
    }

    public function sendData(array $data): AdapterInterface
    {
        $this->setOption(CURLOPT_POSTFIELDS, $data);
        return $this;
    }

    public function requestTransferStatus(bool $requestIt): AdapterInterface
    {
        $this->setOption(CURLOPT_RETURNTRANSFER, $requestIt);
        return $this;
    }

    public function waitForFirstByte(int $seconds): AdapterInterface
    {
        $this->setOption(CURLOPT_CONNECTTIMEOUT, $seconds);
        return $this;
    }

    public function waitForFinishingTheRequest(int $seconds): AdapterInterface
    {
        $this->setOption(CURLOPT_TIMEOUT, $seconds);
        return $this;
    }

    /**
     * @throws AdapterApplicationException
     */
    public function execute(): AdapterInterface
    {
        if (($response = curl_exec($this->connection)) === false) {
            throw new AdapterApplicationException(curl_error($this->connection));
        }

        $this->response = $response;
        return $this;
    }

    public function activateAutoReferer(bool $activate): AdapterInterface
    {
        $this->setOption(CURLOPT_AUTOREFERER, $activate);
        return $this;
    }

    public function withHttpVersion(int $versionIdentifier): AdapterInterface
    {
        $this->setOption(CURLOPT_HTTP_VERSION, $versionIdentifier);
        return $this;
    }

    /**
     * @todo Version translator
     */
    public function includeHeaderInResponse(bool $include): AdapterInterface
    {
        $this->setOption(CURLOPT_HEADER, $include);
        return $this;
    }

    public function hangUp(): AdapterInterface
    {
        curl_close($this->connection);
        return $this;
    }

    public function getResponse(): string
    {
        return $this->response;
    }

    private function setOption(int $option, bool|int|string|array $value): void
    {
        curl_setopt($this->connection, $option, $value);
    }
}
