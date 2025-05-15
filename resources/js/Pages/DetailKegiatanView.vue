<script setup>
import DetailKegiatanLayout from '@/Layouts/DetailKegiatanLayout.vue';
import { usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { Head } from '@inertiajs/vue3';

const page = usePage();
const kegiatan = computed(() => page.props.kegiatan);

const goBack = () => {
  window.history.back();
};

function formatTanggal(tanggal) {
  const date = new Date(tanggal);
  const day = String(date.getDate()).padStart(2, '0');
  const month = String(date.getMonth() + 1).padStart(2, '0');
  const year = date.getFullYear();
  return `${day}-${month}-${year}`;
}

function formatWaktu(waktu) {
  if (!waktu) return '';
  const [hours, minutes] = waktu.split(':');
  return `${hours}:${minutes}`;
}

const currentIndex = ref(0);
const nextImage = () => {
  if (currentIndex.value < kegiatan.value.galeris.length - 1) {
    currentIndex.value++;
  } else {
    currentIndex.value = 0;
  }
};
const prevImage = () => {
  if (currentIndex.value > 0) {
    currentIndex.value--;
  } else {
    currentIndex.value = kegiatan.value.galeris.length - 1;
  }
};
</script>

<template>
  <DetailKegiatanLayout>
    <Head title="Artikel Kegiatan" />
    <main class="flex-1 p-6 max-md:p-4 max-sm:p-2.5 h-screen overflow-y-auto scrollbar-hide">
      <button @click="goBack"
        class="mb-4 px-4 py-2 bg-sky-600 text-white rounded-md hover:bg-sky-700 transition">
        ← Kembali
      </button>
      <article class="p-8 bg-white w-full rounded-[30px] shadow-[0_10px_60px_rgba(226,236,249,0.5)] max-sm:p-4">
        <header class="mb-5 text-center">
          <h2 class="mb-2.5 text-base font-bold">
            {{ kegiatan.judul }}
          </h2>
          <div class="flex gap-2.5 justify-center items-center text-sm text-slate-400 max-sm:flex-wrap">
            <time>{{ formatTanggal(kegiatan.tanggal) }}</time>
            <span>|</span>
            <time>{{ formatWaktu(kegiatan.waktu) }}</time>
          </div>
        </header>
        <!-- Gambar Slider -->
        <div class="relative w-full max-w-[500px] mx-auto mb-6">
          <img
            :src="`/storage/${kegiatan.galeris[currentIndex]?.foto}`"
            alt="Foto Kegiatan"
            class="rounded-xl w-full h-[266px] object-cover"
          />
          <button
            @click="prevImage"
            class="absolute left-2 top-1/2 transform -translate-y-1/2 bg-gray-200 hover:bg-gray-300 p-2 rounded-full"
          >
            ‹
          </button>
          <button
            @click="nextImage"
            class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-gray-200 hover:bg-gray-300 p-2 rounded-full"
          >
            ›
          </button>
        </div>
        <p class="text-base leading-normal text-black">
          {{ kegiatan.deskripsi }}
        </p>
      </article>
    </main>
  </DetailKegiatanLayout>
</template>

<style>
/* Untuk Chrome, Safari, dan Edge */
.hide-scrollbar::-webkit-scrollbar {
  display: none;
}

/* Untuk Firefox */
.hide-scrollbar {
  scrollbar-width: none;
}
</style>
