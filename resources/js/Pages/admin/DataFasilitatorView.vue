<script>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import Multiselect from "@vueform/multiselect";
import '@vueform/multiselect/themes/default.css';
import * as XLSX from 'xlsx';
import { saveAs } from 'file-saver';
import axios from 'axios';

export default {
  components: {
    AdminLayout,
    Head,
    Multiselect
  },

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
    },
    paginatedFasilitators() {
      const start = (this.currentPage - 1) * this.perPage;
      const end = start + this.perPage;
      return this.filteredFasilitators.slice(start, end);
    },
    totalPages() {
      return Math.ceil(this.filteredFasilitators.length / this.perPage);
    },
    paginationInfo() {
      const start = (this.currentPage - 1) * this.perPage + 1;
      const end = Math.min(this.currentPage * this.perPage, this.filteredFasilitators.length);
      return `Menampilkan ${start}-${end} dari ${this.filteredFasilitators.length} data`;
    }
  },

  data() {
    return {
      currentPage: 1,
      perPage: 10,

      showPopup: false,
      showPopupHapus: false,

      bidangList: [],
      newBidang: '',

      selectedDampingan: null,
      selectedFasilitatorId: null,

      showExportDropdown: false,

      showDetailPopup: false,
      selectedFasilDetail: null,
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
    goToPage(page) {
      if (page >= 1 && page <= this.totalPages) {
        this.currentPage = page;
      }
    },

    showDetail(fasil) {
      this.selectedFasilDetail = fasil;
      this.showDetailPopup = true;
    },

    closeDetail() {
      this.showDetailPopup = false;
      this.selectedFasilDetail = null;
    },

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
      // Pakai axios.delete agar sesuai dengan fetching via axios
      axios.delete(route('bidang.destroy', bidang.id_bidang))
        .then(() => {
          // Hapus dari array lokal setelah berhasil
          this.bidangList.splice(index, 1);
          alert('Bidang berhasil dihapus');
        })
        .catch(err => {
          console.error(err);
          alert('Bidang tidak dapat dihapus karena masih terhubung dengan grup, masyarakat, atau fasillitator');
        });
    },

    downloadExcel() {
      const exportData = this.filteredFasilitators.map((f, index) => ({
        'No': index + 1,
        'Nama Lengkap': f.name,
        'Nomor Telepon': f.nomor_telepon,
        'Alamat': f.alamat,
        'Username': f.username,
        'Bidang Dampingan': f.bidangs.map(b => b.nama_bidang).join(', ')
      }));
      const ws = XLSX.utils.json_to_sheet(exportData);
      const wb = XLSX.utils.book_new();
      XLSX.utils.book_append_sheet(wb, ws, 'Data');
      const buf = XLSX.write(wb, { bookType: 'xlsx', type: 'array' });
      saveAs(new Blob([buf]), 'data-fasilitator.xlsx');
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

    resetPassword(id) {
      if (confirm('Apakah Anda yakin ingin mereset password pengguna ini ke password default?')) {
        router.post(`/fasilitator/${id}/reset-password`, {}, {
          onSuccess: () => {
            alert('Password berhasil direset.')
          },
          preserveScroll: true,
        });
      }
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
          <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-4">
            <h2 class="text-xl font-bold">Data Fasilitator</h2>
            <div class="flex flex-wrap gap-2 w-full sm:w-auto">
              <a :href="route('fasilitator.create')"
                class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded whitespace-nowrap">
                + Tambah
              </a>

              <!-- Export Dropdown -->
              <div class="relative">
                <button @click="toggleExportDropdown"
                  class="bg-green-500 hover:bg-green-600 text-white px-3 py-2 rounded flex items-center whitespace-nowrap">
                  <span class="mr-1">🖨</span> Cetak Data
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                      d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                      clip-rule="evenodd" />
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

          <div class="flex flex-wrap gap-4 mb-6">
            <div class="w-full sm:w-1 min-w-[250px]">
              <Multiselect v-model="selectedDampingan" :options="availableDampinganList"
                placeholder="Pilih Bidang Dampingan" :searchable="true" :clearable="true" class="w-full"
                @change="currentPage = 1" />
            </div>
            <button @click="showPopup = true"
              class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded whitespace-nowrap">
              ⚙ Kelola Bidang
            </button>
          </div>

          <div class="overflow-auto rounded-lg border border-gray-200 mb-4">
            <table class="w-full min-w-[600px] border-collapse">
              <thead>
                <tr class="bg-gray-200 text-center">
                  <th class="border p-2">No</th>
                  <th class="border p-2">Nama</th>
                  <th class="border p-2">Username</th>
                  <th class="border p-2">Bidang Dampingan</th>
                  <th class="border p-2">Grup Dampingan</th>
                  <th class="border p-2">Aksi</th>
                  <th class="border p-2">Detail</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(f, index) in paginatedFasilitators" :key="f.id" class="hover:bg-gray-50">
                  <td class="border p-2 text-center">{{ (currentPage - 1) * perPage + index + 1 }}</td>
                  <td class="border px-2 py-3">{{ f.name }}</td>
                  <td class="border px-2 py-3">{{ f.username }}</td>
                  <td class="border px-2 py-3">
                    <span v-for="(b, i) in f.bidangs" :key="i">
                      {{ b.nama_bidang }}<span v-if="i < f.bidangs.length - 1">, </span>
                    </span>
                  </td>
                  <td class="border px-2 py-3">
                    <span v-for="(g, i) in f.grup_dampingan" :key="i">
                      {{ g.nama_grup_dampingan }}<span v-if="i < f.grup_dampingan.length - 1">, </span>
                    </span>
                  </td>
                  <td class="border p-2 text-center space-x-1">
                    <a :href="route('fasilitator.edit', f.id)" class="text-blue-500" title="Edit">✏️</a>
                    <button @click="selectedFasilitatorId = f.id; showPopupHapus = true" class="text-red-500" title="Hapus">🗑️</button>
                    <button v-if="$page.props.auth.user.role === 'superadmin'" @click="resetPassword(f.id)"
                      class="text-red-500 hover:text-red-700" title="Reset Password">🔐</button>
                  </td>
                  <td class="text-center">
                    <button @click="showDetail(f)"
                      class="bg-blue-500 text-white px-3 py-2 rounded-lg">Detail</button>
                  </td>
                </tr>
                <tr v-if="filteredFasilitators.length === 0">
                  <td colspan="7" class="border p-4 text-center text-gray-500">
                    Tidak ada data yang sesuai
                  </td>
                </tr>
              </tbody>
            </table>

            <!-- Modal Detail Fasilitator -->
            <div v-if="showDetailPopup"
              class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
              <div class="bg-white rounded-xl shadow-lg max-w-2xl w-full p-6 relative">
                <button @click="closeDetail"
                  class="absolute top-4 right-4 text-gray-600 hover:text-red-500 text-xl">✖</button>

                <div class="flex items-center gap-4 mb-6">
                  <img
                    :src="selectedFasilDetail.foto ? `/storage/${ selectedFasilDetail.foto }` : '/images/default-profile.png'"
                    alt="Foto" class="w-16 h-16 rounded-full object-cover border" />

                  <div>
                    <h3 class="text-2xl font-bold">{{ selectedFasilDetail.name }}</h3>
                    <p class="text-base text-gray-700">@{{ selectedFasilDetail.username }}</p>
                  </div>
                </div>

                <table class="w-full text-sm">
                  <tbody>
                    <tr class="border-b">
                      <td class="font-semibold py-2 pr-4 w-1/3">Nomor Telepon</td>
                      <td class="py-2">
                        <a :href="`https://wa.me/${selectedFasilDetail.nomor_telepon.replace(/^0/, '62')}`"
                          class="text-green-600 hover:underline">
                          {{ selectedFasilDetail.nomor_telepon }}
                        </a>
                      </td>
                    </tr>
                    <tr class="border-b">
                      <td class="font-semibold py-2 pr-4">Alamat</td>
                      <td class="py-2">{{ selectedFasilDetail.alamat }}</td>
                    </tr>
                    <tr class="border-b">
                      <td class="font-semibold py-2 pr-4">Bidang Dampingan</td>
                      <td class="py-2">
                        <span v-for="(b, i) in selectedFasilDetail.bidangs" :key="i">
                          {{ b.nama_bidang }}<span v-if="i < selectedFasilDetail.bidangs.length - 1">, </span>
                        </span>
                      </td>
                    </tr>
                    <tr>
                      <td class="font-semibold py-2 pr-4">Grup Dampingan</td>
                      <td class="py-2">
                        <span v-for="(g, i) in selectedFasilDetail.grup_dampingan" :key="i">
                          {{ g.nama_grup_dampingan }}<span v-if="i < selectedFasilDetail.grup_dampingan.length - 1">,
                          </span>
                        </span>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- Pagination -->
          <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mt-4">
            <div class="text-sm text-gray-600">
              {{ paginationInfo }}
            </div>
            <div class="flex items-center gap-1">
              <button @click="goToPage(currentPage - 1)" :disabled="currentPage === 1"
                class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50 disabled:cursor-not-allowed">
                &lt;
              </button>
              <button v-for="page in totalPages" :key="page" @click="goToPage(page)" :class="[
                'px-3 py-1 rounded',
                currentPage === page ? 'bg-blue-500 text-white' : 'bg-gray-200 hover:bg-gray-300'
              ]">
                {{ page }}
              </button>
              <button @click="goToPage(currentPage + 1)" :disabled="currentPage === totalPages"
                class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50 disabled:cursor-not-allowed">
                &gt;
              </button>
            </div>
          </div>
        </div>
      </main>
    </div>

    <!-- Delete Confirmation Popup -->
    <div v-if="showPopupHapus" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white p-6 rounded-lg shadow-xl w-80">
        <div class="flex items-center mb-4">
          <div class="bg-red-100 p-2 rounded-full mr-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24"
              stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
          </div>
          <h3 class="text-lg font-medium">Konfirmasi Hapus</h3>
        </div>
        <p class="text-gray-600 mb-6">Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.
        </p>
        <div class="flex justify-end gap-3">
          <button @click="showPopupHapus = false" class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50">
            Batal
          </button>
          <button @click="deleteItem(selectedFasilitatorId); showPopupHapus = false"
            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
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
          <p class="text-sm">Jl. KH. Ahmad Dahlan No. 103, Notoprajan, Ngampilan, Daerah Istimewa Yogyakarta • Telp:
            (0274) 375025 • @kabarmpm</p>
        </div>
      </div>
      <h2 class="text-xl font-semibold text-center mb-4">Daftar Data Fasilitator</h2>
      <table class="w-full border-collapse text-sm">
        <thead>
          <tr class="bg-gray-200">
            <th class="border p-2">No</th>
            <th class="border p-2">Nama</th>
            <th class="border p-2">Telepon</th>
            <th class="border p-2">Alamat</th>
            <th class="border p-2">Username</th>
            <th class="border p-2">Bidang</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(f, index) in filteredFasilitators" :key="f.id">
            <td class="border p-2 text-center">{{ index + 1 }}</td>
            <td class="border p-2">{{ f.name }}</td>
            <td class="border p-2">{{ f.nomor_telepon }}</td>
            <td class="border p-2">{{ f.alamat }}</td>
            <td class="border p-2">{{ f.username }}</td>
            <td class="border p-2">
              <span v-for="(b, i) in f.bidangs" :key="i">
                {{ b.nama_bidang }}<span v-if="i < f.bidangs.length - 1">, </span>
              </span>
            </td>
          </tr>
          <tr v-if="filteredFasilitators.length === 0">
            <td colspan="6" class="border p-2 text-center text-gray-500">
              Tidak ada data yang sesuai
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Kelola Bidang Popup -->
    <div v-if="showPopup" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50">
      <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-md">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-xl font-bold">Kelola Bidang Dampingan</h3>
          <button @click="showPopup = false" class="text-gray-600 hover:text-gray-900 text-xl">
            ✖
          </button>
        </div>
        <div class="flex mb-4 gap-2">
          <input v-model="newBidang" type="text" placeholder="Nama bidang baru"
            class="flex-1 border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
          <button @click="tambahBidang"
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 whitespace-nowrap">
            + Tambah
          </button>
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
              <tr v-for="(b, i) in bidangList" :key="i" class="hover:bg-gray-50">
                <td class="p-2 border">{{ b.nama_bidang || b }}</td>
                <td class="p-2 border text-center">
                  <button @click="hapusBidang(i)" class="text-red-500 hover:text-red-700 hover:underline">
                    Hapus
                  </button>
                </td>
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

<style>
@media print {
  body * {
    visibility: hidden;
  }

  #print-area,
  #print-area * {
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