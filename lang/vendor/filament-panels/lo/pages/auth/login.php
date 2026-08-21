<?php

return [

    'title' => 'ເຂົ້າສູ່ລະບົບ',

    'heading' => 'ເຂົ້າສູ່ລະບົບ',

    'actions' => [

        'register' => [
            'before' => 'ຫຼື',
            'label' => 'ສະໝັກບັນຊີໃໝ່',
        ],

        'request_password_reset' => [
            'label' => 'ລືມລະຫັດຜ່ານ?',
        ],

    ],

    'form' => [

        'email' => [
            'label' => 'ອີເມວ',
        ],

        'password' => [
            'label' => 'ລະຫັດຜ່ານ',
        ],

        'remember' => [
            'label' => 'ຈື່ຈຳຂ້ອຍໄວ້',
        ],

        'actions' => [

            'authenticate' => [
                'label' => 'ເຂົ້າສູ່ລະບົບ',
            ],

        ],

    ],

    'messages' => [

        'failed' => 'ອີເມວ ຫຼື ລະຫັດຜ່ານບໍ່ຖືກຕ້ອງ.',

    ],

    'notifications' => [

        'throttled' => [
            'title' => 'ພະຍາຍາມເຂົ້າສູ່ລະບົບຫຼາຍເກີນໄປ',
            'body' => 'ກະລຸນາລອງໃໝ່ອີກຄັ້ງໃນ :seconds ວິນາທີ.',
        ],

    ],

];
