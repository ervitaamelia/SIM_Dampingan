<script>
import FasilitatorLayout from '@/layouts/FasilitatorLayout.vue'

export default {
  components: {
    FasilitatorLayout,
  },
  data() {
    return {
      isMenuOpen: false,
      isDropdownOpen: false,
      showPopup: false,
      events: [
        {
          image:
            'https://cdn.builder.io/api/v1/image/assets/TEMP/43b5a174a5c9b8be591289512abba2d75768c2d4',
          title:
            'Peluncuran BLASMU: Beras Sehat Inovasi Jamaah Tani Muhammadiyah (JATAM) Lebong Bengkulu',
          description:
            'Jamaah Tani Muhammadiyah (JATAM) Lebong sukses menyelenggarakan panen raya padi sehat yang berlangsung pada Rabu (5/2) di Kampung Jawa, Lebong.',
          date: '05 Februari 2025',
          time: '10.00 WIB',
        },
        {
          image:
            'https://cdn.builder.io/api/v1/image/assets/TEMP/dc7488aab7f45cca0173cf65958b4a21cc722e1f',
          title:
            'JATAM Difabel Bejen Dampingan MPM Muhammadiyah Bagikan Kisah Sukses Peternakan Ayam Petelur Inklusif',
          description:
            'Jamaah Tani Muhammadiyah (JATAM) Lebong sukses menyelenggarakan panen raya padi sehat yang berlangsung pada Rabu (5/2) di Kampung Jawa, Lebong.',
          date: '14 Desember 2024',
          time: '10.00 WIB',
        },
        {
          image:
            'https://cdn.builder.io/api/v1/image/assets/TEMP/0e1465ff78b072509abf47b0b7f2336f1a75d9c7',
          title:
            'MPM Muhammadiyah Kuatkan Kaderisasi untuk Pemberdayaan Masyarakat di Kalimantan Timur',
          description:
            'MPM Pimpinan Pusat Muhammadiyah dan Pimpinan Wilayah Muhammadiyah (PWM) Kalimantan Timur resmi membuka Sekolah Kader Pemberdayaan Masyarakat (SEKAM) Angkatan I Tahun 2025 di Kampus Universitas Muhammadiyah Kalimantan Timur (UMKT)',
          date: '25 Januari 2025',
          time: '10.00 WIB',
        },
      ],
    }
  },
  methods: {
    toggleDropdown() {
      this.isDropdownOpen = !this.isDropdownOpen
    },
    tambahData() {
      console.log('Tombol ditekan!')
    },
  },
}
</script>

<template>
  <FasilitatorLayout>
    <section
      class="p-4 bg-white rounded-lg shadow-md border border-gray-200 max-w-[1000px] mx-auto mt-10"
    >
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold text-zinc-800">Tambah Kegiatan</h2>
        <a
          href="FormKegiatan"
          class="bg-blue-600 text-white px-4 py-2 rounded flex items-center gap-2"
        >
          <img
            src="https://cdn.builder.io/api/v1/image/assets/99e98c75e74449b086557677558acabb/3fb2a52c394a94791444c2d1acbc0cc96fc3e309192427551991d996db26506f?placeholderIfAbsent=true"
            alt="Tambah"
            class="w-5 h-5"
          />
          Tambah
        </a>
      </div>

      <!-- Header Tabel -->
      <div
        class="grid gap-4 px-0 py-4 text-sm border-b border-solid border-b-zinc-100 grid-cols-[210px_1fr_1fr_140px_80px_40px] text-zinc-500 max-md:grid-cols-[150px_1fr_1fr_120px_80px_40px] max-sm:grid-cols-[120px_1fr_1fr_100px_70px_40px] max-sm:min-w-[800px]"
      >
        <div>Dokumentasi</div>
        <div>Judul Kegiatan</div>
        <div>Deskripsi</div>
        <div>Tanggal Pelaksanaan</div>
        <div>Waktu</div>
        <div>Aksi</div>
      </div>

      <!-- Kontainer Scroll -->
      <div class="max-h-[400px] overflow-y-auto scrollbar-hidden">
        <div
          v-for="(event, index) in events"
          :key="index"
          class="grid gap-4 px-0 py-6 border-b border-solid border-b-zinc-100 grid-cols-[210px_1fr_1fr_140px_80px_40px] max-md:grid-cols-[150px_1fr_1fr_120px_80px_40px] max-sm:grid-cols-[120px_1fr_1fr_100px_70px_40px] max-sm:min-w-[800px]"
        >
          <img
            :src="event.image"
            :alt="event.title"
            class="object-cover rounded h-[120px] w-[210px] max-md:w-[150px] max-sm:w-[120px]"
          />
          <div class="text-sm text-zinc-800">{{ event.title }}</div>
          <div class="text-sm text-zinc-800">{{ event.description }}</div>
          <div class="text-sm text-zinc-800">{{ event.date }}</div>
          <div class="text-sm text-zinc-800">{{ event.time }}</div>
          <a href="FormKegiatan">
            <svg
              width="18"
              height="18"
              viewBox="0 0 18 18"
              fill="none"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                d="M2 16H3.425L13.2 6.225L11.775 4.8L2 14.575V16ZM0 18V13.75L13.2 0.575C13.4 0.391667 13.6208 0.25 13.8625 0.15C14.1042 0.05 14.3583 0 14.625 0C14.8917 0 15.15 0.05 15.4 0.15C15.65 0.25 15.8667 0.4 16.05 0.6L17.425 2C17.625 2.18333 17.7708 2.4 17.8625 2.65C17.9542 2.9 18 3.15 18 3.4C18 3.66667 17.9542 3.92083 17.8625 4.1625C17.7708 4.40417 17.625 4.625 17.425 4.825L4.25 18H0ZM12.475 5.525L11.775 4.8L13.2 6.225L12.475 5.525Z"
                fill="#0080C5"
              />
            </svg>
          </a>
        </div>
      </div>
    </section>
  </FasilitatorLayout>
</template>

<style>
/* Menghilangkan scrollbar tapi tetap bisa di-scroll */
.scrollbar-hidden {
  -ms-overflow-style: none; /* IE & Edge */
  scrollbar-width: none; /* Firefox */
}

.scrollbar-hidden::-webkit-scrollbar {
  display: none; /* Chrome, Safari */
}
</style>