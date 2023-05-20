<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector;
use Rector\Config\RectorConfig;
use Rector\Core\ValueObject\PhpVersion;
use Rector\Php54\Rector\Array_\LongArrayToShortArrayRector;
use Rector\Set\ValueObject\LevelSetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/lib',
    ]);

    $rectorConfig->skip([
        '/lib/vendors/*',
        '/lib/config/*',
        '/lib/updates/*',
        LongArrayToShortArrayRector::class
    ]);
    // register a single rule
    $rectorConfig->rule(InlineConstructorDefaultToPropertyRector::class);
    $rectorConfig->phpVersion(PhpVersion::PHP_74);
    // define sets of rules
        $rectorConfig->sets([
            LevelSetList::UP_TO_PHP_74
        ]);
};
