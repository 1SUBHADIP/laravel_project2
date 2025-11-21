<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Member;

class MemberSeeder extends Seeder
{
    public function run()
    {
        $members = [
            [
                'name' => 'John Smith',
                'email' => 'john.smith@email.com',
                'phone' => '+1-555-0101',
                'address' => '123 Main Street, Springfield, IL 62701',
                'membership_type' => 'Premium',
                'membership_date' => '2024-01-15',
                'status' => 'Active'
            ],
            [
                'name' => 'Emily Johnson',
                'email' => 'emily.johnson@email.com',
                'phone' => '+1-555-0102',
                'address' => '456 Oak Avenue, Springfield, IL 62702',
                'membership_type' => 'Standard',
                'membership_date' => '2024-02-20',
                'status' => 'Active'
            ],
            [
                'name' => 'Michael Davis',
                'email' => 'michael.davis@email.com',
                'phone' => '+1-555-0103',
                'address' => '789 Pine Road, Springfield, IL 62703',
                'membership_type' => 'Premium',
                'membership_date' => '2024-01-30',
                'status' => 'Active'
            ],
            [
                'name' => 'Sarah Wilson',
                'email' => 'sarah.wilson@email.com',
                'phone' => '+1-555-0104',
                'address' => '321 Elm Street, Springfield, IL 62704',
                'membership_type' => 'Standard',
                'membership_date' => '2024-03-10',
                'status' => 'Active'
            ],
            [
                'name' => 'David Brown',
                'email' => 'david.brown@email.com',
                'phone' => '+1-555-0105',
                'address' => '654 Maple Drive, Springfield, IL 62705',
                'membership_type' => 'Student',
                'membership_date' => '2024-09-01',
                'status' => 'Active'
            ],
            [
                'name' => 'Lisa Anderson',
                'email' => 'lisa.anderson@email.com',
                'phone' => '+1-555-0106',
                'address' => '987 Cedar Lane, Springfield, IL 62706',
                'membership_type' => 'Premium',
                'membership_date' => '2023-12-05',
                'status' => 'Active'
            ],
            [
                'name' => 'Robert Taylor',
                'email' => 'robert.taylor@email.com',
                'phone' => '+1-555-0107',
                'address' => '147 Birch Boulevard, Springfield, IL 62707',
                'membership_type' => 'Standard',
                'membership_date' => '2024-04-18',
                'status' => 'Active'
            ],
            [
                'name' => 'Jennifer Martinez',
                'email' => 'jennifer.martinez@email.com',
                'phone' => '+1-555-0108',
                'address' => '258 Walnut Way, Springfield, IL 62708',
                'membership_type' => 'Student',
                'membership_date' => '2024-08-25',
                'status' => 'Active'
            ],
            [
                'name' => 'Christopher Lee',
                'email' => 'christopher.lee@email.com',
                'phone' => '+1-555-0109',
                'address' => '369 Ash Court, Springfield, IL 62709',
                'membership_type' => 'Premium',
                'membership_date' => '2024-01-08',
                'status' => 'Active'
            ],
            [
                'name' => 'Amanda White',
                'email' => 'amanda.white@email.com',
                'phone' => '+1-555-0110',
                'address' => '741 Spruce Street, Springfield, IL 62710',
                'membership_type' => 'Standard',
                'membership_date' => '2024-05-12',
                'status' => 'Active'
            ],
            [
                'name' => 'Daniel Garcia',
                'email' => 'daniel.garcia@email.com',
                'phone' => '+1-555-0111',
                'address' => '852 Hickory Hill, Springfield, IL 62711',
                'membership_type' => 'Student',
                'membership_date' => '2024-09-03',
                'status' => 'Active'
            ],
            [
                'name' => 'Michelle Thomas',
                'email' => 'michelle.thomas@email.com',
                'phone' => '+1-555-0112',
                'address' => '963 Poplar Place, Springfield, IL 62712',
                'membership_type' => 'Premium',
                'membership_date' => '2023-11-20',
                'status' => 'Active'
            ],
            [
                'name' => 'Kevin Rodriguez',
                'email' => 'kevin.rodriguez@email.com',
                'phone' => '+1-555-0113',
                'address' => '159 Dogwood Drive, Springfield, IL 62713',
                'membership_type' => 'Standard',
                'membership_date' => '2024-06-07',
                'status' => 'Suspended'
            ],
            [
                'name' => 'Rachel Miller',
                'email' => 'rachel.miller@email.com',
                'phone' => '+1-555-0114',
                'address' => '267 Willow Creek, Springfield, IL 62714',
                'membership_type' => 'Premium',
                'membership_date' => '2024-02-14',
                'status' => 'Active'
            ],
            [
                'name' => 'James Wilson',
                'email' => 'james.wilson@email.com',
                'phone' => '+1-555-0115',
                'address' => '378 Magnolia Avenue, Springfield, IL 62715',
                'membership_type' => 'Student',
                'membership_date' => '2024-08-30',
                'status' => 'Active'
            ],
            [
                'name' => 'Nicole Moore',
                'email' => 'nicole.moore@email.com',
                'phone' => '+1-555-0116',
                'address' => '489 Sycamore Street, Springfield, IL 62716',
                'membership_type' => 'Standard',
                'membership_date' => '2024-03-28',
                'status' => 'Active'
            ],
            [
                'name' => 'Matthew Jackson',
                'email' => 'matthew.jackson@email.com',
                'phone' => '+1-555-0117',
                'address' => '591 Chestnut Circle, Springfield, IL 62717',
                'membership_type' => 'Premium',
                'membership_date' => '2023-10-15',
                'status' => 'Active'
            ],
            [
                'name' => 'Stephanie Clark',
                'email' => 'stephanie.clark@email.com',
                'phone' => '+1-555-0118',
                'address' => '692 Beech Road, Springfield, IL 62718',
                'membership_type' => 'Student',
                'membership_date' => '2024-09-05',
                'status' => 'Active'
            ],
            [
                'name' => 'Andrew Harris',
                'email' => 'andrew.harris@email.com',
                'phone' => '+1-555-0119',
                'address' => '793 Redwood Lane, Springfield, IL 62719',
                'membership_type' => 'Standard',
                'membership_date' => '2024-04-22',
                'status' => 'Active'
            ],
            [
                'name' => 'Jessica Lewis',
                'email' => 'jessica.lewis@email.com',
                'phone' => '+1-555-0120',
                'address' => '894 Fir Valley, Springfield, IL 62720',
                'membership_type' => 'Premium',
                'membership_date' => '2024-01-25',
                'status' => 'Active'
            ]
        ];

        foreach ($members as $member) {
            Member::create($member);
        }
    }
}
