<?php

namespace aliirfaan\LaravelSimpleForceUpdate\Services;

use aliirfaan\LaravelSimpleForceUpdate\Contracts\SemVerInterface;
use aliirfaan\LaravelSimpleForceUpdate\Exceptions\ComparisonException;
use Naneau\SemVer\Parser;
use Naneau\SemVer\Compare;

class SemVerService implements SemVerInterface
{
    public function greaterThan($semVer1, $semVer2)
    {
        try {
            return Compare::greaterThan(
                Parser::parse($semVer1),
                Parser::parse($semVer2)
            );
        } catch (\InvalidArgumentException $e) {
            throw new ComparisonException($e->getMessage());
        }
    }

    public function equals($semVer1, $semVer2)
    {
        try {
            return Compare::equals(
                Parser::parse($semVer1),
                Parser::parse($semVer2)
            );
        } catch (\InvalidArgumentException $e) {
            throw new ComparisonException($e->getMessage());
        }
    }
    
    public function smallerThan($semVer1, $semVer2)
    {
        try {
            return Compare::smallerThan(
                Parser::parse($semVer1),
                Parser::parse($semVer2)
            );
        } catch (\InvalidArgumentException $e) {
            throw new ComparisonException($e->getMessage());
        }
    }
}