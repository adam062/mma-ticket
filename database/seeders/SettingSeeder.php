<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['event', 'name_en', 'MMA Championship 2026', 'string'],
            ['event', 'name_ar', 'بطولة MMA ٢٠٢٦', 'string'],
            ['event', 'date', '2026-12-15', 'string'],
            ['event', 'time', '20:00', 'string'],
            ['event', 'location_en', 'Cairo International Stadium, Cairo, Egypt', 'string'],
            ['event', 'location_ar', 'ملعب القاهرة الدولي، القاهرة، مصر', 'string'],
            ['event', 'description_en', 'Witness the ultimate MMA showdown featuring world-class fighters in an unforgettable night of combat sports.', 'string'],
            ['event', 'description_ar', 'شهدوا أقوى معركة MMA مع لاعبين عالميين في ليلة لا تُنسى من رياضة القتال.', 'string'],
            ['event', 'logo', null, 'string'],

            ['payment', 'vodafone_cash_number', '01000000000', 'string'],
            ['payment', 'vodafone_cash_name', 'MMA Championship', 'string'],
            ['payment', 'instapay_account', 'mma@championship.com', 'string'],
            ['payment', 'instapay_name', 'MMA Championship', 'string'],
            ['payment', 'instructions_en', "Step 1: Check the total amount.\nStep 2: Transfer the exact amount using Vodafone Cash or InstaPay.\nStep 3: Enter the phone/account you transferred from.\nStep 4: Upload a screenshot of the successful transfer.\nStep 5: Submit your request.\nStep 6: Wait for manual verification.", 'string'],
            ['payment', 'instructions_ar', "الخطوة ١: تحقق من المبلغ الإجمالي.\nالخطوة ٢: حول المبلغ المحدد باستخدام فودافون كاش أو إنستاباي.\nالخطوة ٣: أدخل رقم الهاتف/الحساب الذي قمت بهتجاوزه.\nالخطوة ٤: قم بتحميل لقطة شاشة للتحويل الناجح.\nالخطوة ٥: أرسل طلبك.\nالخطوة ٦: انتظر المراجعة اليدوية.", 'string'],

            ['telegram', 'bot_username', 'mma_championship_bot', 'string'],
            ['telegram', 'enabled', '0', 'boolean'],

            ['tickets', 'serial_prefix', 'MMA-2026', 'string'],
        ];

        foreach ($settings as $setting) {
            Setting::set($setting[0], $setting[1], $setting[2], $setting[3]);
        }
    }
}
