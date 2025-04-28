<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { ref, watch, onMounted, computed } from 'vue'
import { Head, useForm, router } from '@inertiajs/vue3'

const { admin, provinsis, kabupatens, kecamatans } = defineProps({
  admin: Object,
  provinsis: Array,
  kabupatens: Array,
  kecamatans: Array
})

const roleOptions = [
  { value: 'superadmin', label: 'Admin Pusat', level: '' },
  { value: 'admin', label: 'Admin Provinsi', level: 'provinsi' },
  { value: 'admin', label: 'Admin Kabupaten', level: 'kabupaten' },
  { value: 'admin', label: 'Admin Kecamatan', level: 'kecamatan' }
]

const form = useForm({
  name: '',
  email: '',
  password: '',
  nomor_telepon: '',
  alamat: '',
  role: '',
  admin_level: '',
  kode_provinsi: '',
  kode_kabupaten: '',
  kode_kecamatan: ''
})

const selectedRole = ref(null)
const filteredKabupatens = ref([])
const filteredKecamatans = ref([])

// Search inputs
const searchProvinsi = ref('')
const searchKabupaten = ref('')
const searchKecamatan = ref('')

const showProvDropdown = ref(false)
const showKabDropdown = ref(false)
const showKecDropdown = ref(false)

onMounted(() => {
  if (admin) {
    form.name = admin.name
    form.email = admin.email
    form.nomor_telepon = admin.nomor_telepon
    form.alamat = admin.alamat
  }
})

watch(selectedRole, (val) => {
  if (val) {
    form.role = val.value === 'superadmin' ? 'superadmin' : 'admin'
    form.admin_level = val.level
    // Reset kode saat role berubah
    form.kode_provinsi = ''
    form.kode_kabupaten = ''
    form.kode_kecamatan = ''

    filteredKabupatens.value = []
    filteredKecamatans.value = []
  }
})

watch(() => form.kode_provinsi, () => {
  filteredKabupatens.value = kabupatens.filter(k => k.kode_provinsi === form.kode_provinsi)
  form.kode_kabupaten = ''
  form.kode_kecamatan = ''
  filteredKecamatans.value = []
})

watch(() => form.kode_kabupaten, () => {
  filteredKecamatans.value = kecamatans.filter(k => k.kode_kabupaten === form.kode_kabupaten)
  form.kode_kecamatan = ''
})

const filteredProvinsis = computed(() => {
  if (!searchProvinsi.value) return provinsis
  return provinsis.filter(p => p.nama.toLowerCase().includes(searchProvinsi.value.toLowerCase()))
})

const filteredKabupatenSearch = computed(() => {
  if (!searchKabupaten.value) return filteredKabupatens.value
  return filteredKabupatens.value.filter(k => k.nama.toLowerCase().includes(searchKabupaten.value.toLowerCase()))
})

const filteredKecamatanSearch = computed(() => {
  if (!searchKecamatan.value) return filteredKecamatans.value
  return filteredKecamatans.value.filter(kec => kec.nama.toLowerCase().includes(searchKecamatan.value.toLowerCase()))
})

const handleSubmit = () => {
  if (admin) {
    form.put(`/admin/data-admin/${admin.id}`)
  } else {
    form.post('/admin/data-admin')
  }
}

const resetSearch = (type) => {
  if (type === 'provinsi') {
    searchProvinsi.value = ''
    form.kode_provinsi = ''
    form.kode_kabupaten = ''
    form.kode_kecamatan = ''
  } else if (type === 'kabupaten') {
    searchKabupaten.value = ''
    form.kode_kabupaten = ''
    form.kode_kecamatan = ''
  } else if (type === 'kecamatan') {
    searchKecamatan.value = ''
    form.kode_kecamatan = ''
  }
}

const clearSelection = (type) => {
  if (type === 'provinsi') {
    form.kode_provinsi = ''
    form.kode_kabupaten = ''
    form.kode_kecamatan = ''
    filteredKabupatens.value = []
    filteredKecamatans.value = []
  } else if (type === 'kabupaten') {
    form.kode_kabupaten = ''
    form.kode_kecamatan = ''
    filteredKecamatans.value = []
  } else if (type === 'kecamatan') {
    form.kode_kecamatan = ''
  }
}
</script>

<template>
  <AdminLayout>

    <Head title="Form Admin" />

    <div class="ml-5 w-full max-md:w-full mx-auto flex justify-center">
      <section
        class="flex flex-col items-center px-8 pt-6 pb-8 mt-12 w-full max-w-5xl bg-white rounded-[20px] shadow-lg overflow-y-auto max-h-[80vh]">
        <div class="flex flex-col w-full">
          <h2 class="self-center text-2xl font-bold text-black">
            {{ admin ? 'Form Edit Admin' : 'Form Tambah Admin' }}
          </h2>
          <form @submit.prevent="handleSubmit" class="mt-6 w-full">

            <!-- Role -->
            <div class="flex flex-col gap-2 pb-2" v-if="!admin">
              <label for="role" class="text-sm font-medium text-gray-600">Role</label>
              <select id="role" v-model="selectedRole"
                class="w-full py-3 px-3 mt-1 border border-gray-300 rounded-md shadow-sm text-base">
                <option disabled value="">Pilih Role</option>
                <option v-for="option in roleOptions" :key="option.value" :value="option">
                  {{ option.label }}
                </option>
              </select>
            </div>

            <!-- Nama Lengkap -->
            <div class="flex flex-col gap-2 pb-2">
              <label for="name" class="text-sm font-medium text-gray-600">Nama Lengkap</label>
              <input id="name" type="text" v-model="form.name"
                class="w-full py-3 px-3 mt-1 border border-gray-400 rounded-md outline-none text-base"
                placeholder="Masukkan Nama Lengkap" />
            </div>

            <!-- No. Telepon -->
            <div class="flex flex-col gap-2 pb-2">
              <label for="nomor_telepon" class="text-sm font-medium text-gray-600">No. Telepon</label>
              <input id="nomor_telepon" type="tel" v-model="form.nomor_telepon"
                class="w-full py-3 px-3 mt-1 border border-gray-400 rounded-md outline-none text-base"
                placeholder="Masukkan No. Telepon" />
            </div>

            <!-- Email -->
            <div class="flex flex-col gap-2 pb-2">
              <label for="email" class="text-sm font-medium text-gray-600">Email</label>
              <input id="email" type="email" v-model="form.email"
                class="w-full py-3 px-3 mt-1 border border-gray-400 rounded-md outline-none text-base"
                placeholder="Masukkan Email" />
            </div>

            <!-- Password -->
            <div class="flex flex-col gap-2 pb-2" v-if="!admin">
              <label for="password" class="text-sm font-medium text-gray-600">Password</label>
              <input id="password" type="password" v-model="form.password"
                class="w-full py-3 px-3 mt-1 border border-gray-400 rounded-md outline-none text-base"
                placeholder="Masukkan Password" />
            </div>

            <!-- Alamat -->
            <div class="flex flex-col gap-2 pb-2">
              <label for="alamat" class="text-sm font-medium text-gray-600">Alamat</label>
              <textarea id="alamat" v-model="form.alamat" rows="4"
                class="w-full py-3 px-3 mt-1 border border-gray-400 rounded-md outline-none text-base resize-none"
                placeholder="Masukkan Alamat"></textarea>
            </div>

            <!-- Provinsi -->
            <div v-if="selectedRole?.level === 'provinsi' || selectedRole?.level === 'kabupaten' || selectedRole?.level === 'kecamatan'">
              <label class="text-sm font-medium text-gray-600">Provinsi</label>
              <div class="relative">
                <div class="flex items-center mt-1 border border-gray-400 rounded-md">
                  <select 
                    v-model="form.kode_provinsi" 
                    @focus="showProvDropdown = true"
                    @blur="setTimeout(() => showProvDropdown = false, 200)"
                    class="w-full py-3 px-3 text-base appearance-none bg-transparent"
                  >
                    <option disabled value="">Pilih Provinsi</option>
                    <option v-if="form.kode_provinsi" :value="form.kode_provinsi">
                      {{ provinsis.find(p => p.kode === form.kode_provinsi)?.nama }}
                    </option>
                  </select>
                  <button 
                    v-if="form.kode_provinsi" 
                    @click="clearSelection('provinsi')" 
                    type="button"
                    class="px-3 text-gray-500 hover:text-gray-700"
                  >
                    ×
                  </button>
                </div>
                <div 
                  v-if="showProvDropdown" 
                  class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg"
                >
                  <div class="relative p-2 border-b border-gray-300">
                    <input 
                      v-model="searchProvinsi" 
                      placeholder="Cari Provinsi"
                      class="w-full p-2 pl-8 pr-8 border rounded focus:outline-none"
                      @click.stop
                    />
                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                      </svg>
                    </span>
                    <button 
                      v-if="searchProvinsi" 
                      @click="resetSearch('provinsi')" 
                      type="button"
                      class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600"
                    >
                      ×
                    </button>
                  </div>
                  <div class="max-h-60 overflow-y-auto">
                    <div 
                      v-for="p in filteredProvinsis" 
                      :key="p.kode" 
                      class="block w-full p-3 hover:bg-gray-100 cursor-pointer border-b border-gray-100 last:border-b-0"
                      @mousedown="form.kode_provinsi = p.kode; showProvDropdown = false"
                    >
                      {{ p.nama }}
                    </div>
                    <div 
                      v-if="filteredProvinsis.length === 0" 
                      class="p-3 text-gray-500 text-center"
                    >
                      Tidak ditemukan
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Kabupaten -->
            <div v-if="selectedRole?.level === 'kabupaten' || selectedRole?.level === 'kecamatan'">
              <label class="text-sm font-medium text-gray-600">Kabupaten</label>
              <div class="relative">
                <div class="flex items-center mt-1 border border-gray-400 rounded-md">
                  <select 
                    v-model="form.kode_kabupaten" 
                    @focus="showKabDropdown = true"
                    @blur="setTimeout(() => showKabDropdown = false, 200)"
                    class="w-full py-3 px-3 text-base appearance-none bg-transparent"
                  >
                    <option disabled value="">Pilih Kabupaten</option>
                    <option v-if="form.kode_kabupaten" :value="form.kode_kabupaten">
                      {{ kabupatens.find(k => k.kode === form.kode_kabupaten)?.nama }}
                    </option>
                  </select>
                  <button 
                    v-if="form.kode_kabupaten" 
                    @click="clearSelection('kabupaten')" 
                    type="button"
                    class="px-3 text-gray-500 hover:text-gray-700"
                  >
                    ×
                  </button>
                </div>
                <div 
                  v-if="showKabDropdown" 
                  class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg"
                >
                  <div class="relative p-2 border-b border-gray-300">
                    <input 
                      v-model="searchKabupaten" 
                      placeholder="Cari Kabupaten"
                      class="w-full p-2 pl-8 pr-8 border rounded focus:outline-none"
                      @click.stop
                    />
                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                      </svg>
                    </span>
                    <button 
                      v-if="searchKabupaten" 
                      @click="resetSearch('kabupaten')" 
                      type="button"
                      class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600"
                    >
                      ×
                    </button>
                  </div>
                  <div class="max-h-60 overflow-y-auto">
                    <div 
                      v-for="k in filteredKabupatenSearch" 
                      :key="k.kode" 
                      class="block w-full p-3 hover:bg-gray-100 cursor-pointer border-b border-gray-100 last:border-b-0"
                      @mousedown="form.kode_kabupaten = k.kode; showKabDropdown = false"
                    >
                      {{ k.nama }}
                    </div>
                    <div 
                      v-if="filteredKabupatenSearch.length === 0" 
                      class="p-3 text-gray-500 text-center"
                    >
                      Tidak ditemukan
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Kecamatan -->
            <div v-if="selectedRole?.level === 'kecamatan'">
              <label class="text-sm font-medium text-gray-600">Kecamatan</label>
              <div class="relative">
                <div class="flex items-center mt-1 border border-gray-400 rounded-md">
                  <select 
                    v-model="form.kode_kecamatan" 
                    @focus="showKecDropdown = true"
                    @blur="setTimeout(() => showKecDropdown = false, 200)"
                    class="w-full py-3 px-3 text-base appearance-none bg-transparent"
                  >
                    <option disabled value="">Pilih Kecamatan</option>
                    <option v-if="form.kode_kecamatan" :value="form.kode_kecamatan">
                      {{ kecamatans.find(k => k.kode === form.kode_kecamatan)?.nama }}
                    </option>
                  </select>
                  <button 
                    v-if="form.kode_kecamatan" 
                    @click="clearSelection('kecamatan')" 
                    type="button"
                    class="px-3 text-gray-500 hover:text-gray-700"
                  >
                    ×
                  </button>
                </div>
                <div 
                  v-if="showKecDropdown" 
                  class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg"
                >
                  <div class="relative p-2 border-b border-gray-300">
                    <input 
                      v-model="searchKecamatan" 
                      placeholder="Cari Kecamatan"
                      class="w-full p-2 pl-8 pr-8 border rounded focus:outline-none"
                      @click.stop
                    />
                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                      </svg>
                    </span>
                    <button 
                      v-if="searchKecamatan" 
                      @click="resetSearch('kecamatan')" 
                      type="button"
                      class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600"
                    >
                      ×
                    </button>
                  </div>
                  <div class="max-h-60 overflow-y-auto">
                    <div 
                      v-for="k in filteredKecamatanSearch" 
                      :key="k.kode" 
                      class="block w-full p-3 hover:bg-gray-100 cursor-pointer border-b border-gray-100 last:border-b-0"
                      @mousedown="form.kode_kecamatan = k.kode; showKecDropdown = false"
                    >
                      {{ k.nama }}
                    </div>
                    <div 
                      v-if="filteredKecamatanSearch.length === 0" 
                      class="p-3 text-gray-500 text-center"
                    >
                      Tidak ditemukan
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Tombol Submit -->
            <div class="flex gap-4 mt-4 justify-end">
              <a :href="route('admin.index')"
                class="px-6 py-2 text-sm font-medium text-white bg-gray-500 rounded-md">Batal</a>
              <button type="submit" class="px-6 py-2 text-sm font-medium text-white bg-sky-600 rounded-md">
                {{ admin ? "Simpan Perubahan" : "Tambah" }}
              </button>
            </div>
          </form>
        </div>
      </section>
    </div>
  </AdminLayout>
</template>
