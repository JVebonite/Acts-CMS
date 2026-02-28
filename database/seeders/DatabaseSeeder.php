<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Family;
use App\Models\Member;
use App\Models\Visitor;
use App\Models\Service;
use App\Models\Campaign;
use App\Models\ExpenseCategory;
use App\Models\Cluster;
use App\Models\Setting;
use App\Models\SmsTemplate;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Default Admin User
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@actscms.com',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Finance Officer',
            'email' => 'finance@actscms.com',
            'password' => Hash::make('password'),
            'role' => 'finance',
            'is_active' => true,
        ]);

        // Families
        $families = [];
        $familyNames = ['Osei', 'Mensah', 'Asante', 'Boateng', 'Adjei'];
        foreach ($familyNames as $name) {
            $families[] = Family::create([
                'family_name' => $name . ' Family',
                'address' => 'Accra, Ghana',
            ]);
        }

        // Members
        $membersData = [
            ['first_name' => 'Kwame', 'last_name' => 'Osei', 'gender' => 'male', 'phone' => '0241234567', 'email' => 'kwame@example.com', 'membership_status' => 'active', 'family_id' => $families[0]->id],
            ['first_name' => 'Ama', 'last_name' => 'Osei', 'gender' => 'female', 'phone' => '0241234568', 'email' => 'ama@example.com', 'membership_status' => 'active', 'family_id' => $families[0]->id],
            ['first_name' => 'Kofi', 'last_name' => 'Mensah', 'gender' => 'male', 'phone' => '0551234567', 'email' => 'kofi@example.com', 'membership_status' => 'active', 'family_id' => $families[1]->id],
            ['first_name' => 'Abena', 'last_name' => 'Mensah', 'gender' => 'female', 'phone' => '0551234568', 'membership_status' => 'active', 'family_id' => $families[1]->id],
            ['first_name' => 'Yaw', 'last_name' => 'Asante', 'gender' => 'male', 'phone' => '0201234567', 'membership_status' => 'active', 'family_id' => $families[2]->id],
            ['first_name' => 'Akua', 'last_name' => 'Boateng', 'gender' => 'female', 'phone' => '0271234567', 'membership_status' => 'active', 'family_id' => $families[3]->id],
            ['first_name' => 'Kwesi', 'last_name' => 'Adjei', 'gender' => 'male', 'phone' => '0261234567', 'membership_status' => 'active', 'family_id' => $families[4]->id],
            ['first_name' => 'Efua', 'last_name' => 'Adjei', 'gender' => 'female', 'phone' => '0261234568', 'membership_status' => 'active', 'family_id' => $families[4]->id],
            ['first_name' => 'Kojo', 'last_name' => 'Agyemang', 'gender' => 'male', 'phone' => '0231234567', 'membership_status' => 'inactive'],
            ['first_name' => 'Adwoa', 'last_name' => 'Darko', 'gender' => 'female', 'phone' => '0501234567', 'membership_status' => 'active'],
        ];

        $members = [];
        foreach ($membersData as $data) {
            $data['membership_date'] = now()->subDays(rand(30, 730));
            $data['date_of_birth'] = now()->subYears(rand(20, 60));
            $data['created_by'] = 1;
            $members[] = Member::create($data);
        }

        // Visitors
        $visitorsData = [
            ['first_name' => 'Grace', 'last_name' => 'Owusu', 'phone' => '0241111111', 'visit_date' => now()->subDays(3), 'follow_up_status' => 'pending', 'invited_by' => 'Kwame Osei'],
            ['first_name' => 'Emmanuel', 'last_name' => 'Tetteh', 'phone' => '0551111111', 'visit_date' => now()->subDays(7), 'follow_up_status' => 'contacted', 'invited_by' => 'Kofi Mensah'],
            ['first_name' => 'Patience', 'last_name' => 'Amoah', 'email' => 'patience@example.com', 'visit_date' => now()->subDays(14), 'follow_up_status' => 'completed'],
        ];
        foreach ($visitorsData as $data) {
            Visitor::create($data);
        }

        // Services
        $services = [];
        $serviceData = [
            ['name' => 'Sunday Worship', 'type' => 'sunday_service', 'service_date' => now()->previous('Sunday'), 'service_time' => '09:00'],
            ['name' => 'Midweek Service', 'type' => 'midweek', 'service_date' => now()->previous('Wednesday'), 'service_time' => '18:00'],
            ['name' => 'Prayer Meeting', 'type' => 'prayer_meeting', 'service_date' => now()->previous('Friday'), 'service_time' => '06:00'],
        ];
        foreach ($serviceData as $svc) {
            $services[] = Service::create($svc);
        }

        // Campaigns
        Campaign::create([
            'name' => 'Building Fund',
            'description' => 'New church building construction fund',
            'target_amount' => 500000,
            'start_date' => now()->startOfYear(),
            'end_date' => now()->endOfYear(),
            'status' => 'active',
        ]);

        Campaign::create([
            'name' => 'Mission Outreach 2024',
            'description' => 'Annual mission outreach program',
            'target_amount' => 50000,
            'start_date' => now()->subMonths(2),
            'end_date' => now()->addMonths(4),
            'status' => 'active',
        ]);

        // Expense Categories
        $expCats = ['Utilities', 'Maintenance', 'Salaries', 'Events', 'Office Supplies', 'Transport'];
        foreach ($expCats as $cat) {
            ExpenseCategory::create([
                'name' => $cat,
                'description' => $cat . ' expenses',
                'budget_amount' => rand(5000, 30000),
            ]);
        }

        // Clusters
        $cluster1 = Cluster::create([
            'name' => 'Faith Cluster',
            'leader_id' => $members[0]->id,
            'meeting_day' => 'tuesday',
            'meeting_time' => '18:00',
            'location' => 'Block A, Room 3',
            'status' => 'active',
        ]);

        $cluster2 = Cluster::create([
            'name' => 'Grace Cluster',
            'leader_id' => $members[2]->id,
            'meeting_day' => 'thursday',
            'meeting_time' => '18:30',
            'location' => 'Block B, Room 1',
            'status' => 'active',
        ]);

        // Assign members to clusters
        $cluster1->members()->attach([$members[0]->id, $members[1]->id, $members[4]->id, $members[5]->id], ['role' => 'member', 'joined_date' => now()]);
        $cluster2->members()->attach([$members[2]->id, $members[3]->id, $members[6]->id, $members[7]->id], ['role' => 'member', 'joined_date' => now()]);

        // SMS Templates
        SmsTemplate::create(['name' => 'Welcome', 'content' => 'Welcome to ACTS Church! We are glad to have you worship with us. God bless you.', 'category' => 'greeting']);
        SmsTemplate::create(['name' => 'Service Reminder', 'content' => 'Reminder: Join us this Sunday for our worship service at 9:00 AM. See you there!', 'category' => 'reminder']);
        SmsTemplate::create(['name' => 'Birthday Wish', 'content' => 'Happy Birthday! The ACTS Church family celebrates you today. May God bless your new year.', 'category' => 'birthday']);

        // Settings
        Setting::set('church_name', 'ACTS Church', 'general');
        Setting::set('church_address', 'P.O. Box 123, Accra, Ghana', 'general');
        Setting::set('church_phone', '+233 24 123 4567', 'general');
        Setting::set('church_email', 'info@actschurch.org', 'general');
        Setting::set('currency', 'GHS', 'finance');
        Setting::set('sms_provider', 'hubtel', 'sms');
    }
}
