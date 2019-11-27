<template>
  <div class="jumbotron">
    <h2>Edit User</h2>
    <div class="form-group">
      <label for="inputName">Name</label>
      <input
        type="text"
        class="form-control"
        v-model="user.name"
        name="name"
        id="inputName"
        placeholder="Fullname"
      />
    </div>
    <div class="form-group">
      <label for="inputEmail">Email</label>
      <input
        type="email"
        class="form-control"
        v-model="user.email"
        name="email"
        id="inputEmail"
        placeholder="Email address"
      />
    </div>

    <div class="widget-user-image">
      <img class="img-circle" :src="getProfilePhoto()" alt="User Avatar" />
    </div>

    <div class="form-group">
      <label for="photo" class="col-sm-2 control-label">Profile Photo</label>
      <div class="col-sm-12">
        <input type="file" @change="updateProfile" name="photo" class="form-input" />
      </div>
    </div>
    <!-- <div class="form-group">
	        <label for="inputActive">Active</label>
	        <input
	            type="number" class="form-control" v-model="user.active"
	            name="active" id="inputActive"
	            placeholder="Active"/>
	    </div>
		<div class="form-group">
	        <label for="inputType">Type</label>
	        <input
	            type="text" class="form-control" v-model="user.type"
	            name="type" id="inputType"
	            placeholder="Type"/>
    </div>-->

    <div class="form-group">
      <label for="inputNif">Nif</label>
      <input
        type="number"
        class="form-control"
        v-model="user.nif"
        name="nif"
        id="inputNif"
        placeholder="999999999"
      />
    </div>

    <div class="form-group">
      <a class="btn btn-primary" v-on:click.prevent="saveUser()">Save</a>
      <a class="btn btn-light" v-on:click.prevent="cancelEdit()">Cancel</a>
    </div>
  </div>
</template>

<script type="text/javascript">
export default {
  props: ["user"],
  methods: {
    saveUser: function() {
      axios.put("api/users/" + this.user.id, this.user).then(response => {
        // Copy object properties from response.data.data to this.user
        // without creating a new reference
        Object.assign(this.user, response.data.data);
        this.$emit("user-saved", this.user);
      });
    },
    cancelEdit: function() {
      axios.get("api/users/" + this.user.id).then(response => {
        // Copy object properties from response.data.data to this.user
        // without creating a new reference
        Object.assign(this.user, response.data.data);
        this.$emit("user-canceled", this.user);
      });
    },
    //I CREATE A SYMBOLIC LINK TO PUBLIC/STORAGE , NOW WE CAN ACESS DATA FROM THAT FOLDER
    getProfilePhoto: function() {
      return "/storage/fotos/" + this.user.photo;
    },

    //method to save foto uploadead - TEST ***
    updateProfile(e) {
      let file = e.target.files[0];
      let reader = new FileReader();
      let limit = 1024 * 1024 * 2;
      if (file["size"] > limit) {
        console.log("File to Large");
        return false;
      }
      reader.onloadend = file => {
        this.user.photo = reader.result;
      };
      reader.readAsDataURL(file);
    }
  }
};
</script>
<style scoped>
</style>
