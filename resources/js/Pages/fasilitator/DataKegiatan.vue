<script>
import FasilitatorLayout from '@/Layouts/FasilitatorLayout.vue'
import { Head } from '@inertiajs/vue3'

export default {
  components: {
    FasilitatorLayout,
    Head
  },

  data() {
    return {
      expandedItems: []
    }
  },

  computed: {
    kegiatans() {
      return this.$page.props.data || []
    }
  },

  methods: {
    isExpanded(id) {
      return this.expandedItems.includes(id)
    },

    toggleExpanded(id) {
      if (this.isExpanded(id)) {
        this.expandedItems = this.expandedItems.filter(item => item !== id)
      } else {
        this.expandedItems.push(id)
      }
    },

    truncateText(text, length = 300) {
      if (!text) return ''
      return text.length > length ? text.substring(0, length) + '...' : text
    },

    formatTanggal(tanggal) {
      const date = new Date(tanggal);
      const day = String(date.getDate()).padStart(2, '0');
      const month = String(date.getMonth() + 1).padStart(2, '0'); // Januari = 0
      const year = date.getFullYear();
      return `${day}-${month}-${year}`;
    },

    formatWaktu(waktu) {
      if (!waktu) return ''
      const [hours, minutes] = waktu.split(':')
      return `${hours}:${minutes}`
    }
  }
}
</script>

<template>
  <FasilitatorLayout>

    <Head title="Data Kegiatan" />
    <section class="p-4 bg-white rounded-lg shadow-md border border-gray-200 h-auto">
      <div class="flex justify-between items-center mb-2">
        <h2 class="text-2xl font-bold text-zinc-800">Data Kegiatan</h2>
        <a :href="route('kegiatan.create')" class="bg-blue-600 text-white px-4 py-2 rounded flex items-center gap-2">
          <img
            src="https://cdn.builder.io/api/v1/image/assets/99e98c75e74449b086557677558acabb/3fb2a52c394a94791444c2d1acbc0cc96fc3e309192427551991d996db26506f?placeholderIfAbsent=true"
            alt="Tambah" class="w-5 h-5" />
          Tambah
        </a>
      </div>

      <!-- Header Tabel -->
      <div
        class="grid gap-4 px-0 py-4 text-sm border-b border-solid border-b-zinc-100 grid-cols-[210px_1fr_1fr_140px_80px_40px] text-zinc-500 max-md:grid-cols-[150px_1fr_1fr_120px_80px_40px] max-sm:grid-cols-[120px_1fr_1fr_100px_70px_40px] max-sm:min-w-[800px]">
        <div>Foto Dokumentasi</div>
        <div>Judul Kegiatan</div>
        <div>Deskripsi</div>
        <div>Tanggal Pelaksanaan</div>
        <div>Waktu</div>
        <div>Aksi</div>
      </div>

      <!-- Kontainer Scroll -->
      <div class="max-h-[530px] overflow-y-auto scrollbar-hidden">
        <div v-for="kegiatan in kegiatans" :key="kegiatan.id_kegiatan"
          class="grid gap-4 px-0 py-6 border-b border-solid border-b-zinc-100 grid-cols-[210px_0.5fr_1fr_140px_80px_40px] max-md:grid-cols-[150px_1fr_1fr_120px_80px_40px] max-sm:grid-cols-[120px_1fr_1fr_100px_70px_40px] max-sm:min-w-[800px]">
          <img :src="`/storage/${kegiatan.galeris[0]?.foto}`" :alt="kegiatan.judul"
            class="object-cover rounded h-[120px] w-[210px] max-md:w-[150px] max-sm:w-[120px]" />
          <div class="text-sm text-zinc-800">{{ kegiatan.judul }}</div>
          <div class="text-sm text-zinc-800">
            <span v-if="isExpanded(kegiatan.id_kegiatan)">
              {{ kegiatan.deskripsi }}
            </span>
            <span v-else>
              {{ truncateText(kegiatan.deskripsi, 300) }}
            </span>
            <button v-if="kegiatan.deskripsi.length > 300" @click="toggleExpanded(kegiatan.id_kegiatan)"
              class="text-blue-600 ml-1 hover:underline">
              {{ isExpanded(kegiatan.id_kegiatan) ? 'Sembunyikan' : 'Lihat selengkapnya' }}
            </button>
          </div>
          <div class="text-sm text-zinc-800">{{ formatTanggal(kegiatan.tanggal) }}</div>
          <div class="text-sm text-zinc-800">{{ formatWaktu(kegiatan.waktu) }}</div>
          <a :href="route('kegiatan.edit', kegiatan.id_kegiatan)">
            <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path
                d="M2 16H3.425L13.2 6.225L11.775 4.8L2 14.575V16ZM0 18V13.75L13.2 0.575C13.4 0.391667 13.6208 0.25 13.8625 0.15C14.1042 0.05 14.3583 0 14.625 0C14.8917 0 15.15 0.05 15.4 0.15C15.65 0.25 15.8667 0.4 16.05 0.6L17.425 2C17.625 2.18333 17.7708 2.4 17.8625 2.65C17.9542 2.9 18 3.15 18 3.4C18 3.66667 17.9542 3.92083 17.8625 4.1625C17.7708 4.40417 17.625 4.625 17.425 4.825L4.25 18H0ZM12.475 5.525L11.775 4.8L13.2 6.225L12.475 5.525Z"
                fill="#0080C5" />
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
  -ms-overflow-style: none;
  /* IE & Edge */
  scrollbar-width: none;
  /* Firefox */
}

.scrollbar-hidden::-webkit-scrollbar {
  display: none;
  /* Chrome, Safari */
}
</style>