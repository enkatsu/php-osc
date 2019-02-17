<?php

namespace Enkatsu\PhpOscServer;
use Enkatsu\PhpOscParser\Parser;

class Server {
  private $parser;
  private $BUFFER_SIZE = 8192;
  private $host;
  private $port;
  
  function __construct($host, $port) {
    $this->parser = new Parser();
    $this->host = $host;
    $this->port = $port;
  }
  
  public function recieve() {
    $socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
    socket_bind($socket, $this->host, $this->port);
    socket_recv($socket, $buf, $BUFFER_SIZE, 0);
    $pos = 0;
    $buf = collect(str_split($buf))->chunk(2);
    $this->parser->parse($buf, $pos, $buf->count());
    return $this->parser->getMessages();
  }
}
