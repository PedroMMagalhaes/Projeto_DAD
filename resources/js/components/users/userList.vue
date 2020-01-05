<template>
  <div>
    <div class="row">
      <div class="col-md-6">
        <h6>Users</h6>
        <input class="form-control" type="text" align="center" v-model="searchName" placeholder="Search Users" />
      </div>
      <div class="col-md-6">
        <h6>Type</h6>
        <select class="form-control" v-model="searchType">
          <option value="None">Select User Type to Filter</option>
          <option value="Admin">Admin</option>
          <option value="Operator">Operator</option>
          <option value="Platform User">Platform User</option>
        </select>
        <br>
      </div>
    </div>
    <table class="table table-striped">
      <thead>
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
    </table>
    <pagination v-if="!filtering" :data="users" :limit=3 @pagination-change-page="getUsers" align="left">
      <span slot="prev-nav">Previous</span>
      <span slot="next-nav">Next</span>
    </pagination>
  </div>
</template>

<script type="text/javascript">
// Component code (not registered)
export default {
  // props: ["users"],
  data: function() {
    return {
      editingUser: null,
      selected: ["Apache", "Cochise"],
      selected: false,
      users: {},
      type:"",
      searchName: "",
      searchType:"",
      regExFilterName: "",
      regExFilterType: "",
      filtering: false,
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

    getUsers: function(page) {
      //page=1
      axios
        .get("api/users?page=" + page)
        .then(({ data }) => ( this.usersAUX = this.users = data ));
    },

    getUsersFiltered: function() {
      axios.get("api/users").then(({ data }) => (this.usersFilter = data));
    },

    filterData: function(){
      var usersAUX = this.usersFilter.data;
      if (this.regExFilterName === "" && this.regExFilterType === ""){
        this.filtering = false;
        this.getUsers();
        this.getUsersFiltered();
      }
      else
      {
        this.filtering = true;
        if (this.regExFilterName != "") {
          usersAUX = usersAUX.filter(user => {
            return user.name.match(this.regExFilterName);
          });
        }
        if (this.regExFilterType != "") {
          usersAUX = usersAUX.filter(user => {
            return user.type.match(this.regExFilterType);
          });
        }
      }

      this.users.data = usersAUX;
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
    searchName: function(val) {
      this.regExFilterName = new RegExp(".*" + val + ".*", "i");
      if (val == "") {
         this.regExFilterName = "";
      }
      this.filterData();
    },

    searchType: function(val) {
      this.regExFilterType = new RegExp(".*" + val + ".*", "i");
      if (val == "" || val == "None") {
         this.regExFilterType = "";
      }
      this.filterData();
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
