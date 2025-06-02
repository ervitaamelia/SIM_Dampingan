<script setup>
import InputError from '@/Components/InputError.vue';
import { Head, useForm } from '@inertiajs/vue3';

defineProps({
  status: {
    type: String,
  },
});

const form = useForm({
  email: '',
});

const submit = () => {
  form.post(route('password.email'));
};
</script>

<template>
  <main class="flex flex-col md:flex-row h-screen w-screen bg-white overflow-hidden">
    <Head title="Lupa Password" />

    <!-- Bagian Form -->
    <section class="w-full md:w-1/2 h-full flex items-center justify-center px-6 md:px-10">
      <form
        @submit.prevent="submit"
        class="flex flex-col w-full max-w-md text-sm tracking-wide"
      >
        <header class="text-center mb-8">
          <h1 class="text-3xl md:text-4xl font-medium tracking-wider text-black uppercase">
            Lupa Password
          </h1>
        </header>

        <div class="mb-4 text-sm text-gray-600 text-center">
          Silakan masukkan alamat email Anda. Kami akan mengirimkan tautan untuk mereset kata sandi melalui email.
        </div>

        <div v-if="status" class="mb-4 text-sm font-medium text-green-600 text-center">
          {{ status }}
        </div>

        <div class="flex flex-col mb-4">
          <label for="email" class="mb-2 font-medium text-neutral-900">Email</label>
          <input
            type="email"
            id="email"
            v-model="form.email"
            class="px-4 py-3 rounded-xl border border-black border-opacity-30 shadow-sm text-zinc-600"
            placeholder="example@email.com"
            required
            autofocus
            autocomplete="username"
          />
          <InputError class="mt-2" :message="form.errors.email" />
        </div>

        <button
          type="submit"
          :disabled="form.processing"
          class="w-full py-3 font-medium text-white bg-sky-600 rounded-xl shadow hover:bg-blue-700 transition-colors flex justify-center items-center"
          :class="{ 'opacity-50': form.processing }"
        >
          Kirim Link Reset Password
        </button>
      </form>
    </section>

    <!-- Bagian Gambar -->
    <section class="w-1/2 h-full overflow-hidden hidden md:flex items-center justify-center">
      <img
        src="https://cdn.builder.io/api/v1/image/assets/99e98c75e74449b086557677558acabb/565513d8e8b2eff5bf3fd018f49e7907f07b7b0998074991ec9da8a42f4ad693?placeholderIfAbsent=true"
        alt="Forgot Password Illustration"
        class="w-full h-full object-cover object-[50%_30%]"
      />
    </section>
  </main>
</template>