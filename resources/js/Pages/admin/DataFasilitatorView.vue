<script>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';
import Multiselect from "@vueform/multiselect";
import '@vueform/multiselect/themes/default.css';
import * as XLSX from 'xlsx';
import { saveAs } from 'file-saver';
import axios from 'axios';

export default {
  components: { AdminLayout, Head, Multiselect },

  computed: {
    fasilitators() {
      return this.$page.props.data || [];
    },
    availableDampinganList() {
      const allBidangs = this.fasilitators.flatMap(f =>
        f.bidangs.map(b => b.nama_bidang)
      );
      return [...new Set(allBidangs)];
    },
    filteredFasilitators() {
      if (!this.selectedDampingan) return this.fasilitators;
      return this.fasilitators.filter(f =>
        f.bidangs.some(b => b.nama_bidang === this.selectedDampingan)
      );
    }
  },

  data() {
    return {
      showPopup: false,
      showPopupHapus: false,
      bidangList: [],
      newBidang: '',
      selectedDampingan: null,
      selectedFasilitatorId: null,
    };
  },

  mounted() {
    // Load daftar bidang via API
    axios.get(route('bidang.index'))
      .then(res => {
        this.bidangList = res.data.data;
      })
      .catch(err => console.error(err));
  },

  methods: {
    tambahBidang() {
      const nama = this.newBidang.trim();
      if (!nama) return;

      axios.post(route('bidang.store'), { nama_bidang: nama })
        .then(res => {
          this.bidangList.push(res.data.data);
          this.newBidang = '';
          alert('Bidang baru berhasil ditambahkan');
        })
        .catch(err => {
          console.error(err);
          alert('Gagal menambahkan bidang');
        });
    },

    hapusBidang(index) {
      const bidang = this.bidangList[index];
      this.$inertia.delete(
        route('bidang.destroy', bidang.id_bidang),
        {}, // no data payload
        {
          preserveState: true,
          preserveScroll: true,
          onSuccess: () => {
            this.bidangList.splice(index, 1);
            alert('Bidang berhasil dihapus');
          },
          onError: () => {
            alert('Gagal menghapus bidang');
          }
        }
      );
    },

    downloadExcel() {
      const exportData = this.filteredFasilitators.map((f, index) => ({
        'No': index + 1,
        'Nama Lengkap': f.name,
        'Nomor Telepon': f.nomor_telepon,
        'Alamat': f.alamat,
        'Email': f.email,
        'Bidang Dampingan': f.bidangs.map(b => b.nama_bidang).join(', ')
      }));
      const ws = XLSX.utils.json_to_sheet(exportData);
      const wb = XLSX.utils.book_new();
      XLSX.utils.book_append_sheet(wb, ws, 'Data');
      const buf = XLSX.write(wb, { bookType: 'xlsx', type: 'array' });
      saveAs(new Blob([buf]), 'data-fasilitator.xlsx');
    },

    deleteItem(id) {
      this.$inertia.delete(route('fasilitator.destroy', id));
    }
  }
};
</script>

<template>
  <AdminLayout>

    <Head title="Data Fasilitator" />

    <div class="flex bg-gray-100 overflow-auto">
      <main class="flex-1">
        <div class="bg-white rounded-lg shadow-md p-4">
          <div class="flex justify-between mb-2">
            <h2 class="text-xl font-bold">Data Fasilitator</h2>
            <div class="flex space-x-2">
              <a :href="route('fasilitator.create')" class="bg-blue-500 text-white px-3 py-2 rounded">+ Tambah</a>
              <button @click="downloadExcel" class="bg-green-500 text-white px-3 py-2 rounded">🖨 Cetak</button>
            </div>
          </div>

          <div class="flex flex-wrap gap-4 mb-4">
            <div class="w-1 min-w-[250px]">
              <Multiselect v-model="selectedDampingan" :options="availableDampinganList"
                placeholder="Pilih Bidang Dampingan" :searchable="true" :clearable="true" class="w-full" />
            </div>
            <button @click="showPopup = true" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-00">⚙ Kelola
              Bidang</button>
          </div>

          <div class="overflow-auto rounded-lg">
            <table class="w-full min-w-[600px] border-collapse">
              <thead>
                <tr class="bg-gray-200 text-center">
                  <th class="border p-2">No</th>
                  <th class="border p-2">Nama</th>
                  <th class="border p-2">Telepon</th>
                  <th class="border p-2">Alamat</th>
                  <th class="border p-2">Email</th>
                  <th class="border p-2">Bidang</th>
                  <th class="border p-2">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(f, index) in filteredFasilitators" :key="f.id">
                  <td class="border p-2 text-center">{{ index + 1 }}</td>
                  <td class="border p-2">{{ f.name }}</td>
                  <td class="border p-2">{{ f.nomor_telepon }}</td>
                  <td class="border p-2">{{ f.alamat }}</td>
                  <td class="border p-2">{{ f.email }}</td>
                  <td class="border p-2">
                    <span v-for="(b, i) in f.bidangs" :key="i">{{ b.nama_bidang }}<span v-if="i < f.bidangs.length - 1">,
                      </span></span>
                  </td>
                  <td class="border p-2 text-center space-x-1">
                    <a :href="route('fasilitator.edit', f.id)" class="text-blue-500">✏️</a>
                    <button @click="selectedFasilitatorId = f.id; showPopupHapus = true"
                      class="text-red-500">🗑️</button>
                  </td>
                </tr>
                <tr v-if="filteredFasilitators.length === 0">
                  <td colspan="7" class="border p-4 text-center text-gray-500">
                    Tidak ada data yang sesuai
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </main>
    </div>

    <div v-if="showPopupHapus" class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center">
      <div class="bg-white p-6 rounded shadow-lg w-80 text-center">
        <p class="text-lg font-semibold">Hapus data ini?</p>
        <div class="mt-4 flex justify-center space-x-4">
          <button @click="deleteItem(selectedFasilitatorId); showPopupHapus = false"
            class="px-4 py-2 bg-red-600 text-white rounded">Ya</button>
          <button @click="showPopupHapus = false" class="px-4 py-2 bg-gray-300 rounded">Batal</button>
        </div>
      </div>
    </div>

    <!-- Kelola Bidang -->
    <div v-if="showPopup" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50">
      <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-md">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-xl font-bold">List Bidang</h3>
          <button @click="showPopup = false" class="text-gray-600 hover:text-gray-900">✖</button>
        </div>
        <div class="flex mb-4 gap-2">
          <input v-model="newBidang" type="text" placeholder="Nama bidang baru"
            class="flex-1 border rounded px-3 py-2" />
          <button @click="tambahBidang" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">+
            Tambah</button>
        </div>
        <div class="overflow-y-auto max-h-60 border rounded">
          <table class="w-full border-collapse">
            <thead>
              <tr class="bg-gray-100">
                <th class="p-2 border">Nama Bidang</th>
                <th class="p-2 border text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(b, i) in bidangList" :key="i">
                <td class="p-2 border">{{ b.nama_bidang || b }}</td>
                <td class="p-2 border text-center"><button @click="hapusBidang(i)"
                    class="text-red-500 hover:underline">Hapus</button></td>
              </tr>
              <tr v-if="bidangList.length === 0">
                <td colspan="2" class="p-2 border text-center text-gray-500">Belum ada bidang</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
