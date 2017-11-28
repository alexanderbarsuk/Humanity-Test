<?php

return [

    '' => 'index/index',

    'login/([0-9]+)' => 'authorization/login/$1',
    'login/manager' => 'authorization/login_manager',

    'logout' => 'authorization/logout',

    'leave' => 'request/show_make_request',

    'requests' => 'request/show_list',
    'request/view/([0-9]+)' => 'request/view/$1',
    'request/edit/([0-9]+)' => 'request/edit/$1',
    'request/delete/([0-9]+)' => 'request/delete/$1',
    'request/approve/([0-9]+)' => 'request/change_status/$1/APPROVED',
    'request/reject/([0-9]+)' => 'request/change_status/$1/REJECTED',

];
