<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { ref, watch, onMounted, computed } from 'vue'
import { Head, useForm } from '@inertiajs/vue3'
import Multiselect from '@vueform/multiselect'
import '@vueform/multiselect/themes/default.css'

const { admin } = defineProps({
  admin: Object,
})

// state untuk toggle visibility password
const showPassword = ref(false);
const togglePassword = () => {
  showPassword.value = !showPassword.value;
};

const roleOptions = [
  { value: 'superadmin', label: 'Superadmin' },
  { value: 'admin-provinsi', label: 'Admin Provinsi' },
  { value: 'admin-kabupaten', label: 'Admin Kabupaten' },
  { value: 'admin-kecamatan', label: 'Admin Kecamatan' }
]

const form = useForm({
  name: '',
  email: '',
  password: '',
  nomor_telepon: '',
  alamat: '',
  role: '',
  kode_provinsi: '',
  kode_kabupaten: '',
  kode_kecamatan: ''
})

const selectedRole = ref(null)

onMounted(() => {
  fetchProvinsi();
  fetchKabupaten(admin.kode_provinsi);
  fetchKecamatan(admin.kode_kabupaten);

  if (admin) {
    form.name = admin.name
    form.email = admin.email
    form.nomor_telepon = admin.nomor_telepon
    form.alamat = admin.alamat
    form.kode_provinsi = admin.kode_provinsi
    form.kode_kabupaten = admin.kode_kabupaten
    form.kode_kecamatan = admin.kode_kecamatan
    form.role = admin.role
    selectedRole.value = roleOptions.find(option => option.value === admin.role)
  }
})

watch(selectedRole, (val) => {
  if (val) {
    form.role = val.value
    form.kode_provinsi = ''
    form.kode_kabupaten = ''
    form.kode_kecamatan = ''
  }
})

const handleSubmit = () => {
  if (admin) {
    form.put(`/admin/data-admin/${admin.id}`)
  } else {
    form.post('/admin/data-admin')
  }
}

const provinsiList = ref([])
const kabupatenList = ref([])
const kecamatanList = ref([])

const selectedKabupaten = ref(null)
const selectedKecamatan = ref(null)

function fetchProvinsi() {
  fetch('/api/provinsi')
    .then(res => {
      if (!res.ok) throw new Error('Network response was not ok');
      return res.json();
    })
    .then(data => {
      provinsiList.value = data.map(item => ({
        label: item.nama,
        value: item.kode
      }))
    })
    .catch(error => {
      console.error('Error fetching provinsi:', error)
    })
}

function fetchKabupaten(kodeProvinsi) {
  selectedKabupaten.value = null
  selectedKecamatan.value = null
  kabupatenList.value = []
  kecamatanList.value = []

  if (kodeProvinsi) {
    fetch(`/api/kabupaten/${kodeProvinsi}`)
      .then(res => res.json())
      .then(data => {
        kabupatenList.value = data.map(item => ({
          label: item.nama,
          value: item.kode
        }))
      })
  }
}

function fetchKecamatan(kodeKabupaten) {
  selectedKecamatan.value = null
  kecamatanList.value = []

  if (kodeKabupaten) {
    fetch(`/api/kecamatan/${kodeKabupaten}`)
      .then(res => res.json())
      .then(data => {
        kecamatanList.value = data.map(item => ({
          label: item.nama,
          value: item.kode
        }))
      })
  }
}

const isFormIncomplete = computed(() => {
  if (!form.name || !form.email || !form.nomor_telepon || !form.alamat) return true
  if (!admin && !form.password) return true
  if (!form.role) return true

  if (['admin-provinsi'].includes(form.role) && !form.kode_provinsi) return true
  if (['admin-kabupaten'].includes(form.role) && (!form.kode_provinsi || !form.kode_kabupaten)) return true
  if (['admin-kecamatan'].includes(form.role) && (!form.kode_provinsi || !form.kode_kabupaten || !form.kode_kecamatan)) return true

  return false
})
</script>

<template>
  <AdminLayout>

    <Head :title="admin ? 'Edit Admin' : 'Tambah Admin'" />

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
              <label for="name" class="text-sm font-medium text-gray-600">Nama</label>
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

            <!-- Password dengan toggle icon -->
            <div v-if="!admin" class="flex flex-col gap-2">
              <label class="text-sm font-medium text-gray-600">Password</label>
              <div class="relative">
                <input :type="showPassword ? 'text' : 'password'" v-model="form.password"
                  placeholder="Masukkan Password" class="w-full py-2 px-3 border border-gray-400 rounded-md" />
                <button type="button" @click="togglePassword"
                  class="absolute inset-y-0 right-3 flex items-center focus:outline-none">
                  <svg v-if="showPassword" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-600" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M13.875 18.825A10.05 10.05 0 0112 19c-5.523 0-10-4.477-10-10a9.97 9.97 0 012.192-6.174M3 3l18 18M9.88 9.88a3 3 0 104.24 4.24" />
                  </svg>
                  <svg v-else xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-600" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.944 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                  </svg>
                </button>
              </div>
            </div>

            <!-- Alamat -->
            <div class="flex flex-col gap-2 pb-2">
              <label for="alamat" class="text-sm font-medium text-gray-600">Alamat</label>
              <textarea id="alamat" v-model="form.alamat" rows="4"
                class="w-full py-3 px-3 mt-1 border border-gray-400 rounded-md outline-none text-base resize-none"
                placeholder="Masukkan Alamat"></textarea>
            </div>

            <div class="flex flex-col gap-2 pb-2">
              <!-- Provinsi -->
              <div v-if="['admin-provinsi', 'admin-kabupaten', 'admin-kecamatan'].includes(selectedRole?.value)">
                <label for="kode_provinsi" class="text-sm font-medium text-gray-600">Provinsi</label>
                <Multiselect v-model="form.kode_provinsi" :options="provinsiList" placeholder="Pilih Provinsi"
                  :searchable="true" class="w-full" @update:modelValue="fetchKabupaten" />
              </div>

              <!-- Kabupaten -->
              <div v-if="['admin-kabupaten', 'admin-kecamatan'].includes(selectedRole?.value)">
                <label for="kode_kabupaten" class="text-sm font-medium text-gray-600">Kabupaten</label>
                <Multiselect v-model="form.kode_kabupaten" :options="kabupatenList" placeholder="Pilih Kabupaten"
                  :searchable="true" class="w-full" :disabled="!form.kode_provinsi"
                  @update:modelValue="fetchKecamatan" />
              </div>

              <!-- Kecamatan -->
              <div v-if="selectedRole?.value === 'admin-kecamatan'">
                <label for="kode_kecamatan" class="text-sm font-medium text-gray-600">Kecamatan</label>
                <Multiselect v-model="form.kode_kecamatan" :options="kecamatanList" placeholder="Pilih Kecamatan"
                  :searchable="true" class="w-full" :disabled="!form.kode_kabupaten" />
              </div>
            </div>

            <!-- Tombol Submit -->
            <div class="flex gap-4 mt-4 justify-end">
              <a :href="route('admin.index')"
                class="px-6 py-2 text-sm font-medium text-white bg-gray-500 rounded-md">Batal</a>
              <button type="submit" :disabled="isFormIncomplete"
                class="px-6 py-2 text-sm font-medium text-white bg-sky-600 rounded-md disabled:opacity-50 disabled:cursor-not-allowed">
                {{ admin ? "Simpan Perubahan" : "Tambah" }}
              </button>
            </div>
          </form>
        </div>
      </section>
    </div>
  </AdminLayout>
</template>
