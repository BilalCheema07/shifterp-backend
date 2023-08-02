<?php
namespace App\Services\Http;

use App\Services\Auth\ApiToken;
use Illuminate\Http\Request;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;
use Psr\Log\LogLevel;

class RequestLogger implements LoggerInterface {

    use LoggerTrait;

    /**
     * @var Logger;
     */
    protected $logger;

    protected static $levelMap = [
        LogLevel::DEBUG     => Logger::DEBUG,
        LogLevel::INFO      => Logger::INFO,
        LogLevel::NOTICE    => Logger::NOTICE,
        LogLevel::WARNING   => Logger::WARNING,
        LogLevel::ERROR     => Logger::ERROR,
        LogLevel::CRITICAL  => Logger::CRITICAL,
        LogLevel::ALERT     => Logger::ALERT,
        LogLevel::EMERGENCY => Logger::EMERGENCY,
    ];

    /**
     * TODO: This method isn't writing to the log as expected.  Need to fix.
     *
     * @param  mixed  $level
     * @param  string  $message
     * @param  array  $context
     */
    public function log($level, $message, array $context = array())
    {
        if (empty($this->logger)) {
            $this->logger = new Logger('apilog');
        }

        /** @var Request $request */
        $request = app('request');

        if ($request) {
            $bearerToken = $request->bearerToken();
            $apiToken = ApiToken::fromToken($bearerToken);

            if ($apiToken->isValid()) {

                // TODO: This isn't working.  Need to investigate.
                $this->logger->addRecord(self::$levelMap[$level], $message, $context);
            }
        }



    }

    public function __destruct()
    {
        if (!empty($this->logger)) {
            $this->logger->close();
        }
    }
}
