<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Exam;
use App\Models\Question;
use Illuminate\Support\Facades\Hash;

class CbtSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Admin & Siswa Users
        $admin = User::updateOrCreate(
            ['email' => 'admin@cbt.com'],
            ['name' => 'Admin Guru', 'password' => Hash::make('password'), 'role' => 'admin']
        );
        $siswa = User::updateOrCreate(
            ['email' => 'siswa@cbt.com'],
            ['name' => 'Siswa', 'password' => Hash::make('password'), 'role' => 'siswa']
        );

        // =====================================================================
        // MAPEL 1: Pendidikan Agama dan Budi Pekerti (50 Soal Unik)
        // =====================================================================
        $bank_agama = [];
        $agama_questions = [
            ['Apa arti dari sifat wajib Allah "Ar-Rahman"?', 'Maha Pengasih', 'Maha Penyayang', 'Maha Kuasa', 'Maha Mengetahui', 'Maha Adil', 'a'],
            ['Kitab suci Al-Qur\'an diturunkan kepada Nabi Muhammad SAW melalui malaikat...', 'Mikail', 'Jibril', 'Israfil', 'Izrail', 'Munkar', 'b'],
            ['Sikap menghormati dan menghargai perbedaan pendapat dan keyakinan disebut...', 'Jujur (Siddiq)', 'Amanah', 'Toleransi (Tasamuh)', 'Tawadhu', 'Istiqamah', 'c'],
            ['Rukun Islam yang keempat adalah...', 'Mendirikan Shalat', 'Mengucapkan Kalimat Syahadat', 'Berpuasa di Bulan Ramadhan', 'Menunaikan Zakat', 'Menunaikan Ibadah Haji', 'd'],
            ['Huruf-huruf yang termasuk kelompok Qalqalah adalah...', 'Alif, Ba, Ta, Tsa, Jim', 'Jim, Ha, Kha, Dal, Dzal', 'Sin, Syin, Sad, Dad, Tha', 'Nun, Mim, Wau, Ya, Lam', 'Ba, Jim, Dal, Tha, Qaf', 'e'],
            ['Perilaku berkata jujur dan sesuai dengan kenyataan disebut...', 'Siddiq', 'Amanah', 'Tabligh', 'Fathanah', 'Husnuzhan', 'a'],
            ['Shalat sunnah yang dilakukan pada malam hari di bulan Ramadhan dinamakan...', 'Shalat Dhuha', 'Shalat Tarawih', 'Shalat Tahajud', 'Shalat Istikhara', 'Shalat Witir', 'b'],
            ['Orang yang berhak menerima zakat dinamakan...', 'Muzakki', 'Amil', 'Mustahik', 'Munfiq', 'Mukallaf', 'c'],
            ['Sikap rendah hati dan tidak sombong dalam Islam disebut...', 'Takabur', 'Riya', 'Hasad', 'Tawadhu', 'Nifak', 'd'],
            ['Nabi yang diutus kepada kaum Tsamud adalah...', 'Nabi Musa AS', 'Nabi Ibrahim AS', 'Nabi Shaleh AS', 'Nabi Luth AS', 'Nabi Hud AS', 'c'],
            ['Bacaan yang dibaca saat ruku\' adalah...', 'Subhana Rabbiyal A\'la', 'Subhana Rabbiyal Adzim', 'Sami\'allahu Liman Hamidah', 'Rabbana Lakal Hamd', 'Allahu Akbar', 'b'],
            ['Zakat fitrah wajib dikeluarkan sebesar...', '1 sha\' = sekitar 2,5 kg beras', '1 sha\' = sekitar 3,5 liter beras', '2,5% dari harta simpanan', '1/40 dari harta', '5% dari pendapatan', 'a'],
            ['Akhlak yang dicontohkan Rasulullah SAW ketika menghadapi musuh adalah...', 'Balas dendam setimpal', 'Memaafkan dan berlapang dada', 'Menghukum seberat-beratnya', 'Mengusir dari negeri', 'Memutus hubungan dagang', 'b'],
            ['Malaikat yang bertugas mencabut nyawa adalah...', 'Malaikat Jibril', 'Malaikat Mikail', 'Malaikat Israfil', 'Malaikat Izrail', 'Malaikat Ridwan', 'd'],
            ['Iman kepada Qada dan Qadar Allah termasuk rukun iman yang ke...', 'Tiga', 'Empat', 'Lima', 'Enam', 'Dua', 'd'],
            ['Hukum bacaan yang terjadi ketika nun sukun atau tanwin bertemu huruf ba\' disebut...', 'Idzhar', 'Idgham', 'Iqlab', 'Ikhfa\'', 'Qalqalah', 'c'],
            ['Sifat mustahil bagi Allah yang berarti tidak ada permulaan disebut...', 'Baqa\'', 'Qidam', 'Huduts', 'Fana\'', 'Adam', 'b'],
            ['Puasa yang hukumnya wajib adalah puasa...', 'Senin dan Kamis', 'Syawal', 'Ramadhan', 'Arafah', 'Daud', 'c'],
            ['Jumlah rakaat shalat Maghrib adalah...', 'Dua rakaat', 'Tiga rakaat', 'Empat rakaat', 'Lima rakaat', 'Enam rakaat', 'b'],
            ['Nabi yang dilempar ke dalam api oleh Raja Namrud adalah...', 'Nabi Muhammad SAW', 'Nabi Musa AS', 'Nabi Ibrahim AS', 'Nabi Isa AS', 'Nabi Adam AS', 'c'],
            ['Hukum mempelajari ilmu fardhu ain adalah...', 'Sunnah', 'Mubah', 'Fardhu kifayah', 'Fardhu ain', 'Makruh', 'd'],
            ['Nabi yang mendapat gelar "Ulul Azmi" berjumlah...', 'Dua nabi', 'Tiga nabi', 'Empat nabi', 'Lima nabi', 'Enam nabi', 'd'],
            ['Kalimat tayyibah yang diucapkan ketika mendapat musibah adalah...', 'Subhanallah', 'Alhamdulillah', 'Innalillahi wa inna ilaihi raji\'un', 'Allahu Akbar', 'Astaghfirullah', 'c'],
            ['Wudhu menjadi batal apabila...', 'Tidur dalam posisi duduk yang tidak nyaman', 'Tertidur pulas berbaring', 'Batuk keras', 'Bersin', 'Berkeringat banyak', 'b'],
            ['Peristiwa Isra\' Mi\'raj terjadi pada tahun...', 'Pertama hijriah', 'Kesepuluh kenabian', 'Kedua hijriah', 'Kelima kenabian', 'Ketiga hijriah', 'b'],
            ['Syarat sah shalat yang berkaitan dengan pakaian adalah...', 'Pakaian berwarna putih', 'Pakaian bersih dari najis', 'Pakaian mahal', 'Pakaian berbahan katun', 'Pakaian baru', 'b'],
            ['Arti dari "Amar Ma\'ruf Nahi Munkar" adalah...', 'Menyebarkan kebaikan di mana-mana', 'Memerintahkan kebaikan dan mencegah kemungkaran', 'Menghindari perbuatan dosa', 'Selalu berbuat baik kepada sesama', 'Melarang segala perbuatan', 'b'],
            ['Kitab yang diturunkan kepada Nabi Musa AS adalah...', 'Injil', 'Zabur', 'Taurat', 'Al-Qur\'an', 'Suhuf', 'c'],
            ['Azan dikumandangkan sebagai tanda...', 'Shalat sedang berlangsung', 'Masuknya waktu shalat', 'Shalat akan segera selesai', 'Peringatan bahaya', 'Pertemuan umat Islam', 'b'],
            ['Sifat wajib Allah "Al-Qudrah" artinya...', 'Maha Mengetahui', 'Maha Mendengar', 'Maha Kuasa', 'Maha Melihat', 'Maha Hidup', 'c'],
            ['Ibadah haji menjadi wajib bagi orang yang sudah memenuhi syarat, salah satunya adalah...', 'Berusia minimal 17 tahun', 'Mampu secara finansial', 'Sudah menikah', 'Bekerja sebagai PNS', 'Memiliki rumah sendiri', 'b'],
            ['Nabi yang menerima wahyu berupa Kitab Zabur adalah...', 'Nabi Ibrahim AS', 'Nabi Musa AS', 'Nabi Dawud AS', 'Nabi Isa AS', 'Nabi Muhammad SAW', 'c'],
            ['Doa iftitah dibaca pada saat...', 'Sebelum takbiratul ihram', 'Setelah takbiratul ihram sebelum membaca Al-Fatihah', 'Setelah membaca Al-Fatihah', 'Saat ruku\'', 'Saat sujud', 'b'],
            ['Tindakan memutuskan silaturahmi dalam Islam hukumnya...', 'Makruh', 'Sunnah', 'Mubah', 'Haram', 'Wajib', 'd'],
            ['Hukum bacaan idzhar terjadi apabila nun sukun atau tanwin bertemu dengan huruf...', 'Ba\'', 'Mim', 'Wau', 'Ha\', Ain, Ghain, Kha\', Hamzah', 'Ya\'', 'd'],
            ['Shalat yang dikerjakan ketika terjadi gerhana matahari disebut shalat...', 'Istisqa\'', 'Khusuf', 'Kusuf', 'Dhuha', 'Tahiyatul masjid', 'c'],
            ['Konsep "Husnuzhan" dalam Islam berarti...', 'Berprasangka buruk kepada orang lain', 'Berprasangka baik kepada orang lain', 'Tidak peduli dengan orang lain', 'Selalu mencurigai orang lain', 'Menjauhi pergaulan', 'b'],
            ['Salah satu hikmah berpuasa Ramadhan adalah...', 'Menambah kekayaan', 'Melatih kesabaran dan mengendalikan hawa nafsu', 'Membuat badan menjadi kurus', 'Menghemat pengeluaran makan', 'Meningkatkan status sosial', 'b'],
            ['Nabi yang dimasukkan ke dalam penjara karena fitnah di Mesir adalah...', 'Nabi Musa AS', 'Nabi Yusuf AS', 'Nabi Ibrahim AS', 'Nabi Yakub AS', 'Nabi Sulaiman AS', 'b'],
            ['Shalat Jumat hukumnya wajib bagi...', 'Semua muslim', 'Laki-laki muslim yang baligh, berakal, merdeka, dan mukim', 'Perempuan muslim yang dewasa', 'Semua orang dewasa', 'Muslim yang kaya', 'b'],
            ['Pengertian "Al-Asma Al-Husna" adalah...', 'Nama-nama Nabi yang agung', 'Nama-nama Allah yang indah dan terbaik', 'Sifat-sifat manusia yang sempurna', 'Doa-doa yang mustajab', 'Ayat-ayat pilihan dalam Al-Qur\'an', 'b'],
            ['Kadar zakat mal (harta) adalah sebesar...', '2%', '2,5%', '3%', '5%', '10%', 'b'],
            ['Nabi yang dikenal sebagai "Khalilullah" (kekasih Allah) adalah...', 'Nabi Adam AS', 'Nabi Nuh AS', 'Nabi Ibrahim AS', 'Nabi Musa AS', 'Nabi Muhammad SAW', 'c'],
            ['Bacaan saat bangun dari ruku\' (i\'tidal) adalah...', 'Subhana Rabbiyal A\'la', 'Subhana Rabbiyal Adzim', 'Sami\'allahu Liman Hamidah Rabbana Lakal Hamd', 'Allahu Akbar', 'Subhanakallahumma', 'c'],
            ['Ilmu yang mempelajari cara membaca Al-Qur\'an dengan benar disebut...', 'Ilmu Fiqih', 'Ilmu Tajwid', 'Ilmu Tafsir', 'Ilmu Hadis', 'Ilmu Nahwu', 'b'],
            ['Larangan Allah yang apabila dilanggar mendapat dosa disebut...', 'Sunnah', 'Mubah', 'Makruh', 'Haram', 'Wajib', 'd'],
            ['Hari kiamat dalam Al-Qur\'an juga disebut sebagai...', 'Yaumul Akhir dan Yaumul Qiyamah', 'Yaumul Hisab dan Yaumul Jaza\'', 'Yaumul Mizan dan Yaumul Hasyr', 'Semua jawaban benar', 'Tidak ada jawaban benar', 'd'],
            ['Shalat fardhu yang jumlah rakaatnya paling banyak adalah shalat...', 'Subuh', 'Dhuhur', 'Ashar', 'Maghrib', 'Isya\'', 'b'],
            ['Dalam Islam, hukum berbohong adalah...', 'Makruh', 'Mubah jika darurat', 'Haram', 'Sunnah ditinggalkan', 'Dibolehkan asal tidak menyakiti', 'c'],
            ['Surah Al-Fatihah terdiri dari berapa ayat?', 'Lima ayat', 'Enam ayat', 'Tujuh ayat', 'Delapan ayat', 'Sembilan ayat', 'c'],
        ];
        foreach ($agama_questions as $q) {
            $bank_agama[] = Question::create([
                'subject' => 'Pendidikan Agama dan Budi Pekerti',
                'question_text' => $q[0], 'option_a' => $q[1], 'option_b' => $q[2],
                'option_c' => $q[3], 'option_d' => $q[4], 'option_e' => $q[5],
                'correct_option' => $q[6],
            ])->id;
        }

        // =====================================================================
        // MAPEL 2: Pendidikan Pancasila (50 Soal Unik)
        // =====================================================================
        $bank_pancasila = [];
        $pancasila_questions = [
            ['Sila pertama Pancasila berbunyi...', 'Persatuan Indonesia', 'Kemanusiaan yang adil dan beradab', 'Ketuhanan Yang Maha Esa', 'Kerakyatan yang dipimpin oleh hikmat kebijaksanaan', 'Keadilan sosial bagi seluruh rakyat', 'c'],
            ['Lambang sila kedua Pancasila adalah...', 'Bintang', 'Rantai', 'Pohon Beringin', 'Kepala Banteng', 'Padi dan Kapas', 'b'],
            ['Pancasila sebagai dasar negara tercantum dalam...', 'Batang tubuh UUD 1945', 'Pembukaan UUD 1945 alinea keempat', 'Penjelasan UUD 1945', 'Tap MPR', 'Undang-Undang No. 1 Tahun 1945', 'b'],
            ['Sidang BPUPKI pertama membahas tentang...', 'Proklamasi kemerdekaan', 'Dasar negara Indonesia', 'Bentuk pemerintahan', 'Wilayah negara Indonesia', 'Undang-Undang Dasar', 'b'],
            ['Fungsi Pancasila sebagai pandangan hidup bangsa berarti...', 'Pancasila adalah peraturan hukum tertinggi', 'Pancasila menjadi pedoman dalam kehidupan sehari-hari', 'Pancasila dibuat oleh pemerintah', 'Pancasila berlaku untuk pejabat negara saja', 'Pancasila adalah semboyan negara', 'b'],
            ['Rumusan Pancasila yang sah dan resmi terdapat dalam...', 'Piagam Jakarta', 'Dekrit Presiden 5 Juli 1959', 'Pembukaan UUD 1945', 'Tap MPRS No. XX/MPRS/1966', 'Naskah Proklamasi', 'c'],
            ['Gotong royong mencerminkan nilai Pancasila terutama sila...', 'Pertama', 'Kedua', 'Ketiga', 'Keempat', 'Kelima', 'c'],
            ['Perilaku yang mencerminkan sila keempat Pancasila adalah...', 'Rajin beribadah', 'Menolong orang yang kesusahan', 'Musyawarah dalam mengambil keputusan', 'Tidak membeda-bedakan teman', 'Menjaga lingkungan hidup', 'c'],
            ['Tokoh yang dijuluki sebagai Bapak Bangsa dan Penyambung Lidah Rakyat adalah...', 'Moh. Hatta', 'Soepomo', 'Moh. Yamin', 'Ir. Soekarno', 'Ki Hajar Dewantara', 'd'],
            ['Nilai yang terkandung dalam sila kelima Pancasila adalah...', 'Keimanan dan ketakwaan', 'Penghargaan terhadap sesama manusia', 'Pemerataan kesejahteraan seluruh rakyat', 'Persatuan dan kesatuan bangsa', 'Demokrasi dan musyawarah', 'c'],
            ['Pancasila sebagai sumber dari segala sumber hukum artinya...', 'Pancasila adalah hukum tertinggi', 'Semua hukum dan peraturan harus bersumber dan tidak boleh bertentangan dengan Pancasila', 'Pancasila dibuat oleh hakim', 'Pancasila mengatur hukum pidana', 'Pancasila berlaku di pengadilan saja', 'b'],
            ['Salah satu contoh penerapan sila persatuan Indonesia di lingkungan sekolah adalah...', 'Berdoa sebelum belajar', 'Menolong teman yang jatuh', 'Berteman tanpa membeda-bedakan suku dan agama', 'Mengerjakan soal ujian sendiri', 'Menghormati guru', 'c'],
            ['BPUPKI singkatan dari...', 'Badan Penyelidik Usaha-usaha Persiapan Kemerdekaan Indonesia', 'Badan Penyusun Undang-undang Persiapan Kemerdekaan Indonesia', 'Badan Pengawas Usaha Persiapan Kemerdekaan Indonesia', 'Badan Perumus Undang-undang Persiapan Kemerdekaan Indonesia', 'Badan Penghimpun Unsur-unsur Persiapan Kemerdekaan Indonesia', 'a'],
            ['Sikap menolak masuknya pengaruh budaya asing yang bertentangan dengan nilai Pancasila disebut...', 'Nasionalisme sempit', 'Filter budaya', 'Chauvinisme', 'Xenofobia', 'Isolasionisme', 'b'],
            ['Hak asasi manusia dalam konteks Pancasila bersifat...', 'Mutlak dan tidak bisa dibatasi', 'Harus selalu diperjuangkan secara individual', 'Seimbang dengan kewajiban dan tidak boleh melanggar hak orang lain', 'Hanya berlaku bagi warga negara Indonesia', 'Tergantung pada keputusan pemerintah', 'c'],
            ['Piagam Jakarta berisi rumusan dasar negara yang diubah pada sila pertama, yaitu menghapus kalimat...', '"dengan berdasar kepada agama"', '"kewajiban menjalankan syariat Islam bagi pemeluk-pemeluknya"', '"berdasarkan ajaran agama"', '"menurut agama masing-masing"', '"dengan dasar agama Islam"', 'b'],
            ['Lembaga negara yang bertugas melaksanakan pemilu adalah...', 'MPR', 'DPR', 'KPU (Komisi Pemilihan Umum)', 'MA (Mahkamah Agung)', 'BPK (Badan Pemeriksa Keuangan)', 'c'],
            ['Contoh perilaku yang mencerminkan nilai kemanusiaan yang adil dan beradab adalah...', 'Memberi sumbangan hanya kepada keluarga', 'Menolong orang yang membutuhkan tanpa memandang latar belakangnya', 'Hanya berteman dengan orang yang seagama', 'Memberi hadiah kepada orang yang kita sukai', 'Mengikuti kegiatan yang menguntungkan saja', 'b'],
            ['Norma yang memiliki sanksi paling tegas dalam kehidupan bermasyarakat adalah...', 'Norma agama', 'Norma kesusilaan', 'Norma kesopanan', 'Norma hukum', 'Norma adat', 'd'],
            ['Kedaulatan rakyat dalam sistem demokrasi Indonesia dilaksanakan melalui...', 'Pengambilan keputusan oleh presiden', 'Pemilihan umum dan lembaga perwakilan', 'Keputusan partai politik', 'Referendum nasional', 'Musyawarah kepala daerah', 'b'],
            ['Sila "Persatuan Indonesia" mengandung makna bahwa...', 'Indonesia adalah negara suku Jawa', 'Bangsa Indonesia harus bersatu meski memiliki keberagaman', 'Indonesia hanya memiliki satu agama', 'Semua warga negara harus berbahasa Jawa', 'Indonesia mengutamakan golongan tertentu', 'b'],
            ['Demokrasi Pancasila mengutamakan pengambilan keputusan melalui...', 'Suara terbanyak (voting) semata', 'Keputusan pemimpin tertinggi', 'Musyawarah untuk mencapai mufakat', 'Keputusan partai yang berkuasa', 'Pendapat pakar atau ahli', 'c'],
            ['Hak warga negara yang paling mendasar yang dijamin dalam UUD 1945 adalah...', 'Hak mendapatkan pekerjaan', 'Hak hidup dan hak atas keamanan', 'Hak memilih dan dipilih', 'Hak mendapatkan pendidikan', 'Hak berusaha dan berniaga', 'b'],
            ['Nilai luhur budaya bangsa Indonesia yang tercermin dalam Pancasila salah satunya adalah...', 'Individualisme', 'Materialisme', 'Gotong royong dan kebersamaan', 'Liberalisme', 'Kapitalisme', 'c'],
            ['Kewajiban warga negara Indonesia dalam bidang pertahanan negara diatur dalam UUD 1945 pasal...', 'Pasal 27 ayat 1', 'Pasal 28', 'Pasal 30', 'Pasal 31', 'Pasal 33', 'c'],
            ['Bhinneka Tunggal Ika berasal dari kitab...', 'Kitab Ramayana', 'Kitab Mahabharata', 'Kitab Sutasoma karangan Mpu Tantular', 'Kitab Negarakertagama', 'Kitab Arjunawiwaha', 'c'],
            ['Pancasila dirumuskan oleh para pendiri bangsa berdasarkan...', 'Ajaran agama Islam semata', 'Nilai-nilai yang berkembang dalam kehidupan masyarakat Indonesia', 'Konstitusi negara asing', 'Keputusan penjajah Belanda', 'Teori-teori filsafat Barat', 'b'],
            ['Pasal 1 ayat 3 UUD 1945 menyatakan bahwa Indonesia adalah negara...', 'Federasi', 'Kesatuan yang berbentuk republik', 'Hukum', 'Demokrasi liberal', 'Monarki konstitusional', 'c'],
            ['Upaya menjaga persatuan dan kesatuan bangsa dapat dilakukan dengan cara...', 'Menonjolkan keunggulan suku sendiri', 'Menghindari pergaulan dengan suku lain', 'Menghargai dan menghormati perbedaan suku, agama, ras, dan antar golongan', 'Hanya menggunakan bahasa daerah sendiri', 'Membatasi pernikahan antar suku', 'c'],
            ['Konstitusi pertama Indonesia (UUD 1945) disahkan pada tanggal...', '17 Agustus 1945', '18 Agustus 1945', '1 Juni 1945', '22 Juni 1945', '29 Mei 1945', 'b'],
            ['Lambang sila ketiga Pancasila adalah...', 'Bintang emas', 'Rantai emas', 'Pohon Beringin', 'Kepala Banteng', 'Padi dan Kapas', 'c'],
            ['Pengertian norma dalam kehidupan bermasyarakat adalah...', 'Aturan yang mengikat dan menjadi pedoman perilaku', 'Kebiasaan yang dilakukan berulang-ulang', 'Peraturan yang dibuat pemerintah', 'Adat yang berlaku secara lokal', 'Hukum yang tertulis dalam undang-undang', 'a'],
            ['Hak asasi yang paling pokok dan tidak dapat dicabut oleh siapa pun adalah...', 'Hak bekerja', 'Hak memilih', 'Hak hidup', 'Hak berkeluarga', 'Hak berpendidikan', 'c'],
            ['Dalam musyawarah, pendapat yang disampaikan harus...', 'Didukung oleh mayoritas peserta', 'Berdasarkan kepentingan golongan', 'Bertanggung jawab dan didasari niat baik untuk kepentingan bersama', 'Ditolak jika berbeda dengan pemimpin', 'Menguntungkan pihak tertentu', 'c'],
            ['Sila keadilan sosial bagi seluruh rakyat Indonesia mengamanatkan agar...', 'Kekayaan dimiliki oleh negara saja', 'Tidak ada perbedaan kelas sosial sama sekali', 'Setiap warga negara mendapat kesempatan yang sama dan perlakuan adil', 'Hanya orang kaya yang boleh berinvestasi', 'Semua gaji harus sama rata', 'c'],
            ['Indonesia menganut sistem pemerintahan...', 'Parlementer', 'Presidensial', 'Monarki', 'Oligarki', 'Teokrasi', 'b'],
            ['Nilai toleransi dalam Pancasila mendorong warga negara untuk...', 'Mengubah keyakinan orang lain', 'Memaksakan pendapat kepada orang lain', 'Menerima dan menghargai perbedaan', 'Menghindari interaksi dengan orang yang berbeda', 'Hanya bergaul dengan kelompok sendiri', 'c'],
            ['Lembaga yang berwenang mengubah dan menetapkan UUD adalah...', 'Presiden', 'DPR', 'DPD', 'MPR', 'MK (Mahkamah Konstitusi)', 'd'],
            ['Pancasila sebagai kepribadian bangsa berarti...', 'Nilai-nilai Pancasila hanya untuk pejabat negara', 'Nilai-nilai Pancasila tercermin dalam karakter dan perilaku bangsa Indonesia', 'Pancasila merupakan identitas partai politik', 'Pancasila hanya berlaku dalam upacara resmi', 'Nilai Pancasila bersifat asing bagi rakyat', 'b'],
            ['Makna proklamasi kemerdekaan bagi bangsa Indonesia adalah...', 'Puncak perjuangan dan lahirnya bangsa Indonesia sebagai negara merdeka dan berdaulat', 'Akhir dari penjajahan Belanda', 'Awal dari penjajahan Jepang', 'Pernyataan perang terhadap sekutu', 'Permohonan bantuan kepada PBB', 'a'],
            ['Pengamalan nilai Pancasila sila pertama dalam kehidupan sehari-hari antara lain...', 'Menghormati hak dan kewajiban orang lain', 'Rajin belajar dan bekerja keras', 'Menjalankan ibadah sesuai agama dan kepercayaan masing-masing', 'Aktif dalam kegiatan gotong royong', 'Mengutamakan kepentingan bersama', 'c'],
            ['Pasal 34 UUD 1945 mengatur tentang...', 'Hak atas pendidikan', 'Fakir miskin dan anak terlantar dipelihara oleh negara', 'Kebebasan berserikat', 'Pemilihan umum', 'Hak atas pekerjaan', 'b'],
            ['Prinsip persamaan hak dalam demokrasi Pancasila berarti...', 'Semua orang mendapat gaji yang sama', 'Setiap warga negara memiliki kedudukan yang sama di hadapan hukum', 'Semua jabatan negara harus diisi bergiliran', 'Kekayaan harus dibagi merata', 'Semua orang boleh menjadi presiden', 'b'],
            ['Salah satu ancaman terhadap persatuan bangsa Indonesia yang datang dari dalam negeri adalah...', 'Agresi militer negara asing', 'Paham separatisme dan radikalisme', 'Embargo ekonomi internasional', 'Persaingan dagang antarnegara', 'Pengaruh kebudayaan asing', 'b'],
            ['Tujuan nasional Indonesia tercantum dalam Pembukaan UUD 1945 alinea...', 'Pertama', 'Kedua', 'Ketiga', 'Keempat', 'Kelima', 'd'],
            ['Semangat dan komitmen para pendiri bangsa dalam merumuskan Pancasila adalah...', 'Mengutamakan kelompok agama tertentu', 'Mempertahankan pengaruh budaya asing', 'Berjuang untuk kepentingan seluruh bangsa tanpa mementingkan kelompok sendiri', 'Mengikuti sistem negara-negara maju', 'Memenangkan kepentingan satu suku atau golongan', 'c'],
            ['Negara kesatuan Republik Indonesia adalah negara yang berdaulat, berarti...', 'Indonesia bebas dari aturan internasional', 'Indonesia memiliki kekuasaan tertinggi dalam mengatur urusan pemerintahannya sendiri', 'Indonesia tidak perlu menjalin hubungan dengan negara lain', 'Presiden memiliki kekuasaan mutlak', 'Rakyat tidak boleh ikut campur dalam pemerintahan', 'b'],
            ['Contoh pelanggaran terhadap nilai Pancasila sila kedua adalah...', 'Tidak mengikuti upacara bendera', 'Melakukan tindakan diskriminasi terhadap kelompok tertentu', 'Tidak membayar pajak', 'Merusak fasilitas umum', 'Tidak mematuhi rambu lalu lintas', 'b'],
            ['Peran pelajar dalam menjaga keutuhan NKRI dapat dilakukan dengan cara...', 'Mengkritik pemerintah di media sosial', 'Berprestasi di bidang akademik dan menjaga kerukunan antarwarga', 'Menghindari pergaulan dengan orang dari daerah lain', 'Menonjolkan budaya daerah sendiri', 'Menolak budaya daerah lain', 'b'],
            ['Pancasila disebut sebagai ideologi terbuka karena...', 'Nilai-nilainya dapat berubah sewaktu-waktu', 'Siapapun boleh mengubah isi Pancasila', 'Nilai-nilainya bersifat dinamis dan dapat menyesuaikan perkembangan zaman tanpa mengubah nilai dasarnya', 'Pancasila terbuka untuk digantikan ideologi lain', 'Pancasila tidak memiliki aturan yang jelas', 'c'],
        ];
        foreach ($pancasila_questions as $q) {
            $bank_pancasila[] = Question::create([
                'subject' => 'Pendidikan Pancasila',
                'question_text' => $q[0], 'option_a' => $q[1], 'option_b' => $q[2],
                'option_c' => $q[3], 'option_d' => $q[4], 'option_e' => $q[5],
                'correct_option' => $q[6],
            ])->id;
        }

        // =====================================================================
        // MAPEL 3: Bahasa Indonesia (50 Soal Unik)
        // =====================================================================
        $bank_bindo = [];
        $bindo_questions = [
            ['Kalimat yang mengandung satu subjek dan satu predikat disebut kalimat...', 'Majemuk setara', 'Majemuk bertingkat', 'Tunggal', 'Pasif', 'Aktif transitif', 'c'],
            ['Teks yang bertujuan memengaruhi pembaca agar mau melakukan sesuatu disebut teks...', 'Deskripsi', 'Narasi', 'Persuasi', 'Eksplanasi', 'Prosedur', 'c'],
            ['Arti kata "ambiguitas" adalah...', 'Ketidakjelasan', 'Kebingungan makna (bermakna ganda)', 'Ketegasan dalam berbicara', 'Keindahan bahasa', 'Kesalahan tata bahasa', 'b'],
            ['Penulisan kata baku yang benar adalah...', 'Nasehat', 'Aktifitas', 'Ijin', 'Nasihat', 'Tehnik', 'd'],
            ['Kalimat "Para siswa-siswa mengerjakan tugas" mengandung kesalahan berupa...', 'Kesalahan ejaan', 'Kata mubazir (pleonasme)', 'Kesalahan tanda baca', 'Kesalahan konjungsi', 'Kalimat pasif yang salah', 'b'],
            ['Gagasan utama dalam sebuah paragraf disebut...', 'Kalimat penjelas', 'Ide pokok / gagasan pokok', 'Kalimat pendukung', 'Alinea', 'Konjungsi', 'b'],
            ['Majas yang membandingkan dua hal menggunakan kata "seperti" atau "bagaikan" adalah majas...', 'Metafora', 'Personifikasi', 'Simile', 'Hiperbola', 'Ironi', 'c'],
            ['Tanda baca yang digunakan untuk mengakhiri kalimat tanya adalah...', 'Tanda titik (.)', 'Tanda seru (!)', 'Tanda tanya (?)', 'Tanda koma (,)', 'Tanda titik dua (:)', 'c'],
            ['Penulisan huruf kapital yang benar terdapat pada kalimat...', 'saya pergi ke jakarta', 'Saya pergi ke jakarta', 'Saya Pergi ke Jakarta', 'saya Pergi ke Jakarta', 'Saya pergi ke Jakarta', 'e'],
            ['Imbuhan me- yang membentuk kata kerja dari kata dasar "tulis" adalah...', 'Menulis', 'Menyulis', 'Mentulis', 'Menulisi', 'Menulisin', 'a'],
            ['Bacaan yang memaparkan suatu kejadian atau peristiwa secara kronologis disebut teks...', 'Deskripsi', 'Narasi', 'Argumentasi', 'Eksposisi', 'Anekdot', 'b'],
            ['Makna kata denotatif adalah...', 'Makna kiasan', 'Makna konotasi', 'Makna sebenarnya atau makna kamus', 'Makna tambahan berdasarkan perasaan', 'Makna yang tersirat', 'c'],
            ['Sinonim dari kata "bijaksana" adalah...', 'Bodoh', 'Ceroboh', 'Arif', 'Egois', 'Angkuh', 'c'],
            ['Kata penghubung (konjungsi) yang menyatakan pertentangan adalah...', 'dan', 'serta', 'atau', 'tetapi', 'karena', 'd'],
            ['Paragraph yang kalimat utamanya terletak di akhir paragraf disebut paragraf...', 'Deduktif', 'Induktif', 'Campuran', 'Naratif', 'Deskriptif', 'b'],
            ['Penulisan singkatan yang benar menurut EYD adalah...', 'dll.', 'Dll', 'D.L.L', 'dll', 'DLL.', 'a'],
            ['Jenis kata "dengan cepat" dalam kalimat termasuk...', 'Kata benda', 'Kata sifat', 'Kata kerja', 'Kata keterangan', 'Kata sambung', 'd'],
            ['Antonym dari kata "efisien" adalah...', 'Hemat', 'Cermat', 'Boros dan tidak efektif', 'Rajin', 'Produktif', 'c'],
            ['Dalam penulisan surat resmi, bagian yang berisi tujuan surat disebut...', 'Kepala surat', 'Salam pembuka', 'Isi / perihal surat', 'Penutup surat', 'Lampiran', 'c'],
            ['Majas yang memberikan sifat-sifat manusia kepada benda mati disebut...', 'Simile', 'Metafora', 'Personifikasi', 'Hiperbola', 'Litotes', 'c'],
            ['Kalimat efektif memiliki ciri-ciri...', 'Panjang dan terperinci', 'Boros kata dan berulang', 'Jelas, hemat kata, dan tidak ambigu', 'Menggunakan kata-kata asing', 'Berisi banyak kata kiasan', 'c'],
            ['Lafal yang tepat pada pembacaan puisi memperhatikan...', 'Panjang pendek tulisan', 'Jenis kertas yang digunakan', 'Kejelasan pengucapan setiap kata', 'Warna tinta pada tulisan', 'Ukuran huruf', 'c'],
            ['Struktur teks eksposisi yang benar adalah...', 'Orientasi – Komplikasi – Resolusi', 'Tesis – Argumen – Penegasan ulang', 'Pernyataan umum – Deretan penjelas – Interpretasi', 'Abstrak – Orientasi – Krisis – Reaksi – Koda', 'Pembukaan – Isi – Penutup', 'b'],
            ['Kata "di" pada kalimat "Buku itu ada di meja" ditulis...', 'Dipisah, karena merupakan kata depan (preposisi)', 'Digabung, karena merupakan prefiks', 'Digabung, karena merupakan kata keterangan', 'Dipisah, karena merupakan kata ganti', 'Bebas, boleh digabung atau dipisah', 'a'],
            ['Cerpen merupakan singkatan dari...', 'Cerita Penting', 'Cerita Pendek', 'Cerita Penuh Makna', 'Cerita Pengantar', 'Cerita Pelajaran', 'b'],
            ['Penulisan bilangan yang benar dalam kalimat resmi adalah...', '1000 orang menghadiri acara itu', 'Seribu orang menghadiri acara itu', '1.000 orang menghadiri acara itu', 'Se-ribu orang menghadiri acara itu', '(1000) orang menghadiri acara itu', 'b'],
            ['Ciri-ciri teks berita yang baik adalah...', 'Mengandung opini penulis secara berlebihan', 'Memuat fakta, aktual, menarik, dan sesuai kepentingan publik', 'Hanya menyajikan peristiwa yang menyedihkan', 'Menggunakan kata-kata yang sulit dipahami', 'Tidak menyertakan narasumber', 'b'],
            ['Kalimat pasif dapat diubah dari kalimat aktif dengan cara...', 'Menambah imbuhan me- pada kata kerja', 'Memindahkan objek menjadi subjek dan memberi imbuhan di- atau ter-', 'Menghilangkan predikat kalimat', 'Mengubah kata benda menjadi kata kerja', 'Menambahkan kata "oleh" di awal kalimat', 'b'],
            ['Teks prosedur biasanya menggunakan kalimat...', 'Kalimat majemuk bertingkat', 'Kalimat imperatif (perintah)', 'Kalimat pasif', 'Kalimat tanya retoris', 'Kalimat berita', 'b'],
            ['Arti dari imbuhan -an pada kata "minuman" adalah...', 'Proses melakukan', 'Alat atau benda yang dikenai pekerjaan', 'Sesuatu yang diminum / hasil meminum', 'Orang yang minum', 'Cara minum', 'c'],
            ['Dalam karya sastra, sudut pandang "orang pertama" ditandai dengan penggunaan kata...', 'Dia, beliau, ia', 'Mereka, kalian', 'Anda, kamu', 'Saya, aku', 'Kita, kami', 'd'],
            ['Kalimat "Hatinya hancur berkeping-keping mendengar kabar itu" menggunakan majas...', 'Simile', 'Metafora', 'Personifikasi', 'Hiperbola', 'Ironi', 'd'],
            ['Pernyataan yang sesuai dengan isi teks disebut...', 'Inferensi', 'Simpulan', 'Fakta tekstual / parafrase', 'Opini', 'Asumsi', 'c'],
            ['Perbedaan antara cerpen dan novel terletak pada...', 'Tema yang diangkat', 'Jumlah halaman dan kompleksitas alur serta tokoh', 'Gaya bahasa yang digunakan', 'Latar tempat cerita', 'Jenis konflik dalam cerita', 'b'],
            ['Kata "bertanggung jawab" merupakan contoh kata...', 'Kata dasar', 'Kata ulang', 'Kata majemuk', 'Kata berimbuhan', 'Kata serapan', 'c'],
            ['Tanda koma (,) digunakan dalam kalimat...', '"Ia belajar keras namun gagal ujian"', '"Mereka pergi ke pasar, ke toko buku, dan ke restoran"', '"Dia berlari cepat sekali"', '"Buku itu sangat tebal"', '"Siswa berprestasi mendapat beasiswa"', 'b'],
            ['Istilah "biografi" berarti...', 'Cerita fiksi tentang kehidupan seseorang', 'Riwayat hidup seseorang yang ditulis oleh orang lain', 'Catatan harian pribadi seseorang', 'Laporan kegiatan seseorang', 'Kumpulan puisi seseorang', 'b'],
            ['Kalimat yang berisi simpulan atau kesimpulan sebuah argumen disebut...', 'Premis mayor', 'Premis minor', 'Deduksi', 'Konklusi / simpulan', 'Asumsi', 'd'],
            ['Penulisan ejaan yang benar adalah...', '"Senin" untuk nama hari', '"senin" untuk nama hari', '"SENIN" untuk nama hari dalam kalimat biasa', '"s.enin" untuk nama hari', '"hari Senin" boleh ditulis "hari senin"', 'a'],
            ['Apa yang dimaksud dengan diksi dalam penulisan?', 'Pilihan kata yang tepat sesuai makna dan konteks', 'Panjang kalimat dalam paragraf', 'Penggunaan tanda baca yang benar', 'Struktur paragraf yang baik', 'Gaya penulisan secara umum', 'a'],
            ['Puisi yang tidak terikat rima dan jumlah baris disebut puisi...', 'Lama', 'Klasik', 'Bebas', 'Pantun', 'Syair', 'c'],
            ['Kata "sinkron" berarti...', 'Tidak bersamaan', 'Terjadi pada waktu yang bersamaan', 'Berbeda pendapat', 'Saling bertentangan', 'Tidak relevan', 'b'],
            ['Fungsi tanda titik dua (:) salah satunya adalah...', 'Menghubungkan dua kalimat yang berlawanan', 'Mengapit kata yang dikutip', 'Memperkenalkan daftar, penjelasan, atau kutipan', 'Menandai akhir kalimat tanya', 'Memisahkan kata dalam perincian', 'c'],
            ['Kata "seyogyanya" dalam bahasa Indonesia berarti...', 'Seharusnya / sebaiknya', 'Sesungguhnya', 'Setidaknya', 'Sebaliknya', 'Semestinya juga', 'a'],
            ['Dalam debat, pernyataan yang bertujuan mematahkan argumen lawan disebut...', 'Argumen pendukung', 'Sanggahan / bantahan', 'Klaim', 'Premis', 'Kesimpulan', 'b'],
            ['Paragraf campuran (deduktif-induktif) memiliki kalimat utama...', 'Hanya di awal paragraf', 'Hanya di akhir paragraf', 'Di awal dan diulang/dipertegas di akhir paragraf', 'Di tengah paragraf', 'Tidak memiliki kalimat utama', 'c'],
            ['Makna konotasi dari kata "bunga" dalam puisi biasanya berarti...', 'Tanaman berbunga', 'Bagian tumbuhan', 'Keindahan, keharuman, atau gadis yang cantik', 'Biaya tambahan dalam pinjaman', 'Ornamen dekorasi', 'c'],
            ['Penulisan kata depan "ke" yang benar adalah...', '"Kemarketpagi ini" disambung', '"Ke pasar pagi ini" dipisah karena menunjukkan tempat tujuan', '"kepasar" disambung karena sudah lazim', '"Ke-pasar" dengan tanda hubung', '"k\'pasar" disingkat', 'b'],
            ['Jenis karangan yang berdasarkan data dan fakta serta bertujuan menambah pengetahuan pembaca disebut...', 'Narasi', 'Deskripsi', 'Argumentasi', 'Eksposisi', 'Persuasi', 'd'],
            ['Kata baku untuk "merubah" adalah...', 'merubah', 'merobah', 'mengubah', 'meubah', 'memubah', 'c'],
        ];
        foreach ($bindo_questions as $q) {
            $bank_bindo[] = Question::create([
                'subject' => 'Bahasa Indonesia',
                'question_text' => $q[0], 'option_a' => $q[1], 'option_b' => $q[2],
                'option_c' => $q[3], 'option_d' => $q[4], 'option_e' => $q[5],
                'correct_option' => $q[6],
            ])->id;
        }

        // =====================================================================
        // MAPEL 4: Bahasa Inggris (50 Soal Unik)
        // =====================================================================
        $bank_inggris = [];
        $inggris_questions = [
            ['What is the correct past tense of the verb "go"?', 'Goes', 'Goed', 'Went', 'Gone', 'Going', 'c'],
            ['Choose the correct sentence using "Present Perfect Tense".', 'She goes to school yesterday.', 'She has gone to school.', 'She go to school.', 'She is going to school.', 'She will go to school.', 'b'],
            ['The synonym of "enormous" is...', 'tiny', 'huge', 'narrow', 'weak', 'quiet', 'b'],
            ['What does the word "beneficial" mean?', 'Harmful', 'Expensive', 'Advantageous / useful', 'Beautiful', 'Difficult', 'c'],
            ['Choose the correct question tag for: "She is a doctor, ..."', 'is she?', 'does she?', 'isn\'t she?', 'doesn\'t she?', 'was she?', 'c'],
            ['"I have been waiting for two hours." This sentence uses...', 'Simple Past Tense', 'Present Continuous Tense', 'Past Perfect Tense', 'Present Perfect Continuous Tense', 'Future Tense', 'd'],
            ['The antonym of "generous" is...', 'kind', 'friendly', 'stingy', 'honest', 'brave', 'c'],
            ['Which sentence uses the passive voice correctly?', 'The cake eaten by her.', 'The cake was eaten by her.', 'Her eaten the cake.', 'The cake is eat by her.', 'She eaten the cake.', 'b'],
            ['What is the meaning of "to burn the midnight oil"?', 'To set fire to oil', 'To study or work very late at night', 'To waste electricity', 'To cook at midnight', 'To sleep very late', 'b'],
            ['Choose the correct use of "if clause" (conditional type 2): "If I _____ rich, I _____ travel the world."', 'am / will', 'was / would', 'were / would', 'am / would', 'will be / would', 'c'],
            ['The word "although" is used to express...', 'Cause and effect', 'Contrast / concession', 'Addition', 'Sequence', 'Purpose', 'b'],
            ['What part of speech is the word "quickly" in: "She runs quickly"?', 'Noun', 'Verb', 'Adjective', 'Adverb', 'Preposition', 'd'],
            ['Choose the correct comparative form: "This book is ___ than that one." (expensive)', 'most expensive', 'more expensive', 'expensiver', 'the most expensive', 'very expensive', 'b'],
            ['What is the correct form of reported speech for: He said, "I am tired"?', 'He said that he is tired.', 'He said that he was tired.', 'He said that I am tired.', 'He said that I was tired.', 'He said that he tired.', 'b'],
            ['In a narrative text, the part that tells the problem or conflict is called...', 'Orientation', 'Resolution', 'Complication', 'Coda', 'Reorientation', 'c'],
            ['The sentence "Water boils at 100 degrees Celsius" expresses...', 'Habit', 'A scheduled future event', 'A general truth / scientific fact', 'A past event', 'A continuous action', 'c'],
            ['Choose the correct article: "___ Eiffel Tower is in Paris."', 'A', 'An', 'The', 'No article needed', 'Some', 'c'],
            ['What is the main purpose of a procedure text?', 'To entertain the reader', 'To describe a specific object', 'To tell how something is done step by step', 'To explain a natural phenomenon', 'To persuade the reader', 'c'],
            ['The word "procrastinate" means...', 'To work very hard', 'To delay or postpone something', 'To finish a task quickly', 'To plan carefully', 'To refuse a task', 'b'],
            ['Choose the correct preposition: "She has been working ___ this company ___ 2010."', 'at / for', 'in / for', 'at / since', 'in / since', 'for / since', 'd'],
            ['What is the plural of "crisis"?', 'Crisises', 'Crises', 'Crisisses', 'Crisiss', 'Crisin', 'b'],
            ['Which of the following is an example of a gerund?', 'To swim is my hobby.', 'Swimming is my hobby.', 'I swim every day.', 'She is swimming now.', 'They will swim tomorrow.', 'b'],
            ['"Neither the students nor the teacher ___ ready yet." Fill in the blank with the correct verb.', 'are', 'were', 'is', 'have been', 'being', 'c'],
            ['What literary device is used in "The stars danced in the night sky"?', 'Simile', 'Metaphor', 'Personification', 'Hyperbole', 'Alliteration', 'c'],
            ['The word "ambivalent" means...', 'Very confident', 'Having mixed feelings or contradictory ideas about something', 'Extremely happy', 'Deeply sad', 'Completely indifferent', 'b'],
            ['What tense is used in: "By the time she arrived, we had already eaten dinner"?', 'Past Continuous', 'Past Perfect', 'Present Perfect', 'Past Simple', 'Future Perfect', 'b'],
            ['Choose the word that best completes the sentence: "The new policy will ___ unemployment significantly."', 'raise', 'rise', 'reduce', 'increasing', 'grown', 'c'],
            ['An expository text aims to...', 'Tell an entertaining story', 'Provide a clear explanation or information about a topic', 'Persuade the reader to take action', 'Describe a beautiful place', 'Tell about the writer\'s experience', 'b'],
            ['Which sentence contains a modal verb expressing obligation?', 'She can speak French.', 'He might come later.', 'You must wear a seatbelt.', 'They may leave now.', 'I will help you.', 'c'],
            ['The phrase "a blessing in disguise" means...', 'A hidden curse', 'Something that seems bad at first but turns out to be good', 'A religious ritual', 'A beautiful appearance', 'An unexpected gift', 'b'],
            ['What is the function of a conjunction in a sentence?', 'To name a person, place, or thing', 'To describe a noun', 'To connect words, phrases, or clauses', 'To show action', 'To modify a verb', 'c'],
            ['Choose the correct form: "The manager, along with his staff, ___ attending the meeting."', 'are', 'were', 'is', 'have', 'be', 'c'],
            ['In an analytical exposition text, a "Reiteration" refers to...', 'The introduction of the topic', 'The supporting arguments', 'The restatement of the main thesis at the end', 'A counterargument', 'A summary of events', 'c'],
            ['What is the correct sentence?', 'I didn\'t said anything.', 'I didn\'t say anything.', 'I not say anything.', 'I didn\'t says anything.', 'I not said anything.', 'b'],
            ['The word "eloquent" means...', 'Difficult to understand', 'Able to speak or write clearly and persuasively', 'Very quiet and shy', 'Extremely rude', 'Visually impressive', 'b'],
            ['Choose the correct use of "wish": "I wish I ___ fly like a bird."', 'can', 'could', 'will', 'am able to', 'would', 'b'],
            ['What is a "topic sentence" in a paragraph?', 'The last sentence that concludes the paragraph', 'The sentence that introduces the main idea of the paragraph', 'A sentence that provides supporting details', 'The sentence with the most interesting fact', 'A question that opens the paragraph', 'b'],
            ['The word "tenacious" most closely means...', 'Gentle and kind', 'Holding firmly to a goal; persistent and determined', 'Quick and agile', 'Bright and intelligent', 'Calm and composed', 'b'],
            ['"She suggested that he ___ the doctor immediately." Which form is correct?', 'to see', 'seeing', 'see', 'saw', 'seen', 'c'],
            ['What is the genre of a text that aims to retell past events for entertainment?', 'Procedure', 'Report', 'Recount', 'Explanation', 'Discussion', 'c'],
            ['The prefix "un-" in "unhappy" indicates...', 'Again', 'Before', 'Not / opposite of', 'Too much', 'Half', 'c'],
            ['"Would you mind ___ the window?" Choose the correct form.', 'close', 'to close', 'closing', 'closed', 'closes', 'c'],
            ['Which sentence uses "despite" correctly?', 'Despite of the rain, we went out.', 'Despite the rain, we went out.', 'Despite it rained, we went out.', 'Despite that it rained, we went out.', 'Despite raining, we went out for.', 'b'],
            ['The word "meticulous" means...', 'Careless and sloppy', 'Very careful and precise about details', 'Bold and daring', 'Lazy and unmotivated', 'Talkative and outgoing', 'b'],
            ['In a hortatory exposition text, the writer\'s goal is to...', 'Tell an interesting story', 'Describe a natural phenomenon scientifically', 'Persuade the reader to do or not to do something', 'Explain the steps of a process', 'Report factual information', 'c'],
            ['Choose the correct sentence with "used to": "He ___ smoke, but he quit last year."', 'is used to', 'uses to', 'used to', 'was used to', 'use to', 'c'],
            ['What does the expression "hit the nail on the head" mean?', 'To physically hit something', 'To be exactly right about something', 'To fail completely', 'To lose one\'s temper', 'To start a project', 'b'],
            ['Which option contains a correctly written complex sentence?', 'Because he was tired he slept early.', 'Because he was tired, he slept early.', 'He slept early, because was tired.', 'Although but he was tired, slept early.', 'He was tired he slept early because.', 'b'],
            ['The suffix "-tion" as in "education" changes a verb into a...', 'Adjective', 'Adverb', 'Noun', 'Verb with a different meaning', 'Preposition', 'c'],
            ['What is meant by "infer" when reading a text?', 'To copy the main idea', 'To summarize the entire text', 'To draw a conclusion based on evidence not explicitly stated', 'To translate the text into another language', 'To find the title of the text', 'c'],
        ];
        foreach ($inggris_questions as $q) {
            $bank_inggris[] = Question::create([
                'subject' => 'Bahasa Inggris',
                'question_text' => $q[0], 'option_a' => $q[1], 'option_b' => $q[2],
                'option_c' => $q[3], 'option_d' => $q[4], 'option_e' => $q[5],
                'correct_option' => $q[6],
            ])->id;
        }

        // =====================================================================
        // MAPEL 5: Matematika Umum (50 Soal Unik)
        // =====================================================================
        $bank_mtk = [];
        $mtk_questions = [
            ['Hasil dari 2³ + 4² − √25 adalah...', '25', '18', '15', '20', '23', 'b'],
            ['Jika f(x) = 3x² − 2x + 1, maka f(2) = ...', '5', '7', '9', '11', '13', 'c'],
            ['Akar-akar persamaan kuadrat x² − 5x + 6 = 0 adalah...', 'x = 2 dan x = 3', 'x = 1 dan x = 6', 'x = −2 dan x = −3', 'x = 2 dan x = −3', 'x = −1 dan x = −6', 'a'],
            ['Nilai sin 30° + cos 60° = ...', '0', '0,5', '1', '1,5', '√3/2', 'c'],
            ['Determinan matriks [[3,1],[2,4]] adalah...', '10', '14', '8', '12', '16', 'a'],
            ['Lim (x→2) (x²−4)/(x−2) = ...', '0', '2', '4', '8', 'Tidak ada', 'c'],
            ['Turunan pertama dari f(x) = 5x³ − 2x + 7 adalah...', '15x² − 2', '5x² − 2', '15x³ − 2', '10x² − 2', '15x² + 7', 'a'],
            ['Integral dari ∫ (2x + 3) dx = ...', 'x² + 3x + C', 'x² + 3 + C', '2x² + 3x + C', 'x + 3x + C', '2 + C', 'a'],
            ['Diketahui barisan aritmatika: 3, 7, 11, 15, ... Suku ke-10 adalah...', '39', '41', '43', '45', '37', 'c'],
            ['Jumlah 5 suku pertama barisan geometri 2, 6, 18, ... adalah...', '162', '242', '244', '234', '246', 'b'],
            ['Persamaan garis yang melalui titik (2, 3) dan sejajar garis y = 2x + 1 adalah...', 'y = 2x − 1', 'y = 2x + 3', 'y = −2x + 1', 'y = x + 1', 'y = 2x − 4', 'a'],
            ['Nilai dari log₂ 32 = ...', '4', '5', '6', '8', '16', 'b'],
            ['Jika vektor a = (3, 4), maka |a| = ...', '3', '4', '5', '7', '12', 'c'],
            ['Suku banyak f(x) = x³ − 4x² + x + 6 dibagi (x − 2), sisa pembagiannya adalah...', '0', '4', '−4', '6', '2', 'a'],
            ['Nilai dari ⁵C₂ (kombinasi) adalah...', '5', '10', '15', '20', '25', 'b'],
            ['Permutasi ⁵P₃ sama dengan...', '10', '20', '30', '40', '60', 'd'],
            ['Panjang sisi miring segitiga siku-siku dengan kaki 6 cm dan 8 cm adalah...', '10 cm', '12 cm', '14 cm', '11 cm', '9 cm', 'a'],
            ['Volume kerucut dengan jari-jari 7 cm dan tinggi 12 cm (π ≈ 22/7) adalah...', '616 cm³', '632 cm³', '528 cm³', '572 cm³', '506 cm³', 'a'],
            ['Luas lingkaran dengan diameter 14 cm (π ≈ 22/7) adalah...', '154 cm²', '176 cm²', '196 cm²', '143 cm²', '132 cm²', 'a'],
            ['Nilai median dari data: 4, 7, 2, 9, 5, 8, 3 adalah...', '5', '7', '4', '6', '3', 'a'],
            ['Nilai modus dari data: 3, 5, 7, 5, 2, 5, 3, 7, 5, adalah...', '3', '5', '7', '2', '4', 'b'],
            ['Nilai rata-rata dari 8, 10, 7, 12, 13 adalah...', '9', '10', '11', '12', '8', 'b'],
            ['Sistem persamaan 2x + y = 10 dan x − y = 2 memiliki penyelesaian...', 'x = 4, y = 2', 'x = 3, y = 4', 'x = 5, y = 0', 'x = 2, y = 6', 'x = 6, y = −2', 'a'],
            ['Grafik fungsi y = x² − 4 memotong sumbu x di titik...', '(2, 0) dan (−2, 0)', '(4, 0) dan (−4, 0)', '(0, 4) dan (0, −4)', '(2, 0) saja', '(0, 0) dan (0, 4)', 'a'],
            ['Nilai tan 45° = ...', '0', '1/2', '√2/2', '1', '√3', 'd'],
            ['Jika P(A) = 0,4 dan P(B) = 0,3, serta A dan B saling bebas, maka P(A ∩ B) = ...', '0,7', '0,1', '0,12', '0,4', '0,3', 'c'],
            ['Diketahui f(x) = 2x + 1 dan g(x) = x², maka (f∘g)(3) = ...', '19', '17', '25', '10', '14', 'a'],
            ['Persamaan lingkaran berpusat di (0,0) berjari-jari 5 adalah...', 'x² + y² = 10', 'x + y = 5', 'x² + y² = 25', 'x² − y² = 25', '(x+5)² + (y+5)² = 25', 'c'],
            ['Nilai dari ²⁷⁄₃ + ³⁄₉ dalam bentuk paling sederhana adalah...', '10', '9 1/3', '9,33', '9 + 1/3', '28/3', 'e'],
            ['Panjang sisi segitiga sama sisi yang luasnya 36√3 cm² adalah...', '8 cm', '10 cm', '12 cm', '6 cm', '9 cm', 'c'],
            ['Jika ABCD adalah jajar genjang dengan AB = 8 cm, BC = 6 cm, dan sudut B = 30°, luas ABCD adalah...', '24 cm²', '48 cm²', '36 cm²', '16 cm²', '32 cm²', 'a'],
            ['Akar-akar persamaan 2x² + 3x − 2 = 0 adalah...', 'x = 1/2 dan x = −2', 'x = 2 dan x = −1/2', 'x = −1 dan x = 2', 'x = 1 dan x = −2', 'x = 1/2 dan x = 2', 'a'],
            ['Nilai dari 8^(2/3) adalah...', '2', '4', '8', '16', '6', 'b'],
            ['Fungsi f(x) = log(x−1) terdefinisi untuk...', 'x > 0', 'x > 1', 'x ≥ 1', 'x < 1', 'Semua x', 'b'],
            ['Diketahui cos A = 3/5, maka sin A = ...', '4/5', '5/3', '3/4', '5/4', '2/5', 'a'],
            ['Jumlah deret geometri tak hingga: 4 + 2 + 1 + 1/2 + ... adalah...', '6', '7', '8', '9', '10', 'c'],
            ['Nilai minimum fungsi f(x) = x² − 6x + 10 adalah...', '1', '2', '3', '4', '5', 'a'],
            ['Bayangan titik A(3, −2) jika direfleksikan terhadap sumbu y adalah...', '(3, 2)', '(−3, −2)', '(−3, 2)', '(2, −3)', '(−2, 3)', 'b'],
            ['Dilatasi titik P(2, 4) terhadap pusat O(0,0) dengan faktor skala 3 menghasilkan titik...', '(6, 12)', '(5, 7)', '(2, 12)', '(6, 4)', '(3, 6)', 'a'],
            ['Nilai dari ₁∫³ (2x) dx adalah...', '6', '8', '9', '4', '5', 'b'],
            ['Jika matriks A = [[1,2],[3,4]], maka invers A adalah...', '[[−2,1],[3/2,−1/2]]', '[[4,−2],[−3,1]]', '[[2,−1],[3/2,−1/2]]', '[[−4,2],[3,−1]]', 'Tidak ada invers', 'b'],
            ['Sebuah tabung memiliki jari-jari 7 cm dan tinggi 10 cm. Volumenya (π ≈ 22/7) adalah...', '1540 cm³', '1760 cm³', '2000 cm³', '1420 cm³', '1320 cm³', 'a'],
            ['Hasil operasi matriks [[2,1],[0,3]] + [[1,−1],[2,0]] adalah...', '[[3,0],[2,3]]', '[[3,2],[2,3]]', '[[3,0],[0,3]]', '[[2,0],[2,3]]', '[[1,2],[2,3]]', 'a'],
            ['Pada segitiga ABC, a = 8, b = 6, dan C = 90°. Panjang sisi c adalah...', '7', '8', '10', '9', '11', 'c'],
            ['Nilai x yang memenuhi |2x − 3| = 7 adalah...', 'x = 5 atau x = −2', 'x = 7 atau x = −3', 'x = 5 atau x = 2', 'x = 10 atau x = −4', 'x = 4 atau x = −2', 'a'],
            ['Simpangan baku dari data 2, 4, 4, 4, 5, 5, 7, 9 adalah...', '1', '2', '3', '4', '5', 'b'],
            ['Nilai dari ₀∫^(π/2) sin(x) dx adalah...', '0', '1', '−1', 'π/2', '2', 'b'],
            ['Suatu persamaan lingkaran (x−2)² + (y+3)² = 16. Pusatnya berada di...', '(2, 3)', '(−2, 3)', '(2, −3)', '(−2, −3)', '(4, −3)', 'c'],
            ['Diketahui P(A) = 0.6, P(B|A) = 0.5. Nilai P(A ∩ B) adalah...', '0.1', '0.3', '1.1', '0.8', '0.25', 'b'],
            ['Hasil kali akar-akar dari 3x² − 5x + 2 = 0 berdasarkan rumus Vieta adalah...', '5/3', '−5/3', '2/3', '−2/3', '3/2', 'c'],
        ];
        foreach ($mtk_questions as $q) {
            $bank_mtk[] = Question::create([
                'subject' => 'Matematika Umum',
                'question_text' => $q[0], 'option_a' => $q[1], 'option_b' => $q[2],
                'option_c' => $q[3], 'option_d' => $q[4], 'option_e' => $q[5],
                'correct_option' => $q[6],
            ])->id;
        }

        // =====================================================================
        // MAPEL 6: Sejarah (50 Soal Unik)
        // =====================================================================
        $bank_sejarah = [];
        $sejarah_questions = [
            ['Proklamasi kemerdekaan Indonesia dibacakan pada tanggal...', '15 Agustus 1945', '16 Agustus 1945', '17 Agustus 1945', '18 Agustus 1945', '20 Agustus 1945', 'c'],
            ['Peristiwa Rengasdengklok terjadi pada tanggal...', '14 Agustus 1945', '15 Agustus 1945', '16 Agustus 1945', '17 Agustus 1945', '18 Agustus 1945', 'c'],
            ['Tokoh yang menculik Soekarno dan Hatta ke Rengasdengklok adalah...', 'Tan Malaka dan Sutan Sjahrir', 'Chairul Saleh dan Wikana', 'Sukarni dan Wikana', 'Adam Malik dan Sukarni', 'B.M. Diah dan Yusuf Kunto', 'c'],
            ['Kongres Pemuda II yang menghasilkan Sumpah Pemuda berlangsung pada...', '28 Oktober 1926', '28 Oktober 1927', '28 Oktober 1928', '28 Oktober 1929', '28 Oktober 1930', 'c'],
            ['Isi Sumpah Pemuda yang pertama adalah...', 'Kami putra dan putri Indonesia mengaku berbangsa yang satu, bangsa Indonesia', 'Kami putra dan putri Indonesia mengaku bertanah air yang satu, tanah air Indonesia', 'Kami putra dan putri Indonesia menjunjung bahasa persatuan, bahasa Indonesia', 'Kami putra dan putri Indonesia bersatu padu dalam perjuangan', 'Kami putra dan putri Indonesia berjanji setia kepada negara', 'b'],
            ['Kerajaan Majapahit mencapai puncak kejayaannya pada masa pemerintahan...', 'Raden Wijaya', 'Hayam Wuruk', 'Tribhuwana Tunggadewi', 'Jayanagara', 'Wikramawardhana', 'b'],
            ['Patih Kerajaan Majapahit yang terkenal dengan Sumpah Palapa adalah...', 'Arya Damar', 'Lembu Sora', 'Gajah Mada', 'Adityawarman', 'Mpu Tantular', 'c'],
            ['Kerajaan Islam pertama di Indonesia adalah...', 'Kerajaan Demak', 'Kerajaan Mataram Islam', 'Kerajaan Samudra Pasai', 'Kerajaan Aceh', 'Kerajaan Ternate', 'c'],
            ['VOC (Vereenigde Oost-Indische Compagnie) didirikan pada tahun...', '1596', '1602', '1619', '1641', '1650', 'b'],
            ['Cultuurstelsel (Tanam Paksa) diterapkan di Indonesia oleh...', 'Herman Willem Daendels', 'Thomas Stamford Raffles', 'Johannes van den Bosch', 'Jan Pieterszoon Coen', 'Pieter Both', 'c'],
            ['Perlawanan rakyat Maluku terhadap VOC dipimpin oleh...', 'Sultan Baab Ullah', 'Kapitan Pattimura', 'Sultan Ageng Tirtayasa', 'Pangeran Diponegoro', 'Imam Bonjol', 'b'],
            ['Perang Diponegoro berlangsung pada tahun...', '1815−1820', '1820−1825', '1825−1830', '1830−1835', '1835−1840', 'c'],
            ['Organisasi pergerakan nasional pertama di Indonesia adalah...', 'Sarekat Islam', 'Budi Utomo', 'Indische Partij', 'Muhammadiyah', 'Partai Nasional Indonesia', 'b'],
            ['Budi Utomo didirikan pada tanggal...', '20 Mei 1908', '28 Oktober 1908', '17 Agustus 1908', '1 Juni 1908', '20 Mei 1910', 'a'],
            ['Peristiwa yang menandai akhir Perang Dunia II di Pasifik adalah...', 'Jatuhnya Berlin kepada Sekutu', 'Serangan Sekutu di Normandia', 'Pengeboman atom Hiroshima dan Nagasaki', 'Menyerahnya Italia', 'Serangan ke Pearl Harbor', 'c'],
            ['Pemberontakan PKI Madiun terjadi pada tahun...', '1945', '1946', '1947', '1948', '1949', 'd'],
            ['Konferensi Meja Bundar (KMB) yang mengakui kedaulatan Indonesia berlangsung di...', 'London, Inggris', 'Paris, Perancis', 'Den Haag (The Hague), Belanda', 'New York, AS', 'Jenewa, Swiss', 'c'],
            ['Agresi Militer Belanda I terjadi pada tahun...', '1945', '1946', '1947', '1948', '1949', 'c'],
            ['G30S/PKI terjadi pada tanggal...', '30 September 1965', '1 Oktober 1965', '30 September 1966', '1 Oktober 1966', '30 Oktober 1965', 'a'],
            ['Orde Baru di Indonesia berlangsung dari tahun...', '1945−1966', '1966−1998', '1950−1965', '1998−2004', '1959−1966', 'b'],
            ['Reformasi 1998 ditandai dengan mundurnya Presiden...', 'B.J. Habibie', 'Abdurrahman Wahid', 'Megawati Soekarnoputri', 'Soeharto', 'Susilo Bambang Yudhoyono', 'd'],
            ['Kerajaan Hindu tertua di Indonesia adalah...', 'Kerajaan Sriwijaya', 'Kerajaan Kutai', 'Kerajaan Tarumanegara', 'Kerajaan Mataram Kuno', 'Kerajaan Singhasari', 'b'],
            ['Yupa yang merupakan prasasti kerajaan Kutai menggunakan huruf...', 'Jawa Kuno', 'Arab', 'Pallawa', 'Sanksekerta Melayu', 'Kanji', 'c'],
            ['Sistem ekonomi yang diterapkan pemerintah Jepang dalam penguasaan Indonesia antara lain...', 'Tanam paksa', 'Kerja rodi (romusha)', 'Politik etis', 'Ekonomi liberal', 'Sistem sewa tanah', 'b'],
            ['Perang Padri di Sumatra Barat dipimpin oleh...', 'Tuanku Imam Bonjol', 'Pangeran Diponegoro', 'Sultan Hasanuddin', 'Kapitan Pattimura', 'Cut Nyak Dhien', 'a'],
            ['Sriwijaya adalah kerajaan yang berpusat di wilayah yang kini dikenal sebagai...', 'Jawa Barat', 'Kalimantan', 'Sumatra Selatan', 'Sulawesi', 'Bali', 'c'],
            ['Perjanjian Linggarjati antara Indonesia dan Belanda ditandatangani pada...', '15 November 1946', '17 Januari 1948', '27 Desember 1949', '19 Desember 1948', '12 November 1946', 'a'],
            ['Dekrit Presiden 5 Juli 1959 menyatakan...', 'Pemberlakuan UUDS 1950', 'Pembubaran DPRS', 'Kembali ke UUD 1945 dan pembubaran Konstituante', 'Pembentukan MPRS', 'Pergantian Presiden', 'c'],
            ['Peristiwa Tritura (Tri Tuntutan Rakyat) pada tahun 1966 menuntut...', 'Pembentukan kabinet baru, turunnya harga, dan pembebasan tahanan', 'Pembubaran PKI, perombakan kabinet, dan penurunan harga', 'Pemilu segera, otonomi daerah, dan kebebasan pers', 'Pemberantasan korupsi, kolusi, dan nepotisme', 'Kenaikan gaji PNS, penghapusan pajak, dan otonomi daerah', 'b'],
            ['Salah satu dampak positif penjajahan Belanda bagi Indonesia adalah...', 'Kemiskinan rakyat meningkat', 'Dibangunnya infrastruktur seperti jalan dan rel kereta api', 'Budaya Indonesia terhapus', 'Bahasa daerah punah', 'Agama asli masyarakat diganti', 'b'],
            ['Teori masuknya Islam ke Indonesia yang menyatakan Islam dibawa langsung dari Arab adalah Teori...', 'Gujarat', 'Persia', 'Makkah', 'China', 'Benggala', 'c'],
            ['Gerakan Non-Blok didirikan di Belgrade pada tahun...', '1955', '1959', '1961', '1963', '1965', 'c'],
            ['Konferensi Asia Afrika berlangsung di...', 'Jakarta, Indonesia', 'Kuala Lumpur, Malaysia', 'New Delhi, India', 'Bandung, Indonesia', 'Colombo, Sri Lanka', 'd'],
            ['Dwifungsi ABRI adalah doktrin yang menyatakan bahwa...', 'TNI bertugas di bidang pertahanan saja', 'TNI memiliki peran ganda: pertahanan dan sosial-politik', 'TNI adalah alat rakyat dan alat negara', 'TNI berada di bawah kendali parlemen', 'TNI tidak boleh berpolitik', 'b'],
            ['Kronologi sejarah yang benar mengenai kemerdekaan Indonesia adalah...', 'Proklamasi → Jepang menyerah → Sekutu datang', 'Jepang menyerah → Proklamasi → Sekutu datang', 'Sekutu datang → Proklamasi → Jepang menyerah', 'Sekutu datang → Jepang menyerah → Proklamasi', 'Proklamasi → Sekutu datang → Jepang menyerah', 'b'],
            ['Salah satu penyebab runtuhnya kerajaan Sriwijaya adalah...', 'Invasi dari Kerajaan Majapahit', 'Serangan Kerajaan Cola dari India', 'Penaklukan oleh Kerajaan Demak', 'Bencana alam besar', 'Serangan VOC Belanda', 'b'],
            ['Tanggal 10 November diperingati sebagai...', 'Hari Kemerdekaan', 'Hari Sumpah Pemuda', 'Hari Pahlawan', 'Hari Kebangkitan Nasional', 'Hari Kartini', 'c'],
            ['Sistem pemerintahan yang berlaku di Indonesia dari tahun 1949 hingga 1950 adalah...', 'Demokrasi Pancasila', 'Demokrasi Liberal', 'Demokrasi Terpimpin', 'Negara Kesatuan (UUD 1945)', 'Negara Federal (RIS)', 'e'],
            ['Pahlawan wanita dari Aceh yang terkenal dalam perlawanan terhadap Belanda adalah...', 'R.A. Kartini', 'Cut Nyak Dhien', 'Dewi Sartika', 'Nyi Ageng Serang', 'Martha Christina Tiahahu', 'b'],
            ['Perjanjian Renville antara Indonesia dan Belanda ditandatangani pada...', '12 Januari 1948', '17 Januari 1948', '25 Maret 1947', '19 Desember 1948', '27 Desember 1949', 'b'],
            ['Proklamasi kemerdekaan Indonesia dibacakan di...', 'Istana Merdeka, Jakarta', 'Lapangan IKADA, Jakarta', 'Jalan Pegangsaan Timur No. 56, Jakarta', 'Gedung Pancasila, Jakarta', 'Lapangan Banteng, Jakarta', 'c'],
            ['Tujuan Jepang masuk ke Asia Tenggara termasuk Indonesia adalah...', 'Membantu kemerdekaan bangsa Asia dari penjajahan', 'Mencari daerah pemasaran produk Jepang', 'Menguasai sumber daya alam untuk keperluan perang (Perang Pasifik)', 'Memperkenalkan kebudayaan Jepang', 'Membangun jaringan diplomatik di Asia', 'c'],
            ['Nasionalisme Indonesia pada awal abad ke-20 dipengaruhi oleh faktor...', 'Keberhasilan revolusi Prancis dan penderitaan akibat penjajahan', 'Pengaruh budaya Belanda yang masuk ke Indonesia', 'Keberhasilan peperangan di Eropa', 'Perkembangan teknologi pertanian', 'Hubungan dagang dengan negara Asia lainnya', 'a'],
            ['Lahirnya Budi Utomo dipelopori oleh...', 'Soekarno dan Hatta', 'dr. Soetomo dan para mahasiswa STOVIA', 'H.O.S. Tjokroaminoto', 'Ki Hajar Dewantara', 'Ahmad Dahlan', 'b'],
            ['Masa bercocok tanam di Indonesia ditandai dengan kemampuan manusia purba untuk...', 'Membuat peralatan logam', 'Berlayar mengarungi lautan', 'Menanam padi dan menetap di suatu tempat', 'Menggunakan tulisan', 'Membangun candi', 'c'],
            ['Kebudayaan Dongson yang masuk ke Indonesia berhubungan dengan...', 'Masuknya Islam di Nusantara', 'Kebudayaan perunggu (nekara, moko)', 'Pembangunan candi Hindu', 'Perdagangan rempah-rempah', 'Teknik pertanian sawah', 'b'],
            ['Undang-Undang Agraria 1870 (Agrarische Wet) di masa Belanda bertujuan...', 'Membagikan tanah kepada rakyat Indonesia', 'Membuka Indonesia bagi penanaman modal swasta asing', 'Menghapus sistem tanam paksa', 'Memberikan hak milik tanah kepada pribumi', 'Mengatur sistem pajak tanah', 'b'],
            ['Peristiwa bersejarah "Serangan Umum 1 Maret 1949" dipimpin oleh...', 'Jenderal Sudirman', 'Sri Sultan Hamengkubuwono IX', 'Soekarno', 'Letkol Soeharto', 'A.H. Nasution', 'd'],
            ['Periodisasi sejarah Indonesia berdasarkan Prasasti Yupa menunjukkan bahwa kerajaan pertama yang diketahui adalah...', 'Sriwijaya', 'Tarumanegara', 'Kutai Martadipura', 'Medang Mataram', 'Majapahit', 'c'],
            ['Salah satu faktor penyebab Perang Dunia I adalah...', 'Invasi Jerman ke Polandia', 'Pembunuhan Archduke Franz Ferdinand di Sarajevo', 'Serangan Jepang ke Pearl Harbor', 'Revolusi Bolshevik di Rusia', 'Penandatanganan Perjanjian Versailles', 'b'],
        ];
        foreach ($sejarah_questions as $q) {
            $bank_sejarah[] = Question::create([
                'subject' => 'Sejarah',
                'question_text' => $q[0], 'option_a' => $q[1], 'option_b' => $q[2],
                'option_c' => $q[3], 'option_d' => $q[4], 'option_e' => $q[5],
                'correct_option' => $q[6],
            ])->id;
        }

        // =====================================================================
        // MAPEL 7: IPA Terintegrasi (50 Soal Unik)
        // =====================================================================
        $bank_ipa = [];
        $ipa_questions = [
            ['Organel sel yang berfungsi sebagai tempat respirasi sel adalah...', 'Nukleus', 'Ribosom', 'Mitokondria', 'Kloroplas', 'Vakuola', 'c'],
            ['Proses fotosintesis menghasilkan...', 'CO₂ dan H₂O', 'O₂ dan glukosa', 'N₂ dan glukosa', 'CO₂ dan glukosa', 'O₂ dan H₂O', 'b'],
            ['Bunyi hukum Newton pertama adalah...', 'F = m × a', 'Setiap aksi menimbulkan reaksi yang besarnya sama', 'Benda diam cenderung tetap diam dan benda bergerak cenderung tetap bergerak', 'Energi tidak dapat diciptakan dan tidak dapat dimusnahkan', 'Gaya berbanding lurus dengan massa', 'c'],
            ['Rumus kimia air adalah...', 'H₂O₂', 'HO', 'H₂O', 'H₃O', 'OH', 'c'],
            ['Unsur yang memiliki nomor atom 6 adalah...', 'Nitrogen (N)', 'Oksigen (O)', 'Karbon (C)', 'Boron (B)', 'Neon (Ne)', 'c'],
            ['Reaksi kimia yang melepaskan energi (panas) disebut reaksi...', 'Endoterm', 'Netralisasi', 'Redoks', 'Eksoterm', 'Hidrolisis', 'd'],
            ['Jaringan pada tumbuhan yang berfungsi mengangkut air dan mineral dari akar ke daun adalah...', 'Floem', 'Xilem', 'Kambium', 'Epidermis', 'Meristem', 'b'],
            ['Hukum kekekalan massa dikemukakan oleh...', 'Isaac Newton', 'Antoine Lavoisier', 'John Dalton', 'Dmitri Mendeleev', 'Niels Bohr', 'b'],
            ['Gelombang elektromagnetik yang memiliki frekuensi paling tinggi adalah...', 'Gelombang radio', 'Sinar inframerah', 'Cahaya tampak', 'Sinar X', 'Sinar gamma', 'e'],
            ['Persilangan dua individu bergenotipe Bb × Bb menghasilkan keturunan dengan perbandingan fenotip...', '3 dominan : 1 resesif', '1 : 2 : 1', '1 : 1', '3 : 1 : 3', '2 : 1 : 2', 'a'],
            ['pH larutan bersifat asam berada di rentang...', 'pH 7−14', 'pH 0−7', 'pH tepat 7', 'pH 8−14', 'pH 5−9', 'b'],
            ['Tekanan hidrostatis dipengaruhi oleh...', 'Luas penampang dan massa cairan', 'Kerapatan cairan, gravitasi, dan kedalaman', 'Suhu dan volume cairan', 'Bentuk wadah cairan', 'Warna dan kejernihan cairan', 'b'],
            ['Sistem saraf pusat manusia terdiri dari...', 'Otak dan sumsum tulang belakang', 'Otak dan saraf tepi', 'Sumsum tulang belakang dan saraf otonom', 'Saraf somatik dan saraf otonom', 'Otak dan sistem limbik', 'a'],
            ['Atom yang kehilangan elektron akan membentuk ion...', 'Anion (bermuatan negatif)', 'Kation (bermuatan positif)', 'Atom netral yang lebih besar', 'Isotop', 'Isomer', 'b'],
            ['Prinsip kerja pompa air pada dasarnya menggunakan...', 'Hukum Newton I', 'Hukum Pascal', 'Hukum Archimedes', 'Hukum Hooke', 'Hukum Boyle', 'b'],
            ['Efek rumah kaca terjadi karena peningkatan konsentrasi gas...', 'Nitrogen (N₂)', 'Oksigen (O₂)', 'Karbon dioksida (CO₂) dan gas rumah kaca lainnya', 'Argon (Ar)', 'Hidrogen (H₂)', 'c'],
            ['Jenis ikatan kimia yang terjadi antara atom Na dan Cl dalam NaCl adalah...', 'Ikatan kovalen polar', 'Ikatan kovalen non-polar', 'Ikatan ion', 'Ikatan hidrogen', 'Ikatan logam', 'c'],
            ['Fungsi utama ginjal pada sistem ekskresi manusia adalah...', 'Menghasilkan enzim pencernaan', 'Menyaring darah dan menghasilkan urin', 'Mengatur kadar hormon', 'Memompa darah ke seluruh tubuh', 'Menghasilkan empedu', 'b'],
            ['Hukum Ohm menyatakan bahwa...', 'Arus listrik berbanding terbalik dengan tegangan', 'Tegangan (V) = Arus (I) × Hambatan (R)', 'Daya = Tegangan × Arus kuadrat', 'Hambatan berbanding lurus dengan arus', 'Energi = Massa × Kecepatan cahaya²', 'b'],
            ['Evolusi menurut Darwin didasarkan pada konsep...', 'Pewarisan sifat yang didapat (use and disuse)', 'Seleksi alam dan adaptasi', 'Mutasi gen yang selalu menguntungkan', 'Perubahan lingkungan menyebabkan perubahan gen', 'Reproduksi selalu menghasilkan keturunan yang lebih kuat', 'b'],
            ['Persamaan reaksi pembakaran glukosa (respirasi aerob) yang lengkap adalah...', 'C₆H₁₂O₆ + O₂ → CO₂ + H₂O', 'C₆H₁₂O₆ + 6O₂ → 6CO₂ + 6H₂O + Energi (ATP)', 'C₆H₁₂O₆ → CO₂ + H₂O + Alkohol', 'C₆H₁₂O₆ + CO₂ → O₂ + H₂O', '6CO₂ + 6H₂O → C₆H₁₂O₆ + 6O₂', 'b'],
            ['Besaran fisika yang merupakan besaran vektor adalah...', 'Massa', 'Waktu', 'Suhu', 'Gaya', 'Volume', 'd'],
            ['Lapisan ozon yang melindungi Bumi dari sinar UV terdapat di...', 'Troposfer', 'Stratosfer', 'Mesosfer', 'Termosfer', 'Eksosfer', 'b'],
            ['Peristiwa terjadinya hujan asam disebabkan oleh...', 'Peningkatan kadar CO₂ di atmosfer', 'Gas SO₂ dan NO₂ dari industri yang bereaksi dengan air hujan', 'Erosi tanah akibat hujan lebat', 'Peningkatan suhu laut', 'Aktivitas vulkanik yang intensif', 'b'],
            ['Dalam sistem tata surya, planet yang paling dekat dengan Matahari adalah...', 'Venus', 'Bumi', 'Mars', 'Merkurius', 'Jupiter', 'd'],
            ['Proses pembelahan sel yang menghasilkan dua sel anak identik dengan sel induk adalah...', 'Meiosis I', 'Meiosis II', 'Mitosis', 'Fertilisasi', 'Amitosis pada hewan tingkat tinggi', 'c'],
            ['Senyawa yang merupakan asam amino esensial harus diperoleh dari...', 'Sintesis dalam tubuh secara mandiri', 'Makanan karena tidak dapat disintesis tubuh', 'Suplementasi vitamin', 'Fermentasi dalam usus', 'Cahaya matahari', 'b'],
            ['Magnet permanen terbuat dari bahan...', 'Aluminium', 'Tembaga', 'Besi lunak', 'Baja keras', 'Perak', 'd'],
            ['Transformasi (perubahan) energi yang terjadi pada sel surya (fotovoltaik) adalah...', 'Energi kimia → Energi listrik', 'Energi mekanik → Energi listrik', 'Energi cahaya → Energi listrik', 'Energi panas → Energi listrik', 'Energi nuklir → Energi listrik', 'c'],
            ['Tulang pada sendi lutut termasuk jenis sendi...', 'Sendi putar', 'Sendi pelana', 'Sendi peluru', 'Sendi engsel', 'Sendi geser', 'd'],
            ['Teori Big Bang menyatakan bahwa alam semesta...', 'Selalu ada dan tidak pernah berubah', 'Berasal dari ledakan besar tunggal sekitar 13,8 miliar tahun lalu', 'Diciptakan 6.000 tahun lalu', 'Berputar mengelilingi Bumi', 'Tidak memiliki awal', 'b'],
            ['Komponen darah yang berfungsi dalam pembekuan darah adalah...', 'Sel darah merah (eritrosit)', 'Sel darah putih (leukosit)', 'Plasma darah', 'Trombosit', 'Hemoglobin', 'd'],
            ['Gelombang suara merambat paling cepat melalui medium...', 'Ruang hampa (vakum)', 'Gas', 'Zat cair', 'Zat padat', 'Plasma', 'd'],
            ['Reaksi pembentukan garam dari asam dan basa disebut...', 'Oksidasi', 'Reduksi', 'Netralisasi', 'Fermentasi', 'Hidrolisis', 'c'],
            ['Simbiosis mutualisme adalah hubungan antara dua organisme yang...', 'Satu diuntungkan, yang lain dirugikan', 'Satu diuntungkan, yang lain tidak terpengaruh', 'Keduanya saling diuntungkan', 'Keduanya saling dirugikan', 'Satu menumpang pada yang lain', 'c'],
            ['Zat yang dapat mempercepat reaksi kimia tanpa ikut bereaksi disebut...', 'Inhibitor', 'Reaktan', 'Produk', 'Katalis', 'Enzim destruktif', 'd'],
            ['Pada hukum kekekalan energi mekanik, jumlah energi kinetik dan energi potensial...', 'Selalu bertambah', 'Selalu berkurang', 'Tidak konstan', 'Selalu konstan (tetap) selama tidak ada gaya non-konservatif', 'Bergantung pada massa benda', 'd'],
            ['Pencemaran air yang disebabkan oleh pupuk pertanian mengandung nitrat dapat menyebabkan...', 'Peningkatan pH air secara drastis', 'Eutrofikasi (ledakan pertumbuhan alga) yang mengurangi oksigen', 'Pembentukan endapan lumpur', 'Air menjadi berwarna merah', 'Kematian ikan karena suhu air meningkat', 'b'],
            ['DNA (Deoxyribonucleic Acid) terdiri atas monomer berupa...', 'Asam amino', 'Glukosa', 'Nukleotida (fosfat, gula deoksiribosa, dan basa nitrogen)', 'Asam lemak', 'Gliserol', 'c'],
            ['Lensa cembung (konveks) digunakan untuk menolong penderita...', 'Miopi (rabun jauh)', 'Hipermetropi (rabun dekat)', 'Astigmatisme (silinder)', 'Presbiopi dan miopi', 'Buta warna', 'b'],
            ['Perpindahan panas secara konveksi terjadi pada...', 'Perpindahan langsung antar partikel tanpa gerakan', 'Perpindahan melalui medium yang tidak bergerak', 'Perpindahan melalui gerakan massa fluida (zat cair atau gas)', 'Perpindahan tanpa medium apapun', 'Perpindahan melalui gelombang elektromagnetik', 'c'],
            ['Organisme yang dapat membuat makanan sendiri (produsen) disebut...', 'Heterotrof', 'Saprofit', 'Dekomposer', 'Autotrof', 'Parasit', 'd'],
            ['Pada elektrolisis, elektron mengalir dari anoda ke katoda melalui...', 'Larutan elektrolit', 'Rangkaian luar (kawat penghantar)', 'Ion dalam larutan', 'Jembatan garam', 'Membran semipermeable', 'b'],
            ['Gempa bumi diukur menggunakan alat yang disebut...', 'Barometer', 'Termometer', 'Seismograf', 'Anemometer', 'Higrometer', 'c'],
            ['Proses perpindahan gen dari satu generasi ke generasi berikutnya disebut...', 'Mutasi', 'Seleksi alam', 'Rekombinasi', 'Pewarisan sifat (hereditas)', 'Adaptasi', 'd'],
            ['Suatu larutan memiliki pH = 9, maka larutan tersebut bersifat...', 'Asam lemah', 'Asam kuat', 'Netral', 'Basa lemah', 'Basa kuat', 'd'],
            ['Kuat arus listrik (I) diukur menggunakan...', 'Voltmeter', 'Ohmmeter', 'Amperemeter', 'Wattmeter', 'Galvanometer', 'c'],
            ['Mutasi gen dapat disebabkan oleh paparan...', 'Sinar inframerah', 'Sinar ultraviolet dan zat mutagen kimia', 'Gelombang radio', 'Cahaya tampak berwarna biru', 'Gelombang mikro dari microwave', 'b'],
            ['Keanekaragaman hayati tingkat gen ditunjukkan oleh...', 'Berbagai jenis organisme yang berbeda', 'Berbagai ekosistem di bumi', 'Variasi dalam satu spesies (seperti anjing berbagai ras)', 'Perbedaan antara flora dan fauna', 'Berbagai kingdom makhluk hidup', 'c'],
            ['Energi yang tersimpan dalam ikatan kimia bahan bakar diubah menjadi energi kinetik pada mesin...', 'Generator listrik', 'Panel surya', 'Motor bakar (mesin bensin/diesel)', 'Reaktor nuklir', 'Kincir angin', 'c'],
        ];
        foreach ($ipa_questions as $q) {
            $bank_ipa[] = Question::create([
                'subject' => 'IPA Terintegrasi',
                'question_text' => $q[0], 'option_a' => $q[1], 'option_b' => $q[2],
                'option_c' => $q[3], 'option_d' => $q[4], 'option_e' => $q[5],
                'correct_option' => $q[6],
            ])->id;
        }

        // =====================================================================
        // MAPEL 8: IPS Terintegrasi (50 Soal Unik)
        // =====================================================================
        $bank_ips = [];
        $ips_questions = [
            ['Interaksi sosial yang menghasilkan kerja sama positif disebut...', 'Konflik', 'Asimilasi', 'Akomodasi', 'Kooperasi', 'Kompetisi', 'd'],
            ['Ilmu yang mempelajari interaksi manusia dalam kelompok sosial adalah...', 'Geografi', 'Ekonomi', 'Sosiologi', 'Antropologi', 'Sejarah', 'c'],
            ['Peta yang menunjukkan kenampakan fisik bumi seperti gunung dan sungai disebut peta...', 'Tematik', 'Topografi', 'Korografi', 'Kadaster', 'Navigasi', 'b'],
            ['Sistem ekonomi di mana keputusan ekonomi dikendalikan penuh oleh negara disebut...', 'Ekonomi pasar bebas', 'Ekonomi campuran', 'Ekonomi terpusat (komando)', 'Ekonomi tradisional', 'Ekonomi liberal', 'c'],
            ['Faktor yang memengaruhi persebaran penduduk di suatu wilayah antara lain...', 'Warna bendera negara', 'Kondisi fisik lahan dan ketersediaan air', 'Jumlah partai politik', 'Tinggi badan rata-rata penduduk', 'Jenis musik yang populer', 'b'],
            ['Nilai tukar mata uang suatu negara terhadap mata uang negara lain disebut...', 'Inflasi', 'Deflasi', 'Kurs (valuta asing)', 'Suku bunga', 'Cadangan devisa', 'c'],
            ['Contoh lembaga sosial dalam bidang agama adalah...', 'Pengadilan', 'Sekolah', 'Masjid, gereja, pura', 'Pasar', 'Partai Politik', 'c'],
            ['Revolusi Industri yang mengubah dunia dimulai di...', 'Perancis', 'Jerman', 'Amerika Serikat', 'Inggris', 'Italia', 'd'],
            ['Persebaran penduduk yang tidak merata di Indonesia terutama disebabkan oleh...', 'Perbedaan iklim antarwilayah', 'Ketidakseimbangan pembangunan antara Jawa dan luar Jawa', 'Perbedaan kesuburan tanah di seluruh pulau', 'Kebijakan transmigrasi yang gagal', 'Larangan pemerintah untuk berpindah tempat', 'b'],
            ['Harga keseimbangan pasar terjadi saat...', 'Penawaran melebihi permintaan', 'Permintaan melebihi penawaran', 'Jumlah yang diminta sama dengan jumlah yang ditawarkan', 'Pemerintah menetapkan harga maksimum', 'Produsen menentukan harga sepihak', 'c'],
            ['Urbanisasi adalah...', 'Perpindahan penduduk dari kota ke desa', 'Perpindahan penduduk dari desa ke kota', 'Perpindahan penduduk antar negara', 'Pertumbuhan penduduk alami', 'Penyebaran penduduk ke seluruh wilayah', 'b'],
            ['Dampak negatif dari globalisasi di bidang budaya adalah...', 'Kemudahan pertukaran informasi', 'Peningkatan kerja sama antarnegara', 'Masuknya budaya asing yang tidak sesuai norma lokal', 'Perkembangan teknologi yang pesat', 'Terbukanya pasar ekspor', 'c'],
            ['PDB (Produk Domestik Bruto) mengukur...', 'Total kekayaan warga negara di luar negeri', 'Total nilai barang dan jasa yang diproduksi dalam suatu negara dalam satu periode', 'Jumlah utang negara', 'Cadangan emas negara', 'Total impor dan ekspor negara', 'b'],
            ['Tindakan diskriminasi rasial dalam kehidupan sosial adalah contoh dari...', 'Integrasi sosial', 'Asimilasi yang berhasil', 'Stratifikasi sosial yang sehat', 'Prasangka dan stereotip negatif yang melanggar HAM', 'Mobilitas sosial vertikal', 'd'],
            ['Lempeng tektonik Indonesia yang sangat aktif menyebabkan Indonesia rawan...', 'Banjir bandang saja', 'Gempa bumi dan letusan gunung api', 'Kekeringan panjang', 'Badai tropis', 'Kebakaran hutan secara alami', 'b'],
            ['Konsep dasar ekonomi yang mempelajari unit ekonomi kecil (rumah tangga dan perusahaan) adalah...', 'Makroekonomi', 'Ekonometri', 'Mikroekonomi', 'Moneter', 'Fiskal', 'c'],
            ['Letak astronomis Indonesia adalah...', '6°LU−11°LS dan 95°BT−141°BT', '6°LU−11°LU dan 95°BB−141°BB', '11°LU−6°LS dan 95°BT−141°BT', '6°LU−11°LS dan 95°BB−141°BB', '11°LS−6°LU dan 95°BT−141°BT', 'a'],
            ['Sistem pemerintahan yang memisahkan kekuasaan menjadi legislatif, eksekutif, dan yudikatif disebut...', 'Monarki absolut', 'Oligarki', 'Trias Politica', 'Teokrasi', 'Anarki', 'c'],
            ['Salah satu upaya pemerintah mengatasi pengangguran adalah...', 'Mengurangi fasilitas kesehatan', 'Mencabut program beasiswa', 'Membuka lapangan kerja baru melalui investasi dan pembangunan', 'Membatasi pertumbuhan industri', 'Menaikkan pajak penghasilan', 'c'],
            ['Norma yang bersumber dari Tuhan dan memiliki sanksi di akhirat adalah norma...', 'Hukum', 'Kesopanan', 'Kesusilaan', 'Agama', 'Adat', 'd'],
            ['Iklim yang ditentukan oleh letak geografis terhadap garis lintang disebut iklim...', 'Musim', 'Matahari', 'Darat', 'Laut', 'Fisiografis', 'b'],
            ['Pembangunan berkelanjutan (sustainable development) bertujuan untuk...', 'Memaksimalkan eksploitasi sumber daya untuk generasi sekarang', 'Memenuhi kebutuhan generasi sekarang tanpa mengorbankan kemampuan generasi mendatang', 'Menghentikan semua kegiatan industri demi lingkungan', 'Membangun ekonomi tanpa memperhatikan lingkungan', 'Memprioritaskan kebutuhan negara maju', 'b'],
            ['Badan dunia yang khusus menangani masalah kesehatan internasional adalah...', 'UNESCO', 'ILO', 'FAO', 'WHO', 'UNICEF', 'd'],
            ['Inflasi adalah...', 'Penurunan harga barang secara umum dan terus-menerus', 'Kenaikan nilai mata uang', 'Kenaikan harga barang secara umum dan terus-menerus', 'Penurunan nilai ekspor', 'Kelebihan pasokan barang di pasar', 'c'],
            ['Jenis mobilitas sosial yang terjadi ketika seseorang berpindah ke lapisan sosial yang lebih tinggi disebut...', 'Mobilitas horizontal', 'Mobilitas lateral', 'Mobilitas vertikal ke bawah', 'Mobilitas vertikal ke atas', 'Mobilitas geografis', 'd'],
            ['ASEAN (Association of Southeast Asian Nations) didirikan melalui Deklarasi Bangkok pada tahun...', '1945', '1955', '1967', '1970', '1975', 'c'],
            ['Kegiatan ekspor barang dari Indonesia ke negara lain memberikan dampak...', 'Menambah pasokan barang dalam negeri', 'Menambah devisa negara dan meningkatkan perekonomian', 'Mengurangi nilai tukar rupiah', 'Meningkatkan impor barang', 'Mengurangi cadangan bahan baku', 'b'],
            ['Sosialisasi primer adalah proses sosialisasi yang berlangsung di...', 'Sekolah', 'Lingkungan kerja', 'Media sosial', 'Keluarga (usia dini)', 'Masyarakat luas', 'd'],
            ['Adat istiadat yang apabila dilanggar mendapat sanksi sosial dari masyarakat termasuk norma...', 'Agama', 'Kesusilaan', 'Kesopanan dan adat (mores)', 'Hukum formal tertulis', 'Etika profesional', 'c'],
            ['Sumber daya alam yang tidak dapat diperbarui adalah...', 'Air', 'Tanah pertanian', 'Hutan hujan tropis', 'Minyak bumi dan batu bara', 'Angin', 'd'],
            ['Kondisi geografi Indonesia yang dikelilingi dua samudra dan dua benua membuat Indonesia memiliki posisi...', 'Geografis yang terisolasi', 'Strategis dalam jalur perdagangan internasional', 'Rentan terhadap serangan dari luar', 'Sulit dijangkau oleh negara lain', 'Terpencil dari pergaulan dunia', 'b'],
            ['Prinsip keadilan sosial dalam ekonomi bertujuan untuk...', 'Menjamin bahwa orang kaya semakin kaya', 'Menjamin pendistribusian kekayaan yang lebih merata', 'Membatasi kebebasan berwirausaha', 'Menghilangkan perbedaan kelas sosial sepenuhnya', 'Mengontrol semua kegiatan ekonomi oleh negara', 'b'],
            ['Kebudayaan yang dihasilkan dari percampuran dua kebudayaan atau lebih disebut...', 'Asimilasi', 'Akulturasi', 'Difusi', 'Inovasi', 'Enkultural', 'b'],
            ['Pelaku utama dalam kegiatan ekonomi adalah...', 'Pemerintah saja', 'Perusahaan multinasional saja', 'Rumah tangga konsumen, produsen, pemerintah, dan luar negeri', 'Bank sentral dan lembaga keuangan', 'Pedagang pasar dan eksportir', 'c'],
            ['Apa yang dimaksud dengan "bencana alam" dalam perspektif IPS?', 'Hanya bencana yang terjadi di laut', 'Peristiwa alam yang terjadi karena ulah manusia saja', 'Peristiwa atau rangkaian peristiwa alam yang mengancam kehidupan dan penghidupan manusia', 'Bencana yang selalu dapat diprediksi', 'Hanya gempa bumi dan tsunami', 'c'],
            ['Perubahan sosial yang terjadi dengan cepat dan memengaruhi sendi-sendi kehidupan masyarakat disebut...', 'Evolusi sosial', 'Reformasi kecil', 'Revolusi sosial', 'Adaptasi sosial', 'Difusi budaya', 'c'],
            ['Laut yang berada di antara dua pulau atau lebih yang merupakan bagian wilayah Indonesia disebut laut...', 'Teritorial', 'ZEE', 'Nusantara', 'Landas Kontinen', 'Bebas', 'c'],
            ['Bank Indonesia berfungsi sebagai...', 'Bank umum yang melayani masyarakat', 'Bank tabungan untuk PNS', 'Bank sentral yang mengatur kebijakan moneter', 'Bank investasi untuk perusahaan swasta', 'Bank perkreditan rakyat', 'c'],
            ['Kemiskinan absolut adalah kondisi di mana seseorang...', 'Merasa kurang puas dengan pendapatannya', 'Memiliki penghasilan di bawah standar minimum untuk memenuhi kebutuhan dasar', 'Tidak memiliki tabungan', 'Lebih miskin dari tetangganya', 'Tidak memiliki pekerjaan tetap', 'b'],
            ['Piramida penduduk dengan bentuk limas (melebar di bawah) menunjukkan...', 'Angka kelahiran rendah dan kematian tinggi', 'Jumlah penduduk usia tua lebih banyak', 'Angka kelahiran tinggi dan penduduk muda mendominasi', 'Pertumbuhan penduduk yang stagnan', 'Jumlah penduduk yang seimbang di semua kelompok usia', 'c'],
            ['Globalisasi di bidang ekonomi ditandai dengan...', 'Pembatasan perdagangan antarnegara', 'Munculnya batas-batas negara yang semakin ketat', 'Integrasi ekonomi dan perdagangan bebas antar negara di seluruh dunia', 'Penguatan ekonomi lokal yang tertutup', 'Larangan investasi asing', 'c'],
            ['Konsep "Wawasan Nusantara" merupakan cara pandang Indonesia terhadap...', 'Kebijakan luar negeri Indonesia', 'Wilayah dan lingkungan kehidupan bangsa Indonesia sebagai satu kesatuan', 'Budaya dan tradisi nusantara', 'Sistem pemerintahan Indonesia', 'Pertahanan militer Indonesia', 'b'],
            ['Fungsi utama pasar dalam sistem ekonomi adalah...', 'Tempat pemerintah mengontrol harga', 'Tempat bertemunya penjual dan pembeli untuk menentukan harga dan jumlah barang', 'Wadah distribusi bantuan sosial', 'Pusat industri manufaktur', 'Tempat penyimpanan cadangan pangan', 'b'],
            ['Contoh migrasi internasional adalah...', 'Penduduk Jawa pindah ke Kalimantan', 'Transmigrasi dari Jawa ke Sumatra', 'Urbanisasi dari desa ke kota', 'TKI (Tenaga Kerja Indonesia) bekerja di luar negeri', 'Perpindahan dari satu kota ke kota lain', 'd'],
            ['Tenaga kerja terdidik yang sulit mendapatkan pekerjaan meskipun memiliki keahlian disebut pengangguran...', 'Friksional', 'Musiman', 'Teknologis', 'Terdidik (intelektual)', 'Struktural', 'd'],
            ['Peta skala besar digunakan untuk menampilkan...', 'Wilayah yang sangat luas seperti benua', 'Peta dunia', 'Wilayah yang sempit dengan detail tinggi', 'Peta jalur penerbangan internasional', 'Peta distribusi penduduk dunia', 'c'],
            ['Konflik sosial dapat diselesaikan melalui cara...', 'Pemaksaan kehendak satu pihak', 'Mengabaikan pihak yang berkonflik', 'Mediasi, negosiasi, dan rekonsiliasi', 'Menghilangkan salah satu pihak', 'Menyerahkan sepenuhnya kepada aparat', 'c'],
            ['Pembangunan ekonomi yang tidak memperhatikan aspek lingkungan dapat mengakibatkan...', 'Pertumbuhan ekonomi yang lebih cepat', 'Kesejahteraan masyarakat yang meningkat pesat', 'Kerusakan lingkungan yang mengancam generasi mendatang', 'Pemerataan pendapatan yang lebih baik', 'Meningkatnya kualitas sumber daya manusia', 'c'],
            ['Keunggulan komparatif suatu negara dalam perdagangan internasional berarti...', 'Negara tersebut unggul dalam semua bidang produksi', 'Negara tersebut memiliki teknologi paling canggih', 'Negara tersebut dapat memproduksi barang tertentu dengan biaya relatif lebih rendah', 'Negara tersebut memiliki cadangan emas terbesar', 'Negara tersebut menguasai pasar dunia', 'c'],
            ['Era digital membawa perubahan sosial berupa...', 'Hilangnya interaksi sosial secara keseluruhan', 'Munculnya komunitas virtual dan pergeseran pola komunikasi masyarakat', 'Semakin kuatnya nilai-nilai tradisional', 'Berkurangnya kesenjangan ekonomi antara si kaya dan si miskin', 'Meratanya distribusi teknologi di seluruh lapisan masyarakat', 'b'],
        ];
        foreach ($ips_questions as $q) {
            $bank_ips[] = Question::create([
                'subject' => 'IPS Terintegrasi',
                'question_text' => $q[0], 'option_a' => $q[1], 'option_b' => $q[2],
                'option_c' => $q[3], 'option_d' => $q[4], 'option_e' => $q[5],
                'correct_option' => $q[6],
            ])->id;
        }

        // =====================================================================
        // MAPEL 9: Informatika (50 Soal Unik)
        // =====================================================================
        $bank_tik = [];
        $tik_questions = [
            ['Kepanjangan dari CPU adalah...', 'Central Programming Unit', 'Central Process Unit', 'Central Processing Unit', 'Computer Processing Unit', 'Core Processing Unit', 'c'],
            ['Satuan data terkecil dalam komputer disebut...', 'Byte', 'Kilobyte', 'Bit', 'Nibble', 'Word', 'c'],
            ['Sistem bilangan yang hanya menggunakan angka 0 dan 1 disebut sistem bilangan...', 'Desimal', 'Oktal', 'Heksadesimal', 'Biner', 'Tersenary', 'd'],
            ['Fungsi utama RAM (Random Access Memory) adalah...', 'Menyimpan data secara permanen', 'Memproses instruksi program', 'Menyimpan data sementara yang sedang diproses', 'Menghubungkan komputer ke jaringan', 'Mengatur tampilan layar', 'c'],
            ['Protokol yang digunakan untuk mengakses halaman web adalah...', 'FTP', 'SMTP', 'HTTP/HTTPS', 'POP3', 'SSH', 'c'],
            ['Bahasa pemrograman Python pertama kali dikembangkan oleh...', 'Dennis Ritchie', 'Linus Torvalds', 'Guido van Rossum', 'James Gosling', 'Bjarne Stroustrup', 'c'],
            ['Algoritma adalah...', 'Bahasa pemrograman generasi baru', 'Urutan langkah-langkah logis untuk menyelesaikan masalah', 'Perangkat keras komputer', 'Sistem operasi komputer', 'Jaringan komputer', 'b'],
            ['Firewall dalam jaringan komputer berfungsi untuk...', 'Mempercepat koneksi internet', 'Menyimpan data pengguna', 'Memfilter dan mengontrol lalu lintas jaringan demi keamanan', 'Mengatur alamat IP', 'Menghubungkan dua jaringan berbeda', 'c'],
            ['Tipe data boolean hanya memiliki dua nilai, yaitu...', 'Integer dan Float', 'String dan Char', 'True dan False', 'Yes dan No', '0 dan 1 saja (bukan boolean)', 'c'],
            ['Dalam pemrograman, perulangan (loop) yang tidak akan berhenti jika tidak ada kondisi pemberhenti disebut...', 'Infinite loop', 'Nested loop', 'While loop', 'For loop', 'Do-while loop', 'a'],
            ['Kecerdasan Buatan (AI) adalah bidang ilmu komputer yang mempelajari...', 'Cara membuat robot fisik', 'Simulasi kecerdasan manusia oleh mesin/komputer', 'Pengkabelan komputer', 'Desain chip komputer', 'Pembuatan baterai komputer', 'b'],
            ['HTML adalah singkatan dari...', 'HyperText Machine Language', 'HyperText Markup Language', 'High-level Text Markup Language', 'HyperText Media Language', 'Hyper Transfer Markup Language', 'b'],
            ['Alamat IP (Internet Protocol) versi 4 (IPv4) terdiri dari berapa bit?', '8 bit', '16 bit', '24 bit', '32 bit', '64 bit', 'd'],
            ['Enkripsi data bertujuan untuk...', 'Mempercepat pengiriman data', 'Mengubah data menjadi format yang tidak bisa dibaca tanpa kunci dekripsi', 'Menghapus data yang tidak diperlukan', 'Memadatkan ukuran file', 'Mencadangkan data', 'b'],
            ['Dalam struktur data, antrian (queue) bekerja berdasarkan prinsip...', 'LIFO (Last In First Out)', 'FIFO (First In First Out)', 'LILO (Last In Last Out)', 'FILO (First In Last Out)', 'Random access', 'b'],
            ['Sistem operasi yang bersifat open-source dan gratis adalah...', 'Windows 11', 'macOS Ventura', 'Linux Ubuntu', 'iOS', 'Chrome OS (versi komersial)', 'c'],
            ['Perangkat lunak yang dirancang untuk merusak, mengganggu, atau mendapatkan akses tidak sah ke sistem komputer disebut...', 'Antivirus', 'Firmware', 'Malware', 'Driver', 'Middleware', 'c'],
            ['Konversi bilangan biner 1010 ke desimal adalah...', '8', '9', '10', '11', '12', 'c'],
            ['Dalam bahasa SQL, perintah untuk mengambil data dari database adalah...', 'INSERT', 'UPDATE', 'DELETE', 'SELECT', 'CREATE', 'd'],
            ['Cloud computing memungkinkan pengguna untuk...', 'Mengakses sumber daya komputasi melalui internet tanpa perlu perangkat keras lokal yang mahal', 'Menyimpan data hanya di hard disk lokal', 'Menjalankan program tanpa koneksi internet', 'Membeli software secara permanen', 'Menggunakan komputer tanpa sistem operasi', 'a'],
            ['Prinsip OOP (Object-Oriented Programming) yang menyembunyikan detail implementasi internal disebut...', 'Inheritance (Pewarisan)', 'Polymorphism', 'Encapsulation (Enkapsulasi)', 'Abstraction (Abstraksi)', 'Composition', 'c'],
            ['CSS dalam pengembangan web berfungsi untuk...', 'Mendefinisikan struktur halaman web', 'Mengatur tampilan dan gaya visual halaman web', 'Menghubungkan database ke web', 'Membuat server web', 'Memroses data dari formulir', 'b'],
            ['Metode agile dalam pengembangan perangkat lunak berfokus pada...', 'Perencanaan yang sangat detail di awal', 'Pengembangan bertahap, fleksibel, dan kolaboratif dengan feedback rutin', 'Pengembangan oleh satu programmer saja', 'Tidak ada dokumentasi sama sekali', 'Pengujian hanya di akhir proyek', 'b'],
            ['Kecepatan transfer data yang biasa digunakan di koneksi internet diukur dalam...', 'Hertz (Hz)', 'Megapixel', 'Mbps (Megabits per second)', 'GB (Gigabyte)', 'Watt', 'c'],
            ['Stack (tumpukan) dalam struktur data bekerja berdasarkan prinsip...', 'FIFO (First In First Out)', 'LIFO (Last In First Out)', 'Acak (random)', 'Prioritas tertinggi lebih dulu', 'Urutan abjad', 'b'],
            ['Perangkat keras yang berfungsi menghubungkan komputer ke internet melalui sambungan telepon adalah...', 'Router', 'Switch', 'Modem', 'Hub', 'Access Point', 'c'],
            ['Dalam pseudocode, instruksi IF-THEN-ELSE digunakan untuk...', 'Pengulangan proses', 'Percabangan berdasarkan kondisi tertentu', 'Mendeklarasikan variabel', 'Memasukkan data dari pengguna', 'Mengakhiri program', 'b'],
            ['Big Data dicirikan oleh 3V, yaitu...', 'Volume, Value, Velocity', 'Volume, Variety, Velocity', 'Value, Variety, Volume', 'Velocity, Validity, Volume', 'Virtual, Volume, Variety', 'b'],
            ['Flowchart (diagram alir) dalam pemrograman digunakan untuk...', 'Menulis kode program secara langsung', 'Menggambarkan alur/logika algoritma secara visual', 'Mendesain tampilan antarmuka', 'Mendokumentasikan database', 'Mengukur kecepatan program', 'b'],
            ['Resolusi layar 1920 × 1080 piksel dikenal dengan nama...', 'HD (720p)', 'FHD (Full HD / 1080p)', '4K (Ultra HD)', 'QHD (1440p)', 'SD (480p)', 'b'],
            ['IoT (Internet of Things) adalah konsep di mana...', 'Internet hanya untuk komputer dan ponsel', 'Objek-objek fisik sehari-hari terhubung ke internet dan dapat bertukar data', 'Internet digunakan untuk tujuan hiburan saja', 'Semua data disimpan secara lokal', 'Jaringan komputer hanya di dalam kantor', 'b'],
            ['Dalam basis data relasional, primary key berfungsi sebagai...', 'Kata sandi tabel', 'Penghubung ke tabel lain', 'Pengidentifikasi unik setiap baris/record dalam tabel', 'Nama tabel database', 'Jenis data dalam kolom', 'c'],
            ['Phishing adalah serangan siber yang bertujuan...', 'Memperlambat kecepatan internet', 'Mencuri informasi sensitif dengan menyamar sebagai entitas tepercaya', 'Memblokir akses ke situs web', 'Merusak perangkat keras komputer', 'Menghapus semua file di komputer', 'b'],
            ['Bahasa pemrograman yang paling umum digunakan untuk pengembangan web di sisi klien (client-side) adalah...', 'Python', 'Java', 'C++', 'JavaScript', 'PHP', 'd'],
            ['Konversi bilangan heksadesimal FF ke desimal adalah...', '150', '200', '255', '256', '250', 'c'],
            ['VPN (Virtual Private Network) digunakan untuk...', 'Meningkatkan kecepatan internet', 'Membuat jaringan yang aman dan terenkripsi melalui internet publik', 'Menyimpan data di cloud', 'Mengatur prioritas bandwidth', 'Memblokir iklan online', 'b'],
            ['Pengujian perangkat lunak yang dilakukan oleh tim internal pengembang disebut...', 'Beta testing', 'User acceptance testing', 'Alpha testing', 'Regression testing', 'Stress testing', 'c'],
            ['Kompiler (compiler) adalah program yang...', 'Menjalankan program baris per baris secara langsung', 'Menerjemahkan kode sumber menjadi kode mesin secara keseluruhan sebelum dijalankan', 'Mencari kesalahan dalam database', 'Menghubungkan komputer ke jaringan', 'Mengelola file sistem operasi', 'b'],
            ['Rekursi dalam pemrograman adalah konsep di mana...', 'Suatu fungsi memanggil fungsi lain yang berbeda', 'Suatu fungsi memanggil dirinya sendiri', 'Sebuah loop yang tidak pernah berhenti', 'Penggunaan banyak variabel sekaligus', 'Fungsi yang tidak mengembalikan nilai', 'b'],
            ['Keamanan kata sandi yang baik menggunakan kombinasi...', 'Nama pengguna yang sama dengan kata sandi', 'Tanggal lahir yang mudah diingat', 'Huruf besar, huruf kecil, angka, dan simbol dengan panjang minimal 12 karakter', 'Kata-kata dari kamus yang umum', 'Angka saja minimal 4 digit', 'c'],
            ['Perangkat output yang menghasilkan informasi dalam bentuk cetakan fisik disebut...', 'Monitor', 'Speaker', 'Proyektor', 'Printer', 'Plotter', 'd'],
            ['Machine Learning adalah cabang AI yang memungkinkan komputer...', 'Bergerak seperti robot', 'Belajar dari data tanpa diprogram secara eksplisit untuk setiap tugas', 'Menggantikan manusia sepenuhnya', 'Menyimpan data tanpa batas', 'Menghitung lebih cepat dari superkomputer', 'b'],
            ['Format file yang umum digunakan untuk pertukaran data terstruktur di web adalah...', 'DOC', 'XLS', 'JSON dan XML', 'MP4', 'PDF', 'c'],
            ['Jaringan komputer yang mencakup area yang sangat luas (antar kota/negara) disebut...', 'LAN (Local Area Network)', 'MAN (Metropolitan Area Network)', 'PAN (Personal Area Network)', 'WAN (Wide Area Network)', 'CAN (Campus Area Network)', 'd'],
            ['Basis data NoSQL berbeda dari SQL karena...', 'NoSQL tidak bisa menyimpan data teks', 'NoSQL menggunakan struktur tabel yang kaku', 'NoSQL bersifat fleksibel dan tidak memerlukan skema tetap', 'NoSQL hanya untuk data numerik', 'NoSQL tidak dapat diakses melalui internet', 'c'],
            ['Metode pengembangan software "Waterfall" memiliki karakteristik...', 'Pengembangan yang fleksibel dan iteratif', 'Pengembangan yang dilakukan secara linear dan berurutan', 'Tidak ada fase perencanaan', 'Pengujian dilakukan di awal', 'Tidak ada dokumentasi', 'b'],
            ['Keuntungan menggunakan sistem operasi berbasis cloud adalah...', 'Tidak memerlukan koneksi internet sama sekali', 'Pembaruan dan pemeliharaan lebih mudah dan sumber daya dapat diakses dari mana saja', 'Lebih aman karena tidak terhubung ke internet', 'Tidak memerlukan perangkat keras sama sekali', 'Data tidak dapat dicuri karena ada di cloud', 'b'],
            ['Kode ASCII untuk huruf "A" (kapital) adalah...', '65', '97', '48', '90', '41', 'a'],
            ['Cyberlaw adalah...', 'Hukum yang mengatur perang siber', 'Hukum yang mengatur kejahatan di dunia maya dan transaksi elektronik', 'Aturan bermain game online', 'Standar pemrograman internasional', 'Lisensi perangkat lunak', 'b'],
            ['Kompresi data bertujuan untuk...', 'Mengenkripsi data agar lebih aman', 'Mempercepat prosesor komputer', 'Mengurangi ukuran file sehingga lebih efisien dalam penyimpanan dan pengiriman', 'Menduplikasi data sebagai cadangan', 'Mengonversi format file', 'c'],
        ];
        foreach ($tik_questions as $q) {
            $bank_tik[] = Question::create([
                'subject' => 'Informatika',
                'question_text' => $q[0], 'option_a' => $q[1], 'option_b' => $q[2],
                'option_c' => $q[3], 'option_d' => $q[4], 'option_e' => $q[5],
                'correct_option' => $q[6],
            ])->id;
        }

        // =====================================================================
        // MAPEL 10: PJOK (50 Soal Unik)
        // =====================================================================
        $bank_pjok = [];
        $pjok_questions = [
            ['Cabang olahraga atletik yang melombakan lemparan bola besi berat disebut...', 'Lempar lembing', 'Lempar cakram', 'Tolak peluru', 'Lempar martil', 'Lompat tinggi', 'c'],
            ['Olahraga renang gaya kupu-kupu dikenal juga dengan nama gaya...', 'Dada', 'Bebas (crawl)', 'Punggung', 'Dolphin / butterfly', 'Samping', 'd'],
            ['Dalam permainan sepak bola, pertandingan berlangsung selama...', '40 menit (2 × 20 menit)', '60 menit (2 × 30 menit)', '80 menit (2 × 40 menit)', '90 menit (2 × 45 menit)', '120 menit (2 × 60 menit)', 'd'],
            ['Teknik dasar bola voli yang digunakan untuk menerima servis atau serangan lawan dengan kedua lengan bawah disebut...', 'Smash', 'Spike', 'Block', 'Passing bawah', 'Servis atas', 'd'],
            ['Senam yang mengutamakan keindahan gerakan dan dilakukan tanpa alat disebut senam...', 'Ketangkasan', 'Ritmik', 'Aerobik', 'Artistik', 'Akrobatik', 'b'],
            ['Dalam bulu tangkis, sistem poin yang digunakan secara resmi adalah...', '15 poin per set', '21 poin per set dengan sistem rally point', '11 poin per set', '25 poin per set', '15 poin dengan sistem tradisional', 'b'],
            ['Induk organisasi olahraga voli internasional adalah...', 'FIFA', 'FIBA', 'FIVB', 'BWF', 'IAAF', 'c'],
            ['Prinsip latihan FITT (Frequency, Intensity, Time, Type) dalam kebugaran jasmani mengacu pada...', 'Empat komponen gizi seimbang', 'Empat variabel yang harus diperhatikan dalam program latihan', 'Empat jenis olahraga yang dianjurkan', 'Empat fase dalam pertandingan', 'Empat jenis cedera olahraga', 'b'],
            ['Penyakit yang menyerang sistem pernapasan dan dapat menular melalui udara adalah...', 'Diabetes mellitus', 'Hipertensi', 'Tuberkulosis (TBC)', 'Asam urat', 'Osteoporosis', 'c'],
            ['Teknik dasar pencak silat yang berupa gerakan menghindari serangan lawan disebut...', 'Tendangan', 'Pukulan', 'Tangkisan', 'Elakan / hindaran', 'Kuncian', 'd'],
            ['Jarak lari maraton yang menjadi standar internasional adalah...', '21,0975 km', '42,195 km', '25 km', '50 km', '10 km', 'b'],
            ['Dalam permainan basket, berapa poin yang diperoleh dari tembakan di luar garis arc (3 point line)?', '1 poin', '2 poin', '3 poin', '4 poin', '5 poin', 'c'],
            ['Apa yang dimaksud dengan doping dalam olahraga?', 'Teknik latihan intensitas tinggi', 'Konsumsi suplemen vitamin secara teratur', 'Penggunaan zat atau metode terlarang untuk meningkatkan performa', 'Pola makan khusus atlet', 'Istirahat yang berlebihan sebelum pertandingan', 'c'],
            ['Latihan yang bertujuan meningkatkan kemampuan jantung dan paru-paru disebut latihan...', 'Kekuatan (strength)', 'Fleksibilitas', 'Kardiovaskular / aerobik', 'Keseimbangan', 'Koordinasi', 'c'],
            ['Lintasan lari dalam atletik standar memiliki panjang...', '200 meter', '300 meter', '400 meter', '500 meter', '600 meter', 'c'],
            ['Olahraga bela diri yang berasal dari Jepang dan menggunakan teknik lemparan (kuncian) adalah...', 'Karate', 'Taekwondo', 'Kung Fu', 'Judo', 'Aikido', 'd'],
            ['Pertolongan Pertama Pada Kecelakaan (P3K) dilakukan dengan tujuan...', 'Menggantikan tugas dokter', 'Menyembuhkan penyakit kronis', 'Memberikan pertolongan segera untuk mencegah kondisi memburuk sebelum bantuan medis tiba', 'Menghilangkan rasa sakit sepenuhnya', 'Merawat korban hingga benar-benar sembuh', 'c'],
            ['Dalam renang, gaya yang menggunakan gerakan kaki seperti katak adalah gaya...', 'Bebas (crawl)', 'Punggung', 'Kupu-kupu (butterfly)', 'Dada (breaststroke)', 'Samping', 'd'],
            ['Indeks Massa Tubuh (IMT/BMI) dihitung dengan rumus...', 'Berat badan (kg) ÷ Tinggi badan (cm)', 'Tinggi badan (m)² ÷ Berat badan (kg)', 'Berat badan (kg) ÷ Tinggi badan (m)²', 'Berat badan (kg) × Tinggi badan (m)', 'Tinggi badan (m) × Berat badan (kg)²', 'c'],
            ['Olahraga yang menggunakan lapangan berbentuk persegi dengan ukuran 9 × 18 meter per tim adalah...', 'Basket', 'Sepak bola', 'Bulu Tangkis', 'Voli', 'Tennis', 'd'],
            ['Cedera otot akibat peregangan atau robekan jaringan otot disebut...', 'Dislokasi', 'Fraktur', 'Strain (cedera otot/tendon)', 'Sprain (cedera ligamen)', 'Tendinitis', 'c'],
            ['Dalam olahraga atletik, teknik lari jarak pendek menggunakan start...', 'Start berdiri', 'Start melayang', 'Start jongkok dengan blok start', 'Start mundur', 'Start samping', 'c'],
            ['Tujuan utama pemanasan (warming-up) sebelum olahraga adalah...', 'Menurunkan berat badan', 'Mempersiapkan otot dan sendi, meningkatkan suhu tubuh, serta mencegah cedera', 'Meningkatkan massa otot', 'Menghemat energi selama olahraga', 'Mempercepat proses pemulihan', 'b'],
            ['Senam lantai termasuk dalam cabang olahraga...', 'Atletik', 'Akuatik (renang)', 'Senam artistik / gymnastic', 'Olahraga bela diri', 'Permainan bola besar', 'c'],
            ['Olahraga yang menggunakan raket dan shuttlecock disebut...', 'Tennis', 'Squash', 'Badminton (Bulu Tangkis)', 'Pingpong (Tenis Meja)', 'Padel', 'c'],
            ['Penyakit tidak menular yang berhubungan dengan gaya hidup sedentari (kurang gerak) adalah...', 'Flu', 'Malaria', 'Diabetes mellitus tipe 2 dan penyakit jantung', 'Demam berdarah', 'Typhoid', 'c'],
            ['Sistem energi yang digunakan untuk aktivitas olahraga dengan intensitas sangat tinggi dan durasi singkat (< 10 detik) adalah...', 'Sistem aerobik', 'Sistem glikolitik', 'Sistem Fosfagen (ATP-PC)', 'Sistem oksidatif lemak', 'Sistem glikolisis laktat aerobik', 'c'],
            ['Kepanjangan dari FIFA adalah...', 'Federation Internationale de Football Association', 'Federation Internationale de Football Athletics', 'Federal Institute of Football Association', 'Federation Internationale de Futsal Association', 'Federal International Football Arena', 'a'],
            ['Gerakan meluncur di atas air menggunakan papan selancar disebut...', 'Scuba diving', 'Snorkeling', 'Surfing', 'Waterski', 'Parasailing', 'c'],
            ['Teknik memukul bola dengan keras ke bawah dari atas net dalam bola voli disebut...', 'Servis', 'Set', 'Dig', 'Smash / Spike', 'Block', 'd'],
            ['Komponen kebugaran jasmani yang berkaitan dengan kemampuan otot mengangkat beban maksimal disebut...', 'Daya tahan kardiovaskular', 'Fleksibilitas', 'Kekuatan otot (strength)', 'Kelincahan (agility)', 'Koordinasi', 'c'],
            ['Olahraga yang dipertandingkan pertama kali pada Olimpiade Kuno di Yunani adalah...', 'Renang', 'Lompat jauh', 'Lari (sprint)', 'Gulat', 'Balap kereta kuda', 'c'],
            ['Waktu istirahat yang dianjurkan antar sesi latihan berat adalah...', '24 jam', '12 jam', '48−72 jam untuk kelompok otot yang sama', '6 jam', '1 jam', 'c'],
            ['Senam irama atau senam ritmik identik dengan penggunaan...', 'Matras dan palang sejajar', 'Bola, pita, simpai, gada, dan tali', 'Barbel dan dumbell', 'Kuda-kuda dan trampolin', 'Raket dan net', 'b'],
            ['Kondisi kesehatan yang ditandai dengan tekanan darah di atas 140/90 mmHg disebut...', 'Hipotensi', 'Hipertensi', 'Aritmia', 'Anemia', 'Takikardia', 'b'],
            ['Pada start lari jarak menengah, posisi awal atlet menggunakan...', 'Start jongkok dengan blok', 'Start melayang sambil berlari', 'Start berdiri', 'Start tengkurap', 'Start duduk', 'c'],
            ['Olahraga bela diri Taekwondo berasal dari negara...', 'Jepang', 'China', 'Korea', 'Brazil', 'Thailand', 'c'],
            ['Fungsi pendinginan (cooling-down) setelah olahraga adalah...', 'Meningkatkan intensitas latihan', 'Menambah massa otot', 'Membantu tubuh kembali ke kondisi normal dan mengurangi nyeri otot', 'Membakar lebih banyak kalori', 'Meningkatkan kecepatan', 'c'],
            ['Panjang kolam renang standar olimpik adalah...', '25 meter', '50 meter', '75 meter', '100 meter', '20 meter', 'b'],
            ['Olahraga yang termasuk dalam cabang senam yang menggunakan aparatus (alat) disebut senam...', 'Aerobik', 'Ritmik', 'Akrobatik', 'Artistik', 'Trampolin', 'd'],
            ['Lompat harimau adalah salah satu gerakan dalam senam yang termasuk...', 'Senam ritmik', 'Senam aerobik', 'Senam lantai', 'Senam alat', 'Senam kesegaran jasmani', 'c'],
            ['Narkoba sangat berbahaya bagi kesehatan karena...', 'Membuat tubuh lebih kuat dan sehat', 'Menyebabkan ketergantungan, merusak organ vital, dan mengganggu kesehatan jiwa', 'Meningkatkan konsentrasi belajar', 'Mengurangi rasa nyeri olahraga secara permanen', 'Mempercepat pertumbuhan otot', 'b'],
            ['Jumlah pemain dalam satu tim sepak bola yang ada di lapangan adalah...', '9 orang', '10 orang', '11 orang', '12 orang', '13 orang', 'c'],
            ['Latihan interval training (interval pendek-panjang) bertujuan untuk...', 'Meningkatkan fleksibilitas', 'Melatih keseimbangan', 'Meningkatkan kapasitas aerobik dan anaerobik sekaligus', 'Mengurangi berat badan saja', 'Memperkuat tulang', 'c'],
            ['Gaya renang tercepat adalah...', 'Gaya dada', 'Gaya punggung', 'Gaya bebas (crawl)', 'Gaya kupu-kupu', 'Gaya samping', 'c'],
            ['Istilah "ace" dalam olahraga tenis dan bulu tangkis berarti...', 'Pukulan keras yang sulit dikembalikan', 'Servis yang langsung memenangkan poin karena tidak bisa dikembalikan', 'Servis yang mengenai net', 'Pukulan smash yang berhasil', 'Pukulan lob yang sempurna', 'b'],
            ['Prinsip "overload" dalam latihan fisik berarti...', 'Berlatih dengan beban yang lebih ringan dari kemampuan', 'Beristirahat lebih lama dari biasanya', 'Memberikan beban latihan yang sedikit melebihi kemampuan saat ini agar ada kemajuan', 'Berlatih sebanyak mungkin tanpa istirahat', 'Mengulangi gerakan yang sama berulang kali', 'c'],
            ['Dalam olahraga pencak silat, tingkatan sabuk/sertifikasi ditunjukkan oleh...', 'Warna baju', 'Warna sabuk', 'Jumlah medali', 'Tinggi badan atlet', 'Usia atlet', 'b'],
            ['Manfaat olahraga rutin bagi kesehatan mental antara lain...', 'Meningkatkan risiko depresi', 'Menyebabkan kelelahan kronis', 'Mengurangi stres, meningkatkan mood, dan mencegah depresi', 'Mengganggu pola tidur', 'Mengurangi konsentrasi', 'c'],
            ['Istilah "libero" dalam bola voli adalah...', 'Pemain penyerang utama', 'Pemain yang bertugas sebagai pengumpan', 'Pemain bertahan khusus yang boleh masuk-keluar bebas dari posisi belakang', 'Pemain yang berdiri di dekat net', 'Kapten tim', 'c'],
        ];
        foreach ($pjok_questions as $q) {
            $bank_pjok[] = Question::create([
                'subject' => 'Pendidikan Jasmani, Olahraga, dan Kesehatan',
                'question_text' => $q[0], 'option_a' => $q[1], 'option_b' => $q[2],
                'option_c' => $q[3], 'option_d' => $q[4], 'option_e' => $q[5],
                'correct_option' => $q[6],
            ])->id;
        }

        // =====================================================================
        // MAPEL 11: Seni dan Budaya (50 Soal Unik)
        // =====================================================================
        $bank_senibudaya = [];
        $senibudaya_questions = [
            ['Unsur seni rupa yang paling dasar dan berupa goresan di atas media adalah...', 'Bidang', 'Warna', 'Garis', 'Tekstur', 'Ruang', 'c'],
            ['Tarian yang berasal dari Bali dan biasanya dibawakan oleh puluhan penari pria dengan seruan "Cak" disebut...', 'Tari Kecak', 'Tari Pendet', 'Tari Legong', 'Tari Barong', 'Tari Baris', 'a'],
            ['Teknik melukis dengan cat minyak di atas kanvas dikenal dengan istilah...', 'Aquarel', 'Fresco', 'Cat Air', 'Oil painting', 'Batik', 'd'],
            ['Alat musik yang cara memainkannya dipetik adalah...', 'Gitar', 'Biola', 'Drum', 'Terompet', 'Seruling', 'a'],
            ['Gamelan merupakan ansambel musik tradisional yang berasal dari...', 'Sumatra', 'Kalimantan', 'Jawa dan Bali', 'Sulawesi', 'Papua', 'c'],
            ['Prinsip seni rupa yang berkaitan dengan kesebandingan antar bagian karya seni disebut...', 'Irama', 'Keseimbangan (balance)', 'Proporsi', 'Kesatuan', 'Kontras', 'c'],
            ['Tari Saman yang terkenal dan masuk dalam daftar UNESCO berasal dari...', 'Jawa Barat', 'Sumatra Selatan', 'Aceh', 'Nusa Tenggara Timur', 'Kalimantan', 'c'],
            ['Teknik pembuatan batik dengan menggunakan lilin malam sebagai perintang warna disebut teknik...', 'Cap', 'Tulis', 'Printing', 'Jumputan', 'Celup', 'b'],
            ['Karya seni tiga dimensi yang dibuat dengan cara dipahat atau diukir dari bahan keras disebut...', 'Lukisan', 'Gambar', 'Patung / Skulptur', 'Keramik', 'Mozaik', 'c'],
            ['Not balok dalam musik tertulis di atas lima garis yang disebut...', 'Kunci', 'Birama', 'Paranada (staff)', 'Tanda tempo', 'Tanda birama', 'c'],
            ['Kain tenun ikat khas Nusa Tenggara Timur yang memiliki motif dan makna budaya tinggi adalah...', 'Batik Parang', 'Songket Palembang', 'Tenun Ikat NTT (Sumba/Flores)', 'Ulos Batak', 'Lurik Jawa', 'c'],
            ['Tempo "Allegro" dalam musik berarti...', 'Sangat lambat', 'Lambat', 'Sedang', 'Cepat', 'Sangat cepat', 'd'],
            ['Seni pertunjukan tradisional Jawa yang menampilkan cerita pewayangan oleh seorang dalang disebut...', 'Ludruk', 'Ketoprak', 'Wayang Kulit', 'Randai', 'Lenong', 'c'],
            ['Tangga nada yang terdiri dari tujuh nada dengan jarak tertentu disebut tangga nada...', 'Pentatonik', 'Diatonik', 'Kromatis', 'Harmonik', 'Blues', 'b'],
            ['Keramik adalah karya seni yang dibuat dari bahan...', 'Logam', 'Kayu', 'Tanah liat yang dibakar', 'Kaca', 'Plastik daur ulang', 'c'],
            ['Alat musik tradisional Jawa yang terbuat dari bambu dan cara memainkannya ditiup adalah...', 'Kendang', 'Gender', 'Gambang', 'Suling', 'Bonang', 'd'],
            ['Warna primer dalam seni rupa adalah...', 'Merah, Kuning, Biru', 'Merah, Putih, Hitam', 'Oranye, Hijau, Ungu', 'Merah, Hijau, Biru', 'Kuning, Putih, Hitam', 'a'],
            ['Tari Jaipong berasal dari daerah...', 'Jawa Tengah', 'Jawa Timur', 'Jawa Barat', 'Bali', 'Yogyakarta', 'c'],
            ['Unsur instrinsik cerpen yang berkaitan dengan gambaran tempat, waktu, dan suasana cerita adalah...', 'Tema', 'Plot/Alur', 'Latar (setting)', 'Tokoh', 'Sudut pandang', 'c'],
            ['Teknik melukis dengan menyapukan cat encer sehingga hasilnya transparan disebut teknik...', 'Plakat', 'Opaque', 'Aquarel (cat air tipis)', 'Impasto', 'Grattage', 'c'],
            ['Gedung kesenian tempat pertunjukan opera, ballet, atau konser musik disebut...', 'Studio', 'Galeri seni', 'Teater / Gedung opera (Auditorium)', 'Museum', 'Sanggar', 'c'],
            ['Musik yang menggabungkan unsur-unsur dari berbagai genre/tradisi budaya disebut musik...', 'Jazz', 'Klasik', 'Fusion / World music', 'Rock', 'Pop', 'c'],
            ['Alat musik kendang termasuk dalam kelompok alat musik...', 'Chordophone (senar)', 'Aerophone (tiup)', 'Idiophone (dipukul sendiri)', 'Membranophone (membran/kulit)', 'Electrophone (elektronik)', 'd'],
            ['Pelukis Indonesia yang terkenal dengan karya-karya bertema kehidupan rakyat Bali dan dijuluki "Maestro" adalah...', 'Raden Saleh', 'Affandi', 'Basuki Abdullah', 'I Nyoman Masriadi', 'Dullah', 'b'],
            ['Teknik pembuatan kain dengan cara merangkai benang secara vertikal dan horizontal disebut...', 'Menjahit', 'Menyulam', 'Menenun', 'Merajut', 'Mengepang', 'c'],
            ['Tari yang ditampilkan oleh dua penari (berpasangan) disebut tari...', 'Tunggal (solo)', 'Duet (berpasangan)', 'Kelompok', 'Massal', 'Dramatari', 'b'],
            ['Kaligrafi adalah seni menulis indah yang paling banyak berkembang dalam budaya...', 'Hindu', 'Buddha', 'Melayu Islam', 'Kristen', 'Animisme', 'c'],
            ['Ansambel musik yang terdiri dari berbagai alat musik tiup, gesek, dan pukul dalam jumlah besar disebut...', 'Band', 'Orkestra', 'Kuartet', 'Duet', 'Trio', 'b'],
            ['Dalam teater, istilah "blocking" merujuk pada...', 'Pembagian naskah kepada pemain', 'Pengaturan posisi dan perpindahan aktor di atas panggung', 'Pemilihan kostum pertunjukan', 'Penyusunan jadwal latihan', 'Teknik pengucapan dialog', 'b'],
            ['Relief adalah karya seni rupa tiga dimensi yang...', 'Berdiri bebas dan dapat dilihat dari semua sisi', 'Hanya dapat dilihat dari satu sisi dan menempel pada permukaan datar', 'Dibuat di atas kanvas', 'Bergerak mengikuti angin', 'Dibuat dari logam cair', 'b'],
            ['Batik yang dibuat dengan menggunakan alat cap (stempel bermotif) disebut batik...', 'Tulis', 'Lukis', 'Cap', 'Jumputan', 'Print', 'c'],
            ['Tanda # (kres/sharp) dalam notasi musik berarti nada tersebut dinaikkan sebesar...', '1 nada penuh', 'Setengah nada', '2 nada penuh', 'Seperempat nada', 'Tidak ada perubahan', 'b'],
            ['Raden Saleh dikenal sebagai pelukis Indonesia pertama yang berkarya di gaya...', 'Batik abstrak', 'Wayang kulit', 'Romantisme Eropa', 'Seni kontemporer digital', 'Realisme sosialis', 'c'],
            ['Dalam musik, tanda dinamika "f" (forte) berarti...', 'Lembut', 'Sangat lembut', 'Keras', 'Sangat keras', 'Sedang', 'c'],
            ['Seni instalasi adalah karya seni yang...', 'Digantung di dinding galeri saja', 'Diciptakan untuk ruang tertentu dan mengajak penonton berinteraksi dengan lingkungan sekitarnya', 'Selalu bersifat permanen', 'Hanya berupa patung besar', 'Dibuat menggunakan teknologi digital semata', 'b'],
            ['Tarian tradisional yang berfungsi sebagai ungkapan rasa syukur dalam panen di Jawa disebut...', 'Tari Topeng', 'Tari Gambyong', 'Tari Reog', 'Tari Bedhaya', 'Tari Serimpi', 'b'],
            ['Musik yang berkembang dari tradisi budaya Afrika-Amerika dan terkenal dengan improvisasi serta ritme "swing" adalah...', 'Klasik', 'Pop', 'Rock', 'Jazz', 'Country', 'd'],
            ['Unsur seni rupa yang memberikan kesan berat atau ringan, kasar atau halus disebut...', 'Garis', 'Warna', 'Tekstur', 'Bentuk', 'Ruang', 'c'],
            ['Cerita rakyat yang disampaikan secara turun-temurun secara lisan disebut...', 'Novel', 'Puisi epik', 'Folklor / dongeng tradisional', 'Biografi', 'Laporan', 'c'],
            ['Makna filosofis dari motif batik "Parang Rusak" adalah...', 'Keberanian dan semangat juang seperti ombak laut yang tak berhenti', 'Kesuburan dan kemakmuran', 'Kesucian dan kesetiaan', 'Kematian dan kesedihan', 'Persatuan dan kekeluargaan', 'a'],
            ['Dalam seni teater, "monolog" berarti...', 'Percakapan dua orang di atas panggung', 'Pertunjukan tanpa dialog', 'Satu orang berbicara atau menampilkan cerita sendiri', 'Nyanyian dalam pertunjukan', 'Gerak tubuh tanpa kata', 'c'],
            ['Karya seni rupa yang dibuat dengan teknik mosaik menggunakan bahan...', 'Cat minyak di atas kanvas', 'Potongan-potongan kecil bahan (keramik, kaca, batu) yang disusun membentuk gambar', 'Tanah liat yang diukir', 'Benang yang dirangkai', 'Lilin dan pigmen warna', 'b'],
            ['Perbedaan antara seni tradisional dan seni kontemporer terletak pada...', 'Seni tradisional selalu menggunakan warna cerah', 'Seni kontemporer lebih mahal harganya', 'Seni tradisional terikat pada nilai dan pakem budaya setempat, sedangkan kontemporer lebih bebas dan eksperimental', 'Seni tradisional hanya ada di museum', 'Seni kontemporer selalu abstrak', 'c'],
            ['Alat musik piano termasuk dalam kelompok...', 'String instrument (senar)', 'Wind instrument (tiup)', 'Keyboard instrument dan Percussion', 'Brass instrument (logam)', 'Electronic instrument', 'c'],
            ['Teknik menggambar dengan titik-titik kecil untuk membentuk gambar disebut teknik...', 'Arsir', 'Pointilis (stippling)', 'Dusel', 'Blok', 'Gosok', 'b'],
            ['Lagu Nasional Indonesia "Indonesia Raya" diciptakan oleh...', 'W.R. Supratman', 'H. Mutahar', 'Ismail Marzuki', 'Ibu Sud', 'Cornel Simanjuntak', 'a'],
            ['Drama musikal (musical theater) adalah bentuk pertunjukan yang menggabungkan...', 'Hanya tari dan musik', 'Hanya lagu dan dialog', 'Akting, nyanyian, tari, dan musik secara terpadu', 'Hanya akting dan tari', 'Hanya dekorasi panggung yang rumit', 'c'],
            ['Seni grafis (printmaking) adalah teknik seni rupa yang menghasilkan karya dengan cara...', 'Melukis langsung di atas kanvas', 'Mencetak gambar dari acuan (klise) ke kertas atau kain', 'Mengukir kayu menjadi patung', 'Menyusun potongan-potongan gambar', 'Memotret objek dengan kamera', 'b'],
            ['Ornamen atau hiasan yang bersumber dari alam dan distilisasi (digayakan) dalam seni rupa disebut motif...', 'Geometris', 'Figuratif', 'Abstrak', 'Flora / fauna (naturalis yang distilisasi)', 'Simbolik', 'd'],
            ['Fungsi sosial seni dalam masyarakat antara lain adalah...', 'Hanya untuk kesenangan pribadi seniman', 'Sebagai media komunikasi, ekspresi budaya, dan pemersatu masyarakat', 'Untuk mencari keuntungan finansial semata', 'Hanya untuk ritual keagamaan', 'Untuk membedakan kelas sosial masyarakat', 'b'],
        ];
        foreach ($senibudaya_questions as $q) {
            $bank_senibudaya[] = Question::create([
                'subject' => 'Seni dan Budaya',
                'question_text' => $q[0], 'option_a' => $q[1], 'option_b' => $q[2],
                'option_c' => $q[3], 'option_d' => $q[4], 'option_e' => $q[5],
                'correct_option' => $q[6],
            ])->id;
        }

        // =====================================================================
        // 2. CREATE EXAMS — Satu ujian per mapel, 20 soal diambil dari bank
        // =====================================================================
        $exam_data = [
            ['Ujian Pendidikan Agama dan Budi Pekerti', 'Pendidikan Agama dan Budi Pekerti', $bank_agama],
            ['Ujian Pendidikan Pancasila', 'Pendidikan Pancasila', $bank_pancasila],
            ['Ujian Bahasa Indonesia', 'Bahasa Indonesia', $bank_bindo],
            ['Ujian Bahasa Inggris', 'Bahasa Inggris', $bank_inggris],
            ['Ujian Matematika Umum', 'Matematika Umum', $bank_mtk],
            ['Ujian Sejarah Indonesia', 'Sejarah', $bank_sejarah],
            ['Ujian IPA Terintegrasi', 'IPA Terintegrasi', $bank_ipa],
            ['Ujian IPS Terintegrasi', 'IPS Terintegrasi', $bank_ips],
            ['Ujian Informatika', 'Informatika', $bank_tik],
            ['Ujian PJOK', 'Pendidikan Jasmani, Olahraga, dan Kesehatan', $bank_pjok],
            ['Ujian Seni dan Budaya', 'Seni dan Budaya', $bank_senibudaya],
        ];

        foreach ($exam_data as $ed) {
            $exam = Exam::create([
                'title' => $ed[0],
                'subject' => $ed[1],
                'duration_minutes' => 60,
                'is_active' => true,
                'created_by' => $admin->id,
            ]);
            $exam->questions()->attach($ed[2]);
        }
    }
}