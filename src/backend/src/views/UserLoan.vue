<template>
  <div class="container-fluid">
    <h1 v-if="!!user && !!user.name" class="d-inline-block p-0 mr-3 mb-4">
      {{ user.name }}
      <router-link class="btn btn-sm btn-primary" :to="{ name: 'user_loan_form', params: { user_id: user_id } }">
        <i class="fas fa-plus"></i>
        Loan
      </router-link>
    </h1>
    <router-view></router-view>
  </div>
</template>

<script>
import { mapState } from 'vuex';
import { USER_GET } from '@/store/mutation-types';

export default {
  name: 'UserLoan',
  props: {
    user_id: {
      required: true,
    },
  },
  computed: {
    ...mapState({
      user: (state) => state.users.user,
    }),
  },
  created() {
    this.$store.dispatch(USER_GET, this.user_id);
  },
};
</script>
