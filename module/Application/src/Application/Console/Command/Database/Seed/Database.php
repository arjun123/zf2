<?php

namespace Application\Console\Command\Database\Seed;

use RdnConsole\Command\AbstractCommand;
use RdnConsole\Command\ConfigurableInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Filesystem\Filesystem;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Album\Model\Album;

class Database extends AbstractCommand implements ConfigurableInterface,
ServiceLocatorAwareInterface
{
    protected $servicelocator;
    protected $filesystem;

    public function configure()
    {
        $this->adapter
            ->setName('application:seed')
            ->setDescription('Seed the database with record')
            ->addArgument(
                'name',
                InputArgument::OPTIONAL,
                'Who do you want to greet?'
            )
            ->addOption(
               'yell',
               null,
               InputOption::VALUE_NONE,
               'If set, the task will yell in uppercase letters'
            )
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
            $name = $input->getArgument('name');

            if ($name) {
                $em = $this->getServiceLocator()->getServiceLocator()
                    ->get('Album\Model\AlbumTable');
                    $em->getAdapter()->getDriver()->getConnection()->beginTransaction();
                    $em->getAdapter()->getDriver()->getConnection()->rollback();
                $output->writeln('<info>Rollback Successfully</info>');
            } else {
                // $text = 'Hello';
                // $this->AlbunTableSeed();
                // $output->writeln('<info>Success</info>');
                $path = ''
                $filesystem = new \Filesystem();
                $filesystem->dumpFile($path, $license.PHP_EOL);

                $output->writeln(sprintf('Created the file %s', $path));
            }
        } catch (Exception $e) {
            $output->writeln('<error>'.$e->getMessage().'</error>');
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
                foreach ($dataSet as $data) {
                    $album = new Album();

                    $album->exchangeArray($data);
                    $em->saveAlbum($album);
                }
            } else {
                $album = new Album();

                $album->exchangeArray($dataSet);
                $em->saveAlbum($album);
            }
        } catch (Exception $e) {
            throw new Exception("Error Processing Request", 1);
        }
    }
}
