<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\IgnoreDNTEnabledByDefault;

use Piwik\Tracker\Request;

class IgnoreDNTEnabledByDefault extends \Piwik\Plugin
{
    public function registerEvents() {
        return array(
            'PrivacyManager.shouldIgnoreDnt' => 'handleDNTHeader'
        );
    }

    public function isTrackerPlugin() {
        return true;
    }

    public function handleDNTHeader(&$shouldIgnore) {
        $shouldIgnore = $this->isUserAgentWithDoNotTrackAlwaysEnabled();
    }

    /**
     * @return bool
     */
    public function isUserAgentWithDoNotTrackAlwaysEnabled() {
        $request = new Request($_REQUEST);
        $userAgent = $request->getUserAgent();
        $browsersWithDnt = $this->getBrowsersWithDNTAlwaysEnabled();
        foreach ($browsersWithDnt as $userAgentBrowserFragment) {
            if (stripos($userAgent, $userAgentBrowserFragment) !== false) {
                return true;
            }
        }
        return false;
    }

    /**
     * Some browsers have DNT enabled by default. For those we will ignore DNT and always track those users.
     *
     * @return array
     */
    protected function getBrowsersWithDNTAlwaysEnabled() {
        return array(
            // IE
            'MSIE',
            'Trident',
            // Maxthon
            'Maxthon',

            // Epiphany - https://github.com/matomo-org/matomo/issues/8682
            'Epiphany',
        );
    }
}
