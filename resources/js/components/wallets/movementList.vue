<template>
  <table class="table table-striped">
    <thead>
      <tr>
        <th>ID</th>
        <th>Type</th>
        <th>Transfer Email</th>
        <th>Type</th>
        <th>Category</th>
        <th>Date</th>
        <th>Value</th>
        <th>Start Balance</th>
        <th>End Balance</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="movement in movements" :key="movement.id" :class="{activerow: editingMovement === movement}">
        <td>{{ movement.id }}</td>
        <td>{{ movement.typeString }}</td>
        <td>{{ movement.transferEmail }}</td>
        <td>{{ movement.type_paymentString}}</td>
        <td>{{ movement.category_name }}</td>
        <td>{{ movement.date }}</td>
        <td>{{ movement.value }}</td>
        <td>{{ movement.start_balance }}</td>
        <td>{{ movement.end_balance }}</td>
        <td>
          <a class="btn btn-sm btn-primary" v-on:click.prevent="editMovement(movement)">Edit</a>
        </td>
      </tr>
    </tbody>
  </table>
</template>

<script type="text/javascript">
// Component code (not registered)
export default {
  props: ["movements"],
  data: function() {
    return {
      editingMovement: null
    };
  },
  methods: {
    editMovement: function(movement) {
      this.editingMovement = movement;
      this.$emit("edit-click", movement);
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
