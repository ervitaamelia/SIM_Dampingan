<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import Multiselect from "@vueform/multiselect";
import { Head } from "@inertiajs/vue3";

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
            isMenuOpen: false,
            isDropdownOpen: false,
            showPopup: false,
            showPopupHapus: false,
            searchQuery: "",

            selectedProvinsi: null,
            selectedKabupaten: null,
            selectedKecamatan: null,
            selectedDampingan: null,
            provinsiList: ["Jawa Tengah", "Jawa Barat", "Jawa Timur"],
            kabupatenList: ["Semarang", "Bandung", "Surabaya"],
            kecamatanList: ["Tembalang", "Cibadak", "Rungkut"],
            dampinganList: ["Dampingan A", "Dampingan B"],
        };
    },

    methods: {
        toggleDropdown(event) {
            event.stopPropagation();
            this.isDropdownOpen = !this.isDropdownOpen;
        },
        selectDaerah(daerah) {
            console.log("Dipilih:", daerah);
            this.isDropdownOpen = false;
        },
        closeDropdown(event) {
            if (!this.$el.contains(event.target)) {
                this.isDropdownOpen = false;
            }
        },
        tambahData() {
            console.log("Tombol ditekan!");
        },
    },

    mounted() {
        document.addEventListener("click", this.closeDropdown);
    },

    beforeUnmount() {
        document.removeEventListener("click", this.closeDropdown);
    },
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
                                href="TambahAdmin"
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

                    <div class="overflow-auto">
                        <table class="w-full min-w-[600px] border-collapse">
                            <thead>
                                <tr class="bg-gray-200">
                                    <th class="border p-2">Id</th>
                                    <th class="border p-2">Nama Lengkap</th>
                                    <th class="border p-2">Email</th>
                                    <th class="border p-2">Alamat</th>
                                    <th class="border p-2">Nomor Telepon</th>
                                    <th class="border p-2">Role</th>
                                    <th class="border p-2">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="admin in admins"
                                    :key="admin.id"
                                    class="text-center"
                                >
                                    <td class="border p-2">{{ admin.id }}</td>
                                    <td class="border p-2">{{ admin.name }}</td>
                                    <td class="border p-2">
                                        {{ admin.email }}
                                    </td>
                                    <td class="border p-2">
                                        {{ admin.alamat }}
                                    </td>
                                    <td class="border p-2">
                                        {{ admin.nomor_telepon }}
                                    </td>
                                    <td class="border p-2">{{ admin.role }}</td>
                                    <td class="border p-2">
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
