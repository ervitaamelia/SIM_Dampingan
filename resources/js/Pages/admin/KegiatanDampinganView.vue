<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const page = usePage();
const allKegiatans = computed(() => page.props.data || []);

const selectedDate = ref('');
const currentPage = ref(1);
const perPage = 6;

// Filter berdasarkan tanggal jika dipilih
const filteredKegiatans = computed(() => {
  if (!selectedDate.value) return allKegiatans.value;
  return allKegiatans.value.filter((k) => {
    const kegiatanDate = new Date(k.tanggal).toISOString().split('T')[0];
    return kegiatanDate === selectedDate.value;
  });
});

// Urutkan dari terbaru ke terlama (default)
const sortedKegiatans = computed(() => {
  return [...filteredKegiatans.value].sort(
    (a, b) => new Date(b.tanggal) - new Date(a.tanggal)
  );
});

const totalPages = computed(() =>
  Math.ceil(sortedKegiatans.value.length / perPage)
);

const paginatedKegiatans = computed(() => {
  const start = (currentPage.value - 1) * perPage;
  return sortedKegiatans.value.slice(start, start + perPage);
});

const paginationInfo = computed(() => {
  const total = sortedKegiatans.value.length;
  const from = (currentPage.value - 1) * perPage + 1;
  const to = Math.min(currentPage.value * perPage, total);
  return `Menampilkan ${from} - ${to} dari ${total} kegiatan`;
});

const goToPage = (page) => {
  if (page >= 1 && page <= totalPages.value) {
    currentPage.value = page;
  }
};

const getArtikelLink = (id) => `/artikel/${id}`;
</script>

<template>
  <AdminLayout>

    <Head title="Kegiatan Dampingan" />

    <div class="flex bg-gray-100">
      <main class="grow px-10 py-6 bg-white rounded-lg shadow-md max-md:p-4 max-md:m-3 h-auto overflow-y-auto">
        <h2 class="mb-6 text-2xl font-semibold text-black">Kegiatan Dampingan</h2>

        <!-- Sort by calendar -->
        <div class="mb-4 flex justify-end items-center gap-2">
          <label class="text-sm text-gray-700">Filter berdasarkan tanggal:</label>
          <input type="date" v-model="selectedDate" class="border text-sm rounded px-2 py-1" />
        </div>

        <!-- Grid Artikel -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
          <article v-for="kegiatan in paginatedKegiatans" :key="kegiatan.id_kegiatan"
            class="h-full flex flex-col justify-between bg-white p-4 rounded-xl shadow-md hover:scale-105 transition-transform cursor-pointer">
            <a :href="getArtikelLink(kegiatan.id_kegiatan)">
              <img :src="`/storage/${kegiatan.galeris[0]?.foto ?? 'placeholder.jpg'}`" :alt="kegiatan.judul"
                class="object-cover w-full h-[230px] rounded-xl" />
            </a>

            <div class="flex flex-col flex-grow mt-3">
              <time class="text-xs font-bold text-gray-700 mb-1">
                {{ new Date(kegiatan.tanggal).toLocaleDateString('id-ID') }}
              </time>
              <h3 class="text-base font-semibold text-gray-800 leading-snug mb-4">
                {{ kegiatan.judul }}
              </h3>

              <a v-if="kegiatan.laporan" :href="`/storage/${kegiatan.laporan}`" target="_blank"
                class="mt-auto px-4 py-1.5 text-xs font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 transition text-center w-fit">
                Lihat Laporan
              </a>
              <span v-else class="mt-auto h-[38px]"></span> <!-- dummy spacer -->

            </div>
          </article>
        </div>

        <!-- Pagination -->
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mt-4">
          <div class="text-sm text-gray-600">{{ paginationInfo }}</div>
          <div class="flex items-center gap-1">
            <button @click="goToPage(currentPage - 1)" :disabled="currentPage === 1"
              class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50 disabled:cursor-not-allowed">
              &lt;
            </button>
            <button v-for="page in totalPages" :key="page" @click="goToPage(page)" :class="[
              'px-3 py-1 rounded',
              currentPage === page ? 'bg-blue-500 text-white' : 'bg-gray-200 hover:bg-gray-300'
            ]">
              {{ page }}
            </button>
            <button @click="goToPage(currentPage + 1)" :disabled="currentPage === totalPages"
              class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50 disabled:cursor-not-allowed">
              &gt;
            </button>
          </div>
        </div>
      </main>
    </div>
  </AdminLayout>
</template>