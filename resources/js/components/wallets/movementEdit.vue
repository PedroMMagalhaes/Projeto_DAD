<template>
  <div class="MovementEdit">
    <pre>
<!--{{ $v.name }} -->
</pre>
    <div class="jumbotron">
      <h2>Edit Movement</h2>
      <div class="form-group">
        <label for="inputDescription">Description</label>
        <input
          type="text"
          class="form-control"
          v-model="movement.description"
          name="description"
          id="inputDescription"
          placeholder="Description"
        />
        <!-- <select v-model="movement.category_id">
          <option disabled value>Select Movement Category</option>
          <option value="a">Admin</option>
          <option value="o">Operator</option>
        </select> -->

        <!-- <select v-model="formVariables.country_id">
          <option disabled value>Select Movement Category</option>
          <option v-for = "category in categories" :value="category.category_id" :key="category">{{category.category_name}}</option>
        </select> -->
        <br />
        <br />
      </div> 

      <div class="form-group">
        <a class="btn btn-primary" v-on:click.prevent="saveMovement()">Save</a>
        <a class="btn btn-light" v-on:click.prevent="cancelEdit()">Cancel</a>
      </div>
    </div>
  </div>
</template>

<script type="text/javascript">
import { required } from "vuelidate/lib/validators";
export default {
  props: ["movement"],
  //Validations -> Movement entries
  // validations: {
  //   name: { required },
  //   password: { required }
  // },
  data: function() {
    return {
      id: null,
      type: "",
      typeString: "",
      description: "",
      category_id: 0,
      category_name: "",
      editingMovement: this.movement,
      categories: []
    };
  },
  methods: {
    saveMovement: function() {
      axios
        .put("/api/movements/me", this.movement, { "headers": { "Authorization": 'Bearer '.concat(this.$store.state.token) } })
        .then(response => {
          this.$emit("movement-saved", this.movement);
        })
        .catch(error => {
          console.log("error update");
        });
    },
    cancelEdit: function() {
      axios.get("api/movements/" + this.movement.id, { "headers": { "Authorization": 'Bearer '.concat(this.$store.state.token) } }).then(response => {
        Object.assign(this.movement, response.data.data);
        this.$emit("movement-canceled", this.movement);
      });
    },
    getCategories: function() {
      axios.get("api/categories/"+this.editingMovement.type, { "headers": { "Authorization": 'Bearer '.concat(this.$store.state.token) } }).then(response => {
        console.log(response.data.data);
        this.categories = response.data.data;
      });
      // console.log(this.categories);
    },
    mounted() {
      console.log(this.editingMovement);
      this.getCategories();
    }
  }
};
</script>
<style scoped>
</style>
