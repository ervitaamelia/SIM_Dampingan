<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { onMounted, computed, ref, watch } from "vue";
import { Head, useForm } from "@inertiajs/vue3";
import Multiselect from '@vueform/multiselect';
import '@vueform/multiselect/themes/default.css';

const props = defineProps({
  fasilitator: Object,
  bidangs: Array,
});

const showPassword = ref(false);
const togglePassword = () => {
  showPassword.value = !showPassword.value;
};

const form = useForm(props.fasilitator ? {
  name: props.fasilitator.name,
  username: props.fasilitator.username,
  nomor_telepon: props.fasilitator.nomor_telepon,
  alamat: props.fasilitator.alamat,
  foto: null,
  bidang_dampingan: props.fasilitator.bidang_dampingan || [""],
  _method: props.fasilitator ? 'PUT' : 'POST'
} : {
  name: "",
  username: "",
  password: "",
  nomor_telepon: "",
  alamat: "",
  foto: null,
  bidang_dampingan: [""],
});

const getBidangOptions = (index) => {
  const selectedIds = form.bidang_dampingan
    .filter((_, i) => i !== index);

  return props.bidangs
    .filter(bidang => !selectedIds.includes(String(bidang.id_bidang)))
    .map(bidang => ({
      value: String(bidang.id_bidang),
      label: bidang.nama_bidang,
    }));
};

const addBidang = () => {
  form.bidang_dampingan.push("");
};

const removeBidang = (index) => {
  if (form.bidang_dampingan.length > 1) {
    form.bidang_dampingan.splice(index, 1);
  }
};

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
      id: props.fasilitator?.id ?? ""
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

const isFormValid = computed(() => {
  return (
    form.name.trim() !== "" &&
    form.username.trim() !== "" &&
    !usernameError.value &&
    (props.fasilitator || (form.password.trim() !== "" && form.password.length >= 8)) &&
    form.nomor_telepon.trim() !== "" &&
    form.alamat.trim() !== "" &&
    form.bidang_dampingan.every(bidang => bidang !== "")
  );
});

const handleSubmit = () => {
  if (!form.foto) {
    delete form.foto; // hanya kirim jika ada file baru
  }

  if (props.fasilitator) {
    // Untuk update, gunakan post dengan _method PUT
    form.post(route('fasilitator.update', props.fasilitator.id), {
      forceFormData: true,
      preserveScroll: true,
      onSuccess: () => form.reset(),
    })
  } else {
    form.post(route('fasilitator.store'), {
      preserveScroll: true,
      onSuccess: () => form.reset(),
    })
  }
};
</script>

<template>
  <AdminLayout>

    <Head :title="props.fasilitator ? 'Edit Fasilitator' : 'Tambah Fasilitator'" />

    <div class="ml-5 w-full max-md:w-full mx-auto flex justify-center">
      <section
        class="flex flex-col items-center px-8 pt-6 pb-8 mt-12 w-full max-w-4xl bg-white rounded-[20px] shadow-lg overflow-y-auto scrollbar-hide max-h-[80vh]">
        <div class="flex flex-col w-full">
          <h2 class="self-center text-2xl font-bold text-black">
            {{ props.fasilitator ? "Form Edit Fasilitator" : "Form Tambah Fasilitator" }}
          </h2>

          <form @submit.prevent="handleSubmit" enctype="multipart/form-data" class="mt-6 w-full">

            <p class="text-sm text-gray-600 mb-4">
              Kolom yang ditandai dengan <span class="text-red-500 font-semibold">*</span> wajib diisi.
            </p>

            <!-- Nama Lengkap -->
            <div class="flex flex-col gap-2 pb-2">
              <label for="name" class="text-sm font-medium text-gray-600">Nama Lengkap <span
                  class="text-red-500">*</span></label>
              <input id="name" type="text" v-model="form.name"
                class="w-full py-2 px-3 mt-1 border border-gray-400 rounded-md outline-none text-sm"
                placeholder="Masukkan Nama Lengkap" />
            </div>

            <!-- Alamat -->
            <div class="flex flex-col gap-2 pb-2">
              <label for="alamat" class="text-sm font-medium text-gray-600">Alamat <span
                  class="text-red-500">*</span></label>
              <textarea id="alamat" v-model="form.alamat" rows="4"
                class="w-full py-3 px-3 mt-1 border border-gray-400 rounded-md outline-none text-base resize-none"
                placeholder="Masukkan Alamat"></textarea>
            </div>

            <!-- No. Telepon -->
            <div class="flex flex-col gap-2 pb-2">
              <label for="nomor_telepon" class="text-sm font-medium text-gray-600">No. Telepon <span
                  class="text-red-500">*</span></label>
              <input id="nomor_telepon" type="tel" v-model="form.nomor_telepon"
                class="w-full py-2 px-3 mt-1 border border-gray-400 rounded-md outline-none text-sm"
                placeholder="Masukkan No. Telepon" />
            </div>

            <!-- Bidang Dampingan -->
            <div class="flex flex-col gap-2 pb-2">
              <label class="text-sm font-medium text-gray-600">Bidang Dampingan <span
                  class="text-red-500">*</span></label>

              <div v-for="(bidang, index) in form.bidang_dampingan" :key="index" class="flex items-center gap-2 mb-2">
                <Multiselect v-model="form.bidang_dampingan[index]" :options="getBidangOptions(index)"
                  placeholder="Pilih Bidang" class="w-full border border-gray-300 rounded-md shadow-sm text-sm"
                  :searchable="true" />
                <button type="button" @click="addBidang"
                  class="bg-green-500 text-white px-3 py-1 rounded-md text-sm hover:bg-green-600"
                  v-if="index === form.bidang_dampingan.length - 1">
                  +
                </button>
                <button type="button" @click="removeBidang(index)"
                  class="bg-red-500 text-white px-3 py-1 rounded-md text-sm hover:bg-red-600"
                  v-if="form.bidang_dampingan.length > 1">
                  -
                </button>
              </div>
            </div>

            <!-- Username -->
            <div class="flex flex-col gap-2 pb-2">
              <label for="username" class="text-sm font-medium text-gray-600">Username <span
                  class="text-red-500">*</span></label>
              <input id="username" type="text" v-model="form.username"
                class="w-full py-2 px-3 mt-1 border rounded-md outline-none text-sm" :class="{
                  'border-gray-400': !usernameError,
                  'border-red-500': usernameError
                }"
                placeholder="Masukkan Username" />
              <div v-if="usernameError" class="text-red-500 text-sm">
                {{ usernameError }}
              </div>
              <div v-else-if="usernameValid && form.username" class="text-green-500 text-sm">
                Username tersedia.
              </div>
            </div>

            <!-- Password dengan toggle icon -->
            <div v-if="!props.fasilitator" class="flex flex-col gap-2 mb-2">
              <label for="password" class="text-sm font-medium text-gray-600">Password <span
                  class="text-red-500">*</span></label>
              <div class="relative">
                <input id="password" :type="showPassword ? 'text' : 'password'" v-model="form.password"
                  placeholder="Masukkan Password"
                  class="w-full py-2 px-3 border border-gray-400 rounded-md outline-none text-sm" 
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

            <!-- Foto -->
            <div class="flex flex-col gap-2 pb-2">
              <label class="text-sm font-medium text-gray-600">Foto</label>
              <input type="file" @change="form.foto = $event.target.files[0]"
                class="w-full py-2 px-3 border border-gray-400 rounded-md outline-none text-sm" />
            </div>
            <div v-if="props.fasilitator?.foto" class="mb-4">
              <img :src="`/storage/${props.fasilitator.foto}`" alt="Foto Fasilitator" class="w-32 rounded" />
              <p class="text-xs text-gray-500 mt-1">Foto saat ini</p>
            </div>

            <div class="flex gap-4 mt-4 justify-end">
              <a :href="route('fasilitator.index')"
                class="px-6 py-2 text-sm font-medium text-white bg-gray-500 rounded-md">
                Batal
              </a>
              <button type="submit" :disabled="!isFormValid" :class="[
                'px-6 py-2 text-sm font-medium rounded-md',
                isFormValid ? 'bg-sky-600 text-white' : 'bg-sky-600 text-white opacity-50 cursor-not-allowed'
              ]">
                {{ props.fasilitator ? "Simpan Perubahan" : "Tambah" }}
              </button>
            </div>

          </form>
        </div>
      </section>
    </div>
  </AdminLayout>
</template>