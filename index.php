<?php

//Turn on error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

//Start a session
session_start();

//Require the autoload file
require_once("vendor/autoload.php");
require_once("model/data-layer.php");

//Instantiate the F3 Base class
$f3 = Base::instance();

//Default route
$f3->route('GET /', function() {
    //echo '<h1>Food page.</h1>';

    $views = new Template();
    echo $views->render('views/home.html');
});

//breakfast route
$f3->route('GET /breakfast', function() {
    //echo '<h1>breakfast page.</h1>';

    $views = new Template();
    echo $views->render('views/bfast.html');
});

//breakfast green eggs and ham route
$f3->route('GET /breakfast/green-eggs', function() {
    //echo '<h1>breakfast page.</h1>';

    $views = new Template();
    echo $views->render('views/greenEggsAndHam.html');
});

//lunch route
$f3->route('GET /lunch', function() {
    //echo '<h1>breakfast page.</h1>';

    $views = new Template();
    echo $views->render('views/lunch.html');
});

//order form route
$f3->route('GET|POST /order', function($f3) {
    //echo '<h1>breakfast page.</h1>';

    $meals = getMeals();

    //If the form has been submitted
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        //var_dump($_POST);
        //["food"]=>"tacos" ["meal"]=>"lunch"

        //Validate the data

        if (empty($_POST['food']) || !in_array($_POST['meal'], $meals)) {
            echo "<p>Please enter a food and select a meal</p>";
        }
        //Data is valid
        else {
            //Store the data in the session array
            $_SESSION['food'] = $_POST['food'];
            $_SESSION['meal'] = $_POST['meal'];

            //Redirect to order2 page
            $f3->reroute('order2');
            session_destroy();
        }
    }

    $f3->set('meals', $meals);

    $views = new Template();
    echo $views->render('views/order.html');
});

//order 2 route
$f3->route('GET /order2', function($f3) {

    $conds = getCondiments();

    //If the form has been submitted
    if($_SERVER['REQUEST_METHOD'] == 'POST') {

        //Store the data in the session array
        $_SESSION['conds'] = $_POST['conds'];

        //Redirect to summary page
        $f3->reroute('summary');

    }

    $f3->set('conds', $conds);

    $views = new Template();
    echo $views->render('views/orderForm2.html');
});

//Breakfast route
$f3->route('GET /summary', function() {
    //echo '<h1>Thank you for your order!</h1>';

    $view = new Template();
    echo $view->render('views/summary.html');

});

//Run F3
$f3->run();