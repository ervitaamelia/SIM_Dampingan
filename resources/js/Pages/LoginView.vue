<script setup>
import { ref } from 'vue'

const email = ref('')
const password = ref('')
const rememberMe = ref(false)

const handleSubmit = () => {
  console.log('Form submitted:', {
    email: email.value,
    password: password.value,
    rememberMe: rememberMe.value,
  })
}

const handleForgotPassword = () => {
  console.log('Forgot password clicked')
}
</script>

<template>
  <main class="flex h-screen w-screen bg-white max-md:flex-col">
    <!-- Form Login -->
    <section
      class="w-1/2 h-full flex items-center justify-center px-10 max-md:w-full max-md:h-auto max-md:py-10"
    >
      <form
        @submit.prevent="handleSubmit"
        class="flex flex-col w-full max-w-md text-sm tracking-wide"
      >
        <header class="text-center mb-10">
          <h1
            class="text-4xl font-medium tracking-wider text-black uppercase max-md:text-3xl"
          >
            login
          </h1>
          <p class="mt-6 text-zinc-600 text-center">
            Selamat Datang di Website Resmi MPM - Mentora<br />
            Masukkan Email dan Password Anda!
          </p>
        </header>

        <!-- Email -->
        <div class="flex flex-col mb-4">
          <label for="email" class="mb-2 font-medium text-neutral-900"
            >Email</label
          >
          <input
            id="email"
            type="email"
            v-model="email"
            placeholder="Masukkan Email"
            class="px-4 py-3 rounded-xl border border-black border-opacity-30 shadow-sm text-zinc-600"
          />
        </div>

        <!-- Password -->
        <div class="flex flex-col mb-4">
          <label for="password" class="mb-2 font-medium text-neutral-900"
            >Password</label
          >
          <input
            id="password"
            type="password"
            v-model="password"
            placeholder="**********"
            class="px-4 py-3 rounded-xl border border-black border-opacity-30 shadow-sm text-zinc-600"
          />
        </div>

        <!-- Remember Me & Lupa Password -->
        <div
          class="flex justify-between items-center text-xs font-medium text-neutral-900 mb-6"
        >
          <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" v-model="rememberMe" class="hidden" />
            <div
              class="w-5 h-5 rounded border border-black border-opacity-30 flex items-center justify-center"
              :class="{ 'bg-sky-600': rememberMe }"
            >
              <svg
                v-if="rememberMe"
                class="w-4 h-4 text-white"
                viewBox="0 0 20 20"
                fill="currentColor"
              >
                <path
                  fill-rule="evenodd"
                  d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                  clip-rule="evenodd"
                />
              </svg>
            </div>
            Ingat Saya
          </label>

          <a href="/Forget" class="text-sky-600 hover:underline">
            Lupa Password
          </a>
        </div>

        <!-- Submit Button -->
        <a
          href="DashboardAdmin"
          class="w-full py-3 font-medium text-white bg-sky-600 rounded-xl shadow hover:bg-sky-700 transition-colors flex justify-center items-center"
        >
          Masuk
        </a>
      </form>
    </section>

    <!-- Gambar Samping -->
    <section class="w-full md:w-1/2 h-full overflow-hidden">
      <img
        src="https://cdn.builder.io/api/v1/image/assets/99e98c75e74449b086557677558acabb/565513d8e8b2eff5bf3fd018f49e7907f07b7b0998074991ec9da8a42f4ad693?placeholderIfAbsent=true"
        alt="Login illustration"
        class="w-full h-full object-cover object-[50%_30%]"
      />
    </section>
  </main>
</template>