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
          no: 1,
          name: 'Ervita',
          bidang: 'Tani',
          provinsi: 'Jawa Tengah',
          kabupaten: 'Surakarta',
          daerah: 'Pusat: Yogyakarta',
          fasilitator: 'Lala, Jeje, Amel',
          tempatlahir: 'Surakarta',
          tanggallahir: '01-01-2000',
          jeniskelamin: 'Perempuan',
          agama: 'Islam',
          pekerjaan: 'Petani',
          phone: '081234567890',
          location: 'Bantul',
        },
        {
          no: 2,
          name: 'Fevy',
          bidang: 'Tani',
          provinsi: 'Jawa Tengah',
          kabupaten: 'Surakarta',
          daerah: 'Pusat: Yogyakarta',
          fasilitator: 'Lala, Jeje, Amel',
          tempatlahir: 'Semarang',
          tanggallahir: '02-02-2001',
          jeniskelamin: 'Laki-laki',
          agama: 'Kristen',
          pekerjaan: 'Petani',
          phone: '081298765432',
          location: 'Kulon Progo',
        },
      ],
    }
  },
  methods: {
    printRow(admin) {
      let printContent = `
        <html>
          <head>
            <title>Print Data</title>
            <style>
              table { border-collapse: collapse; width: 100%; }
              th, td { border: 1px solid black; padding: 8px; text-align: left; }
            </style>
          </head>
          <body>
            <h2>Data Dampingan</h2>
            <table>
              <tr><th>No Anggota</th><td>${admin.no}</td></tr>
              <tr><th>Nama Lengkap</th><td>${admin.name}</td></tr>
              <tr><th>Tempat Lahir</th><td>${admin.tempatlahir}</td></tr>
              <tr><th>Tanggal Lahir</th><td>${admin.tanggallahir}</td></tr>
              <tr><th>Jenis Kelamin</th><td>${admin.jeniskelamin}</td></tr>
              <tr><th>Agama</th><td>${admin.agama}</td></tr>
              <tr><th>Pekerjaan</th><td>${admin.pekerjaan}</td></tr>
              <tr><th>No Telepon</th><td>${admin.phone}</td></tr>
              <tr><th>Alamat</th><td>${admin.location}</td></tr>
            </table>
            <script>
              window.onload = function() { 
                window.print(); 
                window.close(); 
              };
            <\/script>
          </body>
        </html>
      `

      let newWindow = window.open('', '_blank')
      newWindow.document.write(printContent)
      newWindow.document.close()
    },

    toggleDropdown(event) {
      event.stopPropagation() // Agar tidak tertutup langsung saat diklik
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
  },
  tambahData() {
    console.log('Tombol ditekan!')
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
}
</script>

<template>
  <AdminLayout>
    <Head title="Data Dampingan" />
    <div class="flex h-screen bg-gray-100 overflow-auto">
      <main class="flex-1 p-6">
        <div class="bg-white shadow-md rounded p-4">
          <div class="flex justify-between mb-4">
            <h2 class="text-xl font-bold">Data Dampingan</h2>
            <div class="flex space-x-2">
              <a
                href="TambahDampingan"
                class="bg-blue-500 text-white px-3 py-2 rounded"
                >+ Tambah</a
              >
              <button class="bg-green-500 text-white px-3 py-2 rounded">
                🖨 Cetak
              </button>
            </div>
          </div>

          <!-- Wrapper untuk sejajarkan dropdown dan memberi jarak -->
          <!-- Dropdown yang Rapi -->
          <div class="flex flex-wrap gap-4 mb-4">
            <!-- Dropdown Provinsi -->
            <div class="w-1 min-w-[200px]">
              <Multiselect
                v-model="selectedProvinsi"
                :options="provinsiList"
                placeholder="Pilih Provinsi"
                :searchable="true"
                :class="{ 'transparent-dropdown': showPopup }"
                class="w-full"
              />
            </div>

            <!-- Dropdown Kabupaten -->
            <div class="w-1 min-w-[200px]">
              <Multiselect
                v-model="selectedKabupaten"
                :options="kabupatenList"
                placeholder="Pilih Kabupaten"
                :searchable="true"
                :class="{ 'transparent-dropdown': showPopup }"
                class="w-full"
              />
            </div>

            <!-- Dropdown Kecamatan -->
            <div class="w-1 min-w-[200px]">
              <Multiselect
                v-model="selectedKecamatan"
                :options="kecamatanList"
                placeholder="Pilih Kecamatan"
                :searchable="true"
                :class="{ 'transparent-dropdown': showPopup }"
                class="w-full"
              />
            </div>

            <!-- Dropdown Dampingan -->
            <div class="w-1 min-w-[200px]">
              <Multiselect
                v-model="selectedDampingan"
                :options="dampinganList"
                placeholder="Pilih Dampingan"
                :searchable="true"
                :class="{ 'transparent-dropdown': showPopup }"
                class="w-full"
              />
            </div>
          </div>
          <div class="overflow-auto">
            <table class="w-full min-w-[600px] border-collapse">
              <thead>
                <tr class="bg-gray-200">
                  <th class="border p-2">ID</th>
                  <th class="border p-2">Nama Dampingan</th>
                  <th class="border p-2">Bidang Dampingan</th>
                  <th class="border p-2">Provinsi</th>
                  <th class="border p-2">Kabupaten</th>
                  <th class="border p-2">Daerah Dampingan</th>
                  <th class="border p-2">Fasilitator</th>
                  <th class="border p-2">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="admin in admins" :key="admin.no" class="text-center">
                  <td class="border p-2">{{ admin.no }}</td>
                  <td class="border p-2">{{ admin.name }}</td>
                  <td class="border p-2">{{ admin.bidang }}</td>
                  <td class="border p-2">{{ admin.provinsi }}</td>
                  <td class="border p-2">{{ admin.kabupaten }}</td>
                  <td class="border p-2">{{ admin.daerah }}</td>
                  <td class="border p-2">{{ admin.fasilitator }}</td>
                  <td class="border p-2">
                    <a href="EditDampingan" class="text-blue-500">✏️</a>
                    <button @click="showPopupHapus = true" class="text-red-500">
                      🗑️
                    </button>

                    <!-- Popup Modal -->
                    <div
                      v-if="showPopupHapus"
                      class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-40"
                    >
                      <div
                        class="bg-white p-6 rounded-lg shadow-lg w-80 text-center"
                      >
                        <p class="text-lg font-semibold">
                          Apakah Anda yakin ingin menghapus?
                        </p>
                        <div class="flex justify-center gap-4 mt-4">
                          <a
                            href="DataDampingan"
                            @click="deleteItem"
                            class="px-4 py-2 bg-red-600 text-white rounded-md"
                            >Ya</a
                          >
                          <a
                            href="DataDampingan"
                            @click="showPopupHapus = false"
                            class="px-4 py-2 bg-gray-300 rounded-md"
                            >Batal</a
                          >
                        </div>
                      </div>
                    </div>

                    <button class="text-green-500" onclick="printRow(this)">
                      🖨️
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </main>
    </div>

    <!-- Popup -->
    <div
      v-if="showPopup"
      class="absolute left-4 bottom-10 bg-white p-4 rounded shadow-md text-center w-56"
    >
      <button
        class="bg-green-500 text-white px-4 py-2 rounded mb-2 block w-full"
        @click="showPopup = false"
      >
        Batal
      </button>
      <button
        class="bg-red-500 text-white px-4 py-2 rounded block w-full"
        @click="logout"
      >
        Keluar
      </button>
    </div>
  </AdminLayout>
</template>

<style>
body {
  font-family: Arial, sans-serif;
}

/* Ukuran font dropdown */
.multiselect {
  font-size: 14px !important;
}

.multiselect-dropdown {
  font-size: 14px !important;
}

.multiselect-option {
  font-size: 14px !important;
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
  background-color: rgba(
    255,
    255,
    255,
    0.2
  ) !important; /* Sesuaikan transparansi */
  color: rgb(66, 65, 65) !important; /* Agar teks tetap terbaca */
  border: 1px solid rgba(255, 255, 255, 0.15); /* Sesuaikan border agar tidak menonjol */
}
</style>
