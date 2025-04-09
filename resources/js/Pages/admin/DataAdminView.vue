<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import Multiselect from "@vueform/multiselect";
import { Head } from "@inertiajs/vue3";
import '@vueform/multiselect/themes/default.css'

export default {
    components: {
        Multiselect,
        AdminLayout,
        Head,
    },

    computed: {
        // Ambil data dari props yang dikirim oleh Inertia
        admins() {
            return this.$page.props.data || []; // Jika props kosong, kembalikan array kosong agar tidak error
        },

        filteredDaerah() {
            const daerahList = [
                ...new Set(this.admins.map((admin) => admin.location)),
            ];
            return daerahList.filter((daerah) =>
                daerah.toLowerCase().includes(this.searchQuery.toLowerCase())
            );
        },
    },

    data() {
    return {
        selectedProvinsi: null,
        selectedKabupaten: null,
        selectedKecamatan: null,
        selectedDampingan: null,

        provinsiList: [],
        kabupatenList: [],
        kecamatanList: [],
        dampinganList: [],
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
}
};

</script>

<template>
    <AdminLayout>
        <Head title="Data Admin" />
        <div class="flex h-screen bg-gray-100 overflow-auto">
            <main class="flex-1 p-6">
                <div class="bg-white shadow-md rounded p-4">
                    <div class="flex justify-between mb-2">
                        <h2 class="text-xl font-bold">Data Admin</h2>
                        <div class="flex space-x-2">
                            <a
                                :href="route('admin.create')"
                                class="bg-blue-500 text-white px-3 py-2 rounded"
                                >+ Tambah</a
                            >
                            <button
                                class="bg-green-500 text-white px-3 py-2 rounded"
                            >
                                🖨 Cetak
                            </button>
                        </div>
                    </div>

                    <!-- Wrapper untuk Dropdown (Pindahkan ke bawah tombol Tambah) -->
                    <div class="flex flex-wrap gap-4 mb-8">
                        <!-- Dropdown Provinsi -->
                        <div class="w-1 min-w-[200px]">
                                                    <!-- Provinsi -->
                            <Multiselect
                            v-model="selectedProvinsi"
                            :options="provinsiList"
                            placeholder="Pilih Provinsi"
                            :searchable="true"
                            class="w-full"
                            @update:modelValue="fetchKabupaten"
                            />

                        </div>

                        <!-- Dropdown Kabupaten -->
                        <!-- Kabupaten -->
                            <Multiselect
                            v-model="selectedKabupaten"
                            :options="kabupatenList"
                            placeholder="Pilih Kabupaten"
                            :searchable="true"
                            class="w-full"
                            :disabled="!selectedProvinsi"
                            @update:modelValue="fetchKecamatan"
/>

                        <!-- Dropdown Kecamatan -->
                        <div class="w-1 min-w-[200px]">
                            <!-- Kecamatan -->
                                <Multiselect
                                v-model="selectedKecamatan"
                                :options="kecamatanList"
                                placeholder="Pilih Kecamatan"
                                :searchable="true"
                                class="w-full"
                                :disabled="!selectedKabupaten"
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

                    <div class="overflow-auto">
                        <table class="w-full min-w-[600px] border-collapse">
                            <thead>
                                <tr class="bg-gray-200">
                                    <th class="border p-2">No.</th>
                                    <th class="border p-2">Nama Lengkap</th>
                                    <th class="border p-2">Email</th>
                                    <th class="border p-2">Alamat</th>
                                    <th class="border p-2">Nomor Telepon</th>
                                    <th class="border p-2">Provinsi</th>
                                    <th class="border p-2">Kabupaten</th>
                                    <th class="border p-2">Kecamatan</th>
                                    <th class="border p-2">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="admin in admins"
                                    :key="admin.id"
                                    class="text-left"
                                >
                                    <td class="border p-2 text-center">{{ admin.id }}</td>
                                    <td class="border p-2">{{ admin.name }}</td>
                                    <td class="border p-2">{{ admin.email }}</td>
                                    <td class="border p-2">{{ admin.alamat }}</td>
                                    <td class="border p-2">{{ admin.nomor_telepon }}</td>
                                    <td class="border p-2">{{ admin.nama_provinsi }}</td>
                                    <td class="border p-2">{{ admin.nama_kabupaten }}</td>
                                    <td class="border p-2">{{ admin.nama_kecamatan }}</td>
                                    <td class="border p-2 text-center w-20">
                                        <a
                                            href="EditAdmin"
                                            class="text-blue-500 mr-2"
                                            >✏️</a
                                        >
                                        <button
                                            @click="showPopupHapus = true"
                                            class="text-red-500"
                                        >
                                            🗑️
                                        </button>

                                        <!-- Popup Modal -->
                                        <div
                                            v-if="showPopupHapus"
                                            class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-10"
                                        >
                                            <div
                                                class="bg-white p-6 rounded-lg shadow-lg w-80 text-center"
                                            >
                                                <p
                                                    class="text-lg font-semibold"
                                                >
                                                    Apakah Anda yakin ingin
                                                    menghapus?
                                                </p>
                                                <div
                                                    class="flex justify-center gap-4 mt-4"
                                                >
                                                    <a
                                                        href="DataAdmin"
                                                        @click="deleteItem"
                                                        class="px-4 py-2 bg-red-600 text-white rounded-md"
                                                        >Ya</a
                                                    >
                                                    <a
                                                        href="DataAdmin"
                                                        @click="
                                                            showPopupHapus = false
                                                        "
                                                        class="px-4 py-2 bg-gray-300 rounded-md"
                                                        >Batal</a
                                                    >
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
