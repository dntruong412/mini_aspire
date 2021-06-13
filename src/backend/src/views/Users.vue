<template>
  <div class="container-fluid">
    <h1 class="d-inline-block p-0 mr-3 mb-4">
      Users
      <router-link class="btn btn-sm btn-primary" :to="{ name: 'user_add' }">
        <i class="fas fa-plus"></i>
        Add new
      </router-link>
    </h1>

    <router-view></router-view>
  </div>
</template>

<script>
export default {
  name: 'Users',
};
</script>
