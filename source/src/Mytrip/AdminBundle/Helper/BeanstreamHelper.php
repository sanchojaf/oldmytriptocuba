<?php
namespace Mytrip\AdminBundle\Helper;

use Symfony\Component\Templating\Helper\Helper;

class BeanstreamHelper 
{
    protected $options;
    
    public function __construct(array $options)
    {
        $this->options = $options;
    }
    
    public function getName()
    {
        return 'beanstream';
    }    
    
	
    public function getOption($name)
    {
        if (array_key_exists($name, $this->options)) {
            return $this->options[$name];
        }
        
        throw new Exception('Options does not exist');
    }
	
	
}


?>