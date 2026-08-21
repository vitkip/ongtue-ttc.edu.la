<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\Event;
use App\Models\GalleryImage;
use App\Models\Hero;
use App\Models\OrgDepartment;
use App\Models\ContentBlock;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@ongtuecollege.edu.la'],
            [
                'name' => 'ຜູ້ດູແລລະບົບ',
                'password' => bcrypt('password'),
                'is_admin' => true,
            ]
        );

        $this->seedHero();
        $this->seedCourseCategories();
        $this->seedCourses();
        $this->seedEvents();
        $this->seedGallery();
        $this->seedOrgChart();
        $this->seedContentBlocks();
    }

    private function seedHero(): void
    {
        Hero::updateOrCreate(
            ['title_line1' => 'ສູນກາງການສຶກສາພຸດທະສາດ'],
            [
                'image_url' => 'https://images.unsplash.com/photo-1544816155-12df9643f363?auto=format&fit=crop&w=1920&q=80',
                'badge_text' => 'ພຸດທະສາສະໜາ ແລະ ການສຶກສາ',
                'title_line1' => 'ສູນກາງການສຶກສາພຸດທະສາດ',
                'title_line2' => 'ທີ່ເປັນເລີດທາງດ້ານວິຊາການ',
                'description' => 'ວິທະຍາໄລຄູສົງ ອົງຕື້ ມຸ່ງໝັ້ນສ້າງຊັບພະຍາກອນມະນຸດ ທີ່ມີຄວາມຮູ້ທາງໂລກ ແລະ ທາງທຳ ຄຽງຄູ່ກັນ, ສົ່ງເສີມການຮຽນຮູ້ເພື່ອສັນຕິພາບ ແລະ ປັນຍາໃນສັງຄົມຍຸກໃໝ່.',
                'primary_button_text' => 'ສະໝັກຮຽນດຽວນີ້',
                'primary_button_link' => null,
                'secondary_button_text' => 'ທ່ຽວຊົມວິທະຍາໄລ',
                'secondary_button_link' => '/about-us',
                'sort_order' => 0,
                'is_active' => true,
            ]
        );
    }

    private function seedCourseCategories(): void
    {
        $categories = [
            ['name' => 'ພາສາບາລີ (Pali Language)', 'slug' => 'pali', 'sort_order' => 1],
            ['name' => 'ປັດຊະຍາພຸດທະສາສະໜາ (Buddhist Philosophy)', 'slug' => 'philosophy', 'sort_order' => 2],
            ['name' => 'ພຣະທຳວິໄນ (Dharma Studies)', 'slug' => 'dharma', 'sort_order' => 3],
            ['name' => 'ວິຊາສາມັນ (General Education)', 'slug' => 'general', 'sort_order' => 4],
        ];

        foreach ($categories as $category) {
            CourseCategory::updateOrCreate(
                ['slug' => $category['slug']],
                [...$category, 'is_active' => true]
            );
        }
    }

    private function seedCourses(): void
    {
        $categoryIds = CourseCategory::pluck('id', 'slug');

        $courses = [
            [
                'category_id' => $categoryIds['pali'],
                'image_url' => 'https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?auto=format&fit=crop&w=600&q=80',
                'badge_label' => 'ພາສາບາລີ',
                'title' => 'ຫຼັກສູດພາສາບາລີ ຊັ້ນກາງ ແລະ ຊັ້ນສູງ',
                'description' => 'ສຶກສາໄວຍາກອນ, ການແປ ແລະ ການວິເຄາະວັນນະຄະດີພາສາບາລີ ເພື່ອຄວາມເຂົ້າໃຈຢ່າງເລິກເຊິ່ງໃນພຣະໄຕປິດົກ. ເໝາະສຳລັບພຣະສົງ-ສຳມະເນນ ແລະ ຜູ້ສົນໃຈທົ່ວໄປ.',
                'fee_type' => 'free',
                'fee_label' => 'ຮຽນຟຣີ (ສຳລັບພຣະສົງ)',
                'is_featured' => true,
                'sort_order' => 1,
            ],
            [
                'category_id' => $categoryIds['philosophy'],
                'image_url' => 'https://images.unsplash.com/photo-1544816155-12df9643f363?auto=format&fit=crop&w=600&q=80',
                'badge_label' => 'ປັດຊະຍາພຸດທະສາສະໜາ',
                'title' => 'ພຸດທະປັດຊະຍາ ແລະ ສັງຄົມຍຸກໃໝ່',
                'description' => 'ວິເຄາະຫຼັກທຳຄຳສອນຂອງພຣະພຸດທະເຈົ້າໃນມຸມມອງຂອງປັດຊະຍາ ແລະ ການນຳໃຊ້ເຂົ້າໃນການແກ້ໄຂບັນຫາສັງຄົມ ແລະ ການດຳລົງຊີວິດໃນຍຸກປັດຈຸບັນ.',
                'fee_type' => 'paid',
                'fee_label' => '500,000 LAK / ເທີມ',
                'is_featured' => false,
                'sort_order' => 2,
            ],
            [
                'category_id' => $categoryIds['dharma'],
                'image_url' => 'https://images.unsplash.com/photo-1519817650390-64a93db51149?auto=format&fit=crop&w=600&q=80',
                'badge_label' => 'ພຣະທຳວິໄນ',
                'title' => 'ຫຼັກສູດນັກທຳ ຕຣີ-ໂທ-ເອກ',
                'description' => 'ການສຶກສາພື້ນຖານຂອງພຣະທຳ ແລະ ວິໄນສຳລັບພຣະພິກຂຸ-ສຳມະເນນ ເພື່ອເປັນຫຼັກໃນການປະຕິບັດ ແລະ ການເຜີຍແຜ່ພຸດທະສາສະໜາຢ່າງຖືກຕ້ອງ.',
                'fee_type' => 'scholarship',
                'fee_label' => 'ທຶນການສຶກສາເຕັມຈຳນວນ',
                'is_featured' => false,
                'sort_order' => 3,
            ],
            [
                'category_id' => $categoryIds['general'],
                'image_url' => 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?auto=format&fit=crop&w=600&q=80',
                'badge_label' => 'ວິຊາສາມັນ',
                'title' => 'ໄອທີ ແລະ ພາສາອັງກິດ ສຳລັບການເຜີຍແຜ່',
                'description' => 'ເສີມສ້າງທັກສະດ້ານເຕັກໂນໂລຊີຂໍ້ມູນຂ່າວສານ ແລະ ພາສາຕ່າງປະເທດ ເພື່ອນຳໃຊ້ເຂົ້າໃນການສຶກສາຄົ້ນຄວ້າ ແລະ ການເຜີຍແຜ່ພຸດທະສາສະໜາສູ່ສາກົນ.',
                'fee_type' => 'paid',
                'fee_label' => '300,000 LAK / ເທີມ',
                'is_featured' => true,
                'sort_order' => 4,
            ],
            [
                'category_id' => $categoryIds['dharma'],
                'image_url' => 'https://images.unsplash.com/photo-1544816155-12df9643f363?auto=format&fit=crop&w=600&q=80',
                'badge_label' => 'ຮັບສະໝັກດ່ວນ',
                'title' => 'ພຸດທະສາສະໜາສຶກສາ (B.A.)',
                'description' => 'ສຶກສາຫຼັກທຳຄຳສອນ, ປະຫວັດສາດ ແລະ ປັດຊະຍາພຸດທະສາສະໜາຢ່າງເລິກເຊິ່ງ ເພື່ອປະຍຸກໃຊ້ໃນສັງຄົມປະຈຸບັນ.',
                'fee_type' => 'paid',
                'duration_label' => '4 ປີ | ປະລິນຍາຕີ',
                'is_featured' => true,
                'sort_order' => 0,
            ],
        ];

        foreach ($courses as $course) {
            Course::updateOrCreate(['title' => $course['title']], $course);
        }
    }

    private function seedEvents(): void
    {
        $events = [
            [
                'category' => 'academic',
                'title' => 'ງານສຳມະນາພຸດທະສາສະໜາ ແລະ ສັງຄົມລາວໃນຍຸກດິຈິຕອນ',
                'description' => 'ຂໍເຊີນຊວນນັກສຶກສາ ແລະ ພຸດທະສາສະນິກະຊົນທົ່ວໄປ ເຂົ້າຮ່ວມຮັບຟັງການບັນຍາຍພິເສດ ໂດຍພະອາຈານຜູ້ຊົງຄຸນວຸດທິ ກ່ຽວກັບການປັບຕົວຂອງຊາວພຸດໃນຍຸກເຕັກໂນໂລຊີ.',
                'image_url' => 'https://images.unsplash.com/photo-1544816155-12df9643f363?auto=format&fit=crop&w=1000&q=80',
                'badge_label' => 'ກິດຈະກຳພິເສດ',
                'event_date' => '2026-11-15',
                'start_time' => '08:30',
                'end_time' => '16:00',
                'location' => 'ຫໍປະຊຸມໃຫຍ່ ວິທະຍາໄລຄູສົງອົງຕື້',
                'is_featured' => true,
            ],
            [
                'category' => 'social',
                'title' => 'ກິດຈະກຳອະນາໄມລວມ ແລະ ບຳເພັນປະໂຫຍດ',
                'description' => 'ຮ່ວມແຮງຮ່ວມໃຈກັນທຳຄວາມສະອາດບໍລິເວນວັດ ແລະ ຊຸມຊົນອ້ອມຂ້າງ ເພື່ອສ້າງສະພາບແວດລ້ອມທີ່ດີ.',
                'image_url' => 'https://images.unsplash.com/photo-1519817650390-64a93db51149?auto=format&fit=crop&w=600&q=80',
                'badge_label' => 'ເພື່ອສັງຄົມ',
                'event_date' => '2026-11-20',
                'location' => 'ບໍລິເວນວັດອົງຕື້ ແລະ ຊຸມຊົນບ້ານວັດຈັນ',
                'is_featured' => false,
            ],
            [
                'category' => 'religious',
                'title' => 'ພິທີສູດມົນຂ້າມຄືນ ເພື່ອຄວາມເປັນສິລິມຸງຄຸນ',
                'description' => 'ຂໍເຊີນພຸດທະສາສະນິກະຊົນຮ່ວມພິທີສູດມົນຈະເລີນພຸດທະມົນ ເພື່ອຄວາມເປັນສິລິມຸງຄຸນແກ່ຊີວິດ ແລະ ຄອບຄົວ.',
                'image_url' => 'https://images.unsplash.com/photo-1544816155-12df9643f363?auto=format&fit=crop&w=600&q=80',
                'badge_label' => 'ພິທີທາງສາສະໜາ',
                'event_date' => '2026-11-28',
                'location' => 'ພະອຸໂບສົດ ວັດອົງຕື້',
                'is_featured' => false,
            ],
            [
                'category' => 'academic',
                'title' => 'ການນຳສະເໜີບົດຄົ້ນຄວ້າປະຈຳພາກຮຽນ',
                'description' => 'ນັກສຶກສາປີສຸດທ້າຍຈະຂຶ້ນນຳສະເໜີບົດຄົ້ນຄວ້າທາງດ້ານພຸດທະສາສະໜາ ແລະ ປັດຊະຍາ.',
                'image_url' => 'https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?auto=format&fit=crop&w=600&q=80',
                'badge_label' => 'ວິຊາການ',
                'event_date' => '2026-12-04',
                'location' => 'ຫ້ອງປະຊຸມ ອາຄານຮຽນລວມ',
                'is_featured' => false,
            ],
        ];

        foreach ($events as $event) {
            Event::updateOrCreate(['title' => $event['title']], $event);
        }
    }

    private function seedGallery(): void
    {
        $images = [
            [
                'image_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDKvJB-tS2lNy9B7sWA1wtKWXiZ2e1B_jQSB0aojdPhrZzJL4ZviwwHZDM228DoUjBNu-OjhDhN3t1AJ95KRTz7E9z0JJBNsvwN7bSCgWvJ0mEv2q_8hZsDgmvHXl9fo_DOxhgZojJTP7H652tvHqvwzn0Cztx1yrld8d2oQR7787-VsPWohVUew6SaBMrzWuXtbEc9CoIupX6XrDZW7erKy4q5kK35xJx6sTNU1sFcPr1t3vsJ4kZcYg',
                'alt_text' => 'Large detailed photo of a major religious ceremony at the college. Monks chanting, beautiful floral arrangements, golden Buddha statues in the background.',
                'caption' => 'ພິທີທາງສາສະໜາທີ່ສຳຄັນ',
                'sort_order' => 1,
            ],
            [
                'image_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBZvlk4Gje2miPVBV2gW2ObVvPujqSzWlFuqSChrS2aU7Y2hVx1Mck6nrJ1yvmBl7lfbK1Ziq1DqjQ1fXfaEgxXy10Pl1IEPvlYaAEXw3SW1RRM-wZi0X7_njQnIEhiIjWFPaTQIIca2t9kjSo0JWkQHT5aa4QTB-X3Xk4PG93gpODL6C2T4SsuvKyuo_mu9mIzzKbrXBBCtHJ4GMMzFbKCt3OlGO9eqILWl90yv5k13vSmTafQg8V2nQ',
                'alt_text' => "Close up of a monk's hands writing ancient Pali script on parchment.",
                'caption' => null,
                'sort_order' => 2,
            ],
            [
                'image_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDytwOytT6fPya12eSvqDfY9gyq1h2Z60OkMEs_oWvnRGc_XOVfxddJn2Z8ewWKk40cXepN4RDn5WzQ5YvA1PNR26LAmUSjSMhw_MVjKi74S3tikfrbtGtpdoP4r9Hl55EuFbFupz6mIuBKDHa3FO47CcScYkW0TB3oVB_CEAofgj7HWwYlbpROAD7IXEjnzm0IKCkp4HyyXf_qJvlXCblBZDjF-cxwLDQhXi6b9JW0KEDcNOXttH4dVQ',
                'alt_text' => 'Group of young monks studying together in the college library.',
                'caption' => null,
                'sort_order' => 3,
            ],
            [
                'image_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBVVsnwVoajciBT9u4sFQNH3LJ_wH7u7FSK4a9AS3FGei2uoEyO1ZdAgvZi0DRBVYefN0ukvUaVlQuwQ-QMWQjBQwfbxXDHUmgJZ0p4A7MGR_fV1JW9yASUdjIyxkSVDC3Rv7PEXslmhVD42jw6UxeixyUUInJ5oqeFdzZErTNespl2iEH97-5KM1IJrR0CAhS3FK7x5Q-OgrOyDV55GZV3y70klEqVhvVjRZ_B-hSPouWhvOzlIyWeXA',
                'alt_text' => 'Wide landscape view of the college grounds during a community outreach event.',
                'caption' => null,
                'sort_order' => 4,
            ],
        ];

        foreach ($images as $image) {
            GalleryImage::updateOrCreate(['image_url' => $image['image_url']], $image);
        }
    }

    private function seedOrgChart(): void
    {
        $root = OrgDepartment::updateOrCreate(
            ['name' => 'ສະພາວິທະຍາໄລ', 'parent_id' => null],
            ['role_label' => 'ຜູ້ອຳນວຍການ', 'sort_order' => 0]
        );

        $departments = [
            ['name' => 'ພະແນກວິຊາການ', 'icon' => 'menu_book', 'sort_order' => 1],
            ['name' => 'ພະແນກຄຸ້ມຄອງນັກສຶກສາ', 'icon' => 'account_circle', 'sort_order' => 2],
            ['name' => 'ພະແນກບໍລິຫານຈັດການ', 'icon' => 'admin_panel_settings', 'sort_order' => 3],
        ];

        foreach ($departments as $dept) {
            OrgDepartment::updateOrCreate(
                ['name' => $dept['name'], 'parent_id' => $root->id],
                $dept
            );
        }
    }

    private function seedContentBlocks(): void
    {
        $stats = [
            ['block_group' => 'stat_counter', 'title' => 'ປີສ້າງຕັ້ງ', 'value' => '1995', 'sort_order' => 1],
            ['block_group' => 'stat_counter', 'title' => 'ນັກສຶກສາທີ່ຈົບການສຶກສາ', 'value' => '+5000', 'sort_order' => 2],
        ];

        $pillars = [
            ['block_group' => 'mission_pillar', 'icon' => 'school', 'title' => 'ດ້ານການສຶກສາ', 'description' => 'ຈັດການຮຽນການສອນທາງດ້ານພຸດທະສາດ, ປັດຊະຍາ ແລະ ສາສະໜາປຽບທຽບ ໃຫ້ມີຄຸນນະພາບທຽບເທົ່າລະດັບສາກົນ.', 'sort_order' => 1],
            ['block_group' => 'mission_pillar', 'icon' => 'menu_book', 'title' => 'ດ້ານການຄົ້ນຄວ້າ', 'description' => 'ສົງເສີມການວິໄຈ ແລະ ການຄົ້ນຄວ້າທາງວິຊາການ ເພື່ອປະຍຸກໃຊ້ຫຼັກທຳເຂົ້າໃນການແກ້ໄຂບັນຫາສັງຄົມໃນຍຸກປັດຈຸບັນ.', 'sort_order' => 2],
            ['block_group' => 'mission_pillar', 'icon' => 'record_voice_over', 'title' => 'ດ້ານການເຜີຍແຜ່', 'description' => 'ບໍລິການວິຊາການແກ່ສັງຄົມ ຜ່ານການຈັດກິດຈະກຳເຜີຍແຜ່ທຳມະ ແລະ ສົ່ງເສີມຄຸນນະທຳຈະລິຍະທຳ.', 'sort_order' => 3],
            ['block_group' => 'mission_pillar', 'icon' => 'account_balance', 'title' => 'ດ້ານການອະນຸລັກ', 'description' => 'ປົກປັກຮັກສາ ແລະ ສືບສານສິລະປະ, ວັດທະນະທຳ ແລະ ຮີດຄອງປະເພນີອັນດີງາມຂອງຊາດລາວ.', 'sort_order' => 4],
        ];

        $features = [
            ['block_group' => 'home_feature', 'icon' => 'school', 'color' => 'saffron', 'title' => 'ຄວາມເປັນເລີດທາງວິຊາການ', 'description' => 'ຫຼັກສູດທີ່ໄດ້ຮັບການຮັບຮອງມາດຕະຖານ, ເນັ້ນການຄົ້ນຄວ້າ ແລະ ການປະຕິບັດຈິງໃນທຸກຂະແໜງການ.', 'sort_order' => 1],
            ['block_group' => 'home_feature', 'icon' => 'self_improvement', 'color' => 'maroon', 'title' => 'ຄຸນຄ່າທາງພຸດທະສາສະໜາ', 'description' => 'ປູກຝັງຈັນຍາບັນ, ຄຸນນະທຳ ແລະ ການຈະເລີນສະຕິ ໃຫ້ເປັນພື້ນຖານຂອງການດຳລົງຊີວິດ.', 'sort_order' => 2],
            ['block_group' => 'home_feature', 'icon' => 'public', 'color' => 'saffron', 'title' => 'ຊຸມຊົນລະດັບໂລກ', 'description' => 'ເປີດກວ້າງຮັບນັກສຶກສາຈາກທຸກມຸມໂລກ ສ້າງເຄືອຂ່າຍການຮຽນຮູ້ທີ່ຫຼາກຫຼາຍ ແລະ ກວ້າງຂວາງ.', 'sort_order' => 3],
            ['block_group' => 'home_feature', 'icon' => 'auto_stories', 'color' => 'maroon', 'title' => 'ຫໍສະໝຸດທີ່ທັນສະໄໝ', 'description' => 'ແຫຼ່ງລວບລວມຄຳພີ, ຕຳລາ ແລະ ສື່ການຮຽນການສອນທີ່ຄົບຖ້ວນ ທັງຮູບແບບສິ່ງພິມ ແລະ ດິຈິຕອນ.', 'sort_order' => 4],
        ];

        foreach ([...$stats, ...$pillars, ...$features] as $block) {
            ContentBlock::updateOrCreate(
                ['block_group' => $block['block_group'], 'title' => $block['title']],
                $block
            );
        }
    }
}
