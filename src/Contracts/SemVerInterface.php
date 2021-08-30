<?php

namespace aliirfaan\LaravelSimpleForceUpdate\Contracts;

interface SemVerInterface
{    
    /**
     * greaterThan
     *
     * @param  string $semVer1
     * @param  string $semVer2
     * @return bool
     * @throws ComparisonException if semVer cannot be parsed
     */
    public function greaterThan($semVer1, $semVer2);
    
    /**
     * equals
     *
     * @param  string $semVer1
     * @param  string $semVer2
     * @return bool
     * @throws ComparisonException if semVer cannot be parsed
     */
    public function equals($semVer1, $semVer2);
        
    /**
     * smallerThan
     *
     * @param  string $semVer1
     * @param  string $semVer2
     * @return bool
     * @throws ComparisonException if semVer cannot be parsed
     */
    public function smallerThan($semVer1, $semVer2);
}