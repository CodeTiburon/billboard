<?php

return [
    '/' => ['IndexController', 'index'],

    '/signin' => ['SigninController', 'index'],
    '/signin/submit' => ['SigninController', 'submit'],

    '/signup' => ['SignupController', 'index'],
    '/signup/submit' => ['SignupController', 'submit']
];