<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'auth';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

/*authentication*/
$route['login'] = 'auth/login';
$route['forgot-password'] = 'auth/forgot_password';
$route['logout'] = 'auth/logout';


#<-----Administrator ---->

/*Dashboard*/
$route['dashboard'] = 'backend/admin/dashboard';
$route['home'] = 'frontend/member/dashboard';


$route['vehicles-managment'] = 'backend/admin/vehicles';
$route['clients'] = 'backend/admin/client_management';
$route['add-client'] = 'backend/admin/create_client';

$route['update-client'] = 'backend/admin/update_client';
$route['profile'] = 'backend/admin/client_profile';
$route['departments'] = 'backend/admin/client_departments';

$route['transactions-manage'] = 'backend/admin/db_dump';
$route['range-transactions'] = 'backend/admin/range_db_dump';
$route['weekly-report'] = 'backend/admin/weekly_report';
$route['toll-spending'] = 'backend/admin/client_toll_spending';
$route['account'] = 'backend/admin/accounts';
$route['citations-management'] = 'backend/admin/citations';
$route['manage-invoices'] = 'backend/admin/invoice_listing';
$route['monthly-invoices'] = 'backend/admin/month_for_invoice';
$route['fulfilment'] = 'backend/admin/fulfilment';
$route['users'] = 'backend/admin/system_users';

$route['states'] = 'backend/admin/states';
$route['agencies'] = 'backend/admin/agencies';
$route['tag'] = 'backend/admin/tags';
$route['toll_tag'] = 'backend/admin/toll_tag';
$route['card_info'] = 'backend/admin/card_info';
$route['signups'] = 'backend/admin/signups';

// client-end
$route['vehicles'] = 'frontend/member/vehicles';
$route['transactions'] = 'frontend/member/transactions';
$route['invoices'] = 'frontend/member/invoices';
$route['citations'] = 'frontend/member/citations';
$route['transponder-fulfilment'] = 'frontend/member/transponders';
$route['transponder'] = 'frontend/member/order';

$route['card'] = 'api/card';
$route['card/(:any)'] = 'api/card/$1';
