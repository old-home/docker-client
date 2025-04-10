<?php

declare(strict_types=1);

use Graywings\DockerClient\Domain\Network\CIDRBlock;

require_once __DIR__ . '/../vendor/autoload.php';

var_dump('10.0.0.0/8');
var_dump(CIDRBlock::parse('10.0.0.0/8'));
var_dump(CIDRBlock::parse('10.0.0.0/8')->__toString());
var_dump('172.16.0.0/12');
var_dump(CIDRBlock::parse('172.16.0.0/12'));
var_dump(CIDRBlock::parse('172.16.0.0/12')->__toString());
var_dump('192.168.0.0/16');
var_dump(CIDRBlock::parse('192.168.0.0/16'));
var_dump(CIDRBlock::parse('192.168.0.0/16')->__toString());
var_dump('169.254.0.0/16');
var_dump(CIDRBlock::parse('169.254.0.0/16'));
var_dump(CIDRBlock::parse('169.254.0.0/16')->__toString());
var_dump('127.0.0.0/8');
var_dump(CIDRBlock::parse('127.0.0.0/8'));
var_dump(CIDRBlock::parse('127.0.0.0/8')->__toString());

var_dump('fc00::/7');
var_dump(CIDRBlock::parse('fc00::/7'));
var_dump(CIDRBlock::parse('fc00::/7')->__toString());
var_dump('fe80::/10');
var_dump(CIDRBlock::parse('fe80::/10'));
var_dump(CIDRBlock::parse('fe80::/10')->__toString());
var_dump('::1/128');
var_dump(CIDRBlock::parse('::1/128'));
var_dump(CIDRBlock::parse('::1/128')->__toString());
var_dump('ff00::/8');
var_dump(CIDRBlock::parse('ff00::/8'));
var_dump(CIDRBlock::parse('ff00::/8')->__toString());
