<?php
/**
 * First request into graviton to be saved
 */
namespace Graviton\AuditTrackingBundle\Listener;

use Graviton\AuditTrackingBundle\Manager\ActivityManager;
use Symfony\Component\HttpKernel\Event\RequestEvent;

/**
 * Class RequestActivityListener
 * @package Graviton\AuditTrackingBundle\Listener
 *
 * @author   List of contributors <https://github.com/libgraviton/graviton/graphs/contributors>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://swisscom.ch
 */
class RequestActivityListener
{
    /** @var ActivityManager $manager */
    private $manager;

    /**
     * RequestActivityListener constructor.
     * @param ActivityManager $activityManager Business logic
     */
    public function __construct(ActivityManager $activityManager)
    {
        $this->manager = $activityManager;
    }

    /**
     * When request is received from user.
     *
     * @param RequestEvent $event Sf Event
     * @return void
     */
    public function onKernelRequest(RequestEvent $event)
    {
        if ($event->isMasterRequest()) {
            $this->manager->registerRequestEvent($event->getRequest());
        }
    }
}
