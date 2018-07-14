<?php
declare(strict_types=1);

namespace Xervice\Redis\Commands;

interface ProviderInterface
{
    public function provideCommands();
}
