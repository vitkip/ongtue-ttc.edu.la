<?php

namespace App\Support;

class ApplicationOptions
{
    public static function genders(): array
    {
        return [
            'monk' => 'ພຣະ',
            'novice' => 'ສາມະເນນ',
            'male' => 'ຊາຍ',
            'female' => 'ຍິງ',
            'other' => 'ອຶ່ນໆ',
        ];
    }

    public static function accommodationTypes(): array
    {
        return [
            'has_temple' => 'ມີວັດຢູ່ແລ້ວ',
            'needs_temple' => 'ຫາວັດໃຫ້',
        ];
    }

    public static function provinces(): array
    {
        $names = [
            'ນະຄອນຫຼວງວຽງຈັນ', 'ຜົ້ງສາລີ', 'ຫຼວງນໍ້າທາ', 'ອຸດົມໄຊ', 'ບໍ່ແກ້ວ',
            'ຫຼວງພະບາງ', 'ຫົວພັນ', 'ໄຊຍະບູລີ', 'ຊຽງຂວາງ', 'ວຽງຈັນ',
            'ບໍລິຄໍາໄຊ', 'ຄໍາມ່ວນ', 'ສະຫວັນນະເຂດ', 'ສາລະວັນ', 'ເຊກອງ',
            'ຈໍາປາສັກ', 'ອັດຕະປື', 'ໄຊສົມບູນ',
        ];

        return array_combine($names, $names);
    }
}
