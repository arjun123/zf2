<?php

namespace Application\Console\Command;

use RdnConsole\Command\AbstractCommand;
use RdnConsole\Command\ConfigurableInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Album\Model\Album;

class HelloWorld extends AbstractCommand implements ConfigurableInterface,
ServiceLocatorAwareInterface
{
    protected $servicelocator;

    public function configure()
    {
        $this->adapter
            ->setName('application:hello-world')
            ->setDescription('Test hello world command')
        ;
    }
    protected $serviceLocator;

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getServiceLocator()->getServiceLocator()
            ->get('Album\Model\AlbumTable');
        $album = new Album();

        $album->exchangeArray(array('artist' => 'Arjun', 'title' => 'Someone like you'));
        $em->saveAlbum($album);

        $output->writeln('Hello world!');
    }
}
