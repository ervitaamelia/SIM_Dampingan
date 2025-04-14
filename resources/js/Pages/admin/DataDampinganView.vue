<script>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Multiselect from '@vueform/multiselect';
import { Head } from '@inertiajs/vue3';
import '@vueform/multiselect/themes/default.css';

export default {
  components: {
    Multiselect,
    AdminLayout,
    Head,
  },

  computed: {
    grups() {
      return this.$page.props.data || [];
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

      selectedDampinganId: null,

      provinsiList: ["Jawa Tengah", "Jawa Barat", "Jawa Timur"],
      kabupatenList: ["Semarang", "Bandung", "Surabaya"],
      kecamatanList: ["Tembalang", "Cibadak", "Rungkut"],
      dampinganList: ["Dampingan A", "Dampingan B"],
    }
  },

  methods: {
    deleteItem(id) {
      this.$inertia.delete(route('dampingan.destroy', id));
    },

    toggleShowMore(grupId) {
      this.showMore[grupId] = !this.showMore[grupId];
    }
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
                :searchable="true" :class="{ 'transparent-dropdown': showPopup }" class="w-full" />
            </div>

            <!-- Dropdown Kabupaten -->
            <div class="w-1 min-w-[200px]">
              <Multiselect v-model="selectedKabupaten" :options="kabupatenList" placeholder="Pilih Kabupaten"
                :searchable="true" :class="{ 'transparent-dropdown': showPopup }" class="w-full" />
            </div>

            <!-- Dropdown Kecamatan -->
            <div class="w-1 min-w-[200px]">
              <Multiselect v-model="selectedKecamatan" :options="kecamatanList" placeholder="Pilih Kecamatan"
                :searchable="true" :class="{ 'transparent-dropdown': showPopup }" class="w-full" />
            </div>

            <!-- Dropdown Dampingan -->
            <div class="w-1 min-w-[200px]">
              <Multiselect v-model="selectedDampingan" :options="dampinganList" placeholder="Pilih Dampingan"
                :searchable="true" :class="{ 'transparent-dropdown': showPopup }" class="w-full" />
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
