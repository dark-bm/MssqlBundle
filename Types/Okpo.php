<?php
namespace SCM\MssqlBundle\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class Okpo extends Type
{
    const OKPO = 'okpo';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getVarcharTypeDeclarationSQL($fieldDeclaration);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return ($value === null) ? null : implode('|',$value);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return ($value === null) ? null : array_values(array_filter(array_unique(explode('|',$value))));
    }

    public function getName()
    {
        return self::OKPO;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }

}