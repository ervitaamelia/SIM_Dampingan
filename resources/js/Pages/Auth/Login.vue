<script setup>
import { ref, computed } from 'vue'
import { Head, usePage, useForm } from '@inertiajs/vue3'

const form = useForm({
  username: '',
  password: '',
  remember: false,
})

const showPassword = ref(false)

const togglePassword = () => {
  showPassword.value = !showPassword.value
}

const page = usePage()
const adminPhone = '6289507291774'

const waResetLink = computed(() => {
  const message = `Halo Admin, saya ingin mereset password akun saya dengan username:`
  return `https://wa.me/${adminPhone}?text=${encodeURIComponent(message)}`
})

const submit = () => {
  form.post(route('login'), {
    onFinish: () => form.reset('password'),
  })
}
</script>

<template>

  <Head title="Login" />
  <main class="flex h-screen w-screen bg-white max-md:flex-col">

    <!-- Form Login -->
    <section class="w-1/2 h-full flex items-center justify-center px-10 max-md:w-full max-md:h-auto max-md:py-10">
      <form @submit.prevent="submit" class="flex flex-col w-full max-w-md text-sm tracking-wide" method="POST">
        <header class="text-center mb-10">
          <h1 class="text-4xl font-medium tracking-wider text-black uppercase max-md:text-3xl">
            login
          </h1>
          <p class="mt-6 text-zinc-600 text-center">
            Selamat Datang di Website Resmi MPM - Mentora<br />
            Masukkan Username dan Password Anda!
          </p>
        </header>

        <!-- Username -->
        <div class="flex flex-col mb-4">
          <label for="username" class="mb-2 font-medium text-neutral-900">Username</label>
          <input id="username" type="text" v-model="form.username" placeholder="Masukkan Username Anda"
            class="px-4 py-3 rounded-xl border border-black border-opacity-30 shadow-sm text-zinc-600" required />
          <span v-if="form.errors.username" class="text-red-500 text-xs mt-1">
            {{ form.errors.username }}
          </span>
        </div>

        <!-- Password dengan icon toggle -->
        <div class="flex flex-col mb-4">
          <label for="password" class="mb-2 font-medium text-neutral-900">Password</label>
          <div class="relative">
            <input id="password" :type="showPassword ? 'text' : 'password'" v-model="form.password"
              placeholder="Masukkan Password Anda"
              class="w-full px-4 py-3 rounded-xl border border-black border-opacity-30 shadow-sm text-zinc-600"
              required />
            <button type="button" @click="togglePassword"
              class="absolute inset-y-0 right-3 flex items-center justify-center focus:outline-none">
              <svg v-if="showPassword" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-zinc-500" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <!-- eye-off icon -->
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M13.875 18.825A10.05 10.05 0 0112 19c-5.523 0-10-4.477-10-10a9.97 9.97 0 012.192-6.174M3 3l18 18M9.88 9.88a3 3 0 104.24 4.24" />
              </svg>
              <svg v-else xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-zinc-500" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <!-- eye icon -->
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.944 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
              </svg>
            </button>
          </div>
          <span v-if="form.errors.password" class="text-red-500 text-xs mt-1">
            {{ form.errors.password }}
          </span>
        </div>

        <!-- Remember Me & Lupa Password -->
        <div class="flex justify-between items-center text-xs font-medium text-neutral-900 mb-6">
          <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" v-model="form.remember" class="hidden" />
            <div class="w-5 h-5 rounded border border-black border-opacity-30 flex items-center justify-center"
              :class="{ 'bg-sky-600': form.remember }">
              <svg v-if="form.remember" class="w-4 h-4 text-white" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd"
                  d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                  clip-rule="evenodd" />
              </svg>
            </div>
            Ingat Saya
          </label>

          <a :href="waResetLink" target="_blank" class="text-sky-600 hover:underline">
            Lupa Password
          </a>
        </div>

        <!-- Submit Button -->
        <button type="submit"
          class="w-full py-3 font-medium text-white bg-sky-600 rounded-xl shadow hover:bg-sky-700 transition-colors flex justify-center items-center"
          :class="{ 'opacity-50': form.processing }" :disabled="form.processing">
          Masuk
        </button>
      </form>
    </section>

    <!-- Gambar Samping -->
    <section class="w-full md:w-1/2 h-full overflow-hidden">
      <img
        src="https://cdn.builder.io/api/v1/image/assets/99e98c75e74449b086557677558acabb/565513d8e8b2eff5bf3fd018f49e7907f07b7b0998074991ec9da8a42f4ad693?placeholderIfAbsent=true"
        alt="Login illustration" class="w-full h-full object-cover object-[50%_30%]" />
    </section>
  </main>
</template>