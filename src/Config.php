<?php
/**
 * Created by Marcin.
 * Date: 12.11.2018
 * Time: 19:43
 */

namespace mrcnpdlk\Lib\Lank3;


use Curl\Curl;
use Psr\Log\NullLogger;

/**
 * Class Config
 *
 * @package mrcnpdlk\Lib\Lank3
 */
class Config
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;
    /**
     * @var \Psr\SimpleCache\CacheInterface|null
     */
    private $cache;
    /**
     * @var string
     */
    private $host;
    /**
     * @var string|null
     */
    private $user;
    /**
     * @var string|null
     */
    private $password;

    public function __construct(string $host, string $user = null, string $password = null)
    {
        $this->logger   = new NullLogger();
        $this->cache    = null;
        $this->host     = $host;
        $this->user     = $user;
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getBasicUrl(): string
    {
        return sprintf('http://%s', $this->host);
    }

    /**
     * @return \Curl\Curl
     * @throws \ErrorException
     */
    public function getCurl(): Curl
    {
        $oCurl = new Curl();
        if ($this->user && $this->password) {
            $oCurl->setBasicAuthentication($this->user, $this->password);
        }

        return $oCurl;

    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @return null|string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @return null|string
     */
    public function getUser(): ?string
    {
        return $this->user;
    }
}
