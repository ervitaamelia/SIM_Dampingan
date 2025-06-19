<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { ref, watch, onMounted, computed } from 'vue'
import { Head, useForm } from '@inertiajs/vue3'
import Multiselect from '@vueform/multiselect'
import '@vueform/multiselect/themes/default.css'

const { admin, auth } = defineProps({
  admin: Object,
  auth: Object
})

const errors = ref({})

const showPassword = ref(false);
const togglePassword = () => {
  showPassword.value = !showPassword.value;
};

// Role options berdasarkan user yang login
const roleOptions = computed(() => {
  if (auth.user.role === 'superadmin') {
    return [
      { value: 'superadmin', label: 'Superadmin' },
      { value: 'admin-provinsi', label: 'Admin Provinsi' },
      { value: 'admin-kabupaten', label: 'Admin Kabupaten' },
      { value: 'admin-kecamatan', label: 'Admin Kecamatan' }
    ]
  } else if (auth.user.role === 'admin-provinsi') {
    return [
      { value: 'admin-kabupaten', label: 'Admin Kabupaten' },
      { value: 'admin-kecamatan', label: 'Admin Kecamatan' }
    ]
  } else if (auth.user.role === 'admin-kabupaten') {
    return [
      { value: 'admin-kecamatan', label: 'Admin Kecamatan' }
    ]
  }
  return []
})

const form = useForm({
  name: '',
  username: '',
  password: '',
  nomor_telepon: '',
  alamat: '',
  role: '',
  kode_provinsi: '',
  kode_kabupaten: '',
  kode_kecamatan: ''
})

const selectedRole = ref(null)

// Data wilayah
const provinsiList = ref([])
const kabupatenList = ref([])
const kecamatanList = ref([])

// Fungsi untuk fetch data wilayah
function fetchProvinsi() {
  fetch('/api/provinsi')
    .then(res => res.json())
    .then(data => {
      provinsiList.value = data.map(item => ({
        label: item.nama,
        value: item.kode
      }))
    })
}

function fetchKabupaten(kodeProvinsi) {
  form.kode_kabupaten = ''
  form.kode_kecamatan = ''
  kecamatanList.value = []

  if (auth.user.role === 'admin-kabupaten') {
    // Untuk admin kabupaten, langsung set kabupaten mereka saja
    kabupatenList.value = [{
      label: auth.user.nama_kabupaten, // Asumsi ada field nama_kabupaten
      value: auth.user.kode_kabupaten
    }]
    form.kode_kabupaten = auth.user.kode_kabupaten
  } else if (kodeProvinsi) {
    // Untuk role lain, fetch dari API seperti biasa
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

async function fetchKecamatan(kodeKabupaten) {
  form.kode_kecamatan = ''
  kecamatanList.value = []

  if (kodeKabupaten) {
    const res = await fetch(`/api/kecamatan/${kodeKabupaten}`)
    const data = await res.json()
    kecamatanList.value = data.map(item => ({
      label: item.nama,
      value: item.kode
    }))
  }
}

onMounted(() => {
  fetchProvinsi()

  // Jika edit admin, set nilai form dan fetch wilayah
  if (admin) {
    form.name = admin.name
    form.username = admin.username
    form.nomor_telepon = admin.nomor_telepon
    form.alamat = admin.alamat
    form.kode_provinsi = admin.kode_provinsi
    form.kode_kabupaten = admin.kode_kabupaten
    form.kode_kecamatan = admin.kode_kecamatan
    form.role = admin.role
    selectedRole.value = roleOptions.value.find(option => option.value === admin.role)

    if (admin.kode_provinsi) {
      fetchKabupaten(admin.kode_provinsi).then(() => {
        if (admin.kode_kabupaten) {
          fetchKecamatan(admin.kode_kabupaten)
        }
      })
    }
  }
  // Jika tambah admin, set role dan wilayah berdasarkan user yang login
  else {
    // Auto set wilayah untuk admin wilayah
    if (auth.user.role === 'admin-provinsi') {
      form.kode_provinsi = auth.user.kode_provinsi
      fetchKabupaten(auth.user.kode_provinsi)
    }
    else if (auth.user.role === 'admin-kabupaten') {
      form.kode_provinsi = auth.user.kode_provinsi
      form.kode_kabupaten = auth.user.kode_kabupaten

      // Langsung set kabupatenList dengan kabupaten admin yang login
      kabupatenList.value = [{
        label: auth.user.kabupaten.nama, // Pastikan data ini tersedia di auth.user
        value: auth.user.kode_kabupaten
      }]

      // Langsung fetch kecamatan dari kabupaten admin
      fetchKecamatan(auth.user.kode_kabupaten)
    }
  }
})

watch(selectedRole, (val) => {
  if (val) {
    form.role = val.value
  }
})

// Watch perubahan provinsi dan kabupaten
watch(() => form.kode_provinsi, (val) => {
  fetchKabupaten(val)
})

watch(() => form.kode_kabupaten, (val) => {
  fetchKecamatan(val)
})

const usernameError = ref("");
const usernameValid = ref(false);

watch(() => form.username, async (newValue) => {
  usernameError.value = "";
  usernameValid.value = false;

  if (!newValue) return;

  // Cek apakah ada spasi
  if (/\s/.test(newValue)) {
    usernameError.value = "Username tidak boleh mengandung spasi.";
    return;
  }

  try {
    const query = new URLSearchParams({
      username: newValue,
      id: admin?.id ?? ""
    });

    const res = await fetch(`/api/check-username?${query}`);
    const data = await res.json();

    if (data.exists) {
      usernameError.value = "Username sudah digunakan.";
    } else {
      usernameValid.value = true;
    }
  } catch (e) {
    console.error("Gagal memeriksa username:", e);
  }
});

const isFormIncomplete = computed(() => {
  if (!form.name || !form.username || !form.nomor_telepon || !form.alamat) return true
  if (!admin && (!form.password || form.password.length < 8)) return true
  if (!form.role) return true

  if (form.role === 'admin-kabupaten' && !form.kode_kabupaten) return true
  if (form.role === 'admin-kecamatan' && !form.kode_kecamatan) return true

  if (usernameError.value) return true

  return false
})

const handleSubmit = () => {
  if (admin) {
    form.put(`/admin/data-admin/${admin.id}`)
  } else {
    form.post('/admin/data-admin')
  }
}
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

            <p class="text-sm text-gray-600 mb-4">
              Kolom yang ditandai dengan <span class="text-red-500 font-semibold">*</span> wajib diisi.
            </p>

            <!-- Role -->
            <div class="flex flex-col gap-2 pb-2" v-if="!admin && roleOptions.length > 0">
              <label for="role" class="text-sm font-medium text-gray-600">Role <span
                  class="text-red-500">*</span></label>
              <select id="role" v-model="selectedRole"
                class="w-full py-3 px-3 mt-1 border border-gray-300 rounded-md shadow-sm text-base">
                <option disabled value="">Pilih Role</option>
                <option v-for="option in roleOptions" :key="option.value" :value="option">
                  {{ option.label }}
                </option>
              </select>
            </div>

            <!-- Nama -->
            <div class="flex flex-col gap-2 pb-2">
              <label for="name" class="text-sm font-medium text-gray-600">Nama <span
                  class="text-red-500">*</span></label>
              <input id="name" type="text" v-model="form.name"
                class="w-full py-3 px-3 mt-1 border border-gray-400 rounded-md outline-none text-base"
                placeholder="Masukkan Nama Lengkap" />
            </div>


            <!-- No. Telepon -->
            <div class="flex flex-col gap-2 pb-2">
              <label for="nomor_telepon" class="text-sm font-medium text-gray-600">No. Telepon <span
                  class="text-red-500">*</span></label>
              <input id="nomor_telepon" type="text" v-model="form.nomor_telepon"
                class="w-full py-3 px-3 mt-1 border border-gray-400 rounded-md outline-none text-base"
                placeholder="Masukkan Nomor Telepon" />
            </div>

            <!-- Username -->
            <div class="flex flex-col gap-2 pb-2">
              <label for="username" class="text-sm font-medium text-gray-600">Username <span
                  class="text-red-500">*</span></label>
              <input id="username" type="text" v-model="form.username"
                class="w-full py-3 px-3 mt-1 border rounded-md outline-none text-base" :class="{
                  'border-gray-400': !usernameError,
                  'border-red-500': usernameError
                }" placeholder="Masukkan Username" />
              <div v-if="usernameError" class="text-red-500 text-sm">
                {{ usernameError }}
              </div>
              <div v-else-if="usernameValid && form.username" class="text-green-500 text-sm">
                Username tersedia.
              </div>
            </div>

            <!-- Password dengan toggle icon -->
            <div v-if="!admin" class="flex flex-col gap-2 pb-2">
              <label for="password" class="text-sm font-medium text-gray-600">Password <span
                  class="text-red-500">*</span></label>
              <div class="relative">
                <input :type="showPassword ? 'text' : 'password'" v-model="form.password"
                  placeholder="Masukkan Password" class="w-full py-2 px-3 border border-gray-400 rounded-md"
                  :class="{ 'border-red-500': form.password && form.password.length < 8 }" />
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
              <div v-if="form.password && form.password.length < 8" class="text-red-500 text-sm">
                Password harus terdiri dari minimal 8 karakter.
              </div>
            </div>

            <!-- Alamat -->
            <div class="flex flex-col gap-2 pb-2">
              <label for="alamat" class="text-sm font-medium text-gray-600">Alamat <span
                  class="text-red-500">*</span></label>
              <textarea id="alamat" v-model="form.alamat" rows="4"
                class="w-full py-3 px-3 mt-1 border border-gray-400 rounded-md outline-none text-base resize-none"
                placeholder="Masukkan Alamat"></textarea>
            </div>

            <!-- Wilayah sesuai role -->
            <div class="flex flex-col gap-2 pb-2">
              <!-- Untuk admin-provinsi (hanya bisa buat admin kabupaten/kecamatan) -->
              <div v-if="auth.user.role === 'admin-provinsi' && !admin">
                <!-- Kabupaten -->
                <div v-if="form.role === 'admin-kabupaten' || form.role === 'admin-kecamatan'">
                  <label for="kode_kabupaten" class="text-sm font-medium text-gray-600">Kabupaten <span
                      class="text-red-500">*</span></label>
                  <Multiselect v-model="form.kode_kabupaten" :options="kabupatenList" placeholder="Pilih Kabupaten"
                    :searchable="true" class="w-full" />
                </div>

                <!-- Kecamatan (hanya untuk role admin-kecamatan) -->
                <div v-if="form.role === 'admin-kecamatan'">
                  <label for="kode_kecamatan" class="text-sm font-medium text-gray-600">Kecamatan <span
                      class="text-red-500">*</span></label>
                  <Multiselect v-model="form.kode_kecamatan" :options="kecamatanList" placeholder="Pilih Kecamatan"
                    :searchable="true" class="w-full" :disabled="!form.kode_kabupaten" />
                </div>
              </div>

              <!-- Untuk admin-kabupaten (hanya bisa buat admin kecamatan) -->
              <div v-else-if="auth.user.role === 'admin-kabupaten' && !admin && form.role === 'admin-kecamatan'">
                <!-- Kabupaten (disabled dan otomatis terisi) -->
                <div class="mb-4">
                  <label for="kode_kabupaten" class="text-sm font-medium text-gray-600">Kabupaten <span
                      class="text-red-500">*</span></label>
                  <Multiselect v-model="form.kode_kabupaten" :options="kabupatenList" placeholder="Kabupaten"
                    class="w-full" :disabled="true" />
                </div>

                <!-- Kecamatan -->
                <div>
                  <label for="kode_kecamatan" class="text-sm font-medium text-gray-600">Kecamatan <span
                      class="text-red-500">*</span></label>
                  <Multiselect v-model="form.kode_kecamatan" :options="kecamatanList" placeholder="Pilih Kecamatan"
                    :searchable="true" class="w-full" />
                </div>
              </div>

              <!-- Untuk superadmin (bisa buat semua role) -->
              <div v-else-if="auth.user.role === 'superadmin'">
                <!-- Provinsi -->
                <div v-if="['admin-provinsi', 'admin-kabupaten', 'admin-kecamatan'].includes(form.role)">
                  <label for="kode_provinsi" class="text-sm font-medium text-gray-600">Provinsi <span
                      class="text-red-500">*</span></label>
                  <Multiselect v-model="form.kode_provinsi" :options="provinsiList" placeholder="Pilih Provinsi"
                    :searchable="true" class="w-full" />
                </div>

                <!-- Kabupaten -->
                <div v-if="['admin-kabupaten', 'admin-kecamatan'].includes(form.role)">
                  <label for="kode_kabupaten" class="text-sm font-medium text-gray-600">Kabupaten <span
                      class="text-red-500">*</span></label>
                  <Multiselect v-model="form.kode_kabupaten" :options="kabupatenList" placeholder="Pilih Kabupaten"
                    :searchable="true" class="w-full" :disabled="!form.kode_provinsi" />
                </div>

                <!-- Kecamatan -->
                <div v-if="form.role === 'admin-kecamatan'">
                  <label for="kode_kecamatan" class="text-sm font-medium text-gray-600">Kecamatan <span
                      class="text-red-500">*</span></label>
                  <Multiselect v-model="form.kode_kecamatan" :options="kecamatanList" placeholder="Pilih Kecamatan"
                    :searchable="true" class="w-full" :disabled="!form.kode_kabupaten" />
                </div>
              </div>
            </div>

            <!-- Tombol Submit -->
            <div class="flex gap-4 mt-4 justify-end">
              <a :href="route('admin.index')"
                class="px-6 py-2 text-sm font-medium text-white bg-gray-500 rounded-md">Batal</a>
              <button type="submit" :disabled="isFormIncomplete && !admin"
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