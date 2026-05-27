import { createRouter, createWebHistory } from 'vue-router'
import BaseLayout from '@/layouts/BaseLayout.vue'
import HomeComponent from '@/components/HomeComponent.vue'
import FindLocationComponent from '@/components/FindLocationComponent.vue'
import SigninComponent from '@/components/SigninComponent.vue'
import RegisterComponent from '@/components/RegisterComponent.vue'
import RoleComponent from '@/components/RoleComponent.vue'
import ProvincesComponent from '@/components/ProvincesComponent.vue'
import DistrictsComponent from '@/components/DistrictsComponent.vue'
import CommunesComponent from '@/components/CommunesComponent.vue'
import ODComponent from '@/components/ODComponent.vue'
import HCComponent from '@/components/HCComponent.vue'
import VillagesComponent from '@/components/VillagesComponent.vue'
import ResetPasswordComponent from '@/components/ResetPasswordComponent.vue'
import HealthFacilityIndex from '@/components/HealthFacilityIndex.vue'
import FindHealthFacility from "@/components/FindHealthFacility.vue";

const routes = [
  {
    path: '/',
    name: 'login',
    component: SigninComponent,
  },
  {
    path: '/reset-password',
    name: 'reset_password',
    component: ResetPasswordComponent,
  },
  {
    path: '/',
    component: BaseLayout,
    children: [
      {
        path: 'home',
        name: 'home',
        component: HomeComponent,
      },
      {
        path: 'find-location',
        name: 'find_location',
        component: FindLocationComponent,
      },
      {
        path: 'register',
        name: 'register',
        component: RegisterComponent,
      },
      {
        path: 'roles',
        name: 'roles',
        component: RoleComponent,
      },

      {
        path: 'provinces',
        name: 'provinces',
        component: ProvincesComponent,
      },
      {
        path: 'districts',
        name: 'districts',
        component: DistrictsComponent,
      },
      {
        path: 'communes',
        name: 'communes',
        component: CommunesComponent,
      },
      {
        path: 'od',
        name: 'od',
        component: ODComponent,
      },
      {
        path: 'hc',
        name: 'hc',
        component: HCComponent,
      },
      {
        path: 'villages',
        name: 'villages',
        component: VillagesComponent,
      },
      {
        path: 'health-facilities',
        name: 'health-facilities',
        component: HealthFacilityIndex,
      },
        {
            path: "/health-facilities/find-location",
            name: "health-facilities.find-location",
            component: FindHealthFacility,
        },
    ],
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

router.beforeEach((to, from, next) => {
  const userStr = localStorage.getItem('user')
  const user = userStr ? JSON.parse(userStr) : null

  const isLoggedIn = !!user

  // convert role name to lowercase
  const roleName = (user?.role?.name || '').toLowerCase()

  const guestAllowedPaths = ['/', '/reset-password']
  const adminOnlyPaths = ['/home', '/register', '/provinces', '/districts', '/roles']


  if (!isLoggedIn && !guestAllowedPaths.includes(to.path)) {
    return next('/')
  }
  // if (to.path !== '/' && !isLoggedIn) {
  //   return next('/')
  // }

  // staff/user cannot open admin pages
  if (isLoggedIn && roleName !== 'admin' && adminOnlyPaths.includes(to.path)) {
    return next('/find-location')
  }

  next()
})

export default router
