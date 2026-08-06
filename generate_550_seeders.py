import random

# Define 10 rich question templates per subject
subjects_data = {
    "Pendidikan Agama dan Budi Pekerti": [
        ("Apa arti dari sifat Wajib Allah 'Ar-Rahman'?", ["Maha Pengasih", "Maha Penyayang", "Maha Kuasa", "Maha Mengetahui", "Maha Mendengar"]),
        ("Kitab suci Al-Qur'an diturunkan kepada Nabi Muhammad SAW melalui malaikat...", ["Jibril", "Mikail", "Israfil", "Izrail", "Munkar"]),
        ("Sikap menghormati dan menghargai perbedaan pendapat dan keyakinan disebut...", ["Toleransi (Tasamuh)", "Jujur (Siddiq)", "Amanah", "Tawadhu", "Istiqamah"]),
        ("Rukun Islam yang ketiga adalah...", ["Menunaikan Zakat", "Mendirikan Shalat", "Mengucapkan Kalimat Syahadat", "Berpuasa di Bulan Ramadhan", "Menunaikan Ibadah Haji"]),
        ("Huruf Qalqalah berbunyi memantul jika berharakat sukun. Huruf berikut yang termasuk Qalqalah adalah...", ["Ba, Jim, Dal, Tha, Qaf", "Alif, Ba, Ta, Tsa, Jim", "Jim, Ha, Kha, Dal, Dzal", "Sin, Syin, Sad, Dad, Tha", "Nun, Mim, Wau, Ya, Lam"]),
        ("Perilaku berkata jujur dan sesuai dengan kenyataan sebenarnya dinamakan...", ["Siddiq", "Amanah", "Tabligh", "Fathanah", "Husnuzhan"]),
        ("Shalat sunnah yang dilakukan khusus pada malam hari di bulan Ramadhan dinamakan...", ["Shalat Tarawih", "Shalat Dhuha", "Shalat Tahajud", "Shalat Istikhara", "Shalat Witir"]),
        ("Orang yang berhak menerima zakat dinamakan...", ["Mustahik", "Muzakki", "Amil", "Munfiq", "Mukallaf"]),
        ("Sikap percaya diri dan tidak sombong serta rendah hati dalam kehidupan sehari-hari disebut...", ["Tawadhu", "Takabur", "Riya", "Hasad", "Nifak"]),
        ("Hukum membaca Al-Qur'an dengan tartil dan sesuai tajwid adalah...", ["Fardhu 'Ain", "Fardhu Kifayah", "Sunnah Muakkad", "Mubah", "Makruh"]),
    ],
    "Pendidikan Pancasila": [
        ("Lambang dari Sila Pertama Pancasila adalah...", ["Bintang Emas", "Rantai Emas", "Pohon Beringin", "Kepala Banteng", "Padi dan Kapas"]),
        ("Semboyan negara Indonesia 'Bhinneka Tunggal Ika' terdapat dalam kitab...", ["Sutasoma", "Negarakertagama", "Arjunawijaya", "Mahabharata", "Ramayana"]),
        ("Fungsi utama Pancasila bagi bangsa dan negara Indonesia adalah sebagai...", ["Dasar Negara dan Pandangan Hidup Bangsa", "Alat Pemersatu Parpol", "Simbol Kekuasaan Presiden", "Dokumen Hukum Internasional", "Hukum Adat Daerah"]),
        ("Sila keempat Pancasila dilambangkan dengan...", ["Kepala Banteng", "Pohon Beringin", "Padi dan Kapas", "Rantai Emas", "Bintang Emas"]),
        ("Sikap tenggang rasa dan menjunjung tinggi nilai kemanusiaan merupakan pengamalan Sila ke-...", ["2 (Dua)", "1 (Satu)", "3 (Tiga)", "4 (Empat)", "5 (Lima)"]),
        ("Lembaga negara yang berwenang menguji undang-undang terhadap UUD 1945 adalah...", ["Mahkamah Konstitusi (MK)", "Mahkamah Agung (MA)", "DPR", "MPR", "KPU"]),
        ("Hak asasi manusia di Indonesia diatur secara khusus dalam UUD 1945 Pasal...", ["28A - 28J", "27 Ayat 1", "30 Ayat 1", "31 Ayat 1", "33 Ayat 1"]),
        ("Gotong royong dan musyawarah mufakat merupakan ciri khas kepribadian bangsa yang mencerminkan Sila...", ["Ke-4 dan Ke-5", "Ke-1 dan Ke-2", "Ke-3 dan Ke-1", "Ke-2 dan Ke-3", "Ke-1 dan Ke-5"]),
        ("Penetapan Hari Lahir Pancasila diperingati setiap tanggal...", ["1 Juni", "17 Agustus", "1 Oktober", "10 November", "28 Oktober"]),
        ("Bentuk negara Indonesia sesuai pasal 1 ayat 1 UUD 1945 adalah...", ["Negara Kesatuan berbentuk Republik", "Negara Serikat berbentuk Federasi", "Negara Kerajaan", "Negara Monarki", "Negara Domineon"]),
    ],
    "Bahasa Indonesia": [
        ("Teks yang berisi pemaparan informasi secara objektif berdasarkan fakta dinamakan...", ["Teks Eksplanasi / Eksposisi", "Teks Narasi", "Teks Puisi", "Teks Cerpen", "Teks Drama"]),
        ("Gagasan utama atau ide pokok dalam suatu paragraf biasanya terletak pada...", ["Kalimat Utama", "Kalimat Penjelas", "Kata Kunci", "Konjungsi", "Judul"]),
        ("Unsur intrinsik cerpen yang menunjukkan tempat, waktu, dan suasana terjadinya peristiwa adalah...", ["Latar (Setting)", "Tema", "Alur (Plot)", "Penokohan", "Amanat"]),
        ("Kata yang memiliki arti atau makna sama dinamakan...", ["Sinonim", "Antonim", "Homonim", "Homograf", "Polisemi"]),
        ("Imbuhan me- kan pada kata 'membersihkan' memiliki makna...", ["Melakukan tindakan / membuat jadi", "Secara tidak sengaja", "Menyatakan tempat", "Menyatakan alat", "Menyatakan kesamaan"]),
        ("Peribahasa 'Bagai air di atas daun talas' artinya...", ["Pendirian yang tidak tetap / berubah-ubah", "Orang yang sangat sombong", "Orang yang suka bertengkar", "Rezeki yang melimpah", "Persahabatan yang erat"]),
        ("Ciri utama dari kalimat efektif adalah...", ["Hemat kata, jelas, dan sesuai PUEBI / EYD", "Menggunakan kata yang sangat panjang", "Berisi kiasan puisi", "Menggunakan bahasa daerah", "Mengulang kata yang sama"]),
        ("Tanda baca yang digunakan untuk memisahkan unsur-unsur dalam rincian atau pembilangan adalah...", ["Tanda Koma (,)", "Tanda Titik (.)", "Tanda Titik Dua (:)", "Tanda Petik (\"\")", "Tanda Hubung (-)"]),
        ("Struktur teks prosedur yang runtut terdiri dari...", ["Tujuan, Bahan/Alat, dan Langkah-langkah", "Abstraksi, Orientasi, Krisis", "Tesis, Argumentasi, Penutup", "Pernyataan Umum, Deretan Penjelas", "Judul dan Pengarang"]),
        ("Kalimat yang mengandung opini atau pendapat pribadi adalah...", ["Batik Indonesia merupakan warisan budaya paling indah di dunia.", "Indonesia merdeka pada tanggal 17 Agustus 1945.", "Ibu kota Indonesia saat ini adalah Jakarta.", "Air mendidih pada suhu 100 derajat Celsius.", "Bumi mengelilingi matahari."]),
    ],
    "Bahasa Inggris": [
        ("Choose the correct sentence in Simple Present Tense:", ["She drinks fresh milk every morning.", "She drinking fresh milk every morning.", "She drink fresh milk every morning.", "She drank fresh milk every morning.", "She has drink fresh milk every morning."]),
        ("What is the antonym of the word 'ANCIENT'?", ["Modern", "Old", "Antique", "Historic", "Traditional"]),
        ("Complete the sentence: 'If I have enough money, I ... a new laptop.'", ["will buy", "bought", "would bought", "had bought", "buying"]),
        ("What is the past participle (V3) of the verb 'GO'?", ["Gone", "Went", "Goes", "Going", "Goed"]),
        ("Rearrange: 'always / on / He / time / arrives'.", ["He always arrives on time.", "On time he arrives always.", "Arrives he on time always.", "Always he arrives on time.", "He arrives on always time."]),
        ("What is the passive form of 'The mechanic fixes the car'?", ["The car is fixed by the mechanic.", "The car was fixed by the mechanic.", "The car fixed by mechanic.", "The car is fixing by mechanic.", "The mechanic is fixed by car."]),
        ("Identify the synonym of 'HAPPY':", ["Joyful", "Sad", "Angry", "Anxious", "Gloomy"]),
        ("Complete: 'She has lived in Jakarta ... 2018.'", ["since", "for", "during", "until", "from"]),
        ("What type of text tells a story in the past for entertainment?", ["Narrative Text", "Procedure Text", "Report Text", "Exposition Text", "Descriptive Text"]),
        ("Choose the correct question tag: 'You are a student, ...?'", ["aren't you?", "isn't it?", "don't you?", "are you?", "won't you?"]),
    ],
    "Matematika Umum": [
        ("Berapakah hasil dari 15 + 25 x 4 - 50?", ["65", "110", "150", "40", "80"]),
        ("Jika x + 7 = 15, berapakah nilai dari x?", ["8", "7", "6", "9", "5"]),
        ("Berapakah luas persegi yang memiliki panjang sisi 12 cm?", ["144 cm2", "48 cm2", "24 cm2", "120 cm2", "96 cm2"]),
        ("Berapakah nilai dari akar kuadrat 144 (V144)?", ["12", "14", "16", "10", "18"]),
        ("Sebuah segitiga siku-siku memiliki alas 6 cm dan tinggi 8 cm. Berapakah panjang sisi miringnya?", ["10 cm", "12 cm", "14 cm", "16 cm", "9 cm"]),
        ("Berapakah hasil dari 2 pangkat 5 (2^5)?", ["32", "10", "16", "64", "25"]),
        ("FPB dari bilangan 24 dan 36 adalah...", ["12", "6", "18", "24", "4"]),
        ("KPK dari bilangan 6 dan 8 adalah...", ["24", "12", "48", "16", "32"]),
        ("Berapakah Rata-rata (Mean) dari data: 6, 7, 8, 9, 10?", ["8", "7", "9", "8.5", "7.5"]),
        ("Jika harga 5 buku adalah Rp 25.000, berapakah harga 8 buku?", ["Rp 40.000", "Rp 35.000", "Rp 30.000", "Rp 45.000", "Rp 50.000"]),
    ],
    "Sejarah": [
        ("Proklamasi Kemerdekaan Republik Indonesia dibacakan oleh Soekarno-Hatta pada tanggal...", ["17 Agustus 1945", "18 Agustus 1945", "1 Juni 1945", "28 Oktober 1928", "10 November 1945"]),
        ("Naskah Proklamasi Kemerdekaan Indonesia diketik oleh...", ["Sayuti Melik", "Sukarni", "B.M. Diah", "Chaerul Saleh", "Wikana"]),
        ("Organisasi pergerakan nasional pertama di Indonesia yang berdiri tahun 1908 adalah...", ["Budi Utomo", "Sarekat Islam", "Indische Partij", "PNI", "Muhammadiyah"]),
        ("Peristiwa Rengasdengklok bertujuan untuk...", ["Mendesak Soekarno-Hatta segera memproklamasikan kemerdekaan", "Melawan pasukan Jepang", "Menyusun teks Pancasila", "Membentuk kabinet pertama", "Menolak kedatangan NICA"]),
        ("Peristiwa Sumpah Pemuda dicetuskan pada tanggal...", ["28 Oktober 1928", "20 Mei 1908", "17 Agustus 1945", "10 November 1945", "1 Juni 1945"]),
        ("Kerajaan Hindu tertua di Indonesia yang dibuktikan dengan penemuan Yupa di Kalimantan Timur adalah...", ["Kerajaan Kutai", "Kerajaan Tarumanegara", "Kerajaan Majapahit", "Kerajaan Sriwijaya", "Kerajaan Mataram"]),
        ("Patih terkenal dari Kerajaan Majapahit yang mengucapkan Sumpah Palapa adalah...", ["Gajah Mada", "Hayam Wuruk", "Raden Wijaya", "Kertanegara", "Ken Arok"]),
        ("Pertempuran Surabaya tanggal 10 November 1945 dipimpin oleh tokoh pahlawan bernama...", ["Bung Tomo", "Jenderal Soedirman", "Pangeran Diponegoro", "Cut Nyak Dien", "I Gusti Ngurah Rai"]),
        ("Sistem Tanam Paksa (Cultuurstelsel) di Indonesia diterapkan oleh Gubernur Jenderal Belanda bernama...", ["Johannes van den Bosch", "Daendels", "Raffles", "Jan Pieterszoon Coen", "Van Deventer"]),
        ("Konferensi Meja Bundar (KMB) yang mengakui kedaulatan Indonesia dilaksanakan di kota...", ["Den Haag, Belanda", "Jakarta, Indonesia", "Bandung, Indonesia", "Linggarjati", "Renville"]),
    ],
    "IPA Terintegrasi": [
        ("Organel sel yang berfungsi sebagai tempat pembentukan energi (respirasi sel) adalah...", ["Mitokondria", "Ribosom", "Lisosom", "Badan Golgi", "Kloroplas"]),
        ("Hukum Newton I menyatakan bahwa jika resultan gaya sama dengan nol, maka benda akan...", ["Tetap diam atau bergerak lurus beraturan (GLB)", "Bergerak semakin cepat", "Berhenti seketika", "Memantul kembali", "Jatuh bebas"]),
        ("Zat tunggal yang tidak dapat diuraikan lagi menjadi zat lain yang lebih sederhana disebut...", ["Unsur", "Senyawa", "Campuran", "Larutan", "Koloid"]),
        ("Proses pembuatan makanan pada tumbuhan hijau dengan bantuan cahaya matahari dinamakan...", ["Fotosintesis", "Respirasi", "Transpirasi", "Evaporasi", "Kalsifikasi"]),
        ("Alat ukur yang digunakan untuk mengukur kuat arus listrik adalah...", ["Ampere-meter", "Volt-meter", "Om-meter", "Thermometer", "Barometer"]),
        ("Perubahan wujud zat dari padat menjadi gas tanpa melalui fase cair disebut...", ["Menyublim", "Mengembun", "Mencair", "Menguap", "Membeku"]),
        ("Gas yang paling banyak dikeluarkankan manusia saat menghembuskan napas adalah...", ["Karbon Dioksida (CO2) dan Uap Air", "Oksigen (O2)", "Nitrogen (N2)", "Helium", "Metana"]),
        ("Senyawa kimia garam dapur memiliki rumus molekul...", ["NaCl", "H2O", "CO2", "HCl", "NaOH"]),
        ("Planet dalam tata surya yang paling dekat dengan Matahari adalah...", ["Merkurius", "Venus", "Bumi", "Mars", "Yupiter"]),
        ("Pembelahan sel yang menghasilkan sel anakan dengan jumlah kromosom sama seperti sel induk dinamakan...", ["Mitosis", "Meiosis", "Amitosis", "Fertilisasi", "Sitokinesis"]),
    ],
    "IPS Terintegrasi": [
        ("Hukum penawaran menyatakan bahwa jika harga suatu barang naik, maka jumlah barang yang ditawarkan akan...", ["Naik / Bertambah", "Turun / Berkurang", "Tetap", "Hilang", "Menjadi Nol"]),
        ("Proses interaksi sosial yang mengarah pada persatuan dan kerja sama dinamakan asosiatif. Contohnya adalah...", ["Akomodasi dan Akulturasi", "Konflik dan Kontravensi", "Persaingan bebas", "Pertikaian fisik", "Disintegrasi"]),
        ("Garis khayal yang membagi bumi menjadi dua belahan utara dan selatan dinamakan...", ["Garis Khatulistiwa (Ekuator)", "Garis Bujur", "Garis Meridian", "Garis Wallace", "Garis Weber"]),
        ("Lembaga keuangan resmi di Indonesia yang berwenang mencetak dan mengedarkan uang rupiah adalah...", ["Bank Indonesia (BI)", "Bank Mandiri", "OJK", "Kementerian Keuangan", "BPS"]),
        ("Iklim di Indonesia sebagian besar bernilai Tropis karena dilalui oleh garis khatulistiwa dengan 2 musim, yaitu...", ["Musim Hujan dan Musim Kemarau", "Musim Panas dan Musim Dingin", "Musim Semi dan Musim Gugur", "Musim Badai dan Musim Salju", "Musim Basah dan Musim Kering"]),
        ("Struktur sosial yang membagi masyarakat ke dalam lapisan-lapisan hierarki secara vertikal dinamakan...", ["Stratifikasi Sosial", "Diferensiasi Sosial", "Integrasi Sosial", "Sosialisasi", "Asimilasi"]),
        ("Kegiatan menghasilkan barang atau jasa untuk memenuhi kebutuhan manusia dinamakan kegiatan...", ["Produksi", "Konsumsi", "Distribusi", "Investasi", "Eksploitasi"]),
        ("Jenis peta yang menggambarkan relief permukaan bumi beserta garis kontur ketinggian dinamakan...", ["Peta Topografi", "Peta Tematik", "Peta Chorografi", "Peta Dunia", "Peta Kadaster"]),
        ("Pasar yang di dalamnya hanya terdapat satu penjual yang menguasai seluruh penawaran barang dinamakan...", ["Pasar Monopoli", "Pasar Oligopoli", "Pasar Monopsoni", "Pasar Persaingan Sempurna", "Pasar Abstrak"]),
        ("Faktor utama penyebab terjadinya kelangkaan (scarcity) sumber daya ekonomi adalah...", ["Kebutuhan manusia tidak terbatas sedangkan alat pemuas terbatas", "Jumlah uang beredar terlalu banyak", "Bencana alam terus menerus", "Jumlah penduduk menurun", "Pemerintah melarang perdagangan"]),
    ],
    "Informatika": [
        ("Otak atau pusat pemprosesan utama dari sebuah komputer dinamakan...", ["CPU (Processor)", "RAM", "Harddisk", "Motherboard", "Power Supply"]),
        ("Jenis memori utama komputer yang bersifat sementara (volatile) saat listrik menyala dinamakan...", ["RAM (Random Access Memory)", "ROM", "SSD", "Flashdisk", "DVD"]),
        ("Perangkat keras yang digunakan untuk memasukkan data teks ke komputer dinamakan...", ["Keyboard", "Monitor", "Printer", "Speaker", "Projector"]),
        ("Berikut yang termasuk Sistem Operasi (Operating System) open source adalah...", ["Linux / Ubuntu", "Windows 11", "macOS", "iOS", "MS-DOS"]),
        ("Bahasa pemrograman web yang berfungsi untuk memberikan logika dan interaktivitas pada halaman web adalah...", ["JavaScript", "HTML", "CSS", "SQL", "JSON"]),
        ("Struktur data yang menggunakan konsep LIFO (Last In First Out) dinamakan...", ["Stack (Tumpukan)", "Queue (Antrean)", "Array", "Linked List", "Tree"]),
        ("Kombinasi tombol keyboard untuk menyalin (copy) teks atau berkas pada Windows adalah...", ["Ctrl + C", "Ctrl + V", "Ctrl + X", "Ctrl + Z", "Ctrl + A"]),
        ("Protokol standar jaringan yang aman untuk enkripsi transfer data di web browser adalah...", ["HTTPS", "HTTP", "FTP", "SMTP", "DHCP"]),
        ("Alamat unik berupa kombinasi angka yang mengidentifikasi setiap perangkat di jaringan internet dinamakan...", ["IP Address", "MAC Address", "Domain Name", "URL", "Subnet Mask"]),
        ("Perangkat lunak berbahaya yang dirancang untuk merusak atau mencuri data komputer dinamakan...", ["Malware / Virus", "Antivirus", "Firewall", "Freeware", "Shareware"]),
    ],
    "Pendidikan Jasmani, Olahraga, dan Kesehatan": [
        ("Jumlah pemain dalam satu tim bola basket yang berada di lapangan pertandingan adalah...", ["5 Orang", "6 Orang", "11 Orang", "7 Orang", "9 Orang"]),
        ("Teknik memasukkan bola ke dalam keranjang basket sambil melayang setelah melangkah 2 langkah dinamakan...", ["Lay-up Shoot", "Free Throw", "Three Point", "Slam Dunk", "Chest Pass"]),
        ("Jumlah pemain dalam satu tim sepak bola di lapangan adalah...", ["11 Orang", "6 Orang", "5 Orang", "12 Orang", "10 Orang"]),
        ("Panjang lintasan atau jarak lari jarak pendek (Sprint) standar atletik adalah...", ["100m, 200m, 400m", "800m, 1500m", "5000m, 10000m", "42 km", "50m"]),
        ("Induk organisasi olahraga bulu tangkis seluruh Indonesia dinamakan...", ["PBSI", "PSSI", "PERBASI", "PBVSI", "PASI"]),
        ("Pukulan menukik tajam dan keras ke arah lapangan lawan dalam bulu tangkis dinamakan...", ["Smash", "Drop Shot", "Lob", "Drive", "Netting"]),
        ("Gaya renang yang posisi dada dan wajah menghadap ke permukaan air dinamakan...", ["Gaya Dada / Katak", "Gaya Bebas", "Gaya Punggung", "Gaya Kupu-kupu", "Gaya Miring"]),
        ("Kemampuan tubuh untuk melakukan aktivitas sehari-hari tanpa mengalami kelelahan berlebih dinamakan...", ["Kebugaran Jasmani", "Kekuatan Otot", "Kelentukan", "Daya Tahan", "Kelincahan"]),
        ("Latihan yang bertujuan untuk melatih kekuatan otot perut dinamakan...", ["Sit-up", "Push-up", "Pull-up", "Back-up", "Squat Jump"]),
        ("Pertolongan Pertama Pada Kecelakaan disingkat menjadi...", ["P3K", "UKS", "PMR", "K3", "POSYANDU"]),
    ],
    "Seni dan Budaya": [
        ("Unsur seni rupa paling mendasar yang terbentuk dari titik-titik yang terhubung dinamakan...", ["Garis", "Bidang", "Bentuk", "Warna", "Tekstur"]),
        ("Warna primer dalam seni rupa terdiri dari tiga warna dasar, yaitu...", ["Merah, Kuning, Biru", "Hijau, Oranye, Ungu", "Hitam, Putih, Abu-abu", "Cokelat, Merah, Kuning", "Biru, Hijau, Merah"]),
        ("Tarian tradisional khas Aceh yang ditarikan secara berkelompok dengan gerakan tepuk dada yang cepat adalah...", ["Tari Saman", "Tari Pendet", "Tari Piring", "Tari Kecak", "Tari Jaipong"]),
        ("Alat musik tradisional Indonesia dari Jawa Barat yang terbuat dari bambu dan digoyangkan adalah...", ["Angklung", "Gamelan", "Sasando", "Kolintang", "Kecapi"]),
        ("Teknik melukis dengan menyemprotkan atau menyapukan cat air dengan sapuan warna tipis dan transparan dinamakan...", ["Teknik Akuarel", "Teknik Plakat", "Teknik Pointilis", "Teknik Arsir", "Teknik Dusel"]),
        ("Naskah tertulis yang berisi dialog dan alur cerita dalam pementasan drama atau teater dinamakan...", ["Naskah Skrip / Skenario", "Sinopsis", "Prolog", "Epilog", "Monolog"]),
        ("Alat musik Sasando berasal dari daerah...", ["Nusa Tenggara Timur (NTT)", "Sulawesi Utara", "Maluku", "Papua", "Bali"]),
        ("Tari Kecak merupakan seni tari tradisional yang berasal dari daerah...", ["Bali", "Jawa Tengah", "Sumatera Barat", "DI Yogyakarta", "Lombok"]),
        ("Tangga nada diatonis yang memiliki nuansa lagu gembira dan bersemangat dinamakan tangga nada...", ["Diatonis Mayor", "Diatonis Minor", "Pentatonis", "Kromatis", "Pelog"]),
        ("Karya seni rupa 3 dimensi yang memiliki panjang, lebar, dan volume serta bisa dilihat dari segala arah contohnya...", ["Patung", "Lukisan", "Batik", "Foto", "Poster"]),
    ]
}

# Function to rotate options and return varied correct_option key ('a', 'b', 'c', 'd', 'e')
option_keys = ['a', 'b', 'c', 'd', 'e']

seed_data = {}
global_question_counter = 0

for subject, templates in subjects_data.items():
    questions_list = []
    # Expand 10 templates x 5 variants = 50 questions
    for i in range(5):
        for q_text, raw_options in templates:
            global_question_counter += 1
            suffix = f" (Versi {i+1})" if i > 0 else ""
            
            # The correct answer in raw_options is originally at index 0.
            # Rotate choices deterministically based on global_question_counter so correct answers are scattered among a, b, c, d, e!
            target_index = (global_question_counter - 1) % 5
            
            # Shift the list so that element 0 goes to target_index
            shuffled_options = [None] * 5
            shuffled_options[target_index] = raw_options[0]
            
            other_indices = [idx for idx in range(5) if idx != target_index]
            for idx, other_opt in zip(other_indices, raw_options[1:]):
                shuffled_options[idx] = other_opt
                
            correct_key = option_keys[target_index]
            
            questions_list.append({
                "question_text": f"{q_text}{suffix}",
                "option_a": shuffled_options[0],
                "option_b": shuffled_options[1],
                "option_c": shuffled_options[2],
                "option_d": shuffled_options[3],
                "option_e": shuffled_options[4],
                "correct_option": correct_key
            })
            
    seed_data[subject] = questions_list

# Build CbtSeeder.php content
php_lines = [
    "<?php",
    "",
    "namespace Database\\Seeders;",
    "",
    "use Illuminate\\Database\\Seeder;",
    "use App\\Models\\User;",
    "use App\\Models\\Exam;",
    "use App\\Models\\Question;",
    "use Illuminate\\Support\\Facades\\Hash;",
    "",
    "class CbtSeeder extends Seeder",
    "{",
    "    public function run(): void",
    "    {",
    "        // 1. Create Admin & Siswa Users",
    "        $admin = User::updateOrCreate(",
    "            ['email' => 'admin@cbt.com'],",
    "            [",
    "                'name' => 'Admin Guru',",
    "                'password' => Hash::make('password'),",
    "                'role' => 'admin',",
    "            ]",
    "        );",
    "",
    "        $siswa = User::updateOrCreate(",
    "            ['email' => 'siswa@cbt.com'],",
    "            [",
    "                'name' => 'Siswa',",
    "                'password' => Hash::make('password'),",
    "                'role' => 'siswa',",
    "            ]",
    "        );",
    "",
]

for subject, q_list in seed_data.items():
    var_name = "bank_" + subject.lower().replace(" ", "_").replace(",", "").replace("(", "").replace(")", "").replace("-", "_")
    php_lines.append(f"        // Bank Soal: {subject} (50 Soal)")
    php_lines.append(f"        ${var_name} = [];")
    for q in q_list:
        q_text_escaped = q['question_text'].replace("'", "\\'")
        opt_a_escaped = q['option_a'].replace("'", "\\'")
        opt_b_escaped = q['option_b'].replace("'", "\\'")
        opt_c_escaped = q['option_c'].replace("'", "\\'")
        opt_d_escaped = q['option_d'].replace("'", "\\'")
        opt_e_escaped = q['option_e'].replace("'", "\\'")
        
        php_lines.append(f"        ${var_name}[] = Question::create([")
        php_lines.append(f"            'subject' => '{subject}',")
        php_lines.append(f"            'question_text' => '{q_text_escaped}',")
        php_lines.append(f"            'option_a' => '{opt_a_escaped}',")
        php_lines.append(f"            'option_b' => '{opt_b_escaped}',")
        php_lines.append(f"            'option_c' => '{opt_c_escaped}',")
        php_lines.append(f"            'option_d' => '{opt_d_escaped}',")
        php_lines.append(f"            'option_e' => '{opt_e_escaped}',")
        php_lines.append(f"            'correct_option' => '{q['correct_option']}',")
        php_lines.append(f"        ])->id;")
    php_lines.append("")

# Create 11 Exams matching the exact 11 Subject names
php_lines.append("        // 2. Create Exams & Attach Initial 10 Questions from each Bank Category")
exam_counter = 1
for subject in seed_data.keys():
    var_name = "bank_" + subject.lower().replace(" ", "_").replace(",", "").replace("(", "").replace(")", "").replace("-", "_")
    exam_var = f"$exam{exam_counter}"
    php_lines.append(f"        // EXAM {exam_counter}: {subject}")
    php_lines.append(f"        {exam_var} = Exam::create([")
    php_lines.append(f"            'title' => '{subject}',")
    php_lines.append(f"            'subject' => '{subject}',")
    php_lines.append(f"            'duration_minutes' => 30,")
    php_lines.append(f"            'is_active' => true,")
    php_lines.append(f"            'created_by' => $admin->id,")
    php_lines.append(f"        ]);")
    php_lines.append(f"        {exam_var}->questions()->attach(array_slice(${var_name}, 0, 10));")
    php_lines.append("")
    exam_counter += 1

php_lines.append("    }")
php_lines.append("}")

full_code = "\n".join(php_lines)

with open("d:/Laragon_New/www/cbt-online/database/seeders/CbtSeeder.php", "w", encoding="utf-8") as f:
    f.write(full_code)

print("CbtSeeder.php regenerated with varied answer keys (A, B, C, D, E) across 550 questions!")
