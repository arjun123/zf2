<?php
namespace Application\Helper;

use Zend\View\Helper\AbstractHelper;

class LowerCase extends AbstractHelper
{
    protected $layout;
    public function __invoke()
    {

        return $this->layout = 'album';
    }
}

 ?>
