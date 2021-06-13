<template>
  <form class="row">
    <div class="col-12 col-sm-6">
      <div class="border p-3 rounded">
        <FormMessage ref="formMessage"></FormMessage>
        <div class="row mb-3">
          <div class="col-6 col-sm-4">
            <label for="name" class="form-label fw-bold">Name</label>
            <input type="text" class="form-control text-right" id="name" v-model="name" />
          </div>
          <div class="error" v-if="!$v.name.required">Field is required</div>
          <div class="error" v-if="!$v.name.minLength">Name must have at least {{ $v.name.$params.minLength.min }} letters.</div>
          <div class="error" v-if="!$v.name.maxLength">Name must have less than {{ $v.name.$params.maxLength.max }} letters.</div>
        </div>
        <button class="btn btn-primary" @click.prevent="updateUser">Update</button>
        <router-link class="btn btn-link" :to="{ name: 'user_list' }">Close</router-link>
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
import { USER_GET, USER_UPDATE } from '@/store/mutation-types';
import { required, minLength, maxLength } from 'vuelidate/lib/validators';
import { validationMixin } from 'vuelidate';

export default {
  name: 'UserDetail',
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
      name: '',
    };
  },
  validations: {
    name: {
      required,
      minLength: minLength(1),
      maxLength: maxLength(15),
    },
  },
  created() {
    this.$store.dispatch(USER_GET, this.user_id).then((response) => {
      if (response.status == 1) {
        this.name = response.data.name;
      }
    });
  },
  methods: {
    async updateUser() {
      this.$v.$reset();
      this.$v.$touch();
      if (this.$v.$invalid) {
        return;
      }

      const data = new FormData();
      data.append('name', this.name);

      let result = {};
      try {
        result = await this.$store.dispatch(USER_UPDATE, {
          user_id: this.user_id,
          info: data,
        });
      } catch (error) {
        console.error(error);
      }
      this.$refs.formMessage.display(result);
    },
  },
};
</script>
