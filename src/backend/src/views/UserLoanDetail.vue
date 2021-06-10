<template>
  <div>
    <h5>Loan</h5>
    <table class="table table-bordered table-sm mb-4">
      <thead>
        <tr class="bg-light">
          <th class="text-center">Amount</th>
          <th class="text-center">Duration <br /><small class="fw-normal">(Months)</small></th>
          <th class="text-center">Debt</th>
          <th class="text-center">Interest rate <br /><small class="fw-normal">(%)</small></th>
          <th class="text-center">Repayment<br />frequency</th>
          <th class="text-center">Arrangement<br />fee</th>
          <th class="text-center">Payment<br />status</th>
          <th class="text-center">Created</th>
          <th class="text-center">Updated</th>
          <th>&nbsp;</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="loan in loans" :key="loan.id">
          <td class="text-end">{{ loan.amount | to_currency }}</td>
          <td class="text-end">{{ loan.duration }}</td>
          <td class="text-end">{{ loan.debt | to_currency }}</td>
          <td class="text-end">{{ loan.interest_rate }}</td>
          <td class="text-end">{{ loan.repayment_frequency }}</td>
          <td class="text-end">{{ loan.arrangement_fee | to_currency }}</td>
          <td class="text-end">{{ loan.payment_status | to_loan_status }}</td>
          <td>{{ loan.created_at }}</td>
          <td>{{ loan.updated_at }}</td>
          <td>
            <router-link
              v-if="isNotPaid(loan.payment_status)"
              class="btn btn-link"
              :to="{ name: 'user_loan_repayment', params: { user_id: user_id, user_loan_id: loan.id } }"
            >
              Repay
            </router-link>
          </td>
        </tr>
      </tbody>
    </table>

    <div v-if="loan_repayments.length > 0">
      <h5 class="text-pri">Repayments</h5>
      <table class="table table-bordered table-sm">
        <thead>
          <tr class="bg-light">
            <th>#</th>
            <th>Amount</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(payment, index) in loan_repayments" :key="payment.id">
            <td>{{ index + 1 }}</td>
            <td>{{ payment.amount | to_currency }}</td>
            <td>{{ payment.created_at }}</td>
          </tr>
        </tbody>
      </table>
    </div>

    <p>
      <router-link
        class="btn btn-link"
        :to="{ name: 'user_loans', params: { user_id: user_id } }"
      >
        Back
      </router-link>
    </p>
  </div>
</template>

<script>
import { mapState } from 'vuex';
import { USER_GET_LOAN, USER_GET_LOAN_REPAYMENTS } from '@/store/mutation-types';
import { currencyFormat } from '@/helpers';

export default {
  name: 'UserLoanDetail',
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
      loans: [],
    };
  },
  computed: {
    ...mapState({
      loan_repayments: (state) => state.users.loan_repayments,
    }),
  },
  filters: {
    to_currency(value) {
      return currencyFormat(value);
    },
    to_loan_status(value) {
      return value == 2 ? 'Paid' : '_';
    },
  },
  async created() {
    this.getLoan();
    this.getLoanRepayments();
  },
  methods: {
    isNotPaid(payment_status) {
      return payment_status != 2;
    },
    async getLoan() {
      const { data } = await this.$store.dispatch(USER_GET_LOAN, {
        user_id: this.user_id,
        user_loan_id: this.user_loan_id,
      });
      this.loans = [data];
    },
    async getLoanRepayments() {
      this.$store.dispatch(USER_GET_LOAN_REPAYMENTS, {
        user_id: this.user_id,
        user_loan_id: this.user_loan_id,
      });
    },
  },
};
</script>
