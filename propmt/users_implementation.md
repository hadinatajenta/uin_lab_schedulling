Masih ada beberapa masalah yang perlu diperbaiki dan beberapa flow baru yang perlu ditambahkan.

## 1. Perbaikan Modal User Management

### Overflowing Text

* Saat ini masih terdapat masalah text overflow pada modal.
* Pastikan seluruh konten modal responsif untuk berbagai ukuran layar.
* Terapkan handling untuk:

  * Long username
  * Long email
  * Long role name
  * Long organization name
* Gunakan wrapping atau truncation yang sesuai tanpa merusak layout.
* Pastikan tidak ada horizontal scrolling yang tidak diperlukan.

---

## 2. Restriksi Remove / Delete User

### Self Remove Protection

Baik pada Frontend maupun Backend:

* User tidak boleh menghapus dirinya sendiri.
* Tombol Remove/Delete untuk akun yang sedang login harus:

  * Tidak ditampilkan, atau
  * Disabled dengan informasi yang jelas kepada user.

Backend wajib melakukan validasi tambahan meskipun frontend sudah melakukan pembatasan.

Contoh:

* Admin A tidak boleh menghapus Admin A sendiri.
* Dosen A tidak boleh menghapus Dosen A sendiri.

---

## 3. Role-Based Access Control (RBAC)

Implementasikan validasi yang ketat di Frontend dan Backend.

### Hierarki Role

Admin Lab > Dosen > Mahasiswa

### Aturan Edit

#### Admin Lab

* Dapat mengedit Dosen
* Dapat mengedit Mahasiswa
* Dapat mengedit Admin Lab lain (jika memang diizinkan oleh sistem)
* Tidak dapat menurunkan atau mengubah role yang akan menyebabkan kehilangan akses kritikal sistem

#### Dosen

* Hanya dapat mengedit Mahasiswa
* Tidak dapat mengedit Dosen lain
* Tidak dapat mengedit Admin Lab

#### Mahasiswa

* Tidak dapat mengedit user lain

### Aturan Delete

#### Admin Lab

* Dapat menghapus Dosen
* Dapat menghapus Mahasiswa

#### Dosen

* Hanya dapat menghapus Mahasiswa

#### Mahasiswa

* Tidak dapat menghapus user lain

### Backend Validation

Jangan hanya mengandalkan frontend.

Semua endpoint:

* Update User
* Delete User
* Change Role
* Bulk Actions

harus memvalidasi hirarki role di backend.

Jika terjadi pelanggaran:

* Return HTTP status yang sesuai (403 Forbidden).
* Sertakan pesan error yang jelas.

---

## 4. Halaman Struktur Organisasi / Hierarki

Tambahkan section atau tab baru pada halaman Users.

### Tujuan

Menampilkan struktur organisasi dan hubungan hirarki antar role.

### Tampilan

Contoh:

Admin Lab
├── Dosen A
│   ├── Mahasiswa A
│   ├── Mahasiswa B
│
├── Dosen B
│   ├── Mahasiswa C

### Fitur

* Expand / Collapse Tree
* Search Node
* Jumlah anggota per role
* Responsive layout
* Lazy rendering jika data besar

---

## 5. Pencarian dan Filtering User

Tambahkan fitur filter yang lebih lengkap.

### Search

* Nama
* Email
* Username

### Filter

* Role
* Status Aktif / Nonaktif
* Tanggal Pembuatan
* Organisasi / Lab (jika ada)

### UX

* Debounce search input
* Server-side filtering
* Persist filter state saat reload halaman

---

## 6. Grid View dan Table View

Tambahkan opsi tampilan data user.

### Grid View

Cocok untuk visual browsing.

Tampilkan:

* Avatar
* Nama
* Email
* Role
* Status

### Table View

Cocok untuk manajemen data skala besar.

Kolom:

* Nama
* Email
* Role
* Status
* Created At
* Action

### Requirements

* Toggle Grid ↔ Table
* Simpan preferensi tampilan user
* Pagination tetap berfungsi pada kedua mode

---

## 7. Performance Optimization (High Priority)

Karena aplikasi menggunakan PHP dan jumlah user dapat bertambah besar, lakukan optimisasi secara ketat.

### Backend

#### Database

* Tambahkan index pada:

  * role_id
  * email
  * username
  * status
  * created_at

#### Query Optimization

* Hindari N+1 Query
* Gunakan eager loading
* Ambil hanya field yang dibutuhkan
* Implementasikan pagination server-side

#### API

* Hindari payload berlebihan
* Gunakan DTO/Transformer jika diperlukan
* Implementasikan caching untuk data yang jarang berubah

### Frontend

#### Rendering

* Hindari re-render tidak perlu
* Gunakan virtualized list jika jumlah data besar
* Lazy load komponen berat

#### Search

* Debounce minimal 300–500ms
* Request cancellation untuk pencarian yang sudah tidak relevan

#### Organization Tree

* Lazy load child node
* Expand on demand
* Jangan render seluruh tree sekaligus jika data besar

### Target

* Halaman Users tetap responsif pada 1.000–10.000+ user.
* Waktu respon API konsisten dan minim query berulang.
* Tidak ada bottleneck signifikan pada filtering, searching, maupun hierarchy rendering.

---

## 8. Acceptance Criteria

* Tidak ada text overflow pada modal.
* User tidak dapat menghapus dirinya sendiri.
* RBAC berjalan konsisten di frontend dan backend.
* Hierarchy permissions tervalidasi pada seluruh endpoint terkait.
* Tersedia tab Struktur Organisasi.
* Tersedia Search & Advanced Filter.
* Tersedia Grid View dan Table View.
* Seluruh fitur telah dioptimalkan untuk performa tinggi.
* Tidak terdapat N+1 query.
* Pagination, filtering, dan sorting dilakukan di server-side.
