<script>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Head } from '@inertiajs/vue3'

export default {
  components: {
    AdminLayout,
    Head,
  },

  props: {
    totalMasyarakat: Number,
    totalFasilitator: Number,
    totalGrup: Number,
    anggotaTerbanyak: Array,
  }
}
</script>

<template>
  <AdminLayout>

    <Head title="Dashboard" />
    <!-- Grid Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
      <div class="bg-white p-4 rounded-lg shadow-md flex items-center">
        <span class="text-3xl mr-4">👥</span>
        <div>
          <h3 class="text-gray-500 text-sm">Total Masyarakat Dampingan</h3>
          <p class="text-xl font-bold">{{ totalMasyarakat }}</p>
        </div>
      </div>
      <div class="bg-white p-4 rounded-lg shadow-md flex items-center">
        <span class="text-3xl mr-4">🧑‍🏫</span>
        <div>
          <h3 class="text-gray-500 text-sm">Total Fasilitator</h3>
          <p class="text-xl font-bold">{{ totalFasilitator }}</p>
        </div>
      </div>
      <div class="bg-white p-4 rounded-lg shadow-md flex items-center">
        <span class="text-3xl mr-4">🏠</span>
        <div>
          <h3 class="text-gray-500 text-sm">Total Grup Dampingan</h3>
          <p class="text-xl font-bold">{{ totalGrup }}</p>
        </div>
      </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white p-6 rounded-lg shadow-md">
      <h2 class="text-lg font-bold mb-4">Grup Dampingan MPM Muhammadiyah</h2>
      <div class="overflow-x-auto">
        <table class="min-w-full border-collapse rounded-lg overflow-hidden shadow-sm">
          <thead>
            <tr class="bg-gray-400 text-white">
              <th class="p-3 text-left">Grup Dampingan</th>
              <th class="p-3 text-left">Bidang Dampingan</th>
              <th class="p-3 text-left">Jumlah Masyarakat</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(data, index) in anggotaTerbanyak" :key="index" class="border-b">
              <td class="p-3">{{ data.nama_grup_dampingan }}</td>
              <td class="p-3">{{ data.nama_bidang }}</td>
              <td class="p-3">{{ data.jumlah_anggota }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AdminLayout>
</template>