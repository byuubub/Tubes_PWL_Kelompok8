<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Hospital;
use App\Models\User;
use App\Models\Specialization;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Staff;
use App\Models\Schedule;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use App\Models\Prescription;
use App\Models\Bill;
use App\Models\Payment;
use App\Models\Queue;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat spesialisasi
        $specializations = [
            ['name' => 'Umum', 'description' => 'Dokter umum untuk pemeriksaan awal'],
            ['name' => 'Jantung', 'description' => 'Spesialis penyakit jantung'],
            ['name' => 'Anak', 'description' => 'Spesialis kesehatan anak'],
            ['name' => 'Bedah', 'description' => 'Spesialis bedah umum'],
            ['name' => 'Saraf', 'description' => 'Spesialis saraf'],
            ['name' => 'THT', 'description' => 'Telinga, hidung, tenggorokan'],
        ];
        foreach ($specializations as $spec) {
            Specialization::create($spec);
        }

        // 2. Buat rumah sakit
        $hospital1 = Hospital::create([
            'name' => 'RS Sehat Sentosa',
            'code' => 'RSSS',
            'city' => 'Jakarta',
            'address' => 'Jl. Kesehatan No. 1, Jakarta Pusat',
            'logo' => null,
            'is_active' => true,
        ]);

        $hospital2 = Hospital::create([
            'name' => 'RS Bunda Medika',
            'code' => 'RSBM',
            'city' => 'Bandung',
            'address' => 'Jl. Melati No. 10, Bandung',
            'logo' => null,
            'is_active' => true,
        ]);

        // 3. Super Admin
        User::create([
            'hospital_id' => null,
            'name' => 'Super Admin',
            'email' => 'superadmin@hospital.com',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
            'phone' => '081234567890',
            'address' => 'Kantor Pusat',
            'gender' => 'L',
            'date_of_birth' => '1980-01-01',
            'is_active' => true,
        ]);

        // 4. Admin RS Sehat Sentosa
        $adminRs1 = User::create([
            'hospital_id' => $hospital1->id,
            'name' => 'Admin RS Sehat',
            'email' => 'admin@rssehat.com',
            'password' => Hash::make('password'),
            'role' => 'admin_rs',
            'phone' => '081234567891',
            'address' => 'Jl. Kesehatan No. 1',
            'gender' => 'L',
            'date_of_birth' => '1985-05-10',
            'is_active' => true,
        ]);

        // 5. Staff RS Sehat Sentosa
        $staffUser1 = User::create([
            'hospital_id' => $hospital1->id,
            'name' => 'Siti Resepsionis',
            'email' => 'staff@rssehat.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'phone' => '081234567892',
            'address' => 'Jl. Kesehatan No. 1',
            'gender' => 'P',
            'date_of_birth' => '1990-02-20',
            'is_active' => true,
        ]);
        Staff::create([
            'user_id' => $staffUser1->id,
            'position' => 'Receptionist & Cashier',
            'department' => 'Front Office',
        ]);

        // 6. Dokter RS Sehat Sentosa
        // Dokter umum
        $dokterUser1 = User::create([
            'hospital_id' => $hospital1->id,
            'name' => 'dr. Budi Santoso',
            'email' => 'dokter.budi@rssehat.com',
            'password' => Hash::make('password'),
            'role' => 'dokter',
            'phone' => '081234567893',
            'address' => 'Jl. Dokter No. 5',
            'gender' => 'L',
            'date_of_birth' => '1975-03-15',
            'is_active' => true,
        ]);
        $doctor1 = Doctor::create([
            'user_id' => $dokterUser1->id,
            'specialization_id' => Specialization::where('name', 'Umum')->first()->id,
            'licence_number' => 'LIC001',
            'consultation_fee' => 150000,
            'years_of_experience' => 15,
        ]);

        // Dokter jantung
        $dokterUser2 = User::create([
            'hospital_id' => $hospital1->id,
            'name' => 'dr. Citra Dewi, Sp.JP',
            'email' => 'dokter.citra@rssehat.com',
            'password' => Hash::make('password'),
            'role' => 'dokter',
            'phone' => '081234567894',
            'address' => 'Jl. Dokter No. 6',
            'gender' => 'P',
            'date_of_birth' => '1980-07-22',
            'is_active' => true,
        ]);
        $doctor2 = Doctor::create([
            'user_id' => $dokterUser2->id,
            'specialization_id' => Specialization::where('name', 'Jantung')->first()->id,
            'licence_number' => 'LIC002',
            'consultation_fee' => 300000,
            'years_of_experience' => 12,
        ]);

        // 7. Jadwal dokter
        // dr. Budi (Umum): Senin & Rabu jam 08-12
        Schedule::create([
            'doctor_id' => $doctor1->id,
            'day_of_week' => 1,
            'start_time' => '08:00:00',
            'end_time' => '12:00:00',
            'max_patients' => 15,
            'is_active' => true,
        ]);
        Schedule::create([
            'doctor_id' => $doctor1->id,
            'day_of_week' => 3,
            'start_time' => '08:00:00',
            'end_time' => '12:00:00',
            'max_patients' => 15,
            'is_active' => true,
        ]);
        // dr. Citra (Jantung): Selasa & Kamis jam 09-13
        Schedule::create([
            'doctor_id' => $doctor2->id,
            'day_of_week' => 2,
            'start_time' => '09:00:00',
            'end_time' => '13:00:00',
            'max_patients' => 10,
            'is_active' => true,
        ]);
        Schedule::create([
            'doctor_id' => $doctor2->id,
            'day_of_week' => 4,
            'start_time' => '09:00:00',
            'end_time' => '13:00:00',
            'max_patients' => 10,
            'is_active' => true,
        ]);

        // 8. Pasien RS Sehat Sentosa
        $pasienUser1 = User::create([
            'hospital_id' => $hospital1->id,
            'name' => 'Andi Wijaya',
            'email' => 'andi@pasien.com',
            'password' => Hash::make('password'),
            'role' => 'pasien',
            'phone' => '081234567895',
            'address' => 'Jl. Pasien No. 10',
            'gender' => 'L',
            'date_of_birth' => '1990-01-01',
            'is_active' => true,
        ]);
        $patient1 = Patient::create([
            'user_id' => $pasienUser1->id,
            'hospital_id' => $hospital1->id,
            'medical_record_number' => 'RSSS/001',
            'blood_type' => 'O',
            'allergies' => 'Amoxicillin',
            'emergency_contact_name' => 'Budi (Ayah)',
            'emergency_contact_phone' => '081234567896',
            'insurance_provider' => 'BPJS',
            'insurance_policy_number' => 'BPJS001',
        ]);

        $pasienUser2 = User::create([
            'hospital_id' => $hospital1->id,
            'name' => 'Siti Aisyah',
            'email' => 'siti@pasien.com',
            'password' => Hash::make('password'),
            'role' => 'pasien',
            'phone' => '081234567897',
            'address' => 'Jl. Pasien No. 12',
            'gender' => 'P',
            'date_of_birth' => '1985-05-05',
            'is_active' => true,
        ]);
        $patient2 = Patient::create([
            'user_id' => $pasienUser2->id,
            'hospital_id' => $hospital1->id,
            'medical_record_number' => 'RSSS/002',
            'blood_type' => 'A',
            'allergies' => null,
            'emergency_contact_name' => 'Diah (Ibu)',
            'emergency_contact_phone' => '081234567898',
            'insurance_provider' => null,
            'insurance_policy_number' => null,
        ]);

        // 9. Appointment (janji temu) untuk pasien kontrol
        $appointment1 = Appointment::create([
            'patient_id' => $patient1->id,
            'doctor_id' => $doctor2->id,
            'scheduled_at' => Carbon::tomorrow()->setTime(10, 0),
            'status' => 'scheduled',
            'complaint' => 'Sesak napas dan nyeri dada',
        ]);

        // 10. Rekam medis kunjungan pertama (tanpa appointment)
        $medicalRecord1 = MedicalRecord::create([
            'patient_id' => $patient1->id,
            'doctor_id' => $doctor1->id,
            'appointment_id' => null,
            'visit_date' => Carbon::now()->subDays(5),
            'diagnosis' => 'Hipertensi tingkat 1',
            'treatment_plan' => 'Kontrol tekanan darah, diet rendah garam, rujuk ke spesialis jantung',
            'notes' => 'Pasien direkomendasikan kontrol ke dr. Citra',
            'case_status' => 'active',
        ]);

        // Resep
        Prescription::create([
            'medical_record_id' => $medicalRecord1->id,
            'medication_name' => 'Amlodipine 5mg',
            'dosage' => '1 tablet per hari',
            'duration' => '30 hari',
            'notes' => 'Setelah makan',
        ]);

        // Tagihan untuk kunjungan pertama (sudah lunas)
        $bill1 = Bill::create([
            'patient_id' => $patient1->id,
            'appointment_id' => null,
            'total_amount' => $doctor1->consultation_fee,
            'status' => 'paid',
            'payment_due_date' => Carbon::now()->addDays(7),
        ]);
        Payment::create([
            'bill_id' => $bill1->id,
            'amount' => $doctor1->consultation_fee,
            'payment_method' => 'bank_transfer',
            'payment_date' => Carbon::now()->subDays(4),
            'reference_number' => 'TRX001',
        ]);

        // 11. Antrian untuk hari ini
        Queue::truncate(); // hapus data lama jika ada
        Queue::create([
            'patient_id' => $patient1->id,
            'doctor_id' => $doctor2->id,
            'queue_date' => Carbon::today(),
            'queue_number' => 1,
            'type' => 'appointment',
            'appointment_id' => $appointment1->id,
            'status' => 'waiting',
            'priority' => 1,
        ]);
        Queue::create([
            'patient_id' => $patient2->id,
            'doctor_id' => $doctor1->id,
            'queue_date' => Carbon::today(),
            'queue_number' => 2,
            'type' => 'walk_in',
            'appointment_id' => null,
            'status' => 'waiting',
            'priority' => 2,
        ]);

        // 12. Data untuk RS kedua (minimal admin dan staff)
        User::create([
            'hospital_id' => $hospital2->id,
            'name' => 'Admin RS Bunda',
            'email' => 'admin@rsbunda.com',
            'password' => Hash::make('password'),
            'role' => 'admin_rs',
            'phone' => '081234567899',
            'address' => 'Jl. Melati No. 10',
            'gender' => 'P',
            'date_of_birth' => '1992-08-15',
            'is_active' => true,
        ]);

        $staffRsBunda = User::create([
            'hospital_id' => $hospital2->id,
            'name' => 'Rina Kasir',
            'email' => 'staff@rsbunda.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'phone' => '081234567900',
            'address' => 'Jl. Melati No. 10',
            'gender' => 'P',
            'date_of_birth' => '1995-05-20',
            'is_active' => true,
        ]);
        Staff::create([
            'user_id' => $staffRsBunda->id,
            'position' => 'Receptionist & Cashier',
            'department' => 'Front Office',
        ]);

        $this->command->info('Seeder berhasil dijalankan!');
    }
}