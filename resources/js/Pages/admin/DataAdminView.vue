<script>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Multiselect from '@vueform/multiselect';
import { Head } from '@inertiajs/vue3';

export default {
  components: {
    Multiselect,
    AdminLayout,
    Head,
  },

  data() {
    return {
      isMenuOpen: false,
      isDropdownOpen: false,
      showPopup: false,
      showPopupHapus: false,

      searchQuery: '',

      selectedProvinsi: null,
      selectedKabupaten: null,
      selectedKecamatan: null,
      selectedDampingan: null,
      provinsiList: ['Jawa Tengah', 'Jawa Barat', 'Jawa Timur'],
      kabupatenList: ['Semarang', 'Bandung', 'Surabaya'],
      kecamatanList: ['Tembalang', 'Cibadak', 'Rungkut'],
      dampinganList: ['Dampingan A', 'Dampingan B'],

      admins: [
        {
          id: 1,
          name: 'Ervita',
          phone: '(225) 555-0118',
          location: 'Bantul',
          email: 'Ervita@gmail.com',
        },
        {
          id: 2,
          name: 'Fevy',
          phone: '(205) 555-0100',
          location: 'Bantul',
          email: 'Fevy@gmail.com',
        },
        {
          id: 3,
          name: 'Fica',
          phone: '(302) 555-0107',
          location: 'Bantul',
          email: 'Fica@gmail.com',
        },
        {
          id: 4,
          name: 'Dinda',
          phone: '(252) 555-0126',
          location: 'Kulon Progo',
          email: 'Dinda@gmail.com',
        },
        {
          id: 5,
          name: 'Aulia',
          phone: '(629) 555-0129',
          location: 'Kulon Progo',
          email: 'Aulia@gmail.com',
        },
        {
          id: 6,
          name: 'Jessica',
          phone: '(406) 555-0120',
          location: 'Kulon Progo',
          email: 'Jessica@gmail.com',
        },
        {
          id: 7,
          name: 'Isna',
          phone: '(208) 555-0112',
          location: 'Gunung Kidul',
          email: 'Isna@gmail.com',
        },
        {
          id: 8,
          name: 'Imelda',
          phone: '(704) 555-0127',
          location: 'Gunung Kidul',
          email: 'Imelda@gmail.com',
        },
      ],
    }
  },
  computed: {
    filteredDaerah() {
      const daerahList = [...new Set(this.admins.map(admin => admin.location))] // Ambil daftar unik lokasi
      return daerahList.filter(daerah =>
        daerah.toLowerCase().includes(this.searchQuery.toLowerCase()),
      )
    },
  },
  mounted() {
    document.addEventListener('click', this.closeDropdown)
  },
  beforeUnmount() {
    document.removeEventListener('click', this.closeDropdown)
  },
  methods: {
    toggleDropdown(event) {
      event.stopPropagation() // Menghentikan event bubbling agar tidak langsung tertutup
      this.isDropdownOpen = !this.isDropdownOpen
    },
    selectDaerah(daerah) {
      console.log('Dipilih:', daerah)
      this.isDropdownOpen = false
    },
    closeDropdown(event) {
      if (!this.$el.contains(event.target)) {
        this.isDropdownOpen = false
      }
    },
    tambahData() {
      console.log('Tombol ditekan!')
    },
  },
}
</script>

<template>
  <AdminLayout>
    <Head title="Data Admin" />
    <div class="flex h-screen bg-gray-100 overflow-auto">
      <main class="flex-1 p-6">
        <div class="bg-white shadow-md rounded p-4">
          <div class="flex justify-between mb-2">
            <h2 class="text-xl font-bold">Data Admin</h2>
            <div class="flex space-x-2">
              <a href="TambahAdmin" class="bg-blue-500 text-white px-3 py-2 rounded">+ Tambah</a>
              <button class="bg-green-500 text-white px-3 py-2 rounded">
                🖨 Cetak
              </button>
            </div>
          </div>

          <!-- Wrapper untuk Dropdown (Pindahkan ke bawah tombol Tambah) -->
          <div class="flex flex-wrap gap-4 mb-8">
            <!-- Dropdown Provinsi -->
            <div class="w-1 min-w-[200px]">
              <Multiselect v-model="selectedProvinsi" :options="provinsiList" placeholder="Pilih Provinsi"
                :searchable="true" :class="{ 'transparent-dropdown': showPopup }" class="w-full" />
            </div>

            <!-- Dropdown Kabupaten -->
            <div class="w-1 min-w-[200px]">
              <Multiselect v-model="selectedKabupaten" :options="kabupatenList" placeholder="Pilih Kabupaten"
                :searchable="true" :class="{ 'transparent-dropdown': showPopup }" class="w-full" />
            </div>

            <!-- Dropdown Kecamatan -->
            <div class="w-1 min-w-[200px]">
              <Multiselect v-model="selectedKecamatan" :options="kecamatanList" placeholder="Pilih Kecamatan"
                :searchable="true" :class="{ 'transparent-dropdown': showPopup }" class="w-full" />
            </div>

            <!-- Dropdown Dampingan -->
            <div class="w-1 min-w-[200px]">
              <Multiselect v-model="selectedDampingan" :options="dampinganList" placeholder="Pilih Dampingan"
                :searchable="true" :class="{ 'transparent-dropdown': showPopup }" class="w-full" />
            </div>
          </div>
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
                <tr v-for="admin in admins" :key="admin.id" class="text-center">
                  <td class="border p-2">{{ admin.id }}</td>
                  <td class="border p-2">{{ admin.name }}</td>
                  <td class="border p-2">{{ admin.phone }}</td>
                  <td class="border p-2">{{ admin.location }}</td>
                  <td class="border p-2">{{ admin.email }}</td>
                  <td class="border p-2">
                    <a href="EditAdmin" class="text-blue-500 mr-2">✏️</a>
                    <button @click="showPopupHapus = true" class="text-red-500">
                      🗑️
                    </button>

                    <!-- Popup Modal -->
                    <div v-if="showPopupHapus"
                      class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-10">
                      <div class="bg-white p-6 rounded-lg shadow-lg w-80 text-center">
                        <p class="text-lg font-semibold">
                          Apakah Anda yakin ingin menghapus?
                        </p>
                        <div class="flex justify-center gap-4 mt-4">
                          <a href="DataAdmin" @click="deleteItem"
                            class="px-4 py-2 bg-red-600 text-white rounded-md">Ya</a>
                          <a href="DataAdmin" @click="showPopupHapus = false"
                            class="px-4 py-2 bg-gray-300 rounded-md">Batal</a>
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
      <div v-if="showPopup" class="absolute left-4 bottom-10 bg-white p-4 rounded shadow-md text-center w-56">
        <a href="DataAdmin" class="bg-green-500 text-white px-4 py-2 rounded mb-2 block w-full"
          @click="showPopup = false">Batal</a>
        <button class="bg-red-500 text-white px-4 py-2 rounded block w-full" @click="logout">
          Keluar
        </button>
      </div>
    </div>
  </AdminLayout>
</template>

<style scoped>
/* Batasan tinggi dropdown agar bisa di-scroll */
.max-h-40 {
  max-height: 10rem;
}

.multiselect[disabled] {
  background-color: #e0e0e0 !important;
  cursor: not-allowed;
  opacity: 0.6;
}

.transparent-dropdown {
  opacity: 0.5;
  pointer-events: none;
}

.transparent-dropdown {
  background-color: rgba(255,
      255,
      255,
      0.2) !important;
  /* Sesuaikan transparansi */
  color: rgb(66, 65, 65) !important;
  /* Agar teks tetap terbaca */
  border: 1px solid rgba(255, 255, 255, 0.15);
  /* Sesuaikan border agar tidak menonjol */
}
</style>
