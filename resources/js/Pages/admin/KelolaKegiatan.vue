<script>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Head } from '@inertiajs/vue3'

export default {
  components: { AdminLayout, Head },
  data() {
    return {
      kegiatans: this.$page.props.data || [],
      showModal: false,
      selectedKegiatan: null,
      currentIndex: 0,
      intervalId: null,
      expandedItems: [],
    }
  },
  methods: {
    truncateText(text, len = 80) {
      return text?.length > len ? text.substr(0, len) + '...' : text
    },
    formatTanggal(t) {
      const d = new Date(t)
      return `${String(d.getDate()).padStart(2, '0')}-${String(d.getMonth()+1).padStart(2,'0')}-${d.getFullYear()}`
    },
    formatWaktu(w) {
      if (!w) return ''
      const [h, m] = w.split(':')
      return `${h}:${m}`
    },

    validateActivity(id) {
      this.$inertia.put(`/admin/kelola-kegiatan/${id}/validasi`, {}, {
        onSuccess: () => console.log(`Validasi ${id}`),
      })
    },
    rejectActivity(id) {
      this.$inertia.put(`/admin/kelola-kegiatan/${id}/tolak`, {}, {
        onSuccess: () => console.log(`Tolak ${id}`),
      })
    },

    openModal(k) {
      this.selectedKegiatan = k
      this.currentIndex = 0
      this.showModal = true
      this.startSlider()
    },
    closeModal() {
      this.showModal = false
      clearInterval(this.intervalId)
    },
    startSlider() {
      clearInterval(this.intervalId)
      this.intervalId = setInterval(() => {
        const gal = this.selectedKegiatan.galeris
        if (!gal?.length) return
        this.currentIndex = (this.currentIndex + 1) % gal.length
      }, 3000)
    }
  },

  beforeUnmount() {
    clearInterval(this.intervalId)
  }
}
</script>

<template>
  <AdminLayout>
    <Head title="Kelola Kegiatan" />

    <section class="p-4 bg-white rounded-lg shadow-md border border-gray-200">
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold text-zinc-800">Kelola Kegiatan</h2>
      </div>

      <div class="overflow-x-auto">
        <div class="min-w-[720px]">
          <!-- Header -->
          <div class="grid grid-cols-[150px_150px_1fr_130px_80px_140px] gap-4 py-2 border-b text-sm text-zinc-500">
            <div>Foto</div>
            <div>Judul</div>
            <div>Deskripsi</div>
            <div>Tanggal</div>
            <div>Waktu</div>
            <div class="text-center">Aksi</div>
          </div>

          <!-- Daftar Kegiatan -->
          <div v-for="k in kegiatans" :key="k.id_kegiatan"
               class="grid grid-cols-[150px_150px_1fr_130px_80px_140px] gap-4 items-center py-4 border-b text-sm text-zinc-800">
            
            <img :src="`/storage/${k.galeris[0]?.foto}`" :alt="k.judul"
                 class="object-cover rounded w-[150px] h-[90px]" />

            <div class="font-medium">{{ k.judul }}</div>
            <div class="truncate">{{ truncateText(k.deskripsi, 80) }}</div>
            <div>{{ formatTanggal(k.tanggal) }}</div>
            <div>{{ formatWaktu(k.waktu) }}</div>

            <div class="flex gap-2 justify-center">
              <button
                @click="openModal(k)"
                class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 text-xs rounded"
                title="Detail">
                Detail
              </button>

              <button
                @click="validateActivity(k.id_kegiatan)"
                class="p-1 text-green-600 hover:text-green-800"
                title="Validasi">
                ✔
              </button>

              <button
                @click="rejectActivity(k.id_kegiatan)"
                class="p-1 text-red-600 hover:text-red-800"
                title="Tolak">
                ❌
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal -->
      <transition name="fade">
        <div v-if="showModal" class="fixed inset-0 z-50 bg-black/50 flex justify-center items-start overflow-y-auto">
          <div class="bg-white w-full max-w-4xl mx-4 my-8 p-6 rounded-xl relative shadow-lg max-h-[90vh] overflow-y-auto">
            <button @click="closeModal" class="absolute top-3 right-3 text-gray-600 hover:text-black text-xl">&times;</button>
            <div v-if="selectedKegiatan">
              <h2 class="text-xl font-bold text-center mb-2">{{ selectedKegiatan.judul }}</h2>
              <div class="text-sm text-center text-gray-500 mb-4">
                <span>{{ formatTanggal(selectedKegiatan.tanggal) }}</span> |
                <span>{{ formatWaktu(selectedKegiatan.waktu) }}</span>
              </div>

              <div class="relative w-full max-w-2xl mx-auto mb-6">
                <img :src="`/storage/${selectedKegiatan.galeris[currentIndex]?.foto}`" alt="Foto"
                     class="rounded-xl w-full h-[266px] object-cover transition duration-500" />
                <div class="absolute bottom-2 left-1/2 -translate-x-1/2 flex gap-1">
                  <span v-for="(g,i) in selectedKegiatan.galeris" :key="i" 
                        class="w-2 h-2 rounded-full"
                        :class="currentIndex === i ? 'bg-white' : 'bg-white/50'"></span>
                </div>
              </div>

              <p class="text-justify text-sm text-gray-700 whitespace-pre-line">
                {{ selectedKegiatan.deskripsi }}
              </p>

              <div class="mt-4 text-center">
                <a v-if="selectedKegiatan.laporan" :href="`/storage/${selectedKegiatan.laporan}`" target="_blank"
                   class="px-3 py-1.5 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 transition">
                  Lihat Laporan
                </a>
              </div>
            </div>
          </div>
        </div>
      </transition>
    </section>
  </AdminLayout>
</template>

<style scoped>
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
</style>