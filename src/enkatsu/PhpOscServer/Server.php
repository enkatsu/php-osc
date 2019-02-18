<?php

namespace Enkatsu\PhpOscServer;

use Tightenco\Collect\Support\Collection;
use Enkatsu\PhpOscParser\Parser;
use Enkatsu\PhpOscParser\Bundle;

class Server
{
  static private $BUFFER_SIZE = 8192;
  private $parser;
  private $socket;
  private $host;
  private $port;

  function __construct(string $host, int $port)
  {
    $this->parser = new Parser();
    $this->host = $host;
    $this->port = $port;
    $this->socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
    socket_bind($this->socket, $this->host, $this->port);
  }

  public function recieve(): ?Bundle
  {
    // socket_recv($this->socket, $buf, self::$BUFFER_SIZE, 0);
    $buf = socket_read($this->socket, self::$BUFFER_SIZE, PHP_BINARY_READ);
    flush();
    if(is_null($buf)) return null;
    $pos = 0;
    $buf = collect(str_split(bin2hex($buf), 8));
    return $this->parser->parse($buf, $pos, $buf->count() - 1);
  }
}
