<?php

return [
    'date' => [

        /*
         * Carbon date format
         */
        'format' => 'd/m/Y',

        /*
         * Due date for payment since invoice's date.
         */
        'pay_until_days' => 7,
    ],

    'serial_number' => [
        'series'   => 'AA',
        'sequence' => 1,

        /*
         * Sequence will be padded accordingly, for ex. 00001
         */
        'sequence_padding' => 5,
        'delimiter'        => '.',

        /*
         * Supported tags {SERIES}, {DELIMITER}, {SEQUENCE}
         * Example: AA.00001
         */
        'format' => '{SERIES}',
    ],

    'currency' => [
        'code' => 'idr',

        /*
         * Usually cents
         * Used when spelling out the amount and if your currency has decimals.
         *
         * Example: Amount in words: Eight hundred fifty thousand sixty-eight EUR and fifteen ct.
         */
        'fraction' => 'ct.',
        'symbol'   => 'Rp',

        /*
         * Example: 19.00
         */
        'decimals' => 0,

        /*
         * Example: 1.99
         */
        'decimal_point' => ',',

        /*
         * By default empty.
         * Example: 1,999.00
         */
        'thousands_separator' => '.',

        /*
         * Supported tags {VALUE}, {SYMBOL}, {CODE}
         * Example: 1.99 €
         */
        'format' => '{SYMBOL} {VALUE}',
    ],

    'paper' => [
        // A4 = 210 mm x 297 mm = 595 pt x 842 pt
        'size'        => 'a4',
        'orientation' => 'portrait',
    ],

    'disk' => 'local',

    'seller' => [
        /*
         * Class used in templates via $invoice->seller
         *
         * Must implement LaravelDaily\Invoices\Contracts\PartyContract
         *      or extend LaravelDaily\Invoices\Classes\Party
         */
        'class' => \LaravelDaily\Invoices\Classes\Seller::class,

        /*
         * Default attributes for Seller::class
         */
        'attributes' => [
            'name'          => 'Azkia Hijab',
            'address'       => 'Nagrak, Cisaat, Sukabumi Regency, West Java 43152',
            'code'          => '',
            'vat'           => '',
            'phone'         => '0856-8924-115',
            'custom_fields' => [
                /*
                 * Custom attributes for Seller::class
                 *
                 * Used to display additional info on Seller section in invoice
                 * attribute => value
                 */
                'SWIFT' => 'BANK101',
            ],
        ],
    ],

    'dompdf_options' => [
        'enable_php' => true,
        /**
         * Do not write log.html or make it optional
         *  @see https://github.com/dompdf/dompdf/issues/2810
         */
        'logOutputFile' => '/dev/null',
    ],
];
