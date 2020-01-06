<template>
    <div>
      <navbar/>
      <div v-bind:class="[alert ,alerttype]" v-if="showSuccess">             
            <button type="button" class="close-btn" v-on:click="showSuccess=false">&times;</button>
            <strong>{{ Notification }}</strong>
        </div>
       <div class="jumbotron">
       
        <h2>Create Movement</h2>
        <div class="form-group">
          <label for="inputName">email</label>
          <input
            type="text"
            class="form-control"
            v-model.trim="email"
            @change="$v.email.$touch()"
            name="email"
            id="inputemail"
            placeholder="Email"
            />
          <template v-if="$v.email.$dirty">
            <p v-if="!$v.email.required">Field name required</p>
          </template>
          </div>
          <div class="form-group">
      <label for="inputNif">Value</label>
      <input
        type="number"
        @change="$v.value.$touch()"
        class="form-control"
        v-model.trim="value"
        name="value"
        id="inputValue"
        placeholder="Value"
      />
      <template v-if="$v.value.$dirty">
        <p v-if="!$v.value.numeric">Only numbers are allowed</p>
        <!--<p v-if="!$v.nif.required">Field NIF is required</p> -->
      </template>

      <div >
      <br />
      <br />
      <label for="inputType">Movement Type</label>
      <select v-model="type">
        
        <option value="">cash</option>
        <option value="bt">bank transfer</option>
      </select>
      <!--<span>User Type Selected: {{ type }}</span> -->
      <br />
      <br />
    </div>
    <div v-if="type == 'bt'" class="form-group">
          <label for="inputName">description</label>
          <input
            type="text"
            class="form-control"
            v-model.trim="description"
            @change="$v.description.$touch()"
            name="description"
            id="inputdescription"
            placeholder="description"
            />
          <template v-if="$v.description.$dirty">
            <p v-if="!$v.description.required">Field required</p>
          </template>
          </div>
          

          <div v-if="type == 'bt'" class="form-group">
          <label for="inputName">IBAN</label>
          <input
            type="text"
            class="form-control"
            v-model.trim="iban"
            @change="$v.iban.$touch()"
            name="iban"
            id="inputiban"
            placeholder="iban"
            />
          <template v-if="$v.iban.$dirty">
            <p v-if="!$v.iban.check">Field data incompatible</p>
          </template>
          </div>

    </div>
       <a class="btn btn-light" v-on:click.prevent="createUser()">Create</a>
       <a class="btn btn-light" v-on:click.prevent="clear()">Clear</a>
       </div>
       </div>
</template>

<script type="text/javascript">
import {
  required,
  email,
  minLength,
  sameAs,
  numeric,
  text
} from "vuelidate/lib/validators";
import moment from "moment";
const ibanregex = /^[A-Z]{2}(?:[ ]?[0-9]){18,20}$/g;

export default {validations: {
    email: { required, email },
    value: {  numeric },
    description: {  },
    iban: { check(iban){
        return (
        ibanregex.test(iban)
        );
      } },
  },

  data: function() {
    return {
      email: "",
      value: "",
      type: "",
      description: "",
      iban: "",
      Notification: "",
      showSuccess: false,
      alert:"alert",
      alerttype:"alert-success",
    };
  },
  methods: {
    clear: function(){
      this.email = "";
      this.value = "";
      this.type = "";
      this.description = "";
      this.iban = "";
    },
    createUser: function() {
      var formData = new FormData();
      formData.append("email", this.email);
      formData.append("value", this.value);
      formData.append("type", this.type);
      if(this.type == "bt"){
      formData.append("description", this.description);
      formData.append("iban", this.iban);
      }
      axios.post("api/movement/createcredit",formData).then(response => {
        console.log(response.data.msg);
        if(response.data.msg == 'sucess'){
          this.alerttype ="alert-success";
          this.showSuccess = true;
          this.Notification = "Movement Created"
          this.clear();
        }else{
          this.alerttype ="alert-danger";
          this.showSuccess = true;
          this.Notification = "Something gone wrong"
           this.clear();
        }
      });
    },
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
