<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright  Mandrágora Web-Based Systems 2015 (http://www.mandragora-web-systems.com)
 */
require __DIR__ . '/../vendor/autoload.php';

use Example\Forms\AddToCartForm;
use Example\Forms\Configuration\AddToCartConfiguration;
use Example\Forms\Filters\AddToCartFilter;
use Forms\Bridges\Twig\FormExtension;
use Forms\Bridges\Twig\FormRenderer;
use Forms\Bridges\Zend\InputFilter\InputFilterValidator;
use ProductCatalog\Catalog;
use ProductCatalog\Product;

$catalog = new Catalog();
$catalog->add(new Product(1, 100, 'Super Mario Bros'));
$catalog->add(new Product(2, 120, 'Call of Duty'));
$catalog->add(new Product(3, 110, 'Mortal Kombat II'));

$configuration = new AddToCartConfiguration($catalog);

$shoppingCartForm = new AddToCartForm();
$shoppingCartForm->configure($configuration);

if ($_POST) {
    $filter = new AddToCartFilter();
    $filter->configure($configuration);
    $validator = new InputFilterValidator($filter);

    $shoppingCartForm->submit($_POST);

    $validator->validate($shoppingCartForm);
}


$loader = new Twig_Loader_Filesystem([
    __DIR__ . '/../vendor/comphppuebla/simple-form/src/Forms/Bridges/Twig',
    __DIR__ . '/../app/templates',
]);
$environment = new Twig_Environment($loader, [
    'cache' => __DIR__ . '/../var/cache/twig',
    'debug' => true,
    'strict_variables' => true,
]);
$environment->addExtension(new FormExtension(new FormRenderer($environment, 'forms/form.html.twig')));

echo $environment->render('cart.html.twig', [
    'cart' => $shoppingCartForm->buildView(),
]);
