<script>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import Multiselect from '@vueform/multiselect'
import { Head } from '@inertiajs/vue3'
import * as XLSX from 'xlsx'
import { saveAs } from 'file-saver'
import html2canvas from 'html2canvas'
import QRCode from 'qrcode'
import '@vueform/multiselect/themes/default.css'

export default {
  components: {
    Multiselect,
    AdminLayout,
    Head
  },

  computed: {
    masyarakats() {
      return this.$page.props.data || []
    },
    bidangList() {
      return this.$page.props.bidangList?.map(b => b.nama_bidang) || []
    },
    grupList() {
      return this.$page.props.grupList?.map(g => g.nama_grup_dampingan) || []
    },
    filteredMasyarakats() {
      return this.masyarakats.filter(m => {
        const matchJenisKelamin = this.selectedJenisKelamin ? m.jenis_kelamin === this.selectedJenisKelamin : true
        const matchBidang = this.selectedBidang ? m.bidang?.nama_bidang === this.selectedBidang : true
        const matchGrup = this.selectedGrup ? m.grup?.nama_grup_dampingan === this.selectedGrup : true

        return matchJenisKelamin && matchBidang && matchGrup
      })
    },
    paginatedMasyarakats() {
      const start = (this.currentPage - 1) * this.perPage
      const end = start + this.perPage
      return this.filteredMasyarakats.slice(start, end)
    },
    totalPages() {
      return Math.ceil(this.filteredMasyarakats.length / this.perPage)
    },
    paginationInfo() {
      const start = (this.currentPage - 1) * this.perPage + 1
      const end = Math.min(this.currentPage * this.perPage, this.filteredMasyarakats.length)
      return `Menampilkan ${start}-${end} dari ${this.filteredMasyarakats.length} data`
    }
  },

  data() {
    return {
      isMenuOpen: false,
      isDropdownOpen: false,
      showPopup: false,
      showPopupHapus: false,
      searchQuery: '',
      showExportDropdown: false,

      selectedJenisKelamin: null,
      selectedBidang: null,
      selectedGrup: null,

      selectedMasyarakat: null,
      selectedMasyarakatId: null,
      selectedMasyarakatForKartu: null,

      qrCodeDataUrl: '',
      currentPage: 1,
      perPage: 7,
    }
  },

  methods: {
    goToPage(page) {
      if (page >= 1 && page <= this.totalPages) {
        this.currentPage = page
      }
    },

    showDetails(masyarakat) {
      this.selectedMasyarakat = masyarakat
    },

    closePopup() {
      this.selectedMasyarakat = null
    },

    confirmDelete(id) {
      this.selectedMasyarakatId = id
      this.showPopupHapus = true
    },

    formatTanggal(tanggal) {
      const date = new Date(tanggal);
      const day = String(date.getDate()).padStart(2, '0');
      const month = String(date.getMonth() + 1).padStart(2, '0'); // Januari = 0
      const year = date.getFullYear();
      return `${day}-${month}-${year}`;
    },

    deleteItem(id) {
      this.$inertia.delete(route('masyarakat.destroy', id))
      this.showPopupHapus = false
    },

    downloadExcel() {
      const filteredData = this.filteredMasyarakats

      const exportData = filteredData.map((m) => ({
        'Nomor Anggota': m.nomor_anggota,
        'Nama Lengkap': m.nama_lengkap,
        'Jenis Kelamin': m.jenis_kelamin,
        'Tempat Lahir': m.tempat_lahir,
        'Tanggal Lahir': this.formatTanggal(m.tanggal_lahir),
        'Agama': m.agama,
        'Alamat': m.alamat,
        'Nomor Telepon': m.nomor_telepon,
        'Status': m.status,
        'Pekerjaan': m.pekerjaan?.nama_pekerjaan,
        'Bidang Dampingan': m.bidang?.nama_bidang || '',
        'Grup Dampingan': m.grup?.nama_grup_dampingan || '',
      }));

      const worksheet = XLSX.utils.json_to_sheet(exportData);
      const workbook = XLSX.utils.book_new();
      XLSX.utils.book_append_sheet(workbook, worksheet, 'Data Masyarakat');

      const excelBuffer = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });
      const file = new Blob([excelBuffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });

      const fileName = 'data-masyarakat.xlsx'
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
    },

    async generateQRCode(text) {
      try {
        this.qrCodeDataUrl = await QRCode.toDataURL(text)
      } catch (err) {
        console.error('Gagal generate QR code:', err)
        this.qrCodeDataUrl = ''
      }
    },

    async downloadKartu(masyarakat) {
      this.selectedMasyarakatForKartu = masyarakat;

      // Generate QR code berdasarkan nomor anggota
      await this.generateQRCode(masyarakat.nomor_anggota);

      // Tunggu supaya DOM sudah update
      await this.$nextTick();

      const kartu = document.getElementById('kartu-digital');
      if (!kartu) return;

      kartu.classList.remove('invisible');
      await this.sleep(100);

      // Capture kartunya
      const canvas = await html2canvas(kartu, { useCORS: true });

      // Buat link download
      const link = document.createElement('a');
      link.download = `${masyarakat.nama_lengkap}_Kartu.png`;
      link.href = canvas.toDataURL('image/png');
      link.click();

      kartu.classList.add('invisible');

      // Kosongkan selected masyarakat
      this.selectedMasyarakatForKartu = null;
      this.qrCodeDataUrl = '';
    },

    sleep(ms) {
      return new Promise(resolve => setTimeout(resolve, ms));
    }

  }
}
</script>

<template>
  <AdminLayout>

    <Head title="Data Masyarakat" />

    <div class="flex bg-gray-100 overflow-auto">
      <main class="flex-1">
        <div class="bg-white shadow-md rounded-lg p-4">

          <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-4">
            <h2 class="text-xl font-bold">Data Masyarakat</h2>
            <div class="flex flex-wrap gap-2 w-full sm:w-auto">
              <a :href="route('masyarakat.create')" 
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

          <!-- Filter -->
          <div class="flex flex-wrap gap-4 mb-4">
            <div class="w-full sm:w-1 min-w-[250px]">
              <Multiselect v-model="selectedJenisKelamin" :options="['Laki-laki', 'Perempuan']"
                placeholder="Pilih Jenis Kelamin" :searchable="false" class="w-full" @change="currentPage = 1" />
            </div>
            <div class="w-full sm:w-1 min-w-[250px]">
              <Multiselect v-model="selectedBidang" :options="bidangList" placeholder="Pilih Bidang Dampingan"
                :searchable="true" class="w-full" @change="currentPage = 1" />
            </div>
            <div class="w-full sm:w-1 min-w-[250px]">
              <Multiselect v-model="selectedGrup" :options="grupList" placeholder="Pilih Grup Dampingan"
                :searchable="true" class="w-full" @change="currentPage = 1" />
            </div>
          </div>

          <div class="overflow-auto rounded-lg border border-gray-200 mb-4">
            <table class="w-full min-w-[600px] border-collapse">
              <thead>
                <tr class="bg-gray-200">
                  <th class="w-32">No. Anggota</th>
                  <th>Nama</th>
                  <th class="w-32">Jenis Kelamin</th>
                  <th>Alamat</th>
                  <th class="w-40">Bidang Dampingan</th>
                  <th class="w-40">Grup Dampingan</th>
                  <th class="w-24">Aksi</th>
                  <th class="w-20">Detail</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="masyarakat in paginatedMasyarakats" :key="masyarakat.nomor_anggota" class="hover:bg-gray-50">
                  <td class="text-center">{{ masyarakat.nomor_anggota }}</td>
                  <td>{{ masyarakat.nama_lengkap }}</td>
                  <td>{{ masyarakat.jenis_kelamin }}</td>
                  <td>{{ masyarakat.alamat }}</td>
                  <td>{{ masyarakat.bidang?.nama_bidang }}</td>
                  <td>{{ masyarakat.grup?.nama_grup_dampingan }}</td>
                  <td class="text-center">
                    <a :href="route('masyarakat.edit', masyarakat.nomor_anggota)" class="text-blue-500">✏️</a>
                    <button @click="confirmDelete(masyarakat.nomor_anggota)" class="text-red-500">🗑️</button>
                    <button @click="downloadKartu(masyarakat)" class="text-green-500">🖨️</button>
                  </td>
                  <td class="text-center">
                    <button @click="showDetails(masyarakat)"
                      class="bg-blue-500 text-white px-3 py-2 rounded-lg">Detail</button>
                  </td>
                </tr>
                <tr v-if="filteredMasyarakats.length === 0">
                  <td colspan="8" class="border p-2 text-center text-gray-500">
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

          <!-- Template Kartu Digital (Hidden) -->
          <div v-if="selectedMasyarakatForKartu" id="kartu-digital" class="invisible fixed top-0 left-0 p-10">
            <!-- HALAMAN DEPAN (Original Design) -->
            <div class="w-[720px] bg-gray-200 shadow-2xl rounded-xl overflow-hidden border border-gray-500 flex flex-col">
              <!-- Header -->
              <div class="bg-blue-700 flex items-center justify-start px-6 py-4">
                <img src="/images/logo-mpm.png" class="object-contain w-12 h-13 mr-4" alt="Logo MPM" />
                <div class="text-center flex-1">
                  <div class="text-3xl font-bold text-white">Kartu Tanda Anggota Dampingan</div>
                  <div class="text-base font-medium text-white mt-1">MPM Muhammadiyah</div>
                </div>
              </div>

              <!-- Isi Kartu -->
              <div class="flex px-6 py-4 gap-6">
                <!-- Foto -->
                <div class="w-[160px] flex items-center justify-center">
                  <img
                    v-if="selectedMasyarakatForKartu?.foto"
                    :src="`/storage/${selectedMasyarakatForKartu.foto}`"
                    alt="Foto"
                    class="w-30 h-40 object-cover border rounded-md"
                  />
                </div>

                <!-- Data Anggota -->
                <div class="flex-1 grid grid-cols-2 gap-y-2 text-[15px] text-gray-800">
                  <div class="font-medium">No. Anggota</div>
                  <div>: {{ selectedMasyarakatForKartu.nomor_anggota }}</div>

                  <div class="font-medium">Nama</div>
                  <div>: {{ selectedMasyarakatForKartu.nama_lengkap }}</div>

                  <div class="font-medium">Tempat, Tgl Lahir</div>
                  <div>: {{ selectedMasyarakatForKartu.tempat_lahir }}, {{ formatTanggal(selectedMasyarakatForKartu.tanggal_lahir) }}</div>

                  <div class="font-medium">Jenis Kelamin</div>
                  <div>: {{ selectedMasyarakatForKartu.jenis_kelamin }}</div>

                  <div class="font-medium">Agama</div>
                  <div>: {{ selectedMasyarakatForKartu.agama }}</div>

                  <div class="font-medium">Pekerjaan</div>
                  <div>: {{ selectedMasyarakatForKartu.pekerjaan?.nama_pekerjaan }}</div>

                  <div class="font-medium">Grup Dampingan</div>
                  <div>: {{ selectedMasyarakatForKartu.grup?.nama_grup_dampingan }}</div>

                  <div class="font-medium">Bidang Dampingan</div>
                  <div>: {{ selectedMasyarakatForKartu.bidang?.nama_bidang }}</div>
                </div>
              </div>

              <!-- Footer -->
              <div class="flex justify-end px-6 pb-4">
                <div class="text-gray-400 text-xs">Generated by D3 TI UNS</div>
              </div>
            </div>

            <!-- HALAMAN BELAKANG (Match Front Size) -->
            <div class="w-[720px] bg-gray-200 shadow-2xl rounded-xl overflow-hidden border border-gray-500 flex flex-col items-center justify-center mt-6">
              <!-- Header Back -->
              <div class="bg-blue-700 text-center pt-7 pb-4 w-full">
                <div class="text-3xl font-bold text-white">Verifikasi Keanggotaan</div>
                <div class="text-base font-medium text-white mt-1">MPM Muhammadiyah</div>
              </div>
              <!-- Back Content -->
              <div class="flex flex-col items-center justify-center p-8">
                <div class="text-xl font-semibold mb-4">Scan untuk verifikasi</div>
                <img
                  v-if="qrCodeDataUrl"
                  :src="qrCodeDataUrl"
                  alt="QR Code"
                  class="w-32 h-32"
                />
                <div class="mt-4 text-gray-700 text-sm font-medium">No. Anggota: {{ selectedMasyarakatForKartu.nomor_anggota }}</div>
              </div>
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
                <button @click="deleteItem(selectedMasyarakatId)" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                  Hapus
                </button>
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
            <h2 class="text-xl font-semibold text-center mb-4">Daftar Data Masyarakat</h2>
            <table class="w-full border-collapse text-sm">
              <thead>
                <tr class="bg-gray-200">
                  <th class="w-32">No. Anggota</th>
                  <th>Nama</th>
                  <th class="w-32">Jenis Kelamin</th>
                  <th>Alamat</th>
                  <th class="w-40">Bidang Dampingan</th>
                  <th class="w-40">Grup Dampingan</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="m in filteredMasyarakats" :key="m.nomor_anggota">
                  <td class="text-center">{{ m.nomor_anggota }}</td>
                  <td>{{ m.nama_lengkap }}</td>
                  <td>{{ m.jenis_kelamin }}</td>
                  <td>{{ m.alamat }}</td>
                  <td>{{ m.bidang?.nama_bidang }}</td>
                  <td>{{ m.grup?.nama_grup_dampingan }}</td>
                </tr>
                <tr v-if="filteredMasyarakats.length === 0">
                  <td colspan="6" class="border p-2 text-center text-gray-500">
                    Tidak ada data yang sesuai
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Popup Detail Masyarakat -->
          <div v-if="selectedMasyarakat"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-xl shadow-lg max-w-2xl w-full p-6 relative">
              <button @click="closePopup"
                class="absolute top-4 right-4 text-gray-600 hover:text-red-500 text-xl">✖</button>

              <div class="flex items-center gap-4 mb-6">
                <img :src="`/storage/${selectedMasyarakat.foto}`" alt="Foto"
                  class="w-16 h-16 rounded-full object-cover border" />
                <div>
                  <h3 class="text-2xl font-bold">{{ selectedMasyarakat.nama_lengkap }}</h3>
                  <p class="text-base">{{ selectedMasyarakat.nomor_anggota }}</p>
                </div>
              </div>

              <table class="w-full text-sm">
                <tbody>
                  <tr class="border-b">
                    <td class="font-semibold py-2 pr-4 w-1/3">Tempat, Tanggal Lahir</td>
                    <td class="py-2">{{ selectedMasyarakat.tempat_lahir }}, {{
                      formatTanggal(selectedMasyarakat.tanggal_lahir) }}</td>
                  </tr>
                  <tr class="border-b">
                    <td class="font-semibold py-2 pr-4">Jenis Kelamin</td>
                    <td class="py-2">{{ selectedMasyarakat.jenis_kelamin }}</td>
                  </tr>
                  <tr class="border-b">
                    <td class="font-semibold py-2 pr-4">Agama</td>
                    <td class="py-2">{{ selectedMasyarakat.agama }}</td>
                  </tr>
                  <tr class="border-b">
                    <td class="font-semibold py-2 pr-4">Pekerjaan Utama</td>
                    <td class="py-2">{{ selectedMasyarakat.pekerjaan?.nama_pekerjaan }}</td>
                  </tr>
                  <tr class="border-b">
                    <td class="font-semibold py-2 pr-4">No. Telepon</td>
                    <td class="py-2">{{ selectedMasyarakat.nomor_telepon }}</td>
                  </tr>
                  <tr class="border-b">
                    <td class="font-semibold py-2 pr-4">Alamat</td>
                    <td class="py-2">{{ selectedMasyarakat.alamat }}</td>
                  </tr>
                  <tr class="border-b">
                    <td class="font-semibold py-2 pr-4">Status</td>
                    <td class="py-2">{{ selectedMasyarakat.status }}</td>
                  </tr>
                  <tr>
                    <td class="font-semibold py-2 pr-4">Bidang Dampingan</td>
                    <td class="py-2">{{ selectedMasyarakat.bidang?.nama_bidang }}</td>
                  </tr>
                  <tr>
                    <td class="font-semibold py-2 pr-4">Grup Dampingan</td>
                    <td class="py-2">{{ selectedMasyarakat.grup?.nama_grup_dampingan }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

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

table {
  width: 100%;
  border-collapse: collapse;
}

th,
td {
  border: 1px solid #ddd;
  padding: 8px;
}

.multiselect[disabled] {
  background-color: #e0e0e0 !important;
  cursor: not-allowed;
  opacity: 0.6;
}

.transparent-dropdown {
  opacity: 0.5;
  pointer-events: none;
  background-color: rgba(255, 255, 255, 0.2) !important;
  color: rgb(66, 65, 65) !important;
  border: 1px solid rgba(255, 255, 255, 0.15);
}
</style>