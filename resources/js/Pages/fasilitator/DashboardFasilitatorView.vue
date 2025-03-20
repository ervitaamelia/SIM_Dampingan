<script setup>
import { ref } from 'vue'
import FasilitatorLayout from '@/layouts/FasilitatorLayout.vue'

const isDropdownOpen = ref(false)
const showPopup = ref(false)
const isMenuOpen = ref(false)

const toggleDropdown = () => {
  isDropdownOpen.value = !isDropdownOpen.value
}

const getArtikelLink = id => {
  return `/Artikel${id}`
}

const tambahData = () => {
  console.log('Tombol ditekan!')
}

const keyIcon = `<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M9 22H15C20 22 22 20 22 15V9C22 4 20 2 15 2H9C4 2 2 4 2 9V15C2 20 4 22 9 22Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M16.2802 13.61C15.1502 14.74 13.5302 15.09 12.1002 14.64L9.5102 17.22C9.3302 17.41 8.9602 17.53 8.6902 17.49L7.4902 17.33C7.0902 17.28 6.7302 16.9 6.6702 16.51L6.5102 15.31C6.4702 15.05 6.6002 14.68 6.7802 14.49L9.3602 11.91C8.9202 10.48 9.2602 8.86001 10.3902 7.73001C12.0102 6.11001 14.6502 6.11001 16.2802 7.73001C17.9002 9.34001 17.9002 11.98 16.2802 13.61Z" stroke="white" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M10.4501 16.28L9.6001 15.42" stroke="white" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M13.3945 10.7H13.4035" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
</svg>`

const productIcon = `<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M15 22.75H9C3.57 22.75 1.25 20.43 1.25 15V9C1.25 3.57 3.57 1.25 9 1.25H15C20.43 1.25 22.75 3.57 22.75 9V15C22.75 20.43 20.43 22.75 15 22.75ZM9 2.75C4.39 2.75 2.75 4.39 2.75 9V15C2.75 19.61 4.39 21.25 9 21.25H15C19.61 21.25 21.25 19.61 21.25 15V9C21.25 4.39 19.61 2.75 15 2.75H9Z" fill="#9197B3"/>
<path d="M12.0006 13.0797C11.8706 13.0797 11.7406 13.0497 11.6206 12.9797L6.32061 9.9197C5.96061 9.7097 5.84059 9.2497 6.05059 8.8997C6.26059 8.5397 6.72061 8.4197 7.07061 8.6297L11.9906 11.4797L16.8806 8.6497C17.2406 8.4397 17.7006 8.5697 17.9006 8.9197C18.1006 9.2697 17.9806 9.7397 17.6306 9.9397L12.3706 12.9797C12.2606 13.0397 12.1306 13.0797 12.0006 13.0797Z" fill="#9197B3"/>
<path d="M12 18.5201C11.59 18.5201 11.25 18.1801 11.25 17.7701V12.3301C11.25 11.9201 11.59 11.5801 12 11.5801C12.41 11.5801 12.75 11.9201 12.75 12.3301V17.7701C12.75 18.1801 12.41 18.5201 12 18.5201Z" fill="#9197B3"/>
<path d="M12.0002 18.7498C11.4202 18.7498 10.8503 18.6198 10.3903 18.3698L7.19025 16.5898C6.23025 16.0598 5.49023 14.7898 5.49023 13.6898V10.2998C5.49023 9.20981 6.24025 7.9398 7.19025 7.3998L10.3903 5.6198C11.3103 5.1098 12.6902 5.1098 13.6102 5.6198L16.8102 7.3998C17.7702 7.9298 18.5103 9.19981 18.5103 10.2998V13.6898C18.5103 14.7798 17.7602 16.0498 16.8102 16.5898L13.6102 18.3698C13.1502 18.6298 12.5802 18.7498 12.0002 18.7498Z" fill="#9197B3"/>
</svg>`

const dropdownIcon = `<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M6 9L12 15L18 9" stroke="#757575" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
</svg>`

const newsItems = [
  {
    id: 1,
    image:
      'https://cdn.builder.io/api/v1/image/assets/TEMP/16271b27a3e1e288d76578e3e2a469550c5ba032',
    date: '05/02/25',
    title:
      'Peluncuran BLASMU: Beras Sehat Inovasi Jamaah Tani Muhammadiyah (JATAM) Lebong Bengkulu',
  },
  {
    id: 2,
    image:
      'https://cdn.builder.io/api/v1/image/assets/TEMP/25b61d5f09e4ad466a1dd3cdd7e2a49e9bdb1f89',
    date: '14/12/24',
    title:
      'JATAM Difabel Bejen Dampingan MPM Muhammadiyah Bagikan Kisah Sukses Peternakan Ayam Petelur Inklusif',
  },
  {
    id: 3,
    image:
      'https://cdn.builder.io/api/v1/image/assets/TEMP/9c3b9782b96384670eaa8bf3d45b86c1ca8b9b23',
    date: '25/01/25',
    title:
      'MPM Muhammadiyah Kuatkan Kaderisasi untuk Pemberdayaan Masyarakat di Kalimantan Timur',
  },
  {
    id: 1,
    image:
      'https://cdn.builder.io/api/v1/image/assets/TEMP/16271b27a3e1e288d76578e3e2a469550c5ba032',
    date: '05/02/25',
    title:
      'Peluncuran BLASMU: Beras Sehat Inovasi Jamaah Tani Muhammadiyah (JATAM) Lebong Bengkulu',
  },
  {
    id: 2,
    image:
      'https://cdn.builder.io/api/v1/image/assets/TEMP/25b61d5f09e4ad466a1dd3cdd7e2a49e9bdb1f89',
    date: '14/12/24',
    title:
      'JATAM Difabel Bejen Dampingan MPM Muhammadiyah Bagikan Kisah Sukses Peternakan Ayam Petelur Inklusif',
  },
  {
    id: 3,
    image:
      'https://cdn.builder.io/api/v1/image/assets/TEMP/9c3b9782b96384670eaa8bf3d45b86c1ca8b9b23',
    date: '25/01/25',
    title:
      'MPM Muhammadiyah Kuatkan Kaderisasi untuk Pemberdayaan Masyarakat di Kalimantan Timur',
  },
]

const tableHeaders = [
  'No Anggota',
  'Nama Lengkap',
  'Tempat Lahir',
  'Tanggal Lahir',
  'Jenis Kelamin',
  'Agama',
  'Pekerjaan',
  'No Telepon',
  'Alamat',
]

const tableData = [
  {
    'No Anggota': '1',
    'Nama Lengkap': 'Ervita',
    'Tempat Lahir': 'Klaten',
    'Tanggal Lahir': '02 Juli 1999',
    'Jenis Kelamin': 'Perempuan',
    Agama: 'Islam',
    Pekerjaan: 'Wiraswasta',
    'No Telepon': '(225) 555-0118',
    Alamat: 'Bantul',
  },
  {
    'No Anggota': '2',
    'Nama Lengkap': 'Fevy',
    'Tempat Lahir': 'Kuningan',
    'Tanggal Lahir': '04 Juni 1999',
    'Jenis Kelamin': 'Perempuan',
    Agama: 'Islam',
    Pekerjaan: 'Wiraswasta',
    'No Telepon': '(225) 555-0118',
    Alamat: 'Bantul',
  },
  {
    'No Anggota': '3',
    'Nama Lengkap': 'Fica',
    'Tempat Lahir': 'Ngawi',
    'Tanggal Lahir': '12 Juni 2000',
    'Jenis Kelamin': 'Perempuan',
    Agama: 'Islam',
    Pekerjaan: 'Wiraswasta',
    'No Telepon': '(225) 555-0118',
    Alamat: 'Bantul',
  },
]
</script>

<template>
  <FasilitatorLayout>
    <main
      class="grow px-6 py-4 m-4 bg-white rounded-3xl shadow-lg max-md:p-4 max-md:m-3 h-[calc(100vh-50px)] w-full overflow-auto scrollbar-hidden"
    >
      <h2 class="mb-6 text-3xl font-semibold text-gray-900">
        Dashboard Fasilitator
      </h2>

      <div class="overflow-x-auto w-full pb-4 scrollbar-hidden relative">
        <div class="flex items-center gap-4">
          <button
            class="p-2 bg-gray-200 rounded-full hover:bg-gray-300 transition"
          >
            &#8249;
          </button>
          <div class="flex flex-col w-max gap-6">
            <section class="flex gap-6">
              <article
                v-for="news in newsItems"
                :key="news.id"
                class="flex flex-col gap-3 w-[350px] flex-shrink-0 bg-white p-4 rounded-xl shadow-md"
              >
                <router-link :to="getArtikelLink(news.id)">
                  <img
                    :src="news.image"
                    :alt="news.title"
                    class="object-cover w-[350px] h-[230px] rounded-xl cursor-pointer transition-transform hover:scale-105"
                  />
                </router-link>
                <time class="text-xs font-bold text-gray-700">{{
                  news.date
                }}</time>
                <h3 class="text-base font-semibold text-gray-800 leading-snug">
                  {{ news.title }}
                </h3>
                <button
                  type="button"
                  class="px-4 py-1.5 text-xs font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 transition"
                >
                  Lihat Laporan
                </button>
              </article>
            </section>
          </div>
          <button
            class="p-2 bg-gray-200 rounded-full hover:bg-gray-300 transition"
          >
            &#8250;
          </button>
        </div>
      </div>

      <section class="mt-8">
        <h2 class="mb-6 text-2xl font-semibold text-gray-900">
          Data Dampingan
        </h2>
        <div class="w-full max-w-full pb-4 overflow-x-auto">
          <table
            class="w-full border-collapse bg-white shadow-md rounded-lg overflow-hidden"
          >
            <thead class="bg-gray-300 text-gray-700">
              <tr>
                <th
                  v-for="header in tableHeaders"
                  :key="header"
                  class="p-4 text-sm font-medium text-left border-b border-gray-200"
                >
                  {{ header }}
                </th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="row in tableData" :key="row.id">
                <td
                  v-for="(value, key) in row"
                  :key="key"
                  class="p-4 text-sm border-b border-gray-200 text-gray-900"
                >
                  {{ value }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>
    </main>
  </FasilitatorLayout>
</template>

<style>
/* Sembunyikan scrollbar di semua browser */
.scrollbar-hidden::-webkit-scrollbar {
  display: none; /* Untuk Chrome, Safari */
}

.scrollbar-hidden {
  -ms-overflow-style: none; /* Untuk Internet Explorer */
  scrollbar-width: none; /* Untuk Firefox */
}

@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');
@import url('https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css');

:root {
  font-family: 'Poppins', sans-serif;
}
</style>
