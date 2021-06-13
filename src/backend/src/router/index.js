import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(VueRouter);

const routes = [
  {
    path: '/',
    name: 'home',
    component: () => import('@/views/Home'),
  },
  {
    path: '/users',
    component: () => import('@/views/Users'),
    children: [
      {
        path: '',
        name: 'user_list',
        component: () => import('@/views/UserList'),
      },
      {
        path: 'add',
        name: 'user_add',
        component: () => import('@/views/UserAdd'),
      },
      {
        path: ':user_id',
        name: 'user_detail',
        props: true,
        component: () => import('@/views/UserDetail'),
      },
    ],
  },
  {
    path: '/users/:user_id/loan',
    props: true,
    component: () => import('@/views/UserLoan'),
    children: [
      {
        path: '',
        name: 'user_loans',
        props: true,
        component: () => import('@/views/UserLoanList'),
      },
      {
        path: 'form',
        name: 'user_loan_form',
        props: true,
        component: () => import('@/views/UserLoanForm'),
      },
      {
        path: ':user_loan_id/repayment',
        name: 'user_loan_repayment',
        props: true,
        component: () => import('@/views/UserLoanRepayment'),
      },
      {
        path: ':user_loan_id/detail',
        name: 'user_loan_detail',
        props: true,
        component: () => import('@/views/UserLoanDetail'),
      },
    ],
  },
];

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes,
});

export default router;
