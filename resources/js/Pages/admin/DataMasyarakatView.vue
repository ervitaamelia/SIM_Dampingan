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

      members: [
        {
          id: 1,
          photo:
            'https://cdn.builder.io/api/v1/image/assets/99e98c75e74449b086557677558acabb/1dd7fcfb3c814633ca11f4f1acd30619ae561e09609f62ad6d6eeae305379255?placeholderIfAbsent=true',
          name: 'Dinda',
          birthDate: '1990-01-01',
          birthPlace: 'Jakarta',
          Jenis: 'Perempuan',
          Agama: 'Islam',
          Job: ' - ',
          Number: '087725987654',
          Dampingan: 'JATAM',
          status: 'Aktif',
          address: 'Jl. Sudirman No. 1',
          email: 'dinda@example.com',
        },
        {
          id: 1,
          name: 'Dinda',
          birthDate: '1990-01-01',
          birthPlace: 'Jakarta',
          Jenis: 'Perempuan',
          Agama: 'Islam',
          Job: ' - ',
          Number: '087725987654',
          Dampingan: 'JATAM',
          status: 'Aktif',
          address: 'Jl. Sudirman No. 1',
          email: 'dinda@example.com',
        },
        {
          id: 1,
          name: 'Dinda',
          birthDate: '1990-01-01',
          birthPlace: 'Jakarta',
          Jenis: 'Perempuan',
          Agama: 'Islam',
          Job: ' - ',
          Number: '087725987654',
          Dampingan: 'JATAM',
          status: 'Aktif',
          address: 'Jl. Sudirman No. 1',
          email: 'dinda@example.com',
        },
      ],
      selectedMember: null,
    }
  },
  methods: {
    showDetails(member) {
      this.selectedMember = member
    },
    closePopup() {
      this.selectedMember = null
    },
    editMember(member) {
      console.log('Edit', member)
    },
    confirmDelete(member) {
      console.log('Delete', member)
    },
  },
  tambahData() {
    console.log('Tombol ditekan!')
  },
}
</script>

<template>
  <AdminLayout>
    <Head title="Data Masyarakat" />
    <div class="flex h-screen bg-gray-100 overflow-auto">
      <main class="flex-1 p-6">
        <div class="bg-white shadow-md rounded p-4">
          <div class="flex justify-between mb-4">
            <h2 class="text-xl font-bold">Data Masyarakat</h2>
            <div class="flex space-x-2">
              <a
                href="TambahMasyarakat"
                class="bg-blue-500 text-white px-3 py-2 rounded"
                >+ Tambah</a
              >
              <button class="bg-green-500 text-white px-3 py-2 rounded">
                🖨 Cetak
              </button>
            </div>
          </div>
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

          <!-- Wrapper dengan overflow-x-auto -->

          <div class="overflow-auto">
            <table class="w-full min-w-[600px] border-collapse">
              <thead class="bg-gray-100">
                <tr>
                  <th>No Anggota</th>
                  <th>Nama</th>
                  <th>Tanggal Lahir</th>
                  <th>Tempat Lahir</th>
                  <th>Status</th>
                  <th>Aksi</th>
                  <th>Detail</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(member, index) in members" :key="member.id">
                  <td>{{ index + 1 }}</td>
                  <td>{{ member.name }}</td>
                  <td>{{ member.birthDate }}</td>
                  <td>{{ member.birthPlace }}</td>
                  <td>{{ member.status }}</td>
                  <td>
                    <a href="EditMasyarakat" class="text-blue-500 mx-2">✏️</a>
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
                            href="DataMasyarakat"
                            @click="deleteItem"
                            class="px-4 py-2 bg-red-600 text-white rounded-md"
                            >Ya</a
                          >
                          <a
                            href="DataMasyarakat"
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

                  <td class="text-center">
                    <button
                      @click="showDetails(member)"
                      class="bg-blue-500 text-white px-3 py-2 rounded"
                    >
                      Detail
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <hr
            class="shrink-0 mt-5 h-px border border-solid border-zinc-100 max-md:max-w-full"
          />

          <div class="pl-6 mt-4 w-full max-md:pl-5 max-md:max-w-full">
            <TableRow
              v-for="member in members"
              :key="member.id"
              :member="member"
            />
          </div>
        </div>

        <div
          v-if="selectedMember"
          class="popup fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50"
        >
          <div
            class="popup-content bg-white p-6 rounded-lg shadow-lg flex w-[550px] items-center"
          >
            <!-- Bagian Gambar -->
            <div class="flex-shrink-0">
              <img
                :src="selectedMember.photo"
                alt="Foto Anggota"
                class="w-28 h-28 rounded-full object-cover mx-16"
              />
            </div>

            <!-- Bagian Detail -->
            <div class="flex-grow">
              <h3 class="text-lg font-semibold mb-2">Detail Anggota</h3>
              <p><strong>Nama Lengkap:</strong> {{ selectedMember.name }}</p>
              <p>
                <strong>Tanggal Lahir:</strong> {{ selectedMember.birthDate }}
              </p>
              <p>
                <strong>Tempat Lahir:</strong> {{ selectedMember.birthPlace }}
              </p>
              <p><strong>Jenis Kelamin:</strong> {{ selectedMember.Jenis }}</p>
              <p><strong>Agama:</strong> {{ selectedMember.Agama }}</p>
              <p><strong>Pekerjaan:</strong> {{ selectedMember.Job }}</p>
              <p><strong>No Telepon:</strong> {{ selectedMember.Number }}</p>
              <p><strong>Alamat:</strong> {{ selectedMember.address }}</p>
              <p><strong>Dampingan:</strong> {{ selectedMember.Dampingan }}</p>
              <p><strong>Status:</strong> {{ selectedMember.status }}</p>
              <p><strong>Email:</strong> {{ selectedMember.email }}</p>
              <button
                @click="closePopup"
                class="bg-blue-500 text-white px-3 py-2 mt-3 rounded ml-auto block"
              >
                Tutup
              </button>
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
.popup {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
}
.popup-content {
  background: white;
  padding: 20px;
  border-radius: 5px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
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
