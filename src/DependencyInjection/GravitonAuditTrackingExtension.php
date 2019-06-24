<?php
/**
 * Sf Extension for Audit Bundle
 */
namespace Graviton\AuditTrackingBundle\DependencyInjection;

use Graviton\BundleBundle\DependencyInjection\GravitonBundleExtension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @author   List of contributors <https://github.com/libgraviton/graviton/graphs/contributors>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://swisscom.ch
 */
class GravitonAuditTrackingExtension extends GravitonBundleExtension
{
    /**
     * get path to bundles Resources/config dir
     *
     * @return string
     */
    public function getConfigDir()
    {
        return __DIR__ . '/../Resources/config';
    }
}
