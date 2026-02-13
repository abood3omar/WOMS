import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import LoginView from '../views/LoginView.vue'
import DashboardView from '../views/DashboardView.vue'
import WorkOrdersView from '../views/WorkOrdersView.vue'
import LogsView from '../views/LogsView.vue'
import ProfileView from '../views/ProfileView.vue'
import UsersView from '../views/security/UsersView.vue'
import RolesView from '../views/security/RolesView.vue'
import ModulesView from '../views/security/ModulesView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/login',
      name: 'login',
      component: LoginView,
      meta: { guest: true }
    },
    {
      path: '/',
      name: 'dashboard',
      component: DashboardView,
      meta: { requiresAuth: true }
    },
    {
      path: '/work-orders',
      name: 'work-orders',
      component: WorkOrdersView,
      meta: { requiresAuth: true }
    },
    {
      path: '/account',
      name: 'account',
      component: ProfileView,
      meta: { requiresAuth: true }
    },
    {
      path: '/logs',
      name: 'logs',
      component: LogsView,
      meta: { requiresAuth: true }
    },
    {
      path: '/security/users',
      name: 'users',
      component: UsersView,
      meta: { requiresAuth: true }
    },
    {
      path: '/security/roles',
      name: 'roles',
      component: RolesView,
      meta: { requiresAuth: true }
    },
    {
      path: '/security/modules',
      name: 'modules',
      component: ModulesView,
      meta: { requiresAuth: true }
    },
    {
    path: '/my-orders',
    name: 'my-orders',
    component: () => import('../views/MyOrdersView.vue'),
    meta: { requiresAuth: true }
    }
  ]
})

router.beforeEach((to, from, next) => {
  const authStore = useAuthStore();
  
  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    next('/login');
  } 


  else if (to.meta.guest && authStore.isAuthenticated) {
    next('/'); 
  } 
  else {
    next(); 
  }
});

export default router