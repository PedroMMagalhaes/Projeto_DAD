<template>
  <div>
    <navbar />
    <div class="jumbotron">
      <!--<h1>Welcome to Virtual Wallet</h1> -->
      <div v-if="this.$store.state.user"> 
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
      <div class="form-group">
        
      </div>
      
      </div>
      <div class="row">
        <div class="col-md-12">
          <h5>Last 10 days with movements</h5>
          <LineChart
            :chart-data="dataMovDays"></LineChart>
        </div>
      </div>
  </div>
</template>

<script type="text/javascript">
import UsersComponent from "./users/user";
import NavbarComponent from "./nav";
import LineChart from './LineChart.js';

export default {
  data: function() {
    return {
      title: "",
      showSuccess: false,
      successMessage: "",
      dataMovDays: null,
      timeMovDays: [0,1,2,3,4,5,6,7,8,9],
      countMovDays: [0,1,2,3,4,5,6,7,8,9],
      loaded: false,
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
    },
    fillChartMovDays(){
      this.dataMovDays = {
        labels: this.timeMovDays,
        datasets: [
          {
            label: 'Movements',
            backgroundColor: 'rgba(0 ,0, 255, 0.2)',
            borderColor: 'lightblue',
            pointBackgroundColor: 'blue',
            borderWidth: 1,
            pointBorderColor: 'blue',
            data: this.countMovDays
          }
        ]
      }
    },
    getChartMovDays(){
      axios.get("api/graph/movements/totalMovements", { "headers": { "Authorization": 'Bearer '.concat(this.$store.state.token) } }).then(response => {
          var results = response.data;
          var mov = [];
          var tim = [];
          for (var i = response.data.length-1; i >= 0 ; i--) {
              mov.push(response.data[i].movements);
              tim.push(response.data[i].day);
          }
          this.countMovDays = mov;
          this.timeMovDays = tim;
          this.fillChartMovDays();
      });
    }
  },
  async mounted() {
    this.getNumWallets();
    this.fillChartMovDays();
    await this.getChartMovDays();
    
  },
  computed: {
    isLogged: function() {
      return this.$store.state.isLogged;
    }
  },
  components: {
        navbar: NavbarComponent,
        LineChart
  }
};
</script>