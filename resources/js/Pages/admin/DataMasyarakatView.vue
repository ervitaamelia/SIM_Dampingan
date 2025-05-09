<script>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import Multiselect from '@vueform/multiselect'
import { Head } from '@inertiajs/vue3'
import * as XLSX from 'xlsx'
import { saveAs } from 'file-saver'
import html2canvas from 'html2canvas'
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
    }
  },

  data() {
    return {
      isMenuOpen: false,
      isDropdownOpen: false,
      showPopup: false,
      showPopupHapus: false,
      searchQuery: '',

      selectedJenisKelamin: null,
      selectedBidang: null,
      selectedGrup: null,

      selectedMasyarakat: null,
      selectedMasyarakatId: null,
      selectedMasyarakatForKartu: null,
    }
  },

  methods: {
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
    },

    async downloadKartu(masyarakat) {
      // Set data masyarakat yang mau dibuat kartu
      this.selectedMasyarakatForKartu = masyarakat;

      // Tunggu supaya DOM sudah update
      await this.$nextTick();

      const kartu = document.getElementById('kartu-digital');
      if (!kartu) return;

      // Pastikan kartu kelihatan
      kartu.classList.remove('invisible');

      // Tunggu sedikit supaya browser render
      await this.sleep(100);

      // Capture kartunya
      const canvas = await html2canvas(kartu, { useCORS: true });

      // Buat link download
      const link = document.createElement('a');
      link.download = `${masyarakat.nama_lengkap}_kartu.png`;
      link.href = canvas.toDataURL('image/png');
      link.click();

      // Balikin kartu jadi invisible lagi
      kartu.classList.add('invisible');

      // Kosongkan selected masyarakat
      this.selectedMasyarakatForKartu = null;
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
          <div class="flex justify-between mb-2">
            <h2 class="text-xl font-bold">Data Masyarakat</h2>
            <div class="flex space-x-2">
              <a :href="route('masyarakat.create')" class="bg-blue-500 text-white px-3 py-2 rounded">+ Tambah</a>
              <button @click="downloadExcel" class="bg-green-500 text-white px-3 py-2 rounded">🖨 Cetak</button>
            </div>
          </div>

          <!-- Filter -->
          <div class="flex flex-wrap gap-4 mb-4">
            <div class="w-1 min-w-[250px]">
              <Multiselect v-model="selectedJenisKelamin" :options="['Laki-laki', 'Perempuan']"
                placeholder="Pilih Jenis Kelamin" :searchable="false" class="w-full" />
            </div>
            <div class="w-1 min-w-[250px]">
              <Multiselect v-model="selectedBidang" :options="bidangList" placeholder="Pilih Bidang Dampingan"
                :searchable="true" class="w-full" />
            </div>
            <div class="w-1 min-w-[250px]">
              <Multiselect v-model="selectedGrup" :options="grupList" placeholder="Pilih Grup Dampingan"
                :searchable="true" class="w-full" />
            </div>
          </div>

          <div class="overflow-auto rounded-lg">
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
                <tr v-for="masyarakat in filteredMasyarakats" :key="masyarakat.nomor_anggota">
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
              </tbody>
            </table>
          </div>

          <!-- Template Kartu Digital (Hidden) -->
          <div v-if="selectedMasyarakatForKartu" id="kartu-digital" class="invisible fixed top-0 left-0 p-10">
            <div class="w-[720px] bg-gray-200 shadow-2xl rounded-xl overflow-hidden border border-gray-500 flex flex-col">

              <!-- Header -->
              <div class="bg-blue-700 text-center pt-7 pb-4">
                <div class="text-3xl font-bold text-white">Kartu Tanda Anggota Dampingan</div>
                <div class="text-base font-medium text-white mt-1">MPM Muhammadiyah</div>
              </div>

              <!-- Isi Kartu -->
              <div class="flex px-6 py-4 gap-6">

                <!-- Foto -->
                <div class="w-[160px] flex items-center justify-center">
                  <img v-if="selectedMasyarakatForKartu?.foto" :src="`/storage/${ selectedMasyarakatForKartu.foto }`"
                    alt="Foto" class="w-30 h-40 object-cover border rounded-md" />
                </div>

                <!-- Data Anggota -->
                <div class="flex-1 grid grid-cols-2 gap-y-2 text-[15px] text-gray-800">
                  <div class="font-medium">No. Anggota</div>
                  <div>: {{ selectedMasyarakatForKartu.nomor_anggota }}</div>

                  <div class="font-medium">Nama</div>
                  <div>: {{ selectedMasyarakatForKartu.nama_lengkap }}</div>

                  <div class="font-medium">Tempat, Tgl Lahir</div>
                  <div>: {{ selectedMasyarakatForKartu.tempat_lahir }}, {{
                    formatTanggal(selectedMasyarakatForKartu.tanggal_lahir) }}</div>

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
          </div>

          <!-- Popup Konfirmasi Hapus -->
          <div v-if="showPopupHapus"
            class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-40 z-50">
            <div class="bg-white p-6 rounded-lg shadow-lg w-80 text-center">
              <p class="text-lg font-semibold">Apakah Anda yakin ingin menghapus?</p>
              <div class="flex justify-center gap-4 mt-4">
                <button @click="deleteItem(selectedMasyarakatId)"
                  class="px-4 py-2 bg-red-600 text-white rounded-md">Ya</button>
                <button @click="showPopupHapus = false" class="px-4 py-2 bg-gray-300 rounded-md">Batal</button>
              </div>
            </div>
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
