<template>
  <div>
    <navbar/>
    <div class="jumbotron">
      <h1>{{ title }}</h1><br><br>

      <h3>Balance: {{ currentBalance }}<strong></strong></h3>
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
    }
  },
  components: {
    "movement-list": MovementList,
    "movement-edit": MovementEdit
  },
  mounted() {
    this.getBalance()
  }
};
</script>

<style scoped>
</style>
