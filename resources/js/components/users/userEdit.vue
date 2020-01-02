<template>
  <div>
    <div class="UserEdit">
      <pre></pre>
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
       <!-- <div class="form-group">
          <label for="inputEmail">Email</label>
          <input
            type="email"
            class="form-control"
            v-model="user.email"
            name="email"
            id="inputEmail"
            placeholder="Email address"
          />
        </div> -->

        <div class="form-group">
          <label for="inputPasswordAtual">Password Atual</label>
          <input
            type="password"
            class="form-control"
            v-model.trim="user.passwordAtual"
            name="passwordAtual"
            id="inputPasswordAtual"
            placeholder="Password Atual"
          />
        </div>

        <div class="form-group">
          <label for="inputPassword">Password</label>
          <input
            type="password"
            class="form-control"
            v-model="user.password"
            name="password"
            id="inputPassword"
            placeholder="Password"
          />
        </div>

        <div class="form-group">
          <label for="inputPasswordConfirmation">Password Confirmation</label>
          <input
            type="password"
            class="form-control"
            v-model="password_confirmation"
            name="password_confirmation"
            id="inputPasswordConfirmation"
            placeholder="Password Confirmation"
          />
        </div>

        <div class="widget-user-image">
          <img
            class="img-circle"
            width="250"
            height="250"
            :src="getProfilePhoto()"
            alt="User Avatar"
          />
        </div>

        <div class="form-group">
          <label for="photo" class="col-sm-2 control-label">Profile Photo</label>
          <div class="col-sm-12">
            <input type="file" @change="uploadPicture" name="photo" class="form-input" />
          </div>
        </div>

        <div v-if="isPlatformUser()" class="form-group">
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
    </div>
  </div>
</template>

<script type="text/javascript">
import { required } from "vuelidate/lib/validators";
import { platform } from "os";
export default {
  props: ["user"],
  //Validations -> User entries
  validations: {
    name: { required },
    password: { required }
  },
  data: function() {
    return {
      photoFile: null,
      name: "",
      email: "",
      password: "",
      password_confirmation: "",
      passwordAtual: "",
      nif: "",
      active: "",
      editingUser: this.user,
      platformUser: false
    };
  },
  methods: {
    isPlatformUser: function() {
      if (this.user.type === "Platform") {
        platformUser = true;
      }
    },
    saveUser: function() {
      //this.editingUser.type = "u";
      //console.log(this.photoFile);
      
      //editing user aqui  tem a photo atual
      //if (this.photoFile != undefined || this.photoFile != null) {
      var formData = new FormData();
      formData.append("file", this.photoFile);
      axios.post("/api/users/me/photo", formData)
      .then(response => {
        if(!(response.data =="No file input")){
          this.user.photo = response.data;
        }
        
        console.log(this.user.photo);
        axios
          .put("/api/users/me/" + this.user.id, this.user)
          .then(response => {
            this.$store.commit("setUser", response.data.data);
            this.$emit("user-saved", this.user);
          })
          .catch(error => {
            console.log("error update");
          });
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
    uploadPicture: function(event) {
      //console.log("OK");
      var input = event.target;
      if (input.files && input.files) {
        this.photoFile = input.files[0];
      }
    }
  },
  mounted() {
    this.$nextTick(function() {
      this.editingUser = this.user;
    });
  }
};
</script>
<style scoped>
</style>
