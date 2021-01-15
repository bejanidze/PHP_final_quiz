import Vue from 'vue'
import VueRouter from 'vue-router'
import store from '@/store/index'
import Home from '@/views/Home.vue'
import About from '@/views/About.vue'
import Quiz from '@/views/Quiz.vue'
import Quizzes from '@/views/Quizzes.vue'
import Login from '@/components/Login.vue'
import Profile from '@/components/Profile.vue'
import Register from '@/components/Register.vue'
//admin
import AdminQuizzess from '@/views/admin/Quizzes.vue';
import AddQuestion from '@/views/admin/AddQuestion.vue';
import AdminStudents from '@/views/admin/Students.vue';

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home,
    meta: {
      requiresAuth: true
    }
  },
  {
    path: '/about',
    name: 'About',
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () => import(/* webpackChunkName: "about" */ '../views/About.vue')
  },
    {
        path: '/login',
        name: 'login',
        component: Login
    },
    {
        path: '/register',
        name: 'register',
        component: Register
    },
    {
        path: '/profile',
        name: 'profile',
        component: Profile,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/quizzes',
        name: 'quizzes',
        component: Quizzes,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/quizzes/:id',
        name: 'quiz',
        component: Quiz,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/admin/quizzes',
        name: 'adminquizzes',
        component: AdminQuizzess,
        meta: {
            requiresAdmin:true
        }
    },
    {
        path: '/admin/quizzes/:id',
        name: 'AddQuestion',
        component: AddQuestion,
        meta: {
            requiresAdmin:true
        }
    },
    {
        path: '/admin/students',
        name: 'adminstudents',
        component: AdminStudents,
        meta: {
            requiresAdmin:true
        }
    }
];

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
});
router.beforeEach((to, from, next) => {

    if(to.matched.some(record => record.meta.requiresAuth)) {
        if (store.getters.isLoggedIn) {
            next();
            return
        }
        next('/login')
    }else if (to.matched.some(record => record.meta.requiresAdmin)) {
        if (store.getters.isLoggedIn) {
            if (store.state.user.role) {
                next();
                return
            }
            next('/')
        }else{
            next('/login')
        }
    }else {
        next();
    }
});
export default router
