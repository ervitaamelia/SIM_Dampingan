<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import Multiselect from "@vueform/multiselect";
import { Head } from "@inertiajs/vue3";
import * as XLSX from 'xlsx';
import { saveAs } from 'file-saver';
import '@vueform/multiselect/themes/default.css';

export default {
    components: {
        Multiselect,
        AdminLayout,
        Head,
    },

    computed: {
        admins() {
            return this.$page.props.data || [];
        },
        filteredAdmins() {
            return this.admins.filter(admin => {
                const matchProvinsi = this.selectedProvinsi 
                    ? admin.kode_provinsi === this.selectedProvinsi 
                    : true;
                const matchKabupaten = this.selectedKabupaten 
                    ? admin.kode_kabupaten === this.selectedKabupaten 
                    : true;
                const matchKecamatan = this.selectedKecamatan 
                    ? admin.kode_kecamatan === this.selectedKecamatan 
                    : true;
                
                return matchProvinsi && matchKabupaten && matchKecamatan;
            });
        }
    },

    data() {
        return {
            showPopup: false,
            showPopupHapus: false,

            selectedProvinsi: null,
            selectedKabupaten: null,
            selectedKecamatan: null,
            selectedAdminId: null,

            provinsiList: [],
            kabupatenList: [],
            kecamatanList: [],
            searchQuery: "",
        };
    },

    mounted() {
        this.fetchProvinsi();
    },

    methods: {
        fetchProvinsi() {
            fetch('/api/provinsi')
                .then(res => res.json())
                .then(data => {
                    this.provinsiList = data.map(item => ({ label: item.nama, value: item.kode }));
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
        downloadExcel() {
            const filteredData = this.filteredAdmins;

            const exportData = filteredData.map((admin) => ({
                'ID': admin.id,
                'Nama Lengkap': admin.name,
                'Email': admin.email,
                'Alamat': admin.alamat,
                'Nomor Telepon': admin.nomor_telepon,
                'Provinsi': admin.nama_provinsi,
                'Kabupaten': admin.nama_kabupaten,
                'Kecamatan': admin.nama_kecamatan,
            }));

            const worksheet = XLSX.utils.json_to_sheet(exportData);
            const workbook = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(workbook, worksheet, 'Data Admin');

            const excelBuffer = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });
            const file = new Blob([excelBuffer], { 
                type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' 
            });

            const fileName = 'data-admin.xlsx';
            saveAs(file, fileName);
        },
        deleteItem(id) {
            this.$inertia.delete(route('admin.destroy', id));
        }
    }
};
</script>

<template>
    <AdminLayout>

        <Head title="Data Admin" />
        <div class="flex bg-gray-100 overflow-auto">
            <main class="flex-1">
                <div class="bg-white shadow-md rounded-lg p-4">
                    <div class="flex justify-between mb-2">
                        <h2 class="text-xl font-bold">Data Admin</h2>
                        <div class="flex space-x-2">
                            <a :href="route('admin.create')" class="bg-blue-500 text-white px-3 py-2 rounded">+
                                Tambah</a>
                            <button @click="downloadExcel" class="bg-green-500 text-white px-3 py-2 rounded">
                                🖨 Cetak
                            </button>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-4 mb-8">
                        <!-- Dropdown Provinsi -->
                        <div class="w-1 min-w-[200px]">
                            <!-- Provinsi -->
                            <Multiselect v-model="selectedProvinsi" :options="provinsiList" placeholder="Pilih Provinsi"
                                :searchable="true" class="w-full" @update:modelValue="fetchKabupaten" />

                        </div>

                        <!-- Dropdown Kabupaten -->
                        <div class="w-1 min-w-[200px]">
                            <!-- Kabupaten -->
                            <Multiselect v-model="selectedKabupaten" :options="kabupatenList"
                                placeholder="Pilih Kabupaten" :searchable="true" class="w-full"
                                :disabled="!selectedProvinsi" @update:modelValue="fetchKecamatan" />
                        </div>

                        <!-- Dropdown Kecamatan -->
                        <div class="w-1 min-w-[200px]">
                            <!-- Kecamatan -->
                            <Multiselect v-model="selectedKecamatan" :options="kecamatanList"
                                placeholder="Pilih Kecamatan" :searchable="true" class="w-full"
                                :disabled="!selectedKabupaten" />
                        </div>
                    </div>

                    <div class="overflow-auto rounded-lg">
                        <table class="w-full min-w-[600px] border-collapse">
                            <thead>
                                <tr class="bg-gray-200">
                                    <th class="border p-2">Id</th>
                                    <th class="border p-2">Nama Lengkap</th>
                                    <th class="border p-2">Email</th>
                                    <th class="border p-2">Alamat</th>
                                    <th class="border p-2">No. Telepon</th>
                                    <th class="border p-2">Provinsi</th>
                                    <th class="border p-2">Kabupaten</th>
                                    <th class="border p-2">Kecamatan</th>
                                    <th class="border p-2">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="admin in filteredAdmins" :key="admin.id" class="text-left">
                                    <td class="border p-2 text-center">{{ admin.id }}</td>
                                    <td class="border p-2">{{ admin.name }}</td>
                                    <td class="border p-2">{{ admin.email }}</td>
                                    <td class="border p-2">{{ admin.alamat }}</td>
                                    <td class="border p-2">{{ admin.nomor_telepon }}</td>
                                    <td class="border p-2">{{ admin.nama_provinsi }}</td>
                                    <td class="border p-2">{{ admin.nama_kabupaten }}</td>
                                    <td class="border p-2">{{ admin.nama_kecamatan }}</td>
                                    <td class="border p-2 text-center w-20">
                                        <a :href="route('admin.edit', admin.id)" class="text-blue-500 mr-2">✏️</a>
                                        <button @click="selectedAdminId = admin.id; showPopupHapus = true"
                                            class="text-red-500">
                                            🗑️
                                        </button>

                                        <!-- Popup Konfirmasi Hapus -->
                                        <div v-if="showPopupHapus"
                                            class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-10">
                                            <div class="bg-white p-6 rounded-lg shadow-lg w-80 text-center">
                                                <p class="text-lg font-semibold">
                                                    Apakah Anda yakin ingin menghapus?
                                                </p>
                                                <div class="flex justify-center gap-4 mt-4">
                                                    <button @click="deleteItem(selectedAdminId); showPopupHapus = false"
                                                        class="px-4 py-2 bg-red-600 text-white rounded-md">
                                                        Ya
                                                    </button>
                                                    <button @click="showPopupHapus = false"
                                                        class="px-4 py-2 bg-gray-300 rounded-md">
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
