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

namespace Tygh\UpgradeCenter\Validators;

use Tygh\Registry;

/**
 * Upgrade validators: Check collisions
 */
class CheckFileValidator implements IValidator
{
    /**
     * Global App config
     *
     * @var array $config
     */
    protected $config = array();

    /**
     * Validator identifier
     *
     * @var array $name ID
     */
    protected $name = 'Demo upgrade: File checker';

    /**
     * Validate specified data by schema
     *
     * @param  array $schema  Incoming validator schema
     * @param  array $request Request data
     * @return array Validation result and Data to be displayed
     */
    public function check($schema, $request)
    {
        // You can perform any checking
        $file_to_be_created = $this->config['dir']['root'] . '/demo.up.txt';

        if (!file_exists($file_to_be_created)) {
            $result = false;
        } else {
            $result = true;
        }
        $data = 'Create <strong>' . $file_to_be_created . '</strong> file first to continue upgrade';

        return array($result, $data);
    }

    /**
     * Gets validator name (ID)
     *
     * @return string Name
     */
    public function getName()
    {
        return $this->name;
    }

    public function __construct()
    {
        $this->config = Registry::get('config');
    }
}
