<?php

declare(strict_types=1);

namespace Graywings\DockerClient\Domain\Network;

/**
 * Enum TransportProtocol
 *
 * This enum represents the transport protocols that can be used in network
 * communications. It provides a strongly-typed way to handle protocol types,
 * ensuring consistency and reducing the risk of errors when working with
 * network configurations.
 *
 * The supported protocols include:
 * - `TCP`: Transmission Control Protocol, a reliable, connection-oriented protocol.
 * - `UDP`: User Datagram Protocol, a connectionless, lightweight protocol.
 * - `SCTP`: Stream Control Transmission Protocol, designed for message-oriented communications.
 * - `DCCP`: Datagram Congestion Control Protocol, used for congestion-controlled communications.
 * - `QUIC`: Quick UDP Internet Connections, a modern protocol for fast and secure communications.
 */
enum TransportProtocol: string
{
    case TCP  = 'tcp';
    case UDP  = 'udp';
    case SCTP = 'sctp';
    case DCCP = 'dccp';
    case QUIC = 'quic';
}
