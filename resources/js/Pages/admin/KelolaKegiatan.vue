<script>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Head } from '@inertiajs/vue3'

export default {
  components: {
    AdminLayout,
    Head
  },
  data() {
    return {
      kegiatans: this.$page.props.data || [],
      expandedItems: [],
      showModal: false,
      selectedKegiatan: null,
    }
  },
  methods: {
    truncateText(text, length = 120) {
      if (!text) return ''
      return text.length > length ? text.substring(0, length) + '...' : text
    },
    toggleExpand(id) {
      if (this.expandedItems.includes(id)) {
        this.expandedItems = this.expandedItems.filter(e => e !== id)
      } else {
        this.expandedItems.push(id)
      }
    },
    formatTanggal(date) {
      if (!date) return '-'
      return new Date(date).toLocaleDateString('id-ID', {
        day: '2-digit', month: 'long', year: 'numeric'
      })
    },
    formatWaktu(time) {
      if (!time) return '-'
      return time.substring(0, 5)
    },
    openModal(kegiatan) {
      this.selectedKegiatan = kegiatan
      this.showModal = true
    },
    closeModal() {
      this.showModal = false
      this.selectedKegiatan = null
    },
    validateActivity(id) {
      this.$inertia.put(`/admin/kelola-kegiatan/${id}/validasi`)
    },
    rejectActivity(id) {
      this.$inertia.put(`/admin/kelola-kegiatan/${id}/tolak`)
    }
  }
}
</script>

<template>
  <AdminLayout>
    <Head title="Kelola Kegiatan" />

    <section class="p-6 bg-white rounded-lg shadow border border-gray-200">
      <h2 class="text-lg font-semibold mb-4 text-zinc-800">Kelola Kegiatan</h2>

      <!-- Header -->
      <div class="grid grid-cols-[160px_200px_300px_140px_100px_150px_120px] gap-4 py-2 border-b text-sm text-zinc-500">
        <div>Foto</div>
        <div>Judul</div>
        <div>Deskripsi</div>
        <div>Tanggal</div>
        <div>Waktu</div>
        <div class="text-center">Aksi</div>
        <div class="text-center">Status</div>
      </div>

      <!-- Body -->
      <div v-if="kegiatans.length > 0">
        <div v-for="k in kegiatans" :key="k.id_kegiatan"
          class="grid grid-cols-[160px_200px_300px_140px_100px_150px_120px] gap-4 items-start py-4 border-b text-sm text-zinc-800">

          <!-- Foto -->
          <img :src="`/storage/${k.galeris[0]?.foto}`" :alt="k.judul"
            class="object-cover rounded w-[160px] h-[100px]" />

          <!-- Judul -->
          <div class="font-medium">{{ k.judul }}</div>

          <!-- Deskripsi -->
          <div class="text-sm text-zinc-800 leading-relaxed">
            <template v-if="expandedItems.includes(k.id_kegiatan)">
              {{ k.deskripsi }}
              <button @click="toggleExpand(k.id_kegiatan)" class="text-blue-600 text-xs ml-1">Sembunyikan</button>
            </template>
            <template v-else>
              {{ truncateText(k.deskripsi, 120) }}
              <span v-if="k.deskripsi && k.deskripsi.length > 120">
                <button @click="toggleExpand(k.id_kegiatan)" class="text-blue-600 text-xs ml-1">Baca Selengkapnya</button>
              </span>
            </template>
          </div>

          <!-- Tanggal -->
          <div>{{ formatTanggal(k.tanggal) }}</div>

          <!-- Waktu -->
          <div>{{ formatWaktu(k.waktu) }}</div>

          <!-- Aksi -->
          <div class="flex gap-2 justify-center">
            <button @click="openModal(k)" class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 text-xs rounded">
              Detail
            </button>
            <template v-if="$page.props.auth.user.role !== 'superadmin' && $page.props.auth.user.role !== 'Pusat'">
              <button @click="validateActivity(k.id_kegiatan)" class="p-1 text-green-600 hover:text-green-800">✔</button>
              <button @click="rejectActivity(k.id_kegiatan)" class="p-1 text-red-600 hover:text-red-800">❌</button>
            </template>
          </div>

          <!-- Status -->
          <div class="text-center">
            <span v-if="k.status_kegiatan === 'diproses'" class="px-2 py-1 rounded text-xs bg-yellow-300 text-yellow-800">
              Diproses
            </span>
            <span v-else-if="k.status_kegiatan === 'ditolak'" class="px-2 py-1 rounded text-xs bg-red-300 text-red-800">
              Ditolak
            </span>
            <span v-else-if="k.status_kegiatan === 'disetujui'" class="px-2 py-1 rounded text-xs bg-green-300 text-green-800">
              Disetujui
            </span>
            <span v-else class="px-2 py-1 rounded text-xs bg-blue-300 text-gray-600">
              {{ k.status_kegiatan }}
            </span>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else class="py-8 text-center text-gray-500 text-sm">
        Belum ada kegiatan yang bisa dikelola.
      </div>
    </section>

    <!-- Modal -->
    <transition name="fade">
      <div v-if="showModal" class="fixed inset-0 z-50 bg-black/50 flex justify-center items-start overflow-y-auto">
        <div class="bg-white w-full max-w-3xl mx-4 my-8 p-6 rounded-xl relative shadow-lg">
          <button @click="closeModal"
            class="absolute top-3 right-3 text-gray-600 hover:text-black text-3xl font-bold">&times;</button>

          <div v-if="selectedKegiatan">
            <h2 class="text-xl font-bold text-center mb-2">{{ selectedKegiatan.judul }}</h2>
            <div class="text-sm text-center text-gray-500 mb-4">
              <span>{{ formatTanggal(selectedKegiatan.tanggal) }}</span> |
              <span>{{ formatWaktu(selectedKegiatan.waktu) }}</span>
            </div>

            <img :src="`/storage/${selectedKegiatan.galeris[0]?.foto}`" alt="Foto"
              class="rounded-xl w-full h-[280px] object-cover mb-4" />

            <p class="text-justify text-sm text-gray-700 whitespace-pre-line">
              {{ selectedKegiatan.deskripsi }}
            </p>
          </div>
        </div>
      </div>
    </transition>
  </AdminLayout>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
