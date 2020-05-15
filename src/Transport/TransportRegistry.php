<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         3.7.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace CakeNotifications\Transport;

use BadMethodCallException;
use Cake\Core\App;
use Cake\Core\ObjectRegistry;
use RuntimeException;
use CakeNotifications\Transport\AbstractTransport;

/**
 * An object registry for mailer transports.
 */
class TransportRegistry extends ObjectRegistry
{
    /**
     * Resolve a mailer tranport classname.
     *
     * Part of the template method for Cake\Core\ObjectRegistry::load()
     *
     * @param string|\Cake\Mailer\AbstractTransport $class Partial classname to resolve or transport instance.
     * @return string|false Either the correct classname or false.
     */
    protected function _resolveClassName(string $class): ?string
    {
        if (is_object($class)) {
            return $class;
        }

        $className = App::className($class, 'Transport', 'Transport');

        return $className;
    }

    /**
     * Throw an exception when the requested object name is missing.
     *
     * @param string $class The class that is missing.
     * @param string|null $plugin The plugin $class is missing from.
     * @return void
     * @throws \Exception
     */
    protected function _throwMissingClassError(string $class, ?string $plugin): void
    {
        throw new BadMethodCallException(sprintf('Notification transport %s is not available.', $class));
    }

    /**
     * Create an instance of a given classname.
     *
     * This method should construct and do any other initialization logic
     * required.
     *
     * @param string|object $class The class to build.
     * @param string $alias The alias of the object.
     * @param array $config The Configuration settings for construction
     * @return object
     * @psalm-param string|TObject $class
     * @psalm-return TObject
     */
    protected function _create($class, string $alias, array $config)
    {
        $instance = null;

        if (is_object($class)) {
            $instance = $class;
        }

        if (!$instance) {
            $instance = new $class($config);
        }

        if ($instance instanceof AbstractTransport) {
            return $instance;
        }

        throw new RuntimeException(
            'Notification transports must use CakeNotifications\Transport\AbstractTransport as a base class.'
        );
    }
}
