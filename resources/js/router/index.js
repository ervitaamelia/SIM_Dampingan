import { createRouter, createWebHistory } from 'vue-router'
import DashboardAdmin from '@/views/admin/DashboardAdminView.vue'
import DataFasilitator from '@/views/admin/DataFasilitatorView.vue'
import DataAdmin from '@/views/admin/DataAdminView.vue'
import DataMasyarakat from '@/views/admin/DataMasyarakatView.vue'
import DataDampingan from '@/views/admin/DataDampinganView.vue'
import KegiatanDampingan from '@/views/admin/KegiatanDampinganView.vue'

import DashboardFasilitator from '@/views/fasilitator/DashboardFasilitatorView.vue'
import DataKegiatan from '@/views/fasilitator/DataKegiatanView.vue'

import FormAdmin from '@/views/admin/FormAdminView.vue'
import FormDampingan from '@/views/admin/FormDampinganView.vue'
import FormFasilitator from '@/views/admin/FormFasilitatorView.vue'
import FormKegiatan from '@/views/fasilitator/FormKegiatanView.vue'
import FormMasyarakat from '@/views/admin/FormMasyarakatView.vue'

import Login from '@/views/LoginView.vue'
import LupaPass from '@/views/LupaPassView.vue'

import DetailKegiatan from '@/views/DetailKegiatanView.vue'

const router = createRouter({
  history: createWebHistory(),

  routes: [
    {
      path: '/admin/',
      name: 'DashboardAdmin',
      component: DashboardAdmin,
    },
    {
      path: '/admin/data-fasilitator',
      name: 'DataFasilitator',
      component: DataFasilitator,
    },
    {
      path: '/admin/data-admin',
      name: 'DataAdmin',
      component: DataAdmin,
    },
    {
      path: '/admin/data-masyarakat',
      name: 'DataMasyarakat',
      component: DataMasyarakat,
    },
    {
      path: '/admin/data-dampingan',
      name: 'DataDampingan',
      component: DataDampingan,
    },
    {
      path: '/admin/kegiatan-dampingan',
      name: 'KegiatanDampingan',
      component: KegiatanDampingan,
    },
    {
      path: '/fasilitator/',
      name: 'DashboardFasilitator',
      component: DashboardFasilitator,
    },
    {
      path: '/fasilitator/data-kegiatan',
      name: 'DataKegiatan',
      component: DataKegiatan,
    },

    {
      path: '/admin/data-admin/tambah',
      name: 'FormAdmin',
      component: FormAdmin,
    },
    {
      path: '/admin/data-dampingan/tambah',
      name: 'FormDampingan',
      component: FormDampingan,
    },
    {
      path: '/admin/data-fasilitator/tambah',
      name: 'FormFasilitator',
      component: FormFasilitator,
    },
    {
      path: '/admin/data-masyarakat/tambah',
      name: 'FormMasyarakat',
      component: FormMasyarakat,
    },
    {
      path: '/fasilitator/data-kegiatan/tambah',
      name: 'FormKegiatan',
      component: FormKegiatan,
    },

    {
      path: '/',
      name: 'Login',
      component: Login,
    },
    {
      path: '/lupa-password',
      name: 'LupaPass',
      component: LupaPass,
    },
    
    {
      path: '/detail-kegiatan',
      name: 'DetailKegiatan',
      component: DetailKegiatan,
    },
  ],
})

export default router
