<template>
  <form class="row">
    <div class="col-12 col-sm-6 mb-4">
      <div class="border p-3 rounded">
        <h3 class="mb-4">Repayment</h3>
        <FormMessage ref="formMessage"></FormMessage>
        <div class="row mb-3">
          <div class="col-6 col-sm-4">
            <label for="amount" class="form-label">Amount</label>
            <input type="number" class="form-control text-right" id="amount" v-model="repaymentAmount" />
          </div>
        </div>
        <p>Minimum monthly repayment: {{ monthlyMinRepayment | to_currency }}</p>
        <button class="btn btn-primary" @click.prevent="submitRepayment">Submit</button>
        <router-link class="btn btn-link" :to="{ name: 'user_loans', params: { user_id: user_id } }">Close</router-link>
      </div>
    </div>

    <UserLoanDetail ref='userLoanDetail' class="col-12 mt-3" :user_id='user_id' :user_loan_id='user_loan_id'/>
  </form>
</template>

<script>
import FormData from 'form-data';
import { USER_SUBMIT_REPAYMENT } from '@/store/mutation-types';
import { calculateMonthlyMinRepayment, currencyFormat } from '@/helpers';
import { mapState } from 'vuex';

export default {
  name: 'UserLoanRepayment',
  components: {
    FormMessage: () => import('@/components/FormMessage'),
    UserLoanDetail: () => import('@/views/UserLoanDetail'),
  },
  props: {
    user_id: {
      required: true,
    },
    user_loan_id: {
      required: true,
    },
  },
  data() {
    return {
      repaymentAmount: 0
    };
  },
  computed: {
    ...mapState({
      loan: state => state.users.loan
    }),
    monthlyMinRepayment() {
      return calculateMonthlyMinRepayment({
        amount: this.loan?.amount || 0,
        interest_rate: this.loan?.interest_rate || 0,
        duration: this.loan?.duration || 0,
      });
    },
  },
  filters: {
    to_currency(value) {
      return currencyFormat(value);
    },
  },
  methods: {
    async submitRepayment() {
      const data = new FormData();
      data.append('amount', this.amount);

      let result = {};
      try {
        result = await this.$store.dispatch(USER_SUBMIT_REPAYMENT, {
          user_id: this.user_id,
          data: {
            user_loan_id: this.user_loan_id,
            amount: this.repaymentAmount,
          },
        });
        this.$refs.userLoanDetail.getLoan();
      } catch (error) {
        console.error(error);
      }
      this.$refs.formMessage.display(result);
    },
  },
};
</script>
