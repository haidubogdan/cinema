<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

Request::setTrustedProxies(array('127.0.0.1'));
$app->get('/', function () use ($app) {
            return $app['twig']->render('index.html', array('name' => 'home'));
        })
        ->bind('homepage')
;

$app->get('/database', function () use ($app) {
            $sql = "SHOW COLUMNS FROM users;";
            $statement = $app['db']->prepare($sql);
            $statement->execute();
            $column = $statement->fetchAll();
            $foos = "test";
            return $app['twig']->render('database_test.html', array('name' => 'database',
                        'foos' => $foos,
                        'column' => $column,));
        })
        ->bind('database')
;

$app->get('/login', function () use ($app) {

            return $app['twig']->render('login.html', array('name' => 'login'));
        })
        ->bind('login')
;



$app->get('/hello/{name}', function ($name) use ($app) {
            return $app['twig']->render(
                            'helloworld.html', array('name' => $name)
            );
        })
        ->bind('helloworld')
;


$app->error(function (\Exception $e, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    // 404.html, or 40x.html, or 4xx.html, or error.html
    $templates = array(
        'errors/' . $code . '.html',
        'errors/' . substr($code, 0, 2) . 'x.html',
        'errors/' . substr($code, 0, 1) . 'xx.html',
        'errors/default.html',
    );

    return new Response($app['twig']->resolveTemplate($templates)->render(array('code' => $code)), $code);
});
