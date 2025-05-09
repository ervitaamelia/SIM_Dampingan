<script>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Multiselect from '@vueform/multiselect';
import { Head } from '@inertiajs/vue3';
import '@vueform/multiselect/themes/default.css';
import * as XLSX from 'xlsx';
import { saveAs } from 'file-saver';

export default {
  components: {
    Multiselect,
    AdminLayout,
    Head,
  },

  computed: {
    grups() {
      return this.$page.props.data || [];
    },

    availableDampinganList() {
      const allBidangs = this.grups.map(g => g.bidang?.nama_bidang).filter(Boolean);
      return [...new Set(allBidangs)]; // Remove duplicates
    },

    filteredGrups() {
      return this.grups.filter(grup => {
        // Provinsi filter
        const matchProvinsi = this.selectedProvinsi
          ? grup.kode_provinsi === this.selectedProvinsi
          : true;

        // Kabupaten filter
        const matchKabupaten = this.selectedKabupaten
          ? grup.kode_kabupaten === this.selectedKabupaten
          : true;

        // Kecamatan filter
        const matchKecamatan = this.selectedKecamatan
          ? grup.kode_kecamatan === this.selectedKecamatan
          : true;

        // Jenis filter
        const matchJenis = this.selectedJenis
          ? grup.jenis_dampingan === this.selectedJenis
          : true;

        // Bidang dampingan filter
        const bidangMatch = !this.selectedDampingan ||
          (grup.bidang?.nama_bidang === this.selectedDampingan);

        return matchProvinsi && matchKabupaten && matchKecamatan && bidangMatch && matchJenis;
      });
    }
  },

  data() {
    return {
      showPopup: false,
      showPopupHapus: false,

      searchQuery: '',
      showMore: {},

      selectedProvinsi: null,
      selectedKabupaten: null,
      selectedKecamatan: null,
      selectedDampingan: null,
      selectedJenis: null,
      selectedDampinganId: null,

      provinsiList: [],
      kabupatenList: [],
      kecamatanList: [],
    };
  },

  mounted() {
    this.fetchProvinsi();
  },

  methods: {
    fetchProvinsi() {
      fetch('/api/provinsi')
        .then(res => {
          if (!res.ok) throw new Error('Network response was not ok');
          return res.json();
        })
        .then(data => {
          this.provinsiList = data.map(item => ({ label: item.nama, value: item.kode }));
        })
        .catch(error => {
          console.error('Error fetching provinsi:', error);
        });
    },

    fetchKabupaten(kodeProvinsi) {
      this.selectedKabupaten = null;
      this.selectedKecamatan = null;
      this.kabupatenList = [];
      this.kecamatanList = [];
      if (kodeProvinsi) {
        fetch(`/api/kabupaten/${kodeProvinsi}`)
          .then(res => res.json())
          .then(data => {
            this.kabupatenList = data.map(item => ({ label: item.nama, value: item.kode }));
          });
      }
    },

    fetchKecamatan(kodeKabupaten) {
      this.selectedKecamatan = null;
      this.kecamatanList = [];
      if (kodeKabupaten) {
        fetch(`/api/kecamatan/${kodeKabupaten}`)
          .then(res => res.json())
          .then(data => {
            this.kecamatanList = data.map(item => ({ label: item.nama, value: item.kode }));
          });
      }
    },

    deleteItem(id) {
      this.$inertia.delete(route('dampingan.destroy', id));
    },

    toggleShowMore(grupId) {
      this.showMore[grupId] = !this.showMore[grupId];
    },

    downloadExcel() {
      const filteredData = this.filteredGrups;

      const exportData = filteredData.map((grup, index) => ({
        'No': index + 1,
        'Grup Dampingan': grup.nama_grup_dampingan,
        'Bidang Dampingan': grup.bidang?.nama_bidang,
        'Jenis Dampingan': grup.jenis_dampingan,
        'Provinsi': grup.nama_provinsi,
        'Kabupaten': grup.nama_kabupaten,
        'Kecamatan': grup.nama_kecamatan,
        'Fasilitator': grup.users.map(user => user.name).join(', ')
      }));

      const worksheet = XLSX.utils.json_to_sheet(exportData);
      const workbook = XLSX.utils.book_new();
      XLSX.utils.book_append_sheet(workbook, worksheet, 'Data Dampingan');

      const excelBuffer = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });
      const file = new Blob([excelBuffer], {
        type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
      });

      const fileName = 'data-dampingan.xlsx';
      saveAs(file, fileName);
    }
  },
}
</script>

<template>
  <AdminLayout>

    <Head title="Data Grup Dampingan" />
    <div class="flex bg-gray-100 overflow-auto">
      <main class="flex-1">
        <div class="bg-white shadow-md rounded-lg p-4">
          <div class="flex justify-between mb-4">
            <h2 class="text-xl font-bold mt-2">Data Grup Dampingan</h2>
            <div class="flex space-x-2">
              <a :href="route('dampingan.create')" class="bg-blue-500 text-white px-3 py-2 rounded">+ Tambah</a>
              <button @click="downloadExcel" class="bg-green-500 text-white px-3 py-2 rounded">
                🖨 Cetak
              </button>
            </div>
          </div>

          <!-- Filter Section -->
          <div class="flex flex-wrap gap-4 mb-4">
            <!-- Dropdown Bidang Dampingan -->
            <div class="w-1 min-w-[200px]">
              <Multiselect v-model="selectedDampingan" :options="availableDampinganList" placeholder="Pilih Bidang"
                :searchable="true" class="w-full" :clearable="true" />
            </div>

            <!-- Dropdown Jenis Dampingan -->
            <div class="w-1 min-w-[200px]">
              <Multiselect v-model="selectedJenis" :options="['Pusat','Provinsi','Kabupaten','Kecamatan']" placeholder="Pilih Jenis"
                :searchable="true" class="w-full" :clearable="true" />
            </div>

            <!-- Dropdown Provinsi -->
            <div class="w-1 min-w-[200px]">
              <!-- Provinsi -->
              <Multiselect v-model="selectedProvinsi" :options="provinsiList" placeholder="Pilih Provinsi"
                :searchable="true" class="w-full" @update:modelValue="fetchKabupaten" />
            </div>

            <!-- Dropdown Kabupaten -->
            <div class="w-1 min-w-[200px]">
              <!-- Kabupaten -->
              <Multiselect v-model="selectedKabupaten" :options="kabupatenList" placeholder="Pilih Kabupaten"
                :searchable="true" class="w-full" :disabled="!selectedProvinsi" @update:modelValue="fetchKecamatan" />
            </div>

            <!-- Dropdown Kecamatan -->
            <div class="w-1 min-w-[200px]">
              <!-- Kecamatan -->
              <Multiselect v-model="selectedKecamatan" :options="kecamatanList" placeholder="Pilih Kecamatan"
                :searchable="true" class="w-full" :disabled="!selectedKabupaten" />
            </div>
          </div>

          <div class="overflow-auto rounded-lg">
            <table class="w-full min-w-[600px] border-collapse">
              <thead>
                <tr class="bg-gray-200">
                  <th class="border p-2">No</th>
                  <th class="border p-2">Grup Dampingan</th>
                  <th class="border p-2">Bidang Dampingan</th>
                  <th class="border p-2">Jenis Dampingan</th>
                  <th class="border p-2">Provinsi</th>
                  <th class="border p-2">Kabupaten</th>
                  <th class="border p-2">Kecamatan</th>
                  <th class="border p-2">Fasilitator</th>
                  <th class="border p-2">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(grup, index) in filteredGrups" :key="grup.id_grup_dampingan" class="text-left">
                  <td class="border p-2 text-center">{{ index + 1 }}</td>
                  <td class="border p-2">{{ grup.nama_grup_dampingan }}</td>
                  <td class="border p-2">{{ grup.bidang?.nama_bidang }}</td>
                  <td class="border p-2">{{ grup.jenis_dampingan }}</td>
                  <td class="border p-2">{{ grup.nama_provinsi }}</td>
                  <td class="border p-2">{{ grup.nama_kabupaten }}</td>
                  <td class="border p-2">{{ grup.nama_kecamatan }}</td>
                  <td class="border p-2">
                    <div v-for="(user, index) in grup.users" :key="index">
                      <template v-if="index < 2 || showMore[grup.id_grup_dampingan]">
                        <div>{{ index + 1 }}. {{ user.name }}</div>
                      </template>
                    </div>

                    <!-- Tombol Read More / Less -->
                    <div v-if="grup.users.length > 2">
                      <button class="text-blue-500 text-sm mt-1" @click="toggleShowMore(grup.id_grup_dampingan)">
                        {{ showMore[grup.id_grup_dampingan] ? 'Read less' : 'Read more' }}
                      </button>
                    </div>
                  </td>

                  <td class="border p-2 text-center">
                    <a :href="route('dampingan.edit', grup.id_grup_dampingan)" class="text-blue-500">✏</a>
                    <button @click="selectedDampinganId = grup.id_grup_dampingan; showPopupHapus = true"
                      class="text-red-500">
                      🗑
                    </button>
                  </td>
                </tr>
                <tr v-if="filteredGrups.length === 0">
                  <td colspan="9" class="border p-2 text-center">
                    Tidak ada data yang sesuai
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Popup Konfirmasi Hapus -->
          <div v-if="showPopupHapus" class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-40">
            <div class="bg-white p-6 rounded-lg shadow-lg w-80 text-center">
              <p class="text-lg font-semibold">
                Apakah Anda yakin ingin menghapus?
              </p>
              <div class="flex justify-center gap-4 mt-4">
                <button @click="deleteItem(selectedDampinganId); showPopupHapus = false"
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