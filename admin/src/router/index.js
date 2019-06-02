import Vue from 'vue'
import Router from 'vue-router'
import AuthUser from '@/components/auth/User'
import AuthGroup from '@/components/auth/Group'
import AuthMenu from '@/components/auth/Menu'
import Game from '@/components/Game'
import Faq from '@/components/Faq'
import FaqAdd from '@/components/FaqAdd'
import FaqUpdate from '@/components/FaqUpdate'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/',
      component: AuthUser
    },
    {
      path: '/admin/auth/user/list',
      component: AuthUser
    },
    {
        path: '/admin/auth/group/list',
        component: AuthGroup
    },
    {
        path: '/admin/auth/menu/list',
        component: AuthMenu
    },
    {
        path: '/admin/game/list',
        component: Game
    },
    {
        path: '/admin/faq/list',
        component: Faq
    },
    {
        path: '/admin/faq/add',
        component: FaqAdd
    },
    {
        path: '/admin/faq/update',
        component: FaqUpdate
    },
  ]
})
