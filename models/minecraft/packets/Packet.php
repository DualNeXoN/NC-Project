<?php

namespace MinecraftServerStatus\Packets;

class Packet {

    protected $packetID;

    protected $data;

    public function __construct($packetID) {
        $this->packetID = $packetID;
        $this->data = pack('C', $packetID);
    }

    public function addSignedChar($data) {
        $this->data .= pack('c', $data);
    }

    public function addUnsignedChar($data) {
        $this->data .= pack('C', $data);
    }

    public function addSignedShort($data) {
        $this->data .= pack('s', $data);
    }

    public function addUnsignedShort($data) {
        $this->data .= pack('S', $data);
    }

    public function addString($data) {
        $this->data .= pack('C', strlen($data));
        $this->data .= $data;
    }

    public function send($socket) {
        $this->data = pack('C', strlen($this->data)) . $this->data;
        socket_send($socket, $this->data, strlen($this->data), 0);
    }
}

class HandshakePacket extends Packet {

    public function __construct($host, $port, $protocol, $nextState) {
        parent::__construct(0);
        $this->addUnsignedChar($protocol);
        $this->addString($host);
        $this->addUnsignedShort($port);
        $this->addUnsignedChar($nextState);
    }
}

class PingPacket extends Packet {

    public function __construct() {
        parent::__construct(0);
    }
}
