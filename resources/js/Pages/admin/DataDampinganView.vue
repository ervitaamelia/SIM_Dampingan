<script>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Multiselect from '@vueform/multiselect';
import { Head } from '@inertiajs/vue3';
import '@vueform/multiselect/themes/default.css';
import FilterComponent from '@/Components/Filter.vue';
import axios from 'axios';

export default {
  components: {
    Multiselect,
    AdminLayout,
    FilterComponent,
    Head,
  },

  computed: {
    grups() {
      return this.$page.props.data || [];
    },

    filteredAdmins() {
      return this.originalAdmins.filter(admin => {
        // Exact matching for each level
        //const matchProvinsi = !this.selectedProvinsi || admin.provinsi === this.selectedProvinsi;
        //const matchKabupaten = !this.selectedKabupaten || admin.kabupaten === this.selectedKabupaten;
        //const matchKecamatan = !this.selectedKecamatan || admin.kecamatan === this.selectedKecamatan;

        const provinsiMatch = !this.selectedProvinsi ||
          (admin.provinsi_id === this.selectedProvinsi.value);

        const kabupatenMatch = !this.selectedKabupaten ||
          (admin.kabupaten_id === this.selectedKabupaten.value);

        const kecamatanMatch = !this.selectedKecamatan ||
          (admin.kecamatan_id === this.selectedKecamatan.value);

        return provinsiMatch && kabupatenMatch && kecamatanMatch;
      });
    },
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

      selectedDampinganId: null,

      provinsiList: [],
      kabupatenList: [],
      kecamatanList: [],
      dampinganList: [],
    }
  },

  methods: {
    // Ambil data dampingan dari API
    fetchDampinganList() {
      axios.get('/api/dampingan-list')
        .then(response => {
          this.dampinganList = response.data;
        })
        .catch(error => {
          console.error('Gagal mengambil data dampingan:', error);
        });
    },
    // Filter fasilitator berdasarkan dampingan
    filterByDampingan() {
      this.$inertia.get(route('fasilitator.index'), {
        bidang: this.selectedDampingan,
      });
    },
    fetchProvinsi() {
      fetch('/api/provinsi')
        .then(res => res.json())
        .then(data => {
          this.provinsiList = data.map(item => ({
            label: item.nama,
            value: item.kode
          }));
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
            this.kabupatenList = data.map(item => ({
              label: item.nama,
              value: item.kode
            }));
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
            this.kecamatanList = data.map(item => ({
              label: item.nama,
              value: item.kode
            }));
          });
      }
    },
    deleteItem(id) {
      this.$inertia.delete(route('dampingan.destroy', id));
    },
    toggleShowMore(grupId) {
      this.showMore[grupId] = !this.showMore[grupId];
    }
  },

  mounted() {
    this.fetchProvinsi();
    this.fetchDampinganList();
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
              <a :href="route('dampingan.create')" class="bg-blue-500 text-white px-3 py-2 rounded">+ Tambah</a>
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
              <Multiselect v-model="selectedProvinsi" :options="provinsiList" placeholder="Pilih Provinsi"
                :searchable="true" class="w-full" @update:modelValue="fetchKabupaten" />
            </div>

            <!-- Dropdown Kabupaten -->
            <div class="w-1 min-w-[200px]">
              <Multiselect v-model="selectedKabupaten" :options="kabupatenList" placeholder="Pilih Kabupaten"
                :searchable="true" class="w-full" :disabled="!selectedProvinsi" @update:modelValue="fetchKecamatan" />
            </div>

            <!-- Dropdown Kecamatan -->
            <div class="w-1 min-w-[200px]">
              <Multiselect v-model="selectedKecamatan" :options="kecamatanList" placeholder="Pilih Kecamatan"
                :searchable="true" class="w-full" :disabled="!selectedKabupaten" />
            </div>

            <!-- Dropdown Dampingan -->
            <div class="w-1 min-w-[200px]">
              <Multiselect v-model="selectedDampingan" :options="dampinganList" placeholder="Pilih Dampingan"
                :searchable="true" class="w-full" />
            </div>
          </div>
          <div class="overflow-auto">
            <table class="w-full min-w-[600px] border-collapse">
              <thead>
                <tr class="bg-gray-200">
                  <th class="border p-2">Id</th>
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
                <tr v-for="grup in grups" :key="grup.id_grup_dampingan" class="text-left">
                  <td class="border p-2 text-center">{{ grup.id_grup_dampingan }}</td>
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
                    <a :href="route('dampingan.edit', grup.id_grup_dampingan)" class="text-blue-500">✏️</a>
                    <button @click="selectedDampinganId = grup.id_grup_dampingan; showPopupHapus = true"
                      class="text-red-500">
                      🗑️
                    </button>

                    <!-- Popup Konfirmasi Hapus -->
                    <div v-if="showPopupHapus"
                      class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-40">
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
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </main>
    </div>
  </AdminLayout>
</template>
