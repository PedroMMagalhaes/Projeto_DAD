<template>
  <div>
    <navbar/>
    <div class="jumbotron" align="center">
      <h1>{{ title }}</h1><br><br>

      <h3>Balance: {{ currentBalance }}<strong></strong></h3>
      <div class="row"> 
        <div class="col-md-4">
          <h5>Expenses - Last 30 days</h5>
          <PieChart
            :chart-data="dataExpenses"></PieChart>
        </div>
        <div class="col-md-4">
          <h5>Incomes - Last 30 days</h5>
          <PieChart
            :chart-data="dataIncomes"></PieChart>
        </div>
        <div class="col-md-4">
          <h5>12 Months Comparation</h5>
          <LineChart
            :chart-data="dataIncExp"></LineChart>
        </div>
      </div>
    </div>
    
    <movement-list
      :movements="movements"
      @edit-click="editMovement"
      @message="childMessage"
      ref="movementsListRef"
    ></movement-list>
    <div class="alert alert-success" v-if="showSuccess">
      <button type="button" class="close-btn" v-on:click="showSuccess=false">&times;</button>
      <strong>{{ successMessage }}</strong>
    </div>
    <movement-edit
      :movement="currentMovement"
      :categories="currentCategories"
      @movement-saved="savedMovement"
      @movement-canceled="cancelEdit"
      v-if="currentMovement"
    ></movement-edit>
  </div>
</template>
<script type="text/javascript">
import MovementList from "./movementList.vue";
import MovementEdit from "./movementEdit.vue";
import PieChart from '../PieChart.js';
import LineChart from '../LineChart.js';

export default {
  data: function() {
    return {
      title: this.$store.state.user.name + " Wallet",
      showSuccess: false,
      successMessage: "",
      loggedUser: this.$store.state.user,
      currentBalance: 0,
      currentMovement: null,
      currentCategories: null,
      dataExpenses: null,
      catExpenses: [0,1,2,3,4,5,6,7,8,9],
      countExpenses: [0,1,2,3,4,5,6,7,8,9],
      dataIncomes: null,
      catIncomes: [0,1,2,3,4,5,6,7,8,9],
      countIncomes: [0,1,2,3,4,5,6,7,8,9],
      dataIncExp: null,
      timeIncExp: [0,1,2,3,4,5,6,7,8,9],
      incIncExp: [0,1,2,3,4,5,6,7,8,9],
      expIncExp: [0,1,2,3,4,5,6,7,8,9],
      movements: {}
    };
  },
  methods: {
    editMovement: function(movement) {
      this.currentMovement = movement;
      this.getCategories();
      this.showSuccess = false;
    },
    savedMovement: function() {
      this.currentMovement = null;
      this.showSuccess = true;
      this.successMessage = "Movement Saved";
    },
    cancelEdit: function() {
      this.currentMovement = null;
      this.showSuccess = false;
    },
    getBalance: function() {
      axios.get("api/wallets/"+this.$store.state.user.id, { "headers": { "Authorization": 'Bearer '.concat(this.$store.state.token) } }).then(response => {
        this.currentBalance = response.data.data.balance;
      });
    },
    childMessage: function(message) {
      this.showSuccess = true;
      this.successMessage = message;
    },
    getCategories: function(){
      axios.get("api/categories/"+this.currentMovement.type, { "headers": { "Authorization": 'Bearer '.concat(this.$store.state.token) } }).then(response => {
        this.currentCategories = response.data;
      });
    },
    fillChartExpenses(){
      this.dataExpenses = {
        labels: this.catExpenses,
        datasets: [
          {
            label: 'Movements',
            // backgroundColor: this.getRandomColor(),
            // borderColor: 'lightblue',
            // pointBackgroundColor: 'blue',
            // borderWidth: 1,
            // pointBorderColor: 'blue',
            data: this.countExpenses
          }
        ]
      }
    },
    getChartExpenses(){
      axios.get("api/graph/movements/movementsExpense/"+this.$store.state.user.id+"?days=30", { "headers": { "Authorization": 'Bearer '.concat(this.$store.state.token) } }).then(response => {
          var results = response.data;
          var mov = [];
          var cat = [];
          for (var i = response.data.length-1; i >= 0 ; i--) {
              mov.push(response.data[i].movements);
              cat.push(response.data[i].category);
          }
          this.countExpenses = mov;
          this.catExpenses = cat;
          this.fillChartExpenses();
      });
    },
    fillChartIncomes(){
      this.dataIncomes = {
        labels: this.catIncomes,
        datasets: [
          {
            label: 'Movements',
            // backgroundColor: this.getRandomColor(),
            // borderColor: 'lightblue',
            // pointBackgroundColor: 'blue',
            // borderWidth: 1,
            // pointBorderColor: 'blue',
            data: this.countIncomes
          }
        ]
      }
    },
    getChartIncomes(){
      axios.get("api/graph/movements/movementsIncome/"+this.$store.state.user.id+"?days=30", { "headers": { "Authorization": 'Bearer '.concat(this.$store.state.token) } }).then(response => {
          var results = response.data;
          var mov = [];
          var cat = [];
          for (var i = response.data.length-1; i >= 0 ; i--) {
              mov.push(response.data[i].movements);
              cat.push(response.data[i].category);
          }
          this.countIncomes = mov;
          this.catIncomes = cat;
          this.fillChartIncomes();
      });
    },
    fillChartIncExp(){
      this.dataIncExp = {
        labels: this.timeIncExp,
        datasets: [
          {
            label: 'Income',
            // backgroundColor: this.getRandomColor(),
            // borderColor: 'lightblue',
            // pointBackgroundColor: 'blue',
            // borderWidth: 1,
            // pointBorderColor: 'blue',
            data: this.incIncExp
          },
          {
            label: 'Expense',
            backgroundColor: 'rgba(0 ,0, 255, 0.2)',
            borderColor: 'lightblue',
            pointBackgroundColor: 'blue',
            borderWidth: 1,
            pointBorderColor: 'blue',
            data: this.expIncExp
          }
        ]
      }
    },
    getChartIncExp(){
      axios.get("api/graph/movements/IncVSExp/"+this.$store.state.user.id, { "headers": { "Authorization": 'Bearer '.concat(this.$store.state.token) } }).then(response => {
          console.log(response.data)
          var results = response.data;
          var inc = [];
          var exp = [];
          var tim = [];
          for (var i = response.data.length-1; i >= 0 ; i--) {
              inc.push(response.data[i].income);
              exp.push(response.data[i].expense);
              tim.push(response.data[i].time);
          }
          this.incIncExp = inc;
          this.expIncExp = exp;
          this.timeIncExp = tim;
          this.fillChartIncExp();
      });
    }
  },
  components: {
    "movement-list": MovementList,
    "movement-edit": MovementEdit,
    PieChart,
    LineChart
  },
  async mounted() {
    this.getBalance();
    this.fillChartExpenses();
    await this.getChartExpenses();
    this.fillChartIncomes();
    await this.getChartIncomes();
    this.fillChartIncExp();
    await this.getChartIncExp();
  }
};
</script>

<style scoped>
</style>
