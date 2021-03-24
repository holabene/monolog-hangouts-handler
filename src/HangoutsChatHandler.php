<?php

namespace Nowyn\Monolog;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use GuzzleHttp\Client;
use Monolog\Formatter\FormatterInterface;

class HangoutsChatHandler extends AbstractProcessingHandler
{
    /**
     * @var string
     */
    private $webhookUrl;

    private $client;

    /**
     * @param  string            $webhookUrl Webhook URL
     * @param  string|null       $username   Name of a bot
     * @param  int               $level      The minimum logging level at which this handler will be triggered
     * @param  bool              $bubble
     * @param \GuzzleHttp\Client $client
     */

    public function __construct($webhookUrl, $username = null, $level = Logger::CRITICAL, $bubble = true)
    {
        parent::__construct($level, $bubble);

        $this->webhookUrl = $webhookUrl;
        $this->client = new Client();
    }

    /**
     * @return \Monolog\Formatter\FormatterInterface|HangoutsChatFormatter
     */
    public function getFormatter(): FormatterInterface
    {
        return new HangoutsChatFormatter();
    }

    /**
     * @param array $record
     *
     * @throws \RuntimeException
     */
    protected function write(array $record): void
    {
        $message = $this->getFormatter()
            ->format($record);
        $this->client->post($this->webhookUrl, [
            'headers' => ['Content-Type' => 'application/json'],
            'body' => $message,
            'curl' => [CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2],
            'http_errors' => false
        ]);
    }
}
