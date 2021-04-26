# Software Development Project (Code Igniter Framework)

**Update 26/04/2021**

Projek ini telah dibuat pada tahun 2018, dan dibuat ulang dengan menggunakan website gratis sebagai hostingannya, maka fitur seperti mengirim email dari server ke client 

untuk registrasi ataupun untuk apapun tidak dapat digunakan sebagai gantinya. Kunjungi https://coba2sdp.000webhostapp.com/ untuk melihat sekilas tentang projek ini.

Gunakan email: **coba@gmail.com** dan password: **coba** untuk login. (Fitur register tidak bisa berjalan karena google smtp sudah tidak dapat berjalan lagi / terdapat perubahan 

penggunaan). Fitur yang kadaluarsa seperti JNE tracking juga tidak dapat digunakan pada website ini. Untuk melihat dashboard admin kunjungi 

https://coba2sdp.000webhostapp.com/public/login?wawa dan masukan kata kunci **admin** sebagai email dan passwordnya.


--

**Agung, Antony, Irvan, Dhietto**

SDP yang akan kita buat adalah Online Shop. Bernama CI-Comp, online di [ci-comp.smankusors.com](ci-comp.smankusors.com)

Untuk meng-update versi online nya, mohon hubungi saya (Antony) agar saya melakukan `git pull` disitu

*Readme ini mohon dibaca lengkap agar ngodingnya lancar. Kecuali kalau Anda sudah berpengalaman dengan Linux ya gpp hehehe*

---

## IDE

Saya merekomendasikan untuk menggunakan [**Visual Studio Code**](https://code.visualstudio.com/) sebagai IDE karena selain lumayan enteng, sudah punya fitur bawaan Git. Namun untuk menggunakan fitur Git di VS Code, perlu install [**Git**](https://git-scm.com/download/win) di Windows terlebih dahulu.

Kemudian, saya merekomendasikan ekstensi berikut ini :

* PHP DocBlocker : untuk mempermudah dokumentasi fungsi, kelas, dsb di PHP
* PHP Intelephense : untuk menghighlight jika ada yang error dan untuk menyediakan fitur auto complete PHP di VS Code
* PHP Debug : install ini jika anda tau cara menggunakan **XDebug**

Selain itu di extension marketplace ada ekstensi snippet untuk codeigniter yang dapat Anda pakai jika Anda males ngetik full

---

## Git

Selanjutnya, adalah melakukan klonengan git. Setelah Git terinstall di komputer Anda, buka **cmd** dan `cd` ke folder dimana proyek ini mau di donlot. Setelah itu, Anda `git clone` proyek ini. Contoh:
```
cd W:\Kuliah
git clone https://[username]@bitbucket.org/Smankusors/sdp.git
```
Dan Anda berhasil melakukan kloning! Horay :D 
Untuk saat ini hanya kloning. Tutorial untuk melakukan **commit** (kodingan Anda siap) dan **push** (kirim kodingan Anda ke git saya) comming soon

---

## Controller & Model

Untuk Controller, bisa nge-extend BaseController untuk menerapkan semi kek Inheritance View. Contoh :
```
class ControllerWawa extends BaseController {
```
Kemudian untuk menampilkan view, bisa memanggil fungsi view saja
```
$this->view('namaView', [data]);
```
Untuk model, bisa nge-extend BaseModel
```
class ModelX extends BaseModel {
```
Dimana BaseModel mempunyai fungsi bawaan `all()` untuk mendapatkan semua data, dan `find($x)` untuk mendapatkan data menggunakan primary key nya

---

## Debugging di lokal

Untuk mengakses website ini, dapat mengakses folder `public`. Contohnya jika anda menaruh project ini di htdocs dengan path `C:\xampp\htdocs\project-sdl`, maka anda dapat mengakses halaman web dengan membuka URL berikut
```
[::1]/project-sdl/public
```
atau jika ingin mengakses controller dan fungsi tertentu, maka
```
[::1]/project-sdl/public/[nama controller]/[nama fungsi]
```
atau anda dapat menambah route anda sendiri di `application\config\routes.php`

Upload file harap ditaruh kedalam folder public, (bukan berarti taruh didepan, buatlah subdirectory untuk itu), kemudian jika ingin membuat link menuju file itu bisa menggunakan fungsi url helper dari CodeIgniter yaitu `base_url($url)`. Pastikan `$url` yang anda masukkan benar!

(Lebih baik menggunakan [::1] karena aku sendiri males mengganti fungsi base_url bawaan CI, fungsi mereka selalu kembali dalam bentuk IPv6 daripada localhost)
