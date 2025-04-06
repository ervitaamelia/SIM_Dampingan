<!-- Halaman Utama Anda -->
<template>
  <AdminLayout>
    <Head title="Data Fasilitator" />
    <div class="flex h-screen bg-gray-100 overflow-auto">
      <main class="flex-1 p-6">
        <div class="bg-white shadow-md rounded p-4">
          <div class="flex justify-between mb-4">
            <h2 class="text-xl font-bold">Data Fasilitator</h2>
            <div class="flex space-x-2">
              <a
                href="TambahFasilitator"
                class="bg-blue-500 text-white px-3 py-2 rounded"
                >+ Tambah</a
              >
              <button class="bg-green-500 text-white px-3 py-2 rounded">
                🖨 Cetak
              </button>
            </div>
          </div>

          <!-- Gunakan Komponen Filter -->
          <FilterComponent :showPopup="showPopup" />

          <!-- Tabel Data Fasilitator -->
          <div class="overflow-auto">
            <table class="w-full min-w-[600px] border-collapse">
              <thead>
                <tr class="bg-gray-200">
                  <th class="border p-2">ID</th>
                  <th class="border p-2">Nama Lengkap</th>
                  <th class="border p-2">No Telepon</th>
                  <th class="border p-2">Domisili</th>
                  <th class="border p-2">Email</th>
                  <th class="border p-2">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="fasilitator in fasilitators"
                  :key="fasilitator.id"
                  class="text-center"
                >
                  <td class="border p-2">{{ fasilitator.id }}</td>
                  <td class="border p-2">{{ fasilitator.nama }}</td>
                  <td class="border p-2">{{ fasilitator.telepon }}</td>
                  <td class="border p-2">{{ fasilitator.domisili }}</td>
                  <td class="border p-2">{{ fasilitator.email }}</td>
                  <td class="border p-2">
                    <a href="EditFasilitator" class="text-blue-500 mx-2">✏️</a>
                    <button @click="showPopupHapus = true" class="text-red-500">
                      🗑️
                    </button>

                    <!-- Popup Modal -->
                    <div
                      v-if="showPopupHapus"
                      class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-10"
                    >
                      <div
                        class="bg-white p-6 rounded-lg shadow-lg w-80 text-center"
                      >
                        <p class="text-lg font-semibold">
                          Apakah Anda yakin ingin menghapus?
                        </p>
                        <div class="flex justify-center gap-4 mt-4">
                          <a
                            href="DataFasilitator"
                            @click="deleteItem"
                            class="px-4 py-2 bg-red-600 text-white rounded-md"
                            >Ya</a
                          >
                          <a
                            href="DataFasilitator"
                            @click="showPopupHapus = false"
                            class="px-4 py-2 bg-gray-300 rounded-md"
                            >Batal</a
                          >
                        </div>
                      </div>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </main>

      <!-- Popup -->
      <div
        v-if="showPopup"
        class="absolute left-4 bottom-10 bg-white p-4 rounded shadow-md text-center w-56"
      >
        <a
          href="DataFasilitator"
          class="bg-green-500 text-white px-4 py-2 rounded mb-2 block w-full"
          @click="showPopup = false"
          >Batal</a
        >
        <button
          class="bg-red-500 text-white px-4 py-2 rounded block w-full"
          @click="logout"
        >
          Keluar
        </button>
      </div>
    </div>
  </AdminLayout>
</template>

<script>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';
import FilterComponent from '@/Components/Filter.vue'

export default {
  components: {
    AdminLayout,
    Head,
    FilterComponent,
  },
  data() {
    return {
      isMenuOpen: false,
      isDropdownOpen: false,
      showPopup: false,
      showPopupHapus: false,

      searchQuery: '',

      fasilitators: [
        {
          id: 1,
          nama: 'Ervita',
          telepon: '(225) 555-0118',
          domisili: 'Bantul',
          email: 'Ervita@gmail.com',
        },
        // ... data fasilitator lainnya
      ],
    };
  },
  computed: {
    filteredDaerah() {
      const daerahList = [...new Set(this.fasilitators.map(f => f.domisili))];
      return daerahList.filter(daerah =>
        daerah.toLowerCase().includes(this.searchQuery.toLowerCase()),
      );
    },
  },
  methods: {
    toggleDropdown() {
      this.isDropdownOpen = !this.isDropdownOpen;
    },
    selectDaerah(daerah) {
      console.log('Dipilih:', daerah);
      this.isDropdownOpen = false;
    },
    closeDropdown() {
      setTimeout(() => {
        this.isDropdownOpen = false;
      }, 200);
    },
    tambahData() {
      console.log('Tombol ditekan!');
    },
  },
};
</script>