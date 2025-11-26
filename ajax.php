<?php
ob_start();
include 'admin_class.php';

$action = $_GET['action'] ?? '';
$crud = new Action();

switch ($action) {

    case 'login':
        echo $crud->login();
        break;

    case 'login2':
        echo $crud->login2();
        break;

    case 'logout':
        echo $crud->logout();
        break;

    case 'logout2':
        echo $crud->logout2();
        break;

    case 'save_user':
        echo $crud->save_user();
        break;

    case 'delete_user':
        echo $crud->delete_user();
        break;

    case 'signup':
        echo $crud->signup();
        break;

    case 'save_settings':
        echo $crud->save_settings();
        break;

    case 'save_category':
        echo $crud->save_category();
        break;

    case 'delete_category':
        echo $crud->delete_category();
        break;

    case 'save_location':
        echo $crud->save_location();
        break;

    case 'delete_location':
        echo $crud->delete_location();
        break;

    case 'save_vehicle':
        echo $crud->save_vehicle();
        break;

    case 'delete_vehicle':
        echo $crud->delete_vehicle();
        break;

    case 'checkout_vehicle':
        echo $crud->checkout_vehicle();
        break;

    default:
        echo "Invalid action";
        break;
}
