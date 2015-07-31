<?php

namespace Application\Console\Command\Generate;

use RdnConsole\Command\AbstractCommand;
use RdnConsole\Command\ConfigurableInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Filesystem\Filesystem;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Generator\DocBlockGenerator;
use Zend\Code\Generator\PropertyGenerator;
use Zend\Code\Generator\FileGenerator;

use Zend\CodeGenerator\Php as CodeGen;

use Album\Model\Album;

class GenerateClass extends AbstractCommand implements ConfigurableInterface,
ServiceLocatorAwareInterface
{
    protected $servicelocator;

    public function configure()
    {
        $this->adapter
            ->setName('application:generate-class')
            ->setDescription('Generate Class')
            ->addArgument(
                'classname',
                InputArgument::REQUIRED,
                'Class name that should be created'
            )

            // ->addArgument(
            //     'path',
            //     InputArgument::OPTIONAL,
            //     'Destination where file would be created'
            // )
            ->addArgument(
                'namespace',
                InputArgument::OPTIONAL,
                'Namespace for class'
            )
            ->addArgument(
                'properties',
                InputArgument::IS_ARRAY,
                'Properities of class(should be enter like property:scope)'
            )

            ->addOption(
               'path',
               null,
               InputOption::VALUE_NONE,
               'If set, the file  will created inside give destination'
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
        //read input from console
        $className = $input->getArgument('classname');
        //read array input from console
        $properties = $input->getArgument('properties');
        var_dump($input->getOption('path'));die;
        // if (empty($input->getOption('path'))) {
        //     $path = getcwd() .'/module/Application/src/Application/Entity';
        // } else {
        //     $path = $input->getOption('path');
        // }
        var_dump($path);die;
        //initialize the file system class of symfony
        $fs = new Filesystem();
        $isExists = $fs->exists($path.'/'.$className.'.php');
        if ($isExists) {
            $output->writeln("<error>$className class already exists.</error>");
            return false;
        }
        // echo $className;
        // print_r($inputValue);die;

        $classObject      = new ClassGenerator();
        $docblock = DocBlockGenerator::fromArray(array(
            'shortDescription' => 'Sample generated class',
            'longDescription'  => 'This is a class generated with Zend\Code\Generator.',
            'tags'             => array(
                array(
                    'name'        => 'version',
                    'description' => '$Rev:$',
                ),
                array(
                    'name'        => 'license',
                    'description' => 'New BSD',
                ),
            ),
        ));
        //set class name
        $classObject->setNamespaceName('Application');
        $classObject->addUse('Doctrine\ORM\Mapping as ORM');
        $classObject->setName($className)
            ->setDocblock($docblock);
        foreach ($properties as $property) {
            $values = explode(':', $property);
            $flag = PropertyGenerator::FLAG_PUBLIC;
            if ($values[1] == 'private') {
                $flag = PropertyGenerator::FLAG_PRIVATE;
            } else if ($values[1] == 'protected') {
                $flag = PropertyGenerator::FLAG_PROTECTED;
            } else if ($values[1] == 'static') {
                $flag = PropertyGenerator::FLAG_STATIC;
            }
            $classObject->addProperties(array(
                 array($values[0], null, $flag),
                //  array('baz',  'bat', PropertyGenerator::FLAG_PUBLIC)
             ));
        }
        //create constant
        /*
        $classObject->addConstants(array(
                 array('bat',  'foobarbazbat')
        ));
        */
        //generate file
        $file = FileGenerator::fromArray(array(
            'classes'  => array($classObject),
            'docblock' => DocBlockGenerator::fromArray(array(
                'shortDescription' => $className .' class file',
                'longDescription'   => null,
                'tags'             => array(
                    array(
                        'name'        => 'license',
                        'description' => 'New BSD',
                    ),
                ),
            )),
        ));

        $code = $file->generate();
        file_put_contents($path.'/'.$className.'.php', $code);

        $output->writeln("<info>$className class successfully generated.</info>");
    }
}
