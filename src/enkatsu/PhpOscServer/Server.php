<?php

namespace Enkatsu\PhpOscServer;
use Enkatsu\PhpOscParser\Parser;

class Server {
  private $parser;
  private $BUFFER_SIZE = 8192;
  
  function __construct() {
    $this->parser = new Parser();
  }
  
  public function recieve($host, $port) {
    $socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
    socket_bind($socket, $host, $port);
    socket_recv($socket, $buf, $BUFFER_SIZE, 0);
    $this->parser->parse($buf);
    return $this->parser->messages;
  }
}
