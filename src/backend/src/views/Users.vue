<template>
  <div class="container-fluid">
    <h1>Users page</h1>

    <paginate
      v-if="pageCount > 0"
      :page-count="pageCount"
      :click-handler="changePage"
      :prev-text="'Prev'"
      :next-text="'Next'"
      :container-class="'pagination'"
      :page-class="'page-item'"
      :prev-class="'page-item'"
      :next-class="'page-item'"
      :page-link-class="'page-link'"
      :prev-link-class="'page-link'"
      :next-link-class="'page-link'"
    >
    </paginate>

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
            <tr v-for="user in users" :key="user.id">
              <td class="fw-bold">{{ user.id }}</td>
              <td>{{ user.name }}</td>
              <td>
                <router-link class="btn btn-link" :to="{ name: 'user_loan_form', params: { user_id: user.id } }">
                  Loan
                </router-link>
                <router-link class="btn btn-link" :to="{ name: 'user_loans', params: { user_id: user.id } }">
                  View
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
import { mapState } from 'vuex';
import { GET_USERS } from '@/store/mutation-types';

export default {
  name: 'Users',
  data() {
    return {
      pageCount: 0,
      currentPage: 1,
    };
  },
  computed: {
    ...mapState({
      users: (state) => state.users.users,
    }),
  },
  created() {
    this.currentPage = this.$route.query?.page || 1;
    this.changePage(this.currentPage);
  },
  methods: {
    changePage(pageNumber) {
      this.$store
        .dispatch(GET_USERS, {
          page: pageNumber,
        })
        .then((data) => {
          this.pageCount = data.last_page;
        });
    },
  },
};
</script>
