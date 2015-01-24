<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
require __DIR__ . '/../vendor/autoload.php';

use ExampleForms\AddToCartForm;
use ExampleForms\Configuration\AddToCartConfiguration;
use ExampleForms\Filters\AddToCartFilter;
use EasyForms\Bridges\Twig\BlockOptions;
use EasyForms\Bridges\Twig\FormExtension;
use EasyForms\Bridges\Twig\FormRenderer;
use EasyForms\Bridges\Twig\FormTheme;
use EasyForms\Bridges\Zend\InputFilter\InputFilterValidator;
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
    __DIR__ . '/../vendor/comphppuebla/easy-forms/src/EasyForms/Bridges/Twig',
    __DIR__ . '/../app/templates',
]);
$environment = new Twig_Environment($loader, [
    'cache' => __DIR__ . '/../var/cache/twig',
    'debug' => true,
    'strict_variables' => true,
]);
$theme = new FormTheme($environment, 'layouts/required.html.twig');
$environment->addExtension(new FormExtension(new FormRenderer($theme, new BlockOptions())));

echo $environment->render('cart.html.twig', [
    'cart' => $shoppingCartForm->buildView(),
]);
