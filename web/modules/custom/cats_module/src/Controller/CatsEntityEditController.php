<?php
namespace Drupal\cats_module\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CatsEntityEditController extends ControllerBase {
  public function edit($cats_module_id) {
    $content = [
      '#markup' => 'Are you sure you want to delete this cat entity?',
    ];
    if($cats_module_id){
      $cats_entity = $this->entityTypeManager()->getStorage('cats_module')->load($cats_module_id);
    }


    if (!$cats_entity) {
      throw new NotFoundHttpException();
    }


    return [
      // Поверніть вашу сторінку редагування тут.
    ];
  }

}
