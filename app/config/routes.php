<?php

return [
    '/' => ['IndexController', 'index'],

    '/signin' => ['SigninController', 'index'],
    '/signout' => ['SigninController', 'cancel'],
    '/signin/submit' => ['SigninController', 'submit'],

    '/signup' => ['SignupController', 'index'],
    '/signup/submit' => ['SignupController', 'submit']
];