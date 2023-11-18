<?php



// header
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: x-requested-with,Content-Type');
header('Access-Control-Max-Age: 86400');
// require
require_once __DIR__ .'/connect_DB.php';
const PAGING_SIZE   = 10;
const PAGING_SCALE  = 10;