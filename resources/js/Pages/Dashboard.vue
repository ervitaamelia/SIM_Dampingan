<script>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';

export default {
  components: {
    AdminLayout,
    Head
  },
  data() {
    return {
      isMenuOpen: false,
      isDropdownOpen: false,
      showPopup: false, // Tambahkan koma di sini
      dashboardData: [
        { daerah: 'Bantul', jumlah: 30 },
        { daerah: 'Sleman', jumlah: 28 },
        { daerah: 'Gunung Kidul', jumlah: 26 },
        { daerah: 'Sragen', jumlah: 24 },
        { daerah: 'Yogyakarta', jumlah: 22 },
        { daerah: 'Bantul', jumlah: 20 },
        { daerah: 'Surakarta', jumlah: 18 },
      ],
    }
  },
  methods: {
    toggleDropdown() {
      this.isDropdownOpen = !this.isDropdownOpen
    },
  },
  tambahData() {
    console.log('Tombol ditekan!')
  },
}
</script>

<template>
  <AdminLayout>
    <Head title="Dashboard"/>
    <div class="flex h-screen bg-gray-100 overflow-auto">
      <main class="flex-1 p-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mb-6">
          <div class="bg-white p-4 rounded-lg shadow-md flex items-center">
            <span class="text-3xl mr-4">👥</span>
            <div>
              <h3 class="text-gray-500 text-sm">Total Dampingan</h3>
              <p class="text-xl font-bold">5,423</p>
            </div>
          </div>
          <div class="bg-white p-4 rounded-lg shadow-md flex items-center">
            <span class="text-3xl mr-4">🧑‍🏫</span>
            <div>
              <h3 class="text-gray-500 text-sm">Total Fasilitator</h3>
              <p class="text-xl font-bold">1,893</p>
            </div>
          </div>
          <div class="bg-white p-4 rounded-lg shadow-md flex items-center">
            <span class="text-3xl mr-4">💻</span>
            <div>
              <h3 class="text-gray-500 text-sm">Active Now</h3>
              <p class="text-xl font-bold">189</p>
            </div>
          </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
          <h2 class="text-lg font-bold mb-4">Dashboard</h2>
          <div class="overflow-x-auto">
            <table
              class="w-full border-collapse rounded-lg overflow-hidden shadow-sm"
            >
              <thead>
                <tr class="bg-gray-400 text-white">
                  <th class="p-3 text-left">Daerah</th>
                  <th class="p-3 text-left">Jumlah Dampingan</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="(data, index) in dashboardData"
                  :key="index"
                  class="border-b"
                >
                  <td class="p-3">{{ data.daerah }}</td>
                  <td class="p-3">{{ data.jumlah }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </main>
    </div>
  </AdminLayout>
</template>
