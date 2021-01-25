<?php
/* 
 * @copyright (C) 2021 Michiel Keijts, Normit
 * 
 * Parses YAML as template and returns a set of options, including a message
 */

namespace CakeNotifications\Helper;

use Cake\Core\InstanceConfigTrait;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;
use Cake\Log\Log;

class NotificationTemplateParser {
    use InstanceConfigTrait;
    
    /**
     * Holder for the YAML template
     * @var string
     */
    private $_template;
    
    /**
     * Holder for the YAML template viewVars
     * @var array
     */
    private $_viewVars;
    
    /**
     * Holder for the renderd message
     * @var string
     */
    private $_message;
    
    /**
     * Check if is rendered
     * @var bool
     */
    private $_is_rendered = FALSE;
    
    /**
     * Default config for InstanceConfig
     * @var array
     */
    protected $_defaultConfig = [];


    /**
     * Creates the NotificationTemplateParser
     * @param string $yaml
     * @param array $viewVars
     */
    public function __construct(string $yaml = "", array $viewVars = []) {
        $this->setTemplate($yaml);
        $this->setViewVars($viewVars);
    }
    
    /**
     * Sets the template
     * @param string $yaml
     */
    public function setTemplate(string $yaml) 
    {
        $this->_template = $yaml;
        
        $this->parseYaml();
    }
    
    /**
     * Sets the viewVars
     * @param array $viewVars
     * @return type
     */
    public function setViewVars(array $viewVars) 
    {
        return $this->_viewVars = $viewVars;
    }
    
    /**
     * Parses the template according to the parameters
     * It returns the message part of the template
     * @param array $options the initial set of options
     * @return array
     */
    public function getMessage(array $options = []) : string
    {
        if ($this->_is_rendered) {
            return $this->_message;
        }

        return $this->_message = $this->renderMessage($this->getConfig('message'));
    }
    
    /**
     * Renders the content of message
     * @param string $message
     * @return string
     */
    private function renderMessage($message) : string
    {
        $this->_is_rendered = TRUE;
        
        $output = preg_replace_callback(
				'/\$([A-Za-z_][\w\.]+)/',	//Pattern: all variables, including dots, for arrays
                function ($matches) {
					list($original, $var) = $matches;
                    return isset($this->_viewVars[$var]) ? $this->_viewVars[$var] : $var;
                },
                $message
        );
        
        
        return $output;
    }
    
    /**
     * Parses the template according to the parameters
     * It returns an array, to be used in the $options of the Notification
     * @param array $options the initial set of options
     * @return array
     */
    public function getOptions(array $options = []) : array
    {
        $config = $this->getConfig();
        unset($config['message']);
        
        return $options + $config;
    }
    
    /**
     * Parses the Yaml in the notification templates
     */
    private function parseYaml() 
    {
        $config = [];
        try {
            $config = Yaml::parse($this->_template);
        } catch (ParseException $exception) {
            Log::error('Unable to parse the YAML string: %s', $exception->getMessage());
        }
        
        if (!isset($config['message']) || !isset($config['recipients'])) {
            Log::error('Template without a message or recipient!');
        }
        
        $this->setConfig($config);
    }
}