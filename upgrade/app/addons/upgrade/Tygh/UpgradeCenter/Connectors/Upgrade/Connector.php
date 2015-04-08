<?php
/***************************************************************************
 *                                                                          *
 *   (c) 2004 Vladimir V. Kalynyak, Alexey V. Vinokurov, Ilya M. Shalnev    *
 *                                                                          *
 * This  is  commercial  software,  only  users  who have purchased a valid *
 * license  and  accept  to the terms of the  License Agreement can install *
 * and use this program.                                                    *
 *                                                                          *
 ****************************************************************************
 * PLEASE READ THE FULL TEXT  OF THE SOFTWARE  LICENSE   AGREEMENT  IN  THE *
 * "copyright.txt" FILE PROVIDED WITH THIS DISTRIBUTION PACKAGE.            *
 ****************************************************************************/

namespace Tygh\UpgradeCenter\Connectors\Upgrade;// "Upgrage" - is an add-on name.
                                                // If your add-on has "my_changes" name, so namespace will look like: Tygh\UpgradeCenter\MyChanges
use Tygh\Addons\SchemesManager;
use Tygh\Registry;
use Tygh\UpgradeCenter\Connectors\IConnector as UCInterface;

/**
 * Core upgrade connector interface
 */
class Connector implements UCInterface
{
    /**
     * Add-on connector settings
     *
     * @var array $settings
     */
    protected $settings = array();

    /**
     * Prepares request data for request to Upgrade server (Check for the new upgrades)
     *
     * @return array Prepared request information
     */
    public function getConnectionData()
    {
        $request_data = array(
            'method' => 'get',
            'url' => $this->settings['upgrade_server'],
            'data' => array(
                'dispatch' => 'updates.check',
                'product_version' => PRODUCT_VERSION,
                'edition' => PRODUCT_EDITION,
                'addon_version' => $this->settings['addon_version'],
                'some_custom_field' => TIME,
                'hello' => 'world',
            ),
            'headers' => array(
                'Content-type: text/xml'
            )
        );

        return $request_data;
    }

    /**
     * Processes the response from the Upgrade server.
     *
     * @param  string $response            server response
     * @param  bool   $show_upgrade_notice internal flag, that allows/disallows Connector displays upgrade notice (A new version of [product] available)
     * @return array  Upgrade package information or empty array if upgrade is not available
     */
    public function processServerResponse($response, $show_upgrade_notice)
    {
        // Make a fake response
        // Theoretically we should not get response if add-on version more than 1.2, so workaround here
        if ($this->settings['addon_version'] == '1.1') {

            $response = '<?xml version="1.0"?>
            <upgrade>
                <available>Y</available>
                <package>
                    <file>upgrade_from_1.1_to_1.2.tgz</file>
                    <name>Upgrade for the "Upgrade add-on" (from 1.1 to 1.2)</name>
                    <description>New version of the addon!

                        Changelog:
                        - PHP warning was displayed when calculating cart. Fixed.
                        - Taxes no longer available</description>
                    <from_version>1.1</from_version>
                    <to_version>1.2</to_version>
                    <timestamp>1412366886</timestamp>
                    <size>18123</size>
                    <custom_field>Hello CS-Cart</custom_field>
                    <my_sha_key>123</my_sha_key>
                </package>
            </upgrade>';
        } else {
            $response = '<?xml version="1.0"?><upgrade><available>N</available></upgrade>';
        }

        $parsed_data = array();
        $data = simplexml_load_string($response);

        if ((string) $data->available == 'Y') {
            $parsed_data = array(
                'file' => (string) $data->package->file,
                'name' => (string) $data->package->name,
                'description' => (string) $data->package->description,
                'from_version' => (string) $data->package->from_version,
                'to_version' => (string) $data->package->to_version,
                'timestamp' => (int) $data->package->timestamp,
                'size' => (int) $data->package->size,
                'my_very_important_field' => (string) $data->package->my_sha_key,
                'custom_field' => (string) $data->package->custom_field,
            );

            if ($show_upgrade_notice) {
                fn_set_notification('W', __('notice'), __('text_upgrade_available', array(
                    '[product]' => 'Upgade add-on',
                    '[link]' => fn_url('upgrade_center.manage')
                )), 'S');
            }
        }

        return $parsed_data;
    }

    /**
     * Downloads upgrade package from the Upgade server
     *
     * @param  array  $schema       Package schema
     * @param  string $package_path Path where the upgrade pack must be saved
     * @return bool   True if upgrade package was successfully downloaded, false otherwise
     */
    public function downloadPackage($schema, $package_path)
    {
        // Make some checking
        if ($schema['my_very_important_field'] == '123' && !empty($schema['custom_field'])) {
            // Fake downloading file
            $fake_path = Registry::get('config.dir.addons') . 'upgrade/fake_upgrade/pack.tgz';

            $result = fn_copy($fake_path, $package_path);
            $message = $result ? '' : __('failed');

            return array($result, $message);
        } else {
            return array(false, __('sha_key_is_invalid'));
        }

    }

    public function __construct()
    {
        // Initial settings
        $addon_scheme = SchemesManager::getScheme('upgrade');

        $this->settings = array(
            'upgrade_server' => 'http://demo.cs-cart.com/index.php',
            'addon_version' => $addon_scheme->getVersion()
        );
    }
}
