# NEXUS — Smart Weather Intelligence System

![Laravel](https://img.shields.io/badge/laravel-%23FF2D20.svg?style=for-the-badge&logo=laravel&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-%231572B6.svg?style=for-the-badge&logo=css3&logoColor=white)
![MySQL](https://img.shields.io/badge/mysql-%234479A1.svg?style=for-the-badge&logo=mysql&logoColor=white)

**NEXUS** adalah platform *weather intelligence* real-time berbasis **Laravel** yang menyajikan data atmosfer presisi dan prediksi cuaca hingga dua hari ke depan. Aplikasi ini memproses data meteorologi secara dinamis ke dalam antarmuka *futuristic-glassmorphism* yang responsif untuk membantu perencanaan aktivitas pengguna secara lebih cerdas.

## ✨ Fitur Utama
- **Real-Time Weather Insights**: Informasi temperatur, kelembapan, visibilitas, dan kecepatan angin yang akurat.
- **3-Day Weather Forecast**: Menyediakan prediksi cuaca untuk hari ini, besok, dan lusa untuk membantu perencanaan jangka pendek.
- **Saved Locations**: Fitur untuk menyimpan lokasi favorit ke dalam database personal untuk akses cepat.
- **Dynamic UI Rendering**: Visual antarmuka dan ikon yang berubah secara otomatis berdasarkan kondisi cuaca yang diterima.
- **Secure Authentication**: Manajemen akses menggunakan sistem otentikasi Laravel (Admin vs User).

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
  <img src="public/IMG\Screenshots\image/Nexus-Homepage.png" alt="NEXUS Preview" width="800">
</p>
