<script setup>
import FasilitatorLayout from "@/Layouts/FasilitatorLayout.vue";
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const kegiatans = computed(() => page.props.data || []);

const getArtikelLink = (id) => `/artikel/${id}`;

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
      class="grow px-6 py-4 bg-white rounded-lg shadow-md max-md:p-4 max-md:m-3 h-[calc(100vh-70px)] w-full overflow-auto scrollbar-hidden">
      <h2 class="mb-6 text-xl font-semibold text-gray-900">
        Kegiatan Dampingan
      </h2>

      <!-- Tampilan artikel horizontal scroll -->
      <div class="overflow-x-auto w-full pb-4 scrollbar-hidden relative">
        <div class="flex items-center gap-4">
          <!-- Tombol panah kiri -->
          <button class="p-2 bg-gray-200 rounded-full hover:bg-gray-300 transition">
            &#8249;
          </button>

          <!-- Card kegiatan -->
          <div class="flex flex-col w-max gap-6">
            <section class="flex gap-6">
              <article v-for="kegiatan in kegiatans" :key="kegiatan.id_kegiatan"
                class="flex flex-col gap-3 w-[350px] flex-shrink-0 bg-white p-4 rounded-xl shadow-md hover:scale-105 transition-transform cursor-pointer ">
                <a :href="getArtikelLink(kegiatan.id_kegiatan)">
                  <img :src="`/storage/${kegiatan.galeris[0]?.foto ?? 'placeholder.jpg'}`" :alt="kegiatan.judul"
                    class="object-cover w-[350px] h-[230px] rounded-xl" />
                </a>
                <time class="text-xs font-bold text-gray-700">
                  {{ new Date(kegiatan.tanggal).toLocaleDateString('id-ID') }}
                </time>
                <h3 class="text-base font-semibold text-gray-800 leading-snug">
                  {{ kegiatan.judul }}
                </h3>
                <a v-if="kegiatan.laporan" :href="`/storage/${kegiatan.laporan}`" target="_blank"
                  class="px-4 py-1.5 text-xs font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 transition text-center">
                  Lihat Laporan
                </a>
              </article>
            </section>
          </div>

          <!-- Tombol panah kanan -->
          <button class="p-2 bg-gray-200 rounded-full hover:bg-gray-300 transition">
            &#8250;
          </button>
        </div>
      </div>

      <section class="mt-8">
        <h2 class="mb-6 text-2xl font-semibold text-gray-900">
          Data Dampingan
        </h2>
        <div class="w-full max-w-full pb-4 overflow-x-auto">
          <table class="w-full border-collapse bg-white shadow-md rounded-lg overflow-hidden">
            <thead class="bg-gray-300 text-gray-700">
              <tr>
                <th v-for="header in tableHeaders" :key="header"
                  class="p-4 text-sm font-medium text-left border-b border-gray-200">
                  {{ header }}
                </th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="row in tableData" :key="row.id">
                <td v-for="(value, key) in row" :key="key" class="p-4 text-sm border-b border-gray-200 text-gray-900">
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
  display: none;
  /* Untuk Chrome, Safari */
}

.scrollbar-hidden {
  -ms-overflow-style: none;
  /* Untuk Internet Explorer */
  scrollbar-width: none;
  /* Untuk Firefox */
}

@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');
@import url('https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css');

:root {
  font-family: 'Poppins', sans-serif;
}
</style>
