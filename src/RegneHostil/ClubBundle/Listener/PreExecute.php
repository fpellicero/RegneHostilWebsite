<?php

namespace RegneHostil\ClubBundle\Listener;
 
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
* 
*/
class PreExecute
{
	
	public function onKernelController(FilterControllerEvent $event) {
	    if(HttpKernelInterface::MASTER_REQUEST === $event->getRequestType()) {
	        $controllers = $event->getController();
	        if(is_array($controllers)) {
	            $controller = $controllers[0];

	            if(is_object($controller) && method_exists($controller, 'preExecute')) {
	                $controller->preExecute();
            	}
        	}
    	}
	}
}


?>