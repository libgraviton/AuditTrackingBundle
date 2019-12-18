<?php
/**
 * On server exception or error exception to be logged
 */
namespace Graviton\AuditTrackingBundle\Listener;

use Graviton\AuditTrackingBundle\Manager\ActivityManager;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

/**
 * Class ExceptionActivityListener
 * @package Graviton\AuditTrackingBundle\Listener
 *
 * @author   List of contributors <https://github.com/libgraviton/graviton/graphs/contributors>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://swisscom.ch
 */
class ExceptionActivityListener
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
     * Should not handle Validation Exceptions and only service exceptions
     *
     * @param ExceptionEvent $event Sf Event
     *
     * @return void
     */
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        $this->manager->registerExceptionEvent($exception);
    }
}
