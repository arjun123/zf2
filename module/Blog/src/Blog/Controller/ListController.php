<?php
/**
 *
 * @package Blog
 *
 */
namespace Blog\Controller;

use Blog\Service\PostServiceInterface;
use Blog\Model;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;


/**
 *
 * @package Blog
 */
class ListController extends AbstractActionController
{
    /**
      * @var \Blog\Service\PostServiceInterface
      */
     protected $postService;

     public function __construct(PostServiceInterface $postService)
     {
         $this->postService = $postService;
     }

     public function indexAction()
     {
         return new ViewModel(array(
             'posts' => $this->postService->findAllPosts()
         ));
     }

}
