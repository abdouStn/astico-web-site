<?php

namespace SNS\MembreBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class SNSMembreBundle extends Bundle
{
	public function getParent()
  	{
    		return 'FOSUserBundle';
  	}
}
