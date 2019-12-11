<template>
  <div>
    <navbar />
  <div class="jumbotron">
    <!--<h1>Welcome to Virtual Wallet</h1> -->
    <div v-if="this.$store.state.isLogged"> 
    <h1>Welcome {{this.$store.state.user.name}} </h1> 
    <!--<user></user>-->
    </div>
    <br />
    <br />
    <h2>Total Wallets : {{ numWallets }}</h2>
    <br />
    <br />
    <div class="form-group">
      <a class="btn btn-primary" v-on:click.prevent="login">Login</a>
      <a class="btn btn-primary" v-on:click.prevent="register">Register</a>
    </div>
    <div class="form-group"></div>
  </div>
  </div>
</template>

<script type="text/javascript">
import UsersComponent from "./users/user";
import NavbarComponent from "./nav";
export default {
  data: function() {
    return {
      title: "",
      showSuccess: false,
      successMessage: "",
      numWallets: 0
    };
  },
  methods: {
    login() {
     this.$router.push({ path: "/login" });
    },
    register() {
      this.$router.push({ path: "/register" });
    },
    getNumWallets: function() {
      axios.get("api/numwallets").then(response => {
        this.numWallets = response.data;
      });
      //console.log(this.numWallets);
    }
  },
  mounted() {
    this.getNumWallets();
  },
  computed: {
    isLogged: function() {
      return this.$store.state.isLogged;
    }
  },
  components: {
        navbar: NavbarComponent,
  }
};
</script>