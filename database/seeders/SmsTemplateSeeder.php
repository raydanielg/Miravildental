<?php

namespace Database\Seeders;

use App\Models\SmsTemplate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SmsTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            [
                'name' => 'Ujumbe wa Kukaribisha Mgonjwa',
                'trigger' => 'welcome',
                'category' => 'registration',
                'body' => 'Habari {{name}}, tunakukaribisha katika {{clinic_name}}. Tumepokea usajili wako (Namba ya file: {{file_number}}). Kwa mawasiliano zaidi piga {{clinic_phone}}. -MIRAVIL',
                'is_active' => true,
            ],
            [
                'name' => 'Uthibitisho wa Miadi',
                'trigger' => 'booking_confirmation',
                'category' => 'dental',
                'body' => 'Habari {{name}}, miadi yako katika Miravil Dental imehifadhiwa tarehe {{date}} saa {{time}}. Tutaonana! -MIRAVIL',
                'is_active' => true,
            ],
            [
                'name' => 'Kikumbusho cha Muda wa 24h',
                'trigger' => 'reminder_24h',
                'category' => 'dental',
                'body' => 'Kikumbusho: Una miadi katika Miravil Dental kesho {{date}} saa {{time}}. Tafadhali jibu NDIYO kuthibitisha. -MIRAVIL',
                'is_active' => true,
                'send_before_hours' => 24,
            ],
            [
                'name' => 'Kikumbusho cha Muda wa 2h',
                'trigger' => 'reminder_2h',
                'category' => 'dental',
                'body' => 'Habari {{name}}, una miadi katika Miravil Dental leo saa {{time}}. Tutaonana hivi karibuni! -MIRAVIL',
                'is_active' => true,
                'send_before_hours' => 2,
            ],
            [
                'name' => 'Taarifa ya Kuhamisha Miadi',
                'trigger' => 'reschedule',
                'category' => 'dental',
                'body' => 'Habari {{name}}, miadi yako katika Miravil Dental imehamishwa kuwa {{date}} saa {{time}}. -MIRAVIL',
                'is_active' => true,
            ],
            [
                'name' => 'Taarifa ya Kufuta Miadi',
                'trigger' => 'cancellation',
                'category' => 'dental',
                'body' => 'Habari {{name}}, miadi yako katika Miravil Dental iliyokuwa {{date}} saa {{time}} imefutwa. Piga simu kupanga tena. -MIRAVIL',
                'is_active' => true,
            ],
            [
                'name' => 'Ufuatiliaji Baada ya Matibabu',
                'trigger' => 'follow_up',
                'category' => 'dental',
                'body' => 'Habari {{name}}, tunatumai uko vizuri baada ya kutembelea kliniki yetu. Endelea kuhifadhi usafi wa meno na wasiliana nasi ikiwa kuna lolote. -MIRAVIL',
                'is_active' => true,
                'send_after_days' => 1,
            ],
            [
                'name' => 'Kikumbusho cha Kuhudhuria Tena',
                'trigger' => 'recall',
                'category' => 'dental',
                'body' => 'Habari {{name}}, muda umefika wa kukagua meno yako katika Miravil Dental. Piga simu au andika miadi mtandaoni kwa ziara yako ijayo. -MIRAVIL',
                'is_active' => true,
                'send_after_days' => 180,
            ],
            [
                'name' => 'Maulid wa Mitume (Mawlid)',
                'trigger' => 'holiday_mawlid',
                'category' => 'holiday',
                'body' => 'Heri ya Sikukuu ya Maulid wa Mitume kutoka Miravil Dental. Tunakutakia furaha na amani. -MIRAVIL',
                'is_active' => false,
            ],
            [
                'name' => 'Eid-el-Fitr',
                'trigger' => 'holiday_eid_fitr',
                'category' => 'holiday',
                'body' => 'Eid Mubarak! Miravil Dental inakutakia wewe na familia yako sikukuu njema yenye baraka tele. -MIRAVIL',
                'is_active' => false,
            ],
            [
                'name' => 'Eid-el-Hajj',
                'trigger' => 'holiday_eid_hajj',
                'category' => 'holiday',
                'body' => 'Bakrid Mubarak kutoka Miravil Dental. Mwaka mzima wa afya na baraka! -MIRAVIL',
                'is_active' => false,
            ],
            [
                'name' => 'Krismasi',
                'trigger' => 'holiday_christmas',
                'category' => 'holiday',
                'body' => 'Heri ya Krismasi na Mwaka Mpya kutoka Miravil Dental. Tunakutakia sherehe njema na meno yenye afya. -MIRAVIL',
                'is_active' => false,
            ],
            [
                'name' => 'Mwaka Mpya',
                'trigger' => 'holiday_new_year',
                'category' => 'holiday',
                'body' => 'Miravil Dental inakutakia Heri ya Mwaka Mpya! Tunakutakia mwaka 2026 wa afya tele na tabasamu nzuri. -MIRAVIL',
                'is_active' => false,
            ],
            [
                'name' => 'Siku ya Wanawake',
                'trigger' => 'holiday_women_day',
                'category' => 'holiday',
                'body' => 'Heri ya Siku ya Wanawake Duniani. Miravil Dental inawapongeza wote kwa mchango wenu mkubwa. -MIRAVIL',
                'is_active' => false,
            ],
            [
                'name' => 'Siku ya Wafanyakazi',
                'trigger' => 'holiday_labour_day',
                'category' => 'holiday',
                'body' => 'Heri ya Mei Mosi. Miravil Dental inakutakia sikukuu njema yenye raha na mapumziko. -MIRAVIL',
                'is_active' => false,
            ],
            [
                'name' => 'Siku ya Muungano',
                'trigger' => 'holiday_union_day',
                'category' => 'holiday',
                'body' => 'Heri ya Maadhimisho ya Muungano wa Tanganyika na Zanzibar kutoka Miravil Dental. -MIRAVIL',
                'is_active' => false,
            ],
            [
                'name' => 'Siku ya Uhuru',
                'trigger' => 'holiday_independence',
                'category' => 'holiday',
                'body' => 'Heri ya Sikukuu ya Uhuru wa Tanzania. Miravil Dental inakutakia sherehe njema. -MIRAVIL',
                'is_active' => false,
            ],
            [
                'name' => 'Siku ya Nane Nane',
                'trigger' => 'holiday_nane_nane',
                'category' => 'holiday',
                'body' => 'Heri ya Sikukuu ya Wakulima na Wafugaji (Nane Nane) kutoka Miravil Dental. -MIRAVIL',
                'is_active' => false,
            ],
        ];

        foreach ($templates as $template) {
            SmsTemplate::updateOrCreate(['trigger' => $template['trigger']], $template);
        }
    }
}
