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
    <div class="mt-8 bg-white p-6 rounded-2xl shadow-lg">
      <h3 class="text-lg font-semibold mb-4 text-gray-700">Lokasi Kantor PP Muhammadiyah</h3>
      <div class="w-full h-64 rounded-xl overflow-hidden">
        <iframe class="w-full h-full"
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3952.8908589824814!2d110.35557917358054!3d-7.801378177438836!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a578b840bb0f3%3A0xceca19d49f1ef380!2sKantor%20PP%20Muhammadiyah!5e0!3m2!1sid!2sid!4v1747030593743!5m2!1sid!2sid"
          frameborder="0" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
        </iframe>
      </div>
    </div>
  </AdminLayout>
</template>