<?php
declare(strict_types=1);

namespace Xervice\Redis\Business\Model\Commands;

interface ProviderInterface
{
    public function provideCommands();
}
