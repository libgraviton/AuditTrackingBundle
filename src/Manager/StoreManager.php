<?php
/**
 * To manage the data to be saved into DB as last thing to do.
 */
namespace Graviton\AuditTrackingBundle\Manager;

use Doctrine\ODM\MongoDB\DocumentManager;
use Graviton\AuditTrackingBundle\Document\AuditTracking;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use Graviton\LinkHeaderParser\LinkHeader;
use Graviton\LinkHeaderParser\LinkHeaderItem;
use Graviton\SecurityBundle\Entities\SecurityUser;
use Graviton\SecurityBundle\Service\SecurityUtils;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Router;

/**
 * Class StoreManager
 * @package Graviton\AuditTrackingBundle\Manager
 *
 * @author   List of contributors <https://github.com/libgraviton/graviton/graphs/contributors>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://swisscom.ch
 */
class StoreManager
{
    const AUDIT_HEADER_LINK = 'audit';

    /** @var ActivityManager */
    private $activityManager;

    /** @var Logger */
    private $logger;

    /** @var DocumentManager */
    private $documentManager;

    /** @var SecurityUtils */
    private $securityUtils;

    /** @var Router */
    private $router;

    /**
     * StoreManager constructor.
     * @param ActivityManager $activityManager Main activity manager
     * @param Logger          $logger          Monolog log service
     * @param ManagerRegistry $doctrine        Doctrine document mapper
     * @param SecurityUtils   $securityUtils   Sf Auth token storage
     * @param Router          $router          Sf Router component
     */
    public function __construct(
        ActivityManager $activityManager,
        Logger $logger,
        ManagerRegistry $doctrine,
        SecurityUtils $securityUtils,
        Router $router
    ) {
        $this->activityManager = $activityManager;
        $this->logger = $logger;
        $this->documentManager = $doctrine->getManager();
        $this->securityUtils = $securityUtils;
        $this->router = $router;
    }

    /**
     * Save data to DB
     * onKernelResponse
     *
     * @param FilterResponseEvent $event Sf fired kernel event
     *
     * @return void
     */
    public function persistEvents(FilterResponseEvent $event)
    {
        // No events or no user.
        if (!($events = $this->activityManager->getEvents())) {
            $this->logger->debug('AuditTracking:exit-no-events');
            return;
        }

        // No events or no user.
        if (!$this->securityUtils->isSecurityUser()) {
            $this->logger->debug('AuditTracking:exit-no-user');
            return;
        }

        // Check if we wanna log test calls
        if (!$this->activityManager->getConfigValue('log_test_calls', 'bool')) {
            if (!$this->securityUtils->isSecurityUser()
                || !$this->securityUtils->hasRole(SecurityUser::ROLE_CONSULTANT)) {
                $this->logger->debug('AuditTracking:exit-no-real-user');
                return;
            }
        }

        $thread = $this->securityUtils->getRequestId();
        $response = $event->getResponse();

        // If request is valid we save it or we do not depending on the exceptions exclude policy
        if (!$this->activityManager->getConfigValue('log_on_failure', 'bool')) {
            $excludedStatus = $this->activityManager->getConfigValue('exceptions_exclude', 'array');
            if (!$response->isSuccessful()
                && !in_array($response->getStatusCode(), $excludedStatus)) {
                $this->logger->debug('AuditTracking:exit-on-failure:'.$thread.':'.json_encode($events));
                return;
            }
        }

        $username = $this->securityUtils->getSecurityUsername();

        $saved = false;
        foreach ($events as $event) {
            if (!($saved = $this->trackEvent($event, $thread, $username))) {
                break;
            }
        }

        // Set Audit header information
        if ($saved) {
            $url = $this->router->generate('graviton.audit.rest.default.all', [], UrlGeneratorInterface::ABSOLUTE_URL);
            $url .= sprintf('?eq(thread,string:%s)&sort(-createdAt)', $thread);

            // append rel=self link to link header
            $linkHeader = LinkHeader::fromString($response->headers->get('Link', null));
            $linkHeader->add(new LinkHeaderItem($url, self::AUDIT_HEADER_LINK));

            // overwrite link headers with new headers
            $response->headers->set('Link', (string) $linkHeader);
        }
    }

    /**
     * Save the event to DB
     *
     * @param AuditTracking $event    Performed by user
     * @param string        $thread   The thread ID
     * @param string        $username User connected name
     * @return bool
     */
    private function trackEvent($event, $thread, $username)
    {
        // Request information
        $event->setThread($thread);
        $event->setUsername($username);
        $saved = true;

        try {
            $this->documentManager->persist($event);
            $this->documentManager->flush($event);
        } catch (\Exception $e) {
            $this->logger->error('AuditTracking:persist-error:'.$thread.':'.json_encode($event));
            $saved = false;
        }

        return $saved;
    }
}