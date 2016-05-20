<?php
/**
 * 
 *
 * @author      Ken Golovin <ken@webplanet.co.nz>
 */

namespace SCM\MssqlBundle\Types;

class SCMDateTime extends \DateTime
{
    public function __toString()
    {
        return $this->format('Y-m-d');
    }
}

