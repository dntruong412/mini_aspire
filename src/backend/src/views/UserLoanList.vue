<template>
  <div>
    <table class="table table-bordered table-striped table-sm">
      <thead>
        <tr class="bg-light">
          <th class="text-center">#</th>
          <th class="text-center">Amount</th>
          <th class="text-center">Duration <br /><small class="fw-normal">(Months)</small></th>
          <th class="text-center">Debt</th>
          <th class="text-center">Interest rate <br /><small class="fw-normal">(%)</small></th>
          <th class="text-center">Repayment<br />frequency</th>
          <th class="text-center">Arrangement<br />fee</th>
          <th class="text-center">Payment<br />status</th>
          <th class="text-center">Created</th>
          <th class="text-center">Updated</th>
          <th class="text-center" colspan="2">&nbsp;</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(loan, index) in loans" :key="loan.id">
          <td class="fw-bold">{{ index + 1 }}</td>
          <td class="text-end">{{ loan.amount | to_currency }}</td>
          <td class="text-end">{{ loan.duration }}</td>
          <td class="text-end">{{ loan.debt | to_currency }}</td>
          <td class="text-end">{{ loan.interest_rate }}</td>
          <td class="text-end">{{ loan.repayment_frequency }}</td>
          <td class="text-end">{{ loan.arrangement_fee | to_currency }}</td>
          <td class="text-end">{{ loan.payment_status | to_loan_status }}</td>
          <td>{{ loan.created_at }}</td>
          <td>{{ loan.updated_at }}</td>
          <td class="text-center">
            <router-link
              class="btn btn-primary btn-sm"
              :to="{ name: 'user_loan_detail', params: { user_id: user_id, user_loan_id: loan.id } }"
            >
              <i class="fas fa-info"></i>
              Detail
            </router-link>
          </td>
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
  </div>
</template>

<script>
import { USER_GET_LOANS } from '@/store/mutation-types';
import { currencyFormat } from '@/helpers';

export default {
  name: 'UserLoanList',
  props: {
    user_id: {
      required: true,
    },
  },
  data() {
    return {
      loans: [],
    };
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
    const { data } = await this.$store.dispatch(USER_GET_LOANS, {
      user_id: this.user_id,
    });
    this.loans = data;
  },
  methods: {
    isNotPaid(payment_status) {
      return payment_status != 2;
    },
  },
};
</script>
