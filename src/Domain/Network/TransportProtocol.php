<?php

declare(strict_types=1);

namespace Graywings\DockerClient\Domain\Network;

enum TransportProtocol: string
{
    case TCP  = 'tcp';
    case UDP  = 'udp';
    case SCTP = 'sctp';
    case DCCP = 'dccp';
    case QUIC = 'quic';
}
