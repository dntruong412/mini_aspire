<template>
  <div id="app">
    <Nav class='mb-3'/>
    <router-view/>
  </div>
</template>

<script>
  export default {
    components: {
      Nav: () => import('@/components/Nav')
    }
  }
</script>
