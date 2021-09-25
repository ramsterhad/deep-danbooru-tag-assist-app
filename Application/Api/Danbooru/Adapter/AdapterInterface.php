<?php

namespace Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Adapter;

interface AdapterInterface
{
    public function init(): AdapterInterface;

    public function sendTo(string $url): AdapterInterface;

    public function byMethod(string $method): AdapterInterface;

    public function authenticateWith(string $username, string $password): AdapterInterface;

    public function sendData(array $data): AdapterInterface;

    public function requestTransferStatus(bool $requestIt): AdapterInterface;

    public function waitForFirstByte(int $seconds): AdapterInterface;

    public function waitForFinishingTheRequest(int $seconds): AdapterInterface;

    public function execute(): AdapterInterface;

    public function hangUp(): AdapterInterface;

    public function getResponse(): string;
}