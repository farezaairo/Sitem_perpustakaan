-- ---------------------------------------------------------
-- DATABASE: perpustakaan
-- ---------------------------------------------------------

CREATE DATABASE IF NOT EXISTS perpustakaan;
USE perpustakaan;

-- ---------------------------------------------------------
-- TABLE: users
-- ---------------------------------------------------------
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `peran` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
);

INSERT INTO `users` (`id`,`name`,`peran`,`email`,`email_verified_at`,`password`,`remember_token`,`created_at`,`updated_at`) VALUES
(1,'Administrator Perpustakaan','admin','admin@perpus.local',NULL,'$2y$12$8nJK59B6BfAFhX5Ex8CCJ.ZsO1ZN0loLr80uFs/Kt6igYxe5Z3GUC',NULL,'2025-11-13 02:09:44','2025-11-16 18:57:34'),
(2,'Kepala Perpustakaan','kepala_perpustakaan','kepala@perpus.local',NULL,'$2y$12$2zaf3H1Lwoy7jukuhD.8Z.BjxqYgav1vb0jyM/ue1kwV1iNz9BzFe',NULL,'2025-11-13 02:09:44','2025-11-16 18:57:34'),
(3,'Ahmad Fauzi','siswa','ahmad.fauzi@sekolah.com',NULL,'$2y$12$zgnOBGcJ0eMg0XndL1pXKecE27XtKTHNju7yVHtYDxZAlamupxZum',NULL,'2025-11-13 02:09:45','2025-11-16 18:57:34'),
(4,'Siti Nurhaliza','siswa','siti.nur@sekolah.com',NULL,'$2y$12$.faMTXVAEN78zOL7h6poUOtolYd2RDo28yOmXNPitZjryEcSyZ47u',NULL,'2025-11-13 02:09:45','2025-11-16 18:57:35'),
(5,'Budi Santoso','siswa','budi.santoso@sekolah.com',NULL,'$2y$12$jabDLql8CdKyIfU9W.UXZO3FElsLvGpYXFDF2nY3i6vKDa.VPCI2C',NULL,'2025-11-13 02:09:46','2025-11-16 18:57:35'),
(6,'Dewi Lestari','siswa','dewi.lestari@sekolah.com',NULL,'$2y$12$Nuz5pNZw9jVcleFcL6AOlesjPPUw1phe1a.afm4.7Qdbpc.aGCByy',NULL,'2025-11-13 02:09:47','2025-11-16 18:57:35'),
(7,'Rizki Pratama','siswa','rizki.pratama@sekolah.com',NULL,'$2y$12$tsLqoACTtScAsZZL/55hbO.Jt8zHGqT3js6uT2G8oUPpBQcga/Fc6',NULL,'2025-11-13 02:09:47','2025-11-16 18:57:36'),
(8,'Putri Ayu','siswa','putri.ayu@sekolah.com',NULL,'$2y$12$x527n/nuMztr.Ptpirr2g.68s6H43GbTfXnXtTcvnh5RULYl2q3IS',NULL,'2025-11-13 02:09:48','2025-11-16 18:57:36'),
(9,'Test User','siswa','test@example.com','2025-11-13 02:09:48','$2y$12$atKfrEzO26kxxBQZ.PPJ3uiTByIlcM9wllr83pz9HUbTfuZbWqQcO','GKScorYf91','2025-11-13 02:09:49','2025-11-16 18:57:36'),
(10,'Admin','admin','admin@example.com',NULL,'$2y$12$/zipBeLMNlJhRqe2iQOUteZMJRIHcjMJcW9v0g3byOgb4a3myaNuS',NULL,'2025-11-14 08:32:58','2025-11-16 18:57:36'),
(11,'Siswa','siswa','Siswa@example.com',NULL,'$2y$12$HKzmR9nKoGEFvE5dCT4HXOKo3fVWXo/JoxGrkW4899CrhYs5WnK4.',NULL,'2025-11-14 09:21:10','2025-11-16 18:57:37'),
(12,'fareza','siswa','eja@mail.com',NULL,'$2y$12$6RpZoRqBmBQFJZJm5hBQyO1ZrmwwDxuqxyxfwPIZ9MgVBxllx7IrW',NULL,'2025-11-19 13:19:04','2025-11-19 13:19:04');

-- ---------------------------------------------------------
-- TABLE: buku
-- ---------------------------------------------------------
CREATE TABLE `buku` (
  `id_buku` varchar(20) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `penulis` varchar(255) NOT NULL,
  `kategori` varchar(100) NOT NULL,
  `isbn` varchar(50) NOT NULL,
  `stok` int NOT NULL,
  `tersedia` int NOT NULL,
  `tahun_terbit` int NOT NULL,
  `gambar_sampul` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_buku`)
);

INSERT INTO `buku` VALUES
('B001','Percobaan','saya','Fiksi','1234-657',1,1,2025,'https://marketplace.canva.com/EAGc_iorFj8/1/0/1003w/canva-biru-moderen-propsal-bisnis-sampul-buku-3m69Xbg8KwA.jpg','2025-11-19 05:59:53','2025-11-19 06:26:09'),
('B002','Bumi Pertiwi','Pramoedya Ananta Toer','Novel','978-979-461-228-4',8,9,1980,'https://images.unsplash.com/photo-1512820790803-83ca734da794?w=400&h=600&fit=crop','2025-11-13 02:09:39','2025-11-19 05:43:33'),
('B003','Matematika Dasar','Prof. Dr. Sumarno','Pendidikan','978-602-8519-93-9',15,15,2018,'https://images.unsplash.com/photo-1509228468518-180dd4864904?w=400&h=600&fit=crop','2025-11-13 02:09:39','2025-11-29 02:26:39'),
('B004','Sejarah Indonesia Modern','Dr. Anhar Gonggong','Sejarah','978-979-9023-77-2',12,9,2015,'https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=400&h=600&fit=crop','2025-11-13 02:09:39','2025-11-29 02:25:34'),
('B005','Fisika untuk SMA','Drs. Marthen Kanginan','Sains','978-602-427-045-9',20,14,2020,'https://images.unsplash.com/photo-1589998059171-988d887df646?w=400&h=600&fit=crop','2025-11-13 02:09:39','2025-11-19 05:43:35'),
('B006','Bahasa Inggris Terapan','Dra. Siti Nurjanah','Bahasa','978-602-244-789-3',18,13,2019,'https://images.unsplash.com/photo-1516979187457-637abb4f9353?w=400&h=600&fit=crop','2025-11-13 02:09:39','2025-11-29 02:15:59'),
('B007','Kimia Organik','Prof. Dr. Bambang Setiaji','Sains','978-979-769-234-1',10,9,2017,'https://images.unsplash.com/photo-1532012197267-da84d127e765?w=400&h=600&fit=crop','2025-11-13 02:09:39','2025-11-29 02:58:37'),
('BK001','Contoh Buku','Penulis A','Fiksi','123456',10,12,2025,'https://online.visual-paradigm.com/repository/images/718013d5-6254-43de-ac1a-84e37ae90ce7/book-covers-design/simple-modern-furniture-design-book-cover.png',NULL,'2025-11-27 17:33:56');

-- ---------------------------------------------------------
-- TABLE: siswa
-- ---------------------------------------------------------
CREATE TABLE `siswa` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `id_siswa` varchar(20) NOT NULL,
  `nis` varchar(50) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `kelas` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `no_hp` varchar(50) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
);

INSERT INTO `siswa` VALUES
(1,12,'SW006','123456789','fareza','12 Bahasa 1','eja@mail.com','0986758493','eja','$2y$12$6RpZoRqBmBQFJZJm5hBQyO1ZrmwwDxuqxyxfwPIZ9MgVBxllx7IrW','2025-11-16 15:19:45','2025-11-16 19:17:30'),
(2,3,'S001','20210001','Ahmad Fauzi','10 IPA 1','ahmad.fauzi@sekolah.com','081234567890','ahmad.fauzi','$2y$12$3WPLNlbleUS7jT/NKGcrBO3i0s3W2leEAPEllqDYrlWXcXDZ3zZMW','2025-11-13 02:09:40','2025-11-13 02:09:40'),
(3,4,'S002','20210002','Siti Nurhaliza','11 IPS 2','siti.nur@sekolah.com','081234567891','siti.nur','$2y$12$wH65wzVP2/Tw.iM6H6WEKuf6xv.zOJQ4xaqcb9DKYQzUOHQ69lor2','2025-11-13 02:09:40','2025-11-13 02:09:40'),
(4,5,'S003','20210003','Budi Santoso','12 IPA 3','budi.santoso@sekolah.com','081234567892','budi.santoso','$2y$12$rzGvKtEiXdazZi78MFfXwuU1PrhlAfhQJd.5/m/wIOhrdewop1zh6','2025-11-13 02:09:41','2025-11-13 02:09:41'),
(5,6,'S004','20210004','Dewi Lestari','10 IPA 2','dewi.lestari@sekolah.com','081234567893','dewi.lestari','$2y$12$eV.5mxsYKHgx6i1w9WdXzesPrhyvV7jUCddNd4lJ.J7jQPQZ4gPSG','2025-11-13 02:09:42','2025-11-13 02:09:42'),
(6,7,'S005','20210005','Rizki Pratama','11 IPA 1','rizki.pratama@sekolah.com','081234567894','rizki.pratama','$2y$12$eO112YKnlsTgCJk4zG1X..nedbLMDQVO5f8JsJaNfYl7xbAGICqzK','2025-11-13 02:09:42','2025-11-13 02:09:42'),
(7,NULL,'SW007','12345678','Fareza Ainur','12 IPA','fairo@students.ac.id','1234567','fairo','$2y$12$5nv7VvIamE73EMTLdtdb5OjoOsd48PWICOqC3j3AN4YznVBe1SCNO','2025-11-19 05:58:44','2025-11-19 05:58:44');

-- ---------------------------------------------------------
-- TABLE: transaksi
-- ---------------------------------------------------------
CREATE TABLE `transaksi` (
  `id_transaksi` varchar(50) NOT NULL,
  `id_siswa` int NOT NULL,
  `id_buku` varchar(20) NOT NULL,
  `tanggal_pinjam` date NOT NULL,
  `tanggal_jatuh_tempo` date NOT NULL,
  `tanggal_kembali` date DEFAULT NULL,
  `status` varchar(50) NOT NULL,
  `denda` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_transaksi`)
);

INSERT INTO `transaksi` VALUES
('TRX1002',5,'B005','2025-11-19','2025-11-26',NULL,'dipinjam',0,'2025-11-19 05:43:35','2025-11-19 05:43:35'),
('TRX1764214298',3,'B003','2025-11-27','2025-12-04','2025-11-29','dikembalikan',0,'2025-11-27 03:31:38','2025-11-29 02:26:39'),
('TRX1764264836',2,'BK001','2025-11-27','2025-12-04',NULL,'dipinjam',0,'2025-11-27 17:33:56','2025-11-27 17:33:56'),
('TRX1764382559',6,'B006','2025-11-29','2025-12-06',NULL,'dipinjam',0,'2025-11-29 02:15:59','2025-11-29 02:15:59'),
('TRX1764383134',3,'B004','2025-11-29','2025-12-06',NULL,'dipinjam',0,'2025-11-29 02:25:34','2025-11-29 02:25:34'),
('TRX1764384568',4,'B006','2025-11-29','2025-12-06',NULL,'dipinjam',0,'2025-11-29 02:49:28','2025-11-29 02:49:28'),
('TRX1764384759',4,'B004','2025-11-29','2025-12-06','2025-11-29','dikembalikan',0,'2025-11-29 02:52:39','2025-11-29 02:54:06');

-- ---------------------------------------------------------
-- TABLE: sessions
-- ---------------------------------------------------------
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`)
);
