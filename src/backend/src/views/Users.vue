<template>
  <div class="container-fluid">
    <h1>Users page</h1>

    <div class="row">
      <div class="col-12 col-sm-6">
        <table class="table table-bordered">
          <thead>
            <tr class="bg-light">
              <th>#</th>
              <th>Name</th>
              <th>&nbsp;</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="user in availableUsers" :key="user.id">
              <td>{{ user.id }}</td>
              <td>{{ user.name }}</td>
              <td>
                <router-link class="btn btn-link" :to="{ name: 'user_loans', params: { user_id: user.id } }">
                  Loan
                </router-link>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
  import { mapGetters } from 'vuex';
  import { GET_USERS } from '@/store/mutation-types';

  export default {
    name: 'Users',
    computed: {
      ...mapGetters(['availableUsers']),
    },
    created() {
      this.$store.dispatch(GET_USERS, {
        page: 1,
      });
    },
  };
</script>
