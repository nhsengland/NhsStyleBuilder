<template>
  <div id="app">
    <main>
      <div id="kdcontainer" class="builder">
        <div id="id_menu-column" class="menu-column" role="navigation">Side menu<br>Constructing...</div>
        <builder></builder>
      </div>
    </main>
  </div>
</template>

<script>
import Builder from './components/Builder'

export default {
  name: 'app',
  components: {
    Builder
  }
}
</script>

<style>
body {
  margin: 0;
  font-size: 16px;
}

#app {
  font-family: "Frutiger W01", Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  color: #2c3e50;
  padding: 20px;
}

.builder a{
  cursor: pointer;
  /* text-decoration: none; */
}
.builder a:hover{
  /* text-decoration: none; */
}
input, textarea, select{
  font-family: "Frutiger W01", Arial, sans-serif;
}
</style>
<style scoped>

</style>
