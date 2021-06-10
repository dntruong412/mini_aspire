import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(VueRouter);

const routes = [
  {
    path: '/',
    name: 'home',
    component: () => import('@/views/Home.vue'),
  },
  {
    path: '/users',
    name: 'users',
    component: () => import('@/views/Users.vue'),
  },
  {
    path: '/users/:user_id/loan',
    props: true,
    component: () => import('@/views/UserLoan.vue'),
    children: [
      {
        path: '',
        name: 'user_loans',
        props: true,
        component: () => import('@/views/UserLoanList.vue'),
      },
      {
        path: 'form',
        name: 'user_loan_form',
        props: true,
        component: () => import('@/views/UserLoanForm.vue'),
      },
      {
        path: ':user_loan_id/repayment',
        name: 'user_loan_repayment',
        props: true,
        component: () => import('@/views/UserLoanRepayment.vue'),
      },
      {
        path: ':user_loan_id/detail',
        name: 'user_loan_detail',
        props: true,
        component: () => import('@/views/UserLoanDetail.vue'),
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
