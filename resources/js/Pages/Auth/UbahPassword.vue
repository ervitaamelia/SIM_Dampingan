<script setup>
import { Head, useForm } from '@inertiajs/vue3'
import InputError from '@/Components/InputError.vue'

const form = useForm({
  password: '',
  password_confirmation: '',
})

function submit() {
  form.post(route('password.change.update'), {
    onFinish: () => form.reset('password', 'password_confirmation'),
  })
}
</script>

<template>
  <main class="flex flex-col md:flex-row h-screen w-screen bg-white overflow-hidden">
    <Head title="Ubah Password" />

    <!-- Form Section -->
    <section class="w-full md:w-1/2 h-full flex items-center justify-center px-6 md:px-10">
      <form
        @submit.prevent="submit"
        class="flex flex-col w-full max-w-md text-sm tracking-wide"
      >
        <header class="text-center mb-8">
          <h1 class="text-3xl md:text-4xl font-medium tracking-wider text-black uppercase">
            Ubah Password
          </h1>
        </header>

        <div class="mb-4 text-sm text-gray-600 text-center">
          Password Anda sebelumnya telah direset oleh admin. Silakan masukkan password baru Anda di bawah ini.
        </div>

        <div class="flex flex-col mb-4">
          <label for="password" class="mb-2 font-medium text-neutral-900">Password Baru</label>
          <input
            type="password"
            id="password"
            v-model="form.password"
            class="px-4 py-3 rounded-xl border border-black border-opacity-30 shadow-sm text-zinc-600"
            placeholder="**********"
            required
            autocomplete="new-password"
          />
          <InputError class="mt-2" :message="form.errors.password" />
        </div>

        <div class="flex flex-col mb-4">
          <label for="password_confirmation" class="mb-2 font-medium text-neutral-900">Konfirmasi Password</label>
          <input
            type="password"
            id="password_confirmation"
            v-model="form.password_confirmation"
            class="px-4 py-3 rounded-xl border border-black border-opacity-30 shadow-sm text-zinc-600"
            placeholder="**********"
            required
            autocomplete="new-password"
          />
          <InputError class="mt-2" :message="form.errors.password_confirmation" />
        </div>

        <button
          type="submit"
          :disabled="form.processing"
          class="w-full py-3 font-medium text-white bg-sky-600 rounded-xl shadow hover:bg-blue-700 transition-colors flex justify-center items-center"
          :class="{ 'opacity-50': form.processing }"
        >
          Simpan Password
        </button>
      </form>
    </section>

    <!-- Image Section -->
    <section class="w-1/2 h-full overflow-hidden hidden md:flex items-center justify-center">
      <img
        src="https://cdn.builder.io/api/v1/image/assets/99e98c75e74449b086557677558acabb/565513d8e8b2eff5bf3fd018f49e7907f07b7b0998074991ec9da8a42f4ad693?placeholderIfAbsent=true"
        alt="Gambar Ilustrasi Ubah Password"
        class="w-full h-full object-cover object-[50%_30%]"
      />
    </section>
  </main>
</template>