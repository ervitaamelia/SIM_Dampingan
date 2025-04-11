<script>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';
import FilterComponent from '@/Components/Filter.vue';
import Multiselect from "@vueform/multiselect";
import '@vueform/multiselect/themes/default.css'

export default {
  components: {
    AdminLayout,
    Head,
    FilterComponent,
    Multiselect,
  },

  computed: {
    fasilitators() {
      return this.$page.props.data || [];
    }
  },

  data() {
    return {
      showPopup: false,
      showPopupHapus: false,

      selectedDampingan: null,
      selectedFasilitatorId: null,

      dampinganList: [],
    };
  },

  methods: {
    deleteItem(id) {
      this.$inertia.delete(route('fasilitator.destroy', id));
    }
  },
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
              <button class="bg-green-500 text-white px-3 py-2 rounded">🖨 Cetak</button>
            </div>
          </div>

          <!-- Dropdown Filter Wilayah -->
          <div class="grid grid-cols-1 md:grid-cols-4 gap-4 my-4">
            <!-- Dampingan -->
            <Multiselect v-model="selectedDampingan" :options="dampinganList" placeholder="Pilih Dampingan"
              :searchable="true" class="w-full" :class="{ 'transparent-dropdown': showPopup }" />
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
                  <th class="border p-2">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="fasilitator in fasilitators" :key="fasilitator.id" class="text-left">
                  <td class="border p-2 text-center">{{ fasilitator.id }}</td>
                  <td class="border p-2">{{ fasilitator.name }}</td>
                  <td class="border p-2">{{ fasilitator.nomor_telepon }}</td>
                  <td class="border p-2">{{ fasilitator.alamat }}</td>
                  <td class="border p-2">{{ fasilitator.email }}</td>
                  <td class="border p-2 text-center w-20">
                    <a :href="route('fasilitator.edit', fasilitator.id)" class="text-blue-500 mx-2">✏️</a>
                    <button @click="selectedFasilitatorId = fasilitator.id; showPopupHapus = true"
                      class="text-red-500">🗑️</button>

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