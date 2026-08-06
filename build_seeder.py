import sys
from scratch_gen import informatika_questions, matematika_questions, indo_questions, inggris_questions

def format_q_list(questions, subject):
    lines = []
    lines.append(f"        // Bank Soal untuk: {subject} ({len(questions)} Soal)")
    lines.append(f"        $items_{subject.replace(' ', '_').replace('&', 'and')} = [")
    for q in questions:
        text, a, b, c, d, e, correct = q
        # Escape quotes in text/options
        def esc(s):
            return s.replace('\\', '\\\\').replace("'", "\\'")
        lines.append("            [")
        lines.append(f"                'question_text' => '{esc(text)}',")
        lines.append(f"                'option_a' => '{esc(a)}',")
        lines.append(f"                'option_b' => '{esc(b)}',")
        lines.append(f"                'option_c' => '{esc(c)}',")
        lines.append(f"                'option_d' => '{esc(d)}',")
        lines.append(f"                'option_e' => '{esc(e)}',")
        lines.append(f"                'correct_option' => '{correct}',")
        lines.append("            ],")
        lines.append("        ];\n")
    return "\n".join(lines)

php_content = """<?php

namespace Database\\Seeders;

use App\\Models\\Exam;
use App\\Models\\Question;
use App\\Models\\User;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\Hash;

class CbtSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Admin (Guru)
        $admin = User::firstOrCreate(
            ['email' => 'admin@cbt.com'],
            [
                'name' => 'Guru Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // 2. Create Student (Siswa)
        $student = User::firstOrCreate(
            ['email' => 'siswa@cbt.com'],
            [
                'name' => 'Bening Aura (Siswa)',
                'password' => Hash::make('password'),
                'role' => 'siswa',
                'email_verified_at' => now(),
            ]
        );

        // ========================================================
        // BANK SOAL MASTER (50 Soal Per-Kategori = 200 Soal Total)
        // ========================================================

"""

php_content += format_q_list(informatika_questions, "Informatika & Pemrograman Web")
php_content += format_q_list(matematika_questions, "Matematika")
php_content += format_q_list(indo_questions, "Bahasa Indonesia")
php_content += format_q_list(inggris_questions, "Bahasa Inggris")

php_content += """
        // Simpan seluruh soal ke dalam Bank Soal Master
        $bankInformatika = [];
        foreach ($items_Informatika_and_Pemrograman_Web as $q) {
            $q['subject'] = 'Informatika & Pemrograman Web';
            $bankInformatika[] = Question::create($q)->id;
        }

        $bankMatematika = [];
        foreach ($items_Matematika as $q) {
            $q['subject'] = 'Matematika';
            $bankMatematika[] = Question::create($q)->id;
        }

        $bankIndo = [];
        foreach ($items_Bahasa_Indonesia as $q) {
            $q['subject'] = 'Bahasa Indonesia';
            $bankIndo[] = Question::create($q)->id;
        }

        $bankInggris = [];
        foreach ($items_Bahasa_Inggris as $q) {
            $q['subject'] = 'Bahasa Inggris';
            $bankInggris[] = Question::create($q)->id;
        }

        // ========================================================
        // MEMBUAT PAKET UJIAN & MENARIK SEBAGIAN SOAL DARI BANK SOAL
        // ========================================================

        // EXAM 1: Ujian Informatika (Narik 10 soal dari 50 soal Bank Informatika)
        $exam1 = Exam::create([
            'title' => 'Simulasi Ujian Teknologi Informasi & Informatika 2026',
            'subject' => 'Informatika & Pemrograman Web',
            'duration_minutes' => 20,
            'is_active' => true,
            'created_by' => $admin->id,
        ]);
        $exam1->questions()->attach(array_slice($bankInformatika, 0, 10));

        // EXAM 2: Ujian Matematika (Narik 10 soal dari 50 soal Bank Matematika)
        $exam2 = Exam::create([
            'title' => 'Ujian Akhir Semester Matematika Dasar & Logika',
            'subject' => 'Matematika',
            'duration_minutes' => 30,
            'is_active' => true,
            'created_by' => $admin->id,
        ]);
        $exam2->questions()->attach(array_slice($bankMatematika, 0, 10));

        // EXAM 3: Ujian Bahasa Indonesia (Narik 10 soal dari 50 soal Bank B. Indo)
        $exam3 = Exam::create([
            'title' => 'Tes Literasi & Tata Bahasa Indonesia',
            'subject' => 'Bahasa Indonesia',
            'duration_minutes' => 25,
            'is_active' => true,
            'created_by' => $admin->id,
        ]);
        $exam3->questions()->attach(array_slice($bankIndo, 0, 10));

        // EXAM 4: Ujian Bahasa Inggris (Narik 10 soal dari 50 soal Bank B. Inggris)
        $exam4 = Exam::create([
            'title' => 'English Proficiency & Grammar Test',
            'subject' => 'Bahasa Inggris',
            'duration_minutes' => 20,
            'is_active' => true,
            'created_by' => $admin->id,
        ]);
        $exam4->questions()->attach(array_slice($bankInggris, 0, 10));
    }
}
"""

with open("d:/Laragon_New/www/cbt-online/database/seeders/CbtSeeder.php", "w", encoding="utf-8") as f:
    f.write(php_content)

print("CbtSeeder.php generated successfully!")
