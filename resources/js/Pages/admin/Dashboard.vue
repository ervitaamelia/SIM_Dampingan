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
  }
}
</script>

<template>
  <AdminLayout>

    <Head title="Dashboard" />

    <!-- Grid Statistik -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
      <StatCard icon="👥" label="Total Masyarakat Dampingan" :value="totalMasyarakat" />
      <StatCard icon="🧑‍🏫" label="Total Fasilitator" :value="totalFasilitator" />
      <StatCard icon="🏠" label="Total Grup Dampingan" :value="totalGrup" />
    </div>

    <!-- Tabel & Statistik Masyarakat -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4">
      <!-- Tabel -->
      <div class="lg:col-span-2 bg-white p-4 rounded-lg shadow-md">
        <h2 class="text-base font-semibold mb-3">Grup Dampingan MPM Muhammadiyah</h2>
        <div class="overflow-auto max-h-64">
          <table class="min-w-full text-sm text-left border-collapse">
            <thead>
              <tr class="bg-sky-600 text-white">
                <th class="p-2">Grup</th>
                <th class="p-2">Bidang</th>
                <th class="p-2">Jumlah</th>
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

      <!-- Diagram Masyarakat -->
      <div class="bg-white p-4 rounded-lg shadow-md flex flex-col items-center justify-center">
        <h3 class="text-sm font-medium text-gray-700 mb-3 text-center">Statistik Masyarakat per Grup</h3>
        <div class="w-full h-48">
          <ChartAnggota :anggotaData="anggotaTerbanyak" />
        </div>
      </div>
    </div>

    <!-- Diagram Kegiatan & Grup per Jenis -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
      <div class="bg-white p-4 rounded-lg shadow-md flex flex-col items-center justify-center">
        <h3 class="text-sm font-medium text-gray-700 mb-3 text-center">Jumlah Kegiatan per Grup</h3>
        <div class="w-full h-48">
          <ChartKegiatan :dataKegiatan="kegiatanPerGrup" />
        </div>
      </div>

      <div class="bg-white p-4 rounded-lg shadow-md flex flex-col items-center justify-center">
        <h3 class="text-sm font-medium text-gray-700 mb-3 text-center">Jumlah Grup per Jenis Dampingan</h3>
        <div class="w-full h-48">
          <ChartGrupJenis :dataGrup="grupPerJenis" />
        </div>
      </div>
    </div>
  </AdminLayout>
</template>