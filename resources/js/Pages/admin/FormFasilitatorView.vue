<script setup>
import AdminLayout from '@/layouts/AdminLayout.vue'
import { ref } from 'vue'

const isMenuOpen = ref(false)
const showPopup = ref(false)

const logout = () => {
  console.log('User logged out')
  showPopup.value = false
}

const formData = ref({
  role: '',
  fullName: '',
  phone: '',
  email: '',
  password: '',
})

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
            Form Tambah Fasilitator
          </h2>
          <form @submit.prevent="handleSubmit" class="mt-6 w-full">
            <!-- Nama Lengkap -->
            <div class="flex flex-col gap-2 pb-2">
              <label for="fullName" class="text-sm font-medium text-gray-600"
                >Nama Lengkap</label
              >
              <input
                id="fullName"
                type="text"
                v-model="formData.fullName"
                class="w-full py-2 px-3 mt-1 border border-gray-400 rounded-md outline-none text-sm"
                placeholder="Masukkan Nama Lengkap"
              />
            </div>

            <!-- No. Telepon -->
            <div class="flex flex-col gap-2 pb-2">
              <label for="phone" class="text-sm font-medium text-gray-600"
                >No. Telepon</label
              >
              <input
                id="phone"
                type="tel"
                v-model="formData.phone"
                class="w-full py-2 px-3 mt-1 border border-gray-400 rounded-md outline-none text-sm"
                placeholder="Masukkan No. Telepon"
              />
            </div>

            <!-- Email -->
            <div class="flex flex-col gap-2 pb-2">
              <label for="email" class="text-sm font-medium text-gray-600"
                >Email</label
              >
              <input
                id="email"
                type="email"
                v-model="formData.email"
                class="w-full py-2 px-3 mt-1 border border-gray-400 rounded-md outline-none text-sm"
                placeholder="Masukkan Email"
              />
            </div>

            <!-- Password -->
            <div class="flex flex-col gap-2 pb-2">
              <label for="password" class="text-sm font-medium text-gray-600"
                >Password</label
              >
              <input
                id="password"
                type="password"
                v-model="formData.password"
                class="w-full py-2 px-3 mt-1 border border-gray-400 rounded-md outline-none text-sm"
                placeholder="Masukkan Password"
              />
            </div>

            <div class="flex justify-between mt-4">
              <a
                href="DataFasilitator"
                type="submit"
                class="px-6 py-2 text-sm font-medium text-white bg-sky-600 rounded-md"
              >
                Tambah
              </a>
              <a
                href="DataFasilitator"
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
