<template>
  <div>
    <div v-if="show" class="alert alert-dismissible" :class="type" role="alert">
      <div>
        <div :class="messageClass">
          {{ message }}
        </div>
        <ul v-if="status == 0 && Object.keys(data).length > 0">
          <li v-for="(item, index) in data" :key="index">
            {{ item[0] }}
          </li>
        </ul>
      </div>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" @click="show = false"></button>
    </div>
  </div>
</template>

<script>
export default {
  name: 'FormMessage',
  data() {
    return {
      show: false,
      status: 0,
      type: null,
      message: null,
      data: {},
    };
  },
  computed: {
    messageClass() {
      if (this.status == 0) {
        return 'fst-normal';
      }
      if (Object.keys(this.data).length == 0) {
        return 'fst-normal';
      }
      return 'fw-bold';
    },
  },
  methods: {
    display(info) {
      this.type = info.status == 1 ? 'alert-success' : 'alert-danger';
      this.status = info.status;
      this.message = info.message;
      this.data = info.data || {};
      this.show = true;
    },
  },
};
</script>
