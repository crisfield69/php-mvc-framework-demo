<?php

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/debug.php';
require_once __DIR__ . '/Core/Util.php';
require_once __DIR__ . '/Core/Autoloader.php';

use Core\Autoloader;
use Core\Routing\Router;

Autoloader::register();
$url = (isset($_GET['url']) && !empty($_GET['url']))? $_GET['url'] : '';
$router = new Router($url);


/**
 *  --------------------- Not found ---------------------
 */

use Core\Controller\NotFoundController;

$router->get('/notfound', [NotFoundController::class, 'index']);
 
/**
 *  --------------------- Frontend ---------------------
 */

use Frontend\Controller\HomeController;
use Frontend\Controller\ExposantsController;
use Frontend\Controller\ProgrammationController;
use Frontend\Controller\ConferencesController as FrontendConferencesController;
use Frontend\Controller\WishlistController;
use Frontend\Controller\SearchController;

$router->get('/', [HomeController::class, 'getDisplay']);

$router->get('/exposants', [ExposantsController::class, 'getHome']);
$router->get('/exposants/:a', [ExposantsController::class, 'getContent']);
$router->get('/exposants/:a/:b', [ExposantsController::class, 'getContent']);
$router->get('/exposants/:a/:b/:c', [ExposantsController::class, 'getContent']);
$router->get('/exposants/:a/:b/:c/:d', [ExposantsController::class, 'getContent']);


$router->get('/programmation', [ProgrammationController::class, 'getHome']);
$router->get('/programmation/theme/:theme', [ProgrammationController::class, 'getAllByTheme']);

//$router->get('/programmation/lieu/:lieu', [ProgrammationController::class, 'getAllByLieu']);
$router->get('/programmation/lieu/:lieu', [ProgrammationController::class, 'getAllByLieuParent']);

$router->get('/programmation/date/:date', [ProgrammationController::class, 'getAllByDate']);
$router->get('/programmation/forme/:forme', [ProgrammationController::class, 'getAllByForme']);
$router->get('/programmation/permanents', [ProgrammationController::class, 'getAllByPermanent']);

$router->get('/programmation/theme/:theme/:programme', [ProgrammationController::class, 'getOneByTheme']);
//$router->get('/programmation/lieu/:lieu/:programme', [ProgrammationController::class, 'getOneByLieu']);
$router->get('/programmation/lieu/:lieu/:programme', [ProgrammationController::class, 'getOneByLieuParent']);
$router->get('/programmation/date/:date/:programme', [ProgrammationController::class, 'getOneByDate']);
$router->get('/programmation/forme/:forme/:programme', [ProgrammationController::class, 'getOneByForme']);
$router->get('/programmation/permanents/:permanent', [ProgrammationController::class, 'getOneByPermanent']);

$router->get('/conferences', [FrontendConferencesController::class, 'getHome']);

$router->get('/wishlist/list', [WishlistController::class, 'getList']);
$router->post('/wishlist/add', [WishlistController::class, 'getAdd']);
$router->post('/wishlist/remove', [WishlistController::class, 'getRemove']);
$router->post('/wishlist/print', [WishlistController::class, 'getPrint']);

$router->post('/recherche', [SearchController::class, 'postSearch']);

use Frontend\Controller\TestController;
$router->get('/test', [TestController::class, 'getHome']);


/**
 *  --------------------- Backend ---------------------
 */

use Backend\Controller\AdminController;
use Backend\Controller\LoginController;
use Backend\Controller\CategoriesController;
use Backend\Controller\GalleriesController;
use Backend\Controller\PagesController;
use Backend\Controller\TranscriptionsController;
use Backend\Controller\ConferencesController;
use Backend\Controller\ApiController;

$router->get('/admin', [AdminController::class, 'getHome']); 

$router->get('/admin/login', [LoginController::class, 'getLogin']); 
$router->post('/admin/login', [LoginController::class, 'postLogin']);
$router->get('/admin/disconnect', [LoginController::class, 'getDisconnect']); 

$router->get('/admin/categories', [CategoriesController::class, 'getHome']);
$router->get('/admin/categories/update/:id', [CategoriesController::class, 'getUpdate']);
$router->post('/admin/categories/update/:id', [CategoriesController::class, 'postUpdate']);
$router->post('/admin/categories/update-order', [CategoriesController::class, 'postUpdateOrder']);

$router->get('/admin/pages', [PagesController::class, 'getHome']);
$router->get('/admin/pages/insert', [PagesController::class, 'getInsert']);
$router->post('/admin/pages/insert', [PagesController::class, 'postInsert']);
$router->get('/admin/pages/update/:id', [PagesController::class, 'getUpdate']);
$router->post('/admin/pages/update/:id', [PagesController::class, 'postUpdate']);
$router->get('/admin/pages/delete/:id', [PagesController::class, 'getDelete']);
$router->post('/admin/pages/delete/:id', [PagesController::class, 'postDelete']);
$router->post('/admin/pages/add-bloc', [PagesController::class, 'postAddBloc']);
$router->post('/admin/pages/upload', [PagesController::class, 'filesUpload']);
$router->post('/admin/pages/update-order', [PagesController::class, 'postUpdateOrder']);

$router->get('/admin/galeries', [GalleriesController::class, 'getHome']);
$router->get('/admin/galeries/insert', [GalleriesController::class, 'getInsert']);
$router->post('/admin/galeries/insert', [GalleriesController::class, 'postInsert']);
$router->get('/admin/galeries/update/:id', [GalleriesController::class, 'getUpdate']);
$router->post('/admin/galeries/update/:id', [GalleriesController::class, 'postUpdate']);
$router->post('/admin/galeries/update-photos', [GalleriesController::class, 'postUpdatePhotos']);
$router->get('/admin/galeries/delete/:id', [GalleriesController::class, 'getDelete']);
$router->post('/admin/galeries/delete/:id', [GalleriesController::class, 'postDelete']);

$router->get('/admin/transcriptions', [TranscriptionsController::class, 'getHome']);
$router->post('/admin/transcriptions', [TranscriptionsController::class, 'postHome']);

$router->get('/admin/conferences', [ConferencesController::class, 'getHome']);
$router->post('/admin/conferences', [ConferencesController::class, 'postHome']);
$router->post('/admin/conferences/update-order', [ConferencesController::class, 'postUpdateOrder']);

$router->get('/admin/imports-api', [ApiController::class, 'getHome']);


/**
 *  --------------------- Frontend page standard ---------------------
 */

use Frontend\Controller\PageController;

$router->get('/:slug', [PageController::class, 'getDisplay']);



$router->run();