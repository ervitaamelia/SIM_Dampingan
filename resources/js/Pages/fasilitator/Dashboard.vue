<script setup>
import { Head } from '@inertiajs/vue3'
import FasilitatorLayout from "@/Layouts/FasilitatorLayout.vue";

const getArtikelLink = id => {
  return `/Artikel${id}`
}

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
  <Head title="Dashboard" />

    <main
      class="grow px-6 py-4 bg-white rounded-lg shadow-md max-md:p-4 max-md:m-3 h-[calc(100vh-70px)] w-full overflow-auto scrollbar-hidden"
    >
      <h2 class="mb-6 text-xl font-semibold text-gray-900">
        Kegiatan Dampingan
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
