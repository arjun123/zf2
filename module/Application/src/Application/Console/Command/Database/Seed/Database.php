<?php

namespace Application\Console\Command\Database\Seed;

use RdnConsole\Command\AbstractCommand;
use RdnConsole\Command\ConfigurableInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Album\Model\Album;

class Database extends AbstractCommand implements ConfigurableInterface,
ServiceLocatorAwareInterface
{
    protected $servicelocator;

    public function configure()
    {
        $this->adapter
            ->setName('application:seed')
            ->setDescription('Seed the database with record')
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
        try {
            $this->AlbunTableSeed();
            $output->writeln('Success');
        } catch (Exception $e) {
            $output->writeln($e->getMessage());
        }
    }

    protected function AlbunTableSeed()
    {
        $dataSet = array(
            array(
                'artist' => 'Arjun',
                'title' => 'Who knows',
            ),
            array(
                'artist' => 'Adele',
                'title' => 'Someone like you',
            ),
            array(
                'artist' => 'Avril',
                'title' => 'Boy friend',
            ),
        );
        try {
            $em = $this->getServiceLocator()->getServiceLocator()
                ->get('Album\Model\AlbumTable');
            $sql = 'Truncate table album';
            $em->getAdapter()->driver->getConnection()->execute($sql);
            if (count($dataSet) != count($dataSet, COUNT_RECURSIVE)) {
                // echo "if";die;
                foreach ($dataSet as $data) {
                    // print_r($data);die;
                    $album = new Album();

                    $album->exchangeArray($data);
                    $em->saveAlbum($album);
                }
            } else {
                echo "lese";die;
                $album = new Album();

                $album->exchangeArray($dataSet);
                $em->saveAlbum($album);
            }
        } catch (Exception $e) {
            throw new Exception("Error Processing Request", 1);
        }
    }
}
