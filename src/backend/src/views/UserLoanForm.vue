<template>
  <form class="row">
    <div class="col-12 col-sm-6">
      <div class="border p-3 rounded">
        <FormMessage ref="formMessage"></FormMessage>
        <div class="row mb-3">
          <div class="col-6 col-sm-4">
            <label for="amount" class="form-label fw-bold">Amount</label>
            <input type="number" class="form-control text-right" id="amount" v-model="amount" />
          </div>
          <div class="error" v-if="!$v.amount.required">Field is required</div>
          <div class="error" v-if="!$v.amount.numeric">
            Amount must be numeric.
          </div>
          <div class="error" v-if="!$v.amount.minValue">
            Amount must be greater than {{$v.amount.$params.minValue.min}}.
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-6 col-sm-4">
            <label for="duration" class="form-label fw-bold">Duration (months)</label>
            <select class="form-select" id="duration" v-model="duration">
              <option v-for="(item, index) in durationMapping" :key="`duration_${index}`">
                {{ item.duration }}
              </option>
            </select>
            <div class="error" v-if="!$v.duration.required">Field is required</div>
            <div class="error" v-if="!$v.duration.between">
              Duration must be between {{ $v.duration.$params.between.min }} and {{ $v.duration.$params.between.max }}.
            </div>
          </div>
          <div class="col-6 col-sm-4">
            <label for="interest_rate" class="form-label fw-bold">Interest rate</label>
            <div class="input-group">
              <input type="number" class="form-control text-right" id="interest_rate" v-model="interest_rate" />
              <span class="input-group-text" id="basic-addon1">%</span>
            </div>
            <div class="error" v-if="!$v.interest_rate.required">Field is required</div>
            <div class="error" v-if="!$v.interest_rate.numeric">
              Interest rate must be numeric.
            </div>
          </div>
          <div class="col-6 col-sm-4">
            <label for="repayment_frequency" class="form-label fw-bold">Repayment frequency</label>
            <input
              type="number"
              class="form-control text-right"
              id="repayment_frequency"
              v-model="repayment_frequency"
              disabled="disabled"
            />
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-6 col-sm-4">
            <label for="arrangement_fee" class="form-label fw-bold">Arrangement fee</label>
            <input type="number" class="form-control text-right" id="arrangement_fee" v-model="arrangement_fee" />
          </div>
          <div class="error" v-if="!$v.arrangement_fee.required">Field is required</div>
          <div class="error" v-if="!$v.arrangement_fee.numeric">
            Arrangement fee must be numeric.
          </div>
        </div>
        <div class="row mb-4">
          <div class="col-6 col-sm-4">
            <label for="arrangement_fee" class="form-label fw-bold">Monthly min repayment</label>
            <input class="form-control text-right" :value="minMonthlyRepayment" disabled="disabled" />
          </div>
        </div>
        <button class="btn btn-primary" @click.prevent="submitLoan">Submit</button>
        <router-link class="btn btn-link" :to="{ name: 'user_loans', params: { user_id: user_id } }">Close</router-link>
      </div>
    </div>
  </form>
</template>

<style lang="css">
.error {
  color: #d25f5f;
}
</style>

<script>
import FormData from 'form-data';
import { durationMapping } from '@/const';
import { calculateMonthlyMinRepayment } from '@/helpers';
import { USER_SUBMIT_LOAN } from '@/store/mutation-types';
import { required, between, numeric, minValue } from 'vuelidate/lib/validators';
import { validationMixin } from 'vuelidate';

export default {
  name: 'UserLoanForm',
  mixins: [validationMixin],
  components: {
    FormMessage: () => import('@/components/FormMessage'),
  },
  props: {
    user_id: {
      required: true,
    },
  },
  data() {
    return {
      amount: 0,
      duration: 0,
      interest_rate: 0,
      repayment_frequency: 0,
      arrangement_fee: 0,

      durationMapping: durationMapping,
    };
  },
  validations: {
    amount: {
      required,
      numeric,
      minValue: minValue(1),
    },
    duration: {
      required,
      numeric,
      between: between(1, 12),
    },
    interest_rate: {
      required,
      numeric,
    },
    arrangement_fee: {
      required,
      numeric,
    },
  },
  computed: {
    minMonthlyRepayment() {
      if (this.amount <= 0 || this.duration <= 0 || this.interest_rate <= 0 || this.repayment_frequency <= 0) {
        return 0;
      }
      return calculateMonthlyMinRepayment({
        amount: this.amount,
        interest_rate: this.interest_rate,
        duration: this.duration,
      });
    },
  },
  watch: {
    duration(value) {
      const selectedDuration = this.durationMapping.find((d) => d.duration == value);
      if (selectedDuration) {
        this.interest_rate = selectedDuration.interest_rate;
        this.repayment_frequency = selectedDuration.repayment_frequency;
        this.arrangement_fee = selectedDuration.arrangement_fee;
      }
    },
  },
  methods: {
    async submitLoan() {
      this.$v.$reset();
      this.$v.$touch();
      if (this.$v.$invalid) {
        return;
      }

      const data = new FormData();
      data.append('amount', this.amount);
      data.append('duration', this.duration);
      data.append('interest_rate', this.interest_rate);
      data.append('arrangement_fee', this.arrangement_fee);
      data.append('repayment_frequency', this.repayment_frequency);

      let result = {};
      try {
        result = await this.$store.dispatch(USER_SUBMIT_LOAN, {
          user_id: this.user_id,
          data,
        });
      } catch (error) {
        console.error(error);
      }
      this.$refs.formMessage.display(result);
      if (result.status == 1) {
        this.$router.push({
          name: 'user_loan_detail',
          params: {
            user_id: this.user_id,
            user_loan_id: result.data.id,
          },
        });
      }
    },
  },
};
</script>
