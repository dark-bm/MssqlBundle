<?php

/*
 * This file is part of the Symfony framework.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace SCM\MssqlBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Doctrine\DBAL\Types\Type;

class SCMMssqlBundle extends Bundle
{
    public function boot()
    {
        // Register custom data types
        if(!Type::hasType('uniqueidentifier')) {
            Type::addType('uniqueidentifier', 'SCM\MssqlBundle\Types\UniqueidentifierType');
        }
        if(!Type::hasType("okpo")) {
            Type::addType("okpo", 'SCM\MssqlBundle\Types\Okpo');
        }

        Type::overrideType('date', 'SCM\MssqlBundle\Types\DateType');
        Type::overrideType('datetime', 'SCM\MssqlBundle\Types\DateTimeType');
    }
}
