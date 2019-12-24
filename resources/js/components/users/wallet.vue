<template>
  <div>
    <div class="jumbotron">
      <h1>{{ title }}</h1><br><br>

      <h3>Balance: {{ currentBalance }}<strong></strong></h3>
    </div>
    <user-list
      :users="movements"
      @edit-click="editUser"
      @delete-click="deleteUser"
      @message="childMessage"
      ref="usersListRef"
    ></user-list>
    <div class="alert alert-success" v-if="showSuccess">
      <button type="button" class="close-btn" v-on:click="showSuccess=false">&times;</button>
      <strong>{{ successMessage }}</strong>
    </div>
    <user-edit
      :user="currentUser"
      @user-saved="savedUser"
      @user-canceled="cancelEdit"
      v-if="currentUser"
    ></user-edit>
  </div>
</template>
<script type="text/javascript">
import UserList from "./userList.vue";
import UserEdit from "./userEdit.vue";

export default {
  data: function() {
    return {
      title: this.$store.state.user.name + " Wallet",
      showSuccess: false,
      successMessage: "",
      currentUser: null,
      loggedUser: this.$store.state.user,
      currentBalance: 0,
      currentMovement: null,
      users: []
    };
  },
  methods: {
    editUser: function(user) {
      this.currentUser = user;
      this.showSuccess = false;
    },
    deleteUser: function(user) {
      axios.delete("api/users/" + user.id).then(response => {
        this.showSuccess = true;
        this.successMessage = "User Deleted";
        this.getUsers();
      });
    },
    savedUser: function() {
      this.currentUser = null;
      this.$refs.usersListRef.editingUser = null;
      this.showSuccess = true;
      this.successMessage = "User Saved";
    },
    cancelEdit: function() {
      this.currentUser = null;
      this.$refs.usersListRef.editingUser = null;
      this.showSuccess = false;
    },
    getMovements: function() {
      axios.get("api/movements/"+this.$store.state.user.id, { "headers": { "Authorization": 'Bearer '.concat(this.$store.state.token) } }).then(response => {
        console.log(response.data.data);
        //this.currentBalance = response.data.data.balance;
      });
    },
    getBalance: function() {
      axios.get("api/wallets/"+this.$store.state.user.id, { "headers": { "Authorization": 'Bearer '.concat(this.$store.state.token) } }).then(response => {
        //console.log(response.data.data.balance);
        this.currentBalance = response.data.data.balance;
      });
    },
    childMessage: function(message) {
      this.showSuccess = true;
      this.successMessage = message;
    }
  },
  components: {
    "user-list": UserList,
    "user-edit": UserEdit
  },
  mounted() {
    //console.log(this.$store.state.token);
    this.getBalance()
    this.getMovements();
  }
};
</script>

<style scoped>
</style>
