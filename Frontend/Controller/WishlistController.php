<?php

namespace Frontend\Controller;

use Frontend\Controller\Controller;
use Frontend\Model\ExposantsModel;
use Backend\Model\ProgrammationModel;
use Core\HttpRequest;
use Frontend\PageManager;


use Dompdf\Dompdf;
use Dompdf\Options;

require_once __DIR__ . '/../../util/dompdf/autoload.inc.php';

class WishlistController extends Controller
{    
    private $httpRequest;
    private $programmationModel;
    private $exposantsModel;
    private $pageManager;  

    public function __construct()
    {
        parent::__construct();
        $this->httpRequest = new HttpRequest(); 
        $this->programmationModel = new ProgrammationModel();
        $this->exposantsModel = new ExposantsModel();
        $this->pageManager = new PageManager();
    }


    public function getAdd()
    {
        foreach($this->httpRequest->post as $key=>$value) {
            $$key = $value;
        }
        if(!isset($_SESSION['wishlist'])) {
            $_SESSION['wishlist'] = [];
        }
        $_SESSION['wishlist'][] = [
            'type'  => $type,
            'slug'  => $slug
        ];        
        echo json_encode(['result' => 'success']);
    }


    public function getRemove()
    {
        foreach($this->httpRequest->post as $key=>$value) {
            $$key = $value;
        }
        if(isset($_SESSION['wishlist'])) {
            for($i=0; $i<count($_SESSION['wishlist']); $i++) {
                $wish = $_SESSION['wishlist'][$i]; 
                if($wish['slug'] === $slug) {
                    unset($_SESSION['wishlist'][$i]);
                    sort($_SESSION['wishlist']); 
                }
            }
            echo json_encode(['result' => 'success']);
        }
        else{
            echo json_encode(['result' => 'error']);
        }
    }


    public function getList($print=false)
    {        
        $programmes = [];
        $exposants = [];
        $content = '';

        if(isset($_SESSION['wishlist'])) {
            sort($_SESSION['wishlist']); 
            
            for($i=0; $i<count($_SESSION['wishlist']); $i++) {
                
                $wish = $_SESSION['wishlist'][$i];
                $type = $wish['type'];
                $slug = $wish['slug'];
                
                if($type === 'exposant') {
                    $exposant = $this->exposantsModel->getSingleBySlug($slug);
                    $exposant->slug = $slug;
                    $exposant->description = $this->truncate($exposant->website_introduction);
                    $exposants[] = $exposant;
                }
                
                if($type === 'programme') {
                    $programme = $this->programmationModel->getSingleBySlug($slug);
                    $programme->slug = $slug;
                    $programme->horraires = $this->programmationModel->getHeuresById($programme->id);
                    $programme->lieux = $this->programmationModel->getlieuxById($programme->id);
                    $programme->texte = $this->truncate($programme->texte);
                    $programmes[] = $programme;
                }

            }
        }

        if($print === true) {
            return (object) [
                'exposants' => $exposants,
                'programmes' => $programmes
            ];
        }

        if(empty($programmes) && empty($exposants)) {
            $content = $this->pageManager->getContent('favoris');
        }

        $this->render('wishlist', [
            'styles'            =>  'favoris',
            'type'              =>  $this->getPageType('favoris'),
            'title'             =>  'Favoris',
            'wishlistWidget'    =>  null,
            'exposants'         => $exposants,
            'programmes'        => $programmes,
            'content'           => $content
        ]);
    }


    public function getPrint()
    {
        $list = $this->getList(true);        

        $html = $this->getRender(['wishlist', 'print'], [
            'exposants'         => $list->exposants,
            'programmes'        => $list->programmes,
        ]);        
        
        $options = new Options();
        $options->set('defaultFont', 'Helvetica');
        $options->set('isRemoteEnabled', true);
        $options->set('debugKeepTemp', true);
        $options->set('isHtml5ParserEnabled', true);
        $dompdf = new Dompdf($options);

        $dompdf->loadHtml($html);	
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        $output = $dompdf->output();

        file_put_contents(SITE_PATH .'print/mes-favoris.pdf', $output);
        echo json_encode(['result' => 'success']);

    }


    private function truncate($string, $length = 200) 
    {
        return mb_substr($string, 0, $length) . ' ...';
    }


}