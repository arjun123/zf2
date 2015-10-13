<?php

namespace Album\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Album\Model\Album;
use Album\Form\AlbumForm;
use Zend\Db\Sql\Sql;

class AlbumController extends AbstractActionController
{
    protected $albumTable;
    public function __construct() {
        $viewModel = new viewModel();
        $viewModel->setTemplate('layout/album');
    }
    public function indexAction()
    {
        move_uploaded_file('test.php', '../Form/');

        return new ViewModel(array(
            'albums' => $this->getAlbumTable()->fetchAll(),
        ));
    }

    public function addAction()
    {
        $form = new AlbumForm();
        $form->get('submit')->setAttribute('value', 'Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $album = new Album();
            $form->setInputFilter($album->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $album->exchangeArray($form->getData());
                $this->getAlbumTable()->saveAlbum($album);

                // Redirect to list of albums
                return $this->redirect()->toRoute('album');
            }
        }

        return array('form' => $form);
    }

    public function editAction()
    {
        $id = (int)$this->params('id');
        if (!$id) {
            return $this->redirect()->toRoute('album', array('action'=>'add'));
        }
        $album = $this->getAlbumTable()->getAlbum($id);

        $form = new AlbumForm();
        $form->bind($album);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $this->getAlbumTable()->saveAlbum($album);

                // Redirect to list of albums
                return $this->redirect()->toRoute('album');
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
        );
    }

    public function deleteAction()
    {
        $id = (int)$this->params('id');
        if (!$id) {
            return $this->redirect()->toRoute('album');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost()->get('del', 'No');
            if ($del == 'Yes') {
                $id = (int)$request->getPost()->get('id');
                $this->getAlbumTable()->deleteAlbum($id);
            }

            // Redirect to list of albums
            return $this->redirect()->toRoute('album');
        }

        return array(
            'id' => $id,
            'album' => $this->getAlbumTable()->getAlbum($id)
        );
    }

    public function detailAction()
    {
        //\Zend\Debug\Debug::dump($this->getServiceLocator()->get('db2'));
        try {
            $adapter = $this->getServiceLocator()->get('db2');
            $sql = new Sql($adapter);
            $insert = $sql->insert('company');
            $newData = array(
            'name'=> 'Arjun Sunar',
            'age'=> 25,
            'address'=> 'Pokhara',
            'salary'=> 300,
            // 'created_date'=> \date('Y-m-d'),
            // 'created_time'=> \time()
            );
            $insert->values($newData);
            $selectString = $sql->getSqlStringForSqlObject($insert);

            $results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE);
        } catch (Exception $e) {
            echo $e->getMesssage();
        }
        return new ViewModel();
    }
    public function artistAction()
    {
        $adapter = $this->getServiceLocator()->get('db2');
        $sql = new Sql($adapter);
        $select = $sql->select();
        $select->from('company');
        // $select->where(array('id' => 2));

        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();
echo "dfdf";
        foreach ($results as $row) {

            var_dump($row['name']);
        }
        return new ViewModel();
    }
    public function getAlbumTable()
    {
        if (!$this->albumTable) {
            $sm = $this->getServiceLocator();
            $this->albumTable = $sm->get('Album\Model\AlbumTable');
        }
        return $this->albumTable;
    }
}
