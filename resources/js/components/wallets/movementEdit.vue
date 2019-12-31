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

        <select v-model="this.category_id">
          <option disabled value>Select Movement Category</option>
          <option v-for = "category in categories" :value="category.id">{{category.name}}</option>
        </select>
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
      id: this.movement.id,
      type: this.movement.type,
      typeString: "",
      description: "",
      category_id: this.movement.category_id,
      category_name: "",
      categories: this.getCategories()
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
    getCategories: function(){
      axios.get("api/categories/"+this.type, { "headers": { "Authorization": 'Bearer '.concat(this.$store.state.token) } }).then(response => {
        console.log(response.data);
        return response.data;
      });
    }
  },
  mounted() {
    //this.getCategories();
  },
  beforeUpdated(){
    console.log(this.movement.id);
    console.log(this.movement.type);
    console.log(this.categories);
  },
  updated(){
    console.log(this.movement.id);
    console.log(this.movement.type);
    console.log(this.categories);
  }
};
</script>
<style scoped>
</style>
