<script>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Head } from '@inertiajs/vue3'
import ChartAnggota from '@/Components/ChartAnggota.vue'
import ChartKegiatan from '@/Components/ChartKegiatan.vue'
import ChartGrupJenis from '@/Components/ChartGrupJenis.vue'
import StatCard from '@/Components/StatCard.vue'

export default {
  components: {
    AdminLayout,
    Head,
    StatCard,
    ChartAnggota,
    ChartKegiatan,
    ChartGrupJenis,
  },

  props: {
    totalMasyarakat: Number,
    totalFasilitator: Number,
    totalGrup: Number,
    anggotaTerbanyak: Array,
    kegiatanPerGrup: Array,
    grupPerJenis: Array,
    jumlahKegiatanBulanan: Number,
    selectedMonth: Number,
    selectedYear: Number,
  },

  data() {
    return {
      selectedMonthLocal: this.selectedMonth,
      selectedYearLocal: this.selectedYear,
    }
  },

  computed: {
    months() {
      return Array.from({ length: 12 }, (_, i) =>
        new Intl.DateTimeFormat('id-ID', { month: 'long' }).format(new Date(2000, i))
      )
    },
    years() {
      const currentYear = new Date().getFullYear()
      return Array.from({ length: 5 }, (_, i) => currentYear - i)
    }
  },

  methods: {
    fetchKegiatanByMonth() {
      this.$inertia.get('/admin', {
        month: this.selectedMonthLocal,
        year: this.selectedYearLocal
      }, {
        preserveState: true,
        preserveScroll: true
      });
    }
  }
}
</script>

<template>
  <AdminLayout>

    <Head title="Dashboard" />

    <!-- Statistik -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
      <StatCard icon="👥" label="Total Masyarakat Dampingan" :value="totalMasyarakat" />
      <StatCard icon="🧑‍🏫" label="Total Fasilitator" :value="totalFasilitator" />
      <StatCard icon="🏠" label="Total Grup Dampingan" :value="totalGrup" />
    </div>

    <!-- Tabel dan Chart Anggota -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4">
      <!-- Tabel Grup -->
      <div class="lg:col-span-2 bg-white p-4 rounded-lg shadow-md">
        <h2 class="text-base font-semibold mb-3">Grup Dampingan MPM Muhammadiyah</h2>
        <div class="overflow-auto max-h-64">
          <table class="min-w-full text-sm text-left border-collapse rounded-lg overflow-hidden shadow-sm">
            <thead>
              <tr class="bg-sky-600 text-white">
                <th class="p-3">Grup Dampingan</th>
                <th class="p-3">Bidang Dampingan</th>
                <th class="p-3">Jumlah Masyarakat</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(data, i) in anggotaTerbanyak" :key="i" class="border-b">
                <td class="p-2">{{ data.nama_grup_dampingan }}</td>
                <td class="p-2">{{ data.nama_bidang }}</td>
                <td class="p-2">{{ data.jumlah_anggota }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Chart Anggota -->
      <div class="bg-white p-4 rounded-lg shadow-md flex flex-col items-center justify-center">
        <h3 class="text-sm font-medium text-gray-700 mb-3 text-center">Statistik Masyarakat per Grup</h3>
        <div class="w-full h-48">
          <ChartAnggota :anggotaData="anggotaTerbanyak" />
        </div>
      </div>
    </div>

    <!-- Chart Kegiatan dan Grup Jenis -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
      <!-- Chart Kegiatan -->
      <div class="bg-white p-4 rounded-lg shadow-md">
        <div class="flex justify-between items-center mb-2">
          <h3 class="text-sm font-medium text-gray-700">Jumlah Kegiatan per Grup</h3>
          <div class="flex gap-2">
            <select v-model="selectedMonthLocal" @change="fetchKegiatanByMonth"
              class="border text-sm rounded px-7 py-1">
              <option v-for="(month, index) in months" :key="index" :value="index + 1">{{ month }}</option>
            </select>
            <select v-model="selectedYearLocal" @change="fetchKegiatanByMonth" class="border text-sm rounded px-7 py-1">
              <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
            </select>
          </div>
        </div>

        <p class="text-gray-600 text-sm mb-2">
          Total kegiatan bulan ini: <span class="font-semibold">{{ jumlahKegiatanBulanan }}</span>
        </p>

        <div class="w-full h-48">
          <div v-if="kegiatanPerGrup.length === 0" class="flex items-center justify-center h-full text-gray-500 italic">
            📭 Kegiatan tidak ditemukan pada bulan ini.
          </div>
          <ChartKegiatan v-else :dataKegiatan="kegiatanPerGrup" />
        </div>
      </div>

      <!-- Chart Grup per Jenis -->
      <div class="bg-white p-4 rounded-lg shadow-md flex flex-col items-center justify-center">
        <h3 class="text-sm font-medium text-gray-700 mb-3 text-center">Jumlah Grup per Jenis Dampingan</h3>
        <div class="w-full h-48">
          <ChartGrupJenis :dataGrup="grupPerJenis" />
        </div>
      </div>
    </div>
  </AdminLayout>
</template>