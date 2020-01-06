<template>
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <a class="navbar-brand" href="/">Virtual Wallet</a>
    <button
      class="navbar-toggler"
      type="button"
      data-toggle="collapse"
      data-target="#navbarNav"
      aria-controls="navbarNav"
      aria-expanded="false"
      aria-label="Toggle navigation"
    >
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li v-if="this.$store.state.user && this.$store.state.user.type=='Admin'" class="nav-item active"> 
          <router-link class="nav-link" active-class="active" to="/users">Users</router-link>
        </li>
        <li class="nav-item">
          <li v-if="this.$store.state.user" class="nav-item active"> 
          <router-link class="nav-link" active-class="active" to="/profile">Profile</router-link>
        </li>
        <li class="nav-item">
          <li v-if="this.$store.state.user && this.$store.state.user.type=='Operator'" class="nav-item active"> 
          <router-link class="nav-link" active-class="active" to="/createcredit">Create Credit Movement</router-link>
        </li>
        <li v-if="this.$store.state.user == null || this.$store.state.user.type=='Admin'" class="nav-item"> 
          <router-link class="nav-link" active-class="active" to="/register">Register</router-link>
        </li>
        <li v-if="!this.$store.state.user" class="nav-item">
          <router-link class="nav-link" active-class="active" to="/login">Login</router-link>
          <!--<a class="nav-link disabled" href="#">Disabled</a> -->
        </li>
        <li class="nav-item">
          <li v-if="this.$store.state.user && this.$store.state.user.type=='Platform User'" class="nav-item active"> 
          <router-link class="nav-link" active-class="active" to="/wallet">Wallet</router-link>
          <!--<a class="nav-link disabled" href="#">Disabled</a> -->
        </li>
        <li class="nav-item">
          <li v-if="this.$store.state.user && this.$store.state.user.type=='Platform User'" class="nav-item active"> 
          <router-link class="nav-link" active-class="active" to="/createdebit">Create Debit Movement</router-link>
        </li>
        <li class="nav-item">
          <li v-if="this.$store.state.user" class="nav-item active"> 
          <router-link class="nav-link" active-class="active" to="/logout">Logout</router-link>
          <!--<a class="nav-link disabled" href="#">Disabled</a> -->
        </li>
      </ul>
    </div>
    <span v-if="this.$store.state.user != null" class="navbar-text">
      {{ this.$store.state.user.name  }}
      <img
        style="border-radius: 50%"
        :src="'/storage/fotos/' + this.$store.state.user.photo"
        width="40"
        height="40"
        alt
      />
    </span>
    <router-view></router-view>
  </nav>
</template>
<script>
export default {
  name: "WalletAPP",
  components: {

  },
  mounted(){
    
    //console.log(this.$store.state.user.type)
  }
  
};
</script>
