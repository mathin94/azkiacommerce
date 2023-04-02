<?php

return [

    'title' => 'Login Admin',

    'heading' => 'Masuk ke akun Anda',

    'buttons' => [

        'submit' => [
            'label' => 'Login',
        ],

    ],

    'fields' => [
        'identity' => [
            'label' => 'Username atau Email'
        ],

        'email' => [
            'label' => 'Alamat Email',
        ],

        'password' => [
            'label' => 'Kata sandi',
        ],

        'remember' => [
            'label' => 'Ingat saya',
        ],

    ],

    'messages' => [
        'failed' => 'Kredensial yang diberikan tidak dapat ditemukan.',
        'throttled' => 'Terlalu banyak percobaan masuk. Silakan ulangi dalam :seconds detik.',
    ],

];
