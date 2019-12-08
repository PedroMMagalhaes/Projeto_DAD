<template>
  <table class="table table-striped">
    <thead>
      <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Type</th>
        <th>Nif</th>
        <th>Photo</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="user in users" :key="user.id" :class="{activerow: editingUser === user}">
        <td>{{ user.name }}</td>
        <td>{{ user.email }}</td>
        <td>{{ user.type}}</td>
        <td>{{ user.nif }}</td>
        <td class="widget-user-image">
          <img class="img-circle" :src="getProfilePhoto(user.photo)" width="40" height="40" alt="User Avatar" />
        </td>
        <td>
          <a class="btn btn-sm btn-primary" v-on:click.prevent="editUser(user)">Edit</a>
          <a class="btn btn-sm btn-danger" v-on:click.prevent="deleteUser(user)">Delete</a>
        </td>
      </tr>
    </tbody>
  </table>
</template>

<script type="text/javascript">
// Component code (not registered)
export default {
  props: ["users"],
  data: function() {
    return {
      editingUser: null
    };
  },
  methods: {
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

    getStringRole(string){
      if (string == "a"){
        return "Admin"
      }
      if(string == "o"){
        return "Operator"
      }
      if(string == "u"){
        return "Platform User"
      }
    }
  }
};
</script>

<style scoped>
tr.activerow {
  background: #123456 !important;
  color: #fff !important;
}
</style>
