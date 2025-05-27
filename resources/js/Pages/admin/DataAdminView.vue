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
                // Filter berdasarkan wilayah
                const matchProvinsi = this.selectedProvinsi
                    ? admin.kode_provinsi === this.selectedProvinsi
                    : true;
                const matchKabupaten = this.selectedKabupaten
                    ? admin.kode_kabupaten === this.selectedKabupaten
                    : true;
                const matchKecamatan = this.selectedKecamatan
                    ? admin.kode_kecamatan === this.selectedKecamatan
                    : true;

                // Filter berdasarkan role
                const matchRole = this.selectedRole
                    ? this.formatRole(admin.role) === this.selectedRole
                    : true;

                return matchProvinsi && matchKabupaten && matchKecamatan && matchRole;
            });
        },
        paginatedAdmins() {
            const start = (this.currentPage - 1) * this.perPage;
            const end = start + this.perPage;
            return this.filteredAdmins.slice(start, end);
        },
        totalPages() {
            return Math.ceil(this.filteredAdmins.length / this.perPage);
        },
        paginationInfo() {
            const start = (this.currentPage - 1) * this.perPage + 1;
            const end = Math.min(this.currentPage * this.perPage, this.filteredAdmins.length);
            return `Menampilkan ${start}-${end} dari ${this.filteredAdmins.length} data`;
        }
    },

    data() {
        const user = this.$page.props.auth.user; // Ganti dari userWilayah ke auth.user jika perlu

        return {
            currentPage: 1,
            perPage: 10,

            showPopup: false,
            showPopupHapus: false,

            selectedProvinsi: user.role === 'admin-provinsi' ||
                user.role === 'admin-kabupaten' ||
                user.role === 'admin-kecamatan'
                ? user.kode_provinsi
                : null,
            selectedKabupaten: user.role === 'admin-kabupaten' ||
                user.role === 'admin-kecamatan'
                ? user.kode_kabupaten
                : null,
            selectedKecamatan: user.role === 'admin-kecamatan'
                ? user.kode_kecamatan
                : null,
            selectedRole: null,
            selectedAdminId: null,

            provinsiList: this.$page.props.provinsiList || [],
            kabupatenList: [],
            kecamatanList: [],
            showExportDropdown: false,
        };
    },

    mounted() {
        this.fetchProvinsi();
        const user = this.$page.props.auth.user; // Ganti dari userWilayah ke auth.user jika perlu

        if (user.role === 'admin-provinsi') {
            this.selectedProvinsi = user.kode_provinsi;
            this.fetchKabupaten(user.kode_provinsi);
        }

        if (user.role === 'admin-kabupaten') {
            this.selectedProvinsi = user.kode_provinsi;
            this.fetchKabupaten(user.kode_provinsi).then(() => {
                this.selectedKabupaten = user.kode_kabupaten;
                this.fetchKecamatan(user.kode_kabupaten);
            });
        }

        if (user.role === 'admin-kecamatan') {
            this.selectedProvinsi = user.kode_provinsi;
            this.fetchKabupaten(user.kode_provinsi).then(() => {
                this.selectedKabupaten = user.kode_kabupaten;
                return this.fetchKecamatan(user.kode_kabupaten);
            }).then(() => {
                this.selectedKecamatan = user.kode_kecamatan;
            });
        }
    },

    methods: {
        goToPage(page) {
            if (page >= 1 && page <= this.totalPages) {
                this.currentPage = page;
            }
        },
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
                return fetch(`/api/kabupaten/${kodeProvinsi}`)
                    .then(res => res.json())
                    .then(data => {
                        this.kabupatenList = data.map(item => ({ label: item.nama, value: item.kode }));
                        return data;
                    });
            }
            return Promise.resolve();
        },

        fetchKecamatan(kodeKabupaten) {
            this.selectedKecamatan = null;
            this.kecamatanList = [];
            if (kodeKabupaten) {
                return fetch(`/api/kecamatan/${kodeKabupaten}`)
                    .then(res => res.json())
                    .then(data => {
                        this.kecamatanList = data.map(item => ({ label: item.nama, value: item.kode }));
                        return data;
                    });
            }
            return Promise.resolve();
        },
        downloadExcel() {
            const filteredData = this.filteredAdmins;

            const exportData = filteredData.map((admin, index) => ({
                'No': index + 1,
                'Nama Lengkap': admin.name,
                'Email': admin.email,
                'Alamat': admin.alamat,
                'Nomor Telepon': admin.nomor_telepon,
                'Role': this.formatRole(admin.role),
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
        formatRole(role) {
            return role
                .split('-')
                .map(word => word.charAt(0).toUpperCase() + word.slice(1))
                .join(' ');
        },
        toggleExportDropdown() {
            this.showExportDropdown = !this.showExportDropdown;
        },
        closeExportDropdown() {
            this.showExportDropdown = false;
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
                    <!-- Header actions -->
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-4">
                        <h2 class="text-xl font-bold">Data Admin</h2>
                        <div class="flex flex-wrap gap-2 w-full sm:w-auto">
                            <a v-if="$page.props.auth.user.role !== 'admin-kecamatan'" :href="route('admin.create')"
                                class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded whitespace-nowrap">
                                + Tambah
                            </a>

                            <!-- Export Dropdown -->
                            <div class="relative">
                                <button @click="toggleExportDropdown"
                                    class="bg-green-500 hover:bg-green-600 text-white px-3 py-2 rounded flex items-center whitespace-nowrap">
                                    <span class="mr-1">🖨</span> Cetak Data
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20"
                                        fill="currentColor">
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

                    <!-- Filter -->
                    <div class="flex flex-wrap gap-4 mb-6">
                        <!-- Dropdown Role -->
                        <div class="w-full sm:w-1 min-w-[200px]">
                            <Multiselect v-model="selectedRole"
                                :options="['Superadmin', 'Admin Provinsi', 'Admin Kabupaten', 'Admin Kecamatan']"
                                placeholder="Pilih Role" :searchable="true" class="w-full" :clearable="true"
                                @change="currentPage = 1" />
                        </div>

                        <!-- Dropdown Provinsi -->
                        <div class="w-full sm:w-1 min-w-[200px]">
                            <Multiselect v-model="selectedProvinsi" :options="provinsiList" placeholder="Pilih Provinsi"
                                :searchable="true" class="w-full" :disabled="$page.props.auth.user.role === 'admin-provinsi' ||
                                    $page.props.auth.user.role === 'admin-kabupaten' ||
                                    $page.props.auth.user.role === 'admin-kecamatan'" @update:modelValue="fetchKabupaten"
                                @change="currentPage = 1" />
                        </div>

                        <!-- Dropdown Kabupaten -->
                        <div class="w-full sm:w-1 min-w-[200px]">
                            <Multiselect v-model="selectedKabupaten" :options="kabupatenList"
                                placeholder="Pilih Kabupaten" :searchable="true" class="w-full" :disabled="!selectedProvinsi ||
                                    $page.props.auth.user.role === 'admin-kabupaten' ||
                                    $page.props.auth.user.role === 'admin-kecamatan'" @update:modelValue="fetchKecamatan"
                                @change="currentPage = 1" />
                        </div>

                        <!-- Dropdown Kecamatan -->
                        <div class="w-full sm:w-1 min-w-[200px]">
                            <Multiselect v-model="selectedKecamatan" :options="kecamatanList"
                                placeholder="Pilih Kecamatan" :searchable="true" class="w-full" :disabled="!selectedKabupaten ||
                                    $page.props.auth.user.role === 'admin-kecamatan'" @change="currentPage = 1" />
                        </div>
                    </div>

                    <!-- Data table -->
                    <div class="overflow-auto rounded-lg border border-gray-200 mb-4">
                        <table class="w-full min-w-[600px] border-collapse">
                            <thead>
                                <tr class="bg-gray-200">
                                    <th class="border p-2">No</th>
                                    <th class="border p-2">Nama</th>
                                    <th class="border p-2">Email</th>
                                    <th class="border p-2">Alamat</th>
                                    <th class="border p-2">No. Telepon</th>
                                    <th class="border p-2">Role</th>
                                    <th class="border p-2">Provinsi</th>
                                    <th class="border p-2">Kabupaten</th>
                                    <th class="border p-2">Kecamatan</th>
                                    <th class="border p-2">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(admin, index) in paginatedAdmins" :key="admin.id"
                                    class="text-left hover:bg-gray-50">
                                    <td class="border p-2 text-center">{{ (currentPage - 1) * perPage + index + 1 }}
                                    </td>
                                    <td class="border px-2 py-3">{{ admin.name }}</td>
                                    <td class="border px-2 py-3">{{ admin.email }}</td>
                                    <td class="border px-2 py-3">{{ admin.alamat }}</td>
                                    <td class="border px-2 py-3">{{ admin.nomor_telepon }}</td>
                                    <td class="border px-2 py-3">{{ formatRole(admin.role) }}</td>
                                    <td class="border px-2 py-3">{{ admin.nama_provinsi }}</td>
                                    <td class="border px-2 py-3">{{ admin.nama_kabupaten }}</td>
                                    <td class="border px-2 py-3">{{ admin.nama_kecamatan }}</td>
                                    <td class="border p-2 text-center w-20">
                                        <a :href="route('admin.edit', admin.id)" class="text-blue-500 mr-2">✏</a>
                                        <button @click="selectedAdminId = admin.id; showPopupHapus = true"
                                            class="text-red-500">
                                            🗑
                                        </button>
                                    </td>
                                    <!-- Popup Konfirmasi Hapus -->
                                    <div v-if="showPopupHapus && selectedAdminId !== null"
                                        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-30 z-50">
                                        <div class="bg-white p-6 rounded-lg shadow-xl w-80">
                                            <div class="flex items-center mb-4">
                                                <div class="bg-red-100 p-2 rounded-full mr-3">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                    </svg>
                                                </div>
                                                <h3 class="text-lg font-medium">Konfirmasi Hapus</h3>
                                            </div>
                                            <p class="text-gray-600 mb-6">Apakah Anda yakin ingin menghapus data ini?
                                                Tindakan ini tidak dapat dibatalkan.</p>
                                            <div class="flex justify-end gap-3">
                                                <button @click="showPopupHapus = false; selectedAdminId = null"
                                                    class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50">
                                                    Batal
                                                </button>
                                                <button
                                                    @click="deleteItem(selectedAdminId); showPopupHapus = false; selectedAdminId = null"
                                                    class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                                                    Hapus
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </tr>
                                <tr v-if="filteredAdmins.length === 0">
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

                <!-- Print / PDF area -->
                <div id="print-area" class="hidden">
                    <div class="flex items-center mb-4">
                        <img src="/images/logo-mpm.png" class="object-contain w-12 h-12 mr-4" alt="Logo MPM" />
                        <div>
                            <h1 class="text-2xl font-bold">MPM Muhammadiyah</h1>
                            <p class="text-sm">Jl. KH. Ahmad Dahlan No. 103, Notoprajan, Ngampilan, Daerah Istimewa
                                Yogyakarta • Telp: (0274) 375025 • @kabarmpm</p>
                        </div>
                    </div>
                    <h2 class="text-xl font-semibold text-center mb-4">Daftar Data Admin</h2>
                    <table class="w-full border-collapse text-sm">
                        <thead>
                            <tr class="bg-gray-200">
                                <th class="border px-2 py-1">No</th>
                                <th class="border px-2 py-1">Nama Lengkap</th>
                                <th class="border px-2 py-1">Email</th>
                                <th class="border px-2 py-1">Alamat</th>
                                <th class="border px-2 py-1">No. Telepon</th>
                                <th class="border px-2 py-1">Provinsi</th>
                                <th class="border px-2 py-1">Kabupaten</th>
                                <th class="border px-2 py-1">Kecamatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(admin, i) in filteredAdmins" :key="admin.id">
                                <td class="border px-2 py-1 text-center">{{ i + 1 }}</td>
                                <td class="border px-2 py-1">{{ admin.name }}</td>
                                <td class="border px-2 py-1">{{ admin.email }}</td>
                                <td class="border px-2 py-1">{{ admin.alamat }}</td>
                                <td class="border px-2 py-1">{{ admin.nomor_telepon }}</td>
                                <td class="border px-2 py-1">{{ admin.nama_provinsi }}</td>
                                <td class="border px-2 py-1">{{ admin.nama_kabupaten }}</td>
                                <td class="border px-2 py-1">{{ admin.nama_kecamatan }}</td>
                            </tr>
                            <tr v-if="filteredAdmins.length === 0">
                                <td colspan="8" class="border px-2 py-1 text-center">Tidak ada data</td>
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