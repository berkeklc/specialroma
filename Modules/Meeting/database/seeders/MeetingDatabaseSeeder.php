<?php

declare(strict_types=1);

namespace Modules\Meeting\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Meeting\App\Models\Staff;

final class MeetingDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $workingHours = [
            'monday' => ['enabled' => true, 'start' => '09:00', 'end' => '17:00'],
            'tuesday' => ['enabled' => true, 'start' => '09:00', 'end' => '17:00'],
            'wednesday' => ['enabled' => true, 'start' => '09:00', 'end' => '17:00'],
            'thursday' => ['enabled' => true, 'start' => '09:00', 'end' => '17:00'],
            'friday' => ['enabled' => true, 'start' => '09:00', 'end' => '17:00'],
            'saturday' => ['enabled' => false, 'start' => '10:00', 'end' => '14:00'],
            'sunday' => ['enabled' => false, 'start' => '10:00', 'end' => '14:00'],
        ];

        Staff::firstOrCreate(
            ['email' => 'bookings@agencystack.local'],
            [
                'name' => 'Default Staff',
                'title' => ['en' => 'Consultant', 'tr' => 'Danışman'],
                'bio' => [
                    'en' => 'Default booking staff for demos and fresh installs.',
                    'tr' => 'Demo ve yeni kurulumlar için varsayılan randevu personeli.',
                ],
                'working_hours' => $workingHours,
                'meeting_duration' => 30,
                'buffer_time' => 0,
                'is_active' => true,
            ]
        );

        $this->command?->info('Meeting default staff seeded.');
    }
}
