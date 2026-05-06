# NEXUS — Smart Weather Intelligence System

![Laravel](https://img.shields.io/badge/laravel-%23FF2D20.svg?style=for-the-badge&logo=laravel&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-%231572B6.svg?style=for-the-badge&logo=css3&logoColor=white)
![MySQL](https://img.shields.io/badge/mysql-%234479A1.svg?style=for-the-badge&logo=mysql&logoColor=white)

NEXUS adalah platform weather intelligence real-time berbasis Laravel yang menyajikan data atmosfer presisi dan analisis prediksi cuaca yang mendalam. Berbeda dari aplikasi cuaca standar, NEXUS mengintegrasikan Business Logic Layer melalui Service Pattern untuk memberikan rekomendasi aktivitas cerdas secara otomatis berdasarkan kondisi meteorologi yang sedang berlangsung.

Aplikasi ini memproses data secara dinamis ke dalam antarmuka futuristic-glassmorphism yang responsif, dirancang untuk memberikan pengalaman pengguna yang intuitif dalam memantau perubahan cuaca per jam (interval 3 jam) hingga prediksi jangka pendek tiga hari ke depan.

## ✨ Fitur Utama
- **Real-Time Weather Insights:** Informasi temperatur, kelembapan, visibilitas, dan kecepatan angin yang akurat menggunakan integrasi Open Weather API.

- **Smart Activity Recommendations (New!):** Logika cerdas menggunakan Service Pattern yang memberikan saran aktivitas (seperti penggunaan sunscreen, membawa payung, atau peringatan berkendara) berdasarkan parameter cuaca secara real-time.

- **3-Hour Interval Forecast (New!):** Detail prakiraan cuaca setiap 3 jam untuk membantu pengguna melihat perubahan kondisi cuaca dalam satu hari secara spesifik.

- **3-Day Weather Forecast:** Menyediakan prediksi cuaca untuk hari ini, besok, dan lusa untuk membantu perencanaan jangka pendek.

- **Saved Locations:** Fitur untuk menyimpan lokasi favorit ke dalam database personal (MySQL) untuk akses cepat di masa mendatang.

- **Dynamic UI Rendering:** Visual antarmuka, palet warna kartu, dan ikon yang berubah secara otomatis menyesuaikan kondisi cuaca (Clear, Rain, Mist, dll).

- **Recent Searches:** Melacak riwayat pencarian kota terakhir pengguna untuk efisiensi navigasi.

- **Secure Authentication:** Manajemen akses menggunakan sistem otentikasi Laravel yang aman dengan pemisahan peran (Admin vs User).

## 🚀 Arsitektur Teknis
- **Backend:** PHP 8.2 dengan Framework **Laravel 12**.
- **Frontend:** **Blade Engine** dengan **Custom CSS3** (glassmorphism, gradient, animasi) dan **Vanilla JavaScript** untuk konversi unit dan interaksi dinamis.
- **Database:** **MySQL** (via WAMP Server) untuk menyimpan data user, favorit, riwayat pencarian, dan konfigurasi aplikasi.
- **API Integration:** Mengambil data meteorologi real-time dan forecast 3 hari melalui **WeatherAPI.com** REST API dengan sistem caching untuk efisiensi kuota.
- **Authentication:** Laravel Breeze dengan role-based access control (Admin & User).

## 📸 Tampilan Aplikasi
<p align="center">
  <img src="public/IMG\Screenshots\image/Nexus-AdminDashboard.png" alt="NEXUS Preview" width="800">
</p>

<p align="center">
  <img src="public/IMG\Screenshots\image/Nexus-Homepage2.png" alt="NEXUS Preview" width="800">
</p>
