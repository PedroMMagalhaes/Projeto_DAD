<template>
  <div class="jumbotron">
    <h2>Create User</h2>
    <div class="form-group">
      <label for="inputName">Name</label>
      <input
        type="text"
        class="form-control"
        v-model.trim="name"
        @change="$v.name.$touch()"
        name="name"
        id="inputName"
        placeholder="Fullname"
      />
      <template v-if="$v.name.$dirty">
        <p v-if="!$v.name.required">Field name required</p>
      </template>
    </div>
    <div class="form-group">
      <label for="inputEmail">Email</label>
      <input
        type="email"
        @change="$v.email.$touch()"
        class="form-control"
        v-model.trim="email"
        name="email"
        id="inputEmail"
        placeholder="Email address"
      />
      <template v-if="$v.email.$dirty">
        <p v-if="!$v.email.email">Email not Valid</p>
        <p v-if="!$v.email.required">Field Email required</p>
      </template>
    </div>
    <div class="form-group">
      <label for="inputPassword">Password</label>
      <input
        type="password"
        @change="$v.password.$touch()"
        class="form-control"
        v-model.trim="password"
        name="password"
        id="inputPassword"
        placeholder="Password"
      />
      <template v-if="$v.password.$dirty">
        <p v-if="!$v.password.minlength">Password need at least 3 characters</p>
        <p v-if="!$v.password.required">Field Password required</p>
      </template>
    </div>

    <div class="form-group">
      <label for="inputConfirmPassword">Password Confirmation</label>
      <input
        type="password"
        @change="$v.password_confirmation.$touch()"
        class="form-control"
        v-model.trim="password_confirmation"
        name="password"
        id="inputConfirmPassword"
        placeholder="Password Confirmation"
      />
      <template v-if="$v.password_confirmation.$dirty">
        <p v-if="!$v.password_confirmation.sameAs">Password's does not match</p>
        <p v-if="!$v.password_confirmation.required">Field Password confirmation required</p>
      </template>
    </div>

    <!--<div class="form-group">
      <label for="inputActive">Active</label>
      <input
        type="number"
        class="form-control"
        v-model="user.active"
        name="active"
        id="inputActive"
        placeholder="Active"
      />
    </div>-->

    <!-- Type User - Just for Admin Creation -->
    <div v-if="isAdmin()">
      <select v-model="type">
        <option disabled value>Please select one</option>
        <option value="a">Admin</option>
        <option value="o">Operator</option>
      </select>
      <span>Selected: {{ type }}</span>
      <br/>
       <br/>
    </div>

    <div class="form-group">
      <label for="inputNif">Nif</label>
      <input
        type="number"
        @change="$v.nif.$touch()"
        class="form-control"
        v-model.trim="nif"
        name="nif"
        id="inputNif"
        placeholder="99999999"
      />
      <template v-if="$v.nif.$dirty">
        <p v-if="!$v.nif.numeric">Only numbers are allowed</p>
        <!--<p v-if="!$v.nif.required">Field NIF is required</p> -->
      </template>
    </div>

    <div class="form-group">
      <label for="photo" class="col-sm-2 control-label">Profile Photo</label>
      <div class="col-sm-12">
        <input type="file" @change="uploadPicture" name="photo" class="form-input" />
      </div>
    </div>

    <div class="form-group">
      <a class="btn btn-primary" v-on:click.prevent="createUser()">Save</a>
      <a class="btn btn-light" v-on:click.prevent="cancelCreate()">Cancel</a>
    </div>
  </div>
</template>

<script type="text/javascript">
import {
  required,
  email,
  minLength,
  sameAs,
  numeric
} from "vuelidate/lib/validators";
import moment from "moment";

export default {
  //create USER - NAO USAR PROPS NAO E NECESSARIO PASSAR DADOS
  // props: ["user"],
  //VALIDATIONS
  validations: {
    name: { required },
    email: { required, email },
    password: { required, minlength: minLength(3) },
    password_confirmation: { required, sameAs: sameAs("password") },
    nif: { numeric }
  },

  data: function() {
    return {
      photoFile: null,
      name: "",
      email: "",
      password: "",
      password_confirmation: "",
      nif: "",
      active: "",
      user: {}, //USER
      type: "",
      
    };
  },
  methods: {
    isAdmin: function() {
      return this.$store.state.user.type === "Admin";
    },
    createUser: function() {
      //this.createdAtDisplay();

      console.log(this.user);
      console.log(this.nif);
      var formData = new FormData();
      this.user.nif = this.nif;
      this.user.name = this.name;
      this.user.type = 1;
      this.user.email = this.email;
      this.user.password = this.password;
      this.user.type = this.type;

      console.log(this.user.nif);
      //formData.append('user',this.user);
      formData.append("name", this.user.name);
      formData.append("email", this.user.email);
      formData.append("password", this.user.password);
      formData.append("nif", this.user.nif);
      formData.append("active", this.user.active);
      formData.append("type", this.user.type);

      formData.append("photoFile", this.photoFile);
      axios.post("api/users", formData).then(response => {
        console.log(response.data);
        // Copy object properties from response.data.data to this.user
        // without creating a new reference
        Object.assign(this.user, response.data.data);
        this.$emit("user-create", this.user);
      });
    },
    cancelCreate: function() {
      axios.get("api/users/" + this.user.id).then(response => {
        // Copy object properties from response.data.data to this.user
        // without creating a new reference
        Object.assign(this.user, response.data.data);
        this.$emit("user-canceled", this.user);
      });
    },

    createdAtDisplay() {
      console.log(moment().format("YYYY-MM-DD h:mm "));
      this.user.created_at = moment().format("YYYY-MM-DD h:mm");
      //return moment().format('YYYY-MM-DD h:mm A');
    },

    uploadPicture: function(event) {
      var input = event.target;
      if (input.files && input.files) {
        this.photoFile = input.files[0];
      }
    }
  },
  mounted() {
    //this.createdAtDisplay();
  }
};
</script>
<style scoped>
p {
  color: red;
}
</style>
