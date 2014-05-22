<?php
/**
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright Copyright (c) 2014 Zend Technologies Ltd. (http://www.zend.com)
 * @author Kaloyan Raev <kaloyan.r@zend.com>
 */
namespace ZendStudioDevelopmentMode;

use Zend\Mvc\MvcEvent;
use Zend\Http\Header\Origin;
use Zend\Http\Request;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\EventManager\EventInterface;

class Module implements BootstrapListenerInterface
{

    public function onBootstrap(EventInterface $event)
    {
        $eventManager = $event->getApplication()->getEventManager();
        
        $eventManager->attach(MvcEvent::EVENT_FINISH, array(
            $this,
            'onFinish'
        ), 1000);
        
        $this->fixBrokenOriginHeader($event->getRequest());
    }

    public function onFinish(MvcEvent $e)
    {
        $response = $e->getResponse();
        if (! method_exists($response, 'getHeaders')) {
            return;
        }
        $response->getHeaders()->addHeaderLine('Cache-Control', 'no-cache');
    }

    public function fixBrokenOriginHeader(Request $request)
    {
        if (! method_exists($request, 'getHeaders') || ! method_exists($request, 'getServer')) {
            // Not an HTTP request
            return;
        }
        
        $origin = $request->getServer('HTTP_ORIGIN', false);
        if (! $origin) {
            // No Origin header; nothing to do
            return;
        }
        
        if ($origin !== 'file://') {
            // Origin header is likely formed correctly; nothing to do
            return;
        }
        
        $headers = $request->getHeaders();
        $headersArray = $headers->toArray();
        
        // Remove all headers
        $headers->clearHeaders();
        
        // Add the headers back one by one, but make sure the Origin headers is with the fixed value
        foreach ($headersArray as $key => $value) {
            if (strtolower($key) === 'origin') {
                $headers->addHeader(Origin::fromString('Origin: file:///'));
            } else {
                $headers->addHeaderLine($key, $value);
            }
        }
    }
}
