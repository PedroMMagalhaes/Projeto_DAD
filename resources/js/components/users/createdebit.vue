<template>
  <div>
    <navbar />
    <div v-bind:class="[alert ,alerttype]" v-if="showSuccess">
      <button type="button" class="close-btn" v-on:click="showSuccess=false">&times;</button>
      <strong>{{ Notification }}</strong>
    </div>
    <div class="jumbotron">
      <h2>Create Movement</h2>
      <div class="form-group">
        <label for="inputName">Description</label>
        <input
          type="text"
          class="form-control"
          v-model.trim="description"
          @change="$v.description.$touch()"
          name="description"
          id="inputdescription"
          placeholder="Description"
        />
        <template v-if="$v.description.$dirty">
          <p v-if="!$v.description.required">Field required</p>
        </template>
      </div>

      <div class="form-group">
        <label for="inputValue">Value</label>
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
          <p v-if="!$v.value.decimal">Only numbers are allowed (From 0,01 to 5000,00)</p>
          <p v-if="!$v.value.between">Only numbers are allowed (From 0,01 to 5000,00)</p>
          <!--<p v-if="!$v.nif.required">Field NIF is required</p> -->
        </template>

        <div class="form-group">
          <br />
          <br />
          <label for="inputType">Movement Type</label>
          <select v-model="type" class="form-control">
            <option value="int">Internal Transfer</option>
            <option value="bt">Bank Transfer</option>
            <option value="mb">MB Payment</option>
          </select>

          <br />
          <br />
        </div>

        <div v-if="type == 'int'" class="form-group">
          <label for="inputName">Email</label>
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
            <p v-if="!$v.email.required">Field required</p>
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
            placeholder="IBAN"
          />
          <template v-if="$v.iban.$dirty">
            <p v-if="!$v.iban.required">Field required</p>
            <p v-if="!$v.iban.minlength">25 Digits</p>
            <p v-if="!$v.iban.maxlength">25 Digits</p>
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
            placeholder="MB Entity"
          />
          <template v-if="$v.mbEnt.$dirty">
            <p v-if="!$v.mbEnt.required">Field required</p>
            <p v-if="!$v.mbEnt.numeric">Numbers Only</p>
            <p v-if="!$v.mbEnt.minlength">5 Digits</p>
            <p v-if="!$v.mbEnt.maxlength">5 Digits</p>
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
            placeholder="MB Reference"
          />
          <template v-if="$v.mbRef.$dirty">
            <p v-if="!$v.mbRef.required">Field Required</p>
            <p v-if="!$v.mbRef.numeric">Numbers Only</p>
            <p v-if="!$v.mbRef.minlength">9 Digits</p>
            <p v-if="!$v.mbRef.maxlength">9 Digits</p>
          </template>
        </div>
      </div>
      <a class="btn btn-sm btn-primary" v-on:click.prevent="createUser()">Create</a>
      <a class="btn btn-sm btn-primary" v-on:click.prevent="clear()">Clear</a>
    </div>
  </div>
</template>

<script type="text/javascript">
import {
  required,
  email,
  minLength,
  maxLength,
  sameAs,
  numeric,
  decimal,
  between,
  text
} from "vuelidate/lib/validators";
import moment from "moment";

export default {
  props: ["user"],
  validations: {
    email: { required, email },
    value: { decimal, between: between(0.01, 5000) },
    decimal: [2, '.'], min_value: 0, max_value: 5000,
    description: {required},
    iban: { required, minlength: minLength(25), maxlength: maxLength(25) },
    sourcedescription: {required},
    mbRef: { required, numeric, minlength: minLength(9), maxlength: maxLength(9) },
    mbEnt: { required, numeric, minlength: minLength(5), maxlength: maxLength(5) }
  },

  data: function() {
    return {
      email: "",
      value: "",
      type: "",
      description: "",
      mbEnt: "",
      mbRef: "",
      iban: "",
      Notification: "",
      showSuccess: false,
      alert: "alert",
      alerttype: "alert-success"
    };
  },
  methods: {
    clear: function() {
      this.email = "";
      this.value = "";
      this.type = "";
      this.description = "";
      this.iban = "";
      this.sourcedescription = "";
      this.mbEnt = "";
      this.mbRef = "";
    },
    createUser: function() {
      var formData = new FormData();
      formData.append("value", this.value);
      formData.append("description", this.description);
      formData.append("type", this.type);
      if (this.type == "int") {
        formData.append("email", this.email);
        formData.append("sourcedescription", this.description);
      }
      if (this.type == "bt") {
        formData.append("iban", this.iban);
      }
      if (this.type == "mb") {
        formData.append("mbEnt", this.mbEnt);
        formData.append("mbRef", this.mbRef);
      }

      axios
        .post("api/movement/createdebit/" + this.$store.state.user.id, formData)
        .then(response => {
          //console.log(response.data.msg);
          if (response.data.msg == "sucess") {
            this.alerttype = "alert-success";
            this.showSuccess = true;
            this.Notification = "Movement Created";
            //this.clear();
          } else {
            this.alerttype = "alert-danger";
            this.showSuccess = true;
            this.Notification = "Something gone wrong";
            //this.clear();
          }
        });
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
