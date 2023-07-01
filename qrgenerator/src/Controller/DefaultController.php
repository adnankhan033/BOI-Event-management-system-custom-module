<?php

namespace Drupal\qrgenerator\Controller;



use Drupal\Core\Controller\ControllerBase;

use Endroid\QrCode\QrCode;
use Drupal\Core\Render\Markup;
use \Drupal\Core\Form\FormStateInterface;

/**
 * Class DefaultController.
 */
class DefaultController extends ControllerBase {

    public static function createQrImage($string)
    {
        $qrcode = new QrCode($string);
        $orig = $qrcode->writeString();
//        echo ;
        mkdir("/tmp/hi");
        $qrcode->writeFile("/tmp/apple.png");
//        echo DRUPAL_ROOT.'/sites/defa';
        exit;
        $img =  'data:image/png;base64, '.base64_encode($orig);
        
        

                kint($qrcode);
                exit;



//        $node = \Drupal\node\Entity\Node::create([
//            'type'             => 'qr',
//            'title'            => 'fgfgf',
//            'field_pic' => [
//                'target_id' =>$orig,
//                'alt' => 'test'
//            ],
//
//        ]);
//        //        $node->field_pic->generateSampleItems();
//
//       
//        $node->save();

        return ['img'=>$img, 'orig'=>$orig];


    }

    /**
   * Qr.
   *
   * @return string
   *   Return Hello string.
   */
    public function qr() {
        //    header("Content-Type: image/png");


        $img = self::createQrImage("Hello world");
        //      echo '<img src="'.$img.'"/>';
        //      exit;
        return [
            '#markup' => Markup::create('Your event is <img src="'.$img['img'].'"/>')
        ];
        //echo '';
        //      kint([$img, 'hi']);
        //      exit;

        //    die();
    }

}

