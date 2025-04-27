<script>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';
import Multiselect from "@vueform/multiselect";
import '@vueform/multiselect/themes/default.css';
import axios from 'axios';
import * as XLSX from 'xlsx';
import { saveAs } from 'file-saver';

export default {
  components: {
    AdminLayout,
    Head,
    Multiselect,
  },

  computed: {
    fasilitators() {
      return this.$page.props.data || [];
    },
    // Dapatkan daftar bidang dampingan yang benar-benar ada di data
    availableDampinganList() {
      const allBidangs = this.fasilitators.flatMap(f => 
        f.bidangs.map(b => b.nama_bidang)
      );
      return [...new Set(allBidangs)]; // Hapus duplikat
    },
    filteredFasilitators() {
      if (!this.selectedDampingan) {
        return this.fasilitators;
      }
      
      return this.fasilitators.filter(f => {
        return f.bidangs.some(b => b.nama_bidang === this.selectedDampingan);
      });
    }
  },

  data() {
    return {
      showPopup: false,
      showPopupHapus: false,

      selectedDampingan: null,
      selectedFasilitatorId: null,

      // Tidak perlu dampinganList dari API lagi
    };
  },

  methods: {
    deleteItem(id) {
      this.$inertia.delete(route('fasilitator.destroy', id));
    },

    downloadExcel() {
      const filteredData = this.filteredFasilitators;

      const exportData = filteredData.map((f) => ({
        'ID': f.id,
        'Nama Lengkap': f.name,
        'Nomor Telepon': f.nomor_telepon,
        'Alamat': f.alamat,
        'Email': f.email,
        'Bidang Dampingan': f.bidangs.map(b => b.nama_bidang).join(', '),
      }));

      const worksheet = XLSX.utils.json_to_sheet(exportData);
      const workbook = XLSX.utils.book_new();
      XLSX.utils.book_append_sheet(workbook, worksheet, 'Data Fasilitator');

      const excelBuffer = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });
      const file = new Blob([excelBuffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });

      const fileName = 'data-fasilitator.xlsx';
      saveAs(file, fileName);
    },

    formatTanggal(tanggal) {
      if (!tanggal) return '';
      const date = new Date(tanggal);
      const day = String(date.getDate()).padStart(2, '0');
      const month = String(date.getMonth() + 1).padStart(2, '0');
      const year = date.getFullYear();
      return `${day}-${month}-${year}`;
    },
  },

  // Tidak perlu mounted/fetchDampinganList lagi
};
</script>

<template>
  <AdminLayout>
    <Head title="Data Fasilitator" />
    <div class="flex h-screen bg-gray-100 overflow-auto">
      <main class="flex-1 p-6">
        <div class="bg-white shadow-md rounded p-4">
          <div class="flex justify-between mb-4">
            <h2 class="text-xl font-bold">Data Fasilitator</h2>
            <div class="flex space-x-2">
              <a :href="route('fasilitator.create')" class="bg-blue-500 text-white px-3 py-2 rounded">+ Tambah</a>
              <button @click="downloadExcel" class="bg-green-500 text-white px-3 py-2 rounded">🖨 Cetak Excel</button>
            </div>
          </div>

          <!-- Filter Dampingan -->
          <div class="grid grid-cols-1 md:grid-cols-4 gap-4 my-4">
            <Multiselect 
              v-model="selectedDampingan" 
              :options="availableDampinganList" 
              placeholder="Pilih Bidang Dampingan"
              :searchable="true" 
              class="w-full"
              :clearable="true"
            />
          </div>

          <!-- Tabel Data Fasilitator -->
          <div class="overflow-auto">
            <table class="w-full min-w-[600px] border-collapse">
              <thead>
                <tr class="bg-gray-200 text-center">
                  <th class="border p-2">Id</th>
                  <th class="border p-2">Nama Lengkap</th>
                  <th class="border p-2">No. Telepon</th>
                  <th class="border p-2">Alamat</th>
                  <th class="border p-2">Email</th>
                  <th class="border p-2">Bidang Dampingan</th>
                  <th class="border p-2">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="fasilitator in filteredFasilitators" :key="fasilitator.id" class="text-left">
                  <td class="border p-2 text-center">{{ fasilitator.id }}</td>
                  <td class="border p-2">{{ fasilitator.name }}</td>
                  <td class="border p-2">{{ fasilitator.nomor_telepon }}</td>
                  <td class="border p-2">{{ fasilitator.alamat }}</td>
                  <td class="border p-2">{{ fasilitator.email }}</td>
                  <td class="border p-2">
                    <span v-for="(bidang, index) in fasilitator.bidangs" :key="index">
                      {{ bidang.nama_bidang }}<span v-if="index < fasilitator.bidangs.length - 1">, </span>
                    </span>
                  </td>
                  <td class="border p-2 text-center w-20">
                    <a :href="route('fasilitator.edit', fasilitator.id)" class="text-blue-500 mx-2">✏️</a>
                    <button @click="selectedFasilitatorId = fasilitator.id; showPopupHapus = true"
                      class="text-red-500">🗑️</button>
                  </td>
                </tr>
                <tr v-if="filteredFasilitators.length === 0">
                  <td colspan="7" class="border p-2 text-center">
                    {{
                      selectedDampingan 
                        ? `Tidak ada data untuk bidang ${selectedDampingan}`
                        : 'Tidak ada data fasilitator'
                    }}
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Popup Konfirmasi Hapus -->
          <div v-if="showPopupHapus"
            class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-10">
            <div class="bg-white p-6 rounded-lg shadow-lg w-80 text-center">
              <p class="text-lg font-semibold">Apakah Anda yakin ingin menghapus?</p>
              <div class="flex justify-center gap-4 mt-4">
                <button @click="deleteItem(selectedFasilitatorId); showPopupHapus = false"
                  class="px-4 py-2 bg-red-600 text-white rounded-md">
                  Ya
                </button>
                <button @click="showPopupHapus = false" class="px-4 py-2 bg-gray-300 rounded-md">
                  Batal
                </button>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  </AdminLayout>
</template>