<?php

namespace Enkatsu\PhpOscServer;
use Enkatsu\PhpOscParser\Parser;

class Server {
  static private $BUFFER_SIZE = 8192;
  private $parser;
  private $socket;
  private $host;
  private $port;
  
  function __construct($host, $port) {
    $this->parser = new Parser();
    $this->host = $host;
    $this->port = $port;
    $this->socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
    socket_bind($this->socket, $this->host, $this->port);
  }
  
  public function recieve() {
    // socket_recv($this->socket, $buf, self::$BUFFER_SIZE, 0);
    $buf = socket_read($this->socket, self::$BUFFER_SIZE, PHP_BINARY_READ);
    flush();
    if(is_null($buf)) return;
    $pos = 0;
    $buf = collect(str_split(bin2hex($buf), 8));
    $this->parser->parse($buf, $pos, $buf->count() - 1);
    return $this->parser->flush();
  }
}
