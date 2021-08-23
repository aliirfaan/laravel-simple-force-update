<?php

namespace aliirfaan\LaravelSimpleForceUpdate\Services;

use aliirfaan\LaravelSimpleForceUpdate\Contracts\SemVerInterface;
use Naneau\SemVer\Parser;
use Naneau\SemVer\Compare;

class SemVerService implements SemVerInterface
{
    public function greaterThan($semVer1, $semVer2)
    {
        return Compare::greaterThan(
            Parser::parse($semVer1),
            Parser::parse($semVer2)
        );
    }

    public function equals($semVer1, $semVer2)
    {
        return Compare::equals(
            Parser::parse($semVer1),
            Parser::parse($semVer2)
        );
    }
    
    public function smallerThan($semVer1, $semVer2)
    {
        return Compare::smallerThan(
            Parser::parse($semVer1),
            Parser::parse($semVer2)
        );
    }
}