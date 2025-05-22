<script setup>
import DetailKegiatanLayout from '@/Layouts/DetailKegiatanLayout.vue';
import { usePage } from '@inertiajs/vue3';
import { ref, computed, onMounted, onBeforeUnmount } from 'vue';
import { Head } from '@inertiajs/vue3';

const page = usePage();
const kegiatan = computed(() => page.props.kegiatan);
const semuaArtikel = computed(() => page.props.artikelLain || []);

const currentIndex = ref(0);
let intervalId = null;

const nextImage = () => {
  currentIndex.value = (currentIndex.value + 1) % kegiatan.value.galeris.length;
};

const prevImage = () => {
  currentIndex.value = (currentIndex.value - 1 + kegiatan.value.galeris.length) % kegiatan.value.galeris.length;
};

const startAutoSlide = () => {
  intervalId = setInterval(nextImage, 3000);
};

const stopAutoSlide = () => {
  if (intervalId) clearInterval(intervalId);
};

onMounted(startAutoSlide);
onBeforeUnmount(stopAutoSlide);

const goBack = () => window.history.back();

function formatTanggal(tanggal) {
  const date = new Date(tanggal);
  return `${String(date.getDate()).padStart(2, '0')}-${String(date.getMonth() + 1).padStart(2, '0')}-${date.getFullYear()}`;
}

function formatWaktu(waktu) {
  if (!waktu) return '';
  const [hours, minutes] = waktu.split(':');
  return `${hours}:${minutes}`;
}
</script>


<template>
  <DetailKegiatanLayout>
    <Head title="Artikel Kegiatan" />
    <main class="p-6 max-md:p-4 max-sm:p-2.5 min-h-screen overflow-y-auto scrollbar-hide">
      <button @click="goBack" class="mb-4 px-4 py-2 bg-sky-600 text-white rounded-md hover:bg-sky-700 transition">
        ← Kembali
      </button>

      <div class="flex flex-col lg:flex-row gap-6">
        <!-- Artikel Utama -->
        <article class="lg:w-2/3 bg-white p-6 rounded-2xl shadow-md max-sm:p-4">
          <header class="mb-5 text-center">
            <h2 class="mb-2 text-xl font-bold text-gray-800">{{ kegiatan.judul }}</h2>
            <div class="flex justify-center gap-2 text-sm text-gray-500">
              <time>{{ formatTanggal(kegiatan.tanggal) }}</time>
              <span>|</span>
              <time>{{ formatWaktu(kegiatan.waktu) }}</time>
            </div>
          </header>

          <!-- Slider Gambar -->
          <div class="relative w-full max-w-[600px] mx-auto mb-6">
            <img
              :src="`/storage/${kegiatan.galeris[currentIndex]?.foto}`"
              alt="Foto Kegiatan"
              class="rounded-xl w-full h-[266px] object-cover transition duration-500"
            />
            
            
            <!-- Indikator -->
            <div class="absolute bottom-2 left-1/2 -translate-x-1/2 flex gap-1">
              <span
                v-for="(g, i) in kegiatan.galeris"
                :key="i"
                class="w-2 h-2 rounded-full"
                :class="currentIndex === i ? 'bg-white' : 'bg-white/50'"
              ></span>
            </div>
          </div>

          <!-- Deskripsi -->
<p class="text-base leading-relaxed text-gray-800 whitespace-pre-line text-justify">
  {{ kegiatan.deskripsi }}
</p>


          <!-- Tombol Lihat Laporan -->
          <div class="mt-6 text-center">
            <a
        v-if="kegiatan.laporan"
        :href="`/storage/${kegiatan.laporan}`"
        target="_blank"
        class="mt-auto px-4 py-1.5 text-xs font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 transition text-center w-fit"
      >
        Lihat Laporan
      </a>
      <span v-else class="mt-auto h-[38px]"></span> <!-- dummy spacer -->
          </div>
        </article>

       <!-- Artikel Lain -->
<aside class="lg:w-1/3 space-y-4 bg-sky-600 p-4 rounded-2xl shadow-md">
  <h3 class="text-lg font-semibold text-white">Artikel Lainnya</h3>
  <div class="grid grid-cols-1 gap-4">
    <div
      v-for="item in semuaArtikel.slice(0, 4)"
      :key="item.id_kegiatan"
      class="bg-white p-3 rounded-xl shadow hover:shadow-md transition"
    >
      <a :href="`/artikel/${item.id_kegiatan}`">
        <img
          :src="`/storage/${item.galeris[0]?.foto ?? 'placeholder.jpg'}`"
          alt="Thumbnail"
          class="w-full h-32 object-cover rounded-lg mb-2"
        />
        <div class="text-sm font-semibold text-gray-700 line-clamp-2">{{ item.judul }}</div>
        <time class="text-xs text-gray-500">{{ formatTanggal(item.tanggal) }}</time>
      </a>
    </div>
  </div>
</aside>

      </div>
    </main>
  </DetailKegiatanLayout>
</template>


<style>
.scrollbar-hide::-webkit-scrollbar {
  display: none;
}
.scrollbar-hide {
  -ms-overflow-style: none;
  scrollbar-width: none;
}
</style>

