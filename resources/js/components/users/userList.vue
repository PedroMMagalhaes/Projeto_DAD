<template>
  <table class="table table-striped">
    <thead>
      <input type="text" align="center" v-model="search" placeholder="Search Users" />
      <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Type</th>
        <th>Nif</th>
        <th>Active</th>
        <th>Photo</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="user in users.data" :key="user.id" :class="{activerow: editingUser === user}">
        <td>{{ user.name }}</td>
        <td>{{ user.email }}</td>
        <td>{{ user.type}}</td>
        <td>{{ user.nif }}</td>
        <td>
          <input type="checkbox" :checked="user.active ? 'checked':'' " @change="!user.active" />
        </td>
        <td class="widget-user-image">
          <img
            class="img-circle"
            stye="border-radius: 50%"
            :src="getProfilePhoto(user.photo)"
            width="40"
            height="40"
            alt="User Avatar"
          />
        </td>
        <td>
          <a class="btn btn-sm btn-primary" v-on:click.prevent="editUser(user)">Edit</a>
          <a class="btn btn-sm btn-danger" v-on:click.prevent="deleteUser(user)">Delete</a>
        </td>
      </tr>
    </tbody>
    <pagination :data="users" @pagination-change-page="getUsers" align="center">
      <span slot="prev-nav">Previous</span>
      <span slot="next-nav">Next</span>
    </pagination>
  </table>
</template>

<script type="text/javascript">
// Component code (not registered)
export default {
  // props: ["users"],
  data: function() {
    return {
      editingUser: null,
      selected: false,
      users: {},
      search: "",
      usersFilter: {}
    };
  },
  methods: {
    //getResults(page = 1) {
    //axios.get("api/users?page=" + page).then(response => {
    //this.users = response.data;
    //});
    //},
    editUser: function(user) {
      this.editingUser = user;
      this.$emit("edit-click", user);
    },
    deleteUser: function(user) {
      this.editingUser = null;
      this.$emit("delete-click", user);
    },

    getProfilePhoto: function(name) {
      return "/storage/fotos/" + name;
    },

    getUsers: function(page) { //page=1
      axios
        .get("api/users?page=" + page)
        .then(({ data }) => (this.users = data));
    },

    getUsersFiltered: function(){
       axios
        .get("api/users")
        .then(({ data }) => (this.usersFilter = data));
    },

    check: function() {
      console.log(this.user.active);
    }
  },
  mounted() {
    this.getUsers();
    this.getUsersFiltered();
  },

  watch: {
    search: function(val) {
      var regExFilter = new RegExp('.*'+val+'.*', 'i');
      //console.log("OK");
      if(val==""){
        //console.log('Teste');
        this.getUsers();
      }else
      this.users.data=this.usersFilter.data.filter(user => {
        return user.name.match(regExFilter);
      });
    },
    filter: function() {
      //var result = Object.keys(this.users)
      //.map(user => users[key]) // turn an array of keys into array of items.
      //.filter(user =>  user.name.match(this.search)) // filter that array,
      //console.log(result);
    }
  }
};
</script>

<style scoped>
tr.activerow {
  background: #123456 !important;
  color: #fff !important;
}

.img-circle {
  border-radius: 50%;
}
</style>
