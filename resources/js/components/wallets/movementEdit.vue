<template>
  <div class="MovementEdit">
    <pre>
<!--{{ $v.name }} -->
</pre>
    <div class="jumbotron">
      <h2>Edit Movement {{movement.id}}</h2>
      <br />
      <div class="form-group">
        <div v-if="movement.source_description">
          <label for="inputDescription">Source Description</label>
          <input
            type="text"
            class="form-control"
            v-model="movement.source_description"
            name="description"
            id="inputSourceDescription"
            placeholder="Description"
            readonly
          />
          <br />
        </div>
        <label for="inputDescription">Description</label>
        <input
          type="text"
          class="form-control"
          v-model="movement.description"
          name="description"
          id="inputDescription"
          placeholder="Description"
        />
        <br />

        <label for="inputDescription">Category</label>
        <select class="form-control" v-model="movement.category_id" v-if="categories">
          <option disabled value>Select Movement Category</option>
          <option v-for = "category in categories" :value="category.id">{{category.name}}</option>
        </select>
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
export default {
  props: ["movement", "categories"],
  data: function() {
    return {
      id: this.movement.id,
      category_id: this.movement.category_id,
      category_name: this.movement.category_name
    };
  },
  methods: {
    saveMovement: function() {
      console.log(this.movement);
      axios
          .put("/api/movement/me/" + this.movement.id, this.movement, { "headers": { "Authorization": 'Bearer '.concat(this.$store.state.token) } })
          .then(response => {
            console.log(response.data.data);
            this.movement.category_name = response.data.data.category_name;
            this.$emit("movement-saved", this.movement);
          })
          .catch(error => {
            console.log("error update");
          });
    },
    cancelEdit: function() {
      axios.get("api/movement/" + this.movement.id, { "headers": { "Authorization": 'Bearer '.concat(this.$store.state.token) } }).then(response => {
        Object.assign(this.movement, response.data.data);
        this.$emit("movement-canceled", this.movement);
      });
    },
  }
};
</script>
<style scoped>
</style>
