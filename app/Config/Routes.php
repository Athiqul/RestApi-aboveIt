<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index',);

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
//Api routing
//Account create for content writer
$routes->post('/user-signup-request','UserSign::userCreate');
$routes->post('/user-auth-request','Authen::authUser');
$routes->post('/user-auth-otp','OtpVerify::otpVerify');
//Authentication

//Blog Category create
//$routes->get('/category-blog/(:num)','Blog::categoryBlog/$1');

$routes->group('blog',function($routes) {
  
    $routes->post('add-category','BlogCategory::create',['filter'=>'auth']);
    $routes->get('category-list','BlogCategory::index');
    $routes->get('category-active-list','BlogCategory::activeCategory');
    $routes->get('category-edit/(:num)', 'BlogCategory::editCategory/$1');//read
    $routes->post('category-update/(:num)', 'BlogCategory::updateCategory/$1',['filter'=>'auth']);
    $routes->post('add-new','Blog::create',['filter'=>'auth']);
    $routes->get('blogs','Blog::index');
    $routes->get('all-blogs','Blog::allBlogList',['filter'=>'auth']);
    $routes->get('view/(:num)','Blog::showBlog/$1');
    $routes->post('update/(:num)','Blog::updateBlogInfo/$1',['filter'=>'auth']);
    $routes->get('category-blog/(:num)','Blog::categoryBlog/$1');
    $routes->post('category-blog-update/(:num)','Blog::blogCategoryAdded/$1',['filter'=>'auth']);
    $routes->post('category-blog-remove/(:num)','Blog::removeCategoryFromBlog/$1',['filter'=>'auth']);
   
});


//Products

$routes->group('products',function($routes){
    $routes->get('above-products','Products::index');
    $routes->post('add-product','Products::create',['filter'=>'auth']);
    $routes->get('show-product/(:num)','Products::showProduct/$1');
    $routes->post('update-product/(:num)','Products::updateProduct/$1',['filter'=>'auth']);
    $routes->post('all-products','Products::allProducts',['filter'=>'auth']);
});

//Services 

$routes->group('services',function($routes){
   $routes->get('show-all','Services::index');
   $routes->post('add-service','Services::create',['filter'=>'auth']);
   $routes->get('show-service/(:num)','Services::showService/$1');
   $routes->post('update-service/(:num)','Services::updateService/$1',['filter'=>'auth']);
   $routes->post('inactive-services','Services::deactivatedServices',['filter'=>'auth']);

});

//Team Member routes

$routes->group('team',function($routes){
    
    $routes->get('show-members','Team::index');
    $routes->post('add-member','Team::create',['filter'=>'auth']);
    $routes->get('show-member/(:num)','Team::showTeamMember/$1');
    $routes->post('update-member/(:num)','Team::updateTeamMemberInfo/$1',['filter'=>'auth']);
    $routes->post('all-members','Team::allTeamMembers',['filter'=>'auth']);
 
 });


 //Customer Review routes

$routes->group('review',function($routes){
    
    $routes->get('show-customers-review','ClientReview::index');//For front end Web view
    $routes->post('add-review','ClientReview::create',['filter'=>'auth']);
    $routes->post('show-review/(:num)','ClientReview::showReview/$1',['filter'=>'auth']);//For admin
    $routes->post('update-review/(:num)','ClientReview::updateReview/$1',['filter'=>'auth']);
    $routes->post('show-all-reviews','ClientReview::showAllReview',['filter'=>'auth']);//For Admin
 
 });

 //Callback Request Api
 $routes->group('call',function($routes){
    
    $routes->get('request-solved','CallbackRequest::index',['filter'=>'auth']);//For Admin Web view
    $routes->post('request-for-call','CallbackRequest::create');
    $routes->get('pending-request','CallbackRequest::showAllPendingRequest',['filter'=>'auth']);//For Admin
    $routes->post('update-request/(:num)','CallbackRequest::statusUpdate/$1',['filter'=>'auth']);
 
 });


 //Packages List
 $routes->group('package',function($routes){   

    $routes->get('category-list','Packages::showActiveCategoryList',['filter'=>'auth']);//For Admin Web view
    $routes->get('category-inactive-list','Packages::showDeactiveCategoryList',['filter'=>'auth']);
    $routes->post('add-category','Packages::createCategory',['filter'=>'auth']);
    $routes->post('update-category/(:num)','Packages::categoryStatusUpdate/$1',['filter'=>'auth']);

    $routes->post('add-package','Packages::createPackage',['filter'=>'auth']);
    $routes->get('show-active-packages','Packages::activePackages');
    $routes->get('show-all-packages','Packages::allPackages',['filter'=>'auth']);
    $routes->get('show-package/(:num)','Packages::showPackages/$1');
    $routes->post('update-package/(:num)','Packages::updatePackage/$1',['filter'=>'auth']);

    $routes->delete('delete-package-service/(:num)','Packages::removeService/$1',['filter'=>'auth']);
    $routes->post('add-package-service/(:num)','Packages::addServices/$1',['filter'=>'auth']);

 });


 //contest
 $routes->group('contest',function($routes){   

     $routes->post('apply','Contest::create');//For Web view
    $routes->get('show-all','Contest::showAll',['filter'=>'auth']);
    $routes->get('email-failed','Contest::emailFailedList',['filter'=>'auth']);
    $routes->get('email-resent/(:num)','Contest::emailResent/$1',['filter'=>'auth']);
    // $routes->post('add-category','Packages::createCategory',['filter'=>'auth']);
    // $routes->post('update-category/(:num)','Packages::categoryStatusUpdate/$1',['filter'=>'auth']);

    // $routes->post('add-package','Packages::createPackage',['filter'=>'auth']);
    // $routes->get('show-active-packages','Packages::activePackages');
    // $routes->get('show-package/(:num)','Packages::showPackages/$1');
    // $routes->post('update-package/(:num)','Packages::updatePackage/$1',['filter'=>'auth']);

    // $routes->delete('delete-package-service/(:num)','Packages::removeService/$1',['filter'=>'auth']);
    // $routes->post('add-package-service/(:num)','Packages::addServices/$1',['filter'=>'auth']);

 });

 //test


if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
