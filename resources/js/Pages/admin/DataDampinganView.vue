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
    },

    paginatedGrups() {
      const start = (this.currentPage - 1) * this.perPage;
      const end = start + this.perPage;
      return this.filteredGrups.slice(start, end);
    },
    totalPages() {
      return Math.ceil(this.filteredGrups.length / this.perPage);
    },
    paginationInfo() {
      const start = (this.currentPage - 1) * this.perPage + 1;
      const end = Math.min(this.currentPage * this.perPage, this.filteredGrups.length);
      return `Menampilkan ${start}-${end} dari ${this.filteredGrups.length} data`;
    }
  },

  data() {
    return {
      currentPage: 1,
      perPage: 10,

      showPopup: false,
      showPopupHapus: false,
      showExportDropdown: false,

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
    goToPage(page) {
      if (page >= 1 && page <= this.totalPages) {
        this.currentPage = page;
      }
    },
    
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
      this.showExportDropdown = false;
    },
    
    printPDF() {
      const printArea = document.getElementById('print-area');
      printArea.classList.remove('hidden');
      window.print();
      setTimeout(() => {
        printArea.classList.add('hidden');
      }, 500);
      this.showExportDropdown = false;
    },
    
    toggleExportDropdown() {
      this.showExportDropdown = !this.showExportDropdown;
    },
    
    closeExportDropdown() {
      this.showExportDropdown = false;
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

          <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-4">
            <h2 class="text-xl font-bold mt-2">Data Grup Dampingan</h2>
            <div class="flex flex-wrap gap-2 w-full sm:w-auto">
              <a :href="route('dampingan.create')" 
                 class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded whitespace-nowrap">
                + Tambah
              </a>
              
              <!-- Export Dropdown -->
              <div class="relative">
                <button @click="toggleExportDropdown" 
                        class="bg-green-500 hover:bg-green-600 text-white px-3 py-2 rounded flex items-center whitespace-nowrap">
                  <span class="mr-1">🖨</span> Cetak Data
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                  </svg>
                </button>
                
                <div v-if="showExportDropdown" 
                     class="absolute right-0 mt-1 w-40 bg-white rounded-md shadow-lg z-10 border border-gray-200">
                  <div class="py-1">
                    <button @click="downloadExcel" 
                            class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-green-50 w-full text-left">
                      <span class="mr-2">📊</span> Export Excel
                    </button>
                    <button @click="printPDF" 
                            class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-green-50 w-full text-left">
                      <span class="mr-2">📄</span> Print PDF
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Click outside to close dropdown -->
          <div v-if="showExportDropdown" @click="closeExportDropdown" class="fixed inset-0 z-0"></div>

          <!-- Filter Section -->
          <div class="flex flex-wrap gap-4 mb-4">
            <!-- Dropdown Bidang Dampingan -->
            <div class="w-full sm:w-1 min-w-[200px]">
              <Multiselect v-model="selectedDampingan" :options="availableDampinganList" placeholder="Pilih Bidang"
                :searchable="true" class="w-full" :clearable="true" @change="currentPage = 1" />
            </div>

            <!-- Dropdown Jenis Dampingan -->
            <div class="w-full sm:w-1 min-w-[200px]">
              <Multiselect v-model="selectedJenis" :options="['Pusat','Provinsi','Kabupaten','Kecamatan']" placeholder="Pilih Jenis"
                :searchable="true" class="w-full" :clearable="true" @change="currentPage = 1" />
            </div>

            <!-- Dropdown Provinsi -->
            <div class="w-full sm:w-1 min-w-[200px]">
              <Multiselect v-model="selectedProvinsi" :options="provinsiList" placeholder="Pilih Provinsi"
                :searchable="true" class="w-full" @update:modelValue="fetchKabupaten" @change="currentPage = 1" />
            </div>

            <!-- Dropdown Kabupaten -->
            <div class="w-full sm:w-1 min-w-[200px]">
              <Multiselect v-model="selectedKabupaten" :options="kabupatenList" placeholder="Pilih Kabupaten"
                :searchable="true" class="w-full" :disabled="!selectedProvinsi" @update:modelValue="fetchKecamatan" @change="currentPage = 1" />
            </div>

            <!-- Dropdown Kecamatan -->
            <div class="w-full sm:w-1 min-w-[200px]">
              <Multiselect v-model="selectedKecamatan" :options="kecamatanList" placeholder="Pilih Kecamatan"
                :searchable="true" class="w-full" :disabled="!selectedKabupaten" @change="currentPage = 1" />
            </div>
          </div>

          <div class="overflow-auto rounded-lg border border-gray-200 mb-4">
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
                <tr v-for="(grup, index) in paginatedGrups" :key="grup.id_grup_dampingan" class="text-left hover:bg-gray-50">
                  <td class="border p-2 text-center">{{ (currentPage - 1) * perPage + index + 1 }}</td>
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
                      <button class="text-blue-500 text-sm mt-1 hover:underline" @click="toggleShowMore(grup.id_grup_dampingan)">
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

          <!-- Pagination -->
          <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mt-4">
            <div class="text-sm text-gray-600">
              {{ paginationInfo }}
            </div>
            <div class="flex items-center gap-1">
              <button
                @click="goToPage(currentPage - 1)"
                :disabled="currentPage === 1"
                class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50 disabled:cursor-not-allowed"
              >
                &lt;
              </button>
              <button
                v-for="page in totalPages"
                :key="page"
                @click="goToPage(page)"
                :class="[
                  'px-3 py-1 rounded',
                  currentPage === page ? 'bg-blue-500 text-white' : 'bg-gray-200 hover:bg-gray-300'
                ]"
              >
                {{ page }}
              </button>
              <button
                @click="goToPage(currentPage + 1)"
                :disabled="currentPage === totalPages"
                class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50 disabled:cursor-not-allowed"
              >
                &gt;
              </button>
            </div>
          </div>

          <!-- Popup Konfirmasi Hapus -->
          <div v-if="showPopupHapus" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="bg-white p-6 rounded-lg shadow-xl w-80">
              <div class="flex items-center mb-4">
                <div class="bg-red-100 p-2 rounded-full mr-3">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                  </svg>
                </div>
                <h3 class="text-lg font-medium">Konfirmasi Hapus</h3>
              </div>
              <p class="text-gray-600 mb-6">Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.</p>
              <div class="flex justify-end gap-3">
                <button @click="showPopupHapus = false" class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50">
                  Batal
                </button>
                <button @click="deleteItem(selectedDampinganId)" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                  Hapus
                </button>
              </div>
            </div>
          </div>
          
        </div>

        <!-- Print / PDF area -->
        <div id="print-area" class="hidden">
          <div class="flex items-center mb-4">
            <img src="/images/logo-mpm.png" class="object-contain w-12 h-12 mr-4" alt="Logo MPM" />
            <div>
              <h1 class="text-2xl font-bold">MPM Muhammadiyah</h1>
              <p class="text-sm">Jl. KH. Ahmad Dahlan No. 103, Notoprajan, Ngampilan, Daerah Istimewa Yogyakarta • Telp: (0274) 375025 • @kabarmpm</p>
            </div>
          </div>
          <h2 class="text-xl font-semibold text-center mb-4">Daftar Data Dampingan</h2>
          <table class="w-full border-collapse text-sm">
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
              </tr>
            </thead>
            <tbody>
              <tr v-for="(grup, index) in filteredGrups" :key="grup.id_grup_dampingan">
                <td class="border p-2 text-center">{{ index + 1 }}</td>
                <td class="border p-2">{{ grup.nama_grup_dampingan }}</td>
                <td class="border p-2">{{ grup.bidang?.nama_bidang }}</td>
                <td class="border p-2">{{ grup.jenis_dampingan }}</td>
                <td class="border p-2">{{ grup.nama_provinsi }}</td>
                <td class="border p-2">{{ grup.nama_kabupaten }}</td>
                <td class="border p-2">{{ grup.nama_kecamatan }}</td>
                <td class="border p-2">
                  <div v-for="(user, index) in grup.users" :key="index">
                    <div>{{ index + 1 }}. {{ user.name }}</div>
                  </div>
                </td>
              </tr>
              <tr v-if="filteredGrups.length === 0">
                <td colspan="8" class="border p-2 text-center text-gray-500">
                  Tidak ada data yang sesuai
                </td>
              </tr>
            </tbody>
          </table>
        </div>

      </main>
    </div>
  </AdminLayout>
</template>

<style>
@media print {
  body * {
    visibility: hidden;
  }
  #print-area, #print-area * {
    visibility: visible;
  }
  #print-area {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    padding: 20px;
  }
}

/* Custom scrollbar for table */
.overflow-auto::-webkit-scrollbar {
  height: 8px;
}
.overflow-auto::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 4px;
}
.overflow-auto::-webkit-scrollbar-thumb {
  background: #c1c1c1;
  border-radius: 4px;
}
.overflow-auto::-webkit-scrollbar-thumb:hover {
  background: #a8a8a8;
}
</style>