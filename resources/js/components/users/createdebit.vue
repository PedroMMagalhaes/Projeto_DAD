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
        <option value="int">internal transfer</option>
        <option value="bt">bank transfer</option>
        <option value="mb">MB payment</option>
      </select>
    
      <br />
      <br />
    </div>

    <div v-if="type == 'int'" class="form-group">
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

    <div v-if="type == 'int'" class="form-group">
          <label for="inputName">source description</label>
          <input
            type="text"
            class="form-control"
            v-model.trim="sourcedescription"
            @change="$v.sourcedescription.$touch()"
            name="sourcedescription"
            id="inputsourcedescription"
            placeholder="source description"
            />
          <template v-if="$v.sourcedescription.$dirty">
            <p v-if="!$v.sourcedescription.required">Field required</p>
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

          <div v-if="type == 'mb'" class="form-group">
          <label for="inputName">MB Entity</label>
          <input
            type="text"
            class="form-control"
            v-model.trim="mbEnt"
            @change="$v.mbEnt.$touch()"
            name="mbEnt"
            id="inputmbEnt"
            placeholder="mbEnt"
            />
          <template v-if="$v.mbEnt.$dirty">
            <p v-if="!$v.mbEnt.required">Field required</p>
          </template>
          </div>

          <div v-if="type == 'mb'" class="form-group">
          <label for="inputName">MB Reference</label>
          <input
            type="text"
            class="form-control"
            v-model.trim="mbRef"
            @change="$v.mbRef.$touch()"
            name="mbRef"
            id="inputmbRef"
            placeholder="mbRef"
            />
          <template v-if="$v.mbRef.$dirty">
            <p v-if="!$v.mbRef.required">Field required</p>
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

export default { props: ["user"],validations: {
    email: { required, email },
    value: {  numeric },
    description: {  },
    iban: { 
      check(iban){
        return (
        ibanregex.test(iban)
        );
      }
     },
    sourcedescription:{},
    mbRef:{},
    mbEnt:{},
  },

  data: function() {
    return {
      email: "",
      value: "",
      type: "",
      description: "",
      sourcedescription: "",
      mbEnt:"",
      mbRef:"",
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
      this.sourcedescription= "";
      this.mbEnt="";
      this.mbRef="";
    },
    createUser: function() {
      var formData = new FormData();
      formData.append("value", this.value);
      formData.append("description", this.description);
      formData.append("type", this.type);
      if(this.type == 'int'){
        formData.append("email", this.email);
        formData.append("sourcedescription", this.sourcedescription);
      }
      if(this.type == "bt"){
      formData.append("iban", this.iban);
      }
      if(this.type == "mb"){
        formData.append("mbEnt", this.mbEnt);
        formData.append("mbRef", this.mbRef);
      }
      
      axios.post("api/movement/createdebit/"+this.$store.state.user.id,formData).then(response => {
        if(response.data.msg == 'sucess'){
          this.alerttype ="alert-success";
          this.showSuccess = true;
          this.Notification = "Movement Created"
          this.$socket.emit("transfer",{value:this.value},{id: this.$store.state.user.id},{email:this.email});
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
