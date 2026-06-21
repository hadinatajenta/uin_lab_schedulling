# Frontend Audit Checklist

Tujuan: menyatukan bahasa visual sistem agar konsisten, modern, dan tidak saling bertabrakan antar halaman.

## 1) Audit Fondasi Visual
- Catat semua warna yang sudah dipakai di layout, dashboard, komponen, dan halaman admin.
- Tetapkan peran warna:
  - `zinc` untuk base, teks, border, surface
  - `indigo` untuk primary action dan state aktif
  - `emerald` untuk success / normal
  - `rose` untuk warning / destructive
- Identifikasi warna yang tidak sesuai palet dan tandai untuk diganti.

## 2) Audit Layout Utama
- Periksa `layouts/app.blade.php` dan `layouts/guest.blade.php`.
- Samakan spacing container, background halaman, dan posisi footer.
- Pastikan sidebar, topbar, dan content wrapper memakai rhythm yang sama.

## 3) Audit Komponen Shared
- Review komponen yang dipakai berulang:
  - `components/footer.blade.php`
  - `components/ui/page-header.blade.php`
  - `components/ui/badge.blade.php`
  - `components/ui/table.blade.php`
  - `components/table/action-menu.blade.php`
  - `components/*button*.blade.php`
  - `components/ui/empty-state.blade.php`
- Standarkan radius, shadow, border, hover state, dan ukuran font.

## 4) Audit Pola Halaman
- Kelompokkan halaman berdasarkan jenis:
  - dashboard / overview
  - list / table
  - detail / article
  - form / create-edit
  - auth
- Tentukan pola masing-masing tipe agar user tidak belajar ulang di setiap halaman.

## 5) Audit Hierarki Konten
- Pastikan setiap halaman punya urutan jelas:
  1. judul
  2. ringkasan
  3. konten utama
  4. aksi sekunder
  5. catatan / meta
- Prioritaskan keterbacaan, bukan dekorasi.

## 6) Audit Typography
- Samakan ukuran judul, subjudul, body, caption, dan helper text.
- Gunakan line-height yang lebih lega untuk konten panjang.
- Batasi penggunaan uppercase hanya untuk label kecil atau section label.

## 7) Audit Komponen Interaktif
- Seragamkan warna dan behavior tombol.
- Pastikan modal, dropdown, tooltip, dan action menu punya state yang sama.
- Cek focus ring, hover, disabled, dan active state.

## 8) Audit Tabel dan List
- Samakan style tabel desktop dan card mobile.
- Tetapkan badge status, action button, dan empty state yang konsisten.
- Hindari campuran style lama dan baru di halaman yang sama.

## 9) Audit Detail Page
- Ubah halaman detail menjadi lebih informatif dan mudah dipindai.
- Untuk konten panjang, gunakan layout seperti artikel:
  - hero
  - daftar isi
  - section anchor
  - konten bertingkat
- Siapkan ruang untuk media multi-gambar jika nanti dibutuhkan.

## 10) Audit Form Page
- Kelompokkan field berdasarkan konteks.
- Kurangi kepadatan visual pada form panjang.
- Gunakan helper text hanya bila membantu keputusan user.

## 11) Audit Konsistensi UX
- Pastikan aksi utama selalu berada di lokasi yang mudah ditemukan.
- Hindari warna yang bersaing antar elemen penting.
- Pastikan state empty, loading, error, dan success tidak ambigu.

## 12) Urutan Implementasi yang Disarankan
1. Audit dan rapikan layout utama.
2. Standarkan komponen shared.
3. Rapikan halaman dashboard.
4. Rapikan halaman list / tabel.
5. Ubah halaman detail menjadi pola artikel bila kontennya panjang.
6. Terakhir, revisi form dan halaman pendukung.

## 13) Kriteria Selesai
- Warna konsisten di seluruh halaman.
- Komponen utama memakai style yang sama.
- Tidak ada elemen yang terasa “asal tempel”.
- Halaman terasa satu sistem, bukan kumpulan template terpisah.
