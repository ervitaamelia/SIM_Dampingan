<script setup>
import AdminLayout from '@/layouts/AdminLayout.vue'
import Multiselect from '@vueform/multiselect'
import { ref } from 'vue'

const showPopup = ref(false)
const isMenuOpen = ref(false)
const logout = () => {
  console.log('User logged out')
  showPopup.value = false
}

const formData = ref({
  Name: '',
  bidangDampingan: '',
  provinsi: '',
  kabupaten: '',
  daerah: '',
  fasilitator: '',
})

const bidangDampinganOptions = [
  { value: 'tani', label: 'Tani' },
  { value: 'nelayan', label: 'Nelayan' },
]

const provinsiOptions = [
  { value: 'jawaTimur', label: 'Jawa Timur' },
  { value: 'jawaTengah', label: 'Jawa Tengah' },
  { value: 'diy', label: 'DIY' },
]

const kabupatenOptions = [
  { value: 'klaten', label: 'Klaten' },
  { value: 'surakarta', label: 'Surakarta' },
  { value: 'ngawi', label: 'Ngawi' },
]

const daerahOptions = [{ value: 'ngawi', label: 'Ngawi' }]

const fasilitatorList = ref(['']) // Awalnya hanya ada satu dropdown
const fasilitatorOptions = ref([
  { value: '1', label: 'Fasilitator A' },
  { value: '2', label: 'Fasilitator B' },
  { value: '3', label: 'Fasilitator C' },
])

const addFasilitator = () => {
  fasilitatorList.value.push('')
}

const fields = [
  { id: 'fullName', label: 'Nama Lengkap', type: 'text', placeholder: '' },
  { id: 'phone', label: 'No. Telepon', type: 'tel', placeholder: '' },
  { id: 'email', label: 'Email', type: 'email', placeholder: '' },
  { id: 'password', label: 'Password', type: 'password', placeholder: '' },
]

const handleSubmit = () => {
  console.log('Form submitted:', formData.value)
}

const selectedRole = ref('Admin')
const isRoleDropdownOpen = ref(false)
const roleDropdownOptions = ['Batal', 'Keluar']

const toggleRoleDropdown = () => {
  isRoleDropdownOpen.value = !isRoleDropdownOpen.value
}

const buttonLabel = ref('Keluar')

const selectRole = role => {
  selectedRole.value = role
  buttonLabel.value = role === 'Admin' ? 'Batal' : 'Keluar'
  isRoleDropdownOpen.value = false
}
const tambahData = () => {
  console.log('Tombol ditekan!')
}
</script>


<template>
  <AdminLayout>
    <div class="ml-5 w-full max-md:w-full mx-auto flex justify-center">
      <section
        class="flex flex-col items-center px-8 pt-6 pb-8 mt-12 w-full max-w-4xl bg-white rounded-[20px] shadow-lg overflow-y-auto scrollbar-hide max-h-[80vh]"
      >
        <div class="flex flex-col w-full">
          <h2 class="self-center text-2xl font-bold text-black">
            Form Tambah Dampingan
          </h2>
          <form @submit.prevent="handleSubmit" class="mt-6 w-full">
            <!-- Nama Dampingan -->
            <div class="flex flex-col gap-2 pb-2">
              <label for="Name" class="text-sm font-medium text-gray-600"
                >Nama Dampingan</label
              >
              <input
                id="Name"
                type="text"
                v-model="formData.Name"
                class="w-full py-2 px-3 mt-1 border border-gray-400 rounded-md outline-none text-sm"
                placeholder="Masukkan Nama Dampingan"
              />
            </div>

            <div class="flex flex-col gap-2 pb-2">
              <label
                for="bidangDampingan"
                class="text-sm font-medium text-gray-600"
              >
                Bidang Dampingan
              </label>

              <Multiselect
                v-model="formData.bidangDampingan"
                :options="bidangDampinganOptions"
                placeholder="Pilih Bidang Dampingan"
                class="w-full border border-gray-300 rounded-md shadow-sm text-sm"
                :searchable="true"
              />
            </div>

            <div class="flex flex-col gap-2 pb-2">
              <label for="provinsi" class="text-sm font-medium text-gray-600">
                Provinsi
              </label>

              <Multiselect
                v-model="formData.provinsi"
                :options="provinsiOptions"
                placeholder="Pilih Provinsi"
                class="w-full border border-gray-300 rounded-md shadow-sm text-sm"
                :searchable="true"
              />
            </div>

            <div class="flex flex-col gap-2 pb-2">
              <label for="kabupaten" class="text-sm font-medium text-gray-600">
                Kabupaten
              </label>

              <Multiselect
                v-model="formData.kabupaten"
                :options="kabupatenOptions"
                placeholder="Pilih Kabupaten"
                class="w-full border border-gray-300 rounded-md shadow-sm text-sm"
                :searchable="true"
              />
            </div>

            <div class="flex flex-col gap-2 pb-2">
              <label class="text-sm font-medium text-gray-600"
                >Fasilitator</label
              >

              <div
                v-for="(item, index) in fasilitatorList"
                :key="index"
                class="flex gap-2 items-center"
              >
                <!-- Multiselect untuk memilih fasilitator -->
                <Multiselect
                  v-model="fasilitatorList[index]"
                  :options="fasilitatorOptions"
                  placeholder="Pilih Fasilitator"
                  class="w-full border border-gray-300 rounded-md shadow-sm text-sm"
                  :searchable="true"
                />

                <!-- Tombol tambah fasilitator -->
                <button
                  @click="addFasilitator"
                  class="p-2 bg-blue-500 text-white rounded-md"
                >
                  +
                </button>
              </div>
            </div>

            <div class="flex justify-between mt-4">
              <a
                href="DataDampingan"
                type="submit"
                class="px-6 py-2 text-sm font-medium text-white bg-sky-600 rounded-md"
              >
                Tambah
              </a>
              <a
                href="DataDampingan"
                type="submit"
                class="px-6 py-2 text-sm font-medium text-white bg-sky-600 rounded-md"
              >
                Batal
              </a>
            </div>
          </form>
        </div>
      </section>
    </div>
  </AdminLayout>
</template>